<?php

namespace App\Models\Vendas;

use App\Models\My_model;

class AcompanhamentoCliente_Model extends My_model
{
    protected $table = 'acompanhamento_cliente';
    protected $allowedFields = ['dt_update', 'edit_usuario_id', 'num_os',  'zap_m_0', 'zap_m_1', 'zap_m_2', 'zap_m_3', 'ativo', 'adimplente'];
}