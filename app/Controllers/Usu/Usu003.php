<?php

namespace App\Controllers\Usu;

use App\Controllers\BaseController;
use App\Models\Usuarios\Acesso_grupoModel;
use App\Models\Usuarios\AplicacoesModel;
use App\Models\Usuarios\Grupo_usuarioModel;

class Usu003 extends BaseController
{

	protected $grupoModel;
	protected $AplicacoesModel;
	protected $acessosModel;
	protected $grp_perm = [];
	private $permAlt = false;
	private $app_pais = [];
	public function __construct()

	{
		$this->grupoModel = new Grupo_usuarioModel();
		$this->AplicacoesModel = new AplicacoesModel();
		$this->acessosModel = new Acesso_grupoModel();

		$pg = session('IndPag');
		$apps = session('appUser');
		$this->permCrud = ($apps[$pg]['perm_cadastrar'] == 'N' || $apps[$pg]['perm_alterar'] == 'N' || $apps[$pg]['perm_excluir'] == 'N') ? false : true;
		$this->permAlt = !$this->permCrud ? 'disabled' : null;
	}
	public function index()
	{

		$grupos = ['' => 'Selecione...'] + array_column($this->grupoModel->where(['superusuario' => 'N'])->findAll(), 'descricao', 'id');
		$data = array(
			"diretorio" => "", //$this->mapa_diretorio(),
			"grupos" => form_dropdown('ID_GRUPO', $grupos, '', "id='ID_GRUPO' class='form-control form-control-sm' required onchange='atualiza_arvore(this)'"),
			"grupos_cp" => form_dropdown('ID_GRUPO_CP', $grupos, '', "id='ID_GRUPO_CP' class='form-control form-control-sm' required"),
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
		$itemsByReference = array();

		// Build array of item references:
		foreach ($rows as $key => &$item) {
			$itemsByReference[$item['id']] = &$item;
			$itemsByReference[$item['id']]['nodes'] = array();
		}

		// Set items as children of the relevant parent item.
		foreach ($rows as $key => &$item)
			if ($item['id_pai'] && isset($itemsByReference[$item['id_pai']])) $itemsByReference[$item['id_pai']]['nodes'][] = &$item;
		// Remove items that were added to parents elsewhere:
		foreach ($rows as $key => &$item) {
			if (empty($item['nodes'])) unset($item['nodes']);
			if ($item['id_pai'] && isset($itemsByReference[$item['id_pai']])) unset($rows[$key]);
		}
		return  '<ul id="myUL" class="list-group">
					<li> Principal<span class="float-right"><span style="writing-mode: vertical-rl;">Cad</span><span style="writing-mode: vertical-rl;">Alt</span><span style="writing-mode: vertical-rl;">Del</span></span>' . $this->renderMenu($rows, ' active', null, $dirs) . '</li></ul>';
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
			if ($ch) {
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
			}

			if (!empty($item['nodes'])) {
				$render .= '<li class="pai" id="pai_' . $item['id'] . '"><input ' . $checked . $this->permAlt . ' type="checkbox" class="input_pai" value="' . $item['id'] . '">&nbsp;<b>' . $space . $item['nome'] . "</b>";
				$render .= $this->renderMenu($item['nodes'], ' active', "&nbsp;&nbsp;&nbsp;", $dirs);
			} else {
				$perm_crud = '<span class="float-right perm_crud" ' . $dispCrud . '  v="' . $item['id'] . '">
								<input ' . $pcDis . $pc . '  type="checkbox" act="perm_cadastrar" class="perm_cad" value="' . $item['id'] . '">&nbsp;&nbsp;&nbsp;
								<input ' . $paDis . $pa . '  type="checkbox" act="perm_alterar" class="perm_upd" value="' . $item['id'] . '">&nbsp;&nbsp;&nbsp;
								<input ' . $peDis . $pe . '  type="checkbox" act="perm_excluir" class="perm_del" value="' . $item['id'] . '"></span>';
				$render .= '<li style="color:grey;margin-top:12px;"><input ' . $checked . $this->permAlt . ' name="APLICACAO[]" class="input_filho" value="' . $item['id'] . '" type="checkbox">&nbsp;' . $item['nome'] . $perm_crud;
			}
			$render .= '</li>';
		}

		return $render . '</ul>';
	}
	private function buspa_pai($idPai, $db)
	{
		$query = " SELECT M.id_pai FROM " . PREFIXO_TB . "aplicacoes M WHERE M.id={$idPai} ";
		$r = $db->query($query)->getRowArray();
		$this->app_pais[] = $idPai;
		if ($r['id_pai'] != "") {
			$this->app_pais[] = $r['id_pai'];
			$this->buspa_pai($r['id_pai'], $db);
		}
	}

	public function atualiza_acesso()
	{
		$db = db_connect();

		$grupo = $this->request->getPost('grupo');
		$query = "	SELECT M.*, N.perm_cadastrar, N.perm_alterar, N.perm_excluir
					FROM " . PREFIXO_TB . "aplicacoes  M
					INNER JOIN " . PREFIXO_TB . "acesso_grupo N ON N.id_aplicacao=M.id
					WHERE N.id_grupo={$grupo} AND caminho != '>' AND lower(caminho) != 'usu/usu006.php'";
		
		$rows = $db->query($query)->getResultArray();
		if (count($rows) > 0) {
			foreach ($rows as $k => $row)
				if ($row['id_pai'] != "" && !in_array($row['id_pai'], $this->app_pais)) $this->buspa_pai($row['id_pai'], $db);

			$ids = implode(',', array_merge(array_column($rows, 'id'), $this->app_pais));

			$query = "SELECT b.*, a.perm_cadastrar, a.perm_alterar, a.perm_excluir
								  FROM " . PREFIXO_TB . "aplicacoes b 
								  LEFT JOIN " . PREFIXO_TB . "acesso_grupo a on b.id=a.id_aplicacao
								  WHERE a.id_grupo={$grupo} and b.ID IN ($ids)  AND lower(caminho) != 'usu/usu006.php'
								  order by ordem asc";
								  //print_r($query);exit;
			$rows = $db->query($query)->getResultArray();
		}
		/*	$query = "WITH RECURSIVE bottom_up_cte AS
		(
		  SELECT M.ID ID_APLICACAO,M.NOME,M.ID_PAI, N.ID AS ID_ACESSO
		  FROM jc_aplicacoes AS M
		  INNER JOIN jc_acesso_grupo N ON N.id_aplicacao=M.id
		  WHERE N.id_grupo={$grupo}
		  UNION
		  SELECT m.ID ID_APLICACAO,m.NOME,m.ID_PAI, null ID_ACESSO FROM bottom_up_cte 
		  INNER JOIN jc_aplicacoes AS m 
			
		  ON bottom_up_cte.ID_PAI = m.ID
		)SELECT * FROM bottom_up_cte";*/ //versao atual o mysql no servidor nao possui essa funcao de recursividade
		$mp = $this->mapa_diretorio($rows);
		echo json_encode(['dirs' => $mp]);
	}
	public function altera_acesso()
	{
		extract($this->request->getPost());
		$del = $this->acessosModel->where('id_grupo', $ID_GRUPO)->delete();

		if (isset($APLICACAO) && $this->permCrud) {
			$dados_ins = array();
			foreach ($APLICACAO as $v) {
				$dados_ins[] = ['id_grupo' => $ID_GRUPO, 'id_aplicacao' => $v];
			}
			$tot = $this->acessosModel->insertBatch($dados_ins);
		} else {
			$tot = $del->connID->affected_rows;
		}
		echo json_encode(['TOT' => $tot]);
	}
	public function update_acesso()
	{
		extract($this->request->getPost());
		$tot = 0;
		if ($this->permCrud) {
			$this->acessosModel->set($campo, $bool);
			$this->acessosModel->where(['id_grupo' => $grupo, 'id_aplicacao' => $aplicacao]);
			$this->acessosModel->update();
			$tot = $this->acessosModel->affectedRows();	
		}
		echo json_encode(['TOT' => $tot]);
	}
}
