<?php

namespace App\Controllers\Home;

use App\Controllers\BaseController;
use App\Models\Documentos_Model;

class Home_default extends BaseController
{
	/*public function index()
	{				
		$dados = [
			'titulo' => 'Painel informativo',
			"js_crud"=>false,
			//"arquivo_js" => array("home/home_default"),
			//'dados_reg' => $this->totais(),
			'fsearch'=> $this->request->getPost('inputformsearchgeral')
		];
		$this->load_template($dados);
	}*/

	public function index()
	{
		$dados = [
			'titulo' => 'Dashboard',
			"arquivo_js" => array("highcharts/highcharts", "highcharts/accessibility", "highcharts/data", "highcharts/drilldown", "highcharts/exporting", "highcharts/export-data"),
			"titulo" => "Home Master"
		];
		$this->load_template($dados);
	}

	public function formsearchgeral()
	{
		$metodo = $this->request->getMethod();
		if ($metodo == "get") {
			echo "vc n tem acesso a essa area!!!";
			exit;
		}

		$G = $this->request->getPost('G');

		$html = "";
		echo json_encode(['DADOS' => $html]);
	}
}
