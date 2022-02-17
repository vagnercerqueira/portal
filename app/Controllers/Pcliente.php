<?php

namespace App\Controllers;
use CodeIgniter\Controller;
class Pcliente extends Controller

{
    public function index()
    {
        $metodo = $this->request->getMethod();
        if ($metodo == "get") {
            return view("pcliente");
        }else{
			return view("voce nao tem permissao para acessar essa pagina");
		}
	}
}
