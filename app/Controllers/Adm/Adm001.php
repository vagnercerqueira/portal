<?php
/*
	DESCRICAO: [GERAR GRAFICO]
	@AUTOR: Vagner Cerqueira
	DATA: 10/2021
*/

namespace App\Controllers\Adm;


use App\Controllers\BaseController;

class Adm001 extends BaseController
{  
    public function __construct(){}
    public function index()
    {
        $dados = [          
            'titulo' => 'Bancos Sqlite',
        ];
        $this->load_template($dados);
    }   
}