<?php

namespace App\Controllers\Usu;

use App\Controllers\BaseController;
use App\Models\Usuarios\Acesso_grupoModel;
use App\Models\Usuarios\Acesso_usuarioModel;
use App\Models\Usuarios\AplicacoesModel;
use App\Models\Usuarios\UsuariosModel;

class Usu006 extends BaseController
{

	protected $usuarioModel;
	protected $AplicacoesModel;
	protected $acessosModel;
	protected $acessosUsuarioModel;
	private $permAlt = false;
	protected $grp_perm = [];
	public function __construct()
	{
		$this->usuarioModel = new UsuariosModel();
		$this->AplicacoesModel = new AplicacoesModel();
		$this->acessosModel = new Acesso_grupoModel();
		$this->acessosUsuarioModel = new Acesso_usuarioModel();
		$pg = session()->get('IndPag');
		$apps = session()->get('appUser');
		$this->permCrud = ($apps[$pg]['perm_cadastrar'] == 'N' || $apps[$pg]['perm_alterar'] == 'N' || $apps[$pg]['perm_excluir'] == 'N') ? false : true;
		$this->permAlt = !$this->permCrud ? 'disabled' : null;
	}
	public function index()
	{

		$usu = ($this->usuarioModel->userGroup());

		$data = array(
			"diretorio" => "",
			"usuarios" => json_encode($this->dsearch($usu, 'id', 'nome'), JSON_FORCE_OBJECT),
			"titulo" => "Acessos"
		);
		$this->load_template($data);
	}

	public function mapa_diretorio($sels = [])
	{
		$dirs = [];

		foreach ($sels as $k => $v) {
			$dirs[$v['id']] = $v;
		}

		$rows = $this->AplicacoesModel->AcessUsuGrupo();

		$data = array();
		foreach ($rows as $k => $row) {
			$tmp = array();
			$tmp['id'] = $row['id'];
			$tmp['nome'] = $row['nome'];
			$tmp['id_pai'] = $row['id_pai'];
			$tmp['href'] = $row['caminho'];
			array_push($data, $tmp);
		}
		$itemsByReference = array();

		// Build array of item references:
		foreach ($data as $key => &$item) {
			$itemsByReference[$item['id']] = &$item;
			// Children array:
			$itemsByReference[$item['id']]['nodes'] = array();
		}

		// Set items as children of the relevant parent item.
		foreach ($data as $key => &$item) {
			if ($item['id_pai'] && isset($itemsByReference[$item['id_pai']])) {
				$itemsByReference[$item['id_pai']]['nodes'][] = &$item;
			}
		}
		// Remove items that were added to parents elsewhere:
		foreach ($data as $key => &$item) {
			if (empty($item['nodes'])) {
				unset($item['nodes']);
			}
			if ($item['id_pai'] && isset($itemsByReference[$item['id_pai']])) {
				unset($data[$key]);
			}
		}
		return  '<ul id="myUL" class="list-group">
					<li> Principal<span class="float-right"><span style="writing-mode: vertical-rl;">Cad</span><span style="writing-mode: vertical-rl;">Alt</span><span style="writing-mode: vertical-rl;">Del</span></span>' . $this->renderMenu($data, ' active', null, $dirs) . '</li></ul>';
	}

	public function renderMenu($items, $active = null, $space = null, $dirs)
	{

		$render = '<ul class="nested' . $active . '">';

		foreach ($items as $item) {
			$ch = array_key_exists($item['id'], $dirs);
			$checked = $ch ? ' checked ' : null;
			$pcDis = $this->permAlt;
			$paDis = $this->permAlt;
			$peDis = $this->permAlt;

			$mdis = null;
			$colorFold = null;
			if ($ch) {
				$mdis = ($dirs[$item['id']]['idgrupo'] == "") ? $this->permAlt : ' disabled';
				//$colorFold = 'text-danger';
				$checked = ' checked ';
				$dispCrud = null;
				if ($dirs[$item['id']]['perm_cadastrar'] == 'S') {
					$pc = " checked";
				} else {
					$pc = null;
					if (session()->get('superusuario') == 'N') {
						$pcDis = ' disabled ';
					}
				}
				if ($dirs[$item['id']]['perm_alterar'] == 'S') {
					$pa = " checked";
				} else {
					$pa = null;
					if (session()->get('superusuario') == 'N') {
						$paDis = ' disabled ';
					}
				}
				if ($dirs[$item['id']]['perm_excluir'] == 'S') {
					$pe = " checked";
				} else {
					$pe = null;
					if (session()->get('superusuario') == 'N') {
						$peDis = ' disabled ';
					}
				}
			} else {
				$pc = null;
				$pa = null;
				$pe = null;
				$checked = null;
				$dispCrud = 'style="display: none"';
				$mdis = $this->permAlt;
			}


			if (!empty($item['nodes'])) {
				$render .= '<li class="pai ' . $colorFold . '"><input ' . $checked . $mdis . ' type="checkbox" name="APLICACAO[]" class="input_pai" value="' . $item['id'] . '">&nbsp;<b>' . $space . $item['nome'] . "</b>";
				$render .= $this->renderMenu($item['nodes'], ' active', "&nbsp;&nbsp;&nbsp;", $dirs);
			} else {
				$perm_crud = '<span class="float-right perm_crud" ' . $dispCrud . '  v="' . $item['id'] . '">
								<input ' . $pcDis . $pc . '  type="checkbox" act="perm_cadastrar" class="perm_cad" value="' . $item['id'] . '">&nbsp;&nbsp;&nbsp;
								<input ' . $paDis . $pa . '  type="checkbox" act="perm_alterar" class="perm_upd" value="' . $item['id'] . '">&nbsp;&nbsp;&nbsp;
								<input ' . $peDis . $pe . '  type="checkbox" act="perm_excluir" class="perm_del" value="' . $item['id'] . '"></span>';
				$render .= '<li style="color:grey;margin-top:12px;" class="' . $colorFold . '"><input ' . $checked . $mdis . ' name="APLICACAO[]" class="input_filho" value="' . $item['id'] . '" type="checkbox">&nbsp;' . $item['nome'] . $perm_crud;
			}
			$render .= '</li>';
		}

		return $render . '</ul>';
	}
	public function atualiza_acesso()
	{
		$usuario = $this->request->getPost('usuario');
		$grupo = $this->usuarioModel->find($usuario);
		$acG = $this->acessosUsuarioModel->AcessUsuGrupo($grupo['grupo'], $usuario);
		$mp = $this->mapa_diretorio($acG);
		echo json_encode(['dirs' => $mp]);
	}
	public function altera_acesso()
	{
		extract($this->request->getPost());
		$del = $this->acessosUsuarioModel->where('id_usuario', $ID_USUARIO)->delete();

		if (isset($APLICACAO) && $this->permCrud) {
			$dados_ins = array();
			foreach ($APLICACAO as $v) {
				$dados_ins[] = ['id_usuario' => $ID_USUARIO, 'id_aplicacao' => $v];
			}
			$tot = $this->acessosUsuarioModel->insertBatch($dados_ins);
		} else {
			$tot = $del->connID->affected_rows;
		}
		echo json_encode(['TOT'=>$tot]);
	}
	public function update_acesso()
	{
		extract($this->request->getPost());
		$tot = 0;
		if ($this->permCrud) {
			$totAp = $this->acessosUsuarioModel->where(['id_usuario' => $usuario, 'id_aplicacao' => $aplicacao])->findAll();
			if (count($totAp) == 0) {
				$ins = [$campo => $bool, 'id_usuario' => $usuario, 'id_aplicacao' => $aplicacao];
				$this->acessosUsuarioModel->insert($ins);
			} else {
				$this->acessosUsuarioModel->set($campo, $bool);
				$this->acessosUsuarioModel->where(['id_usuario' => $usuario, 'id_aplicacao' => $aplicacao]);
				$aff = $this->acessosUsuarioModel->update();
			}
			$tot =  $this->acessosUsuarioModel->affectedRows();
		}
		echo $tot;
	}
}
