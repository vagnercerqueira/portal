<?php

namespace App\Controllers\Ven;

use App\Controllers\BaseController;

class Ven021 extends BaseController
{

    public function __construct()
    {
        $this->titulo = "Peso dos dias";
        $this->db = db_connect();
    }
    public function index()
    {
        $data = [
            "arquivo_js" => ['jquery.mask.min'],
            "arquivo_dataTable" => true,
        ];
        $this->load_template($data);
    }
    public function existe_competencia()
    {
        $comp = $this->request->getPost('COMPETENCIA');
        $sql = "SELECT *, DATE_FORMAT(data, '%d/%m/%Y') dtpt,
                0 as trabalhado,
                CASE WHEN data = date_format( now(), '%Y-%m-%d') THEN 'class=alert-success' ELSE '' END as cl,
                null diasemana
                FROM peso_dias
                WHERE DATE_FORMAT(data, '%Y-%m') ='{$comp}' ";

        $rows = $this->db->query($sql)->getResultArray();

        $trabAnt = 0;

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        foreach ($rows as $k => $v) {
            if ($k == 0) {
                $trabalhado = $v['peso'];
            } else {
                $trabalhado = $v['peso'] + $trabAnt;
            }
            $rows[$k]['trabalhado'] = $trabalhado;
            $rows[$k]['diasemana'] = remove_acentos(utf8_encode(strftime('%A', strtotime($v['data']))));

            $trabAnt = $trabalhado;
        }

        echo json_encode($rows);
    }
    public function salva_competencia()
    {
        $dPost = $this->request->getPost();
        $this->db->transStart();
        foreach ($dPost['dias'] as $k => $v) {
            $sql = "INSERT INTO peso_dias (data, peso)
                        VALUES ('{$v}', '{$dPost['peso'][$k]}')
                    ON DUPLICATE KEY UPDATE peso='{$dPost['peso'][$k]}'";
            $this->db->query($sql);
        }
        $this->db->transComplete();

        echo json_encode(['tot' => 1]);
    }
}
