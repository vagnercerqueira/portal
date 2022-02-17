<?php

namespace App\Controllers\Usu;

use App\Controllers\BaseController;
use Datatables_server_side;
class Usu007 extends BaseController
{
	public function __construct()
	{
		$this->titulo = "Grupo Teste";
		$this->tbs_crud  = ['form_generic' => 'grupo_usuario'];		
	}
	
	public function index(){
		$data = [ 	"fields" => $this->fields_forms(), 
					"generic_forms_js"=>true,
					"tb_colunas" => $this->table_colunas(),
					"arquivo_dataTable" => true, ];
		$this->load_template($data, 'generic_forms');
	}

	public function valida_campos()
	{
		$rules = [
			'DESCRICAO' => 'required|max_length[20]|min_length[3]',
			//'HOME' => 'required|min_length[3]|max_length[5]',
		];
		return $this->validate($rules);
	}
	public function fields_forms(){
		$fields = [
			
			[
				'tag'=>'input',
				'div_size'=>'4',
				'label'=>'desc',
				'attrs'=>[
					'type'      => 'text',
					'name'        => 'DESCRICAO',
					'maxlength' => '50',
					'class'     => 'form-control form-control-sm'
				]
			],
			[
				'tag'=>'input',
				'div_size'=>'4',
				'label'=>'home',
				'attrs'=>[
					'type'      => 'text',
					'name'        => 'HOME',
					'maxlength' => '100',
					'class'     => 'form-control form-control-sm',					
				]
			],			
			[	
				'tag'=>'select',
				'label'=>'label teste input',
				'attrs'=>[
					'name'        => 'COD_INTERNO',
					'class'     => 'form-control form-control-sm'
				],
				'options'=>	['10000'=>'10000', '1000'=>'1000','980'=>'980', '970'=>'970', '960'=>'960', '990'=>'990']
			],			
			[	
				'tag'=>'select',
				'label'=>'SUPERUSUARIO',
				'attrs'=>[
					'name'        => 'SUPERUSUARIO',
					'class'     => 'form-control form-control-sm'
				],
				'options'=>	['s'=>'SIM', 'N'=>'N']
			]
		];
		return $fields;
	}
	public function table_colunas(){
		$colunas = [
			'DESCRICAO',
			'HOME',
			'acao'
		];
		return $colunas;
	}
	
	public function DataTable()
	{
		$sql = "SELECT DESCRICAO, HOME, id FROM grupo_usuario";

		$dt = new Datatables_server_side([
			'tb' => 'grupo_usuario',
			'cols' => ["DESCRICAO", "HOME"],
		]);
		$dt->complexQuery($sql);
	}	
}
