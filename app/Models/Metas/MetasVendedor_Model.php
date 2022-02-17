<?php

namespace App\Models\Metas;

use App\Models\My_model;

class MetasVendedor_Model extends My_model
{
    protected $table = 'meta_vendedor';
    protected $allowedFields = ['competencia', 'tipo', 'equipe', 'venda', 'instalacao'];
}