<?php
/*
	DESCRICAO: [CADASTRO CLIENTE]
	@AUTOR: Isaque Cerqueira
	DATA: 08/2021
*/

namespace App\Controllers\Ven;


use App\Controllers\BaseController;
use App\Models\Vendas\Fibra_Model;
use App\Models\Vendas\Tv_Model;
use App\Models\Vendas\Campanhas_Model;
use Datatables_server_side;


class Ven003 extends BaseController
{
    protected $FibraModel;
    public function __construct()
    {
        $this->FibraModel = new Fibra_Model();
        $this->TvModel = new Tv_Model();
		$this->CampanhasModel = new Campanhas_Model();
        $this->tbs_crud  = ['form_planos' => 'planos_operadora'];
    }
    public function index()
    {
        $fibra = ['' => 'Selecione...'] + array_column($this->FibraModel->findAll(), 'descricao', 'id');
        $tv = ['' => 'Selecione...'] + array_column($this->TvModel->findAll(), 'descricao', 'id');
		$campanhas = ['' => 'Selecione...'] + array_column($this->CampanhasModel->findAll(), 'descricao', 'id');

        $data = [
            "arquivo_js" => ['jquery.mask.min'],
            "arquivo_dataTable" => true,
            "fibra" => form_dropdown('ID_FIBRA', $fibra, '', "id='ID_FIBRA' class='form-control form-control-sm' required"),
            "tv" => form_dropdown('ID_TV', $tv, '', "id='ID_TV' class='form-control form-control-sm'"),
			"campanhas" => form_dropdown('ID_CAMPANHA', $campanhas, '', "id='ID_TV' class='form-control form-control-sm' required"),
        ];
        $this->load_template($data);
    }
    public function DataTable()
    {
        $sql = "SELECT 
                    A.id,
					D.descricao campanha,
                    B.descricao fibra,
                    C.descricao tv,
                    A.valor_faturamento,
                    A.dt_ini,
                    A.dt_fim
                FROM planos_operadora A
                INNER JOIN plano_fibra B ON B.id = A.ID_FIBRA
                LEFT JOIN plano_tv C ON C.id = A.ID_TV
				LEFT JOIN campanhas D ON D.id = A.ID_CAMPANHA";

        $dt = new Datatables_server_side([

            'tb' => 'planos_operadora',
            'cols' => ["campanha","fibra ", "tv",  "valor_faturamento", "dt_ini", "dt_fim"],
            'formata_coluna' => [
                3 => function ($col, $lin) {
                    $vl_faturamento = number_format($col, 2, ",", ".");
                    return  $vl_faturamento;
                },
                4 => function ($col, $lin) {
                    $dt = date('d/m/Y', strtotime($col));
                    return  $dt;
                },
                5 => function ($col, $lin) {
                    $dt_2 = date('d/m/Y', strtotime($col));
                    return  $dt_2;
                },
            ]
        ]);
        $dt->complexQuery($sql);
    }
    public function preInsUpd($dados)

    {
        $dados["VALOR_FATURAMENTO"] = remove_separador_milhar($dados["VALOR_FATURAMENTO"]);
        return $dados;
    }

    public function valida_campos()
    {
        $rules = [
            'ID_FIBRA'             => 'required|integer',
            //'ID_TV'                => "required|integer",
            'VALOR_FATURAMENTO'      => "required|min_length[1]|max_length[6]",
            'DT_INI'                 => 'required|valid_date',
            'DT_FIM'                 => [
                'rules' =>  'required|valid_date|VerificaPlanosOperadora[DT_FIM]',
                'errors' => [
                    'VerificaPlanosOperadora' => 'Ja existe campanha para o periodo.'
                ]

            ],
        ];
        return $this->validate($rules);
    }

    public function post_edicao($row)
    {
        $row['VALOR_FATURAMENTO'] = number_format($row['VALOR_FATURAMENTO'], 2, ",", ".");
        return $row;
    }
}