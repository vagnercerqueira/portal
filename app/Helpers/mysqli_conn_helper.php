<?php

function conn_mysqli($banco)
{		
	try{
		$con = mysqli_connect("localhost","root","",$banco);
		//$con = mysqli_connect($banco.".mysql.dbaas.com.br",$banco,"Evi@171195",$banco);		
		return $con;
	}catch(\Exception $e){
		echo "Nao foi possivel conectar ao banco : " . $e->getMessage();
		exit;
	}
	
}
