<?php

namespace App\Http\Middleware;

use \App\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogin{
  /**
   * Método responsável por executar o Middlweare
   * @param Request $request
   * @param Closure $next
   * @param Response
  */
  public function handle($request, $next){
    //VERIFICA O ESTADO DE MANUTENÇÃO DE PÁGINA
    if(!SessionAdminLogin::isLogged()){
      $request->getRouter()->redirect('/admin/login');

    }
      //EXECUTA O PRÓXIMO NÍVEL DO MIDDLEWARE
      return $next($request);
      //die('Não está logado!');
      //echo "<pre>";    print_r($request);    echo "</pre>"; exit;
  }
}
