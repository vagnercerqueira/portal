<?php

namespace App\Controllers\Ven;
use App\Controllers\BaseController;
use App\Libraries\Fpdf\PDF_MC_Table;
use Datatables_server_side;
use App\Models\Vendas\AcompanhamentoCliente_Model;
use App\Models\Vendas\Vendas_Model;


class Ven028 extends BaseController
{
    private $sttUser = ['S' => 'SIM', 'N' => 'NAO'];
    public function __construct()

    {
        $this->pdf = new PDF_MC_Table();
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        $this->Acompanhamento = new AcompanhamentoCliente_Model();
        $this->vendas_model = new Vendas_Model();
        $this->modelo = "acompanhamento_cliente";
        $this->tbs_crud  = ['form_acompanhamento_cliente' => 'acompanhamento_cliente'];
        $this->titulo = "ACOMPANHAMENTO INDICE QUALIDADE";
        $this->db = db_connect();
    }
    public function index()
    {        
        $data = [
            "arquivo_dataTable" => true
        ];
        $this->load_template($data);
    }

    public function atualiza_base(){
        $sql = "INSERT IGNORE INTO acompanhamento_cliente (num_os) 
        SELECT num_os FROM vendas
            WHERE  status_bov = 'Concluido' AND dt_instalacao > now() - INTERVAL 4 MONTH 
                AND MONTH(dt_instalacao) <> MONTH(CURRENT_DATE())";
        $this->db->query($sql);
        $aff = $this->db->affectedRows();

        echo json_encode($aff);
    }


    public function DataTable()
    {
        #ENVIA ZAP = 1, 2 = ZAP ENVIADO, 3 = NAO ENVIOU, 4 = MES NAO CHEGOU, 5 = MES PASSOU
        $sql = "SELECT
                    A.id,
                    'teste' ficha,
                    CONCAT(B.vencimento_csv, '/', MONTH(B.dt_instalacao + INTERVAL 1 MONTH), '/', CASE WHEN MONTH(dt_instalacao)>11 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END ) venc_1,
                    CONCAT(B.vencimento_csv, '/', MONTH(B.dt_instalacao + INTERVAL 2 MONTH), '/', CASE WHEN MONTH(dt_instalacao)>10 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END ) venc_2,
                    CONCAT(B.vencimento_csv, '/', MONTH(B.dt_instalacao + INTERVAL 3 MONTH), '/', CASE WHEN MONTH(dt_instalacao)>9 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END  ) venc_3,
                    CONCAT(B.vencimento_csv, '/', MONTH(B.dt_instalacao + INTERVAL 4 MONTH), '/', CASE WHEN MONTH(dt_instalacao)>8 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END  ) venc_4,
                    
                    CASE 
                        WHEN MONTH(CONCAT( CASE WHEN MONTH(dt_instalacao)>11 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 1 MONTH), '-', B.vencimento_csv )) = MONTH(CURDATE())
                            THEN 
                                CASE 
                                    WHEN 
                                        DAY(CONCAT( CASE WHEN MONTH(dt_instalacao)>11 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 1 MONTH), '-', B.vencimento_csv )) >= DAY(CURDATE()) AND COALESCE(zap_m_1, '') = '' THEN 1
                                    WHEN 
                                        DAY(CONCAT( CASE WHEN MONTH(dt_instalacao)>11 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 1 MONTH), '-', B.vencimento_csv )) >= DAY(CURDATE()) AND COALESCE(zap_m_1, '') <> '' THEN 2    
                                    WHEN 
                                        DAY(CONCAT( CASE WHEN MONTH(dt_instalacao)>11 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 1 MONTH), '-', B.vencimento_csv )) <= DAY(CURDATE()) AND COALESCE(zap_m_1, '') <> '' THEN 2
                                    WHEN 
                                        DAY(CONCAT( CASE WHEN MONTH(dt_instalacao)>11 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 1 MONTH), '-', B.vencimento_csv )) < DAY(CURDATE()) AND COALESCE(zap_m_1, '') = '' THEN 3
                                END
                        WHEN MONTH(CONCAT( CASE WHEN MONTH(dt_instalacao)>11 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 1 MONTH), '-', B.vencimento_csv )) > MONTH(CURDATE()) 
                            THEN 4
                        WHEN MONTH(CONCAT( CASE WHEN MONTH(dt_instalacao)>11 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 1 MONTH), '-', B.vencimento_csv )) < MONTH(CURDATE()) 
                            THEN 
                                IF (COALESCE(zap_m_1, '') = '', 3, 2)
                    END zap_1,
                    
                    
                    CASE 
                        WHEN DATE_FORMAT(CONCAT( CASE WHEN MONTH(dt_instalacao)>10 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 2 MONTH), '-', B.vencimento_csv ), '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
                            THEN 
                                CASE 
                                    WHEN 
                                        DAY(CONCAT( CASE WHEN MONTH(dt_instalacao)>10 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 2 MONTH), '-', B.vencimento_csv )) >= DAY(CURDATE()) AND COALESCE(zap_m_2, '') = '' THEN 1
                                    WHEN 
                                        DAY(CONCAT( CASE WHEN MONTH(dt_instalacao)>10 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 2 MONTH), '-', B.vencimento_csv )) >= DAY(CURDATE()) AND COALESCE(zap_m_2, '') <> '' THEN 2
                                    WHEN 
                                        DAY(CONCAT( CASE WHEN MONTH(dt_instalacao)>10 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 2 MONTH), '-', B.vencimento_csv )) <= DAY(CURDATE()) AND COALESCE(zap_m_2, '') <> '' THEN 2
                                    WHEN 
                                        DAY(CONCAT( CASE WHEN MONTH(dt_instalacao)>10 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 2 MONTH), '-', B.vencimento_csv )) < DAY(CURDATE()) AND COALESCE(zap_m_2, '') = '' THEN 3
                                END
                        WHEN DATE_FORMAT(CONCAT( CASE WHEN MONTH(dt_instalacao)>10 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 2 MONTH), '-', B.vencimento_csv), '%Y-%m') > DATE_FORMAT(CURDATE(), '%Y-%m')
                            THEN 4
                        WHEN DATE_FORMAT(CONCAT( CASE WHEN MONTH(dt_instalacao)>10 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 2 MONTH), '-', B.vencimento_csv), '%Y-%m') < DATE_FORMAT(CURDATE(), '%Y-%m')
                            THEN 
                                IF (COALESCE(zap_m_2, '') = '', 3, 2)
                    END zap_2,
                    
                    
                    
                    
                    CASE 
                        WHEN DATE_FORMAT(CONCAT( CASE WHEN MONTH(dt_instalacao)>9 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 3 MONTH), '-', B.vencimento_csv ), '%Y-%m-01') = DATE_FORMAT(CURDATE(), '%Y-%m-01')
                            THEN 
                                CASE 
                                    WHEN 
                                        DAY(CONCAT( CASE WHEN MONTH(dt_instalacao)>9 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 3 MONTH), '-', B.vencimento_csv )) >= DAY(CURDATE()) AND COALESCE(zap_m_3, '') = '' THEN 1
                                    WHEN 
                                        DAY(CONCAT( CASE WHEN MONTH(dt_instalacao)>9 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 3 MONTH), '-', B.vencimento_csv )) >= DAY(CURDATE()) AND COALESCE(zap_m_3, '') <> '' THEN 2
                                    WHEN 
                                        DAY(CONCAT( CASE WHEN MONTH(dt_instalacao)>9 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 3 MONTH), '-', B.vencimento_csv )) <= DAY(CURDATE()) AND COALESCE(zap_m_3, '') <> '' THEN 2
                                    WHEN 
                                        DAY(CONCAT( CASE WHEN MONTH(dt_instalacao)>9 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 3 MONTH), '-', B.vencimento_csv )) < DAY(CURDATE()) AND COALESCE(zap_m_3, '') = '' THEN 3
                                END
                                
                        WHEN DATE_FORMAT(CONCAT( CASE WHEN MONTH(dt_instalacao)>9 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 3 MONTH), '-', B.vencimento_csv ), '%Y-%m-01') > DATE_FORMAT(CURDATE(), '%Y-%m-01')
                            THEN 4
                            
                        WHEN DATE_FORMAT(CONCAT( CASE WHEN MONTH(dt_instalacao)>9 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 3 MONTH), '-', B.vencimento_csv ), '%Y-%m-01') < DATE_FORMAT(CURDATE(), '%Y-%m-01')
                            THEN 
                                IF (COALESCE(zap_m_3, '') = '', 3, 2)
                    END zap_3,
                    
                    CASE 
                        WHEN DATE_FORMAT(CONCAT( CASE WHEN MONTH(dt_instalacao)>8 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 4 MONTH), '-', B.vencimento_csv ), '%Y-%m-01') = DATE_FORMAT(CURDATE(), '%Y-%m-01')
                            THEN 
                                CASE 
                                    WHEN 
                                        DAY(CONCAT( CASE WHEN MONTH(dt_instalacao)>8 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 4 MONTH), '-', B.vencimento_csv )) >= DAY(CURDATE()) AND COALESCE(zap_m_4, '') = '' THEN 1
                                        WHEN 
                                        DAY(CONCAT( CASE WHEN MONTH(dt_instalacao)>8 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 4 MONTH), '-', B.vencimento_csv )) >= DAY(CURDATE()) AND COALESCE(zap_m_4, '') <> '' THEN 2
                                    WHEN 
                                        DAY(CONCAT( CASE WHEN MONTH(dt_instalacao)>8 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 4 MONTH), '-', B.vencimento_csv )) <= DAY(CURDATE()) AND COALESCE(zap_m_4, '') <> '' THEN 2
                                    WHEN 
                                        DAY(CONCAT( CASE WHEN MONTH(dt_instalacao)>8 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 4 MONTH), '-', B.vencimento_csv )) < DAY(CURDATE()) AND COALESCE(zap_m_4, '') = '' THEN 3
                                END
                                
                        WHEN DATE_FORMAT(CONCAT( CASE WHEN MONTH(dt_instalacao)>8 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 4 MONTH), '-', B.vencimento_csv ), '%Y-%m-01') > DATE_FORMAT(CURDATE(), '%Y-%m-01')
                            THEN 4
                            
                        WHEN DATE_FORMAT(CONCAT( CASE WHEN MONTH(dt_instalacao)>8 THEN YEAR(B.dt_instalacao + INTERVAL 1 YEAR) ELSE YEAR(B.dt_instalacao) END, '-', MONTH(B.dt_instalacao + INTERVAL 4 MONTH), '-', B.vencimento_csv ), '%Y-%m-01') < DATE_FORMAT(CURDATE(), '%Y-%m-01')
                            THEN 
                                IF (COALESCE(zap_m_4, '') = '', 3, 2)
                    END zap_4,        
                    substring_index(C.nome, ' ', 1) supervisor,
                    substring_index(D.nome, ' ', 1) vendedor,
                    SUBSTR(B.nome_cliente_csv, 1, 15) nome_cliente,
                    B.cpf_cnpj_csv,
					B.contato_principal_csv,
                    A.num_os, B.vencimento_csv,
                    DATE_FORMAT(B.dt_instalacao, '%d/%m/%y') instalacao,
                    A.zap_m_0, A.zap_m_1, A.zap_m_2, 
                    A.zap_m_3, A.zap_m_4, 
                    IF (COALESCE(A.ativo, '') = 'N', 'N', 'S') ativo,
                    IF (COALESCE(A.adimplente, '') = 'N', 'N', 'S') adimplente
                    
                FROM acompanhamento_cliente A
                LEFT JOIN vendas B ON (B.num_os = A.num_os)
                LEFT JOIN usuarios C ON (C.id = B.id_supervisor)
                LEFT JOIN usuarios D ON (D.id = B.id_vendedor)
                WHERE dt_instalacao > now() - INTERVAL 4 MONTH";
        $dt = new Datatables_server_side([
            'tb' => 'vendas',
            'bt_excluir' => false,
            'cols' => ["ficha", "supervisor", "vendedor",
               "cpf_cnpj_csv", "nome_cliente", "contato_principal_csv",
               "num_os", "instalacao", "vencimento_csv",
               "zap_1", "zap_2", "zap_3", "zap_4",
               "ativo", "adimplente"
            ],
            'formata_coluna' => [

                0 => function ($col, $lin) {
                    $id = base64_encode($lin['id']);
                    $link_pdf = "<button type='button' class='btn btn-outline-danger btn-sm' onclick='gerar_pdf_ficha(\"$id\")'><i class='far fa-file-pdf'></i></button>";
                    return $link_pdf;
                },
                9 => function ($col, $lin) {
                    $num_os = base64_encode($lin['num_os']);                   
                    $envia_zap = '<a class="btn btn-success btn-xs text-white whatsapp" target="_blank" onclick="atualiza_zap(\'' . $num_os . '\', 1)">
                    <i class="fab fa-whatsapp"></i></a>';
                    $enviado = '<i class="fas fa-thumbs-up" title="Ja enviado" style="color:green"></i>';
                    $prazo_esgotado = '<i class="fab fa-whatsapp" title="Prazo esgotado" style="color:red"></i>';
                    $a_enviar = '<i class="fab fa-whatsapp" title="Ainda não chegou no prazo"></i>';

                    if($col == 1){
                        $a = $envia_zap;
                    }
                    if($col == 2){
                        $a = $enviado;
                    }
                    if($col == 3){
                        $a = $prazo_esgotado;
                    }
                    if($col == 4){
                        $a = $a_enviar;
                    }
                   
                    return $a;
                },
                10 => function ($col, $lin) {
                    $num_os = base64_encode($lin['num_os']);
                    $envia_zap = '<a class="btn btn-success btn-xs text-white whatsapp" target="_blank" onclick="atualiza_zap(\'' . $num_os . '\', 2)">
                    <i class="fab fa-whatsapp"></i></a>';
                    $enviado = '<i class="fas fa-thumbs-up" title="Ja enviado" style="color:green"></i>';
                    $prazo_esgotado = '<i class="fab fa-whatsapp" title="Prazo esgotado" style="color:red"></i>';
                    $a_enviar = '<i class="fab fa-whatsapp" title="Ainda não chegou no prazo"></i>';

                    if($col == 1){
                        $a = $envia_zap;
                    }
                    if($col == 2){
                        $a = $enviado;
                    }
                    if($col == 3){
                        $a = $prazo_esgotado;
                    }
                    if($col == 4){
                        $a = $a_enviar;
                    }
                   
                    return $a;
                },
                11 => function ($col, $lin) {
                    $num_os = base64_encode($lin['num_os']);
                    $envia_zap = '<a class="btn btn-success btn-xs text-white whatsapp" target="_blank" onclick="atualiza_zap(\'' . $num_os . '\', 3)">
                    <i class="fab fa-whatsapp"></i></a>';
                    $enviado = '<i class="fas fa-thumbs-up" title="Ja enviado" style="color:green"></i>';
                    $prazo_esgotado = '<i class="fab fa-whatsapp" title="Prazo esgotado" style="color:red"></i>';
                    $a_enviar = '<i class="fab fa-whatsapp" title="Ainda não chegou no prazo"></i>';

                    if($col == 1){
                        $a = $envia_zap;
                    }
                    if($col == 2){
                        $a = $enviado;
                    }
                    if($col == 3){
                        $a = $prazo_esgotado;
                    }
                    if($col == 4){
                        $a = $a_enviar;
                    }
                   
                    return $a;
                },
                12 => function ($col, $lin) {
                    $num_os = base64_encode($lin['num_os']);
                    $envia_zap = '<a class="btn btn-success btn-xs text-white whatsapp" target="_blank" onclick="atualiza_zap(\'' . $num_os . '\', 4)">
                    <i class="fab fa-whatsapp"></i></a>';
                    $enviado = '<i class="fas fa-thumbs-up" title="Ja enviado" style="color:green"></i>';
                    $prazo_esgotado = '<i class="fab fa-whatsapp" title="Prazo esgotado" style="color:red"></i>';
                    $a_enviar = '<i class="fab fa-whatsapp" title="Ainda não chegou no prazo"></i>';

                    if($col == 1){
                        $a = $envia_zap;
                    }
                    if($col == 2){
                        $a = $enviado;
                    }
                    if($col == 3){
                        $a = $prazo_esgotado;
                    }
                    if($col == 4){
                        $a = $a_enviar;
                    }
                   
                    return $a;
                },
                13 => function ($col, $lin) {
                   
                    return $this->montaStatus($col, $lin, "a");
                },
                14 => function ($col, $lin) {
                   
                    return $this->montaStatus($col, $lin, "b");
                },
            ]
        ]);
        $dt->complexQuery($sql);
    }


    public function montaStatus($r, $lin, $tipo)

	{
		$c = $r == 'S' ? "success" : "danger";
		$m = '<div class="dropdown">

		  <button class=" btn btn-' . $c . ' dropdown-toggle btn-xs txtativ"  type="button" data-toggle="dropdown">' . $this->sttUser[$r] . '

		  <span class="caret"></span></button>

		  <ul class="dropdown-menu">';

		foreach ($this->sttUser as $k => $v) {

			$c = ($k == 'S') ? 'success' : 'danger';
            if($tipo == "a"){
                $m .= '<li style="margin-bottom:1px;"><button type="button" class="btn btn-xs btn-' . $c . ' btn-block" onclick="desab_status_ativo(this)" data-id="' . base64_encode($lin['id']) . '" st="' . $k . '">' . $v . '</button></li>';
            }
            else{
                $m .= '<li style="margin-bottom:1px;"><button type="button" class="btn btn-xs btn-' . $c . ' btn-block" onclick="desab_status_adiplente(this)" data-id="' . base64_encode($lin['id']) . '" st="' . $k . '">' . $v . '</button></li>';
            }
		}

		$m .= '</ul>

		</div>';

		return  $m;
	}

    public function desab_status_ativo(){
        $chave = $this->competencia  = base64_decode($this->request->getPost('chave'));
        $stat = $this->competencia  = $this->request->getPost('stat');
        $this->Acompanhamento->update($chave, ['ativo' => $stat]);
        $aff = $this->db->affectedRows();
        echo json_encode(["TOT"=>$aff]);

    }

    public function desab_status_adiplente(){
        $chave = $this->competencia  = base64_decode($this->request->getPost('chave'));
        $stat = $this->competencia  = $this->request->getPost('stat');
        $this->Acompanhamento->update($chave, ['adimplente' => $stat]);
        $aff = $this->db->affectedRows();
        echo json_encode(["TOT"=>$aff]);

    }
    public function atualiza_zap()
    {
        $modeloVendas = $this->vendas_model;
        $num_os = base64_decode($this->request->getPost('num_os'));
        $tipo = $this->request->getPost('tipo');
        switch ($tipo) {
            case 1:
                $campo = "zap_m_1";
                break;
            case 2:
                $campo = "zap_m_2";
                break;
            case 3:
                $campo = "zap_m_3";
                break;
            case 4:
                $campo = "zap_m_4";
                break;
            default:
                "Invalido";
        }
       
        $sql = "UPDATE acompanhamento_cliente SET $campo = 1 WHERE num_os = '{$num_os}'";
        $this->db->query($sql);
        $row =  $modeloVendas->where('num_os', $num_os)->findAll()[0];
        $row["nome_cliente_csv"] = explode(" ", ucfirst(strtolower($row["nome_cliente_csv"])))[0];
        $contato_principal = filter_var($row["contato_principal_csv"], FILTER_SANITIZE_NUMBER_INT);
        $vencimento = filter_var($row["vencimento_csv"], FILTER_SANITIZE_NUMBER_INT);
        $link = "https://www.oi.com.br/minha-oi/codigo-de-barras/";
        $mensagem = "Oi, ".$row["nome_cliente_csv"]." , sua fatura vence no dia: ". $vencimento." , caso prefira pode gerar o boleto atraves do link ".$link;
        echo json_encode(["contato" => $contato_principal, "mensagem" => $mensagem]);
    }
    public function exportarVendas()
    {
        $data_final =  $this->request->getGet('expo_per_fim');
        $tipo =  $this->request->getGet('tipo');
        if($tipo == 1){
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename=relatorio-detalhado.csv');
            header('Content-Transfer-Encoding: binary');
            header('Content-type: text/html; charset=utf-8');
            header('Pragma: no-cache');
            $out = fopen('php://output', 'w');
            $retorno = $this->sqlExportarvendas($data_final);
            fputcsv(
                $out,
                $retorno["cabecalho"],
                ";"
            );
            foreach ($retorno["query"] as $result) {
                fputcsv($out, $result, ";");
            }
            fclose($out);
        }
        else{
            $this->gerar_pdf($data_final);
        }
    }
    public function sqlExportarvendas($data_final)
    {        
        $where_date = "  DATE_FORMAT(B.dt_instalacao, '%Y-%m') = '{$data_final}' ";
        $cabecalho = ["SUPERVISOR", "VENDEDOR", "CPF/CNPJ", "CLIENTE", "CONTATO", "NUM OS", "INSTALACAO", "VENCIMENTO", "ATIVO", "ADIPLENTE", "QUALIDADE"];        
        $sql = "SELECT       
                    C.nome supervisor,
                    D.nome vendedor,
                    B.cpf_cnpj_csv,
                    B.nome_cliente_csv nome_cliente,
					B.contato_principal_csv,
                    A.num_os,
                    DATE_FORMAT(B.dt_instalacao, '%d/%m/%y') instalacao,
                    B.vencimento_csv,
                    CASE 
                        WHEN COALESCE(A.ativo, '') <> 'S' THEN 'N' ELSE 'S'
                    END ativo,
                    CASE 
                        WHEN COALESCE(A.adimplente, '') <> 'S' THEN 'N' ELSE 'S'
                    END adimplente,
                    CASE 
                        WHEN COALESCE(A.adimplente, '') = 'S' AND COALESCE(A.ativo, '') = 'S' THEN 'S' ELSE 'N'
                    END qualidade
                FROM acompanhamento_cliente A
                LEFT JOIN vendas B ON (B.num_os = A.num_os)
                LEFT JOIN usuarios C ON (C.id = B.id_supervisor)
                LEFT JOIN usuarios D ON (D.id = B.id_vendedor)
                WHERE $where_date";
        return ["query" => $this->db->query($sql)->getResultArray(), "cabecalho" => $cabecalho];
    }

    public function gerar_pdf($periodo)
    {
        $where_date = "  DATE_FORMAT(B.dt_instalacao, '%Y-%m') = '{$periodo}' ";
        $mes = strtoupper( utf8_encode( strftime("%B / %Y", strtotime($periodo))));
        $this->pdf->SetFont('Arial', 'I', 8);
        
        //$this->pdf->titulo = utf8_decode("REFERENCIA : " . $mes);
        $this->pdf->titulo = utf8_decode("ACOMPANHAMENTO CLIENTES");
        $this->pdf->AddPage('P');
        $this->pdf->AliasNbPages();
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->Cell(40, 2, "REFERENCIA : " . $mes, 0, 1, 'L');
        $this->pdf->ln(5);
        $this->pdf->SetFont('Arial', 'I', 8);
        $this->pdf->SetTextColor(255, 0, 0);
        $this->pdf->Cell(40, 2, '*Todos os valores abaixo de 80% ficam em destaque', 0, 1, 'L');
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->ln(5);
        $this->corpoPDF($where_date);
        $this->pdf->AddPage('P');
        $this->corpoPDF($where_date, 'D.nome');
        $this->response->setHeader('Content-Type', 'application/pdf');
        $this->pdf->Output('ACOMPANHAMENTO - ' . $mes, 'I');
    }

    public function corpoPDF($where_date, $tipo = 'C.nome'){
        $rows = $this->mquery($where_date, $tipo); 
        $this->pdf->SetFont('Arial', 'B', 10);
        if($tipo == 'C.nome'){
            $this->pdf->Cell(40, 5, 'SUPERVISOR', 0, 0, 'L');
        }
        else{
            $this->pdf->Cell(40, 5, 'VENDEDOR', 0, 0, 'L');
        }
       
        $this->pdf->Cell(33, 6, 'INSTALADAS', 0, 0, 'C');
        $this->pdf->Cell(33, 6, utf8_decode('QUALID'), 0, 0, 'C');
        $this->pdf->Cell(33, 6, utf8_decode('NÃO QUALID'), 0, 0, 'C');
        $this->pdf->Cell(33, 6, utf8_decode('% QUALID'), 0, 1, 'C'); 
        $tot_instalada = 0;
        $tot_qualidade = 0;
        $tot_n_qualidade = 0; 
        foreach($rows as $k => $v){
            $tot_instalada += $v["total_instaladas"];
            $tot_qualidade += $v["qualidade_positiva"];
            $tot_n_qualidade += $v["qualidade_negativa"];
            $this->pdf->SetFont('Arial', '', 9);
            $this->pdf->Cell(40, 6, utf8_decode($v["supervisor"]), 0, 0, 'L');
            $this->pdf->Cell(33, 6, $v["total_instaladas"], 0, 0, 'C');
            $this->pdf->Cell(33, 6, $v["qualidade_positiva"], 0, 0, 'C');
            $this->pdf->Cell(33, 6, $v["qualidade_negativa"], 0, 0, 'C');
            if($v["percentual_qualidade"]<80){
                $this->pdf->SetTextColor(255, 0, 0);
            }           
            $this->pdf->Cell(33, 6, $v["percentual_qualidade"], 0, 1, 'C');
            $this->pdf->SetTextColor(0, 0, 0);
        }
        $this->pdf->SetFillColor(192, 192, 192);
        $this->pdf->Cell(180, 0.5, '', 0, 1, 'L', 1);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->Cell(40, 6, 'TOTAL', 0, 0, 'L');
        $this->pdf->Cell(33, 6, $tot_instalada, 0, 0, 'C');        
        $this->pdf->Cell(33, 6, $tot_qualidade, 0, 0, 'C');
        $this->pdf->Cell(33, 6, $tot_n_qualidade, 0, 0, 'C');
        if($tipo == 'C.nome'){
            $this->pdf->Cell(33, 6, number_format(($tot_qualidade/$tot_instalada)*100,2), 0, 0, 'C');
        }
    }

    public function mquery($where_date, $group_by = "C.nome")
    {
        $sql = "SELECT
                    COUNT(*) total_instaladas,
                    SUM(IF(COALESCE(A.adimplente, '') = 'S' AND COALESCE(A.ativo, '') = 'S' AND $where_date, '1', '0')) qualidade_positiva,
                    SUM(IF((COALESCE(A.adimplente, '') <> 'S' OR COALESCE(A.ativo, '') <> 'S') AND $where_date, '1', '0')) qualidade_negativa,
                    COALESCE(FORMAT((SUM(IF(COALESCE(A.adimplente, '') = 'S' AND COALESCE(A.ativo, '') = 'S' AND  $where_date , '1', '0'))/COUNT(*))*100,2),0) percentual_qualidade,
                    COALESCE(substring_index(UPPER($group_by), ' ', 1), 'N ENCONTRADO') supervisor     
                FROM acompanhamento_cliente A
                LEFT JOIN vendas B ON (B.num_os = A.num_os)
                LEFT JOIN usuarios C ON (C.id = B.id_supervisor)
                LEFT JOIN usuarios D ON (D.id = B.id_vendedor)
                WHERE $where_date GROUP BY $group_by ORDER BY percentual_qualidade ASC";
            //print_r($sql); exit;
        return $this->db->query($sql)->getResultArray();
    }
    public function gerar_pdf_ficha()
    {
        $id =  base64_decode($this->request->getGet('id'));
        $rows = $this->mquery_ficha($id);
        $pdf = new PDF_MC_Table();
        $pdf->titulo = utf8_decode("DETALHES DA FICHA - " . $id);
        $pdf->AddPage('P');
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial', '', 8);
        #Qualidade
        if($rows->ativo == 'S' && $rows->adimplente == 'S'){
            $qualidade = 'S';
        }
        else{
            $qualidade = 'N';
        }     
        $dt_instalacao = $rows->instalacao;
        $tipo_mascara_cpf = (strlen($rows->cpf_cnpj) > 11 ? '##.###.###/####-##' : '###.###.###-##');
        $cpf_cnpj = mascaras_uteis($rows->cpf_cnpj, $tipo_mascara_cpf);
       
        #Primeiro bloco
        $pdf->Cell(20, 6, 'VENDEDOR:', 0, 0, 'L');
        $pdf->Cell(90, 6, strtoupper(remove_acentos($rows->vendedor)), 0, 0, 'L');
        $pdf->Cell(50, 6, 'BOV:', 0, 0, 'R');
        $pdf->Cell(20, 6,  $dt_instalacao, 0, 1, 'L');       
        $pdf->Cell(20, 6, 'SUPERVISOR:', 0, 0, 'L');
        $pdf->Cell(88, 6, strtoupper(remove_acentos($rows->supervisor)), 0, 0, 'L');        
        $pdf->Cell(50, 6, 'OS:', 0, 0, 'R');
        $pdf->Cell(20, 6, $rows->num_os, 0, 1, 'R');
        $pdf->ln(5);
        $pdf->SetFont('Arial', 'B', 8);               
        #Segundo bloco
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 6, 'DADOS CLIENTE', 0, 1);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->Cell(195, 0.5, '', 0, 1, 'L', 1);
        $pdf->SetFont('Arial', '', 8);      
        $pdf->SetFillColor(192, 192, 192);
        $pdf->Cell(195, 0.5, '', 0, 1, 'L', 1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(22, 6, 'NOME:', 0, 0);
        $pdf->Cell(125, 6, strtoupper(utf8_decode($rows->nome_cliente)), 0, 1, 'L');
        $pdf->Cell(22, 6, 'CONTATO:', 0, 0, 'L');
        $pdf->Cell(100, 6, strtoupper(utf8_decode($rows->contato_principal)), 0, 1, 'L');       
        $pdf->Cell(22, 6, 'CPF:', 0, 0, 'L');
        $pdf->Cell(120, 6, $cpf_cnpj, 0, 0, 'L');               
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->ln(10);
        #Terceiro bloco
        $pdf->Cell(95, 6, utf8_decode('DADOS QUALIDADE'), 0, 1);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->Cell(195, 1, '', 0, 1, 'L', 1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(22, 6, 'QUALIDADE:', 0, 0);
        $pdf->Cell(120, 6, $qualidade, 0, 1, 'L');
        $pdf->Cell(22, 6, 'ATIVO:', 0, 0);
        $pdf->Cell(120, 6, $rows->ativo, 0, 1, 'L');
        $pdf->Cell(22, 6, 'ADIMPLENTE:', 0, 0);
        $pdf->Cell(120, 6, $rows->adimplente, 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->ln(10);
        #Quarto bloco
        $pdf->Cell(95, 6, utf8_decode('DADOS ENVIO WHATSAPP'), 0, 1);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->Cell(195, 1, '', 0, 1, 'L', 1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(22, 6, 'WHATSAPP 1:', 0, 0);
        $pdf->Cell(120, 6, utf8_decode($rows->zap_m_1), 0, 1, 'L');
        $pdf->Cell(22, 6, 'WHATSAPP 2:', 0, 0);
        $pdf->Cell(120, 6, utf8_decode($rows->zap_m_2), 0, 1, 'L');
        $pdf->Cell(22, 6, 'WHATSAPP 3:', 0, 0);
        $pdf->Cell(120, 6, utf8_decode($rows->zap_m_3), 0, 1, 'L');
        $pdf->Cell(22, 6, 'WHATSAPP 3:', 0, 0);
        $pdf->Cell(120, 6, utf8_decode($rows->zap_m_4), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->ln(10);
         #Quinto bloco
         $pdf->Cell(95, 6, utf8_decode('OBSERVAÇÃO BACKOFFICE'), 0, 1);
         $pdf->SetFillColor(192, 192, 192);
         $pdf->Cell(195, 0.5, '', 0, 1, 'L', 1);
         $pdf->SetFont('Arial', '', 8);
         $pdf->ln(3);
         $pdf->MultiCell(190, 6, utf8_decode(strtoupper($rows->obs_acompanhamento)), 0, 1, '');
         $pdf->ln(3);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('FICHA QUALIDADE -' . strtoupper(utf8_decode($rows->id)), 'I');
    }

    public function mquery_ficha($id)
    {
        $this->db = db_connect();
        $sql = "SELECT
        A.id,
        A.obs_acompanhamento,      
        C.nome supervisor,
        D.nome vendedor,
        B.nome_cliente_csv nome_cliente,
        B.cpf_cnpj_csv cpf_cnpj,
        B.contato_principal_csv contato_principal,
        A.num_os, B.vencimento_csv,
        DATE_FORMAT(B.dt_instalacao, '%d/%m/%Y') instalacao,
        IF (COALESCE(A.zap_m_1, '') = '', 'NÃO ENVIADO', 'ENVIADO') zap_m_1,
        IF (COALESCE(A.zap_m_2, '') = '', 'NÃO ENVIADO', 'ENVIADO') zap_m_2,
        IF (COALESCE(A.zap_m_3, '') = '', 'NÃO ENVIADO', 'ENVIADO') zap_m_3, 
        IF (COALESCE(A.zap_m_4, '') = '', 'NÃO ENVIADO', 'ENVIADO') zap_m_4,
        IF (COALESCE(A.ativo, '') = 'N', 'N', 'S') ativo,
        IF (COALESCE(A.adimplente, '') = 'N', 'N', 'S') adimplente
    FROM acompanhamento_cliente A
    LEFT JOIN vendas B ON (B.num_os = A.num_os)
    LEFT JOIN usuarios C ON (C.id = B.id_supervisor)
    LEFT JOIN usuarios D ON (D.id = B.id_vendedor)
    WHERE A.id= '{$id}'";
        return $this->db->query($sql)->getRow();
    }
   
}