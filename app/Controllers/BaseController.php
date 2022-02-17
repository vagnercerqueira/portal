<?php

namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use App\Models\Auditar_log_Model;
use App\Models\CrudModel;
use CodeIgniter\Controller;

class BaseController extends Controller
{
	private $ErrosDb = [1062 => ' REGISTRO DUPLICADO, VALORES JA CADASTRADOS EM OUTRO REGISTRO', 1452 => ' o valor do campo [X] Nao possui integridade ou esta vazio', 1451 => "ESTE REGISTRO NAO PODE SER EXCLUIDO, ESTA SENDO USADO EM OUTRO CADASTRO!"];

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['form', 'menu_helper', 'funcoes_uteis_helper'];
	protected $tbs_crud = [];
	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();
	}
	public function load_template($vars = [], $pag = null)
	{
		$sess = session()->get();
		$menu = BARRA_NAVEGACAO == 'L' ? "meu_left" : "menu_top";
		$uri = service('uri');
		$segm = $uri->getSegments();
		$parser = \Config\Services::parser();

		$vars['titulo'] = $vars['titulo'] ?? $sess['appUser'][$sess['IndPag']]['nome'];
		$pag  = $pag ?? (count($segm) == 1 ? $segm[0] : $segm[0] . "/" . $segm[1]);

		$vars['js_app'] = $vars['js_app'] ?? $pag;
		$vars['js_crud'] = $vars['js_crud'] ?? true;
		$body = $uri->getSegment(1);
		echo view('template/header', $vars);
		echo view('template/' . $menu);
		echo view($pag);
		echo view('template/footer');
	}

	//-------------------------------------FUNCOES CRUD-------
	public function crud()
	{

		$metodo = $this->request->getMethod();
		$retorno = ['sucesso' => false, 'msg' => ''];
		if ($metodo !== "post") {
			print('Voce nao possui permissao para acessar essa area!!!');
		} else {
			$dPost = $this->request->getPost();

			$pg = session()->get('IndPag');
			$apps = session()->get('appUser');

			if (!isset($dPost['form_']) || !$this->tbs_crud[$dPost['form_']]) {
				$retorno['msg'] = 'voce nao definiu o modelo ou nao foi parametrizado, file: basecontroller!!!';
			} else {
				$f_dados = [];
				$fieldsBefore = [];
				$mod = new CrudModel($this->tbs_crud[$dPost['form_']]);
				if (!$mod->tbExist()) {
					$retorno['msg'] = 'tabela nao existe: file: basecontroller!!!';
				} else {
					if (in_array($dPost['myacao'], ["cadastrar", "alterar"])) {

						$valida_campos = $this->valida_campos();
						if (!$valida_campos) {
							$retorno['msg'] = 'Erro na validacao dos campos'; //listErrors()  lista os erros como string, getErrors() como array
							$retorno['list_erros'] = $this->validator->getErrors();
						} else {
							$dados = $dPost['myacao'] == 'cadastrar' ? $this->preIns($dPost) : $this->preUpd($dPost);
							$dados = $this->preInsUpd($dados);
							/*print_r($f_dados);
							exit;*/
							$f_db = $mod->schema();

							$f_dados = $this->trata_ins_up($dados, $f_db); //verifica os campos em comum entre o post e a tabela
							if (count($f_dados) == 0) {
								$retorno['msg'] = "Nao há campos da tabela";
							} else {
								if ($dPost['myacao'] == 'cadastrar') {
									if ($apps[$pg]['perm_cadastrar'] == 'N') {
										$retorno['msg'] = "Ação nao permitida para este usuario!!!";
									} else {

										$aff = $mod->insertItem($f_dados);

										if ($aff['code'] == 0) {
											$retorno['msg'] = "CADASTRO EFETUADO COM SUCESSO!!!";
										} else {
											$retorno['msg'] = "ERRO AO INSERIR REGISTRO!!!<br>" .  $this->trataErroBanco($aff)  ?? "<br>ERRO NAO ENCONTRADO NA LISTA!!!";
										}

										//$retorno['msg'] = $aff['code'] == 0 ? "CADASTRO EFETUADO COM SUCESSO!!!" : "ERRO AO INSERIR REGISTRO!!!<br>" .  $this->ErrosDb[$aff['code']]  ?? "<br>ERRO NAO ENCONTRADO NA LISTA!!!";
										if ($aff['code'] == 0) {
											$this->posIns($f_dados, $aff['key']);
											$retorno['sucesso'] = true;
											$f_dados['ID'] = $aff['key'];
										}
									}
								} else {
									if ($apps[$pg]['perm_alterar'] == 'N') {
										$retorno['msg'] = "Ação nao permitida para este usuario!!!";
									} else {
										if (is_numeric($f_dados['ID'])) {
											$fieldsBefore = $mod->getAnyItems($f_dados['ID'])[0];
											$aff = $mod->updateItem($f_dados['ID'], $f_dados);
											$retorno['msg'] = $aff['code'] == 0 ? "ALTERAÇÃO EFETUADA COM SUCESSO!!!" : "ERRO AO ALTERAR REGISTRO!!!<br>" .  $this->trataErroBanco($aff)  ?? "<br>ERRO NAO ENCONTRADO NA LISTA!!!";
											
											if ($aff['code'] == 0) {
												$this->posUpd($f_dados);
												$retorno['sucesso'] = true;
											}
										} else {
											$retorno['msg'] = "Chave Invalida";
										}
									}
								}
							}
						}
					} elseif ($dPost['myacao'] === "editar") {
						$id = base64_decode($dPost['chave']);
						$row = $mod->getAnyItems($id);
						if (count($row) == 0) {
							$retorno['msg'] = "Registro nao encontrado";
						} else {
							$row = array_change_key_case($row[0], CASE_UPPER);
							foreach ($row as $k => $v) {
								if ($v == null) {
									$row[$k] = "";
								}
							}
							$row = $this->post_edicao($row);
							$retorno = ['sucesso' => true, 'row' => $row];
						}
					} elseif ($dPost['myacao'] === "excluir") {
						if ($apps[$pg]['perm_excluir'] == 'N') {
							$retorno['msg'] = "Ação nao permitida para este usuario!!!";
						} else {
							$id = base64_decode($dPost['chave']);
							$fieldsBefore = $mod->getAnyItems($id)[0];
							$aff = $mod->deleteItem($id);
							$retorno['msg'] = $aff['aff'] > 0 ? "EXCLUSÃO EFETUADA COM SUCESSO!!!" : "ERRO AO EXLUIR REGISTRO!!!\n" .  $this->ErrosDb[$aff['code']]  ?? "<br>ERRO NAO ENCONTRADO NA LISTA!!!";
							if ($aff['aff'] > 0) {
								$retorno['sucesso'] = true;
							}
						}
					} else {
						$retorno['msg'] = "Ação nao Permitida";
					}
				}
			}
			if ($retorno['sucesso'] == true) {
				$auditaMod = new Auditar_log_Model();
				$dadosAudit['DADOS']  = json_encode(['BEFORE' => $fieldsBefore, 'AFTER' => $f_dados]);
				$auditaMod->insert(['tb' => $this->tbs_crud[$dPost['form_']], 'acao' => $dPost['myacao'], 'dados' => $dadosAudit['DADOS']]);
			}
			echo json_encode($retorno);
		}
	}

	/*public function audit_log($acao, $tb, $fieldsAfter, $fieldsBefore)
	{
		//if($acao != 'editar'){

		$ses = session()->get();
		$ip = (isset($_SERVER['HTTP_CLIENT_IP'])) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
		$aplicacao = service('uri')->getPath();
		$idUser = session()->get('id_usuario');
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		$dadosAudit = ['IP' => $ip, 'HTTP_USER_AGENT' => $userAgent, 'ID_APLICACAO' => $ses['appUser'][$ses['IndPag']]['id'], 'APLICACAO' => $aplicacao, 'ID_USER' => $idUser, 'TB' => $tb, 'ACAO' => $acao];

		$mod = new CrudModel('auditar_log');
		$dadosAudit['DADOS']  = json_encode(['BEFORE' => $fieldsBefore, 'AFTER' => $fieldsAfter]);

		$aff = $mod->insertItem($dadosAudit);
		//}
	}*/
	public function trataErroBanco($Err)
	{
		if($Err['code'] == '1452'){//erro na chave estrangeira - constraint
			$rePosIni =  strpos($Err['message'], 'KEY (');
			$rePosFim =  strpos($Err['message'], ') REFERENCES');		
			$msg =  "FALHA NA CHAVE ESTRANGEIRA: ".substr( $Err['message'], $rePosIni, ( ($rePosFim+1) - $rePosIni) );
		}else{//implementar outros codigos se necessario
			$msg = $this->ErrosDb[$Err['code']];	
		}
		
		return $msg;
	}

	public function valida_campos()
	{
		return true;
	}
	public function preIns($dados)
	{
		return $dados;
	}
	public function preInsUpd($dados)
	{
		return $dados;
	}
	public function preUpd($dados)
	{
		return $dados;
	}
	public function trata_ins_up($dados, $f_db)
	{
		$dados = array_change_key_case($dados, CASE_UPPER);
		$f_db = array_flip($f_db);
		$f_db = array_change_key_case($f_db, CASE_UPPER);

		$f_dados = array_intersect_key($dados, $f_db);
		return $f_dados;
	}
	public function posIns($f_dados, $key)
	{
	}
	public function posUpd($f_dados)
	{
	}
	public function post_edicao($row)
	{
		return $row;
	}
	//-------------------------------------FIM FUNCOES CRUD-------	
	public function retorna_file($arq, $w = '30', $h = '30', $class = null)
	{
		$img_path = WRITEPATH . 'uploads/img/usuarios/' . $arq;

		$file = new \CodeIgniter\Files\File($img_path);

		$is_img = explode('/', $file->getMimeType());

		$img_data = file_get_contents($img_path);

		$base64_code = base64_encode($img_data);

		if ($is_img[0] == 'image') {
			$base64_str = 'data:image/' . $is_img[1] . ';base64,' . $base64_code;
			$retorno = '<img src="' . $base64_str . '" width="' . $w . '" height="' . $h . ' class="' . $class . '">';
		} else {
			$retorno = "";
		}
		return $retorno;
	}
	public function dsearch($rows = [], $id = 'id', $desc = 'descricao')
	{
		$arr = [];
		foreach ($rows as $k => $v) {
			$arr[$k]['ID'] = $v[$id];
			$arr[$k]['DESCRICAO'] = $v[$desc];
		}
		return $arr;
	}
	public function api_ceps()
	{
		$campos = [ //campo=>indicie no csv
			'uf' => null,
			'municipio' => null,
			'logradouro' => null,
			'num_fachada' => null,
			'complemento' => null,
			'complemento2' => null,
			'complemento3' => null,
			'cep' => null,
			'bairro' => null,
			'tipo_viabilidade' => null,
			'nome_cdo' => null,
			'cod_logradouro' => null
		];
		helper('sqlite');
		$db = conn_sqlite();

		$busca = str_replace("'", "''", ($this->request->getPost('cep_or_cdo')));
		if (is_numeric($busca)) {
			$where = "cep=$busca";
		} else {
			$where = "detalhes like '%$busca%'";
		}

		$cliente = session()->get('cliente_db');
		$tb = $cliente . "_dfv";
		$sql = "SELECT * FROM {$tb} WHERE {$where} ";

		$sth = $db->prepare($sql);
		$sth->execute();
		$rows = $sth->fetchAll();

		$arr = [];
		foreach ($rows as $k => $v) {
			$arr_rows = json_decode($v['detalhes']);
			foreach ($arr_rows as $key => $val) {
				if (!is_numeric($busca) && $val[10] != $busca) {
					continue;
				}
				$campos = [ //campo=>indicie no csv
					'uf' => $val[0],
					'municipio' => $val[1],
					'logradouro' => $val[2],
					'num_fachada' => $val[3],
					'complemento' => $val[4],
					'complemento2' => $val[5],
					'complemento3' => $val[6],
					'cep' => $val[7],
					'bairro' => $val[8],
					'tipo_viabilidade' => $val[9],
					'nome_cdo' => $val[10],
					'cod_logradouro' => $val[11],
				];
				$arr[] = $campos;
			}
		}
		echo json_encode(["data"  =>  $arr]);
	}
}
