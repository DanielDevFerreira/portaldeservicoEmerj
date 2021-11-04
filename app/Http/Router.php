<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;
use \App\Http\Middleware\Queue as MiddlewareQueue;

class Router{


  /**
  * URL completa do projeto (raiz)
  * @var string
  */
  private $url = '';

  /**
  * Prefixo de todas as rotas
  * @var string
  */
  private $prefix = '';

  /**
  * Índice de rotas
  * @var array
  */
  private $route = [];

  /**
  * Índice de rotas
  * @var array
  */
  private $matches = [];

  /**
  * Uma instancia de request
  * @var Request
  */
  private $request;

  /**
  * Método responsável por inicar a classes
  * @param string $url
  */
  public function __construct($url){
    $this->request = new Request($this);
    $this->url = $url;
    $this->setPrefix();
  }

  /**
  * Método responsável por definir o prefixo das rotas
  */
  private function setPrefix(){
    //INFORMAÇÔES DA URL ATUAL
    $parseUrl = parse_url($this->url);

    //DEFINE O PREFIXO
    $this->prefix = $parseUrl['path'] ?? '';
  }

  /**
  * Método responsável por adicionar uma rota na classe
  * @param string $method
  * @param string $route
  * @param array $params
  */
  private function addRoute($method,$route,$params = []){
    //VALIDAÇÃO DOS PARAMETROS
    foreach($params as $key=>$value) {
      if($value instanceof Closure){
        $params['controller'] = $value;
        unset($params[$key]);
        continue;
        }
      }

      //MIDDLEWARES DA ROTA
      $params['middlewares'] = $params['middlewares'] ?? [];

      //echo "<pre>";    print_r($params);    echo "</pre>"; exit;

      //VARIÁVEIS DA ROTA
      $params['variables'] = [];

      //PADRÃO DE VALIDAÇÃO DAS VARIÁVEIS DAS ROTAS
      $paternVariable = '/{(.*?)}/';
      if(preg_match_all($paternVariable,$route,$matches)){
        $route = preg_replace($paternVariable,'(.*?)',$route);
        $params['variables'] = $matches[1];
      }

      //PADRAO DE VALIDAÇÃO DA URL***********************************************************
      //$route = rtrim($route,'/');

      //PADRAO DE VALIDAÇÃO DA URL
      $patternRoute = '/^'.str_replace('/','\/',$route).'$/';

      //ADICIONA A ROTA DENTRO DA CLASSE
      $this->routes[$patternRoute][$method] = $params;
    }

    /**
    * Método responsável por definir uma rota GET
    * @param string $router
    * @param array $params
    */
    public function get($route,$params = []){
      return $this->addRoute('GET',$route,$params);
    }

    /**
    * Método responsável por definir uma rota POST
    * @var string $route
    * @var array $params
    */
    public function post($route,$params = []){
      return $this->addRoute('POST',$route,$params);
    }

    /**
    * Método responsável por definir uma rota PUT
    * @var string $route
    * @var array $params
    */
    public function put($route,$params = []){
      return $this->addRoute('PUT',$route,$params);
    }

    /**
    * Método responsável por definir uma rota DELETE
    * @var string $route
    * @var array $params
    */
    public function delete($route,$params = []){
      return $this->addRoute('DELETE',$route,$params);
    }

    /**
    * Método responsável por retornar a URI desconsiderando o prefixo
    * @return string
    */
    private function getUri(){

      //URI DA REQUEST
      $uri = $this->request->getUri();

      //FATIA A URI COM PRFIXO
      $xUri = strlen($this->prefix) ? explode($this->prefix,$uri) : [$uri];
      //RETORNA A URI SEM PREFIXO
      return end($xUri);
    }
    /**
    * Método responsável por retornar os dados da rota atual
    * @return array
    */
    private function getRoute(){
      //URI
      $uri = $this->getUri();

      //METHOD
      $httpMethod = $this->request->getHttpMethod();

      //VALIDA AS ROTAS
      foreach ($this->routes as $patternRoute=>$methods) {
        //VERIFICA SE A URI BATE COM O PADRAO
        if(preg_match($patternRoute,$uri,$matches)){
          //VALIDA A PERMISSÃO DO MÉTODO
          if(isset($methods[$httpMethod])){
            //REMOVE A PRIMEIRA POSIÇÃO
            unset($matches[0]);

            //VARIAVEIS PROCESSADAS
            $keys = $methods[$httpMethod]['variables'];
            $methods[$httpMethod]['variables'] = array_combine($keys,$matches);
            $methods[$httpMethod]['variables']['request'] = $this->request;

            //RETORNO DOS PARAMETROS DA ROTA
            return $methods[$httpMethod];
          }
          //MÉTODO NÃO PERMITIDO/DEFINIDO
          throw new \Exception("Método não permitido.", 405);
        }
      }
      //URL NÃO ENCONTRADA
      throw new \Exception("URL não encontrada.", 404);
    }

    /**
    * Método responsável por definir uma rota de GET
    * @return Response
    */
    public function run(){
      try{
        //OBTEM A ROTA ATUAL
        $route = $this->getRoute();

        //VERIFICA O CONTROLADOR
        if(!isset($route['controller'])){
          throw new Exception("A URL não pôde ser processada.", 500);
        }
        //ARGUMENTOS DA FUNÇÃO
        $args = [];

        //RETORNA A EXECUÇÃO DA FUNÇÃO
        //return call_user_func_array($route['controller'],$args);

        //REFLECTION
        $reflection = new ReflectionFunction($route['controller']);
        foreach ($reflection->getParameters() as $parameter) {
          $name = $parameter->getName();
          $args[$name] = $route['variables'][$name] ?? '';
        }

        //echo "<pre>";    print_r($route);    echo "</pre>"; exit;
        //RETORNA A EXECUÇÃO DA FILA DA MIDDLEWARES
        return (new MiddlewareQueue($route['middlewares'],$route['controller'],$args))->next($this->request);
      }catch(Exception $e){
        return new Response($e->getCode(),$e->getMessage());
      }
    }

    /**
    * Método responsável por retornar a url atual
    * @return string
    **/
    public function getCurrentUrl(){
      return $this->url.$this->getUri();

    }

    /**
    * Método responsável por redirecionar a url
    * @param string $route
    **/
    public function redirect($route){
      $url = $this->url.$route;

      //echo "<pre>";    print_r($url);    echo "</pre>"; exit;

      header('location: '.$url);
      exit;

    }
}
