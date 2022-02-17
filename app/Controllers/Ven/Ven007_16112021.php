<?php

namespace App\Controllers\Ven;

use App\Controllers\BaseController;
use App\Libraries\Fpdf\PDF_MC_Table;
use App\Models\Vendas\ParametroVendaLoteCsv_Model;
use App\Models\Vendas\Vendas_Model;
use App\Models\Vendas\ParametroDfvCsv_Model;
use App\Models\Usuarios\Equipe_usuarioModel;
use App\Models\Usuarios\UsuariosModel;
use App\Models\Vendas\Bancos_Model;
use App\Models\Vendas\ComboPlanos_Model;
use App\Models\Vendas\Fibra_Model;
use App\Models\Vendas\FormaPagamento_Model;
use App\Models\Vendas\PlanosOperadora_Model;
use App\Models\Vendas\SetorTratamento_Model;
use App\Models\Vendas\StatusAtivacao_Model;
use App\Models\Vendas\TipoTurno_Model;
use App\Models\Vendas\Tv_Model;
use App\Models\Vendas\Uf_atuacao_Model;
use App\Models\Vendas\Vencimentos_Model;
use App\Models\Vendas\Whatsapp_Model;
use App\Models\Vendas\ParametroUploadsCsv_Model;
use Datatables_server_side;

class Ven007 extends BaseController
{
    private $ceps = [];
    private $dthr = null;
    private $resposta_upload = ["error" => 0, "message" => "", "rejeitados" => [], "info_card_upload" => []];
    private $turno = [1 => "Manha", 2 => "Tarde"];
    
	//private $campos_bov = ["NUM_OS", "STATUS_BOV", "MIGRACAO_VELOX", "MIGRACAO_FIXO", "DATA_INSTALACAO"];

    private $campos_blindagem = ['NUMERO PEDIDO', 'PRODUTO', 'DATA STATUS', 'STATUS', 'FLAG VENDA VALIDA', 'FLAG MIG COBRE FIXO',
                                'FLAG MIG COBRE VELOX', 'PLANO', 'ID BUNDLE', 'CPF/CNPJ', 'SUBMOTIVO'];

    private $campos_dfv = ['UF', 'MUNICIPIO', 'LOGRADOURO', 'NUM FACHADA', 'COMPLEMENTO', 'COMPLEMENTO2',
                                'COMPLEMENTO3', 'CEP', 'BAIRRO', 'TIPO VIABILIDADE', 'NOME CDO', 'COD LOGRADOURO'];

    /*private $campos_venda_lote = [  'DT VENDA', 'NOME CLIENTE', 'GENERO', 'DT NASCIMENTO', 'CPF/CNPJ', 'RG',
                                    'ORGAO EXPEDIDOR', 'NOME MAE', 'CONTATO PRINCIPAL', 'CONTATO SECUNDARIO', 'EMAIL', 
                                    'MATRICULA VENDEDOR', 'BANDA LARGA VELOCIDADE', 'COMBO/OFERTA CONTRATADA', 'TV - PLANO TV',
                                    'FORMA PGTO','VENCIMENTO', 'CONTA ONLINE', 'BANCO', 'AGENCIA', 'CONTA', 'DIGITO','OPERACAO', 
                                    'CEP', 'LOGRADOURO', 'NÚMERO', 'BAIRRO', 'CIDADE', 'UF', 'REFERENCIA', 'COMPLEMENTO1 TIPO',
                                    'COMPLEMENTO1', 'COMPLEMENTO2 TIPO', 'COMPLEMENTO2', 'COMPLEMENTO3 TIPO', 'COMPLEMENTO3'];*/
    
    public function __construct()

    {

        $this->UfAtuacao_Model = new Uf_atuacao_Model();
        $this->FormaPagamento_Model = new FormaPagamento_Model();
        $this->Vencimentos_Model = new Vencimentos_Model();
        $this->bancos_Model = new Bancos_Model();
        $this->ComboPlanos_Model = new ComboPlanos_Model();
        $this->planos_fibra_model = new Fibra_Model();
        $this->TipoTurno_Model = new TipoTurno_Model();
        $this->planos_tv_model = new Tv_Model();
        $this->StatusAtivacao_Model = new StatusAtivacao_Model();
        $this->Equipe_Model = new Equipe_usuarioModel();
        $this->UsuarioModel = new UsuariosModel();
        $this->SetorTratamento_Model = new SetorTratamento_Model();
        $this->PlanosOperadora_model = new PlanosOperadora_Model();
        $this->WhatsappMsg_model = new Whatsapp_Model();
        $this->modelo = "vendas";
        $this->tbs_crud  = ['form_vendas' => 'vendas'];
        $this->vendas_model = new Vendas_Model();
		
		$ParametroUploadsCsv = new ParametroUploadsCsv_Model();
		$this->parametros_head_csv = $ParametroUploadsCsv->findAll()[0];
		
        $this->titulo = "DFV";
        $cliente = session()->get('cliente_db');
        $this->tb = $cliente . "_dfv";
        helper('sqlite');
        $this->db = conn_sqlite();
        $this->dthr = date('dmYHis');
        $this->dir_cli =  WRITEPATH . $cliente;
        if (!file_exists($this->dir_cli)) mkdir($this->dir_cli, 0777);
        $this->dir_cli .=  "/uploads/";
        if (!file_exists($this->dir_cli)) mkdir($this->dir_cli, 0777);       
    }
    public function index()
    {
        $uf_atuacao = ['' => 'Selecione...'] + array_column($this->UfAtuacao_Model->findAll(), 'uf', 'uf');
        $forma_pagamento = ['' => 'Selecione...'] + array_column($this->FormaPagamento_Model->findAll(), 'descricao', 'id');
        $vencimentos = ['' => 'Selecione...'] + array_column($this->Vencimentos_Model->findAll(), 'descricao', 'descricao');
        $bancos = ['' => 'Selecione...'] + array_column($this->bancos_Model->findAll(), 'descricao', 'id');
        $combo_planos = ['' => 'Selecione...'] + array_column($this->ComboPlanos_Model->findAll(), 'descricao', 'id');
        $planos_fibra = ['' => 'Selecione...'] + array_column($this->planos_fibra_model->findAll(), 'descricao', 'id');
        $planos_tv = ['' => 'Selecione...'] + array_column($this->planos_tv_model->findAll(), 'descricao', 'id');
        $turno = ['' => 'Selecione...'] + array_column($this->TipoTurno_Model->findAll(), 'descricao', 'id');
        $status_ativacoes = ['' => 'Selecione...'] + array_column($this->StatusAtivacao_Model->findAll(), 'descricao', 'id');
        $equipe_vendas = ['' => 'Selecione...'] + array_column($this->Equipe_Model->equipe_supervisor(970), 'equipe', 'id');
        $vendedor = ['' => 'Selecione...'];
	
        $setor_tratamento = ['' => 'Selecione...'] + array_column($this->SetorTratamento_Model->findAll(), 'descricao', 'id');
        $data = [
            "arquivo_js" => ['jquery.mask.min'],
            "arquivo_dataTable" => true,
            "uf_atuacao" => form_dropdown('UF_INSTALACAO_CSV', $uf_atuacao, '', "id='UF_INSTALACAO_CSV' class='form-control form-control-sm' required"),
            "forma_pagamento" => form_dropdown('FORMA_PAGAMENTO_CSV', $forma_pagamento, '', "id='FORMA_PAGAMENTO_CSV' class='form-control form-control-sm' required"),
            "vencimentos" => form_dropdown('VENCIMENTO_CSV', $vencimentos, '', "id='VENCIMENTO_CSV' class='form-control form-control-sm' required"),
            "bancos" => form_dropdown('PAG_BANCO_CSV', $bancos, '', "id='PAG_BANCO_CSV' class='form-control form-control-sm'"),
            "combo_planos" => form_dropdown('COMBO_CONTRATADO_CSV', $combo_planos, '', "id='COMBO_CONTRATADO_CSV' class='form-control form-control-sm' required"),
            "planos_fibra" => form_dropdown('BANDA_LARGA_VELOCIDADE_CSV', $planos_fibra, '', "id='BANDA_LARGA_VELOCIDADE_CSV' class='form-control form-control-sm' required"),
            "planos_tv" => form_dropdown('PLANO_TV_CSV', $planos_tv, '', "id='PLANO_TV_CSV' class='form-control form-control-sm'"),
            "turno" => form_dropdown('TURNO_AGENDAMENTO', $turno, '', "id='TURNO_AGENDAMENTO' class='form-control form-control-sm'"),
            "turno1" => form_dropdown('TURNO_REAGENDAMENTO_1', $turno, '', "id='TURNO_REAGENDAMENTO_1' class='form-control form-control-sm' disabled"),
            "turno2" => form_dropdown('TURNO_REAGENDAMENTO_2', $turno, '', "id='TURNO_REAGENDAMENTO_2' class='form-control form-control-sm' disabled"),
            "turno3" => form_dropdown('TURNO_REAGENDAMENTO_3', $turno, '', "id='TURNO_REAGENDAMENTO_3' class='form-control form-control-sm' disabled"),
            "turno4" => form_dropdown('TURNO_REAGENDAMENTO_4', $turno, '', "id='TURNO_REAGENDAMENTO_4' class='form-control form-control-sm' disabled"),
            "turno5" => form_dropdown('TURNO_REAGENDAMENTO_5', $turno, '', "id='TURNO_REAGENDAMENTO_5' class='form-control form-control-sm' disabled"),
            "status_ativacao" => form_dropdown('STATUS_ATIVACAO', $status_ativacoes, '', "id='STATUS_ATIVACAO' class='form-control form-control-sm' required"),
            "equipe" => form_dropdown('EQUIPE', $equipe_vendas, '', "id='EQUIPE' class='form-control form-control-sm' required"),
            "vendedor" => form_dropdown('ID_VENDEDOR', $vendedor, '', "id='ID_VENDEDOR' class='form-control form-control-sm' required"),
            "setor_tratamento" => form_dropdown('SETOR_RESP_TRATAMENTO', $setor_tratamento, '', "id='SETOR_RESP_TRATAMENTO' class='form-control form-control-sm'"),
			"option_cols_bov"=> array_values(json_decode($this->parametros_head_csv['bov'], true)),
            "option_cols_blindagem"=>$this->campos_blindagem,
            "option_cols_dfv"=>$this->campos_dfv,            
        ];
        $this->load_template($data);
    }

    public function criar_diretorios_dfv()

    {
        $this->dir_dfv = $this->dir_cli . "DFV/";

        if (!file_exists($this->dir_dfv)) {
            mkdir(($this->dir_dfv), 0777);
            mkdir(($this->dir_dfv . "CSV/"), 0777);
            mkdir(($this->dir_dfv . "TXT/"), 0777);
        }
    }

    /**************AQUI COMEÇA TRATAR UPLOAD DE ARQUIVOSDO PRIMEIRO CARD***************/

    public function upload_dfv()
    {

        $paramDvfCsv = new ParametroDfvCsv_Model();
        $this->campos_dfv = $paramDvfCsv->first();
        unset($this->campos_dfv['id']);

        $this->criar_diretorios_dfv();

        $files = $_FILES['files_dfv'];
        $zip = new \ZipArchive;
        $scanDir = $this->dir_dfv . "CSV/";

        $tipo = strtoupper(substr($files['name'], -4)); // arquivo aceito, .txt e .zip ou .rar

        if (strtoupper($tipo) ==  '.ZIP') {
            $zip->open($files['tmp_name']);
            $zip->extractTo($scanDir);
            $tot_csvs = ($zip->numFiles);
            $zip->close();
            if ($tot_csvs == 1) {
                foreach (scandir($scanDir, 1) as $csv) {
                    if ($csv === '.' || $csv === '..') continue;
                    $cArq = ($scanDir . $csv);
                    $partCsv = explode('.', $csv);
                    if (is_dir($cArq) || strtoupper(end($partCsv)) != 'CSV') {
                        unlink($cArq);
                        continue;
                    }
                    $this->processa_arquivo_dfv($csv);
                    unlink($cArq);
                }
            } else {
                $this->resposta_upload = ["error" => 1, "message" => "Nao é permitido mais de uma arquivo csv!!!"];
            }
        } else {
            array_push($this->resposta_upload['rejeitados'], $files['name']);
        }
        echo json_encode($this->resposta_upload);
    }

    public function processa_arquivo_dfv($csv, $delimitador = ";")
    {
        $csv_path = $this->dir_dfv . "CSV/" . $csv;
        $csv_handle = fopen($csv_path, "r");
        $r = 0;
        $csv_head = array_map(function ($field) {
            return strtoupper($field);
        }, fgetcsv($csv_handle, 0, ";"));
        $campos_uteis = array_values(array_filter($this->campos_dfv));

        $campos_dif = array_diff($campos_uteis, $csv_head);
        if (count($campos_dif)) {
            $this->resposta_upload['message'] = 'FAVOR VERIFICAR O ARQUIVO: ' . $csv . " - OS CAMPOS: (" . implode(',', $campos_dif) . " ) NAO ESTAO PARAMETRIZADOS CORRETAMENTE";
            $this->resposta_upload['error'] = 1;
        } else {
            $IdxCamposInsert = array_intersect($csv_head, $campos_uteis);

            $indexCep = array_search($this->campos_dfv['cep'], $csv_head);
            while (($data = fgetcsv($csv_handle, 5000, ";")) !== FALSE) {
                $r++;
                if ($r == 1) continue;
                $arr_insert = [];
                foreach ($this->campos_dfv as $m => $n) $arr_insert[] = str_replace(["'", '"'], ["", ""], remove_acentos(utf8_encode($data[array_search($n, $IdxCamposInsert)])));
                $this->ceps[$data[$indexCep]][] = $arr_insert;
            }
            $dFile = json_encode($this->ceps);
            $dFile = str_replace(['AVENIDA', 'RUA', 'ALAMEDA', 'TRAVESSA', 'PRACA', 'ACAMPAMENTO', 'CAMINHO', 'ESTRADA', 'LARGO'], ['AV', 'R', 'ALAM', 'TR', 'PC', 'ACAMP', 'CAM', 'EST', 'LGO'], $dFile);
            file_put_contents($this->dir_dfv . "TXT/dados_dfv.txt", $dFile);
        }
    }

    function criar_tabelas_dfv()
    {
        //$this->db->prepare("DROP TABLE IF EXISTS {$this->tb}")->execute();
        $this->db->prepare("DROP TABLE IF EXISTS {$this->tb}_new")->execute();
        $this->cria_tabela();
        $this->cria_tabela('_new', $this->dthr);
    }
    public function cria_tabela($pos = '', $idx = '')

    {
        $tb_exists = $this->db->query("SELECT count(*) TOT FROM sqlite_master WHERE type='table' AND name='{$this->tb}{$pos}'")->fetch();
        if ($tb_exists['TOT'] == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS {$this->tb}{$pos} ( cep int(8) PRIMARY KEY, detalhes text )";
            $this->db->prepare($sql)->execute();
        }
    }

    public function leCsvsDfv()
    {
        $this->db->prepare("DROP TABLE IF EXISTS {$this->tb}_new")->execute();
        $this->criar_tabelas_dfv();
        $dados_dfv = file_get_contents($this->dir_cli . "DFV/TXT/dados_dfv.txt", json_encode($this->ceps));
        $dados = json_decode($dados_dfv);
        $this->db->beginTransaction();

        $sqlIns = " INSERT INTO {$this->tb}_new (cep, detalhes) 
                    VALUES (?, ?) ";
        $sqlDel = " DELETE FROM {$this->tb} WHERE cep=?";
        $stmtIns = $this->db->prepare($sqlIns);
        $stmtDel = $this->db->prepare($sqlDel);
        foreach ($dados as $k => $v) {
            $stmtIns->execute([$k, json_encode($v)]);
            $stmtDel->execute([$k]);
        }

        $sqlIns = "INSERT INTO {$this->tb} SELECT * FROM {$this->tb}_new";
        $sqlDrop = " DROP TABLE {$this->tb}_new";
        $this->db->prepare($sqlIns)->execute();
        $this->db->prepare($sqlDrop)->execute();

        $this->db->commit();
      //  if(count((array)$dados) > 0) $this->envio_email("dfv");
        unlink($this->dir_cli . "/DFV/TXT/dados_dfv.txt");
        echo json_encode($this->resposta_upload);
    }
    //------------------------------------BLINDAGEM---------------
    public function upload_blindagem()
    {       
        $this->db = db_connect();
        $files = $_FILES['files_blindagem'];
        $this->db->transStart();
        // $this->db->query("DELETE FROM ultima_blindagem");
		$campos_blindagem = json_decode($this->parametros_head_csv['blindagem'], true);


        foreach ($files['tmp_name'] as $m => $n) {
            $csv_handle = fopen($n, "r");
            $reg = 0;
            $csv_head = array_map(function ($field) {
                return strtoupper($field);
            }, fgetcsv($csv_handle, 0, ";"));
            $IdxCamposInsert = array_intersect($csv_head, $this->campos_blindagem);

            $head_valid = true;
            foreach( $this->campos_blindagem as $kc=>$vc ){ 
                if( $vc != $csv_head[$kc] ){					
                    $head_valid = false;
                    break;
                }
            }
                
            if ( count($this->campos_blindagem) != count($csv_head) || $head_valid === false ) {
                $this->resposta_upload['message'] = 'ESTRUTURA DE COLUNAS ESTA INCORRETO, FAVOR BAIXAR MODELO DE CSV E PREENCHER CORRETAMENTE';
                $this->resposta_upload['error'] = 1;
            } else {
                $aff = 0;
                while (($data = fgetcsv($csv_handle, 5000, ";")) !== FALSE) {
                    $reg++;
                    if ($reg == 1) continue;
                    foreach ($this->campos_blindagem as $k => $v) {
                        $arr_insert[$k] = addslashes(remove_acentos(utf8_encode($data[array_search($v, $IdxCamposInsert)])));
                    }
                    $arr_insert['data_status'] = substr($arr_insert['data_status'], 0, 4) . "-" .
                        substr($arr_insert['data_status'], 4, 2) . "-" .
                        substr($arr_insert['data_status'], 6, 2);

                    $updVendas = "  UPDATE vendas SET
                                        status_blindagem = '{$arr_insert['status']}',
                                        mig_cobre_fixo_blindagem  = '{$arr_insert['flg_mig_cobre_fixo']}',
                                        mig_cobre_velox_blindagem = '{$arr_insert['flg_mig_cobre_velox']}',
                                        dt_instalacao = '{$arr_insert['data_status']}'
                                    WHERE num_os = '{$arr_insert['numero_pedido']}'";

                    $this->db->query($updVendas);
                    $aff = $this->db->affectedRows();
                }
                
                if($aff > 0) $this->envio_email("blindagem");
            }
        }
        $this->db->transComplete();

        echo json_encode($this->resposta_upload);
    }

    //------------------------------------BOV---------------
    public function upload_bov()
    {

        $this->db = db_connect();
	
        $files = $_FILES['files_bov'];

        $this->db->transStart();
        $this->db->query("DELETE FROM ultima_bov");
		$campos_bov = json_decode($this->parametros_head_csv['bov'], true);
		
        $aff = 0;
        foreach ($files['tmp_name'] as $m => $n) {
            $csv_handle = fopen($n, "r");
            $reg = 0;
            $csv_head = array_map(function ($field) {
                //return strtoupper($field);
				return ($field);
            }, fgetcsv($csv_handle, 0, ";"));
			
            $IdxCamposInsert = array_intersect($csv_head, $campos_bov);			

			$head_valid = true;
			foreach( array_values($campos_bov) as $kc=>$vc ){ 
				if( $vc != $csv_head[$kc] ){					
					$head_valid = false;
					break;
				}
			}		
			
            if (count($campos_bov) != count($csv_head) || $head_valid === false) {//quantidade de colunas incorretas
                $this->resposta_upload['message'] =  "Arquivo: ".$files['name'][$m].'. ESTRUTURA DE COLUNAS ESTA INCORRETO, FAVOR BAIXAR MODELO DE CSV E PREENCHER CORRETAMENTE';
                $this->resposta_upload['error'] = 1;
            } else {
					
                while (($data = fgetcsv($csv_handle, 5000, ";")) !== FALSE) {
					
                    $reg++;
                    //if ($reg == 1) continue;
					
                    foreach ($campos_bov as $k => $v) { 
						$arr_insert[$k] = remove_acentos(addslashes(utf8_encode($data[array_search($v, $IdxCamposInsert)])));
					}
					
                    $arr_insert['DATA_STATUS'] = substr($arr_insert['DATA_STATUS'], 0, 4) . "-" .
                        substr($arr_insert['DATA_STATUS'], 4, 2) . "-" .
                        substr($arr_insert['DATA_STATUS'], 6, 2);

                    $updVendas = "  UPDATE vendas SET
                                    status_bov = '{$arr_insert['STATUS']}',
                                    mig_cobre_fixo_bov  = '{$arr_insert['FLG_MIG_COBRE_FIXO']}',
                                    mig_cobre_velox_bov = '{$arr_insert['FLG_MIG_COBRE_VELOX']}',
                                    dt_instalacao = '{$arr_insert['DATA_STATUS']}'
                                WHERE num_os = '{$arr_insert['NUMERO_PEDIDO']}'";
					
                    $this->db->query($updVendas);
                    if($this->db->affectedRows() > 0) $aff++;
					
                    if ($arr_insert['STATUS'] == "Concluido") { //status_ativacao vem da tabela status ativacoes(id)
						
                        $sqlUpdVendaAtiv = "UPDATE vendas SET status_ativacao = 5
                                        WHERE num_os = '{$arr_insert['NUMERO_PEDIDO']}'";
					
                        $this->db->query($sqlUpdVendaAtiv);

                        $sqlInsBov = "  
                        INSERT IGNORE 
                        INTO ultima_bov ( " . strtolower( implode(",", array_keys($campos_bov))) . ", dt_upload, id_usuario )
                        VALUES ('" . implode("','", $arr_insert) . "', now(), '" . (session()->get('id_usuario')) . "')";
						
                        $this->db->query($sqlInsBov);
                        if($this->db->affectedRows() > 0) $aff++;
						
                    }
                }
            }
        }
        $this->db->transComplete();
        if($aff > 0) $this->envio_email("bov");

        echo json_encode($this->resposta_upload);
    }

    public function envio_email($csv=""){
        $sqlGrpEnvio = "SELECT C.email  FROM grupo_envio_emails A
                        INNER JOIN grupo_usuario B ON B.id=A.id_grupo
                        INNER JOIN usuarios C on C.grupo=B.id
                        WHERE COALESCE(C.status, '') = 'A' AND  COALESCE(A.{$csv}_csv, '') = 'S'";
        $rows = $this->db->query($sqlGrpEnvio)->getResultArray();
        if(count($rows)> 0){
            helper('envia_email_helper');
            $emails = array_column($rows, 'email');
            email_simples($emails, 'Atualizacao de base', "a base de ".$csv." foi atualizada!");
        }
    }
    //------------------------------------VENDA LOTE---------------
	
	public function upload_venda_lote()
    {
        $formas_pagamento = $this->FormaPagamento_Model->findAll();
        $bancos =  $this->bancos_Model->findAll();
        $bancos =  $this->bancos_Model->findAll();
        $combo = $this->ComboPlanos_Model->findAll();
        $planos_fibra = $this->planos_fibra_model->findAll();
        $planos_tv = $this->planos_tv_model->findAll();

        $arr_forma_pag = array_column($formas_pagamento, 'descricao', 'id');
        $arr_bancos = array_column($bancos, 'descricao', 'id');
        $arr_combo = array_column($combo, 'descricao', 'id');
        $arr_planos = array_column($planos_fibra, 'descricao', 'id');
        $arr_tv = array_column($planos_tv, 'descricao', 'id');


        $paramVendaLoteCsv = new ParametroVendaLoteCsv_Model();
        $this->campos_venda_lote = $paramVendaLoteCsv->first();
        unset($this->campos_venda_lote['id']);

        $this->vendas_model = new Vendas_Model();
        $usuario_logado = session()->get('id_usuario');
        $data_hora =  date('Y-m-d H:i:s');

        foreach ($this->campos_venda_lote as $k => $v) $this->campos_venda_lote[$k] = strtoupper(remove_acentos($v));


        $this->db = db_connect();
        $this->campos_venda_lote = array_filter($this->campos_venda_lote);

        $files = $_FILES['files_venda_lote'];

        $this->db->transStart();

        foreach ($files['tmp_name'] as $m => $n) {
            $csv_handle = fopen($n, "r");
            $reg = 0;
            $csv_head = array_map(function ($field) {
                return (strtoupper(remove_acentos($field)));
            }, fgetcsv($csv_handle, 0, ","));
            unset($csv_head[0]);
            $IdxCamposInsert = array_intersect($csv_head, $this->campos_venda_lote);

            $campos_uteis = array_values($this->campos_venda_lote);
            $campos_dif = array_diff($campos_uteis, $csv_head);

            if (count($campos_dif)) {
                $this->resposta_upload['message'] = 'FAVOR VERIFICAR SEUS ARQUIVOS OS CAMPOS: (' . implode(',', $campos_dif) . ' ) NAO ESTAO PARAMETRIZADOS CORRETAMENTE';
                $this->resposta_upload['error'] = 1;
            } else {
                $aff = 0;
                while (($data = fgetcsv($csv_handle, 5000, ",")) !== FALSE) {
                    if ($reg == 1) continue;
                    #A query abaixo não permite a inclusão por lote, de um cliente que já tenha CPF na base.
                    foreach ($this->campos_venda_lote as $k => $v) {

                        $arr_insert[$k] = addslashes(utf8_encode(remove_acentos($data[array_search($v, $IdxCamposInsert)])));
                    }

                    $arr_insert["forma_pagamento_csv"] =  remove_acentos($arr_insert["forma_pagamento_csv"]);
                    $arr_insert["forma_pagamento_csv"] = array_search($arr_insert["forma_pagamento_csv"], $arr_forma_pag);

                    //$arr_insert["pag_banco_csv"] = ($arr_insert["pag_banco_csv"] == "-" ? 'null' : "'" . $arr_insert["pag_banco_csv"] . "'");

                    $arr_insert["pag_banco_csv"] =  remove_acentos($arr_insert["pag_banco_csv"]);
                    //$arr_insert["pag_banco_csv"] = array_search($arr_insert["pag_banco_csv"], $arr_bancos);
                    $arr_insert["pag_banco_csv"] = ($arr_insert["pag_banco_csv"] == "-" ? 'null' : "'" . array_search($arr_insert["pag_banco_csv"], $arr_bancos) . "'");
                    /*if ($arr_insert["pag_banco_csv"] != "") {
                        print_r($arr_insert["pag_banco_csv"]);
                        exit;
                    }*/


                    $arr_insert["combo_contratado_csv"] =  remove_acentos($arr_insert["combo_contratado_csv"]);
                    $arr_insert["combo_contratado_csv"] = array_search($arr_insert["combo_contratado_csv"], $arr_combo);

                    $arr_insert["banda_larga_velocidade_csv"] =  remove_acentos($arr_insert["banda_larga_velocidade_csv"]);
                    $arr_insert["banda_larga_velocidade_csv"] = array_search($arr_insert["banda_larga_velocidade_csv"], $arr_planos);

                    $arr_insert["plano_tv_csv"] =  remove_acentos($arr_insert["plano_tv_csv"]);
                    $arr_insert["plano_tv_csv"] = array_search($arr_insert["plano_tv_csv"], $arr_tv);

                    $arr_insert['dt_venda_csv'] = substr($arr_insert['dt_venda_csv'], 6, 4) . "-" .
                        substr($arr_insert['dt_venda_csv'], 3, 2) . "-" .
                        substr($arr_insert['dt_venda_csv'], 0, 2);
                    $arr_insert['dt_nascimento_csv'] = TransformaData($arr_insert['dt_nascimento_csv'], 'en');
                    $arr_insert['cpf_cnpj_csv'] = preg_replace('/[^0-9]/is', '', $arr_insert['cpf_cnpj_csv']);
                    $arr_insert['contato_principal_csv'] = preg_replace('/[^0-9]/is', '', $arr_insert['contato_principal_csv']);
                    $arr_insert['contato_secundario_csv'] = preg_replace('/[^0-9]/is', '', $arr_insert['contato_secundario_csv']);
                    $arr_insert['nome_cliente_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['nome_cliente_csv']);
                    $arr_insert['orgao_expedidor_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['orgao_expedidor_csv']);
                    $arr_insert['nome_mae_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['nome_mae_csv']);
                    $arr_insert['obs_vendedor_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['obs_vendedor_csv']);
                    $arr_insert['logradouro_instalacao_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['logradouro_instalacao_csv']);
                    $arr_insert['bairro_instalacao_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['bairro_instalacao_csv']);
                    $arr_insert['cidade_instalacao_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['cidade_instalacao_csv']);
                    $arr_insert['ref_instalacao_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['ref_instalacao_csv']);
                    $arr_insert['refe_complemento1_tipo_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['refe_complemento1_tipo_csv']);
                    $arr_insert['refe_complemento1_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['refe_complemento1_csv']);
                    $arr_insert['refe_complemento2_tipo_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['refe_complemento2_tipo_csv']);
                    $arr_insert['refe_complemento2_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['refe_complemento2_csv']);
                    $arr_insert['refe_complemento3_tipo_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['refe_complemento3_tipo_csv']);
                    $arr_insert['refe_complemento3_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['refe_complemento3_csv']);
                    $arr_insert['genero_csv'] = ($arr_insert['genero_csv'] = 'FEMININO' ? 'F' : 'M');
                    $arr_insert['pag_conta_online_csv'] = ($arr_insert['pag_conta_online_csv'] = 'Sim' ? 'S' : 'N');

                    $contagem_cliente = $this->vendas_model->where('cpf_cnpj_csv', $arr_insert['cpf_cnpj_csv'])->findAll();
                    if (count($contagem_cliente) > 0) continue;
                    $updVendas = "  INSERT INTO vendas
                                        (dt_venda_csv,
                                        dt_nascimento_csv,
                                        cpf_cnpj_csv,
                                        contato_principal_csv,
                                        rg_csv,
                                        num_instalacao_csv,
                                        contato_secundario_csv,
                                        forma_pagamento_csv,
                                        nome_cliente_csv,
                                        orgao_expedidor_csv,
                                        nome_mae_csv,
                                        uf_instalacao_csv,
                                        pag_agencia_csv,
                                        pag_conta_csv,
                                        pag_operacao_csv,
                                        email_csv,
                                        pag_banco_csv,
                                        genero_csv,
                                        pag_agencia_digito_csv,
                                        pag_conta_online_csv,
                                        plano_tv_csv,
                                        vencimento_csv,
                                        cep_instalacao_csv,
                                        combo_contratado_csv,
                                        matricula_vendedor_csv,
                                        obs_vendedor_csv,
                                        logradouro_instalacao_csv,
                                        bairro_instalacao_csv,
                                        cidade_instalacao_csv,
                                        ref_instalacao_csv,
                                        banda_larga_velocidade_csv,
                                        refe_complemento1_tipo_csv,
                                        refe_complemento2_tipo_csv,
                                        refe_complemento2_csv,
                                        refe_complemento3_tipo_csv,
                                        refe_complemento3_csv,
                                        insert_usuario_id,
                                        insert_data)
                                        VALUES(
                                            '{$arr_insert['dt_venda_csv']}',
                                            '{$arr_insert['dt_nascimento_csv']}',
                                            '{$arr_insert['cpf_cnpj_csv']}',
                                            '{$arr_insert['contato_principal_csv']}',
                                            '{$arr_insert['rg_csv']}',
                                            '{$arr_insert['num_instalacao_csv']}',
                                            '{$arr_insert['contato_secundario_csv']}',
                                            '{$arr_insert['forma_pagamento_csv']}',
                                            '{$arr_insert['nome_cliente_csv']}',
                                            '{$arr_insert['orgao_expedidor_csv']}',
                                            '{$arr_insert['nome_mae_csv']}',
                                            '{$arr_insert['uf_instalacao_csv']}',
                                            '{$arr_insert['pag_agencia_csv']}',
                                            '{$arr_insert['pag_conta_csv']}',
                                            '{$arr_insert['pag_operacao_csv']}',
                                            '{$arr_insert['email_csv']}',
                                            {$arr_insert['pag_banco_csv']},
                                            '{$arr_insert['genero_csv']}',
                                            '{$arr_insert['pag_agencia_digito_csv']}',
                                            '{$arr_insert['pag_conta_online_csv']}',
                                            '{$arr_insert['plano_tv_csv']}',
                                            '{$arr_insert['vencimento_csv']}',
                                            '{$arr_insert['cep_instalacao_csv']}',
                                            '{$arr_insert['combo_contratado_csv']}',
                                            '{$arr_insert['matricula_vendedor_csv']}',
                                            '{$arr_insert['obs_vendedor_csv']}',
                                            '{$arr_insert['logradouro_instalacao_csv']}',
                                            '{$arr_insert['bairro_instalacao_csv']}',
                                            '{$arr_insert['cidade_instalacao_csv']}',
                                            '{$arr_insert['ref_instalacao_csv']}',
                                            '{$arr_insert['banda_larga_velocidade_csv']}',
                                            '{$arr_insert['refe_complemento1_tipo_csv']}',
                                            '{$arr_insert['refe_complemento2_tipo_csv']}',
                                            '{$arr_insert['refe_complemento2_csv']}',
                                            '{$arr_insert['refe_complemento3_tipo_csv']}',
                                            '{$arr_insert['refe_complemento3_csv']}',
                                            '$usuario_logado',
                                            '{$data_hora}');";

                    //print_r($updVendas);
                    //exit;
                    $this->db->query($updVendas);
                    $aff = $this->db->affectedRows();
                }
                if($aff > 0) $this->envio_email("venda_lote");
            }
        }
        $this->db->transComplete();

        echo json_encode($this->resposta_upload);
    }
    /*public function upload_venda_lote()
    {
        $formas_pagamento = $this->FormaPagamento_Model->findAll();
        $bancos =  $this->bancos_Model->findAll();
        $bancos =  $this->bancos_Model->findAll();
        $combo = $this->ComboPlanos_Model->findAll();
        $planos_fibra = $this->planos_fibra_model->findAll();
        $planos_tv = $this->planos_tv_model->findAll();

        $arr_forma_pag = array_column($formas_pagamento, 'descricao', 'id');
        $arr_bancos = array_column($bancos, 'descricao', 'id');
        $arr_combo = array_column($combo, 'descricao', 'id');
        $arr_planos = array_column($planos_fibra, 'descricao', 'id');
        $arr_tv = array_column($planos_tv, 'descricao', 'id');


        $paramVendaLoteCsv = new ParametroVendaLoteCsv_Model();
        $this->campos_venda_lote = $paramVendaLoteCsv->first();
        unset($this->campos_venda_lote['id']);

        $this->vendas_model = new Vendas_Model();
        $usuario_logado = session()->get('id_usuario');
        $data_hora =  date('Y-m-d H:i:s');
		
        $this->db = db_connect();
     //   $this->campos_venda_lote = array_filter($this->campos_venda_lote);

        $files = $_FILES['files_venda_lote'];

        $this->db->transStart();

        foreach ($files['tmp_name'] as $m => $n) {
            $csv_handle = fopen($n, "r");
            $reg = 0;
            
            $csv_head = array_map(function ($field) {
                return (strtoupper(remove_acentos($field)));
            }, fgetcsv($csv_handle, 0, ","));
            unset($csv_head[0]);
            $IdxCamposInsert = array_intersect($csv_head, $this->campos_venda_lote);

            $campos_uteis = array_values($this->campos_venda_lote);
            $campos_dif = array_diff($campos_uteis, $csv_head);
			print_r($campos_dif); exit;
            if (count($campos_dif)) {
                $this->resposta_upload['message'] = 'FAVOR VERIFICAR SEUS ARQUIVOS OS CAMPOS: (' . implode(',', $campos_dif) . ' ) NAO ESTAO PARAMETRIZADOS CORRETAMENTE';
                $this->resposta_upload['error'] = 1;
            } else {
                while (($data = fgetcsv($csv_handle, 5000, ",")) !== FALSE) {
                    if ($reg == 1) continue;
                    #A query abaixo não permite a inclusão por lote, de um cliente que já tenha CPF na base.
                    foreach ($this->campos_venda_lote as $k => $v) {

                        $arr_insert[$k] = addslashes(utf8_encode(remove_acentos($data[array_search($v, $IdxCamposInsert)])));
                    }

                    $arr_insert["forma_pagamento_csv"] =  remove_acentos($arr_insert["forma_pagamento_csv"]);
                    $arr_insert["forma_pagamento_csv"] = array_search($arr_insert["forma_pagamento_csv"], $arr_forma_pag);

                    //$arr_insert["pag_banco_csv"] = ($arr_insert["pag_banco_csv"] == "-" ? 'null' : "'" . $arr_insert["pag_banco_csv"] . "'");

                    $arr_insert["pag_banco_csv"] =  remove_acentos($arr_insert["pag_banco_csv"]);
                    //$arr_insert["pag_banco_csv"] = array_search($arr_insert["pag_banco_csv"], $arr_bancos);
                    $arr_insert["pag_banco_csv"] = ($arr_insert["pag_banco_csv"] == "-" ? 'null' : "'" . array_search($arr_insert["pag_banco_csv"], $arr_bancos) . "'");
                   


                    $arr_insert["combo_contratado_csv"] =  remove_acentos($arr_insert["combo_contratado_csv"]);
                    $arr_insert["combo_contratado_csv"] = array_search($arr_insert["combo_contratado_csv"], $arr_combo);

                    $arr_insert["banda_larga_velocidade_csv"] =  remove_acentos($arr_insert["banda_larga_velocidade_csv"]);
                    $arr_insert["banda_larga_velocidade_csv"] = array_search($arr_insert["banda_larga_velocidade_csv"], $arr_planos);

                    $arr_insert["plano_tv_csv"] =  remove_acentos($arr_insert["plano_tv_csv"]);
                    $arr_insert["plano_tv_csv"] = array_search($arr_insert["plano_tv_csv"], $arr_tv);

                    $arr_insert['dt_venda_csv'] = substr($arr_insert['dt_venda_csv'], 6, 4) . "-" .
                        substr($arr_insert['dt_venda_csv'], 3, 2) . "-" .
                        substr($arr_insert['dt_venda_csv'], 0, 2);
                    $arr_insert['dt_nascimento_csv'] = TransformaData($arr_insert['dt_nascimento_csv'], 'en');
                    $arr_insert['cpf_cnpj_csv'] = preg_replace('/[^0-9]/is', '', $arr_insert['cpf_cnpj_csv']);
                    $arr_insert['contato_principal_csv'] = preg_replace('/[^0-9]/is', '', $arr_insert['contato_principal_csv']);
                    $arr_insert['contato_secundario_csv'] = preg_replace('/[^0-9]/is', '', $arr_insert['contato_secundario_csv']);
                    $arr_insert['nome_cliente_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['nome_cliente_csv']);
                    $arr_insert['orgao_expedidor_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['orgao_expedidor_csv']);
                    $arr_insert['nome_mae_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['nome_mae_csv']);
                    $arr_insert['obs_vendedor_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['obs_vendedor_csv']);
                    $arr_insert['logradouro_instalacao_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['logradouro_instalacao_csv']);
                    $arr_insert['bairro_instalacao_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['bairro_instalacao_csv']);
                    $arr_insert['cidade_instalacao_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['cidade_instalacao_csv']);
                    $arr_insert['ref_instalacao_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['ref_instalacao_csv']);
                    $arr_insert['refe_complemento1_tipo_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['refe_complemento1_tipo_csv']);
                    $arr_insert['refe_complemento1_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['refe_complemento1_csv']);
                    $arr_insert['refe_complemento2_tipo_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['refe_complemento2_tipo_csv']);
                    $arr_insert['refe_complemento2_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['refe_complemento2_csv']);
                    $arr_insert['refe_complemento3_tipo_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['refe_complemento3_tipo_csv']);
                    $arr_insert['refe_complemento3_csv'] = preg_replace('/[^A-Za-z0-9.!? ]/', '', $arr_insert['refe_complemento3_csv']);
                    $arr_insert['genero_csv'] = ($arr_insert['genero_csv'] = 'FEMININO' ? 'F' : 'M');
                    $arr_insert['pag_conta_online_csv'] = ($arr_insert['pag_conta_online_csv'] = 'Sim' ? 'S' : 'N');

                    $contagem_cliente = $this->vendas_model->where('cpf_cnpj_csv', $arr_insert['cpf_cnpj_csv'])->findAll();
                    if (count($contagem_cliente) > 0) continue;
                    $updVendas = "  INSERT INTO vendas
                                        (dt_venda_csv,
                                        dt_nascimento_csv,
                                        cpf_cnpj_csv,
                                        contato_principal_csv,
                                        rg_csv,
                                        num_instalacao_csv,
                                        contato_secundario_csv,
                                        forma_pagamento_csv,
                                        nome_cliente_csv,
                                        orgao_expedidor_csv,
                                        nome_mae_csv,
                                        uf_instalacao_csv,
                                        pag_agencia_csv,
                                        pag_conta_csv,
                                        pag_operacao_csv,
                                        email_csv,
                                        pag_banco_csv,
                                        genero_csv,
                                        pag_agencia_digito_csv,
                                        pag_conta_online_csv,
                                        plano_tv_csv,
                                        vencimento_csv,
                                        cep_instalacao_csv,
                                        combo_contratado_csv,
                                        matricula_vendedor_csv,
                                        obs_vendedor_csv,
                                        logradouro_instalacao_csv,
                                        bairro_instalacao_csv,
                                        cidade_instalacao_csv,
                                        ref_instalacao_csv,
                                        banda_larga_velocidade_csv,
                                        refe_complemento1_tipo_csv,
                                        refe_complemento2_tipo_csv,
                                        refe_complemento2_csv,
                                        refe_complemento3_tipo_csv,
                                        refe_complemento3_csv,
                                        insert_usuario_id,
                                        insert_data)
                                        VALUES(
                                            '{$arr_insert['dt_venda_csv']}',
                                            '{$arr_insert['dt_nascimento_csv']}',
                                            '{$arr_insert['cpf_cnpj_csv']}',
                                            '{$arr_insert['contato_principal_csv']}',
                                            '{$arr_insert['rg_csv']}',
                                            '{$arr_insert['num_instalacao_csv']}',
                                            '{$arr_insert['contato_secundario_csv']}',
                                            '{$arr_insert['forma_pagamento_csv']}',
                                            '{$arr_insert['nome_cliente_csv']}',
                                            '{$arr_insert['orgao_expedidor_csv']}',
                                            '{$arr_insert['nome_mae_csv']}',
                                            '{$arr_insert['uf_instalacao_csv']}',
                                            '{$arr_insert['pag_agencia_csv']}',
                                            '{$arr_insert['pag_conta_csv']}',
                                            '{$arr_insert['pag_operacao_csv']}',
                                            '{$arr_insert['email_csv']}',
                                            {$arr_insert['pag_banco_csv']},
                                            '{$arr_insert['genero_csv']}',
                                            '{$arr_insert['pag_agencia_digito_csv']}',
                                            '{$arr_insert['pag_conta_online_csv']}',
                                            '{$arr_insert['plano_tv_csv']}',
                                            '{$arr_insert['vencimento_csv']}',
                                            '{$arr_insert['cep_instalacao_csv']}',
                                            '{$arr_insert['combo_contratado_csv']}',
                                            '{$arr_insert['matricula_vendedor_csv']}',
                                            '{$arr_insert['obs_vendedor_csv']}',
                                            '{$arr_insert['logradouro_instalacao_csv']}',
                                            '{$arr_insert['bairro_instalacao_csv']}',
                                            '{$arr_insert['cidade_instalacao_csv']}',
                                            '{$arr_insert['ref_instalacao_csv']}',
                                            '{$arr_insert['banda_larga_velocidade_csv']}',
                                            '{$arr_insert['refe_complemento1_tipo_csv']}',
                                            '{$arr_insert['refe_complemento2_tipo_csv']}',
                                            '{$arr_insert['refe_complemento2_csv']}',
                                            '{$arr_insert['refe_complemento3_tipo_csv']}',
                                            '{$arr_insert['refe_complemento3_csv']}',
                                            '$usuario_logado',
                                            '{$data_hora}');";

                    //print_r($updVendas);
                    //exit;
                    $this->db->query($updVendas);
                }
            }
        }
        $this->db->transComplete();

        echo json_encode($this->resposta_upload);
    }*/
	
	
    public function DataTable()
    {

        $cod_interno = session()->get('cod_interno');
        $cod_usuario = session()->get('id_usuario');
        #DETERMINA O NIVEL DE INFORMACAO RECEBIDA
        if ($cod_interno == 970) {
            $where_complemento = " WHERE id_supervisor =  '{$cod_usuario}' ";
        } else {
            $where_complemento = "";
        }
    
        $sql = "SELECT
                    A.id,
                    'Ficha' teste,
                    SUBSTR(nome_cliente_csv, 1, 15) nome_cliente,
                    CASE
                        WHEN dt_agendamento IS NOT NULL AND  A.zap_agendamento1 IS NULL THEN 1
                        WHEN dt_agendamento IS NOT NULL AND  A.zap_agendamento1 IS NOT NULL THEN 2
                        WHEN dt_agendamento IS NULL AND  A.zap_agendamento1 IS NULL THEN 3
                    END zap_1,
                    CASE
                        WHEN dt_reagendamento_1 IS NOT NULL AND  A.zap_reagendamento_1 IS NULL THEN 1
                        WHEN dt_reagendamento_1 IS NOT NULL AND  A.zap_reagendamento_1 IS NOT NULL THEN 2
                        WHEN dt_reagendamento_1 IS NULL AND  A.zap_reagendamento_1 IS NULL THEN 3
                    END zap_2,
                    CASE
                        WHEN dt_reagendamento_2 IS NOT NULL AND  A.zap_reagendamento_2 IS NULL THEN 1
                        WHEN dt_reagendamento_2 IS NOT NULL AND  A.zap_reagendamento_2 IS NOT NULL THEN 2
                        WHEN dt_reagendamento_2 IS NULL AND  A.zap_reagendamento_2 IS NULL THEN 3
                    END zap_3,
                    CASE
                        WHEN dt_reagendamento_3 IS NOT NULL AND  A.zap_reagendamento_3 IS NULL THEN 1
                        WHEN dt_reagendamento_3 IS NOT NULL AND  A.zap_reagendamento_3 IS NOT NULL THEN 2
                        WHEN dt_reagendamento_3 IS NULL AND  A.zap_reagendamento_3 IS NULL THEN 3
                    END zap_4,
                    CONCAT(COALESCE(audio_audit_quality_1, ''), '|', COALESCE(A.audio_audit_quality_2,'')) auditoria,
					A.forma_pagamento_csv,
                    SUBSTR(A.status_bov, 1, 15) status_bov,
                    SUBSTR(B.descricao, 1, 15) desc_status_ativacao,
                    CASE
                        WHEN dt_agendamento IS NOT NULL THEN  DATE_FORMAT(A.dt_agendamento, '%d/%m/%Y')
                        ELSE ''
                    END dt_agendamento,
                    CASE
                        WHEN dt_reagendamento_1 IS NOT NULL THEN  DATE_FORMAT(A.dt_reagendamento_1, '%d/%m/%Y')
                        ELSE ''
                    END dt_reagendamento_1, 
                    CASE
                        WHEN dt_reagendamento_2 IS NOT NULL THEN  DATE_FORMAT(A.dt_reagendamento_2, '%d/%m/%Y')
                        ELSE ''
                    END dt_reagendamento_2, 
                    CASE
                        WHEN dt_reagendamento_3 IS NOT NULL THEN  DATE_FORMAT(A.dt_reagendamento_3, '%d/%m/%Y')
                        ELSE ''
                    END dt_reagendamento_3, 
                   -- DATE_FORMAT(A.dt_venda_csv, '%d/%m/%Y') dt_venda_csv,
                     A.dt_venda_csv,
                    A.num_os,
                    A.cpf_cnpj_csv,
                    A.nome_cliente_csv
                FROM vendas A
                LEFT JOIN status_ativacoes B ON A.status_ativacao = B.id
                $where_complemento";
                //print_r($sql); exit;

        $dt = new Datatables_server_side([
            'tb' => 'vendas',
            'bt_excluir' => false,
            'cols' => [
                "teste", "auditoria", "zap_1", "zap_2",  "zap_3",  "zap_4", 
                "dt_agendamento", "dt_reagendamento_1", "dt_reagendamento_2", "dt_reagendamento_3",
                "status_bov", "desc_status_ativacao", "dt_venda_csv",
                "num_os", "cpf_cnpj_csv", "nome_cliente"
            ],
            'formata_coluna' => [
                0 => function ($col, $lin) {
                    $id = base64_encode($lin['id']);
                    $link_pdf = "<button type='button' class='btn btn-outline-danger btn-sm' onclick='gerar_pdf(\"$id\")'><i class='far fa-file-pdf'></i></button>";
                    return $link_pdf;
                },
                1 => function ($col, $lin) {
                    $coluna = explode("|", $col);
                    $auditoria_1 = $this->auditoria($coluna[0]);
                    $auditoria_2 = $this->auditoria($coluna[1]);

                    return  $auditoria_1 . "&nbsp;&nbsp;" . $auditoria_2;
                },
                2 => function ($col, $lin) {
                    $id = base64_encode($lin['id']);
                    if ($col == 1) {
                        $agendamento = '<a class="btn btn-success btn-xs text-white whatsapp" target="_blank" onclick="atualiza_zap(\'' . $id . '\',1)">
                        <i class="fab fa-whatsapp"></i></a>';
                    } elseif ($col == 2) {
                        $agendamento = '<i class=" fas fa-thumbs-up" title="Ja enviado" style="color:green"></i>';
                    } else {
                        $agendamento = '<i class="fab fa-whatsapp" title="Sem agendamento"></i>';
                    }
                    return  $agendamento;
                },


                3 => function ($col, $lin) {
                    $id = base64_encode($lin['id']);
                    if ($col == 1) {
                        $agendamento = '<a class="btn btn-success btn-xs text-white whatsapp" target="_blank" onclick="atualiza_zap(\'' . $id . '\',2)">
                        <i class="fab fa-whatsapp"></i></a>';
                    } elseif ($col == 2) {
                        $agendamento = '<i class=" fas fa-thumbs-up" title="Ja enviado" style="color:green"></i>';
                    } else {
                        $agendamento = '<i class="fab fa-whatsapp" title="Sem agendamento"></i>';
                    }
                    return  $agendamento;
                    //return $col;
                },

                4 => function ($col, $lin) {
                    $id = base64_encode($lin['id']);
                    if ($col == 1) {
                        $agendamento = '<a class="btn btn-success btn-xs text-white whatsapp" target="_blank" onclick="atualiza_zap(\'' . $id . '\',3)">
                        <i class="fab fa-whatsapp"></i></a>';
                    } elseif ($col == 2) {
                        $agendamento = '<i class=" fas fa-thumbs-up" title="Ja enviado" style="color:green"></i>';
                    } else {
                        $agendamento = '<i class="fab fa-whatsapp" title="Sem agendamento"></i>';
                    }
                    return  $agendamento;
                },
                5  => function ($col, $lin) {
                    $id = base64_encode($lin['id']);
                    if ($col == 1) {
                        $agendamento = '<a class="btn btn-success btn-xs text-white whatsapp" target="_blank" onclick="atualiza_zap(\'' . $id . '\',4)">
                        <i class="fab fa-whatsapp"></i></a>';
                    } elseif ($col == 2) {
                        $agendamento = '<i class=" fas fa-thumbs-up" title="Ja enviado" style="color:green"></i>';
                    } else {
                        $agendamento = '<i class="fab fa-whatsapp" title="Sem agendamento"></i>';
                    }
                    return  $agendamento;
                },

                12 => function ($col, $lin) {
                    $dt_ativacao = date('d/m/Y', strtotime($col));
                    return  $dt_ativacao;
                },
                /*10 => function ($col, $lin) {
                    $tipo_mascara = (strlen($col) > 11 ? '##.###.###/####-##' : '###.###.###-##');
                    $cpf_cnpj = mascaras_uteis($col, $tipo_mascara);
                    return  $cpf_cnpj;
                },*/

            ]
        ]);
        $dt->complexQuery($sql);
    }
    public function auditoria($audi)
    {
        $auditoria = ($audi != '' ? "<a href='" . base_url("ven/ven007/download?arquivo=" . $audi) . "' class='btn btn-primary btn-xs' target='_blank' download>
                    <i class='fas fa-download'></i></a>" : '');
        return $auditoria;
    }
    public function posUpd($dados)
    {
        $this->grava_auditoria($dados["CPF_CNPJ_CSV"], date('d_m_Y_H_i_s') . "_1", $_FILES['AUDIO_AUDIT_QUALITY_1'], $dados['ID'], 'audio_audit_quality_1');
        $this->grava_auditoria($dados["CPF_CNPJ_CSV"], date('d_m_Y_H_i_s') . "_2", $_FILES['AUDIO_AUDIT_QUALITY_2'], $dados['ID'], 'audio_audit_quality_2');
    }
    public function posIns($f_dados, $key)
    {
        $this->grava_auditoria($f_dados["CPF_CNPJ_CSV"], date('d_m_Y_H_i_s') . "_1", $_FILES['AUDIO_AUDIT_QUALITY_1'], $key, 'audio_audit_quality_1');
        $this->grava_auditoria($f_dados["CPF_CNPJ_CSV"], date('d_m_Y_H_i_s') . "_2", $_FILES['AUDIO_AUDIT_QUALITY_2'], $key, 'audio_audit_quality_2');
    }
    public function preInsUpd($dados)

    {

        //print_r($dados); exit;
        $dados["CPF_CNPJ_CSV"] = preg_replace('/[^0-9]/is', '', $dados["CPF_CNPJ_CSV"]);
        $dados["CEP_INSTALACAO_CSV"] = preg_replace('/[^0-9]/is', '', $dados["CEP_INSTALACAO_CSV"]);
        $dados["FATURAMENTO"] = remove_separador_milhar($dados["FATURAMENTO"]);

        $dados["CONTATO_PRINCIPAL_CSV"] =  preg_replace('/[^0-9]/is', '',  $dados["CONTATO_PRINCIPAL_CSV"]);
        $dados["CONTATO_SECUNDARIO_CSV"] =  preg_replace('/[^0-9]/is', '', $dados["CONTATO_SECUNDARIO_CSV"]);

        return $dados;
    }
    public function preIns($dados)
    {
        $dados["insert_usuario_id"] = session()->get('id_usuario');
        $dados["insert_data"] =  date('Y-m-d H:i:s');
        return $dados;
    }
    public function post_edicao($row)
    {
        $row['SUPERVISOR'] = $this->ProcuraSupervisor(1, $row['EQUIPE'])['supervisor'];
        $row['VENDEDORES'] = $this->ProcuraSupervisor(1, $row['EQUIPE'])['vendedor'];
        #Tipo de mascara para CPF / CNPJ
        $tipo_mascara = (strlen($row['CPF_CNPJ_CSV']) > 11 ? '##.###.###/####-##' : '###.###.###-##');
        $row['CPF_CNPJ_CSV'] = mascaras_uteis($row['CPF_CNPJ_CSV'],  $tipo_mascara);
        #FIM
        $row["DT_NASCIMENTO_CSV"] = TransformaData($row["DT_NASCIMENTO_CSV"], 'pt');
        $row["FATURAMENTO"] = formata_monetario($row["FATURAMENTO"]);
        $row["CEP_INSTALACAO_CSV"] = mascaras_uteis($row["CEP_INSTALACAO_CSV"], '##.###-###');
        $row["CONTATO_PRINCIPAL_CSV"] = mascaras_uteis($row["CONTATO_PRINCIPAL_CSV"], '(##) #########');
        $row["CONTATO_SECUNDARIO_CSV"] = mascaras_uteis($row["CONTATO_SECUNDARIO_CSV"], '(##) #########');
        //print_r($row);
        //exit;
        return $row;
    }

    public function valida_campos()
    {
        $rules = [
            'ID_VENDEDOR'    => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Vendedor invalido.'
                ]
            ],
            'CPF_CNPJ_CSV' => [
                'rules' =>  'required|validaTipoPessoa[CPF_CNPJ_CSV]',
                'errors' => [
                    'validaTipoPessoa' => 'CPF ou CNPJ invalido.'
                ]
            ],

            'DT_ATIVACAO'    => [
                'rules'  => 'required|valid_date',
                'errors' => [
                    'valid_date' => 'Data ativação invalida.'
                ]
            ],
            'DT_NASCIMENTO_CSV'    => [
                'rules'  => 'required|valid_date',
                'errors' => [
                    'valid_date' => 'Data nascimento invalida.'
                ]
            ],
            'DT_VENDA_CSV'    => [
                'rules'  => 'required|valid_date',
                'errors' => [
                    'valid_date' => 'Data venda invalida.'
                ]
            ],
            'CONTATO_PRINCIPAL_CSV'    => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Contato invalido.'
                ]
            ],
            'STATUS_ATIVACAO'    => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Status invalido.'
                ]
            ],

            'NOME_CLIENTE_CSV'    => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Nome invalido'
                ]
            ],

            'CEP_INSTALACAO_CSV'    => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'CEP invalido'
                ]
            ],

            'REF_INSTALACAO_CSV'    => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Referencia invalida'
                ]
            ],
            'PAG_CONTA_ONLINE_CSV'    => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Campo invalido'
                ]
            ],
            'NUM_OS'    => [
                'rules'  => 'is_unique[vendas.num_os,id,{ID}]',
                'errors' => [
                    'is_unique' => 'Já existe este numero de OS.'
                ]
            ],

        ];
        return $this->validate($rules);
    }

    public function ProcuraSupervisor($tipo = null, $id_equipe = null)
    {
        if ($tipo == null) {
            $id_equipe = $this->request->getPost('id');
        }

        $equipe_vendas = $this->Equipe_Model->equipe_supervisor(970, $id_equipe);

        //print_r($equipe_vendas);
        //exit;
        $vendedor = $this->Equipe_Model->Vendedores(960, $id_equipe);
        $option = '<option value="" selected="selected">Selecione...</option>';
        foreach ($vendedor  as $v) {
            $option .= "<option value='" . $v["id"] . "'>" . strtoupper($v["vendedor"]) . "</option>";
        }
        $supervisor_vendedor =  [
            'id_supervisor' => strtoupper($equipe_vendas[0]['id_supervisor']),
            'supervisor' => strtoupper($equipe_vendas[0]['supervisor']),
            'vendedor' => $option
        ];

        //print_r($supervisor_vendedor);
        //exit;
        if ($tipo == null) {
            echo json_encode($supervisor_vendedor);
        } else {
            return $supervisor_vendedor;
        }
    }
    public function BuscaFaturamento()
    {
        $velocidade = $this->request->getPost('velocidade');
        $tv = $this->request->getPost('tv');
        $data_ativacao = $this->request->getPost('data_ativacao');
        $busca_faturamento = $this->PlanosOperadora_model->BuscaFaturamento($velocidade, $tv, $data_ativacao);
        echo json_encode(['faturamento' => number_format($busca_faturamento, 2, ",", ".")]);
    }

    public function grava_auditoria($cpf_cliente, $dt_corrente, $campo_auditoria, $id, $campo)
    {
        if (isset($campo_auditoria) && $campo_auditoria["name"] !== "") {
            helper('text');
            $empresa = session()->get('cliente_db');
            $auditoria = $campo_auditoria;
            $pasta_destino = WRITEPATH .  $empresa . "/uploads/AUDITORIA/";
            if (!file_exists($pasta_destino)) {
                mkdir($pasta_destino, 0777, true);
            }
            $extensao = pathinfo($auditoria["name"], PATHINFO_EXTENSION);
            $nome_audio_auditoria = random_string('alnum', 11) . "-" . $dt_corrente . "." . $extensao;
            move_uploaded_file($auditoria["tmp_name"], $pasta_destino . "/" . $nome_audio_auditoria);
            $caminho_final =  $nome_audio_auditoria;
            #############
            $this->vendas_model->update($id, [$campo => $caminho_final]);
        }
    }

    public function download()
    {
        helper('text'); //usado para gerar o nome aleatorio
        $empresa = session()->get('cliente_db');
        $pasta_destino = WRITEPATH .  $empresa . "/uploads/AUDITORIA/";
        $arquivo = $this->request->getGet('arquivo');
        return $this->response->download($pasta_destino . "/" . $arquivo, null);
    }
    public function atualiza_zap()
    {
        $numero_telefone = "";
        $id = base64_decode($this->request->getPost('id'));
        $tipo = $this->request->getPost('tipo');
        switch ($tipo) {
            case 1:
                $campo = "zap_agendamento1";
                break;
            case 2:
                $campo = "zap_reagendamento_1";
                break;
            case 3:
                $campo = "zap_reagendamento_2";
                break;
            case 4:
                $campo = "zap_reagendamento_3";
                break;
            default:
                "Invalido";
        }
        $this->vendas_model->update($id, [$campo => 1]);
        $modeloVendas = $this->vendas_model;
        $de_para_msg = ($tipo == 1 ? "zap_agendamento1" : "zap_reagendamento_1");
        $retorna_msg = $this->WhatsappMsg_model->findColumn($de_para_msg)[0];

        $row =  $modeloVendas->where('id', $id)->findAll()[0];
        $row["nome_cliente_csv"] = explode(" ", ucfirst(strtolower($row["nome_cliente_csv"])))[0];
        $dt_reagendamento = "DT_REAGENDAMENTO_" . ($tipo - 1);
        $tuno_reagendamento = "TURNO_REAGENDAMENTO_" . ($tipo - 1);
        foreach (array_change_key_case($row, CASE_UPPER) as $k => $v) {

            if ($k == $dt_reagendamento) {
                $v = date("d/m/Y", strtotime($v));
                $retorna_msg = str_replace("<DT_REAGENDAMENTO>", $v, $retorna_msg);
            }
            if ($k == $tuno_reagendamento) {
                $v = $this->turno[$v];
                $retorna_msg = str_replace("<TURNO_REAGENDAMENTO>", $v, $retorna_msg);
            }
            if ($k == "DT_AGENDAMENTO") {
                $v = date("d/m/Y", strtotime($v));
            }
            if ($k == "TURNO_AGENDAMENTO") {
                $v = $this->turno[$v];
            }

            $retorna_msg = str_replace("<" . $k . ">", $v, $retorna_msg);
        }
        //Mensagem
        $retorna_telefone = filter_var($row["contato_principal_csv"], FILTER_SANITIZE_NUMBER_INT);
        echo json_encode(["telefone" => $retorna_telefone, "mensagem" => $retorna_msg]);
    }


    public function gerar_pdf()
    {
        $id =  base64_decode($this->request->getGet('id'));
        $rows = $this->mquery($id);
        $pdf = new PDF_MC_Table();
        $pdf->titulo = utf8_decode("DETALHES DA VENDA - " . $id);
        $pdf->AddPage('P');
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial', '', 8);

        $supervisor = $this->Equipe_Model->equipe_supervisor(970, $rows->equipe_id)[0]["supervisor"];
        $equipe = $this->Equipe_Model->equipe_supervisor(970, $rows->equipe_id)[0]["equipe"];
        $dt_instalacao = ($rows->dt_instalacao != null ? date("d/m/Y", strtotime($rows->dt_instalacao)) : 'S/ instalacao');
        $dt_ativacao = ($rows->dt_ativacao != null ? date("d/m/Y", strtotime($rows->dt_ativacao)) : '');
        $dt_retorno_tratamento = ($rows->dt_retorno_tratamento != null ? date("d/m/Y", strtotime($rows->dt_retorno_tratamento)) : '');
        $status_tratamento = ["T" => "TRATADO", "ET" => "EM TRATAMENTO", "NT" => "NÃO TRATADO", "" => ""];
        $auditoria = ($rows->auditoria != "") ? "SIM" : "NAO";
        $dt_nascimento = ($rows->dt_nascimento_csv != null ? date("d/m/Y", strtotime($rows->dt_nascimento_csv)) : '');
        $tipo_mascara_cpf = (strlen($rows->cpf_cnpj_csv) > 11 ? '##.###.###/####-##' : '###.###.###-##');
        $cpf_cnpj = mascaras_uteis($rows->cpf_cnpj_csv, $tipo_mascara_cpf);
        $conta_online = ($rows->pag_conta_online_csv == 'S' ? 'SIM' : 'NAO');
        #Primeiro bloco
        $pdf->Cell(20, 6, 'VENDEDOR:', 0, 0, 'L');
        $pdf->Cell(40, 6, strtoupper(remove_acentos($rows->nome_vendedor)), 0, 0, 'L');
        $pdf->Cell(110, 6, 'VENDA:', 0, 0, 'R');
        $pdf->Cell(20, 6, date("d/m/Y", strtotime($rows->dt_venda)), 0, 1, 'R');
        $pdf->Cell(20, 6, 'SUPERVISOR:', 0, 0, 'L');
        $pdf->Cell(40, 6, strtoupper(remove_acentos($supervisor)), 0, 0, 'L');
        $pdf->Cell(110, 6, 'ATIVACAO:', 0, 0, 'R');
        $pdf->Cell(20, 6, $dt_ativacao, 0, 1, 'R');
        $pdf->Cell(20, 6, 'EQUIPE:', 0, 0, 'L');
        $pdf->Cell(20, 6, strtoupper($equipe), 0, 0, 'L');
        $pdf->Cell(130, 6, 'BOV:', 0, 0, 'R');
        $pdf->Cell(20, 6,  $dt_instalacao, 0, 1, 'R');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(170, 6, 'OS:', 0, 0, 'R');
        $pdf->Cell(20, 6, $rows->num_os, 0, 1, 'R');
        #Segundo bloco
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 6, 'STATUS', 0, 1);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->Cell(195, 0.5, '', 0, 1, 'L', 1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25, 6, utf8_decode('SITUAÇÃO:'), 0, 0);
        $pdf->Cell(20, 6, strtoupper(utf8_decode($rows->status_ativacao)), 0, 0, 'R');
        $pdf->Cell(135, 6, 'AUDITORIA:', 0, 0, 'R');
        $pdf->Cell(10, 6, $auditoria, 0, 1, 'R');
        $pdf->Cell(30, 6, 'BOV:', 0, 0, 'L');
        $pdf->Cell(134, 6, strtoupper(utf8_decode($rows->status_bov)), 0, 0, 'L');
        $pdf->Cell(18, 6, 'WHATSAPP:', 0, 0);
        $pdf->Cell(55, 6, strtoupper(utf8_decode($rows->whatsapp)), 0, 1);
        $pdf->Cell(30, 6, 'TRATAMENTO:', 0, 0);
        $pdf->Cell(20, 6, strtoupper(utf8_decode($status_tratamento[$rows->status_tratamento])), 0, 1);
        $pdf->Cell(30, 6, 'RET TRATAMENTO:', 0, 0);
        $pdf->Cell(20, 6, $dt_retorno_tratamento, 0, 1);
        #Terceiro bloco
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 6, utf8_decode('SERVIÇO CONTRATADO'), 0, 1);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->Cell(195, 0.5, '', 0, 1, 'L', 1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25, 6, 'COMBO:', 0, 0);
        $pdf->Cell(20, 6, strtoupper(utf8_decode($rows->descricao_combo)), 0, 1, 'R');

        $pdf->Cell(23, 6, 'VELOCIDADE:', 0, 0, 'L');
        $pdf->Cell(20, 6, strtoupper(utf8_decode($rows->velocidade)), 0, 1, 'L');
        $pdf->Cell(23, 6, 'TV:', 0, 0, 'L');
        $pdf->Cell(25, 6, strtoupper(utf8_decode($rows->tv)), 0, 1, 'L');
        #Quarto bloco
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 6, utf8_decode('DADOS CLIENTE'), 0, 1);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->Cell(195, 0.5, '', 0, 1, 'L', 1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(22, 6, 'NOME:', 0, 0);
        $pdf->Cell(130, 6, strtoupper(utf8_decode($rows->nome_cliente_csv)), 0, 0, 'L');
        $pdf->Cell(22, 6, 'CONTATO 1:', 0, 0, 'R');
        $pdf->Cell(80, 6, strtoupper(utf8_decode($rows->contato_principal_csv)), 0, 1, 'L');
        $pdf->Cell(22, 6, 'NASCIMENTO:', 0, 0, 'L');
        $pdf->Cell(130, 6, $dt_nascimento, 0, 0, 'L');
        $pdf->Cell(22, 6, 'CONTATO 2:', 0, 0, 'R');
        $pdf->Cell(80, 6, strtoupper(utf8_decode($rows->contato_secundario_csv)), 0, 1, 'L');
        $pdf->Cell(22, 6, 'CPF:', 0, 0, 'L');
        $pdf->Cell(120, 6, $cpf_cnpj, 0, 0, 'L');
        $pdf->Cell(25, 6, 'EMAIL:', 0, 1, 'R');
        $pdf->Cell(143, 6, '', 0, 0, 'R');
        $pdf->Cell(50, 6, $rows->email_csv, 0, 1, 'R');
        #Quinto bloco
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 6, utf8_decode('DADOS ENDEREÇO'), 0, 1);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->Cell(195, 0.5, '', 0, 1, 'L', 1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(22, 6, 'CEP:', 0, 0);
        $pdf->Cell(145, 6, mascaras_uteis($rows->cep_instalacao_csv, '##.###-###'), 0, 0, 'L');
        $pdf->Cell(22, 6, utf8_decode('Nº FACHADA:'), 0, 0, 'R');
        $pdf->Cell(80, 6, strtoupper(utf8_decode($rows->num_instalacao_csv)), 0, 1, 'L');
        $pdf->Cell(22, 6, 'CIDADE:', 0, 0, 'L');
        $pdf->Cell(80, 6, strtoupper(utf8_decode($rows->cidade_instalacao_csv)), 0, 1, 'L');
        $pdf->Cell(22, 6, 'BAIRRO:', 0, 0, 'L');
        $pdf->Cell(130, 6, utf8_decode($rows->bairro_instalacao_csv), 0, 1, 'L');
        $pdf->Cell(22, 6, 'LOGRADOURO:', 0, 0, 'L');
        $pdf->Cell(130, 6, utf8_decode($rows->logradouro_instalacao_csv), 0, 1, 'L');
        $pdf->Cell(22, 6, utf8_decode('REFERÊNCIA:'), 0, 0, 'L');
        $pdf->Cell(130, 6, utf8_decode($rows->ref_instalacao_csv), 0, 1, 'L');
        #Sexto bloco
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 6, utf8_decode('DADOS AGENDAMENTO'), 0, 1);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->Cell(195, 0.5, '', 0, 1, 'L', 1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 6, utf8_decode('1º AGENDAMENTO:'), 0, 0, 'L');
        $pdf->Cell(95, 6, ($rows->dt_agendamento != null ? date("d/m/Y", strtotime($rows->dt_agendamento)) : ''), 0, 0, 'L');
        $pdf->Cell(65, 6, utf8_decode('TURNO:'), 0, 0, 'R');
        $pdf->Cell(20, 6, utf8_decode($rows->turno_agendamento), 0, 1, 'L');
        $pdf->Cell(30, 6, utf8_decode('2º AGENDAMENTO:'), 0, 0, 'L');
        $pdf->Cell(95, 6, ($rows->dt_reagendamento_1 != null ? date("d/m/Y", strtotime($rows->dt_reagendamento_1)) : ''), 0, 0, 'L');
        $pdf->Cell(65, 6, utf8_decode('TURNO:'), 0, 0, 'R');
        $pdf->Cell(20, 6, utf8_decode($rows->turno_reagendamento_1), 0, 1, 'L');
        $pdf->Cell(30, 6, utf8_decode('3º AGENDAMENTO:'), 0, 0, 'L');
        $pdf->Cell(95, 6, ($rows->dt_reagendamento_2 != null ? date("d/m/Y", strtotime($rows->dt_reagendamento_2)) : ''), 0, 0, 'L');
        $pdf->Cell(65, 6, utf8_decode('TURNO:'), 0, 0, 'R');
        $pdf->Cell(20, 6, utf8_decode($rows->turno_reagendamento_2), 0, 1, 'L');
        $pdf->Cell(30, 6, utf8_decode('4º AGENDAMENTO:'), 0, 0, 'L');
        $pdf->Cell(95, 6, ($rows->dt_reagendamento_3 != null ? date("d/m/Y", strtotime($rows->dt_reagendamento_3)) : ''), 0, 0, 'L');
        $pdf->Cell(65, 6, utf8_decode('TURNO:'), 0, 0, 'R');
        $pdf->Cell(20, 6, utf8_decode($rows->turno_reagendamento_3), 0, 1, 'L');
        #Setimo bloco
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 6, utf8_decode('DADOS PAGAMENTO'), 0, 1);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->Cell(195, 0.5, '', 0, 1, 'L', 1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(15, 6, utf8_decode('VENC:'), 0, 0, 'L');
        $pdf->Cell(30, 6, utf8_decode(strtoupper($rows->vencimento_csv)), 0, 1, 'L');
        $pdf->Cell(15, 6, utf8_decode('TIPO:'), 0, 0, 'L');
        $pdf->Cell(30, 6, utf8_decode(strtoupper($rows->forma_pagamento)), 0, 0, 'L');
        $pdf->Cell(140, 6, utf8_decode('CONTA ONLINE:'), 0, 0, 'R');
        $pdf->Cell(30, 6, utf8_decode(strtoupper($conta_online)), 0, 1, 'L');
        $pdf->Cell(15, 6, utf8_decode('BANCO:'), 0, 0, 'L');
        $pdf->Cell(30, 6, utf8_decode(strtoupper($rows->banco)), 0, 0, 'L');
        $pdf->Cell(132, 6, utf8_decode('AGÊNCIA:'), 0, 0, 'R');
        $pdf->Cell(16, 6, utf8_decode(strtoupper($rows->pag_agencia_csv)), 0, 1, 'R');
        $pdf->Cell(15, 6, utf8_decode('CONTA:'), 0, 0, 'L');
        $pdf->Cell(16, 6, utf8_decode(strtoupper($rows->pag_conta_csv)), 0, 0, 'L');
        $pdf->Cell(143, 6, utf8_decode('DIGITO:'), 0, 0, 'R');
        $pdf->Cell(16, 6, utf8_decode(strtoupper($rows->pag_agencia_digito_csv)), 0, 1, 'R');
        $pdf->Cell(15, 6, utf8_decode('OP:'), 0, 0, 'L');
        $pdf->Cell(16, 6, utf8_decode(strtoupper($rows->pag_operacao_csv)), 0, 1, 'L');
        #PAGINA E BLOCO PARA AS OBSERVAÇÕES
        //Obsercao vendedor
        $pdf->AddPage('P');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 6, utf8_decode('OBSERVAÇÃO VENDEDOR'), 0, 1);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->Cell(195, 0.5, '', 0, 1, 'L', 1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->ln(3);
        $pdf->MultiCell(190, 6, utf8_decode(strtoupper($rows->obs_vendedor_csv)), 0, 1, '');
        $pdf->ln(3);
        //Obsercao supervisor
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 6, utf8_decode('OBSERVAÇÃO SUPERVISOR'), 0, 1);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->Cell(195, 0.5, '', 0, 1, 'L', 1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(190, 6, utf8_decode(strtoupper($rows->obs_tratamento_supervisor)), 0, 1, '');
        $pdf->ln(3);
        //Obsercao bko
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 6, utf8_decode('OBSERVAÇÃO TRATAMENTO BACKOFFICE'), 0, 1);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->Cell(195, 0.5, '', 0, 1, 'L', 1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(190, 6, utf8_decode(strtoupper($rows->obs_tratamento_bko)), 0, 1, '');
        $pdf->ln(3);
        //Obsercao bko
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 6, utf8_decode('OBSERVAÇÃO ATIVAÇÃO BACKOFFICE'), 0, 1);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->Cell(195, 0.5, '', 0, 1, 'L', 1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(190, 6, utf8_decode(strtoupper($rows->obs_ativacao)), 0, 1, '');
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('FICHA VENDA -' . strtoupper(utf8_decode($rows->nome_cliente_csv)), 'I');
    }

    public function mquery($id)
    {
        $this->db = db_connect();
        $sql = "SELECT
                    A.dt_venda_csv dt_venda,
                    A.dt_ativacao dt_ativacao,
                    A.dt_instalacao dt_instalacao,
                    A.num_os,
                    A.status_bov,
                    A.status_tratamento,
                    CASE
                        WHEN audio_audit_quality_1 IS NOT NULL AND  A.audio_audit_quality_2 IS NOT NULL THEN 
                            CONCAT(A.audio_audit_quality_1, '|', A.audio_audit_quality_2)
                        WHEN A.audio_audit_quality_1 IS NOT NULL THEN A.audio_audit_quality_1
                        WHEN A.audio_audit_quality_2 IS NOT NULL THEN A.audio_audit_quality_2
                        ELSE
                        NULL
                    END auditoria,

                    CASE
                        WHEN 
                            zap_agendamento1 = 1 OR 
                            zap_reagendamento_1 = 1 OR 
                            zap_reagendamento_2 = 1 OR 
                            zap_reagendamento_3 = 1 THEN 'SIM'
                        ELSE 'NAO'
                    END whatsapp,
                    A.dt_retorno_tratamento,
                    A.banda_larga_velocidade_csv velocidade,
                    A.nome_cliente_csv,
                    A.dt_nascimento_csv,
                    A.cpf_cnpj_csv,
                    A.contato_principal_csv,
                    A.contato_secundario_csv,
                    A.email_csv,
                    A.cidade_instalacao_csv,
                    A.cep_instalacao_csv,
                    A.num_instalacao_csv,
                    A.bairro_instalacao_csv,
                    A.logradouro_instalacao_csv,
                    A.ref_instalacao_csv,
                    A.dt_agendamento,
                    A.turno_agendamento ,
                    A.dt_reagendamento_1,
                    A.turno_reagendamento_1,
                    A.dt_reagendamento_2,
                    A.turno_reagendamento_2,
                    A.dt_reagendamento_3,
                    A.turno_reagendamento_3,
                    A.pag_conta_online_csv,
                    A.pag_agencia_csv,
                    A.pag_conta_csv,
                    A.pag_operacao_csv,
                    A.pag_agencia_digito_csv,
                    A.vencimento_csv,
                    A.obs_vendedor_csv,
                    A.obs_ativacao,
                    A.obs_tratamento_bko,
                    A.obs_tratamento_supervisor,
                    D.descricao descricao_combo,
                    E.descricao velocidade,
                    F.descricao tv,
                    G.descricao forma_pagamento,
                    H.descricao banco,
                    B.nome nome_vendedor,
                    B.equipe_id,
                    C.descricao status_ativacao
         FROM vendas A
        LEFT JOIN usuarios B ON (B.id = A.id_vendedor)
        LEFT JOIN status_ativacoes C ON (C.id = A.status_ativacao)
        LEFT JOIN combo_planos D ON (D.id = A.combo_contratado_csv)
        LEFT JOIN plano_fibra E ON (E.id = A.banda_larga_velocidade_csv)
        LEFT JOIN plano_tv F ON (F.id = A.plano_tv_csv)
        LEFT JOIN forma_pagamento G ON (G.id = A.forma_pagamento_csv)
        LEFT JOIN bancos_permitidos H ON (H.id = A.pag_banco_csv)
        WHERE A.id = '{$id}'";
        return $this->db->query($sql)->getRow();
    }
    public function exportarVendas()
    {
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=relatorio-vendas.csv');
        header('Content-Transfer-Encoding: binary');
        header('Content-type: text/html; charset=utf-8');
        header('Pragma: no-cache');

        $data_inicial =  $this->request->getGet('expo_per_ini');
        $data_final =  $this->request->getGet('expo_per_fim');
        $retorno = $this->sqlExportarvendas($data_inicial, $data_final);
        $out = fopen('php://output', 'w');

        fputcsv(
            $out,
            $retorno["cabecalho"],
            ";"
        );

        foreach ($retorno["query"] as $result) {
            fputcsv($out, $result, ";");
        }
        fclose($out);
    }

    public function sqlExportarvendas($data_inicial, $data_final)
    {
        $this->db = db_connect();
        $cod_interno = session()->get('cod_interno');
        $cod_usuario = session()->get('id_usuario');
        $where_date = " dt_venda_csv >= '{$data_inicial}' AND dt_venda_csv <= '{$data_final}'";
        #DETERMINA O NIVEL DE INFORMACAO RECEBIDA
        if ($cod_interno == 970) {
            $where_complemento = " AND id_supervisor =  '{$cod_usuario}'";
        } elseif ($cod_interno == 960) {
            $where_complemento = " AND id_usuario  =  '{$cod_usuario}'";
        } else {
            $where_complemento = "";
        }

        #RETORNAR O CAMPO DE FINANCEIRO
        if ($cod_interno == 9999 || $cod_interno == 1000) {
            $campo_extra = "format((SELECT SUM(valor) FROM linha_pgto I WHERE I.num_os = A.num_os AND I.valor > 0 ), 2, 'de_DE') as comissao_oi,
            format((SELECT SUM(valor) FROM linha_pgto I WHERE I.num_os = A.num_os AND I.valor < 0 ), 2, 'de_DE') as estorno_oi,
            format((SELECT SUM(valor) FROM linha_pgto I WHERE I.num_os = A.num_os AND I.valor > 0 ) - ABS((SELECT SUM(valor) FROM linha_pgto I WHERE I.num_os = A.num_os AND I.valor < 0 )), 2, 'de_DE') as total,";
            $cabecalho = [
                "Venda", "Ativacao", "Data BOV", "Ordem Servico", "Bundle", "Blindagem", "Tipo",
                "Velocidade", "TV", "Status BOV", "Situacao Ativacao", "MIG FIB", "MIG VOIP",
                "Faturamento", "Comissao Oi", "Estorno Oi", "Total", "Equipe", "Supervisor", "Vendedor",
                "CPF/CNPJ", "Nome Cliente",
                "UF", "Cidade", "Bairro", "Forma Pagamento", "Vencimento", "Contato principal",
                "Contato secundario", "Dt Agendamento", "Turno Agendamento", "Dt reagendamento1", "Turno reagendamento1",
                "Dt reagendamento2", "Turno reagendamento2", "Dt reagendamento3", "Turno reagendamento3",
                "Obs vendedor", "Obs ativacao", "Obs tratamento bko", "Obs tratamento supervisor"];
        } else {
            $campo_extra = "";
            $cabecalho = [
                "Venda", "Ativacao", "Data BOV", "Ordem Servico", "Bundle", "Blindagem", "Tipo",
                "Velocidade", "TV", "Status BOV", "Situacao Ativacao", "MIG FIB", "MIG VOIP",
                "Faturamento", "Equipe", "Supervisor", "Vendedor",
                "CPF/CNPJ", "Nome Cliente",
                "UF", "Cidade", "Bairro", "Forma Pagamento", "Vencimento", "Contato principal",
                "Contato secundario", "Dt Agendamento", "Turno Agendamento", "Dt reagendamento1", "Turno reagendamento1",
                "Dt reagendamento2", "Turno reagendamento2", "Dt reagendamento3", "Turno reagendamento3",
                "Obs vendedor", "Obs ativacao", "Obs tratamento bko", "Obs tratamento supervisor"];
        }
        $sql = "SELECT
                    A.dt_venda_csv dt_venda,
                    A.dt_ativacao dt_ativacao,
                    A.dt_instalacao dt_instalacao,
                    A.num_os,
                    A.id_bundle,
                    A.status_blindagem,
                    IF(LENGTH(A.cpf_cnpj_csv)>11, 'PJ', 'PF') tipo,
                    E.descricao velocidade,
                    F.descricao tv,
                    A.status_bov,
                    C.descricao status_ativacao,
                    A.mig_cobre_velox_bov,
                    A.mig_cobre_fixo_bov,
                    format(A.faturamento, 2, 'de_DE') as faturamento,
                    $campo_extra
                    J.descricao equipe,
                    I.nome supervisor,
                    B.nome vendedor,
                    A.cpf_cnpj_csv,
                    A.nome_cliente_csv,
                    uf_instalacao_csv,
                    A.cidade_instalacao_csv,
                    A.bairro_instalacao_csv,
                    G.descricao forma_pagamento,
                    A.vencimento_csv,
                    A.contato_principal_csv,
                    A.contato_secundario_csv,
                    A.dt_agendamento,
                    A.turno_agendamento,
                    A.dt_reagendamento_1,
                    A.turno_reagendamento_1,
                    A.dt_reagendamento_2,
                    A.turno_reagendamento_2,
                    A.dt_reagendamento_3,
                    A.turno_reagendamento_3,
                    A.obs_vendedor_csv,
                    A.obs_ativacao,
                    A.obs_tratamento_bko,
                    A.obs_tratamento_supervisor
            FROM vendas A
            LEFT JOIN usuarios B ON (B.id = A.id_vendedor)
            LEFT JOIN usuarios I ON (I.id = A.id_supervisor)
            LEFT JOIN status_ativacoes C ON (C.id = A.status_ativacao)
            LEFT JOIN combo_planos D ON (D.id = A.combo_contratado_csv)
            LEFT JOIN plano_fibra E ON (E.id = A.banda_larga_velocidade_csv)
            LEFT JOIN plano_tv F ON (F.id = A.plano_tv_csv)
            LEFT JOIN forma_pagamento G ON (G.id = A.forma_pagamento_csv)
            LEFT JOIN bancos_permitidos H ON (H.id = A.pag_banco_csv)
            LEFT JOIN equipe_usuario J ON (J.id = A.equipe) 
            WHERE
            $where_date
            $where_complemento";
		//print_r($sql); exit;
        return ["query" => $this->db->query($sql)->getResultArray(), "cabecalho" => $cabecalho];
    }
}