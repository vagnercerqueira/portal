<?php

namespace App\Models;

class Auditar_log_Model extends My_model
{
  protected $table = PREFIXO_TB.'auditar_log';
  protected $allowedFields = ['ip', 'id_aplicacao', 'http_user_agent', 'aplicacao', 'id_user', 'tb', 'acao', 'dados'];
  protected $beforeInsert = ['beforeInsert'];
  protected $beforeUpdate = ['beforeUpdate'];

  protected function beforeInsert(array $data)
  {

    $data['data']['ip'] = (isset($_SERVER['HTTP_CLIENT_IP'])) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
    //$data['data']['id_aplicacao'] = service('uri')->getPath() == '/login' ? 0 : $ses['appUser'][$ses['IndPag']]['id'];
    $data['data']['id_aplicacao'] = $data['data']['id_aplicacao']  ?? null;
    $data['data']['http_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $data['data']['aplicacao'] = service('uri')->getPath();
    $data['data']['id_user'] =  session()->get('id_usuario');
    return $data;
  }
}
