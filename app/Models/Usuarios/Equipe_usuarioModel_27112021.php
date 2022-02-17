<?php

namespace App\Models\Usuarios;

use App\Models\My_model;

class Equipe_usuarioModel extends My_model
{
  protected $table = PREFIXO_TB . 'equipe_usuario';
  protected $allowedFields = ['descricao', 'supervisor'];

  public function equipe_supervisor($cod_interno = null, $equipe = 0)
  {
    $tipo_equipe = ($equipe === 0 ? '' : ' AND A.id = "' . $equipe . '"');
    $sql = "SELECT
        A.id,
        B.nome supervisor,
        A.descricao equipe,
        A.supervisor id_supervisor
    FROM equipe_usuario A
    INNER JOIN usuarios B ON B.id = A.supervisor
    INNER JOIN 	grupo_usuario C on C.id = B.grupo
    WHERE C.cod_interno = '{$cod_interno}'" . $tipo_equipe;

    $rows = $this->query($sql)->getResultArray();
    $rows = (count($rows) > 0 ? $rows : [0 => ["supervisor" => "", "equipe" => ""]]);
    return $rows;
  }
  public function Vendedores($cod_interno = '', $equipe = '')
  {
    $tipo_equipe = ($equipe == '' ? '' : ' AND B.equipe_id = ' . $equipe);
    $sql = "SELECT
              B.id,
              B.nome vendedor
            FROM grupo_usuario A 
            INNER JOIN usuarios B ON B.grupo = A.id
             WHERE A.cod_interno = '{$cod_interno}'" . $tipo_equipe;
    $rows = $this->query($sql)->getResultArray();
    return $rows;
  }
}