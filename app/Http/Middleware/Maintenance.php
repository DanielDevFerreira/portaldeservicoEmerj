<?php

namespace App\Http\Middleware;

class Maintenance{
  /**
   * Método responsável por executar o Middlweare
   * @param Request $request
   * @param Closure $next
   * @param Response
  */
  public function handle($request, $next){
    //VERIFICA O ESTADO DE MANUTENÇÃO DE PÁGINA
    if(getenv('MAINTENANCE') == 'true'){
      throw new \Exception("Página em manutenção. Tente novamente mais tarde.", 200);

    }
      //EXECUTA O PRÓXIMO NÍVEL DO MIDDLEWARE 
      return $next($request);
      //echo "<pre>";    print_r($request);    echo "</pre>"; exit;
  }
}
