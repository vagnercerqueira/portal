<?php

namespace App\Models\Vendas;

use App\Models\My_model;

class SetorTratamento_Model extends My_model
{
    protected $table = 'setor_tratamento_vendas';
    protected $allowedFields = ['descricao'];
}