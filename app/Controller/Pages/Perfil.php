<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Perfil as EntityPerfil;
use \App\Db\Pagination;

class Perfil extends Page{

  /**
   * Método responsável por obter a renderização dos itens de Perfils para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getPerfilItens($request,&$obPagination){
    $itens = '';

    //QUANTIDADE TOTAL DE REGISTROS
    $qtTotal = EntityPerfil::getPerfis(null,null,null,'COUNT(*) as qtd')->fetchObject()->qtd;

    //PÁGINA ATUAL
    $queryParams = $request->getQueryParams();

    $paginaAtual = $queryParams['pagina'] ?? 1;

    //INSTÂNCIA DE PAGINAÇÃO
    $obPagination = new Pagination($qtTotal,$paginaAtual,1);

    //RESULTADO DA PAGINA
    $results = EntityPerfil::getPerfis(null,'Perfil_id DESC',$obPagination->getLimit());

    //MONTA E RENDERIZA OS ITENS DE Perfil
    while($obPerfil = $results->fetchObject(EntityPerfil::class)){
      $itens .= View::render('pages/perfil/item',[
        'nome' => $obPerfil->perfil_nm,
        'descricao' => $obPerfil->perfil_des
      ]);
    }
    return $itens;
  }

  /**
   * Método responsável por montar a renderização do select de Perfils para o formulário
   * @param Request $request
   * @return string
   */
  public static function getPerfilItensSelect($request,$id){
    $itensSelect = '';
    $resultsSelect = EntityPerfil::getPerfis(null,'perfil_nm DESC');

    while($obPerfil = $resultsSelect->fetchObject(EntityPerfil::class)){
      $itensSelect .= View::render('pages/perfil/itemselect',[
        'idSelect' => $obPerfil->perfil_id,
        'selecionado' => ($id == $obPerfil->perfil_id) ? 'selected' : '',
        'siglaSelect' => $obPerfil->perfil_nm
      ]);
    }
    return $itensSelect;
  }

  /**
   * Método responsável por retornar o conteúdo (view) de Perfils
   * @param Request $request
   * @param string
   */
  public static function getPerfil($request){
    //RETORNA A VIEW DE PerfilS
    $content = View::render('pages/perfil',[
      'itens' => self::getPerfilItens($request,$obPagination),
      'pagination' => parent::getPagination($request,$obPagination),
      'itemselect' => self::getPerfilItensSelect($request)
    ]);
    //RETORNA A VIEW DA PÁGINA
    return parent::getPage('Perfil - ',$content);
  }

  /**
   * Método responsável por cadastrar um Perfils
   * @param Request $request
   * @return string
   */
  public static function insertPerfil($request){
    //DADOS DO POST
    $posVars = $request->getPostVars();

    //NOVA ISNTANCIA DE Perfil
    $obPerfil = new EntityPerfil;
    $obPerfil->Perfil_nm = $posVars['nome'];
    $obPerfil->Perfil_des = $posVars['descricao'];
    $obPerfil->cadastrar();

    //RETORNA A PAGINA DE LISTAGEM DE PerfilS
    return self::getPerfil($request);
  }

}
