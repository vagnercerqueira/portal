<?php

namespace App\Controllers\Home;

use App\Controllers\BaseController;
use App\Models\Documentos_Model;

class Home_superusuario extends BaseController
{
	private $conexao = null;
	private $competencia = null;
	private $cod_interno = null;
	private $cod_usuario = null;
	private $tipo_busca = null;
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
			"titulo" => "Home Master"
		];
		$this->load_template($dados);
	}

	public function carregaDados()
	{
		$this->db = db_connect();
		$this->competencia  = $this->request->getPost('COMPETENCIA');
		$this->cod_interno = session()->get('cod_interno');
		$this->cod_usuario = session()->get('id_usuario');
		if (
			$this->cod_interno == 980 ||
			$this->cod_interno == 990 ||
			$this->cod_interno == 995 || 			
			$this->cod_interno == 9999 || 
			$this->cod_interno == 1000) {
			$this->tipo_busca = "";
		} elseif($this->cod_interno == 970) {
			$this->tipo_busca = " AND A.id_supervisor = " . $this->cod_usuario;
		}
		elseif($this->cod_interno == 960) {
			$this->tipo_busca = " AND A.id_vendedor = " . $this->cod_usuario;
		}

		//print_r($this->tipo_busca); exit;

		$vendas_brutas = $this->carregaVendaBruta($this->competencia, $this->tipo_busca)["venda_bruta"];
		$vendas_instaladas = $this->carregaInstaladas($this->competencia, $this->tipo_busca)["venda_instaladas"];
		$vendas_aprovisionamento = $this->carregaAprovisionamentos($this->competencia, $this->tipo_busca)["venda_aprovisionamento"];
		$vendas_emtratamento = $this->carregaEmtratamento($this->competencia, $this->tipo_busca)["venda_emtratamento"];
		$vendas_cancelados_bov = $this->carregaCanceladoBov($this->competencia, $this->tipo_busca)["cancelado_bov"];
		$vendas_migracoes = $this->carregaMigracoes($this->competencia, $this->tipo_busca)["migracoes"];
		$vendas_dados_incompletos = $this->carregaDadosIncompletos($this->competencia, $this->tipo_busca)["dados_incompletos"];
		$vendas_instalacoes_atrasadas = $this->carregaInstalacoes_atrasadas($this->competencia, $this->tipo_busca)["instalacoes_atrasadas"];
		$ultima_venda = $this->datasBovVendas($this->competencia, $this->tipo_busca)["ultima_venda"];
		$ultima_bov = $this->datasBovVendas()["ultima_bov"];
		$total_instalada = $this->datasBovVendas()["total_instalada"];
		

		echo json_encode(
			[
				"vendas_bruta" => $vendas_brutas,
				"vendas_instaladas" => $vendas_instaladas,
				"vendas_aprovisionamento" => $vendas_aprovisionamento,
				"vendas_emtratamento" => $vendas_emtratamento,
				"vendas_cancelados_bov" => $vendas_cancelados_bov,
				"vendas_migracoes" => $vendas_migracoes,
				"vendas_dados_incompletos" => $vendas_dados_incompletos,
				"vendas_instalacoes_atrasadas" => $vendas_instalacoes_atrasadas,
				"ultima_venda" => $ultima_venda,
				"ultima_bov" =>  $ultima_bov,
				"total_instalada" => $total_instalada
			]
		);
	}

	public function carregaVendaBruta($competencia, $tipo_busca, $formato = "resumo")
	{
		if ($formato == "resumo") {
			$campos = " COUNT(*) venda_bruta ";
			$left_join = "";
			$order_by = "";
		} else {
			$campos = "
				COALESCE(C.descricao, '') equipe, 
				COALESCE((SELECT nome FROM usuarios D WHERE D.id = A.id_supervisor),'') supervisor, 
				COALESCE((SELECT nome FROM usuarios E WHERE E.id = A.id_vendedor), '') vendedor, 
				DATE_FORMAT(A.dt_venda_csv, '%d/%m/%Y') dt_venda_csv, 
				COALESCE(DATE_FORMAT(A.dt_retorno_tratamento, '%d/%m/%Y'), '') dt_retorno_tratamento, 
				COALESCE(DATE_FORMAT(A.dt_instalacao, '%d/%m/%Y'), '') dt_instalacao, 
				REPLACE(COALESCE(A.status_bov, ''), '??', 'i') status_bov, 
				A.cpf_cnpj_csv, 
				A.setor_resp_tratamento, 
				A.status_tratamento";
			$left_join = " 
			LEFT JOIN usuarios B ON (A.id_supervisor = B.id) 
			LEFT JOIN equipe_usuario C ON (A.equipe = C.id)";
		}
		$sql = "SELECT  $campos FROM vendas A $left_join WHERE DATE_FORMAT(dt_venda_csv, '%Y-%m') ='{$competencia}'  $tipo_busca";
		//print_r($sql); exit;

		$row = $this->db->query($sql)->getResultArray();

		if ($formato == "resumo") {
			return $row[0];
		} else {
			return $row;
		}
	}
	public function carregaInstaladas($competencia, $tipo_busca, $formato = "resumo")
	{

		if ($formato == "resumo") {
			$campos = " COUNT(*) venda_instaladas ";
			$left_join = "";
			$order_by = "";
		} else {
			$campos = "COALESCE(C.descricao, '') equipe, COALESCE((SELECT nome FROM usuarios D WHERE D.id = A.id_supervisor),'') supervisor, COALESCE((SELECT nome FROM usuarios E WHERE E.id = A.id_vendedor), '') vendedor, 
			DATE_FORMAT(A.dt_venda_csv, '%d/%m/%Y') dt_venda_csv, COALESCE(DATE_FORMAT(A.dt_instalacao, '%d/%m/%Y'), '') dt_instalacao, 
			REPLACE(COALESCE(A.status_bov, ''), '??', 'i') status_bov, A.cpf_cnpj_csv, A.num_os";
			$left_join = " LEFT JOIN usuarios B ON (A.id_supervisor = B.id) LEFT JOIN equipe_usuario C ON (A.equipe = C.id)";
			$order_by = " ORDER BY 1 DESC , 2 DESC, 3 DESC, 4 DESC ";
		}

		$sql = "SELECT $campos FROM vendas A $left_join WHERE DATE_FORMAT(dt_instalacao, '%Y-%m') ='{$competencia}' AND status_bov = 'Conclu??do'  $tipo_busca $order_by";
		//print_r($sql); exit;
		$row = $this->db->query($sql)->getResultArray();

		if ($formato == "resumo") {
			return $row[0];
		} else {
			return $row;
		}
	}


	public function carregaAprovisionamentos($competencia, $tipo_busca, $formato = "resumo")
	{
		if ($formato == "resumo") {
			$campos = " COUNT(*) venda_aprovisionamento  ";
			$left_join = "";
			$order_by = "";
		} else {
			$campos = " COALESCE(C.descricao, '') equipe, COALESCE((SELECT nome FROM usuarios D WHERE D.id = A.id_supervisor),'') supervisor, COALESCE((SELECT nome FROM usuarios E WHERE E.id = A.id_vendedor), '') vendedor, 
			DATE_FORMAT(A.dt_venda_csv, '%d/%m/%Y') dt_venda_csv, DATE_FORMAT((GREATEST( COALESCE(dt_agendamento,0),  COALESCE(dt_reagendamento_1,0) ,  COALESCE(dt_reagendamento_2,0),  COALESCE(dt_reagendamento_3,0))), '%d/%m/%Y') dt_agendamento, 
			REPLACE(COALESCE(A.status_bov, ''), '??', 'i') status_bov, A.cpf_cnpj_csv, A.num_os  ";
			$left_join = " LEFT JOIN usuarios B ON (A.id_supervisor = B.id) LEFT JOIN equipe_usuario C ON (A.equipe = C.id)";
		}

		$sql = "SELECT $campos FROM vendas A $left_join WHERE DATE_FORMAT(dt_venda_csv, '%Y-%m') ='{$competencia}' AND status_bov = 'Em Aprovisionamento'  $tipo_busca";
		//print_r($sql);
		//exit;

		$row = $this->db->query($sql)->getResultArray();
		if ($formato == "resumo") {
			return $row[0];
		} else {
			return $row;
		}
	}

	public function carregaEmtratamento($competencia, $tipo_busca, $formato = "resumo")
	{

		if ($formato == "resumo") {
			$campos = " COUNT(*) venda_emtratamento  ";
			$left_join = "";
			$order_by = "";
		} else {
			$campos = " COALESCE(C.descricao, '') equipe, COALESCE((SELECT nome FROM usuarios D WHERE D.id = A.id_supervisor),'') supervisor, COALESCE((SELECT nome FROM usuarios E WHERE E.id = A.id_vendedor), '') vendedor, 
			DATE_FORMAT(A.dt_venda_csv, '%d/%m/%Y') dt_venda_csv, DATE_FORMAT(A.dt_retorno_tratamento, '%d/%m/%Y') dt_retorno_tratamento, CASE WHEN A.status_tratamento = 'ET' THEN 'Em tratamento' ELSE '' END AS status_tratamento, E.descricao setor_resp_tratamento,
			REPLACE(COALESCE(A.status_bov, ''), '??', 'i') status_bov, A.cpf_cnpj_csv, A.num_os  ";
			$left_join = " LEFT JOIN usuarios B ON (A.id_supervisor = B.id) LEFT JOIN equipe_usuario C ON (A.equipe = C.id) LEFT JOIN setor_tratamento_vendas E ON (A.setor_resp_tratamento = E.id)";
		}


		$sql = "SELECT $campos
				FROM vendas A
				$left_join
				WHERE 
					DATE_FORMAT(dt_venda_csv, '%Y-%m') ='{$competencia}'
					AND status_tratamento = 'ET'
					AND (status_bov IS NULL OR status_bov = 'Em Aprovisionamento' OR status_bov LIKE '%CANC%') $tipo_busca";
		$row = $this->db->query($sql)->getResultArray();
		if ($formato == "resumo") {
			return $row[0];
		} else {
			return $row;
		}
	}


	public function carregaCanceladoBov($competencia, $tipo_busca, $formato = "resumo")
	{

		if ($formato == "resumo") {
			$campos = " COUNT(*) cancelado_bov  ";
			$left_join = "";
			$order_by = "";
		} else {
			$campos = " COALESCE(C.descricao, '') equipe, COALESCE((SELECT nome FROM usuarios D WHERE D.id = A.id_supervisor),'') supervisor, COALESCE((SELECT nome FROM usuarios E WHERE E.id = A.id_vendedor), '') vendedor, 
			DATE_FORMAT(A.dt_venda_csv, '%d/%m/%Y') dt_venda_csv, COALESCE(DATE_FORMAT(A.data_status_bov, '%d/%m/%Y'), '') data_status_bov, CASE WHEN A.status_tratamento = 'ET' THEN 'Em tratamento' ELSE '' END AS status_tratamento, E.descricao setor_resp_tratamento,
			REPLACE(COALESCE(A.status_bov, ''), '??', 'i') status_bov, A.cpf_cnpj_csv, A.num_os  ";
			$left_join = " LEFT JOIN usuarios B ON (A.id_supervisor = B.id) LEFT JOIN equipe_usuario C ON (A.equipe = C.id) LEFT JOIN setor_tratamento_vendas E ON (A.setor_resp_tratamento = E.id)";
		}
		$sql = "SELECT $campos 
				FROM vendas A 
				$left_join
				WHERE 
					DATE_FORMAT(dt_venda_csv, '%Y-%m') ='{$competencia}'
					AND (status_bov LIKE '%CANC%') $tipo_busca";

		$row = $this->db->query($sql)->getResultArray();
		if ($formato == "resumo") {
			return $row[0];
		} else {
			return $row;
		}
	}

	public function carregaMigracoes($competencia, $tipo_busca, $formato = "resumo")
	{
		if ($formato == "resumo") {
			$campos = " COUNT(*) migracoes ";
			$left_join = "";
			$order_by = "";
		} else {
			$campos = " COALESCE(C.descricao, '') equipe, COALESCE((SELECT nome FROM usuarios D WHERE D.id = A.id_supervisor),'') supervisor, COALESCE((SELECT nome FROM usuarios E WHERE E.id = A.id_vendedor), '') vendedor, 
			DATE_FORMAT(A.dt_venda_csv, '%d/%m/%Y') dt_venda_csv, COALESCE(DATE_FORMAT(A.data_status_bov, '%d/%m/%Y'), '') data_status_bov, CASE WHEN A.status_tratamento = 'ET' THEN 'Em tratamento' ELSE '' END AS status_tratamento, E.descricao setor_resp_tratamento,
			REPLACE(COALESCE(A.status_bov, ''), '??', 'i') status_bov, A.cpf_cnpj_csv, A.num_os, A.mig_cobre_fixo_bov, A.mig_cobre_velox_bov ";
			$left_join = " LEFT JOIN usuarios B ON (A.id_supervisor = B.id) LEFT JOIN equipe_usuario C ON (A.equipe = C.id) LEFT JOIN setor_tratamento_vendas E ON (A.setor_resp_tratamento = E.id)";
		}

		$sql = "SELECT $campos
				FROM vendas A
				$left_join
				WHERE 
					DATE_FORMAT(dt_venda_csv, '%Y-%m') ='{$competencia}'
					AND (mig_cobre_fixo_bov = 'S' OR mig_cobre_velox_bov = 'S') $tipo_busca";
		$row = $this->db->query($sql)->getResultArray();
		if ($formato == "resumo") {
			return $row[0];
		} else {
			return $row;
		}
	}


	public function carregaDadosIncompletos($competencia, $tipo_busca, $formato = "resumo")
	{

		if ($formato == "resumo") {
			$campos = " COUNT(*) dados_incompletos ";
			$left_join = "";
			$order_by = "";
		} else {
			$campos = " COALESCE(C.descricao, '') equipe, COALESCE((SELECT nome FROM usuarios D WHERE D.id = A.id_supervisor),'') supervisor, COALESCE((SELECT nome FROM usuarios E WHERE E.id = A.id_vendedor), '') vendedor, 
			DATE_FORMAT(A.dt_venda_csv, '%d/%m/%Y') dt_venda_csv, COALESCE(DATE_FORMAT(A.data_status_bov, '%d/%m/%Y'), '') data_status_bov, CASE WHEN A.status_tratamento = 'ET' THEN 'Em tratamento' ELSE '' END AS status_tratamento, E.descricao setor_resp_tratamento,
			REPLACE(COALESCE(A.status_bov, ''), '??', 'i') status_bov, A.cpf_cnpj_csv, A.num_os, A.mig_cobre_fixo_bov, A.mig_cobre_velox_bov ";
			$left_join = " LEFT JOIN usuarios B ON (A.id_supervisor = B.id) LEFT JOIN equipe_usuario C ON (A.equipe = C.id) LEFT JOIN setor_tratamento_vendas E ON (A.setor_resp_tratamento = E.id)";
		}

		$sql = "SELECT  
				$campos
				FROM vendas A
				$left_join
				WHERE 
					DATE_FORMAT(dt_venda_csv, '%Y-%m') ='{$competencia}'
					AND (status_ativacao IS NULL) $tipo_busca";

		//print_r($sql); exit;
		$row = $this->db->query($sql)->getResultArray();
		if ($formato == "resumo") {
			return $row[0];
		} else {
			return $row;
		}
	}
	public function carregaInstalacoes_atrasadas($competencia, $tipo_busca, $formato = "resumo")
	{

		if ($formato == "resumo") {
			$campos = " COUNT(*) instalacoes_atrasadas ";
			$left_join = "";
			$order_by = "";
		} else {
			$campos = " COALESCE(C.descricao, '') equipe, COALESCE((SELECT nome FROM usuarios D WHERE D.id = A.id_supervisor),'') supervisor, COALESCE((SELECT nome FROM usuarios E WHERE E.id = A.id_vendedor), '') vendedor, 
			DATE_FORMAT(A.dt_venda_csv, '%d/%m/%Y') dt_venda_csv, COALESCE(DATE_FORMAT(A.data_status_bov, '%d/%m/%Y'), '') data_status_bov, CASE WHEN A.status_tratamento = 'ET' THEN 'Em tratamento' ELSE '' END AS status_tratamento, E.descricao setor_resp_tratamento,
			REPLACE(COALESCE(A.status_bov, ''), '??', 'i') status_bov, A.cpf_cnpj_csv, COALESCE(A.num_os, '') num_os, A.mig_cobre_fixo_bov, A.mig_cobre_velox_bov ";
			$left_join = " LEFT JOIN usuarios B ON (A.id_supervisor = B.id) LEFT JOIN equipe_usuario C ON (A.equipe = C.id) LEFT JOIN setor_tratamento_vendas E ON (A.setor_resp_tratamento = E.id)";
		}

		$sql = "SELECT 
				$campos
				FROM vendas A
				$left_join
				WHERE
					DATE_FORMAT(dt_venda_csv, '%Y-%m') ='{$competencia}' 
					AND status_bov LIKE '%Aprovi%' 
					AND (
						dt_agendamento < CURRENT_DATE
						OR dt_reagendamento_1 < CURRENT_DATE()
						OR dt_reagendamento_2 < CURRENT_DATE()
						OR dt_reagendamento_3 < CURRENT_DATE()
					) $tipo_busca";
		//print_r($sql);
		//exit;
		$row = $this->db->query($sql)->getResultArray();
		if ($formato == "resumo") {
			return $row[0];
		} else {
			return $row;
		}
	}
	public function datasBovVendas()
	{
		$sql = "SELECT 
				DATE_FORMAT(MAX(dt_venda_csv), '%d/%m/%Y') ultima_venda,
				 COALESCE(DATE_FORMAT((SELECT max(data_status) FROM ultima_bov), '%d/%m/%Y'), ' - ') ultima_bov,
				 (SELECT count(*) FROM ultima_bov) total_instalada
    			FROM vendas";
				
				

		$row = $this->db->query($sql)->getResultArray();
		
		return $row[0];
	}
	public function dadosModal()
	{

		$rotulo = $this->request->getPost("rotulo");
		$competencia = $this->request->getPost("COMPETENCIA");
		$this->cod_interno = session()->get('cod_interno');
		$this->cod_usuario = session()->get('id_usuario');
		if ($this->cod_interno == 980 ||
			$this->cod_interno == 990 ||
			$this->cod_interno == 995 ||			
			$this->cod_interno == 9999 || 
			$this->cod_interno == 1000) {
			$this->tipo_busca = "";
		} elseif($this->cod_interno == 970) {
			$this->tipo_busca = " AND A.id_supervisor = " . $this->cod_usuario;
		}
		elseif($this->cod_interno == 960) {
			$this->tipo_busca = " AND A.id_vendedor = " . $this->cod_usuario;
		}


		if ($rotulo == "Vendas Brutas") {
			$row = ["vendas_brutas" => $this->carregaVendaBruta($competencia, $this->tipo_busca, $formato = "relatorio")];
		} elseif ($rotulo == "Vendas Instaladas") {
			$row = ["vendas_instaladas" => $this->carregaInstaladas($competencia, $this->tipo_busca, $formato = "relatorio")];
		} elseif ($rotulo == "Em Aprovisionamento") {
			$row = ["vendas_aprovisionamento" => $this->carregaAprovisionamentos($competencia, $this->tipo_busca, $formato = "relatorio")];
		} elseif ($rotulo == "Em Tratamento") {
			$row = ["vendas_tratamento" => $this->carregaEmtratamento($competencia, $this->tipo_busca, $formato = "relatorio")];
		} elseif ($rotulo == "Cancelados BOV") {
			$row = ["vendas_cancelada_bov" => $this->carregaCanceladoBov($competencia, $this->tipo_busca, $formato = "relatorio")];
		} elseif ($rotulo == "Migra????es VOIP ou FIBRA") {
			$row = ["vendas_cancelada_bov" => $this->carregaMigracoes($competencia, $this->tipo_busca, $formato = "relatorio")];
		} elseif ($rotulo == "Dados incompletos") {
			$row = ["vendas_dados_incompletos" => $this->carregaDadosIncompletos($competencia, $this->tipo_busca, $formato = "relatorio")];
		} elseif ($rotulo == "Instala????es atrasadas") {
			$row = ["vendas_instalacoes_atrasadas" => $this->carregaInstalacoes_atrasadas($competencia, $this->tipo_busca, $formato = "relatorio")];
		}
		echo json_encode($row);
	}
}