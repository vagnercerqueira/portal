<?php

namespace App\Models\Vendas;

use App\Models\My_model;

class ComboPlanos_Model extends My_model
{
    protected $table = 'combo_planos';
    protected $allowedFields = ['descricao'];
}