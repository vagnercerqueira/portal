<?php
/*
	DESCRICAO: [GERAR GRAFICO]
	@AUTOR: Isaque Cerqueira
	DATA: 10/2021
*/

namespace App\Controllers\Graf;


use App\Controllers\BaseController;

class Graf001 extends BaseController
{
    private $competencia = null;
    public function __construct()
    {
        $this->db = db_connect();
    }

    public function index()
    {
        $dados = [
            "arquivo_dataTable" => true,
            'titulo' => 'Dashboard',
            "arquivo_js" => array("highcharts/highcharts", "highcharts/accessibility", "highcharts/data", "highcharts/drilldown", "highcharts/exporting", "highcharts/export-data"),
            "titulo" => "Bruto x Instalado",
        ];
        $this->load_template($dados);
    }
    public function geraGrafico()
    {
        $this->competencia  = $this->request->getPost('COMPETENCIA');
        //venda bruta e instalado
        $sql = "SELECT 
                    COALESCE((SELECT 
							COUNT(*) 
						FROM vendas C WHERE MONTH(C.dt_venda_csv) = MONTH(A.dt_venda_csv)), 0) qtd_venda,
					COALESCE((SELECT 
                            COUNT(*) 
                        FROM vendas B
                        WHERE 
                        B.status_bov = 'Concluído' and MONTH(B.dt_instalacao) = MONTH(A.dt_instalacao)
		                GROUP BY MONTH(A.dt_instalacao) ), 0) qtd_instalada,
                        CASE 
                            WHEN MONTH(dt_venda_csv) = '1' THEN 'Jan'
                            WHEN MONTH(dt_venda_csv) = '2' THEN 'Fev'
                            WHEN MONTH(dt_venda_csv) = '3' THEN 'Mar'
                            WHEN MONTH(dt_venda_csv) = '4' THEN 'Abr'
                            WHEN MONTH(dt_venda_csv) = '5' THEN 'Mai'
                            WHEN MONTH(dt_venda_csv) = '6' THEN 'Jun'
                            WHEN MONTH(dt_venda_csv) = '7' THEN 'Jul'
                            WHEN MONTH(dt_venda_csv) = '8' THEN 'Ago'
                            WHEN MONTH(dt_venda_csv) = '9' THEN 'Set'
                            WHEN MONTH(dt_venda_csv) = '10' THEN 'Out'
                            WHEN MONTH(dt_venda_csv) = '11' THEN 'Nov'
                            WHEN MONTH(dt_venda_csv) = '12' THEN 'Dez'
                        END mes
                FROM vendas A
                WHERE  YEAR(A.dt_venda_csv) = '{$this->competencia}'
                GROUP BY MONTH(dt_venda_csv)";
		//print_r($sql); exit;

        $row = $this->db->query($sql)->getResultArray();
        $vendas_brutas = $this->geraGraficoBruta();
        $vendas_instaladas = $this->geraGraficoInstalado();
        echo json_encode(["resultado" => $row, "bruto" => $vendas_brutas, "instalado" => $vendas_instaladas]);
    }
    public function geraGraficoBruta()
    {
        $sql = "SELECT 				
        -- MONTH(A.dt_venda_csv) mes,
                COALESCE(B.descricao, 'S EQUIPE') EQUIPE,
                COUNT(*) qtd_venda,
                CASE WHEN MONTH(A.dt_venda_csv) = 1 THEN (select count(*) from vendas C where month(C.dt_venda_csv) = 1 AND C.equipe = A.equipe) ELSE (select count(*) from vendas C where month(C.dt_venda_csv) = 1 AND C.equipe = A.equipe) END 'JAN',    
                CASE WHEN MONTH(A.dt_venda_csv) = 2 THEN (select count(*) from vendas D where month(D.dt_venda_csv) = 2 AND D.equipe = A.equipe) ELSE (select count(*) from vendas D where month(D.dt_venda_csv) = 2 AND D.equipe = A.equipe) END 'FEV',
                CASE WHEN MONTH(A.dt_venda_csv) = 3 THEN (select count(*) from vendas E where month(E.dt_venda_csv) = 3 AND E.equipe = A.equipe) ELSE (select count(*) from vendas E where month(E.dt_venda_csv) = 3 AND E.equipe = A.equipe) END 'MAR',
                CASE WHEN MONTH(A.dt_venda_csv) = 4 THEN (select count(*) from vendas F where month(F.dt_venda_csv) = 4 AND F.equipe = A.equipe) ELSE (select count(*) from vendas F where month(F.dt_venda_csv) = 4 AND F.equipe = A.equipe) END 'ABR',
                CASE WHEN MONTH(A.dt_venda_csv) = 5 THEN (select count(*) from vendas G where month(G.dt_venda_csv) = 5 AND G.equipe = A.equipe) ELSE (select count(*) from vendas G where month(G.dt_venda_csv) = 5 AND G.equipe = A.equipe) END 'MAI',
                CASE WHEN MONTH(A.dt_venda_csv) = 6 THEN (select count(*) from vendas H where month(H.dt_venda_csv) = 6 AND H.equipe = A.equipe) ELSE (select count(*) from vendas H where month(H.dt_venda_csv) = 6 AND H.equipe = A.equipe) END 'JUN',
                CASE WHEN MONTH(A.dt_venda_csv) = 7 THEN (select count(*) from vendas I where month(I.dt_venda_csv) = 7 AND I.equipe = A.equipe) ELSE (select count(*) from vendas I where month(I.dt_venda_csv) = 7 AND I.equipe = A.equipe) END 'JUL',
                CASE WHEN MONTH(A.dt_venda_csv) = 8 THEN (select count(*) from vendas J where month(J.dt_venda_csv) = 8 AND J.equipe = A.equipe) ELSE (select count(*) from vendas J where month(J.dt_venda_csv) = 8 AND J.equipe = A.equipe) END 'AGO',
                CASE WHEN MONTH(A.dt_venda_csv) = 9 THEN (select count(*) from vendas L where month(L.dt_venda_csv) = 9 AND L.equipe = A.equipe) ELSE (select count(*) from vendas L where month(L.dt_venda_csv) = 9 AND L.equipe = A.equipe) END 'SET',
                CASE WHEN MONTH(A.dt_venda_csv) = 10 THEN (select count(*) from vendas M where month(M.dt_venda_csv) = 10 AND M.equipe = A.equipe) ELSE (select count(*) from vendas M where month(M.dt_venda_csv) = 10 AND M.equipe = A.equipe) END 'OUT',
                CASE WHEN MONTH(A.dt_venda_csv) = 11 THEN (select count(*) from vendas N where month(N.dt_venda_csv) = 11 AND N.equipe = A.equipe) ELSE (select count(*) from vendas N where month(N.dt_venda_csv) = 11 AND N.equipe = A.equipe) END 'NOV',
                CASE WHEN MONTH(A.dt_venda_csv) = 12 THEN (select count(*) from vendas O where month(O.dt_venda_csv) = 12 AND O.equipe = A.equipe) ELSE (select count(*) from vendas O where month(O.dt_venda_csv) = 12 AND O.equipe = A.equipe) END 'DEZ'                        
    FROM vendas A
    LEFT JOIN equipe_usuario B ON (B.id = A.equipe)
    WHERE  YEAR(A.dt_venda_csv) = '$this->competencia'
    GROUP BY EQUIPE, 'JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'OUT', 'NOV', 'DEZ'";
        $row = $this->db->query($sql)->getResultArray();
        return $row;
    }
    public function geraGraficoInstalado()
    {
        $sql = "SELECT 				
                -- MONTH(A.dt_instalacao) mes,
                        COALESCE(B.descricao, 'S EQUIPE') EQUIPE,
                        COUNT(*) qtd_venda,
                        CASE WHEN MONTH(A.dt_instalacao) = 1 THEN (select count(*) from vendas C where month(C.dt_instalacao) = 1 AND C.equipe = A.equipe AND C.status_bov = 'Concluído') ELSE (select count(*) from vendas C where month(C.dt_instalacao) = 1 AND C.equipe = A.equipe AND C.status_bov = 'Concluído') END 'JAN',
						
                        CASE WHEN MONTH(A.dt_instalacao) = 2 THEN (select count(*) from vendas D where month(D.dt_instalacao) = 2 AND D.equipe = A.equipe AND D.status_bov = 'Concluído') ELSE (select count(*) from vendas D where month(D.dt_instalacao) = 2 AND D.equipe = A.equipe AND D.status_bov = 'Concluído') END 'FEV',
						
                        CASE WHEN MONTH(A.dt_instalacao) = 3 THEN (select count(*) from vendas E where month(E.dt_instalacao) = 3 AND E.equipe = A.equipe AND E.status_bov = 'Concluído') ELSE (select count(*) from vendas E where month(E.dt_instalacao) = 3 AND E.equipe = A.equipe AND E.status_bov = 'Concluído') END 'MAR',
						
                        CASE WHEN MONTH(A.dt_instalacao) = 4 THEN (select count(*) from vendas F where month(F.dt_instalacao) = 4 AND F.equipe = A.equipe AND F.status_bov = 'Concluído') ELSE (select count(*) from vendas F where month(F.dt_instalacao) = 4 AND F.equipe = A.equipe AND F.status_bov = 'Concluído') END 'ABR',
						
                        CASE WHEN MONTH(A.dt_instalacao) = 5 THEN (select count(*) from vendas G where month(G.dt_instalacao) = 5 AND G.equipe = A.equipe AND G.status_bov = 'Concluído') ELSE (select count(*) from vendas G where month(G.dt_instalacao) = 5 AND G.equipe = A.equipe AND G.status_bov = 'Concluído') END 'MAI',
						
                        CASE WHEN MONTH(A.dt_instalacao) = 6 THEN (select count(*) from vendas H where month(H.dt_instalacao) = 6 AND H.equipe = A.equipe AND H.status_bov = 'Concluído') ELSE (select count(*) from vendas H where month(H.dt_instalacao) = 6 AND H.equipe = A.equipe AND H.status_bov = 'Concluído') END 'JUN',
						
                        CASE WHEN MONTH(A.dt_instalacao) = 7 THEN (select count(*) from vendas I where month(I.dt_instalacao) = 7 AND I.equipe = A.equipe AND I.status_bov = 'Concluído') ELSE (select count(*) from vendas I where month(I.dt_instalacao) = 7 AND I.equipe = A.equipe AND I.status_bov = 'Concluído') END 'JUL',
						
                        CASE WHEN MONTH(A.dt_instalacao) = 8 THEN (select count(*) from vendas J where month(J.dt_instalacao) = 8 AND J.equipe = A.equipe AND J.status_bov = 'Concluído') ELSE (select count(*) from vendas J where month(J.dt_instalacao) = 8 AND J.equipe = A.equipe AND J.status_bov = 'Concluído') END 'AGO',
						
                        CASE WHEN MONTH(A.dt_instalacao) = 9 THEN (select count(*) from vendas L where month(L.dt_instalacao) = 9 AND L.equipe = A.equipe AND L.status_bov = 'Concluído') ELSE (select count(*) from vendas L where month(L.dt_instalacao) = 9 AND L.equipe = A.equipe AND L.status_bov = 'Concluído') END 'SET',
						
                        CASE WHEN MONTH(A.dt_instalacao) = 10 THEN (select count(*) from vendas M where month(M.dt_instalacao) = 10 AND M.equipe = A.equipe AND M.status_bov = 'Concluído') ELSE (select count(*) from vendas M where month(M.dt_instalacao) = 10 AND M.equipe = A.equipe AND M.status_bov = 'Concluído') END 'OUT',
						
                        CASE WHEN MONTH(A.dt_instalacao) = 11 THEN (select count(*) from vendas N where month(N.dt_instalacao) = 11 AND N.equipe = A.equipe AND N.status_bov = 'Concluído') ELSE (select count(*) from vendas N where month(N.dt_instalacao) = 11 AND N.equipe = A.equipe AND N.status_bov = 'Concluído') END 'NOV',
						
                        CASE WHEN MONTH(A.dt_instalacao) = 12 THEN (select count(*) from vendas O where month(O.dt_instalacao) = 12 AND O.equipe = A.equipe AND O.status_bov = 'Concluído') ELSE (select count(*) from vendas O where month(O.dt_instalacao) = 12 AND O.equipe = A.equipe AND O.status_bov = 'Concluído') END 'DEZ'                        
            FROM vendas A
            LEFT JOIN equipe_usuario B ON (B.id = A.equipe)
            WHERE  YEAR(A.dt_instalacao) = '$this->competencia' AND status_bov = 'Concluído'
            GROUP BY EQUIPE, 'JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ'";
		//print_r($sql); exit;
        $row = $this->db->query($sql)->getResultArray();
        return $row;
    }
}