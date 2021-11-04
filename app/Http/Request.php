<?php

namespace App\Http;

class Request{

  /**
  * Instância do Router
  * @var Router
  */
  private $router;

  /**
  * Método HTTP da requisição
  * @var string
  */
  private $httpMethod;

  /**
  * URI da página
  * @var string
  */
  private $uri;

  /**
  * Parametros da URL ($_GET)
  * @var array
  */
  private $queryParams = [];

  /**
  * Variáveis recebidas no POST de página ($_POST)
  * @var array
  */
  private $postVars = [];

  /**
  * Cabeçalho da requisição
  * @var array
  */
  private $headers = [];

  /**
  * Construtor da classe
  */
  public function __construct($router){
    $this->router = $router;
    $this->queryParams = $_GET ?? [];
    $this->postVars = $_POST ?? [];
    $this->headers = getallheaders();
    $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
    $this->setUri();

  }

  /**
  * Metodo responsável por definir a URI
  */
  private function setUri(){
    //URI COMPLETA (COM GETS)
    $this->uri = $_SERVER['REQUEST_URI'] ?? '';
    //REMOVE GETS DA URI
    $xURI = explode('?',$this->uri);
    $this->uri = $xURI[0];

  }

  /**
  * Metodo responsável por retornar a instância de Router
  * @return Router
  */
  public function getRouter(){
    return $this->router;
  }

  /**
  * Metodo responsável por retornar o método HTTP da requisição
  * @return string
  */
  public function getHttpMethod(){
    return $this->httpMethod;
  }

  /**
  * Metodo responsável por retornar a URI da requisição
  * @return string
  */
  public function getUri(){
    return $this->uri;
  }

  /**
  * Metodo responsável por retornar os headers da requisição
  * @return array
  */
  public function getHeaders(){
    return $this->headers;
  }

  /**
  * Metodo responsável por retornar os parâmetro da URL da requisição
  * @return array
  */
  public function getQueryParams(){
    return $this->queryParams;
  }

  /**
  * Metodo responsável por retornar as variáveis POST da requisição
  * @return array
  */
  public function getPostVars(){
    return $this->postVars;
  }

}
