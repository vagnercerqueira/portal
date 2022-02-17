<?php

namespace App\Controllers\Ven;

use App\Controllers\Tb_basicas\Tb_basicas;

class Ven004 extends Tb_basicas
{
    public function __construct()
    {
        $this->titulo = "Cadastro Fibra";
        $this->modelo = "plano_fibra";

        $this->tbs_crud  = ['form_basicos' => $this->modelo];
    }
}