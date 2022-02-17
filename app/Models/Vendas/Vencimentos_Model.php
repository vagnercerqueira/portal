<?php

namespace App\Models\Vendas;

use App\Models\My_model;

class Vencimentos_Model extends My_model
{
    protected $table = 'dias_vencimento';
    protected $allowedFields = ['descricao'];
}