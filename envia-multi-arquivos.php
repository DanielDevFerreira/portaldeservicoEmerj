<?php

require __DIR__.'/vendor/autoload.php';

use \App\File\Upload;

if(isset($_FILES['arquivo'])){

  //INSTÂNCIAS DO UPLOAD
  $uploads = Upload::createMultiUpload($_FILES['arquivo']);

  foreach ($uploads as $obUpload) {

    //ALTERA O NOME DO ARQUIVO
    //$obUpload->setName('novo-arquivo-de-nome-alterado');

    //GERA UM NOME ALEATÓRIO PARA O ARQUIVO
    $obUpload->generateNewName();

    //MOVE OS ARQUIVOS DE UPLOAD
    $sucesso = $obUpload->upload(__DIR__.'/files',false);

    if($sucesso){
      echo 'Arquivo <strong>'.$obUpload->getBasename().'</strong> enviado co sucesso!<br>';
      continue;
    }else{
      echo 'Problemas ao enviar o arquivo!<br>';
    }
    exit;
  }
}

include __DIR__.'/includes/exemplo-de-multi-upload.html';
