<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class PageLogin{


  /**
   * Método responsável por renderizar o topo da nossa página genérica
   * @param string
   */
  public static function getHeader(){
    //return 'Olá Mundo';
    return View::render('page/headerlogin');
  }

  /**
   * Método responsável por renderizar o rodapé da nossa página genérica
   * @param string
   */
  public static function getFooter(){
    //return 'Olá Mundo';
    return View::render('page/footerlogin');
  }

  /**
   * Método responsável por retornar o conteúdo (view) da estrutura genérica de página do painel
   * @param string $title
   * @param string $content
   * @return string
   */
  public static function getPage($title,$content,$currentModule,$currentDepartamento,$currentPerfil){

    return View::render('admin/pagelogin',[
      'titulo' => $title,
      'header' => self::getHeader(),
      'content' => $content,
      'footer' => self::getFooter()
    ]);
  }
}
