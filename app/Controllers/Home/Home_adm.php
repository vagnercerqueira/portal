<?php

namespace App\Controllers\Home;

use App\Controllers\BaseController;
use App\Models\Documentos_Model;

class Home_adm extends BaseController
{
	
    public function index()
    { 
        $dados = [
            'titulo' => 'Dashboard',
            "titulo" => "Home Adm"
        ];
        $this->load_template($dados);
    }	
	
	public function formsearchgeral()
	{
		$metodo = $this->request->getMethod();
		if ($metodo == "get") {
			echo "vc n tem acesso a essa area!!!";
			exit;
		}

		$G = $this->request->getPost('G');

		$this->db = db_connect();

		$sql = "SELECT 	B.NR_PLACA, COALESCE(C.NOME_FANTASIA, C.RAZAO_SOCIAL) CLIENTE, A.NUM_OS, 
				DATE_FORMAT(A.DT_MOV_OS, '%d/%m/%Y') DT_MOV_OS,
				A.ORDEM_COMPRA_CLI,
				( SELECT VALOR_PGTO V FROM jc_os_header_pgtos V WHERE V.ID_OS=A.ID ) VALOR_OS,
				( SELECT NR_NOTA V FROM jc_os_header_pgtos V WHERE V.ID_OS=A.ID ) NR_NOTA,
				( SELECT DATE_FORMAT(DT_PRIM_PGTO, '%d')  V FROM jc_os_header_pgtos V WHERE V.ID_OS=A.ID ) DIA_VENCIMENTO,
				
				CONCAT(
					'<button type=button class=\'btn btn-xs disabled btn-block btn-success kos\' >TOTAL: ',
					(
					SELECT SUM(V.VALOR) 
					FROM   jc_servicos_contidos_os T
					INNER JOIN jc_valor_servicos V ON V.ID_SERVICO=T.ID_SERVICO
					WHERE  V.DATA = ( 	SELECT MAX(F.data) FROM jc_valor_servicos F 
														WHERE 	F.data <= A.DT_MOV_OS
															AND F.id_servico=V.id_servico )
							AND T.ID_OS=A.ID
					),
					'</button>',
					(
						SELECT 
							GROUP_CONCAT(
							CONCAT(	'<button type=button class=\'btn btn-block btn-xs btn-primary kos\' ><small>',	Z.DESCRICAO, '(',
								COALESCE(
									(
										SELECT V.VALOR 
										FROM   jc_valor_servicos V
										WHERE  V.DATA = ( 	SELECT MAX(F.data) FROM jc_valor_servicos F 
															WHERE 	F.data <= A.DT_MOV_OS
																AND F.id_servico=V.id_servico )
												AND V.id_servico=Z.ID
									) , 0 
								)
							,')', '</small></button>')
							SEPARATOR ''
						) 
						FROM jc_servicos_contidos_os T
						INNER JOIN jc_servicos Z ON Z.id=T.ID_SERVICO
						WHERE T.ID_OS=A.ID AND A.TIPO_SERVICO=Z.TIPO_SERVICO
					) 
				)AS SERVICOS,
				D.DESCRICAO TIPO_SERVICO
		FROM jc_os_veiculo A
		INNER JOIN jc_veiculos 	B ON B.ID=A.VEICULO
		INNER JOIN jc_clientes  C ON C.ID=B.CLIENTE
		INNER JOIN jc_tipo_servico D on D.id=A.tipo_servico
		WHERE B.NR_PLACA LIKE '%{$G}%' OR C.RAZAO_SOCIAL LIKE '%$G%' OR C.CPF_CNPJ LIKE '%$G%'";

		$query = $this->db->query($sql);

		$rows = $query->getResultArray();
		if (count($rows) == 0) {
			echo json_encode(['DADOS' => ""]);
			exit;
		}

		$html = "";
		$html = '<div class="table-responsive"><table class="table table-bordered table-sm">';
		$html .= '	<thead>
						<tr>
							<th>Nr os</th>
							<th>Placa</th>
							<th>Cliente</th>
							<th>Dt.movimento</th>
							<th>Ordem de compra</th>
							<th>Nota fiscal</th>
							<th>Dia Vence</th>
							<th>Tipo Servico</th>
							<th>Servicos</th>
						</tr>
					</thead>
					<tbody>';
		foreach ($rows as $k => $v) {
			$html .= "<tr>
						<td>" . $v['NUM_OS'] . "</td>
						<td>" . $v['NR_PLACA'] . "</td>
						<td>" . $v['CLIENTE'] . "</td>
						<td>" . $v['DT_MOV_OS'] . "</td>
						<td>" . $v['ORDEM_COMPRA_CLI'] . "</td> 
						<td>" . $v['NR_NOTA'] . "</td>
						<td>" . $v['DIA_VENCIMENTO'] . "</td>
						<td>" . $v['TIPO_SERVICO'] . "</td>
						<td>" . $v['SERVICOS'] . "</td>
					  </tr>";
		}

		$html .= "</tbody></table></div>";
		echo json_encode(['DADOS' => $html]);
	}
}
