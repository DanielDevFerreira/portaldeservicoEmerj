<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Pagina as EntityPagina;
use \App\Model\Entity\Sessao as EntitySessao;

class Page{

  /**
   * Método responsável por renderizar o topo da nossa página genérica
   * @param string
   */
  public static function getHeader(){
    //return 'Olá Mundo';
    return View::render('pages/header');
  }

  /**
   * Método responsável por renderizar o rodapé da nossa página genérica
   * @param string
   */
  public static function getFooter(){
    //return 'Olá Mundo';
    return View::render('pages/footer');
  }

  /**
   * Módulos disponíveis no painel (AQUI FICA O TEXO DO MENU)
   * @var array $currentModule
   * @return string
   */
  private static function getModules() {

    $valida = '';

    //RESULTADO DA PAGINA
    $results = EntityPagina::getPaginas();

    //MONTA E RENDERIZA OS ITENS DE Pagina
    while($obPagina = $results->fetchObject(EntityPagina::class)){
      $valida = EntitySessao::getQuantidadeSessoesPorPagina($obPagina->pagina_id);
      $itens[$obPagina->pagina_nm] = [
          'label' => $obPagina->pagina_label ?? '',
          'link' => ($valida > 0) ? '#' : URL.'/'.$obPagina->pagina_nm,
          'dropdown' => ($valida > 0) ? 'dropdown' : '',
          'dropdown-toggle' => ($valida > 0) ? 'dropdown-toggle' : '',
          'navbarMenuLink' => ($valida > 0) ? 'navbarDropdownMenuLink_'.$obPagina->pagina_id : 'no_dropdown'.$obPagina->pagina_id,
          'data-toggle' => ($valida > 0) ? 'role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : ''
      ];
    }
    return $itens;
  }

  /**
   * Módulos disponíveis no painel (AQUI FICA O TEXO DO MENU)
   * @var array $currentModule
   * @return string
   */
  private static function getDropdownmodules() {

    //RESULTADO DA PAGINA
    $results = EntitySessao::getSessoes();

    //MONTA E RENDERIZA OS ITENS DE Pagina
    while($obSessao = $results->fetchObject(EntitySessao::class)){
      $itens[$obSessao->sessao_id] = [
          'dropdownlabel' => $obSessao->sessao_nm ?? '',
          //'dropdownlink' => URL.'/pages/'.EntityPagina::getPaginaPorId($obSessao->id_pagina)->pagina_nm.'/'.$obSessao->sessao_id ?? '',
          'dropdownlink' => URL.'/pagina/'.$obSessao->id_pagina.'/'.$obSessao->sessao_id ?? '',
          'navbarDropdownMenuLink' => 'navbarDropdownMenuLink_'.$obSessao->id_pagina ?? ''
      ];
    }
    return $itens;
  }


  /**
   * Método responsável por retornar o conteúdo (view) da estrutura genérica de página do painel
   * @param string $title
   * @param string $content
   * @return string
   */
  public static function getPage($title,$content,$currentModule){

    return View::render('pages/page',[
      'titulo' => $title,
      'header' => self::getHeader(),
      'content' => $content,
      'footer' => self::getFooter(),
      'menu' => self::getMenu($currentModule)
    ]);
  }


  /**
   * Método responsável por retornar o conteúdo (view) da estrutura genérica de página do painel
   * @param string $title
   * @param string $content
   * @return string
   */
  public static function getPrincipal($title,$content,$currentModule){

    return View::render('pages/principal',[
      'titulo' => $title,
      'header' => self::getHeader(),
      'content' => $content,
      'footer' => self::getFooter(),
      'menu' => self::getMenu($currentModule)
    ]);
  }


  /**
   * Método responsável por renderizar a view do painel com coteúdo dinâmico
   * @param string $currentModule
   * @return string
   */
  private static function getMenudropdown($currentModule,$navbarMenuLink){
    //LINKS DO menu
    $dropdownlinks = '';

    //ITERA OS MÓDULOS
    foreach (self::getDropdownmodules() as $hash=>$dropdownmodule) {
      if ($navbarMenuLink == $dropdownmodule['navbarDropdownMenuLink']) {
      $dropdownlinks .= View::render('pages/menu/dropdownitem',[
        'dropdownlabel' => $dropdownmodule['dropdownlabel'],
        'dropdownlink' => $dropdownmodule['dropdownlink']
      ]);
    }
      // code...
    }
    //RENDERIZA A RENDERIZAÇÃO DO DROPDOW
    return View::render('pages/menu/dropdownbox',[
      'navbarDropdownMenuLink' => $navbarMenuLink,
      'dropdownitens' => $dropdownlinks
    ]);
  }

  /**
   * Método responsável por renderizar a view do painel com coteúdo dinâmico
   * @param string $currentModule
   * @return string
   */
  private static function getMenu($currentModule){
    //LINKS DO menu
    $links = '';

    $mystring = self::getModules();

    //$mystring = is_array($mystring) ? $mystring : array();

    //ITERA OS MÓDULOS
    foreach ($mystring as $hash=>$module) {
      $links .= View::render('pages/menu/link',[
        'label' => $module['label'],
        'link' => $module['link'],
        'current' => $hash == $currentModule ? 'texto-nav-link-ativo' : '',
        'dropdown'=> $module['dropdown'],
        'dropdown-toggle'=> $module['dropdown-toggle'],
        'navbarMenuLink'=> $module['navbarMenuLink'],
        'dropdownbox' => ($module['dropdown'] == 'dropdown') ? self::getMenudropdown($currentModule,$module['navbarMenuLink']) : '',
        'data-toggle' => $module['data-toggle']
      ]);
      // code...
    }
    //RENDERIZA A RENDERIZAÇÃO DO MENU
    return View::render('pages/menu/box',[
      'links' => $links
    ]);
  }
}
