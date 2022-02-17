<?php
/*
	DESCRICAO: [CADASTRO VENDA]
	@AUTOR: Isaque Cerqueira
	DATA: 08/2021
*/

namespace App\Controllers\Ven;


use App\Controllers\BaseController;
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
use App\Models\Vendas\Vendas_Model;
use App\Models\Vendas\Whatsapp_Model;
use Datatables_server_side;

class Ven006 extends BaseController
{
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
    }
    public function index()
    {
        $uf_atuacao = ['' => 'Selecione...'] + array_column($this->UfAtuacao_Model->findAll(), 'uf', 'uf');
        $forma_pagamento = ['' => 'Selecione...'] + array_column($this->FormaPagamento_Model->findAll(), 'descricao', 'id');
        $vencimentos = ['' => 'Selecione...'] + array_column($this->Vencimentos_Model->findAll(), 'descricao', 'id');
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

        ];
        $this->load_template($data);
    }
    public function DataTable()
    {
        $sql = "SELECT
                    A.id,
                    'Ficha' teste,
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
                CASE
                        WHEN audio_audit_quality_1 IS NOT NULL AND  A.audio_audit_quality_2 IS NOT NULL THEN 
                            CONCAT(A.audio_audit_quality_1, '|', A.audio_audit_quality_2)
                        WHEN A.audio_audit_quality_1 IS NOT NULL THEN A.audio_audit_quality_1
                        WHEN A.audio_audit_quality_2 IS NOT NULL THEN A.audio_audit_quality_2
                        
                       ELSE
                       NULL
                        
                    END auditoria,
                    A.status_bov,
                    A.status_ativacao,
                    B.descricao desc_status_ativacao,
                    A.dt_venda_csv,
                    A.num_os,
                    A.cpf_cnpj_csv,
                    A.nome_cliente_csv
                FROM vendas A
                LEFT JOIN status_ativacoes B ON A.status_ativacao = B.id";

        $dt = new Datatables_server_side([
            'tb' => 'vendas',
            'cols' => [
                "teste", "auditoria", "zap_1", "zap_2",  "zap_3",  "zap_4",
                "status_bov", "desc_status_ativacao", "dt_venda_csv",
                "num_os", "cpf_cnpj_csv"
            ],
            'formata_coluna' => [
                1 => function ($col, $lin) {
                    $coluna = explode("|", $col);
                    if (strstr($col, "|")) {
                        $auditoria_1 = ($coluna[0] != '' ? "<a href='" . base_url("ven/ven006/download?arquivo=" . $coluna[0]) . "' class='btn btn-primary btn-xs' target='_blank' download>
                    <i class='fas fa-download'></i></a>" : '');
                        $auditoria_2 = ($coluna[1] != '' ? "<a href='" . base_url("ven/ven006/download?arquivo=" . $coluna[1]) . "' class='btn btn-primary btn-xs' target='_blank' download>
                    <i class='fas fa-download'></i></a>" : '');
                    } else {
                        $auditoria_1 = "";
                        $auditoria_2 = "";
                    }
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
                8 => function ($col, $lin) {
                    $dt_ativacao = date('d/m/Y', strtotime($col));
                    return  $dt_ativacao;
                },
                10 => function ($col, $lin) {
                    $tipo_mascara = (strlen($col) > 11 ? '##.###.###/####-##' : '###.###.###-##');
                    $cpf_cnpj = mascaras_uteis($col, $tipo_mascara);
                    return  $cpf_cnpj;
                },

            ]
        ]);
        $dt->complexQuery($sql);
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
        $dados["CPF_CNPJ_CSV"] = preg_replace('/[^0-9]/is', '', $dados["CPF_CNPJ_CSV"]);
        $dados["CEP_INSTALACAO_CSV"] = preg_replace('/[^0-9]/is', '', $dados["CEP_INSTALACAO_CSV"]);
        $dados["FATURAMENTO"] = remove_separador_milhar($dados["FATURAMENTO"]);

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
        $vendedor = $this->Equipe_Model->Vendedores(960, $id_equipe);
        $option = '<option value="" selected="selected">Selecione...</option>';
        foreach ($vendedor  as $v) {
            $option .= "<option value='" . $v["id"] . "'>" . strtoupper($v["vendedor"]) . "</option>";
        }
        $supervisor_vendedor =  [
            'id_supervisor' => strtoupper($equipe_vendas[0]['id']),
            'supervisor' => strtoupper($equipe_vendas[0]['supervisor']),
            'vendedor' => $option
        ];
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
        $cod_pais = "55";
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
        //numero Telefone
        $modeloVendas = $this->vendas_model;
        $busca_telefone =  $modeloVendas->where('id', $id)->findColumn('contato_principal_csv')[0];
        //Nome Cliente
        $nome_cliente =  $modeloVendas->where('id', $id)->findColumn('nome_cliente_csv')[0];
        //Mensagem
        $de_para_msg = [
            1 => "zap_agendamento1",
            2 => "zap_reagendamento_1",
            3 => "zap_reagendamento_2",
            4 => "zap_reagendamento_3"
        ];
        $retorna_telefone = filter_var($busca_telefone, FILTER_SANITIZE_NUMBER_INT);
        $retorna_nome = explode(" ", ucfirst(strtolower($nome_cliente)))[0];
        $retorna_msg = $this->WhatsappMsg_model->findColumn($de_para_msg[$tipo])[0];
        echo json_encode(["telefone" => $retorna_telefone, "nome_cliente" => $retorna_nome, "mensagem" => $retorna_msg]);
    }
}