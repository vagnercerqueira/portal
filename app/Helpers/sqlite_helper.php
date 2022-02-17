<?php

function conn_sqlite()
{
	try {
		$db = new PDO("sqlite:" . WRITEPATH . 'db_clientes.db');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return 	$db;
	} catch (Exception $e) {
		echo "Nao foi possivel conectar ao banco : " . $e->getMessage();
		exit;
	}
}
