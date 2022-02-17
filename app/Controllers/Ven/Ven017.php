<?php

namespace App\Controllers\Ven;

use App\Controllers\BaseController;
use App\Models\Vendas\ParametroLinhaPgto_Model;

use Datatables_server_side;

class Ven017 extends BaseController
{
    private $resposta_upload = ["error" => 0, "message" => "", "rejeitados" => [], "info_card_upload" => []];
    public function __construct()

    {
        $this->titulo = "DFV";
		$paramlinha_LinhaPgto = new ParametroLinhaPgto_Model();
        $this->campos_lpgto = $paramlinha_LinhaPgto->first();
		unset($this->campos_lpgto['id']);
    }
    public function index()
    {
        $data = [
            "arquivo_js" => ['jquery.mask.min'],
            "arquivo_dataTable" => true,
			"campos_lpgto" => $this->campos_lpgto,
        ];
        $this->load_template($data);
    }

    public function upload_linha_pgto()
    {
        
        $this->db = db_connect();

        $files = $_FILES['files_linha_pgto'];

        foreach ($files['tmp_name'] as $m => $n) {
            $csv_handle = fopen($n, "r");
            $reg = 0;
            $csv_head = array_map(function ($field) {
                return strtoupper($field);
            }, fgetcsv($csv_handle, 0, ";"));
            $IdxCamposInsert = array_intersect($csv_head, $this->campos_lpgto);
            $campos_uteis = array_values(array_filter($this->campos_lpgto));
            $campos_dif = array_diff($campos_uteis, $csv_head);
            if (count($campos_dif)) {
                $this->resposta_upload['message'] = 'FAVOR VERIFICAR SEUS ARQUIVOS OS CAMPOS: (' . implode(',', $campos_dif) . ' ) NAO ESTAO PARAMETRIZADOS CORRETAMENTE';
                $this->resposta_upload['error'] = 1;
            } else {
                $this->db->transStart();
                while (($data = fgetcsv($csv_handle, 5000, ";")) !== FALSE) {
                    $reg++;
                    if ($reg == 1) continue;
                    foreach ($this->campos_lpgto as $k => $v) {
                        $arr_insert[$k] = addslashes(remove_acentos(utf8_encode($data[array_search($v, $IdxCamposInsert)])));
                    }

                    $arr_insert['data_instalacao'] = substr($arr_insert['data_instalacao'], 6, 4) . "-" .
                        substr($arr_insert['data_instalacao'], 3, 2) . "-" .
                        substr($arr_insert['data_instalacao'], 0, 2);
                    $arr_insert['valor'] =  str_replace(',', '.', str_replace('.', '', $arr_insert['valor']));

                    if ($arr_insert['cpf_cliente'] != "") {
                        if (strlen($arr_insert['cpf_cliente']) <= 11) {
                            $arr_insert['cpf_cliente'] = str_pad($arr_insert['cpf_cliente'], 11, "0", STR_PAD_LEFT);
                        } else {
                            $arr_insert['cpf_cliente'] = str_pad($arr_insert['cpf_cliente'], 14, "0", STR_PAD_LEFT);
                        }
                    }

                    $sql_insert =
                        "INSERT INTO linha_pgto
                                ( " . (implode(',', array_keys($this->campos_lpgto))) . ", id_usu_insert )
                                VALUES
                                    ('" . implode("','", $arr_insert) . "', '" . (session()->get('id_usuario')) . "')
                        ";
                    $this->db->query($sql_insert);
                }

                $this->db->transComplete();
                $this->envio_email();
            }
        }
        echo json_encode($this->resposta_upload);
    }

    public function envio_email()
    {
        $sql = "SELECT B.email 
                FROM grupo_envio_emails A
                INNER JOIN usuarios B ON B.grupo=A.id_grupo
                WHERE linha_pgto_csv='S' AND B.status='A'";
        $rows = $this->db->query($sql)->getResultArray();
        $emails = array_column($rows, 'email');
        //$emails = ['vagner.cerqueira@live.com', 'isaque.cerqueira@live.com'];
        helper('envia_email_helper');
		$titulo = 'Atualizacao de base ciclo pagamento'. " - ".date("d/m/Y h:i");
		$msg = "a base de ciclo pagamento foi atualizada, verifique o sistema: ".base_url("portal");
        email_simples($emails, $titulo, $msg);
    }

    public function retorna_pgtos()
    {
        $dBusca = $this->request->getPost();
        $where  = $dBusca['num_os'] != '' ? " AND num_os='{$dBusca['num_os']}' " : NULL;
        $where  .= ($dBusca['tipo'] != 'T' ? ($dBusca['tipo'] == 'C' ? ' AND valor >= 0' : ' AND valor < 0') : NULL);
        $db = db_connect();

        $sql = "SELECT  num_os, cod_sap, filial, ciclo, quinzena, cpf_cliente,
                DATE_FORMAT(data_instalacao, '%d/%m/%Y') data_instalacao, 
				CASE WHEN valor >= 0 THEN CONCAT( '<span class=text-success>',FORMAT(valor, 2,'de_DE'),'</span>') 
				ELSE CONCAT( '<span class=text-danger>',FORMAT(valor, 2,'de_DE'),'</span>')  END valor ,
                CASE WHEN valor >= 0 THEN '<span class=text-success>Comissao</span>' ELSE '<span class=text-danger>Desconto</span>' END AS tipo
                FROM linha_pgto A
                WHERE status='A' AND data_instalacao BETWEEN '{$dBusca['data_ini']}' AND '{$dBusca['data_fim']}' {$where}
                ORDER BY A.data_instalacao desc";

        $rows = $db->query($sql)->getResultArray();
        echo json_encode(["data"  =>  $rows]);
    }

    public function exclui_linhas()
    {
        $db = db_connect();
        $dPost = $this->request->getPost();
        $where  = $dPost['num_os'] != '' ? " AND num_os='{$dPost['num_os']}' " : NULL;
        $where  .= ($dPost['tipo'] != 'T' ? ($dPost['tipo'] == 'C' ? ' AND valor >= 0' : ' AND valor < 0') : NULL);
        $sql = "UPDATE linha_pgto SET status='C', id_usu_delete='" . (session()->get('id_usuario')) . "', dt_delete=now()
				WHERE status='A' AND data_instalacao BETWEEN '{$dPost['data_ini']}' AND '{$dPost['data_fim']}' {$where}";
        $db->query($sql);
        echo json_encode(['deletado' => $db->Affectedrows()]);
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
    }
}
