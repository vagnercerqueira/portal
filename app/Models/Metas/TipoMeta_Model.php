<?php

namespace App\Models\Metas;

use App\Models\My_model;

class TipoMeta_Model extends My_model
{
    protected $table = 'tipo_meta';
    protected $allowedFields = ['competencia', 'tipo'];
}