<?php

namespace App\Controllers\Tb_basicas;

use App\Controllers\BaseController;
use Datatables_server_side;

class Tb_basicas extends BaseController
{
	protected $titulo = null;
	protected $modelo = null;
	protected $arr_extra = [];
	protected $field_extra = [];
	protected $joins = [];
	protected $mwhere = [];
	protected $bt_editar = true;
	protected $bt_excluir = true;
	
	public function __construct()
	{
		$this->tbs_crud  = ['form_basicos' => $this->modelo];
	}
	public function index()
	{
		$table = new \CodeIgniter\View\Table();
		$arr_head = ['Descricao'];
		foreach ($this->field_extra as $k => $v)
			array_push($arr_head, ucfirst(str_replace('_', ' ', $k)));
		array_push($arr_head, "Acao");

		$template = [
			'table_open' => '<table class="table table-bordered table-sm" id="tb_basicas">'
		];
		$table->setTemplate($template);
		$table->setHeading($arr_head);

		$vars = array(
			"arquivo_dataTable" => true,
			"modelo" => $this->modelo,
			"extra" => $this->arr_extra,
			"titulo" => $this->titulo,
			'table' => $table->generate(),
		);
		$this->cria_tabela();
		$this->load_template($vars, 'tb_basicas/tb_basicas');
	}
	private function cria_tabela()
	{
		$db = db_connect();
		$ext = null;
		if (!$db->tableExists($this->modelo)) {
			if (count($this->field_extra) > 0) {
				foreach ($this->field_extra as $k => $v) {
					$ext .= "`$k` " . ($v['type'] ?? "varchar ") . "(" . ($v['size'] ?? 100) . ") ,";
				}
			}
			$db->query("CREATE TABLE IF NOT EXISTS `{$this->modelo}` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`descricao` varchar(100) NOT NULL,
				{$ext}
				PRIMARY KEY (`id`)
			  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");
		}
	}
	public function DataTable()
	{
		$f = ["descricao"];
		foreach ($this->field_extra as $k => $v)
			array_push($f, $k);

		$dt = new Datatables_server_side([
			'tb' => $this->modelo,
			'cols' => $f,
			'where' => $this->mwhere,
            'bt_editar' => $this->bt_editar,
            'bt_excluir' => $this->bt_excluir,		
		]);
		$dt->simpleQuery();
	}
}
