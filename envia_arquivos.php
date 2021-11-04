<?php

require __DIR__.'/vendor/autoload.php';

use \App\File\Upload;

if(isset($_FILES['arquivo'])){

  //INSTÂNCIA DE UPLOAD
  $obUpload = new Upload($_FILES['arquivo']);

  //ALTERA O NOME DO ARQUIVO
  //$obUpload->setName('novo-arquivo-de-nome-alterado');

  //GERA UM NOME ALEATÓRIO PARA O ARQUIVO
  $obUpload->generateNewName();

  //MOVE OS ARQUIVOS DE UPLOAD
  $sucesso = $obUpload->upload(__DIR__.'/files',false);

  if($sucesso){
    echo 'Arquivo <strong>'.$obUpload->getBasename().'</strong> enviado co sucesso!';
  }else{
    die('Problemas ao enviar o arquivo!');
  }
}

include __DIR__.'/includes/exemplo-de-upload.html';
