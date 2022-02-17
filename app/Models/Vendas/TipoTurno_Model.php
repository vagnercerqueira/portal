<?php

namespace App\Models\Vendas;

use App\Models\My_model;

class TipoTurno_Model extends My_model
{
    protected $table = 'tipo_turno';
    protected $allowedFields = ['descricao'];
}