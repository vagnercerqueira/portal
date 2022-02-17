<?php

namespace App\Models\Vendas;

use App\Models\My_model;

class ParametroUploadsCsv_Model extends My_model
{	
    protected $table = 'parametros_uploads_csv';
	protected $allowedFields = ['venda_lote', 'bov', 'mailing'];
}