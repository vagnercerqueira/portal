<?php

namespace App\Models\Usuarios;

use App\Models\My_model;

class Grupo_usuarioModel extends My_model
{
  protected $table = PREFIXO_TB . 'grupo_usuario';
  protected $allowedFields = ['descricao', 'home', 'formsearch'];


  public function AcessUsuGrupo($codInterno = 0)
  {
    $query = "SELECT * FROM {$this->table}
              WHERE COD_INTERNO < {$codInterno}";

    $rows = $this->query($query)->getResultArray();
    return $rows;
  }
}
