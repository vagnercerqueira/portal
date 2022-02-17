<?php

namespace App\Controllers\Prod;

use App\Controllers\BaseController;
use App\Models\Usuarios\Equipe_usuarioModel;
use Datatables_server_side;

class Prod002 extends BaseController
{
    private  $arrMeta = [NULL=>'NAO DEFINIDA', 1=>'QUANTIDADE', 2=>'FATURAMENTO'];
    public function __construct()

    {
        $this->titulo = "Produ&ccedil;&atilde;o Mensal";
        $this->db = db_connect();
    }
    public function index()


    {
        $comp = date('Y-m');
        $data = [
            'option_equipes'=>form_dropdown('EQUIPE', $this->retorna_requipes($comp), '', "id='EQUIPE' class='form-control form-control-sm'"),            
        ];
        $this->load_template($data);
    }

    public function producao(){
        $dPost = $this->request->getPost();
        $calculoProducao =  $this->calculoProducao();       
        $dados_vendedor = $this->monta_query_equipe( $dPost['COMPETENCIA'] , $dPost['EQUIPE']);
        echo json_encode(['tendencia'=>$calculoProducao, 'equipe'=>$dados_vendedor]);
    }

    public function calculoProducao()
    {
        $this->db = db_connect();
        $competencia  = $this->request->getPost('COMPETENCIA');
        $equipe  = $this->request->getPost('EQUIPE');
        $cod_interno = session()->get('cod_interno');
        $cod_usuario = session()->get('id_usuario');

        if ($cod_interno == 9999 || $cod_interno == 1000) {
            $tipo_busca = "";
        } else {
            $tipo_busca = " AND A.id = $cod_usuario";
        }
        $sql_tp_meta = "SELECT tipo FROM tipo_meta WHERE DATE_FORMAT(competencia, '%Y-%m') ='{$competencia}'";
        $row =  $this->db->query($sql_tp_meta)->getResultArray();
        if (count($row) > 0) {
            if ($row[0]["tipo"] == 2) { //1 = unitario 2 = faturamento
                $query_complemento = " SELECT SUM(E.faturamento) FROM vendas E WHERE E.id_supervisor = A.id AND DATE_FORMAT(dt_venda_csv, '%Y-%m') ='{$competencia}' ";
            } else {
                $query_complemento = " SELECT COUNT(E.id) FROM vendas E WHERE E.id_supervisor = A.id AND DATE_FORMAT(dt_venda_csv, '%Y-%m') ='{$competencia}' ";
            }
        } else {
            $query_complemento = " SELECT COUNT(E.id) FROM vendas E WHERE E.id_supervisor = A.id AND DATE_FORMAT(dt_venda_csv, '%Y-%m') ='{$competencia}' ";
        }

        $where = ( $equipe != 0 ? " AND C.id=".$equipe : null );


        $sql = "SELECT 
                        UPPER(C.descricao) equipe, 
                        UPPER(A.nome) supervisor, 
                        COALESCE((SELECT D.venda FROM metas D WHERE D.equipe = C.id AND DATE_FORMAT(competencia, '%Y-%m') ='{$competencia}'),0) meta,
                       COALESCE(($query_complemento),0) acumulado,
                        FORMAT(COALESCE((COALESCE(($query_complemento),0)/COALESCE((SELECT D.venda FROM metas D WHERE D.equipe = C.id AND DATE_FORMAT(competencia, '%Y-%m') ='{$competencia}'),0)),0)*100,2) atingido,
                        
                        FORMAT((
                        (COALESCE(($query_complemento),0)/
                        (SELECT SUM(B.peso) FROM peso_dias B WHERE DATE_FORMAT(data, '%Y-%m') ='{$competencia}' AND B.data < CURRENT_DATE))*
                        (SELECT SUM(A.peso) trabalho FROM peso_dias A WHERE DATE_FORMAT(data, '%Y-%m') ='{$competencia}')),2) tend_abs,
                        FORMAT(
                        COALESCE(            
                        (((COALESCE(($query_complemento),0)/
                        (SELECT SUM(B.peso) FROM peso_dias B WHERE DATE_FORMAT(data, '%Y-%m') ='{$competencia}' AND B.data < CURRENT_DATE))*
                        (SELECT SUM(A.peso) trabalho FROM peso_dias A WHERE DATE_FORMAT(data, '%Y-%m') ='{$competencia}'))/COALESCE((SELECT D.venda FROM metas D WHERE D.equipe = C.id AND DATE_FORMAT(competencia, '%Y-%m') ='{$competencia}'),0))*100,0)
                        ,2) tend_rel,        
                        FORMAT(
                            (
                            COALESCE((SELECT D.venda FROM metas D WHERE D.equipe = C.id AND DATE_FORMAT(competencia, '%Y-%m') ='{$competencia}'),0) - 
                            COALESCE(($query_complemento),0))/
                            COALESCE(
                                (SELECT SUM(A.peso) trabalho FROM peso_dias A WHERE DATE_FORMAT(data, '%Y-%m') ='{$competencia}') - 
                                (SELECT SUM(B.peso) FROM peso_dias B WHERE DATE_FORMAT(data, '%Y-%m') ='{$competencia}' AND B.data < CURRENT_DATE),0)
                        ,2)
                        diaria_cem,
                        FORMAT(
                            (
                            COALESCE(((SELECT D.venda FROM metas D WHERE D.equipe = C.id AND DATE_FORMAT(competencia, '%Y-%m') ='{$competencia}')/100)*80,0) - 
                            COALESCE(($query_complemento),0))/
                            COALESCE(
                                (SELECT SUM(A.peso) trabalho FROM peso_dias A WHERE DATE_FORMAT(data, '%Y-%m') ='{$competencia}') - 
                                (SELECT SUM(B.peso) FROM peso_dias B WHERE DATE_FORMAT(data, '%Y-%m') ='{$competencia}' AND B.data < CURRENT_DATE),0)
                        ,2)
                        diaria_oitenta
                    FROM usuarios A
                    INNER JOIN grupo_usuario B ON (A.grupo = B.id)
                    INNER JOIN equipe_usuario C ON (C.supervisor = A.id)
                    WHERE 
                    B.cod_interno IN (970, 990)
                    AND A.status = 'A' 
                    {$where}
                    $tipo_busca ORDER BY tend_rel DESC";
                    

        $row = $this->db->query($sql)->getResultArray();
        $linha = "";
        foreach ($row as $k => $v) {
            $linha .=
                "<tr>
                <td>" . $v["equipe"] . "</td>
                <td>" . $v["supervisor"] . "</td>
                <td class='text-center'>" . $v["meta"] . "</td>
                <td class='text-center'>" . $v["acumulado"] . "</td>
                <td class='text-center'>" . $v["atingido"] . "</td>
                <td class='text-center'>" . $v["tend_abs"] . "</td>
                <td class='text-center'>" . $v["tend_rel"] . "</td>
                <td class='text-center'>" . $v["diaria_cem"] . "</td>
                <td class='text-center'>" . $v["diaria_oitenta"] . "</td>
            </tr>";
        }
        return $linha;
    }

    public function monta_query_equipe($mes = "", $equipe=0)
    {
        
        $ultDia = date("t", strtotime($mes . "-01"));
        $dias = range(1, $ultDia);

        $tpMeta = $this->meta($mes);

        $where = ( $equipe != 0 ? " AND A.equipe_id='{$equipe}' " : null );

        $equipes = $this->retorna_requipes($mes, $equipe);
        
        unset($equipes[0]);
        
        $arr = ['dias' => $dias, 'equipes' => [], 'tpmeta' => $this->arrMeta[$tpMeta], 'TOT' => []];
        //print_r($tpMeta);exit;
        if($tpMeta !== NULL){
            foreach ($equipes as $k => $v) {  
                $sql = "";
                $i = 0;
                $arr['equipes'][$v] = [];
                foreach ($dias as $g=>$j) {
                    $di = $mes . "-" . $j;                    
                    $sql .= ($i > 0 ? ',' : '') .
                        "(  SELECT 
                            " . ($tpMeta == '1' ? 'COUNT(*)' : 'COALESCE(SUM(faturamento),0)') .
                        "   FROM vendas 
                            WHERE  equipe='{$k}'
                            AND dt_venda_csv='{$di}'
                            -- AND status_ativacao is not null
                        ) d_{$j}";
                    $i++;
                }
                
                $row_venda = $this->db->query("select " . $sql . " from dual")->getRowArray();       
                
                $media_atual = (date('Y-m') == $mes ? array_sum($row_venda) / date('d') :  array_sum($row_venda) / $ultDia);
                $arr['equipes'][$v] = ['dias_equipe' => array_values($row_venda), 'total' => array_sum($row_venda), 'media_atual' => $media_atual];
            }
        }
       
        $arr_tot = [];
        foreach($arr['equipes'] as $k=>$v) {
            foreach($v['dias_equipe'] as $m=>$n) { 
                array_key_exists($m, $arr_tot) ? $arr_tot[$m] += $n : $arr_tot[$m] = $n;
            }
        }
        
        $arr_tot[] = array_sum($arr_tot);		
        $arr['total_geral'] = $arr_tot;
		
        return $arr;        
    }
    public function retorna_requipes($mes, $equipe=0){
        $where = null;
        if($equipe != 0){
            $where .= " AND  C.id='{$equipe}'";
        }
        $sql = "
                SELECT '0' id, 'Todas...' equipe FROM DUAL
                UNION
                SELECT C.id, CONCAT(C.descricao, ' (', A.nome,')') equipe
                FROM usuarios A
                INNER JOIN grupo_usuario B ON B.id=A.grupo
                INNER JOIN equipe_usuario C ON C.supervisor=A.id
                WHERE ( A.status='A' OR A.id IN( SELECT id_vendedor FROM vendas WHERE DATE_FORMAT(dt_venda_csv, '%Y-%m')='{$mes}' ) )
                {$where}
                AND B.cod_interno in (970, 990)";
                
        $equipes = array_column(($this->db->query($sql)->getResultArray()), 'equipe', 'id');          
        return $equipes;
    } 
    
    public function ajax_retorna_requipes(){ 
        $dPost = $this->request->getPost();
        $rows = $this->retorna_requipes($dPost['COMPETENCIA']);
        $meta = $this->meta($dPost['COMPETENCIA']);
        echo json_encode(['dados'=>$rows, 'meta'=>$this->arrMeta[$meta]]);
    }
    public function meta($mes){       
        $row = $this->db->query("SELECT tipo FROM tipo_meta WHERE DATE_FORMAT(competencia, '%Y-%m')='{$mes}'")->getRowArray()['tipo'];
        return $row;
    }
}