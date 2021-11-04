<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Cargo as EntityCargo;
use \App\Db\Pagination;

class Cargo extends Page{

  /**
   * Método responsável por obter a renderização dos itens de Cargos para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getCargoItens($request,&$obPagination){
    $itens = '';

    //QUANTIDADE TOTAL DE REGISTROS
    $qtTotal = EntityCargo::getCargos(null,null,null,'COUNT(*) as qtd')->fetchObject()->qtd;

    //PÁGINA ATUAL
    $queryParams = $request->getQueryParams();

    $paginaAtual = $queryParams['pagina'] ?? 1;

    //INSTÂNCIA DE PAGINAÇÃO
    $obPagination = new Pagination($qtTotal,$paginaAtual,1);

    //RESULTADO DA PAGINA
    $results = EntityCargo::getCargos(null,'Cargo_id DESC',$obPagination->getLimit());

    //MONTA E RENDERIZA OS ITENS DE Cargo
    while($obCargo = $results->fetchObject(EntityCargo::class)){
      $itens .= View::render('pages/cargo/item',[
        'nome' => $obCargo->cargo_nm,
        'descricao' => $obCargo->cargo_des
      ]);
    }
    return $itens;
  }

  /**
   * Método responsável por montar a renderização do select de Cargos para o formulário
   * @param Request $request
   * @return string
   */
  public static function getCargoItensSelect($request,$id){
    $itensSelect = '';
    $resultsSelect = EntityCargo::getCargos(null,'cargo_nm DESC');
/*
    echo "<pre>";
    print_r($resultsSelect);
    echo "</pre>";
    exit;]
*/

    while($obCargo = $resultsSelect->fetchObject(EntityCargo::class)){
      $itensSelect .= View::render('pages/cargo/itemselect',[
        'idSelect' => $obCargo->cargo_id,
        'selecionado' => ($id == $obCargo->cargo_id) ? 'selected' : '',
        'siglaSelect' => $obCargo->cargo_nm
      ]);
    }
    return $itensSelect;
  }

  /**
   * Método responsável por retornar o conteúdo (view) de Cargos
   * @param Request $request
   * @param string
   */
  public static function getCargo($request){
    //RETORNA A VIEW DE CargoS
    $content = View::render('pages/cargo',[
      'itens' => self::getCargoItens($request,$obPagination),
      'pagination' => parent::getPagination($request,$obPagination),
      'itemselect' => self::getCargoItensSelect($request)
    ]);
    //RETORNA A VIEW DA PÁGINA
    return parent::getPage('Cargo - ',$content);
  }

  /**
   * Método responsável por cadastrar um Cargos
   * @param Request $request
   * @return string
   */
  public static function insertCargo($request){
    //DADOS DO POST
    $posVars = $request->getPostVars();

    //NOVA ISNTANCIA DE Cargo
    $obCargo = new EntityCargo;
    $obCargo->Cargo_nm = $posVars['nome'];
    $obCargo->Cargo_des = $posVars['descricao'];
    $obCargo->cadastrar();

    //RETORNA A PAGINA DE LISTAGEM DE CargoS
    return self::getCargo($request);
  }

}
