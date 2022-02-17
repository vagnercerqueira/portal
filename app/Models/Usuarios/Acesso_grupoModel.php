<?php

namespace App\Models\Usuarios;

use App\Models\My_model;

class Acesso_grupoModel extends My_model
{
  protected $table = PREFIXO_TB.'acesso_grupo';
  protected $allowedFields = ['id_grupo', 'id_aplicacao', 'perm_cadastrar', 'perm_alterar', 'perm_excluir'];
}
