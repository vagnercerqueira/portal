<?php

namespace App\Models\Vendas;

use App\Models\My_model;

class FormaPagamento_Model extends My_model
{
    protected $table = 'forma_pagamento';
    protected $allowedFields = ['descricao'];
}