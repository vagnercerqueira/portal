<?php
/*

upload base csv para banco sqlite

*/
namespace App\Controllers\Ven;

use App\Controllers\BaseController;

class Ven026 extends BaseController
{
    private $resposta_upload = ["error" => 0, "message" => "", "rejeitados" => [], "info_card_upload" => []];
    private $campos_mailing = [ 
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
        $this->cliente = session()->get('cliente_db');        

        helper('sqlite');
        $this->db_sqlite = conn_sqlite();
    }
    public function index()
    {
        $this->cria_tabela();
        $data = [
            "arquivo_js" => ['jquery.mask.min'],
            "arquivo_dataTable" => true,
            "option_nome_mailing" => $this->arquivosMailing(),
            "option_campos" => $this->campos_mailing,
            "option_tot_reg" => $this->tot_clientes(),
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
            $existeNome = $this->existe_nome($nome_mailing);
            if($existeNome === false){
                $this->resposta_upload['message'] = 'NOME DE ARQUIVO JA EXISTE';
                $this->resposta_upload['error'] = 1;                
            }else{            
                $dadosInsert = [];
                while (($data = fgetcsv($csv_handle, 5000, ";")) !== FALSE) {				
                    $reg++;			
                    foreach ($this->campos_mailing as $k => $v) $arr_insert[$k] = addslashes(remove_acentos(utf8_encode($data[array_search($v, $IdxCamposInsert)])));
                    if ($arr_insert[2] != "") $arr_insert[2] = str_pad($arr_insert[2], ( strlen($arr_insert[2]) <= 11 ? 11 : 14 ), "0", STR_PAD_LEFT);                
                    $dadosInsert[] = $arr_insert;                                                           
                }
                        
                $dDados = json_encode($dadosInsert);            
                $dDados = str_replace(['AVENIDA', 'RUA', 'ALAMEDA', 'TRAVESSA', 'PRACA', 'ACAMPAMENTO', 'CAMINHO', 'ESTRADA', 'LARGO'], ['AV', 'R', 'ALAM', 'TR', 'PC', 'ACAMP', 'CAM', 'EST', 'LGO'], $dDados);

                $this->db_sqlite->beginTransaction();
                $this->db_sqlite->query("INSERT INTO {$this->cliente}_arquivo_mailing (nome, data) values ('{$nome_mailing}', '".date('Y-m-d')."')");
                
                $this->db_sqlite->query("INSERT INTO {$this->cliente}_mailing (dados, id_arquivo) values ('{$dDados}', '".$this->db_sqlite->lastInsertId()."')");
                $this->db_sqlite->commit();
                $this->resposta_upload['options_mailing'] = $this->arquivosMailing();
                $this->resposta_upload['tot_reg_base'] = $this->tot_clientes();
            }
        }

        echo json_encode($this->resposta_upload);
    }

    public function cria_tabela()
    {
        $tb_exists = $this->db_sqlite->query("SELECT count(*) TOT FROM sqlite_master WHERE type='table' AND name='{$this->cliente}_arquivo_mailing'")->fetch();
        $retorno = true;
        if ($tb_exists['TOT'] == 0) {
            $sql = "CREATE TABLE {$this->cliente}_arquivo_mailing (
                                    	id	INTEGER PRIMARY KEY AUTOINCREMENT,
                                        nome text UNIQUE,
                                        data DATE
                                )";
                                
            $this->db_sqlite->prepare($sql)->execute();

            $sql = "CREATE TABLE {$this->cliente}_mailing (
                id	INTEGER PRIMARY KEY AUTOINCREMENT,
                dados TEXT,
                id_arquivo         REFERENCES {$this->cliente}_arquivo_mailing (id) ON DELETE CASCADE
            )";
            $this->db_sqlite->prepare($sql)->execute();
        }
    }
    public function existe_nome($nome_mailing){
        $retorno = true;
        $sql = "SELECT count(*) TOT FROM {$this->cliente}_arquivo_mailing WHERE nome='{$nome_mailing}'";
        $sth = $this->db_sqlite->prepare($sql);
        $sth->execute();
        $row = $sth->fetch();
        if($row['TOT'] > 0) $retorno = false;
        return $retorno;
    }

    public function retorna_mailings()
    {
        $dPost = $this->request->getPost();        
        $mailings = $this->tb_csv($dPost);
        echo json_encode($mailings);        
    }
    public function arquivosMailing(){
        //$this->cria_tabela();
        $sql = "SELECT * 
                FROM {$this->cliente}_arquivo_mailing
                order by id desc";
        $sth = $this->db_sqlite->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll();
        $dados = [];
        foreach($rows as $k=>$v) $dados[] = ['id'=>$v['id'], 'nome'=> $v['nome'], 'data'=>( date('d/m/Y',strtotime($v['data'])))];
        return $dados;
    }

    public function busca_cidades()
    {                    
       $dPost = $this->request->getPost();
       $cidades = $this->busca_cidades_ceps($dPost);
       $tot = count($cidades);
       $cidades = array_unique(array_column($cidades, 9));
       echo json_encode(['cidades'=>$cidades, 'tot'=>number_format($tot, 0, "", ".")]); 
    }
    public function busca_cidades_ceps($filtro){//ind[9] = cidade 
        $sql = "    SELECT dados
                    FROM {$this->cliente}_mailing
                    WHERE id_arquivo='{$filtro['f_nome_mailing']}'";
                    
        $sth = $this->db_sqlite->prepare($sql);
        $sth->execute();
        $row = $sth->fetch();
        $dados = (json_decode($row['dados'], true));
       
        return $dados;
    }
    public function busca_ceps()
    {
        $dPost = $this->request->getPost();
        $ceps = $this->busca_cidades_ceps($dPost);

        $ceps = array_filter($ceps, function($d){
            return $d[9] == $this->request->getPost('f_cidade_mailing');
        }) ;
        $tot = count($ceps);
        $ceps = array_column($ceps, 9, 7);        
        $ceps = array_keys($ceps);
        echo json_encode(["tot"=>number_format($tot, 0, "", ".")]);
    }   

    public function tot_clientes()
    {
        $sql = "SELECT * FROM {$this->cliente}_mailing";
        $sth = $this->db_sqlite->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll();
        $tot = 0;
        foreach($rows as $k=>$v) $tot += (count(json_decode($v['dados'], true)));       
        return number_format($tot, 0, "", ".");
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

    public function tb_csv($dGetPost){
        $mailings = $this->busca_cidades_ceps($dGetPost);
        
        if(count($mailings) > 0){
            
            $sql = "SELECT * FROM {$this->cliente}_dfv";
            $sth = $sth = $this->db_sqlite->prepare($sql);
            $sth->execute();
            $rows_ceps = $sth->fetchAll();            

            $arrCepsViavel = [];
            foreach($rows_ceps as $kc=>$vc){
                $vals = json_decode($vc[1], true);
                $arrCepsViavel[$vals[0][7]] = ( $vals[0][9] == "Viavel" ? "V" : "I");
            }
           
            if($dGetPost['f_cidade_mailing'] != "" ){
                $mailings = array_filter($mailings, function($d) use($arrCepsViavel) {                   
                    return ( $d[9] == $this->request->getPostGet("f_cidade_mailing") );
                });
            }
            $arr_retorno = $mailings;
            foreach($mailings as $t=>$u){
                $arr_retorno[$t][13] = "I";
                if(array_key_exists($u[7], $arrCepsViavel)){
                    $arr_retorno[$t][13] = $arrCepsViavel[$u[7]];
                }
            }
            $mailings = $arr_retorno;
        }
        return $arr_retorno;
    }

    public function download_mailing(){
        $filename = 'mailing_'.date('Ymd').'.csv';
        $dGet = $this->request->getGet();
        $mailings = $this->tb_csv($dGet);

        header("Content-Description: File Transfer;"); 
        header("Content-Disposition: attachment; filename=$filename"); 
        header("Content-Type: application/csv; ");
   
        $file = fopen('php://output', 'w');
   
        $header = $this->campos_mailing;
        fputcsv($file,$header,";", "\0");
        foreach ($mailings as $key=>$line) fputcsv($file,$line,";", "\0");        
        fclose($file); 
        echo $file;           
    }

    public function exclui_mailings(){
        $dPost = $this->request->getPost();
        $this->db_sqlite->query("DELETE FROM {$this->cliente}_mailing WHERE id_arquivo='{$dPost['f_nome_mailing']}'");
        $this->db_sqlite->query("DELETE FROM {$this->cliente}_arquivo_mailing WHERE id='{$dPost['f_nome_mailing']}'");
        echo json_encode(['options_mailing' => $this->arquivosMailing(), 'tot_reg_base'=>$this->tot_clientes(), 'sucesso'=>'ok']);
    }
}
