<?php

namespace App\Controllers;

use App\Models\Usuarios\Senha_emailModel;
use App\Models\Usuarios\UsuariosModel;
use CodeIgniter\Controller;

class Email_senha extends Controller
{
    private function parametro_geral_emp()
    {
        $db = db_connect();

        $row = $db->query("SELECT 
                            CASE WHEN COUNT(*) = 0 THEN 'EMPRESA ?' ELSE nome_empresa END nome_empresa,
                            CASE WHEN COUNT(*) = 0 THEN 'logo_antwort.ico' ELSE logo_empresa END logo_empresa,
                            CASE WHEN COUNT(*) = 0 THEN '1' ELSE barra_navegacao END navbar_empresa,
                            CASE WHEN COUNT(*) = 0 THEN 'navbar-white navbar-light' ELSE cor_navbar END cornavbar_empresa
                            FROM ( SELECT * FROM empresa ) emp")->getRowArray();
        return  $row;
    }
    public function index()
    {
        $data = [
            "titulo" => "Esqueci minha senha",
            "ddEmp" => $this->parametro_geral_emp()
        ];
        return view('email_senha', $data);
    }
    public function valida_dados()
    {
        $dPost = $this->request->getPost();
        $arr_valida = ['sucesso' => false, 'msg' => ""];
        $metodo = $this->request->getMethod();

        if ($metodo !== "post") {
            $arr_valida['msg'] = 'Area nao permitida!!!';
        } else {
            $rules = [
                'usuario' => 'required|max_length[20]|min_length[3]',
                'email' => 'required|valid_email',
            ];
            if (!$this->validate($rules)) {
                $arr_valida['msg'] = $this->validator->listErrors();
            } else {

                $usuarios = new UsuariosModel();
                $usu = $usuarios->where(['usuario' => $dPost['usuario']])->findAll();
                if (count($usu) > 0) {
                    $usu = $usu[0];
                    if ($usu['email'] == $dPost['email']) {
                        if ($usu['status'] == 'A') {
                            $ret_envio = $this->send_email("suporte@arkivar.net", $usu['email'], "Alteracao de senha ANTWORT", "testando senha", $usu['id']);
                            $arr_valida = $ret_envio;
                        } else {
                            $arr_valida['msg'] = "Usuario inativo, fale com o responsavel!!!";
                        }
                    } else {
                        $arr_valida['msg'] = "Email incorreto!!!";
                    }
                } else {
                    $arr_valida['msg'] = "Usuario nao existe!!!";
                }
            }
        }
        echo json_encode([]);
    }
    public function send_email($from, $to, $subject = null, $msg = null, $id_user)
    {
        $dPost = $this->request->getPost();

        $to      = $to;
        $subject = $subject;

        $headers = "From: {$from}" . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        $pwt = str_shuffle(rand(10, 20) . 'a_S-m=TAsKEY' . $id_user . date("dmyHis"));

       // $message = "Voce solicitou a alteracao de senha do sistema arkivar para o usuario: {$dPost['usuario']}\n\n, clique no link abaixo \n\n\n";
       // $message .= base_url('Email_senha/confirm?moment=' . base64_encode($id_user) . '&pw=' . (base64_encode($pwt)));

        $success = mail($to, $subject, $message, $headers);
        if (!$success) {
            return $arr = ['sucesso' => false, 'msg' => "Erro no envio=>" . error_get_last()['message']];
        } else {
            $senhUsu = new Senha_emailModel();
            $arr_insert = [
                'usuario' => $id_user,
                'senha_temp' => $pwt
            ];

            $senhUsu->insert($arr_insert);

            return $arr = ['sucesso' => true, 'msg' => "Email enviado com sucesso"];
        }
        //    $this->headers .= 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=utf-8' . "\r\n";
        //   mail($to_email, $subject, $content, $headers);
    }
    public function confirm()
    {
        $dGet = $this->request->getGet();
        $metodo = $this->request->getMethod();

        if ($metodo !== "get") {
            print_r("Voce nao possui permissao para acessar essa pagina!");
        } else {
            if (!isset($dGet['moment'])  || !isset($dGet['pw'])) {
                print_r("Voce nao possui permissao para acessar essa pagina!!");
            } else {
                $id = base64_decode($dGet['moment']);
                $pwt = base64_decode($dGet['pw']);
                $senhUsu = new Senha_emailModel();
                $row = $senhUsu->where(['usuario' => $id, 'senha_temp' => $pwt, 'status' => 'A'])->findAll();
                if (count($row) > 0) {
                    $data = [
                        "titulo" => "Alterar Senha",
                        'moment' => $dGet['moment'],
                        'pwt' => $dGet['pw']
                    ];
                    return view('alterasenhaemail', $data);
                } else {
                    print_r("Voce nao possui permissao para acessar essa pagina!!!");
                }
            }
        }
    }
    public function confirm_newpass()
    {
        $metodo = $this->request->getMethod();

        if ($metodo !== "post") {
            print_r("Voce nao possui permissao para acessar essa pagina!!!!");
        } else {
            $arr_valida = ['sucesso' => false, 'msg' => ""];
            $dPost = $this->request->getPost();
            $rules = [
                'senha' => 'required|max_length[20]|min_length[3]',
                'confirma_senha' => 'required|max_length[20]|matches[senha]',
            ];
            if (!$this->validate($rules)) {
                $arr_valida['msg'] = $this->validator->listErrors();
            } else {
                $id = base64_decode($dPost['moment']);
                $pwt = base64_decode($dPost['pwt']);
                $senhUsu = new Senha_emailModel();
                $row = $senhUsu->where(['usuario' => $id, 'senha_temp' => $pwt, 'status' => 'A'])->findAll();
                if (count($row) > 0) {
                    $dt = date('Y-m-d', strtotime($row[0]['data']));
                    if ($dt != date("Y-m-d")) {
                        $arr_valida['msg'] = "Voce nao possui permissao para acessar essa pagina!!!!!";
                    } else {
                        $usuM = new UsuariosModel();
                        $usuM->update($id, ['senha' => $dPost['senha']]);
                        $aff = $usuM->affectedRows();
                        if ($aff > 0) {
                            $dh = date('Y-m-d H:i:s');
                            $senhUsu->update($row[0]['id'], ['status' => 'I', 'dt_hr_conf' => $dh]);
                            $arr_valida = ['sucesso' => true, 'msg' => "Senha alterada com sucesso"];
                        } else {
                            $arr_valida['msg'] = "link expirou";
                        }
                    }
                } else {
                    $arr_valida['msg'] = "Voce nao possui permissao para acessar essa pagina!!!!!!";
                }
            }
            return json_encode($arr_valida);
        }
    }
}
