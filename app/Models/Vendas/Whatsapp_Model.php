<?php

namespace App\Models\Vendas;

use App\Models\My_model;

class Whatsapp_Model extends My_model
{
    protected $table = 'mensagem_whatsapp';
    protected $allowedFields = ['zap_agendamento1', 'zap_reagendamento_1', 'zap_reagendamento_2', 'zap_reagendamento_3'];
}