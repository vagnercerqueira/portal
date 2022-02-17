<?php

namespace App\Controllers\Usu;

use App\Controllers\BaseController;
use App\Models\Usuarios\AplicacoesModel;
use Datatables_server_side;

class Usu002 extends BaseController
{
	public function __construct()
	{
		$this->tbs_crud  = ['form_aplicacoes' => PREFIXO_TB . 'aplicacoes'];
	}
	public function index()
	{
		$data = array(
			"arquivo_dataTable" => true,
			"caminho" => $this->monta_diretorios(),
			"diretorio" => $this->mapa_diretorio(),
			"titulo" => "Cadastro de Aplicacoes"
		);
		$this->load_template($data);
	}
	public function mapa_diretorio()
	{

		$aplicacoes = new AplicacoesModel();
		$data = $aplicacoes->orderBy('ordem', 'asc')->findAll();

		$itemsByReference = array();
		foreach ($data as $key => &$item) {
			$itemsByReference[$item['id']] = &$item;
			$itemsByReference[$item['id']]['nodes'] = array();
		}

		foreach ($data as $key => &$item) {
			if ($item['id_pai'] && isset($itemsByReference[$item['id_pai']])) {
				$itemsByReference[$item['id_pai']]['nodes'][] = &$item;
			}
		}
		foreach ($data as $key => &$item) {
			if (empty($item['nodes'])) {
				unset($item['nodes']);
			}
			if ($item['id_pai'] && isset($itemsByReference[$item['id_pai']])) {
				unset($data[$key]);
			}
		}
		return  '<ul id="myUL" class="list-group">
					<li><span class="caret"></span> <input type="radio" required value="0" name="ID_PAI" value="0" >Principal' . $this->renderMenu($data, ' active') . '</li></ul>';
	}
	public function renderMenu($items, $active = null)
	{
		$render = '<ul class="nested' . $active . '">';

		foreach ($items as $item) {
			if (!empty($item['nodes'])) {
				$render .= '<li><span class="caret caret-down"></span>&nbsp;<input type="radio"  value="' . $item['id'] . '" name="ID_PAI"/> ' . $item['nome'];
				$render .= $this->renderMenu($item['nodes']);
			} else {
				$render .= '<li style="color:grey;">&nbsp;' . ($item['caminho'] == '>' ? '<input type="radio" name="ID_PAI" value="' . $item['id'] . '"/>' : '<i class="fa fa-sticky-note-o"></i>') . '&nbsp;' . $item['nome'];
			}
			$render .= '</li>';
		}

		return $render . '</ul>';
	}

	public function monta_diretorios()
	{
		$dir = APPPATH . 'Controllers/';
		$arr = $this->getDirContents($dir);

		$arr_dir = array();
		$arr_files = array();



		foreach ($arr as $k => $v) {
			if (is_array($v)) {
				$arr_dir[$v[0]] = array();
			} else {
				$arr_files[] = $v;
			}
		}

		foreach ($arr_dir as $k => $v) {
			foreach ($arr_files as $kF => $vF) {
				$pos = strpos($vF, $k);
				if ($pos !== false) {
					$arr_dir[$k][] = $vF;
				}
			}
		}
		$select = "<select class='form-control form-control-sm' required id='CAMINHO' name='CAMINHO'><option value=''>Selecione...</option><option value='>'>__Modulo__</option>";
		foreach ($arr_dir as $k => $v) {
			$select .= "<optgroup label='{$k}'>";
			foreach ($v as $k2 => $v2) {

				$vopt = str_replace('\\', '/', $v2);
				if (strtolower($vopt) == 'usu/usu006.php') //CASO O SISTEMA NAO TENHA ACESSO POR USUARIO, SOMENTE POR GRUPO
					continue;

				$select .= "<option value='" . $vopt . "'>" . $vopt . "</option>";
			}
		}
		$select .= "</select>";
		return $select;
	}

	public function getDirContents($dir, &$results = array())
	{
		$files = scandir($dir);

		foreach ($files as $key => $value) {

			$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
			if (!is_dir($path)) {
				$cam = explode("Controllers", $path);
				$results[] = substr($cam[1], 1);
			} else if ($value != "." && $value != "..") {
				$this->getDirContents($path, $results);
				$cam = explode('Controllers', $path);
				$results[] = array(substr($cam[1], 1));
			}
		}

		return $results;
	}

	public function DataTable()
	{
		$sql = "SELECT A.nome, COALESCE(B.NOME, 'Principal') AS pai, A.caminho, A.icone, A.ORDEM,A.id
								FROM " . PREFIXO_TB . "aplicacoes A
								LEFT JOIN " . PREFIXO_TB . "aplicacoes B ON B.id=A.ID_PAI
								WHERE A.caminho <> 'usu/usu006.php'";

		$dt = new Datatables_server_side([
			'tb' => 'aplicacoes',
			'cols' => ["nome", "pai", "caminho", "icone", "ordem"],
			'formata_coluna' => [
				3 => function ($col, $lin) {
					$icos = ns_icon_check($col, null, 'desab_status');
					return  "<i class='" . $col . "'>";
				},
			]
		]);
		$dt->complexQuery($sql);
	}
	public function atualiza_arvore()
	{
		echo json_encode(['DADOS' => $this->mapa_diretorio()]);
	}
	public function valida_campos()
	{
		$rules = [
			'ID_PAI' => 'required',
		];
		return $this->validate($rules);
	}
	public function post_edicao($dados)
	{
		if ($dados['ID_PAI'] == "")
			$dados['ID_PAI'] = "0";
		return $dados;
	}
	function preInsUpd($data)
	{
		if ($data['ID_PAI'] == "0")
			$data['ID_PAI'] = "";
		return $data;
	}
}
