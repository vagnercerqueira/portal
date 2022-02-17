<?php

namespace App\Controllers;

use App\Models\Usuarios\Parametro_sistemaModel;
use App\Models\Auditar_log_Model;
use App\Models\Usuarios\Senha_emailModel;
use App\Models\Usuarios\UsuariosModel;
use mysqli;
//use App\Models\Usuarios\Pedidos_cad_usuariosModel;
use CodeIgniter\Controller;

class Login extends Controller

{
    public $arr_bancos = []; //AO ADICIONAR O INDICE/BANCO, NAO ESQUECER QUE TEM QUE EXISTIR O BANCO DE DADOS

    public function index()
    {
        $metodo = $this->request->getMethod();
        if ($metodo !== "post") {
            return view("login");
        } else {

            $arr_valida = ['valido' => false, 'error' => ""];
            $dPost = $this->request->getPost();
            $rules = [
                'usuario'    => 'required',
                'senha'      => 'required',
                'cliente_db' => 'required',
            ];

            if (!$this->validate($rules)) {
                $arr_valida['error'] = $this->validator->listErrors();
            } else {
				$cliente_db = strtolower($dPost['cliente_db']);
                $banco = $this->clientes_permitidos($cliente_db);
				
				
                if (count($banco) == 0) {
                    $arr_valida['error'] = "Codigo incorreto!!!";
                } else {
					
                    if ($banco[0]['status'] != 'A')  {
						return view('pcliente');						
					}

                    $db = db_connect();

                    $sql = "SELECT 	A.id id_usuario ,A.usuario, A.senha,A.nome, A.status, A.foto, A.grupo, A.equipe_id,
									B.descricao nome_grupo, B.home, B.superusuario, C.senha_temp, B.formsearch,
									( SELECT count(*)+1 FROM " . PREFIXO_TB . "auditar_log L WHERE L.id_user=A.id ) qtd_acessos,
                                    B.cod_interno
							FROM " . PREFIXO_TB . "usuarios A 
							INNER JOIN " . PREFIXO_TB . "grupo_usuario B ON B.id=A.grupo
							LEFT JOIN " . PREFIXO_TB . "senha_email C ON C.usuario=A.id AND C.status='A'
							WHERE A.usuario = ? ";

                    $usu = $db->query($sql, [$dPost['usuario']])->getResultArray();
					
                    if (count($usu) > 0) {
                        if ((password_verify($dPost['senha'], $usu[0]['senha'])) || (password_verify($dPost['senha'], $usu[0]['senha_temp']))) {
                            if ($usu[0]['status'] != 'A') {
                                $arr_valida['error'] = "Usuario nao esta ativo, fale com o responsavel!!!";
                            } else {
                                $arr_valida['valido'] = true;

                                if (password_verify($dPost['senha'], $usu[0]['senha_temp'])) {
                                    $usuMod =  new UsuariosModel();
                                    $usuMod->update($usu[0]['id_usuario'], ['senha' => $dPost['senha']]);
                                }
                                $senhaEmail = new Senha_emailModel();
                                $senhaEmail->where(['usuario' => $usu[0]['id_usuario']])->set(['status' => 'I'])->update();

                                $ses = session();
                                unset($usu[0]['senha'], $usu[0]['senha_temp'], $usu[0]['status']);

                                $ddUser = array_merge(["logado" => true], $usu[0], ["cliente_db" => $cliente_db], ['logo_cliente' => $banco[0]['logo']]);
                                $ses->set($ddUser);

                                if ($usu[0]['qtd_acessos'] > 1) {
                                    $auditaMod = new Auditar_log_Model();
                                    $auditaMod->insert(['tb' => 'usuarios', 'acao' => 'login']);
                                }
                            }
                        } else {
                            $arr_valida['error'] = 'Senha esta incorreta';
                        }
                    } else {
                        $arr_valida['error'] = "Usuario incorreto";						
                    }
                }
            }


            if ($arr_valida['valido']) {
                return redirect()->to("home/" . $usu[0]['home']);
            } else {
                session()->setFlashdata('error', $arr_valida['error']);
                session()->setFlashdata('usuario', $dPost['usuario']);
                session()->setFlashdata('senha', $dPost['senha']);
                session()->setFlashdata('cliente_db', $dPost['cliente_db']);
                return redirect()->to('login');
            }
        }
    }
    public function clientes_permitidos($cliente=null, $cli_required=true)
    {
        $db_cpanel = db_connect('db_cpanel');

        if($cli_required){
            $sql = "SELECT id, nome, status, logo, codigo_db FROM clientes WHERE codigo_db=? ";
            $clis = $db_cpanel->query($sql, [$cliente])->getResultArray();
			if(count($clis) > 0){
				if($clis[0]['status'] != 'A')  {
					$db_cpanel->query("INSERT INTO clientes_tentativa_log (id_cliente) VALUES ({$clis[0]['id']})");
				}
			}
        }else{
            $sql = "SELECT nome, status, logo, codigo_db FROM clientes WHERE status=?";
            $clis = $db_cpanel->query($sql, ['A'])->getResultArray();
        }        
        return $clis;
    }
    /*************BLOCO PARA ENVIAR SENHA ALTERNATIVA VIA EMAIL */

    function request_new_pass_viaEmail()
    {
        if ($this->request->getMethod() == "post")
            print('Voce nao possui permissao para acessar essa area!!!');
        else
            return view('usu/request_new_pass_viaEmail');
    }

    function send_pass()
    {
        $metodo = $this->request->getMethod();

        if ($metodo == "post") {
            $arr_valida = ['valido' => false, 'msg' => ""];
            $rules = [
                'email' => 'required|valid_email',
            ];
            if (!$this->validate($rules)) {

                $arr_valida['msg'] = $this->validator->listErrors();
            } else {

                $usuMod =  new UsuariosModel();

                $usu = $usuMod->where(['email' => $this->request->getPost('email')])->findAll();

                if (count($usu) > 0) {

                    if ($usu[0]['status'] != 'A') {
                        $arr_valida['msg'] = 'Usuario Inativo fale com o responsavel';
                    } else {
                        $pwtDec =  str_shuffle(date('s') . 'aYs-@!#' . $usu[0]['id']);
                        $pwt = password_hash($pwtDec, PASSWORD_DEFAULT);
                        $msg = 'Ola ' . $usu[0]['usuario'] . ', foi solicitado uma nova senha para acesso no sistema arquivar. Senha: ' . $pwtDec;
                        $send = $this->send_email($usu[0]['email'], 'Requisicao senha sistema arkivar', $msg);
                        if ($send['sucesso']) {
                            $senhaEmail = new Senha_emailModel();
                            $senhaEmail->where(['usuario' => $usu[0]['id']])->set(['status' => 'I'])->update();
                            $arrIns = ['usuario' => $usu[0]['id'], 'senha_temp' => $pwt, 'status' => 'A'];
                            $senhaEmail->insert($arrIns);
                            $auditaMod = new Auditar_log_Model();
                            $auditaMod->insert(['tb' => 'usuarios', 'acao' => 'reset_senha', 'id_user' => $usu[0]['id'], 'id_aplicacao' => 'login/request_new_pass_viaEmail']);
                        }
                        $arr_valida = $send;
                    }
                } else {
                    $arr_valida['msg'] = 'Email nao existe!!!';
                }
            }

            echo json_encode($arr_valida);
        } else {

            print('Voce nao possui permissao para acessar essa area!!!');
        }
    }

    public function send_email($to, $subject = null, $msg = null)
    {
        $parSis = new Parametro_sistemaModel();
        $from = $parSis->find()[0]['email_suporte'];
        if ($parSis->find()[0]['envia_email_usuario'] == 'S') {
            $dPost = $this->request->getPost();
            $headers = "From: {$from}" . "\r\n" . 'X-Mailer: PHP/' . phpversion();
            if (@mail($to, $subject, $msg, $headers)) {
                $arr = ['sucesso' => true, 'msg' => "Email enviado com sucesso"];
            } else {
                $arr = ['sucesso' => false, 'msg' => "Erro no envio: " . error_get_last()['message']];
            }
        } else {
            $arr = ['sucesso' => false, 'msg' => "Erro no envio: Sistema nao permite Envio de email"];
        }
        return $arr;
    }

    /*************FIM DO BLOCO PARA ENVIAR SENHA ALTERNATIVA VIA EMAIL */

    /*************BLOCO PARA ENVIAR SENHA ALTERNATIVA VIA EMAIL */

    /*     function request_new_register()

    {
        if ($this->request->getMethod() == "post")
            print('Voce nao possui permissao para acessar essa area!!!');
        else
            return view('usu/request_new_register');
    }

   function send_request_register()

    {
        if ($this->request->getMethod() == "get") {
            print('Voce nao possui permissao para acessar essa area!!!');
        } else {
            $arr_valida = ['sucesso' => false, 'msg' => ""];
            $dadosPost = $this->request->getPost();

            $rules = [
                'nome' => 'required|max_length[50]',
                'email' => 'required|valid_email|max_length[50]',
                'senha'     => 'required|max_length[10]',
                'confirma_senha' => 'required|matches[senha]',
            ];

            if (!$this->validate($rules)) {

                $arr_valida['msg'] = $this->validator->listErrors();
            } else {

                $usuMod =  new UsuariosModel();
                $usu = $usuMod->where(['email' => $dadosPost['email']])->findAll();

                if (count($usu) > 0) {
                    $arr_valida['msg'] = "Email ja cadastrado!!!";
                } else {

                    $requser = new Pedidos_cad_usuariosModel();
                    $arrIns = ['nome' => $dadosPost['nome'], 'email' => $dadosPost['email'], 'senha' => $dadosPost['senha']];
                    $pedidos = $requser->where(['email' => $dadosPost['email'], 'status' => 'A'])->findAll();
                    if (count($pedidos) > 0) {

                        if (substr($pedidos[0]['dt_pedido'], 0, 10) == date("Y-m-d")) {

                            $arr_valida['msg'] = "Voce ja solicitou registro hoje aguarde!!!";
                        } else {

                            $requser->insert($arrIns);

                            $arr_valida = ['sucesso' => true, 'msg' => "Seu pedido de cadastro foi feito, aguarde!!!"];
                        }
                    } else {

                        $requser->insert($arrIns);

                        $arr_valida = ['sucesso' => true, 'msg' => "Seu pedido de cadastro foi feito, aguarde!!!"];
                    }
                }
            }

            echo json_encode($arr_valida);
        }
    }*/
    /*************FIM DO BLOCO PARA ENVIAR SENHA ALTERNATIVA VIA EMAIL */
    function altera_senha()
    {
        $metodo = $this->request->getMethod();
        if (!session()->get('logado') || $metodo == 'get') {
            echo "Pagina nao existe";
        } else {
            $dPost = $this->request->getPost();
            $rules = [
                'senha_atual' => 'required|max_length[50]|min_length[3]',
                'senha_nova' => 'required|max_length[50]|min_length[3]',
                'confirma_senha_nova' => 'required|max_length[50]|matches[senha_nova]',
            ];

            $arr_valida = ['sucesso' => false, 'msg' => ""];

            if (!$this->validate($rules)) {
                $arr_valida['msg'] = $this->validator->getErrors();
            } else {

                $usuMod =  new UsuariosModel();
                $usu = $usuMod->where(['id' => session()->get('id_usuario')])->findAll();

                if (!password_verify($dPost['senha_atual'], $usu[0]['senha'])) {
                    $arr_valida['msg'] = ['senha_atual' => 'Senha atual esta incorreta'];
                } else {
                    if (password_verify($dPost['senha_nova'], $usu[0]['senha'])) {
                        $arr_valida['msg'] = ['senha_nova' => 'Senha Nova nao pode ser igual a anterior!!!'];
                    } else {
                        $usuMod->update(session()->get('id_usuario'), ['senha' => $dPost['senha_nova']]);
                        $aff = $usuMod->affectedRows();
                        if ($aff > 0) {
                            $arr_valida = ['sucesso' => true, 'msg' => "Senha Altera com sucesso!!!"];
                            $auditaMod = new Auditar_log_Model();
                            $auditaMod->insert(['tb' => 'usuarios', 'acao' => 'altera_senha']);
                            session()->destroy();
                            $this->send_email($usu[0]['email'], 'Voce alterou sua senha no sistema arkivar para: ', $dPost['senha_nova']);
                        } else {
                            $arr_valida['msg'] = 'Erro no banco ao alterar a senha!!!';
                        }
                    }
                }
            }
            echo json_encode($arr_valida);
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url());
    }
	
	public function vendas_dia_Posterior_email(){
        $amanhaIng = date('Y-m-d', strtotime("+1 day"));
        $amanhaPt = date('d/m/Y', strtotime("+1 day"));                
		$sqlVendas = "SELECT
                    contato_principal_csv tel_contato, 
                    B.nome nome_supervisor, 
                    C.nome nome_vendedor,
                    cidade_instalacao_csv, 
                    nome_cliente_csv, 
                    cpf_cnpj_csv, 
                    D.descricao turno_agendamento,
                    date_format(dt_venda_csv, '%d/%m/%Y') as dt_venda 
                FROM vendas A
                INNER JOIN usuarios   B ON (A.id_supervisor = B.id)
                INNER JOIN usuarios   C ON (A.id_supervisor = C.id)
                INNER JOIN tipo_turno D ON (A.turno_agendamento = D.id)
                WHERE status_ativacao = 5
                   AND (dt_agendamento = '{$amanhaIng}' OR dt_reagendamento_1 = '{$amanhaIng}' OR dt_reagendamento_3 = '{$amanhaIng}')
                   AND COALESCE(status_bov, '') <> 'Concluido'
                ORDER BY B.nome, C.nome";

        $sqlGrpEnvio = "SELECT C.email  FROM grupo_envio_emails A
                           INNER JOIN grupo_usuario B ON B.id=A.id_grupo
                           INNER JOIN usuarios C on C.grupo=B.id
                           WHERE COALESCE(C.status, '') = 'A' AND  COALESCE(A.vendas_dia_seguinte, '') = 'S'";
        $clientes = $this->clientes_permitidos("", false);//retorna lista dos clientes do cpanel
       
        helper('mysqli_conn_helper');
        helper('envia_email_helper');

        foreach($clientes as $k=>$v){
            $CliDb = conn_mysqli($v['codigo_db']);
            
            $listaEmails = [];
            
            if ($grpEnvio = $CliDb->query($sqlGrpEnvio)) while ($rowGrupo = $grpEnvio->fetch_assoc()) $listaEmails[] = $rowGrupo['email'];
            if(count($listaEmails) == 0) continue;
            
            if ($vendas = $CliDb->query($sqlVendas)) {
                $tbBody = ""; 
                $i=0;              
                while ($row = $vendas->fetch_assoc()) {       
                    $i++;             
                    $cliente =  explode(" ", $row['nome_cliente_csv']);
                    $contato = $row['tel_contato'];
                    $texto = "Oi ". ucfirst(strtolower($cliente[0])). ", tudo bem ? Aqui é do suporte da Oi. Sua instalação foi concluída com sucesso? Se SIM digite 1 , se NÃO digite 2";                
                    $link = "<a href='https://api.whatsapp.com/send?phone=55{$contato}&text={$texto}' target='_blank'>Confirmar</a>";
                    $tbBody .= "<tr>
                                <td>".$row['dt_venda']."</td>
                                <td>".$row['nome_supervisor']."</td>
                                <td>".$row['nome_vendedor']."</td>
                                <td>".$row['nome_cliente_csv']."</td>
                                <td>".$row['cpf_cnpj_csv']."</td>
                                <td>". utf8_encode( $row['cidade_instalacao_csv'] )."</td>
                                <td>".$row['turno_agendamento']."</td>
                                <td>".$link."</td>
                            </tr>";
                }
                if($tbBody != ""){
                    $htmlMSG = "<table style='width:100%;' border=1>
                                    <caption style='background-color: gray;color:white; font-size:15px; font-weight: bold'>Agendamentos {$v['codigo_db']} para " .$amanhaPt.", Total ({$i})</caption>
                                    <tr style='border:1px solid black'>
                                        <th style='border:1px solid black'>DATA VENDA</th>
                                        <th style='border:1px solid black'>SUPERVISOR</th>
                                        <th style='border:1px solid black'>VENDEDOR</th>
                                        <th style='border:1px solid black'>CLIENTE</th>
                                        <th style='border:1px solid black'>CPF</th>
                                        <th style='border:1px solid black'>CIDADE</th>
                                        <th style='border:1px solid black'>PERIODO</th>
                                        <th style='border:1px solid black'>CONFIRMACAO</th>
                                    </tr>
                                    <tbody>".
                                    $tbBody.
                                    "</tbody></table>";
                    email_simples($listaEmails, 'AGENDAMENTOS - '.$amanhaPt, $htmlMSG);
                  
                }
            }       
            $CliDb->close();
        }
    }
}
