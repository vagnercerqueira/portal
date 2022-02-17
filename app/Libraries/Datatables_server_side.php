<?php

class Datatables_server_side
{

	private $db;
	private $req;
	private $cols;
	private $order = ['id' => 'desc'];
	private $tb;
	private $search;
	private $result_order, $result_dir;
	private $join = [];
	private $acao = true;
	private $bt_editar = true;
	private $bt_excluir = true;
	private $formata_coluna = false;
	private $fields_fk = [];
	private $where = [];
	private $fields_date = [];
	public function __construct($params)
	{

		$this->db = db_connect();
		$this->db->query("SET lc_time_names = 'pt_BR'");
		$requisicao = \Config\Services::request();
		$this->req = $requisicao->getPost();
		//$this->req = $requisicao->getGet();
		$this->tb = $params['tb'];
		$this->acao = $params['acao'] ?? $this->acao;
		$this->bt_editar = $params['bt_editar'] ?? $this->bt_editar;
		$this->bt_excluir = $params['bt_excluir'] ?? $this->bt_excluir;

		$this->cols = !isset($params['cols'])  ? $this->schema() : ($params['cols']);
		$this->formata_coluna = $params['formata_coluna'] ?? $this->formata_coluna;
		$this->join = $params['join'] ?? $this->join;
		$this->where = $params['where'] ?? $this->where;
		$this->fields_fk = $params['fields_fk'] ?? $this->fields_fk;
		$this->fields_date = $params['fields_date'] ?? $this->fields_date;
	}
	function simpleQuery()
	{

		$this->set_search(); // search

		$this->set_order(); // order

		$limit = $this->limit($this->req['length'], $this->req['start']);

		if (!in_array('id', $this->cols)) {
			array_push($this->cols, 'id');
		}
		$campo_sel = "{$this->tb}." . implode(", {$this->tb}.", $this->cols);
		foreach ($this->fields_fk as $v) {
			$campo_sel .= " ,{$v[0]} {$v[1]}";
		}

		/*	$builder = $this->db->table($this->tb);
		$builder->select($campo_sel);
		foreach ($this->where as $v) {
			$builder->where($v);
		}
		$builder->where($this->search);

		foreach ($this->join as $v) {
			$leftJ = isset($v[2]) ? $v[2] : 'inner';
			$builder->join($v[0], $v[1], $leftJ);
		}
		$builder->orderBy($this->result_order, $this->result_dir);
		$builder->limit($this->req['length'], $this->req['start']);
		$rows = $builder->get()->getResultArray();*/
		$sql = " SELECT {$campo_sel}
				FROM {$this->tb}";
		foreach ($this->join as $v) {
			$leftJ = isset($v[2]) ? $v[2] : 'inner';
			$sql .= " {$leftJ} join {$v[0]} ON $v[1] ";
		}

		if (count($this->where) > 0) {
			$where = " WHERE ( ";
			foreach ($this->where as $v) {
				$where .= "  {$v}";
			}
			$sql .= $where . " ) ";
		}
		$sql = "SELECT * FROM ($sql) TMP
				WHERE {$this->search}
				ORDER BY $this->result_order $this->result_dir"; //print_r($sql);exit;
		$row_filtered = $this->db->query($sql)->getResultArray();

		$sql .= "{$limit}";

		$query = $this->db->query($sql);

		$rows = $query->getResultArray();

		$tot_rows_tb = $this->db->query("SELECT COUNT({$this->tb}.id) as TOT FROM {$this->tb}")->getRow();
		$this->retorna_dados($rows, $tot_rows_tb, count($row_filtered));
	}
	function complexQuery($sql_or)
	{

		$this->set_search(); // search

		$this->set_order(); // order

		//if ($this->req['length'] != -1);
		$limit = $this->limit($this->req['length'], $this->req['start']);
		$campo_sel = implode(",", $this->cols) . ($this->acao === true ? ', id' : null);
		if (in_array($this->cols[$this->result_order - 1], $this->fields_date)) {
			$this->cols[$this->result_order - 1] = "STR_TO_DATE(" . $this->cols[$this->result_order - 1] . ", '%d/%m/%Y %H:%i')";
		}

		$orderBy = "ORDER BY {$this->cols[$this->result_order - 1]} {$this->result_dir}";



		$sql = "SELECT " . (strpos($sql_or, 'rownumber') !== false ? "row_number() over({$orderBy}) rownum, " : null) . $campo_sel . " 
		FROM (
			$sql_or
		) sub_a
		WHERE {$this->search}
		{$orderBy}";



		$row_filtered = $this->db->query($sql)->getResultArray();

		/*print_r($this->db->getLastQuery());
		exit;*/

		$sql .= "{$limit}";

		//print_r($this->db->query("SELECT @@lc_time_names")->getResultArray());
		//exit;

		$this->db->query($sql);
		
		$query = $this->db->query($sql);
		$rows = $query->getResultArray();

		$tot_rows_tb = $this->db->query("SELECT COUNT(*) as TOT FROM ( $sql_or) sub_b")->getRow();
		$this->retorna_dados($rows, $tot_rows_tb, count($row_filtered));
	}
	public function limit($reqLimit, $start)
	{
		if ($reqLimit != -1)
			$limit = " LIMIT {$start}, {$reqLimit} ";
		else
			$limit = null;
		return $limit;
	}
	public function set_search()
	{

		if ($this->req['search']['value']) {
			$search = $this->req['search']['value'];
			$this->search =  implode(" LIKE '%" . addslashes(trim($search)) . "%' OR ", $this->cols) . " LIKE '%" . addslashes(trim($search)) . "%'";
			foreach ($this->fields_fk as $v) {
				$this->search .=  " OR {$v[1]} LIKE '%" . addslashes(trim($search)) . "%'";
			}
		} else {
			$this->search = " id != ''";
		}
	}
	public function schema()
	{
		$result = $this->db->getFieldNames($this->tb);
		return $result;
	}
	public function set_order()
	{
		if (isset($this->req['order'])) {
			//$this->result_order = $this->cols[$this->req['order']['0']['column']];
			$this->result_order = $this->req['order']['0']['column'] + 1;
			$this->result_dir = $this->req['order']['0']['dir'];
		} else if ($this->order) {
			$order = $this->order;

			$this->result_order = 1;
			$this->result_dir = 'desc';
		}
	}
	public function retorna_dados($rows, $tot_rows_tb, $tot_filtered)
	{
		$data = array();

		foreach ($rows as $ind => $key) {
			$vtmp = [];
			$i = 0;
			$id = $key['id'] ?? null;
			foreach ($key as $m => $n) {

				if ($this->formata_coluna &&  array_key_exists($i, $this->formata_coluna)) {
					$vtmp[$m] = $this->formata_coluna[$i]($n, $key);
				} else {
					$vtmp[$m] = $n;
				}
				if ($m == 'id') {
					$vtmp[$m] = $this->bt_acao($n);
				}

				$i++;
			}
			if (isset($vtmp['id'])) {
				$id = $vtmp['id'];
				unset($vtmp['id']);
				$vtmp['id'] = $id;
			}

			$data[] = array_values($vtmp);
		}

		$output = array(
			"draw" => $this->req['draw'],
			"recordsTotal" => $tot_rows_tb->TOT,
			"recordsFiltered" => $tot_filtered,
			"data" => $data
		);


		echo json_encode($output);
	}
	private function bt_acao($key)
	{
		$btns_action = "";
		$ses = session()->get();
		//$user = $ses->get();
		if ($this->acao) {
			//if ($this->bt_editar) {

			if ($ses['appUser'][$ses['IndPag']]['perm_alterar'] !== 'N' && $this->bt_editar) {
				$btns_action .= '&nbsp;<a onclick=editViwer(\'' . base64_encode($key) . '\',this,\'E\')  href=\'javascript:;\' class=\'btn btn-primary btn-xs editar\'><i class=\'fa fa-edit\'></i>&nbsp;Edita</a>';
			} else {
				$btns_action .= '&nbsp;<a onclick=editViwer(\'' . base64_encode($key) . '\',this,\'V\')  href=\'javascript:;\' class=\'btn btn-primary btn-xs edit_viewer\'><i class=\'fa fa-eye\'></i>&nbsp;Ver</a>';
				//}
			}
			//if ($this->bt_excluir) {
			if ($ses['appUser'][$ses['IndPag']]['perm_excluir'] !== 'N' && $this->bt_excluir) {
				$btns_action .= '&nbsp;<a href=\'javascript:;\' onclick=excluir(\'' . base64_encode($key) . '\',this)  class=\'btn btn-xs btn-danger\'><i class=\'fa fa-trash\'></i>&nbsp;Exclui</a>';
			}
			//}
		}
		return $btns_action;
	}
}