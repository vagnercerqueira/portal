<?php

namespace App\Controllers\Ven;

use App\Controllers\BaseController;
use Datatables_server_side;

class Ven018 extends BaseController
{
	private $campos = [ //campo=>indicie no csv
		'COD_SAP' => 'COD_SAP',
		'VALOR' => 'VALOR',
		'NUM_OS' => 'NUM_OS',
		'DATA_INSTALACAO' => 'DATA_INSTALACAO',
		'FILIAL' => 'FILIAL',
		'CICLO' => 'CICLO',
		'QUINZENA' => 'QUINZENA',
		'CPF_CLIENTE' => ''
	];

	private $nao_obrigatorios = ['CICLO', 'FILIAL', 'CPF_CLIENTE'];

	public function __construct()
	{
		$this->titulo = "BOV DO CSV";
		$this->tbs_crud  = ['form_generic' => 'parametro_linha_pgto_csv'];
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
		$sql = "SELECT " . (implode(',', array_keys($this->campos))) . ",id FROM parametro_linha_pgto_csv";

		$dt = new Datatables_server_side([
			'tb' => 'parametro_linha_pgto_csv',
			'cols' => array_values(array_keys($this->campos)),
			'bt_excluir' => false
		]);
		$dt->complexQuery($sql);
	}
}
