<?php

/*

	DESCRICAO: [MENSAGENS WHATSAPP]

	@AUTOR: Isaque Cerqueira

	DATA: 08/2021

*/



namespace App\Controllers\Ven;



use App\Controllers\BaseController;

use App\Models\Vendas\Whatsapp_Model;

use Datatables_server_side;



class Ven014 extends BaseController

{
    private $lista_campos = [
        '<NOME_CLIENTE_CSV>' => 'Cliente',
        '<TURNO_AGENDAMENTO>' => 'Turno',
        '<DT_AGENDAMENTO>' => 'Data agendamento',
        '<DT_REAGENDAMENTO>' => 'Data Reagendamento',
        '<TURNO_REAGENDAMENTO>' => 'Turno Reagendamento'
    ];

    public function __construct()

    {

        $this->modelo = "mensagem_whatsapp";

        $this->tbs_crud  = ['form_mensagens' => 'mensagem_whatsapp'];

        $this->whatsapp_model = new Whatsapp_Model();
    }

    public function index()

    {

        $data = [

            "arquivo_js" => ['jquery.mask.min'],

            "arquivo_dataTable" => true,

            "option_lista_campos" => $this->lista_campos

        ];

        $this->load_template($data);
    }

    public function DataTable()

    {

        $sql = "SELECT

                    id, 

                    zap_agendamento1,

                    zap_reagendamento_1

                FROM mensagem_whatsapp";



        $dt = new Datatables_server_side([

            'tb' => 'mensagem_whatsapp',

            'cols' => ["zap_agendamento1", "zap_reagendamento_1"],

            //'bt_editar' => false,

            'bt_excluir' => false,

            'formata_coluna' => [

                0 => function ($col, $lin) {

                    $mensagem = substr($col, 0, 40) . "...";

                    return $mensagem;
                },

                1 => function ($col, $lin) {

                    $mensagem = substr($col, 0, 40) . "...";

                    return $mensagem;
                }
            ]

        ]);

        $dt->complexQuery($sql);
    }
}