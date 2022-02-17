<?php

namespace App\Models\Usuarios;

use App\Models\My_model;

class Senha_emailModel extends My_model
{
  protected $table = PREFIXO_TB.'senha_email';
  protected $allowedFields = ['usuario', 'senha_temp', 'status'];

  protected function beforeInsert(array $data)
  {
    $data = $this->passwordHash($data);
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
    if (isset($data['data']['	senha_temp']))
      $data['data']['senha_temp'] = password_hash($data['data']['senha_temp'], PASSWORD_DEFAULT);

    return $data;
  }
}
