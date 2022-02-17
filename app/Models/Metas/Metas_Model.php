<?php

namespace App\Models\Metas;

use App\Models\My_model;

class Metas_Model extends My_model
{
    protected $table = 'metas';
    protected $allowedFields = ['competencia', 'tipo', 'equipe', 'venda', 'instalacao'];
}