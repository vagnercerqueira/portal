<?php

namespace App\Models\Usuarios;

use App\Models\My_model;

class UsuariosModel extends My_model
{
  protected $table = PREFIXO_TB.'usuarios';
  protected $allowedFields = ['login', 'senha', 'nome', 'email', 'foto', 'status'];
  protected $beforeInsert = ['beforeInsert'];
  protected $beforeUpdate = ['beforeUpdate'];

  protected function beforeInsert(array $data)
  {
    $data = $this->passwordHash($data);
    $data['data']['created_at'] = date('Y-m-d H:i:s');

    return $data;
  }

  protected function beforeUpdate(array $data)
  {
    $data = $this->passwordHash($data);
    $data['data']['updated_at'] = date('Y-m-d H:i:s');
    return $data;
  }

  protected function passwordHash(array $data)
  {
    if (isset($data['data']['senha']))
      $data['data']['senha'] = password_hash($data['data']['senha'], PASSWORD_DEFAULT);

    return $data;
  }
  public function userGroup($spUser = 'N')
  {
    $rows = $this->select('usuarios.*')->join('grupo_usuario', 'usuarios.grupo=grupo_usuario.id', 'inner')->where('grupo_usuario.superusuario', $spUser)->findAll();
    return $rows;
  }
}
