<?php

namespace App\Validation;

class CustomRules
{

    #Verifica se é PF ou PJ e redireciona, para cada um do tipo
    public function validaTipoPessoa(string $valor, string $fields, array $data)
    {
        if (strlen(preg_replace('/[^0-9]/is', '', $valor)) <= 11) {
            return $this->validaCPF($valor, $fields, $data);
        } else {
            return $this->validar_cnpj($valor, $fields, $data);
        }
    }

    #VALIDA CPF
    public function validaCPF(string $cpf, string $fields, array $data)
    {

        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    #VALIDA CNPJ

    function validar_cnpj(string $cnpj, string $fields, array $data)
    {

        // Verificar se foi informado
        if (empty($cnpj))
            return false;

        // Remover caracteres especias
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        // Verifica se o numero de digitos informados
        if (strlen($cnpj) != 14)
            return false;

        // Verifica se todos os digitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj))
            return false;

        $b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0, $n = 0; $i < 12; $n += $cnpj[$i] * $b[++$i]);

        if ($cnpj[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        for ($i = 0, $n = 0; $i <= 12; $n += $cnpj[$i] * $b[$i++]);

        if ($cnpj[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        return true;
    }



    public function VerificaPlanosOperadora(string $valor, string $fields, array $data)
    {

        $db = db_connect();

        $where =  ($data['ID'] != '' ? (" AND ID <> " . $data['ID']) : null);

        $sql = "
        SELECT 
	        COUNT(*) total
        FROM planos_operadora 
        WHERE 
            (id_fibra = '" . $data['ID_FIBRA'] . "'
            AND coalesce(id_tv, '') = '" . $data['ID_TV'] . "'
            AND ((dt_ini  BETWEEN '" . $data['DT_INI'] . "' AND '" . $data['DT_FIM'] . "') 
            OR (dt_fim  BETWEEN '" . $data['DT_INI'] . "' AND '" . $data['DT_FIM'] . "')))
            {$where}";
        $query = $db->query($sql);
        $rows = $query->getRow();
        if ($rows->total == 0) {
            return true;
        } else {
            return false;
        }
    }
	
	public function validaNome(string $str){
		$arrNome = explode(' ', trim($str));
		$retorno = true;
		
		if(count($arrNome) > 1){
			foreach($arrNome as $k=>$s){
				if(trim($s) == ''){
					$retorno = false;
					break;
				}
			}
		}else{
			$retorno = false;
		}
		return $retorno;
	}
}