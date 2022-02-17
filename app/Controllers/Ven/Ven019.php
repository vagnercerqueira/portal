<?php

namespace App\Controllers\Ven;

use App\Controllers\BaseController;
use Datatables_server_side;

class Ven019 extends BaseController
{
    private $campos = [
        //'num_pedido_csv' => 'Número do pedido',
        'DT_VENDA_CSV' => 'DATA EM QUE O PEDIDO FOI REALIZADO',
        'NOME_CLIENTE_CSV' => 'NOME CLIENTE',
        'GENERO_CSV' => 'GÊNERO',
        'DT_NASCIMENTO_CSV' => 'DATA DE NASCIMENTO',
        'CPF_CNPJ_CSV' => 'CPF',
        'RG_CSV' => 'RG',
        'ORGAO_EXPEDIDOR_CSV' => 'ÓRGÃO EXPEDIDOR',
        'NOME_MAE_CSV' => 'NOME COMPLETO DA MÃE',
        'CONTATO_PRINCIPAL_CSV' => 'CONTATO PRINCIPAL',
        'CONTATO_SECUNDARIO_CSV' => 'CONTATO SECUNDÁRIO',
        'EMAIL_CSV' => 'E-MAIL',
        'MATRICULA_VENDEDOR_CSV' => 'MATRICULA VENDEDOR',
        'BANDA_LARGA_VELOCIDADE_CSV' => 'BANDA LARGA - VELOCIDADE',
        'COMBO_CONTRATADO_CSV' => 'COMBO/OFERTA CONTRATADA',
        'PLANO_TV_CSV' => 'TV - PLANO TV',
        'FORMA_PAGAMENTO_CSV' => 'PAGAMENTO - FORMA DE PAGAMENTO',
        'VENCIMENTO_CSV' => 'PAGAMENTO - VENCIMENTO',
        'PAG_CONTA_ONLINE_CSV' => 'PAGAMENTO - CONTA ONLINE',
        'PAG_BANCO_CSV' => 'PAGAMENTO - BANCO',
        'PAG_AGENCIA_CSV' => 'PAGAMENTO - AGÊNCIA',
        'PAG_CONTA_CSV' => 'PAGAMENTO - CONTA',
        'PAG_AGENCIA_DIGITO_CSV' => 'PAGAMENTO - DIGITO',
        'PAG_OPERACAO_CSV' => 'PAGAMENTO - OPERAÇÃO',
        'OBS_VENDEDOR_CSV' => 'OBSERVAÇÃO VENDEDOR',
        'CEP_INSTALACAO_CSV' => 'INSTALAÇÃO - CEP',
        'LOGRADOURO_INSTALACAO_CSV' => 'INSTALAÇÃO - LOGRADOURO',
        'NUM_INSTALACAO_CSV' => 'INSTALAÇÃO - NÚMERO',
        'BAIRRO_INSTALACAO_CSV' => 'INSTALAÇÃO - BAIRRO',
        'CIDADE_INSTALACAO_CSV' => 'INSTALAÇÃO - CIDADE',
        'UF_INSTALACAO_CSV' => 'INSTALAÇÃO - ESTADO',
        'REF_INSTALACAO_CSV' => 'INSTALAÇÃO - REFERÊNCIA',
        'REFE_COMPLEMENTO1_TIPO_CSV' => 'INSTALAÇÃO - COMPLEMENTO 1 - TIPO',
        'REFE_COMPLEMENTO1_CSV' => 'INSTALAÇÃO - COMPLEMENTO 1',
        'REFE_COMPLEMENTO2_TIPO_CSV' => 'INSTALAÇÃO - COMPLEMENTO 2 - TIPO',
        'REFE_COMPLEMENTO2_CSV' => 'INSTALAÇÃO - COMPLEMENTO 2',
        'REFE_COMPLEMENTO3_TIPO_CSV' => 'INSTALAÇÃO - COMPLEMENTO 3 - TIPO',
        'REFE_COMPLEMENTO3_CSV' => 'INSTALAÇÃO - COMPLEMENTO 3'
    ];



    private $nao_obrigatorios = [];

    public function __construct()
    {
        $this->titulo = "BASE VENDA";
        $this->tbs_crud  = ['form_generic' => 'parametro_venda_lote_csv'];
    }

    public function index()
    {
        $data = [
            "fields" => $this->fields_forms(),
            "generic_forms_js" => true,
            "tb_colunas" => $this->table_colunas(),
            "arquivo_dataTable" => true,
            'js_app' => false,
            'btn_novo' => false
        ];
        $this->load_template($data, 'generic_forms');
    }

    public function valida_campos()
    {
        $rules = [];
        foreach ($this->campos as $k => $v) {
            $req = (!in_array($k, $this->nao_obrigatorios) ? 'required|' : '');
            $rules[$k] = $req . 'max_length[50]';
        }
        return $this->validate($rules);
    }
    public function fields_forms()
    {
        $arr = [];
        foreach ($this->campos as $k => $v) {
            $req = (!in_array($k, $this->nao_obrigatorios) ? 'required' : 'norequired');
            $arr[] = [
                'tag' => 'input',
                'div_size' => '2',
                'label' => $k,
                'attrs' => [
                    'placeholder' => $k . ' na Linha ',
                    $req => '',
                    'type'      => 'text',
                    'name'        => $k,
                    'maxlength' => '50',
                    'class'     => 'form-control form-control-sm'
                ]
            ];
        }

        $fields = $arr;
        return $fields;
    }
    public function table_colunas()
    {
        $colunas = array_keys($this->campos);
        array_push($colunas, 'ACAO');
        return $colunas;
    }

    public function DataTable()
    {
        $sql = "SELECT " . (implode(',', array_keys($this->campos))) . ",id FROM parametro_venda_lote_csv";

        $dt = new Datatables_server_side([
            'tb' => 'parametro_venda_lote_csv',
            'cols' => array_values(array_keys($this->campos)),
            'bt_excluir' => false
        ]);
        $dt->complexQuery($sql);
    }
}