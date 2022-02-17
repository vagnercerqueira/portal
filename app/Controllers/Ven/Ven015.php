<?php

namespace App\Controllers\Ven;

use App\Controllers\BaseController;
use Datatables_server_side;

class Ven015 extends BaseController
{
	private $campos = [ //campo=>indicie no csv
		'UF' => 'UF',
		'MUNICIPIO' => 'MUNICIPIO',
		'LOGRADOURO' => 'LOGRADOURO',
		'NUM_FACHADA' => 'NUM_FACHADA',
		'COMPLEMENTO' => 'COMPLEMENTO',
		'COMPLEMENTO2' => 'COMPLEMENTO2',
		'COMPLEMENTO3' => 'COMPLEMENTO3',
		'CEP' => 'CEP',
		'BAIRRO' => 'BAIRRO',
		'TIPO_VIABILIDADE' => 'TIPO_VIABILIDADE',
		'NOME_CDO' => 'NOME_CDO',
		'COD_LOGRADOURO' => 'COD_LOGRADOURO',
	];
	private $nao_obrigatorios = [];

	public function __construct()
	{
		$this->titulo = "Parametro DFV DO CSV";
		$this->tbs_crud  = ['form_generic' => 'parametro_dfv_csv'];
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
			$rules[$k] = $req . 'max_length[30]';
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
		$colunas = $this->campos;
		array_push($colunas, 'ACAO');
		return $colunas;
	}

	public function DataTable()
	{
		$sql = "SELECT " . (implode(',', $this->campos)) . ",id FROM parametro_dfv_csv";

		$dt = new Datatables_server_side([
			'tb' => 'parametro_dfv_csv',
			'cols' => array_values($this->campos),
			'bt_excluir' => false
		]);
		$dt->complexQuery($sql);
	}
}
