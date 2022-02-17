<?php

namespace App\Controllers\Ven;

use App\Controllers\Tb_basicas\Tb_basicas;

class Ven011 extends Tb_basicas
{
    public function __construct()
    {
        $this->titulo = "Combo planos";
        $this->modelo = "combo_planos";

        $this->tbs_crud  = ['form_basicos' => $this->modelo];
    }
    public function preInsUpd($dados)

    {
        $dados["DESCRICAO"] = remove_acentos($dados["DESCRICAO"]);
        return $dados;
    }
}