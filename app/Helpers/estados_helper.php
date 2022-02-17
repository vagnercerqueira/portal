<?php

function estados($uf = null)
{
    $arr = [
        '' => 'Selecione...',
        'AC' => 'ACRE',
        'AL' => 'ALAGOAS',
        'AM' => 'AMAZONAS',
        'AP' => 'AMAPÁ',
        'BA' => 'BAHIA',
        'CE' => 'CEARÁ',
        'DF' => 'DISTRITO FEDERAL',
        'ES' => 'ESPÍRITO SANTO',
        'GO' => 'GOIÁS',
        'MA' => 'MARANHÃO',
        'MG' => 'MINAS GERAIS',
        'MS' => 'MATO GROSSO DO SUL',
        'MT' => 'MATO GROSSO',
        'PA' => 'PARÁ',
        'PB' => 'PARAÍBA',
        'PE' => 'PERNAMBUCO',
        'PI' => 'PIAUÍ',
        'PR' => 'PARANÁ',
        'RJ' => 'RIO DE JANEIRO',
        'RN' => 'RIO GRANDE DO NORTE',
        'RO' => 'RONDONIA',
        'RR' => 'RORAIMA',
        'RS' => 'RIO GRANDE DO SUL',
        'SC' => 'SANTA CATARINA',
        'SE' => 'SERGIPE',
        'SP' => 'SÃO PAULO',
        'TO' => 'TOCANTINS'
    ];
    if ($uf !== null) return $arr[$uf];
    else return $arr;
}