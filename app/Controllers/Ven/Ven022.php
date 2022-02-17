<?php

namespace App\Controllers\Ven;

use App\Controllers\BaseController;
use App\Models\Usuarios\Grupo_usuarioModel;
use Datatables_server_side;

class Ven022 extends BaseController
{
    //private $campos_inpt = ['DFV' => 'DFV', 'BOV' => 'BOV', 'BLINDAGEM'=>'BLINDAGEM', 'LINHA_PGTO'=>'LINHA_PGTO'];

    private $campos_venda_lote = [];
    public function __construct()

    {
        $this->modelo = "grupo_envio_emails";
        $this->tbs_crud  = ['form_envio_emails' => 'grupo_envio_emails'];
        $this->titulo = "DFV";
    }
    public function index()
    {
        $grupoModel = new Grupo_usuarioModel();
        $grupos = ['' => 'Selecione...'] + array_column($grupoModel->where(['superusuario' => 'N'])->findAll(), 'descricao', 'id');

        $data = [
            "arquivo_dataTable" => true,
            "grupo" => form_dropdown('ID_GRUPO', $grupos, '', "id='ID_GRUPO' class='form-control form-control-sm' required"),
        ];
        $this->load_template($data);
    }


    public function DataTable()
    {
        $sql = "SELECT B.DESCRICAO AS GRUPO,BOV_CSV, DFV_CSV, BLINDAGEM_CSV, LINHA_PGTO_CSV, VENDA_LOTE_CSV, MAILING_CSV, A.id
                FROM grupo_envio_emails A
                INNER JOIN grupo_usuario B ON B.id=A.id_grupo";

        $dt = new Datatables_server_side([
            'tb' => 'grupo_envio_emails',
            'cols' => [
                "GRUPO", "BOV_CSV", "DFV_CSV", "BLINDAGEM_CSV", "VENDA_LOTE_CSV", "LINHA_PGTO_CSV", "MAILING_CSV"
            ]
        ]);
        $dt->complexQuery($sql);
    }

    public function valida_campos()
    {
        $rules = [
            'ID_GRUPO'    => [
                'rules'  => 'is_unique[grupo_envio_emails.id_grupo,id,{ID}]',
                'errors' => [
                    'is_unique' => 'Já existe este grupo.'
                ]
            ],
        ];
        return $this->validate($rules);
    }
}
