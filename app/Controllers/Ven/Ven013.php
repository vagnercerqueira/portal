<?php

namespace App\Controllers\Ven;

use App\Controllers\BaseController;
use Datatables_server_side;

class Ven013 extends BaseController
{
    public function __construct()
    {
        $this->titulo = "UF de atua&ccedil;&atilde;o";
        $this->tbs_crud  = ['form_generic' => 'uf_atuacao'];
        // $this->modelo = "uf_atuacao";


        //$this->tbs_crud  = ['form_basicos' => $this->modelo];
    }

    public function index()
    {
        $data = [
            "fields" => $this->fields_forms(),
            "generic_forms_js" => true,
            "tb_colunas" => $this->table_colunas(),
            "arquivo_dataTable" => true,
        ];
        $this->load_template($data, 'generic_forms');
    }


    public function fields_forms()
    {

        helper('estados_helper');
        $fields = [
            [
                'tag' => 'select',
                'label' => 'ESTADO',
                'attrs' => [
                    'name'        => 'uf',
                    'class'     => 'form-control form-control-sm'
                ],
                'options' =>  estados()
            ]
        ];
        return $fields;
    }

    public function table_colunas()
    {
        $colunas = [
            'UF',
            'acao'
        ];
        return $colunas;
    }

    public function DataTable()
    {
        $sql = "SELECT id, uf FROM uf_atuacao A";

        $dt = new Datatables_server_side([
            'tb' => 'uf_atuacao',
            'cols' => ["id", "uf"],
        ]);

        $dt->complexQuery($sql);
    }
}