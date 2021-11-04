<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Requisicao extends Page{

  /**
   * Método responsável por retornar o conteúdo (view) de departamentos
   * @param string
   */
  public static function getRequisicao(){
    //RETORNA A VIEW DA HOME
    $content = View::render('pages/requisicoes',[
      'name' => 'Sobre dSobre Sobre Sobre e Alguma Coisa',
      'descricao' => 'Descrição de página de sobre',
      'site' => 'Site: https://pt.stackoverflow.com/'
    ]);
    //echo "<pre>"; print_r($content); echo "<pre>"; exit;
    //RETORNA A VIEW DA PÁGINA
    return parent::getPage('Requisições - ',$content);
  }

}
