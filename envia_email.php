<?php

require __DIR__.'/vendor/autoload.php';

use \App\Communication\Email;

$enderecoEmail = 'a.tangy@gmail.com';
$assuntoEmail = 'Teste de envio de e-mail EMERJ';
$corpoEmail = '<h2>Teste de envio de e-mail EMERJ</h2><hr><h4>Sr. André Rodrigues Ribeiro.<br> Isto é um teste!</h4><br>Com arquivo anexado ao e-mail.<hr>';
$anexosEmail = __DIR__.'/anexo-teste.txt';

$obEmail = new Email;
$sucesso = $obEmail->sendEmail($enderecoEmail,$assuntoEmail,$corpoEmail,$anexosEmail);

echo $sucesso ? 'Mensagem enviada com sucesso!' : $obEmail->getError();


 ?>
