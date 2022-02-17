<?php

namespace App\Controllers\Ven;

use App\Controllers\Tb_basicas\Tb_basicas;

class Ven008 extends Tb_basicas
{
    public function __construct()
    {
        $this->titulo = "Forma pagamento";
        $this->modelo = "forma_pagamento";

        $this->tbs_crud  = ['form_basicos' => $this->modelo];
    }
    public function preInsUpd($dados)

    {
        $dados["DESCRICAO"] = remove_acentos($dados["DESCRICAO"]);
        return $dados;
    }
}