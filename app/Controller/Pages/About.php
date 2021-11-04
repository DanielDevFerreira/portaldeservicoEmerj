<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class About extends Page{

  /**
   * Método responsável por retornar o conteúdo (view) da nossa pagina de sobre
   * @param string
   */
  public static function getAbout(){
    //RETORNA A VIEW DA HOME
    $content = View::render('pages/about',[
      'name' => 'SobrXXXXXXXXXXXXXXXXXXXxoisa',
      'descricao' => 'Descrição de página de sobre',
      'site' => 'Site: https://pt.stackoverflow.com/'
    ]);
    //echo "<pre>"; print_r($content); echo "<pre>"; exit;
    //RETORNA A VIEW DA PÁGINA
    return parent::getPage('ABAOUTTTTTTTTTT - ',$content);
  }

}
