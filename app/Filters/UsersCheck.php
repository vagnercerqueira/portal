<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class UsersCheck implements FilterInterface
{
  private $app_pais = [];
  public function before(RequestInterface $request)
  {
    $uri = service('uri');
    $segm = $uri->getSegments();

    if (count($segm) > 0) {
      if ($segm[0] !== "" && $segm[0] !== "login") {
        $ses = session()->get();
        if (!isset($ses['logado']))
          return redirect()->to(base_url());
        else {
          $db = db_connect();

          if ($ses['superusuario'] === "S") {
            $sqlApp = "SELECT *, null as perm_cadastrar, null as perm_alterar, null as perm_excluir  FROM " . PREFIXO_TB . "aplicacoes B WHERE B.caminho <> 'Usu/Usu006.php' ORDER BY ordem asc";
            $rows = $db->query($sqlApp)->getResultArray();
          } else {
            $query = "SELECT b.id, b.id_pai
						  FROM " . PREFIXO_TB . "acesso_grupo a 
						  INNER JOIN " . PREFIXO_TB . "aplicacoes b on b.id=a.id_aplicacao
						  WHERE id_grupo='{$ses['grupo']}' AND caminho != '>'  ORDER BY ordem asc";

            $rows = $db->query($query)->getResultArray();

            if (count($rows) > 0) {
              foreach ($rows as $k => $row)
                if ($row['id_pai'] != "" && !in_array($row['id_pai'], $this->app_pais)) $this->buspa_pai($row['id_pai'], $db);

              $ids = implode(',', array_merge(array_column($rows, 'id'), $this->app_pais));

              $query = "SELECT b.*, a.perm_cadastrar, a.perm_alterar, a.perm_excluir
                        FROM " . PREFIXO_TB . "aplicacoes b 
                        LEFT JOIN " . PREFIXO_TB . "acesso_grupo a on b.id=a.id_aplicacao
                        WHERE b.ID IN ($ids)
                        order by ordem asc";
              $rows = $db->query($query)->getResultArray();
            }
          }

          $cam = strtolower($segm[0] . (isset($segm[1]) ? ('/' . $segm[1]) : null));
          $indPg = null;
          $sessArr = [];
          foreach ($rows as $k => $v) {
            $sessArr[$v['id']] = $v;
            if ((strtolower(str_replace('.php', '', $v['caminho'])) == $cam)) {
              $indPg = $v['id'];
            }
          }

          $perPag = $this->verifica_acesso($rows, $segm, $ses['home']);
          if (!$perPag) {
            return redirect()->to(base_url());
          }
          session()->set('appUser', $sessArr);
          session()->set('IndPag', $indPg);
        }
      }
    }
  }
  private function buspa_pai($idPai, $db)
  {
    $query = " SELECT M.id_pai FROM " . PREFIXO_TB . "aplicacoes M WHERE M.id={$idPai}";
    $r = $db->query($query)->getRowArray();
    $this->app_pais[] = $idPai;
    if ($r['id_pai'] != "") {
      $this->app_pais[] = $r['id_pai'];
      $this->buspa_pai($r['id_pai'], $db);
    }
  }

  private function verifica_acesso($acessos, $segm, $home, $bool = false)
  {

    if ($segm[0] == 'home' && $segm[1] == $home) {
      $bool = true;
    } else {
      foreach ($acessos as $k => $v) {
        if (strtolower(str_replace('.php', '', $v['caminho'])) == $segm[0] . '/' . $segm[1]) {
          $this->sUser['PERMS'] = array('CADA' => $v['perm_cadastrar'], 'EDIT' => $v['perm_alterar'], 'DEL' => $v['perm_excluir']);
          $bool = true;
          break;
        }
      }
    }
    return $bool;
  }

  //--------------------------------------------------------------------

  public function after(RequestInterface $request, ResponseInterface $response)
  {
  }
}
