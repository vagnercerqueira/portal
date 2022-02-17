<?php

namespace App\Controllers\Ven;

use App\Controllers\Tb_basicas\Tb_basicas;

class Ven001 extends Tb_basicas
{
    public function __construct()
    {
        $this->titulo = "Status Ativacao";
        $this->modelo = "status_ativacoes";

        $this->tbs_crud  = ['form_basicos' => $this->modelo];
    }
}