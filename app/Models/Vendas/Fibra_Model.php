<?php

namespace App\Models\Vendas;

use App\Models\My_model;

class Fibra_Model extends My_model
{
    protected $table = 'plano_fibra';
    protected $allowedFields = ['descricao'];
}