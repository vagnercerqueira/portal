<?php

namespace App\Controllers\Usu;

use App\Controllers\Tb_basicas\Tb_basicas;

class Usu008 extends Tb_basicas
{
	public function __construct()
	{
		$this->titulo = "Tipo Contratação";
		$this->modelo = "tipo_contratacao";

		$this->tbs_crud  = ['form_basicos' => $this->modelo];
	}

	public function valida_campos()
	{
		$rules = [
			'DESCRICAO' => 'required|max_length[20]|min_length[3]',
		];
		return $this->validate($rules);
	}
}