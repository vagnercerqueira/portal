<?php

namespace App\Models\Usuarios;

use App\Models\My_model;

class AplicacoesModel extends My_model
{
  protected $table = PREFIXO_TB . 'aplicacoes';
  protected $allowedFields = ['id_pai', 'nome', 'icone', 'caminho', 'ordem'];
  public function AcessUsuGrupo()
  {

    $where = " WHERE  lower(caminho) NOT IN ('usu/usu006.php')";
    if (session()->get('superusuario') !== 'S') {
      $where = " AND lower(caminho) NOT IN ( 'usu/usu002.php' ,'usu/usu005.php', 'usu/usu004.php') ";
    }

    $query = "SELECT * FROM {$this->table} 
             {$where}";
    $rows = $this->query($query)->getResultArray();
    return $rows;
  }
}
