<?php

function email_simples($to = [], $assunto = 'Email Test', $conteudo = 'Teste envio email')
{
    $from = 'sisconp@sisconp.net';
    $nomeFrom = 'sisconp';    
    $email = \Config\Services::email();
    $email->mailType = 'html';
    $email->setFrom($from, $nomeFrom);
    $email->setTo($to);
    $email->setSubject($assunto);
    $email->setMessage($conteudo);
    $email->send();
}
