<?php

namespace App\Models\Vendas;

use App\Models\My_model;

class PlanosOperadora_Model extends My_model
{
    protected $table = 'planos_operadora';
    protected $allowedFields = ['id_fibra', 'id_tv', 'valor_faturamento', 'dt_ini', 'dt_fim'];
    public function BuscaFaturamento($velocidade, $tv, $data_ativacao)
    {

        if ($tv == "") {
            $tv = "AND A.id_tv IS NULL";
        } else {
            $tv = "AND A.id_tv = '{$tv}'";
        }
        $sql = "SELECT 
                    A.valor_faturamento
                FROM planos_operadora A
                WHERE 
                    A.id_fibra = '{$velocidade}' 
                    $tv
                    AND '{$data_ativacao}' BETWEEN A.dt_ini AND  A.dt_fim";
        $rows = $this->query($sql)->getResultArray();
        return $rows[0]['valor_faturamento'];
    }
}