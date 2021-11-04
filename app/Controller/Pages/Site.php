<?php

namespace App\Controller\Pages;

use \DateTime;

use \App\Utils\View;
use \App\Model\Entity\Pagina as EntityPagina;
use \App\Model\Entity\Sessao as EntitySessao;
use \App\Model\Entity\Subsessao as EntitySubsessao;
use \App\Model\Entity\Noticia as EntityNoticia;
use \App\Model\Entity\Evento as EntityEvento;
use \App\Controller\Admin\Paginas as AdminPaginas;
use \App\Controller\Admin\Sessoes as AdminSessoes;
use \App\Controller\Admin\Noticias as AdminNoticias;

class Site extends Page{

  /**
   * Método responsável por retornar o conteúdo (view) da nossa home
   * @param string
   */
  public static function getSite(){



    $content = View::render('/pages/home',[
      'titulo' => 'Título da Página Principal',
      'conteudo' => 'Conteúdo da página principal',
      'menu' => 'menulateral',
      'indicators' => self::getNoticiasIndicatorCarrousel() ?? '',
      'slider' => self::getNoticiasSliderCarrousel() ?? '',
      'calendario' => View::render('pages/calendario/index') ?? ''
    ]);
    //RETORNA A VIEW DA PÁGINA
    return parent::getPrincipal('EMERJ - Novo Site',$content,'ESCOLA');
  }

  /**
   * Método responsável por retornar o conteúdo (view) da nossa home
   * @param string
   */
  public static function getConteudoSubsessao($pagina,$sessao,$subsessao){

    $objPagina = EntityPagina::getPaginaPorId($pagina);
    $objSessao = EntitySessao::getSessaoPorId($sessao);
    $objSubSessao = EntitySubsessao::getSubsessaoPorId($subsessao);

    $content = View::render('/pages/site',[
      'titulo' => $objSubSessao->subsessao_titulo ?? '',
      'conteudo' => $objSubSessao->subsessao_conteudo ?? '',
      'menu' => 'menu'
    ]);
    //RETORNA A VIEW DA PÁGINA
    return parent::getPrincipal('EMERJ - Novo Site',$content,$objPagina->pagina_nm);
  }




  /**
   * Método responsável por retornar o conteúdo (view) da nossa home
   * @param string
   */
  public static function getConteudoSessao($pagina,$sessao){

    $objPagina = EntityPagina::getPaginaPorId($pagina);
    $objSessao = EntitySessao::getSessaoPorId($sessao);

    $content = View::render('/pages/site',[
      'titulo' => $objSessao->sessao_titulo ?? '',
      'conteudo' => $objSessao->sessao_conteudo ?? '',
      'menu' => self::getMenuSessao($pagina,$sessao)
    ]);
    //RETORNA A VIEW DA PÁGINA
    return parent::getPrincipal('EMERJ - Novo Site',$content,$objPagina->pagina_nm);
  }


  /**
   * Método responsável por retornar o conteúdo (view) da nossa home
   * @param string
   */
  public static function getMenuSessao($pagina,$sessao){

    $itens = '';

    $objPagina = EntityPagina::getPaginaPorId($pagina);
    $where = ' id_pagina = '.$pagina;
    $results = EntitySessao::getSessoes($where,'sessao_nm asc');

    //MONTA E RENDERIZA OS ITENS DE Noticia
    while($obSessao = $results->fetchObject(EntitySessao::class)){
      $itens .= View::render('pages/menu/menusessao',[
       'pagina' => $obSessao->id_pagina,
       'current' => $obSessao->sessao_id == $sessao ? 'texto-menu-lateral-ativo' : '',
       'sessao_id' => $obSessao->sessao_id,
       'sessao_nm' => $obSessao->sessao_nm,
     ]);
   }
   return $itens;
 }

  /**
   * Método responsável por retornar o conteúdo (view) da nossa home
   * @param string
   */
  public static function getNoticiaDetalhe($request,$id){

    $objNoticia = EntityNoticia::getNoticiaPorId($id);

    $content = View::render('/pages/noticia/detalhe',[
      'id' => $objNoticia->noticia_id ?? '',
      'noticia_titulo' => $objNoticia->noticia_titulo ?? '',
      'noticia_img' => $objNoticia->noticia_img,
      'noticia_imgalt' => $objNoticia->noticia_imgalt,
      'noticia_imgtittle' => $objNoticia->noticia_imgtittle,
      'noticia_conteudo' => $objNoticia->noticia_descricao ?? ''
    ]);
    //RETORNA A VIEW DA PÁGINA
    return parent::getPrincipal('EMERJ - Novo Site',$content,'noticias');
  }


  /**
   * Método responsável por obter a renderização das Conteúdos do Noticia para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
   public static function getNoticiasIndicatorCarrousel(){

     $itens = '';
     $indicadorini = 0;
     $indicador = 1;

    //RESULTADO DA PAGINA
    $results = EntityNoticia::getNoticias(null,'noticia_id asc', 4);
    //MONTA E RENDERIZA OS ITENS DE Noticia
    while($objNoticia = $results->fetchObject(EntityNoticia::class)){
    $itens .= View::render('pages/carousel/indicators',[
    // 'classe' => $indicadorini == 0 ? 'class="active" aria-current="true"' : '',
    'classe' => $indicadorini == 0 ? 'active' : '',
    'indicadorini' => $indicadorini++,
    'indicador' => $indicador++
    ]);
    
    
   }
   return $itens;
 }


  /**
   * Método responsável por obter a renderização das Conteúdos do Noticia para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
   public static function getNoticiasSliderCarrousel(){

     $itens = '';

    //RESULTADO DA PAGINA
    $results = EntityNoticia::getNoticias(null,'noticia_id asc', 4);

    //MONTA E RENDERIZA OS ITENS DE Noticia
    while($objNoticia = $results->fetchObject(EntityNoticia::class)){
      $itens .= View::render('pages/carousel/slider',[
       'id' => $objNoticia->noticia_id,
       'noticia_titulo' => $objNoticia->noticia_titulo,
       'noticia_img' => $objNoticia->noticia_img,
       'active' => ($objNoticia->noticia_id == 1) ? 'active' : '',
       'noticia_imgalt' => $objNoticia->noticia_imgalt,
       'noticia_imgtittle' => $objNoticia->noticia_imgtittle
     ]);
   }
   return $itens;
 }

  /**
   * Método responsável por obter a renderização das Conteúdos do Noticia para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
   public static function getNoticiasCapa($request,$obPagination){

     $itens = '';

    //RESULTADO DA PAGINA
    $results = EntityNoticia::getNoticias(null,'data_inicio desc');

    //MONTA E RENDERIZA OS ITENS DE Noticia
    while($obNoticia = $results->fetchObject(EntityNoticia::class)){
      $itens .= View::render('pages/noticia/item',[
       'id' => $obNoticia->noticia_id,
       'noticia_nm' => $obNoticia->noticia_nm,
       'noticia_titulo' => $obNoticia->noticia_titulo,
       'noticia_img' => $obNoticia->noticia_img,
       'noticia_imgalt' => $obNoticia->noticia_imgalt,
       'noticia_imgtittle' => $obNoticia->noticia_imgtittle,
       'noticia_data' => date('d/m/Y', strtotime($obNoticia->data_inicio)),
       'noticia_textocapa' => self::limitCharacter($obNoticia->noticia_descricao,'\S',1,200).' [...]' ?? '',
     ]);
   }
   //CONTEÚDO DA HOME
   $content = View::render('pages/site',[
     'titulo' => 'Notícias',
     'conteudo' => $itens,
     'menu' => 'menu'
   ]);
   //RETORNA A PÁGINA COMPLETA
   return parent::getPrincipal('EMERJ - Novo Site',$content,'noticias');
 }


  // FUNÇÃO PARA LIMITAR A QUANTIDADE DE CARACTERES ATE O PRÓXIMO ESPAÇO
  public static function limitCharacter($string,$srtValor,$ini,$tam){

    $regex = '/.{'.$ini.','.$tam.'}('.$srtValor.'*|$)/';

    preg_match_all($regex, $string, $matches);

    $result = array_shift($matches[0]);

    return $result;
  }

 /**
  * Método responsável por retornar o conteúdo (view) da nossa home
  * @param string
  */
 public static function getEventoDetalhe($request,$id){

   $objEvento = EntityEvento::getEventoPorId($id);

   $content = View::render('/pages/evento/detalhe',[
     'codigo' => $objEvento->codigo ?? '',
     'nome' => $objEvento->nome ?? '',
     'local' => $objEvento->local ?? ''
   ]);
   //RETORNA A VIEW DA PÁGINA
   return parent::getPrincipal('EMERJ - Novo Site',$content,'eventos');
 }

 /**
  * Método responsável por obter a renderização das Conteúdos do Noticia para a página
  * @param Request $request
  * @param Pagination $obPagination
  * @return string
  */
  public static function getEventosCapa($request,$obPagination){

    $itens = '';

   //RESULTADO DA PAGINA
   $results = EntityEvento::getEventos(null,'codigo desc',40);

   //MONTA E RENDERIZA OS ITENS DE Noticia
   while($objEvento = $results->fetchObject(EntityEvento::class)){
     $itens .= View::render('pages/evento/item',[
       'codigo' => $objEvento->codigo ?? '',
       'nome' => $objEvento->nome ?? '',
       'local' => $objEvento->local ?? ''
    ]);
  }
  //CONTEÚDO DA HOME
  $content = View::render('pages/site',[
    'titulo' => 'Eventos',
    'conteudo' => $itens,
    'menu' => 'menu'
  ]);
  //RETORNA A PÁGINA COMPLETA
  return parent::getPrincipal('EMERJ - Novo Site',$content,'eventos');
}


}
