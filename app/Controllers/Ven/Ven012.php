<?php

namespace App\Controllers\Ven;

use App\Controllers\Tb_basicas\Tb_basicas;

class Ven012 extends Tb_basicas
{
    public function __construct()
    {
        $this->titulo = "Setor Tratamento vendas";
        $this->modelo = "setor_tratamento_vendas";

        $this->tbs_crud  = ['form_basicos' => $this->modelo];
    }
}