<?php

namespace App\Models\Vendas;

use App\Models\My_model;

class Campanhas_Model extends My_model
{
    protected $table = 'campanhas';
    protected $allowedFields = ['descricao'];
}