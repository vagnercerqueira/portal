<?php

namespace App\Models\Vendas;

use App\Models\My_model;

class Bancos_Model extends My_model
{
    protected $table = 'bancos_permitidos';
    protected $allowedFields = ['descricao'];
}