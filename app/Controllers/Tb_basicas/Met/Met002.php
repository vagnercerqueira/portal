<?php

namespace App\Controllers\Met;

use App\Controllers\BaseController;
use App\Models\Metas\TipoMeta_Model;
use App\Models\Usuarios\Equipe_usuarioModel;
use Datatables_server_side;

class Met002 extends BaseController
{
    public function __construct()

    {
        $this->modelo = "metas";
        $this->tbs_crud  = ['form_meta' => 'metas'];
        $this->titulo = "Lançamento de metas";
        $this->Equipe_Model = new Equipe_usuarioModel();
    }
    public function index()
    {
        $equipe_vendas = ['' => 'Selecione...'] + array_column($this->Equipe_Model->equipe_supervisor(970), 'equipe', 'id');
        $data = [
            "arquivo_js" => ['jquery.mask.min'],
            "arquivo_dataTable" => true,
            "equipe" => form_dropdown('EQUIPE', $equipe_vendas, '', "id='EQUIPE' class='form-control form-control-sm' required"),
        ];
        $this->load_template($data);
    }


    public function DataTable()
    {
        $sql = "SELECT
                    A.id,
                    A.competencia, 
                    CASE
                        WHEN  tipo = 1
                           THEN 'Unitario'
                        ELSE 'Faturamento'
                    END tipo_meta,
                   C.descricao equipe,
                   A.equipe supervisor,
                    venda, 
                    instalacao 
                FROM metas A
               
                INNER JOIN equipe_usuario C ON (A.equipe = C.id)";

        $dt = new Datatables_server_side([
            'tb' => 'metas',
            'cols' => [
                "competencia", "tipo_meta", "equipe", "supervisor", "venda", "instalacao"
            ],
            'formata_coluna' => [
                0 => function ($col, $lin) {

                    return date("m-Y", strtotime($col));
                },
                3 => function ($col, $lin) {

                    return $this->ProcuraSupervisor(1, $col)['supervisor'];
                },
                4 => function ($col, $lin) {

                    if ($lin["tipo_meta"] == "Unitario") {
                        $valor = number_format($col, 0, "", "");
                    } else {
                        $valor = formata_monetario($col);
                    }
                    return $valor;
                },
                5 => function ($col, $lin) {
                    if ($lin["tipo_meta"] == "Unitario") {
                        $valor = number_format($col, 0, "", "");
                    } else {
                        $valor = formata_monetario($col);
                    }
                    return $valor;
                },
            ]
        ]);
        $dt->complexQuery($sql);
    }

    public function valida_campos()
    {
        $rules = [
            'COMPETENCIA'    => [
                'rules'  => 'required|valid_date',
                'errors' => [
                    'required' => 'campo obrigatorio.'
                ]
            ],
            'TIPO_META'    => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'campo obrigatorio.'
                ]
            ],
            'VENDA'    => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'valor invalido.'
                ]
            ],
            'INSTALACAO'    => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'valor invalido.'
                ]
            ],
        ];
        return $this->validate($rules);
    }
    public function preInsUpd($dados)

    {

        if ($dados["TIPO"] == 2) {
            $dados['VENDA'] = remove_separador_milhar($dados['VENDA']);
            $dados['INSTALACAO'] = remove_separador_milhar($dados['INSTALACAO']);
        }
        $dados["COMPETENCIA"] = $dados["COMPETENCIA"] . "-01";


        return $dados;
    }
    public function post_edicao($row)
    {
        $row['COMPETENCIA'] = date("Y-m", strtotime($row['COMPETENCIA']));
        $row['TIPO_META'] = ($row['TIPO'] == 1 ? "Unitario" : "Faturamento");
        $row['SUPERVISOR'] = $this->ProcuraSupervisor(1, $row['EQUIPE'])['supervisor'];
        if ($row['TIPO'] == 2) {
            $row['VENDA'] = formata_monetario($row['VENDA']);
            $row['INSTALACAO'] = formata_monetario($row['INSTALACAO']);
        } else {
            $row['VENDA'] = number_format($row['VENDA'], 0, "", "");
            $row['INSTALACAO'] = number_format($row['INSTALACAO'], 0, "", "");
        }

        return $row;
    }
    public function ProcuraSupervisor($tipo = null, $id_equipe = null)
    {
        if ($tipo == null) {
            $id_equipe = $this->request->getPost('id');
        }

        $equipe_vendas = $this->Equipe_Model->equipe_supervisor(970, $id_equipe);
        $vendedor = $this->Equipe_Model->Vendedores(960, $id_equipe);
        $option = '<option value="" selected="selected">Selecione...</option>';
        foreach ($vendedor  as $v) {
            $option .= "<option value='" . $v["id"] . "'>" . strtoupper($v["vendedor"]) . "</option>";
        }
        $supervisor_vendedor =  [
            'supervisor' => strtoupper($equipe_vendas[0]['supervisor']),
        ];
        if ($tipo == null) {
            echo json_encode($supervisor_vendedor);
        } else {
            return $supervisor_vendedor;
        }
    }
    public function ProcuraCompetencia($competencia = null)
    {
        $tipoMetaModel = new TipoMeta_Model();
        if ($competencia == null) {
            $competencia = $this->request->getPost('competencia') . "-01";
        }
        $tipo_meta = $tipoMetaModel->where('competencia',  $competencia)->first()['tipo'];
        if ($competencia == null) {
            echo json_encode($tipo_meta);
        } else {
            return $tipo_meta;
        }
    }
    public function trataErroBanco($Err)
    {
        $msgBanco = $this->ErrosDb[$Err['code']];
        if ($Err["code"] == 1062) {
            $msgBanco =  "Já existe meta para essa competencia para a equipe";
        }

        return $msgBanco;
    }
}