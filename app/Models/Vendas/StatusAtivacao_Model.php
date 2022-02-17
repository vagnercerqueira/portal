<?php

namespace App\Models\Vendas;

use App\Models\My_model;

class StatusAtivacao_Model extends My_model
{
    protected $table = 'status_ativacoes';
    protected $allowedFields = ['descricao'];
}