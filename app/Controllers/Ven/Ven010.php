<?php

namespace App\Controllers\Ven;

use App\Controllers\Tb_basicas\Tb_basicas;

class Ven010 extends Tb_basicas
{
    public function __construct()
    {
        $this->titulo = "Bancos";
        $this->modelo = "bancos_permitidos";

        $this->tbs_crud  = ['form_basicos' => $this->modelo];
    }

    public function preInsUpd($dados)

    {
        $dados["DESCRICAO"] = remove_acentos($dados["DESCRICAO"]);
        return $dados;
    }
}