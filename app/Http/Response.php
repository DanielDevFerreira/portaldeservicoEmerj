<?php

namespace App\Http;

class Response{

  /**
  * Código do Status HTTP
  * @var integer
  */
  private $httpCode;

  /**
  * Cabeçalho do Response
  * @var array
  */
  private $headers = [];

  /**
  * Tipo de conteúdo que está sendo retornado
  * @var string
  */
  private $contentType = 'text/html';

  /**
  * Conteúdo do response
  * @var mixed
  */
  private $content;

  /**
  * Metodo responsável por iniciar a classe e definir os valores
  * @param integer $httpCode
  * @param mixed $content
  * @param string $contentType
  */
  public function __construct($httpCode,$content,$contentType = 'text/html'){
    $this->httpCode = $httpCode;
    $this->content = $content;
    $this->setContentType($contentType);
  }

  /**
  * Metodo responsável por alterar o content type do response
  * @param string
  */
  public function setContentType($contentType){
    $this->contentType = $contentType;
    $this->addHeader('Content-Type',$contentType);
  }
  /**
  * Metodo responsável por acicionar um registro no cabeçalho de response
  * @param string $key
  * @param string $value
  */
  public function addHeader($key,$value){
    $this->headers[$key] = $value;
  }
  /**
  * Metodo responsável em enviar os headers para o navegador
  */
  private function sendHeaders(){
    //STATUS
    http_response_code($this->httpCode);
    //ENVIA HEADERS
    foreach($this->headers as $key=>$value){
      header($key.': '.$value);
    }
  }

  /**
  * Metodo responsável em enviar a resposta para o usuário
  */
  public function sendResponse(){
    //ENVIA OS HEADERS
    $this->sendHeaders();
    
    //INPRIME O CONTEÚDO
    switch ($this->contentType) {
      case 'text/html':
        echo $this->content;
      exit;
    }
  }
}
