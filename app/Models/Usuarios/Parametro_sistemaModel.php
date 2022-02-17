<?php

namespace App\Models\Usuarios;

use App\Models\My_model;

class Parametro_sistemaModel extends My_model
{
  protected $table = PREFIXO_TB.'parametro_sistema';
  protected $allowedFields = ['email_suporte'];
}
