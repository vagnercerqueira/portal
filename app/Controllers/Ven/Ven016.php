<?php

namespace App\Controllers\Ven;

use App\Controllers\BaseController;
use Datatables_server_side;

class Ven016 extends BaseController
{
	private $campos = [ //campo=>indicie no csv
		'NUMERO_PEDIDO' => 'NUMERO_PEDIDO',
		'PRODUTO' => 'PRODUTO',
		'DATA_STATUS' => 'DATA_STATUS',
		'STATUS' => 'STATUS',
		'FLG_VENDA_VALIDA' => 'FLG_VENDA_VALIDA',
		'FLG_MIG_COBRE_FIXO' => 'FLG_MIG_COBRE_FIXO', //COLOCAR COMO NAO OBRIGATORIO NA TABELA
		'FLG_MIG_COBRE_VELOX' => 'FLG_MIG_COBRE_VELOX', //COLOCAR COMO NAO OBRIGATORIO NA TABELA
		'PLANO' => 'PLANO',
		'ID_BUNDLE' => 'ID_BUNDLE',
		'CPF_CNPJ' => 'CPF/CNPJ',
		'SUBMOTIVO' => 'SUBMOTIVO'
	];

	private $nao_obrigatorios = ['FLG_MIG_COBRE_FIXO', 'SUBMOTIVO'];

	public function __construct()
	{
		$this->titulo = "Parametro BOV DO CSV";
		$this->tbs_crud  = ['form_generic' => 'parametro_bov_csv'];
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
					'placeholder' => $k . ' no BOV ',
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
		$sql = "SELECT " . (implode(',', array_keys($this->campos))) . ",id FROM parametro_bov_csv";

		$dt = new Datatables_server_side([
			'tb' => 'parametro_bov_csv',
			'cols' => array_values(array_keys($this->campos)),
			'bt_excluir' => false
		]);
		$dt->complexQuery($sql);
	}
}