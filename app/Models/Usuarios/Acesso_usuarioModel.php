<?php

namespace App\Models\Usuarios;

use App\Models\My_model;

class Acesso_usuarioModel extends My_model
{
  protected $table = PREFIXO_TB.'acesso_usuario';
  protected $allowedFields = ['id_usuario', 'id_aplicacao', 'perm_cadastrar', 'perm_alterar', 'perm_excluir'];
  public function AcessUsuGrupo($idGr, $idUsu)
  {
    $query = "SELECT B.*, perm_cadastrar, perm_alterar, perm_excluir, null as idgrupo 
    FROM acesso_usuario A INNER JOIN aplicacoes B ON B.id=A.id_aplicacao 
    WHERE id_usuario='{$idUsu}'
    UNION 
    SELECT B.*, perm_cadastrar, perm_alterar, perm_excluir, A.id as idgrupo
    FROM acesso_grupo A INNER JOIN aplicacoes B ON B.id=A.id_aplicacao 
    WHERE id_grupo='{$idGr} '
    AND B.id not in (
      SELECT Z.id
        FROM acesso_usuario Y INNER JOIN aplicacoes Z ON Z.id=Y.id_aplicacao 
        WHERE id_usuario='{$idUsu}'
    )";

    $rows = $this->query($query)->getResultArray();
    return $rows;
  }
}
