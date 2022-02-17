<?php

function ns_BtnFormulario()
{

	$bt = "<div class='row col_btns_form col_btn_include' style='margin-top: 5px;border-top: 2px solid #17a2b8;padding-top: 5px;padding-bottom: 5px;'>
				<div class='col-xs-6 col-md-3 col-lg-2'>
					<button type='submit' class='btn btn-primary btn-block'>Incluir</button>				
				</div>
				<div class='col-xs-6 col-md-3 col-lg-2'>
					<button type='reset' class='btn btn-danger btn-block' onclick=bt_limpaForm(this)>Listagem</button>
				</div>
			</div>";
	return $bt;
}
function ns_BtNovo()
{
	$sess = session()->get();
	if ($sess['appUser'][$sess['IndPag']]['perm_cadastrar'] !== 'N') {
		$bt = "<div class='row col_btns_form col_btn_novo' style='border-top: 2px solid #17a2b8; padding-top: 5px;padding-bottom: 5px;'>
				<label>&nbsp;</label>
				<div class='col-xs-6 col-md-3 col-lg-2'>
					<button type='button' class='btn btn-primary btn-block' onclick=bt_novo(this)>Novo</button>				
				</div>
			</div>";
	} else {
		$bt = null;
	}
	return $bt;
}
function ns_icon_check($s, $disabled = "disabled", $class = null)
{
	if ($s == 'I' || $s == 0) {
		$btn = '<button type="button" class="btn btn-warning btn-xs ' . $class . '" ' . $disabled . '>
				  <i class="fas fa-user-alt-slash"></i>
				</button>';
	} else {
		$btn = '<button type="button" class="btn btn-success btn-xs ' . $class . '" ' . $disabled . '>
				  <i class="fas fa-user-check"></i> 
				</button>';
	}
	return $btn;
}

function mapa_diretorio($acessos, $menuNav = 'L')
{
	$data = $acessos;

	$itemsByReference = array();
	foreach ($data as $key => &$item) {
		$itemsByReference[$item['id']] = &$item;
		$itemsByReference[$item['id']]['nodes'] = array();
	}
	foreach ($data as $key => &$item) {
		if ($item['id_pai'] && isset($itemsByReference[$item['id_pai']]))
			$itemsByReference[$item['id_pai']]['nodes'][] = &$item;
	}
	foreach ($data as $key => &$item) {
		if (empty($item['nodes']))
			unset($item['nodes']);
		if ($item['id_pai'] && isset($itemsByReference[$item['id_pai']]))
			unset($data[$key]);
	}

	$retorno = ($menuNav == 'L') ? renderMenu($data, ' active') : rtrim(renderMenuTop($data, ' active'), '</ul>');

	return $retorno;
}

function renderMenu($items, $active = null, $i = null, $id = null)
{
	$render = ($i != null) ? '<ul class="nav nav-treeview" id="pagina' . $id . '">' : null;
	foreach ($items as $item) {
		if (!empty($item['nodes'])) {
			$icone = $item['icone'] == "" ? "fas fa-circle" : $item['icone'];
			$render .= '<li class="nav-item has-treeview"><a class="nav-link" href="#" >' . $i . '<i class="nav-icon ' . $icone . '"></i>&nbsp;<p>' . $item['nome'] . "<i class='fas fa-angle-left right'></i></p></a>";
			$render .= renderMenu($item['nodes'], null, $i . '&nbsp;&nbsp;&nbsp;', $item['id']);
		} else {
			$icone = $item['icone'] == "" ? "far fa-circle" : $item['icone'];
			$render .= '<li class="nav-item"><a class="nav-link" href="' . base_url(str_replace('.php', '', strtolower($item['caminho']))) . '">' . $i . '<i class="nav-icon ' . $icone . '"></i>&nbsp;<p>' . $item['nome'] . '</p></a>';
		}
		$render .= '</li>';
	}
	return $render . (($i != null) ? '</ul>' : null);
}
function renderMenuTop($items, $active = null, $i = 1, $id = null)
{
	$render = ($i != 1) ? '<ul aria-labelledby="dropdownSubMenu' . ($i - 1) . '" class="dropdown-menu border-0 shadow" id="pagina' . $id . '">' : null;
	foreach ($items as $item) {
		if ($item['id_pai'] == "" || $item['id_pai'] == 0) {
			$cor =  'color: floralwhite;';
		} else {
			$cor = 'color: black';
		}

		if (!empty($item['nodes'])) {
			$icone = $item['icone'] == "" && $item['id_pai'] > 0  ? "fas fa-circle" : $item['icone'];

			if ($item['id_pai'] == "" || $item['id_pai'] == 0) {
				$li = '<li class="nav-item dropdown">';
				$a =  '<a id="dropdownSubMenu' . ($i++) . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle" href="javascript:;" style="' . $cor . '" ><i class="nav-icon ' . $icone . '"></i>&nbsp;' . $item['nome'] . "</a>";
			} else {
				$li = '<li class="dropdown-divider"></li><li class="dropdown-submenu dropdown-hover">';
				$a = '<b><small><a id="dropdownSubMenu' . ($i++) . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle" href="javascript:;" style="' . $cor . '" >' . $item['nome'] . "</a></small></b>";
			}

			$render .= $li . $a;
			$render .= renderMenuTop($item['nodes'], null, $i, $item['id']);
		} else {
			$icone = $item['icone'] == "" ? "far fa-circle" : $item['icone'];
			$end = $item['caminho'] == ">" ? 'javascript:;' : base_url(str_replace('.php', '', strtolower($item['caminho'])));
			$txt = ($item['id_pai'] == "" || $item['id_pai'] == 0) ? $item['nome'] : "<small>" . $item['nome'] . "</small>";
			$render .= '<li end="' . $end . '" class="nav-item"><a style="' . $cor . '" class="nav-link" href="' . $end . '">' . $txt . '</a>';
		}
		$render .= '</li>';
	}
	return $render . (($i != 1) ? '</ul>' : null);
}
function renderMenuTopBck($items, $active = null, $i = 1, $id = null)
{
	$render = ($i != 1) ? '<ul aria-labelledby="dropdownSubMenu' . ($i - 1) . '" class="dropdown-menu border-0 shadow" id="pagina' . $id . '">' : null;
	foreach ($items as $item) {


		if ($item['id_pai'] == "") {
			$cor =  null;
		} else {
			$cor = 'style="color: black"';
		}

		if (!empty($item['nodes'])) {
			$icone = $item['icone'] == "" ? "fas fa-circle" : $item['icone'];
			$render .= '<li class="nav-item dropdown">
							<a id="dropdownSubMenu' . ($i++) . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle" href="#" ' . $cor . ' >' . $item['nome'] . "</a>";
			$render .= renderMenuTop($item['nodes'], null, $i, $item['id']);
		} else {
			$icone = $item['icone'] == "" ? "far fa-circle" : $item['icone'];
			$render .= '<li class="nav-item"><small><a ' . $cor . ' class="nav-link" href="' . base_url(str_replace('.php', '', strtolower($item['caminho']))) . '">' . $item['nome'] . '</a></small>';
		}
		$render .= '</li>';
	}
	return $render . (($i != 1) ? '</ul>' : null);
}