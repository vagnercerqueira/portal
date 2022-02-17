<?php

namespace App\Controllers\Ven;

use App\Controllers\Tb_basicas\Tb_basicas;

class Ven030 extends Tb_basicas
{
    public function __construct()
    {
        $this->titulo = "Cadastro Campanhas";
        $this->modelo = "campanhas";

        $this->tbs_crud  = ['form_basicos' => $this->modelo];
    }
}