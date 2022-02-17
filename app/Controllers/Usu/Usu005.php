<?php

namespace App\Controllers\Usu;

use App\Controllers\BaseController;
use Datatables_server_side;

class Usu005 extends BaseController{

	public function __construct()
	{
		$this->tbs_crud  = ['form_parger' => PREFIXO_TB.'parametro_sistema'];
	}
	public function index()
	{
		$data = array(
			"arquivo_dataTable" => true,
			"titulo" => "Parametros do sistema",
		);
		$this->load_template($data);
	}
	public function valida_campos()
	{
		$rules = [
			'EMAIL_SUPORTE' => 'required|valid_email',
		];
		return $this->validate($rules);
	}	

	public function DataTable()
	{
		$dt = new Datatables_server_side([
			'tb' => PREFIXO_TB.'parametro_sistema',
			'cols' => ["email_suporte"],
			'acao' => true
		]);
		$dt->simpleQuery();
	}
}
