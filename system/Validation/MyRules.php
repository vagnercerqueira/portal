<?php

/**
 * @package    CodeIgniter
 * @author     Vagner
 */

namespace CodeIgniter\Validation;

class MyRules
{

	public function camposVazios(string $str, string $fields, array $data): bool
	{
		$fieldsCompare = explode('.', $fields);
		$retorno = false;
		foreach ($fieldsCompare as $k => $v)
			if ($data[$v] != "") {
				$error = lang('myerrors.camposVazios');
				return true;
			}
		return false;
	}
}
