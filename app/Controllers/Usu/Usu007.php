<?php

namespace App\Controllers\Usu;

use App\Controllers\Tb_basicas\Tb_basicas;
use App\Models\Usuarios\UsuariosModel;
use Datatables_server_side;

class Usu007 extends Tb_basicas
{
	public function __construct()
	{
		helper('form');
		$this->titulo = "Equipe";
		$this->modelo = "equipe_usuario";
		$this->field_extra = ['supervisor' => ['type' => 'int', 'size' => 11]];

		$usu = $this->grupo_user();

		//$usu = ($this->usuarioModel->userGroup());
		$usu = "<label for='SUPERVISOR'>Lider</label>" .
			//form_dropdown('SUPERVISOR', ['' => 'Selecione...'] + array_column($usu, 'nome', 'id'), '', "id='SUPERVISOR' class='form-control form-control-sm' required");
			form_dropdown('SUPERVISOR', $usu, '', "id='SUPERVISOR' class='form-control form-control-sm' required");
		$this->arr_extra = [$usu];

		$this->tbs_crud  = ['form_basicos' => $this->modelo];
	}

	public function valida_campos()
	{
		$rules = [
			'DESCRICAO' => 'required|max_length[100]|min_length[3]',
			'SUPERVISOR' => 'required',
		];
		return $this->validate($rules);
	}
	public function grupo_user()
	{
		$sql = "SELECT B.id, A.id as id_grupo, B.nome as nome_usuario, A.descricao grupo
		FROM grupo_usuario A
		INNER JOIN usuarios B on A.id=B.grupo
		WHERE A.cod_interno in (970, 990)
		ORDER BY A.id, A.descricao";

		$db = \Config\Database::connect();
		$rows = $db->query($sql)->getResult('array');
		$retorno['Selecione...'][] = 'Selecione...';
		foreach ($rows as $k => $v) $retorno[$v['grupo']][$v['id']] = $v['nome_usuario'];
		return $retorno;
	}

	public function DataTable()
	{
		$sql = "SELECT A.id, A.descricao, B.nome
				FROM equipe_usuario A
				LEFT JOIN usuarios B on B.id=A.supervisor";

		$dt = new Datatables_server_side([
			'tb' => 'equipe_usuario',
			'cols' => ["descricao", "nome"],
			'formata_coluna' => [
				3 => function ($col, $lin) {
					$dt = date('d/m/Y H:i', strtotime($col));
					return  $dt;
				},
			]
		]);
		$dt->complexQuery($sql);
	}
}
