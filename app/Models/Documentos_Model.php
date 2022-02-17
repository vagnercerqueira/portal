<?php

namespace App\Models;

use CodeIgniter\Model;

class Documentos_Model extends Model
{
    protected $table = 'documentos';
    protected $allowedFields = ['nome', 'nome_rand'];

    /* public function salva_docs($files)
    {

        $ids = [];
        if (is_array($files['name'])) {
            foreach ($files['name'] as $k => $v) {
                $imgContent = file_get_contents($files['tmp_name'][$k]);
                $arrIns = ['nome' => $v, 'tipo' => $files['type'][$k], 'conteudo' => $imgContent];
                $ids[] = $this->insert($arrIns);
            }
        } else {
            $imgContent = file_get_contents($files['tmp_name']);
            $arrIns = ['nome' => $files['name'], 'tipo' => $files['type'], 'conteudo' => $imgContent];
            $ids[] = $this->insert($arrIns);
        }
        return $ids;
    }*/
}
