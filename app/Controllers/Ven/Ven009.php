<?php

namespace App\Controllers\Ven;

use App\Controllers\Tb_basicas\Tb_basicas;

class Ven009 extends Tb_basicas
{
    public function __construct()
    {
        $this->titulo = "Dias de vencimento";
        $this->modelo = "dias_vencimento";

        $this->tbs_crud  = ['form_basicos' => $this->modelo];
    }
}