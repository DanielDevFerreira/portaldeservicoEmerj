<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Home extends Page{

  /**
   * Método responsável por retornar o conteúdo (view) da nossa home
   * @param string
   */
  public static function getHome(){

    $content = View::render('pages/',[
      'name' => 'Sobre dSobre Sobre Sobre e Alguma Coisa',
      'descricao' => 'Descrição de página de sobre',
      'site' => 'Site: https://emerj.com.br/'
    ]);
    //RETORNA A VIEW DA PÁGINA
    return parent::getPage('EMERJ - Sistema de controle de demandas',$content);
  }

}
