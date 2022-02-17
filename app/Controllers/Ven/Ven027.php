<?php

namespace App\Controllers\Ven;

use App\Controllers\BaseController;

class Ven027 extends Ven020
{
    private $campos_mailing = [ //ao alterar, n esquece de alterar a lista no js
        0 =>  'NOME',
        1 =>  'CPF',
        2 =>  'EMAIL',
        3 =>  'CONTATO 1',
        4 =>  'CONTATO 2',
        5 =>  'CONTATO 3',
        6 =>  'CONTATO 4',
        7 =>  'CEP',
        8 =>  'UF',
        9 =>  'CIDADE',
        10 => 'BAIRRO',
        11 => 'LOGRADOURO',
        12 => 'NUM FACHADA'
    ];
    public function __construct()

    {
        $this->titulo = "CONSULTA MAILLING";
        $this->db = db_connect();
    }
    public function index()
    {
        $data = [
            "arquivo_js" => ['jquery.mask.min'],
            "arquivo_dataTable" => true,
            "option_nome_mailing" => $this->filtros_mailing('nome_mailing'),
            "option_campos" => $this->campos_mailing,
            "option_tot_clientes" => $this->tot_clientes(),
        ];
        $this->load_template($data);
    }
}
