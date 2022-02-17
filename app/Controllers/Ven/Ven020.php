<?php

namespace App\Controllers\Ven;

use App\Controllers\BaseController;

class Ven020 extends BaseController
{
    private $resposta_upload = ["error" => 0, "message" => "", "rejeitados" => [], "info_card_upload" => []];
    private $campos_mailing = [ //ao alterar, n esquece de alterar a lista no js
        0 =>  'NOME',
        1 =>  'CPF',
        2 =>  'EMAIL',
        3 =>  'CONTATO 1',
        4 =>  'CONTATO 2',
        5 =>  'CONTATO 3',
        6 =>  'CONTATO 4',
        7 =>  'CEP',
        8 =>  'UF',
        9 =>  'CIDADE',
        10 => 'BAIRRO',
        11 => 'LOGRADOURO',
        12 => 'NUM FACHADA'
    ];
    public function __construct()

    {
        $this->titulo = "DFV";
        $this->db = db_connect();
    }
    public function index()
    {
        $data = [
            "arquivo_js" => ['jquery.mask.min'],
            "arquivo_dataTable" => true,
            "option_nome_mailing" => $this->filtros_mailing('nome_mailing'),
            "option_campos" => $this->campos_mailing,
            "option_tot_clientes" => $this->tot_clientes(),
        ];
        $this->load_template($data);
    }

    public function upload_mailing()
    {

        foreach ($this->campos_mailing as $k => $v) $this->campos_mailing[$k] = strtoupper(remove_acentos($v));

        $file = $_FILES['files_mailing']['tmp_name'];

        $csv_handle = fopen($file, "r");
        $reg = 0;
        $csv_head = array_map(function ($field) {
            return strtoupper(remove_acentos($field));
        }, fgetcsv($csv_handle, 0, ";"));
        $IdxCamposInsert = array_intersect($csv_head, $this->campos_mailing);
	
		$head_valid = true;
		foreach( $this->campos_mailing as $kc=>$vc ){ 
			if( $vc != $csv_head[$kc] ){					
				$head_valid = false;
				break;
			}
		}
			
        if ( count($this->campos_mailing) != count($csv_head) || $head_valid === false ) {
            $this->resposta_upload['message'] = 'ESTRUTURA DE COLUNAS ESTA INCORRETO, FAVOR BAIXAR MODELO DE CSV E PREENCHER CORRETAMENTE';
            $this->resposta_upload['error'] = 1;
        } else {
			
            $nome_mailing = $this->request->getPost('nome_mailing');
            $this->db->transStart();
            $sql_insert =
                "INSERT INTO mailing_clientes
                    ( nome, cpf, email, contato1,contato2,contato3,contato4,cep,uf,cidade,bairro,logradouro, num_fachada, nome_mailing )
                    VALUES ";
            while (($data = fgetcsv($csv_handle, 5000, ";")) !== FALSE) {				
                $reg++;
              //  if ($reg == 1) continue;  //se tirar o comentario do if, ir na linha 62 e  mudar para $reg > 2				
                foreach ($this->campos_mailing as $k => $v) {
                    $arr_insert[$k] = addslashes(remove_acentos(utf8_encode($data[array_search($v, $IdxCamposInsert)])));
                }

                if ($arr_insert[2] != "") {
                    if (strlen($arr_insert[2]) <= 11) {
                        $arr_insert[2] = str_pad($arr_insert[2], 11, "0", STR_PAD_LEFT);
                    } else {
                        $arr_insert[2] = str_pad($arr_insert[2], 14, "0", STR_PAD_LEFT);
                    }
                }

                $sql_insert .=
                    ($reg > 1 ? ',' : NULL) . " ('" . implode("','", $arr_insert) . "', '{$nome_mailing}')";
            }
			
            $this->db->query($sql_insert);
            if($this->db->affectedRows() > 0)  $this->envio_email("mailing");

            $this->db->transComplete();
			
			$this->resposta_upload['options_mailing'] = $this->filtros_mailing('nome_mailing');
			$this->resposta_upload['tot_reg_base'] = $this->tot_clientes();
			
        }

        echo json_encode($this->resposta_upload);
    }

    public function retorna_mailings()
    {
        $dBusca = $this->request->getPost();
		$busca_cidade = ($dBusca['f_cidade_mailing'] == null ? '' : "cidade='{$dBusca['f_cidade_mailing']}' AND ");
        $where  = $dBusca['f_cep_mailing'] != '' ? " AND cep='{$dBusca['f_cep_mailing']}' " : NULL;
        //  $where  .= ($dBusca['tipo'] != 'T' ? ($dBusca['tipo'] == 'C' ? ' AND valor >= 0' : ' AND valor < 0') : NULL);

        $sql = "SELECT  cpf, nome, email, contato1, contato2, contato3, contato4, cep, uf, cidade, bairro, logradouro, num_fachada,
                        'I' as viabilidade		
                FROM mailing_clientes
                WHERE $busca_cidade
                    nome_mailing='{$dBusca['f_nome_mailing']}'
                    {$where}
                ORDER BY data_mailing desc, cidade";
        $rows = $this->db->query($sql)->getResultArray();
        $retorno = [];
        $arr_ceps = [];
        if (count($rows) > 0) {
            helper('sqlite');
            $dbSqlite = conn_sqlite();
            $cliente = session()->get('cliente_db');
            $tb = $cliente . "_dfv";
            $ceps = array_values(array_column($rows, 'cep', 'cep'));
            $sql = "SELECT * FROM {$tb} WHERE cep in (" . (implode(',', $ceps)) . ") ";

            $sth = $dbSqlite->prepare($sql);
            $sth->execute();
            $rows_ceps = $sth->fetchAll();
            foreach ($rows_ceps as $k_cep => $v_ceps) {
                $arr_rows = json_decode($v_ceps['detalhes']);
                foreach ($arr_rows as $key => $val) {
                    $arr_ceps[$val[7] . "__" . $val[3]] =  ($val[9] == 'Viavel' ? 'V' : 'I');
                }
            }
        }

        foreach ($rows as $k => $v) {
            $cepFach = $v['cep'] . "__" . $v['num_fachada'];

            if (array_key_exists($cepFach, $arr_ceps)) {
                $rows[$k]['viabilidade'] = $arr_ceps[$cepFach];
            }
        }

        echo json_encode($rows);
    }

    /*  public function exclui_mailings()
    {
        $dPost = $this->request->getPost();
        $sql = "DELETE FROM mailing_clientes
                WHERE cidade='{$dPost['f_cidade_mailing']}' 
                AND nome_mailing='{$dPost['f_nome_mailing']}'";
        $this->db->query($sql);
        echo json_encode(['deletado' => $this->db->Affectedrows()]);
    }
      public function soma_total()
    {
        $dBusca = $this->request->getPost();
        $where  = $dBusca['num_os'] != '' ? " AND num_os='{$dBusca['num_os']}' " : NULL;
        $where  .= ($dBusca['tipo'] != 'T' ? ($dBusca['tipo'] == 'C' ? ' AND valor >= 0' : ' AND valor < 0') : NULL);
        $db = db_connect();

        $sql = "SELECT  
                    FORMAT(coalesce( sum(valor), 0), 2,'de_DE') valor
                FROM linha_pgto A
                WHERE status='A' AND valor >=0 AND data_instalacao BETWEEN '{$dBusca['data_ini']}' AND '{$dBusca['data_fim']}' {$where}
                ";
        $sql_estorno = "SELECT  
                     FORMAT(coalesce( sum(valor), 0), 2,'de_DE') estorno
                FROM linha_pgto A
                WHERE status='A' AND valor < 0 AND data_instalacao BETWEEN '{$dBusca['data_ini']}' AND '{$dBusca['data_fim']}' {$where}
                ";

        $rows = $db->query($sql)->getResultArray();
        $rows_estorno = $db->query($sql_estorno)->getResultArray();
        echo json_encode(["valor" => $rows[0]['valor'], 'estorno' => $rows_estorno[0]['estorno']]);
    }*/
    public function filtros_mailing($campo, $where = null)
    {
        $sql = "SELECT DISTINCT({$campo}) descr
                FROM mailing_clientes
                {$where}
                order by nome_mailing desc, cidade";
        $rows = $this->db->query($sql)->getResultArray();
        return $rows;
    }
    public function busca_cidades()
    {
        $dPost = $this->request->getPost();
        $rows_cidade = $this->filtros_mailing("cidade", "WHERE nome_mailing='{$dPost['f_nome_mailing']}'");
        echo json_encode($rows_cidade);
    }
    public function busca_ceps()
    {
        $dPost = $this->request->getPost();
        $rows_cidade = $this->filtros_mailing("cep", "WHERE nome_mailing='{$dPost['f_nome_mailing']}' and cidade='{$dPost['f_cidade_mailing']}'");
        echo json_encode($rows_cidade);
    }
    public function tot_clientes()
    {
        $sql = "SELECT count(nome) tot FROM mailing_clientes";
        $rows = $this->db->query($sql)->getResultArray();
        return number_format($rows[0]['tot'], 0, "", ".");
    }

    public function envio_email($csv=""){
        $sqlGrpEnvio = "SELECT C.email  FROM grupo_envio_emails A
                        INNER JOIN grupo_usuario B ON B.id=A.id_grupo
                        INNER JOIN usuarios C on C.grupo=B.id
                        WHERE COALESCE(C.status, '') = 'A' AND  COALESCE(A.{$csv}_csv, '') = 'S'
                        and C.email <> 'lucas_salmeida@yahoo.com.br'";
        $rows = $this->db->query($sqlGrpEnvio)->getResultArray();
        if(count($rows)> 0){
            helper('envia_email_helper');
            $emails = array_column($rows, 'email');
            email_simples($emails, 'Atualizacao de base', "a base de ".$csv." foi atualizada!");
        }
    }
}
