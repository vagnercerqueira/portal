<?php



namespace App\Models\Vendas;



use App\Models\My_model;



class Vendas_Model extends My_model

{

    protected $table = 'vendas';

    protected $allowedFields = ['audio_audit_quality_1', 'audio_audit_quality_2', 'zap_agendamento1', "zap_reagendamento_1", "zap_reagendamento_2", "zap_reagendamento_3", "pag_banco_csv", "pdf_audit"];

}