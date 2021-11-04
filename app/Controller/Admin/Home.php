<?php

namespace App\Controller\Admin;

use \App\Utils\View;


class Home extends Page{

  /**
   * Método responsável pela renderização da view de Home do painel
   * @param Request $request
   * @return string
   */

  public static function getHome($request,$errorMessage = null){

    $currentDepartamento = $_SESSION['admin']['usuario']['departamento'];
    $currentPerfil = $_SESSION['admin']['usuario']['id_perfil'];

    //CONTEÚDO DA HOME
    $content = View::render('admin/modules/home/index',[
      'status' => self::getStatus($request)
    ]);

    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel('Home - EMERJ',$content,'home',$currentDepartamento,$currentPerfil);
  }
  /**
   * Método responsável por retornar a mensagem de status
   * @param Request $request
   * @return string
   */
  private static function getStatus($request){
    //QUERY PARAMS
    $queryParams = $request->getQueryParams();

    //STATUS
    if(!isset($queryParams['status'])) return '';

   //MENSAGENS DE STATUS
   switch ($queryParams['status']) {
     case 'gravado':
       return Alert::getSuccess('Usuário cadastrado com sucesso!');
       // code...
       break;
     case 'alterado':
       return Alert::getSuccess('Dados do usuário alterados com sucesso!');
       // code...
       break;
     case 'deletado':
       return Alert::getSuccess('Usuário deletado com sucesso!');
       // code...
       break;
     case 'duplicado':
       return Alert::getError('Já existe um usuário cadastrado com este e-mail!');
       // code...
       break;
     case 'sempermissao':
       return Alert::getError('Sem permissão para acessar está página!');
       // code...
       break;
     case 'senharesetada':
       return Alert::getSuccess('Senha do usuário resetada com sucesso!');
       // code...
       break;
     case 'statusupdate':
       return Alert::getSuccess('Status do usuário alterado com sucesso!');
       // code...
       break;
   }
  }
}
