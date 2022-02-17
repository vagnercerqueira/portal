<?php

namespace App\Models\Vendas;

use App\Models\My_model;

class Tv_Model extends My_model
{
    protected $table = 'plano_tv';
    protected $allowedFields = ['descricao'];
}