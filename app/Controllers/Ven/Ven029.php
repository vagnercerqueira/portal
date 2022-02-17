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


class Ven029 extends Ven007
{
    private $ceps = [];
    private $dthr = null;
    private $resposta_upload = ["error" => 0, "message" => "", "rejeitados" => [], "info_card_upload" => []];
    private $turno = [1 => "Manha", 2 => "Tarde"];

    private $campos_blindagem = ['NUMERO PEDIDO', 'PRODUTO', 'DATA STATUS', 'STATUS', 'FLAG VENDA VALIDA', 'FLAG MIG COBRE FIXO',
                                'FLAG MIG COBRE VELOX', 'PLANO', 'ID BUNDLE', 'CPF/CNPJ', 'SUBMOTIVO'];

    private $campos_dfv = ['UF', 'MUNICIPIO', 'LOGRADOURO', 'NUM FACHADA', 'COMPLEMENTO', 'COMPLEMENTO2',
                                'COMPLEMENTO3', 'CEP', 'BAIRRO', 'TIPO VIABILIDADE', 'NOME CDO', 'COD LOGRADOURO'];

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

        //$cod_interno = session()->get('cod_interno');
        $dados_vendedor = $this->Equipe_Model->equipe_supervisor(970, session()->get('equipe_id'));
        //print_r($dados_vendedor);exit;
        $equipe_vendas = array_column($dados_vendedor, 'equipe', 'id');

        $vendedor = [session()->get('id_usuario') => session()->get('nome')];
        
	
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
            "status_ativacao" => form_dropdown('STATUS_ATIVACAO', $status_ativacoes, '', "id='STATUS_ATIVACAO' tabindex='-1' aria-disabled='true' class='form-control form-control-sm' required"),
            "equipe" => form_dropdown('EQUIPE', $equipe_vendas, '', "id='EQUIPE' class='form-control form-control-sm desativado' required"),
            "vendedor" => form_dropdown('ID_VENDEDOR', $vendedor, '', "id='ID_VENDEDOR' class='form-control form-control-sm desativado' required"),
            "supervisor" => $dados_vendedor[0]['supervisor'],
            "setor_tratamento" => form_dropdown('SETOR_RESP_TRATAMENTO', $setor_tratamento, '', "id='SETOR_RESP_TRATAMENTO' class='form-control form-control-sm desativado'"),
			"option_cols_bov"=> array_values(json_decode($this->parametros_head_csv['bov'], true)),
            "option_cols_blindagem"=>$this->campos_blindagem,
            "option_cols_dfv"=>$this->campos_dfv,            
        ];
        $this->load_template($data);
    }
     public function DataTable()
    {

        $cod_interno = session()->get('cod_interno');
        $cod_usuario = session()->get('id_usuario');
        #DETERMINA O NIVEL DE INFORMACAO RECEBIDA
        if ($cod_interno == 970) {
            $where_complemento = " WHERE id_supervisor =  '{$cod_usuario}' ";
        } 
        elseif($cod_interno == 960) {
            $where_complemento = " WHERE id_vendedor =  '{$cod_usuario}' ";
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
                   DATE_FORMAT(A.dt_venda_csv, '%d/%m/%Y') dt_venda_csv,
                    -- A.dt_venda_csv,
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
            'bt_editar' => false,
            'cols' => [
                "teste", "auditoria", 
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
            ]
        ]);
        $dt->complexQuery($sql);
    }
    public function ProcuraSupervisor($tipo = null, $id_equipe = null)
    {
        $cod_interno = session()->get('cod_interno');
        $id_vendedor = ($cod_interno == 960 ? session()->get('id_usuario') : '');
        $option = ($cod_interno == 960 ? '' : '<option value="" selected="selected">Selecione...</option>');
        $selecionado = ($cod_interno == 960 ? " selected='selected' " : "");
       
        if ($tipo == null) {
            $id_equipe = $this->request->getPost('id');
        }

        $equipe_vendas = $this->Equipe_Model->equipe_supervisor(970, $id_equipe);
        $vendedor = $this->Equipe_Model->Vendedores(960, $id_equipe, $id_vendedor);
        $option = '<option value="" selected="selected">Selecione...</option>';
        foreach ($vendedor  as $v) {
            $option .= "<option value='" . $v["id"] . "' .$selecionado>" . strtoupper($v["vendedor"]) . "</option>";
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
}