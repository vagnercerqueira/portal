<?php

namespace App\Controllers\Met;

use App\Controllers\BaseController;
use Datatables_server_side;

class Met001 extends BaseController
{
    public function __construct()

    {
        $this->modelo = "tipo_meta";
        $this->tbs_crud  = ['form_tipo_meta' => 'tipo_meta'];
        $this->titulo = "Tipo de meta";
    }
    public function index()
    {
        $data = [
            "arquivo_js" => ['jquery.mask.min'],
            "arquivo_dataTable" => true,
        ];
        $this->load_template($data);
    }


    public function DataTable()
    {
        $sql = "SELECT * FROM tipo_meta ORDER BY competencia DESC";

        $dt = new Datatables_server_side([
            'tb' => 'tipo_meta',
            'cols' => [
                "COMPETENCIA", "TIPO"
            ],
            'formata_coluna' => [
                0 => function ($col, $lin) {
                    $competencia = date("m-Y", strtotime($col));
                    return $competencia;
                },
                1 => function ($col, $lin) {
                    if ($col == 1) {
                        $tipo = "Unitario";
                    } else {
                        $tipo = "Faturamento";
                    }
                    return $tipo;
                }
            ]
        ]);
        $dt->complexQuery($sql);
    }

    public function valida_campos()
    {
        $rules = [
            'TIPO'    => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'campo obrigatorio.'
                ]
            ],
            'COMPETENCIA'    => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'campo obrigatorio.'
                ]
            ],
        ];
        return $this->validate($rules);
    }
    public function preInsUpd($dados)

    {
        $dados["COMPETENCIA"] = $dados["COMPETENCIA"] . "-01";

        return $dados;
    }
    public function post_edicao($row)
    {
        $row['COMPETENCIA'] = date("Y-m", strtotime($row['COMPETENCIA']));
        return $row;
    }
}