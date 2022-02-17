<?php

namespace App\Controllers\Home;

//use App\Controllers\BaseController;
//use App\Models\Documentos_Model;

class Home_diretor extends Home_superusuario
{
	public function index()
	{

		$dados = [
			"arquivo_dataTable" => true,
			'titulo' => 'Dashboard',
			"arquivo_js" => array("highcharts/highcharts", "highcharts/accessibility", "highcharts/data", "highcharts/drilldown", "highcharts/exporting", "highcharts/export-data"),
			"titulo" => "Home Master"
		];
		$this->load_template($dados, "home/home_superusuario");
	}
}