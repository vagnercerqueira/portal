<?php

namespace App\Controllers\Usu;

use App\Controllers\Tb_basicas\Tb_basicas;

class Usu004 extends Tb_basicas
{
	public function __construct()
	{
		$this->titulo = "Grupo Usuarios";
		$this->modelo = PREFIXO_TB . "grupo_usuario";
		$this->field_extra = ['home' => ['type' => 'varchar', 'size' => 20], 'cod_interno' => ['type' => 'decimal', 'size' => 6], 'formsearch' => ['char', 'size' => 1]];

		$this->arr_extra = $this->monta_diretorios();
		$this->mwhere = [" superusuario = 'N' "];
		$this->tbs_crud  = ['form_basicos' => $this->modelo];
	}

	public function monta_diretorios()
	{

		$dir    = APPPATH . 'Controllers/Home/';
		$files = scandir($dir, 1);

		$select = "<label for='HOME'>Home</label>
		<select class='form-control form-control-sm' required id='HOME' name='HOME'>";
		foreach ($files as $k => $v) {
			if (!in_array($v, ['.', '..', 'Home_superusuario.php'])) {
				$op = explode('.', $v);
				$sel = strtolower($op[0]) == 'home_default' ? 'selected' : null;
				$select .= "<option $sel value='" . strtolower($op[0]) . "'>" . $op[0] . "</option>";
			}
		}
		$select .= "</select>";

		$formsearch = "<label for='FORMSEARCH'>Formulario de busca</label>
		<select class='form-control form-control-sm' required id='FORMSEARCH' name='FORMSEARCH'>
			<option value='S'>S</option><option value='N'>N</option>
		</select>";

		$cod_interno = "<label for='COD_INTERNO'>Codigo</label>
		<input type='number' id='COD_INTERNO' name='COD_INTERNO' min=1 max='999999' autocomplete=off required class='form-control text-center form-control-sm'>";

		return [$cod_interno, $select, $formsearch];
	}
	public function valida_campos()
	{
		$rules = [
			'DESCRICAO' => 'required|max_length[20]|min_length[3]',
		];
		return $this->validate($rules);
	}
}
