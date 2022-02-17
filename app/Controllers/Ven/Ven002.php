<?php

namespace App\Controllers\Ven;

use App\Controllers\Tb_basicas\Tb_basicas;

class Ven002 extends Tb_basicas
{
    public function __construct()
    {
        $this->titulo = "Turno";
        $this->modelo = "tipo_turno";
		//'bt_excluir' => false,
        $this->tbs_crud  = ['form_basicos' => $this->modelo];
		$this->bt_editar = false;
		$this->bt_excluir = false;
    }
}