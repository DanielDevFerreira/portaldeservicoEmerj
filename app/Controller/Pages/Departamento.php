<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Departamento as EntityDepartamento;
use \App\Db\Pagination;

class Departamento extends Page{

  /**
   * Método responsável por obter a renderização dos itens de departamentos para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getDepartamentoItens($request,&$obPagination){
    $itens = '';

    //QUANTIDADE TOTAL DE REGISTROS
    $qtTotal = EntityDepartamento::getDepartamentos(null,null,null,'COUNT(*) as qtd')->fetchObject()->qtd;

    //PÁGINA ATUAL
    $queryParams = $request->getQueryParams();

    $paginaAtual = $queryParams['pagina'] ?? 1;

    //INSTÂNCIA DE PAGINAÇÃO
    $obPagination = new Pagination($qtTotal,$paginaAtual,1);

    //RESULTADO DA PAGINA
    $results = EntityDepartamento::getDepartamentos(null,'departamento_id DESC',$obPagination->getLimit());

    //MONTA E RENDERIZA OS ITENS DE DEPARTAMENTO
    while($obDepartamento = $results->fetchObject(EntityDepartamento::class)){
      $itens .= View::render('pages/departamento/item',[
        'nome' => $obDepartamento->departamento_nm,
        'sigla' => $obDepartamento->departamento_sg,
        'descricao' => $obDepartamento->departamento_des
      ]);
    }
    return $itens;
  }


  /**
   * Método responsável por montar a renderização do select de departamentos para o formulário
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getDepartamentoItensSelect($request,$id){
    $itensSelect = '';
    $resultsSelect = EntityDepartamento::getDepartamentos(null,'departamento_nm ASC');

    while($obDepartamento = $resultsSelect->fetchObject(EntityDepartamento::class)){
      $itensSelect .= View::render('pages/departamento/itemselect',[
        'idSelect' => $obDepartamento->departamento_id,
        'selecionado' => ($id == $obDepartamento->departamento_id) ? 'selected' : '',
        'siglaSelect' => $obDepartamento->departamento_nm
      ]);
    }
    return $itensSelect;
  }

  /**
   * Método responsável por retornar o conteúdo (view) de departamentos
   * @param Request $request
   * @param string
   */
  public static function getDepartamento($request){
    //RETORNA A VIEW DE DEPARTAMENTOS
    $content = View::render('pages/departamento',[
      'itens' => self::getDepartamentoItens($request,$obPagination),
      'pagination' => parent::getPagination($request,$obPagination),
      'itemselect' => self::getDepartamentoItensSelect($request)
    ]);
    //RETORNA A VIEW DA PÁGINA
    return parent::getPage('Departamento - ',$content);
  }


  /**
   * Método responsável por cadastrar um departamentos
   * @param Request $request
   * @return string
   */
  public static function insertDepartamento($request){
    //DADOS DO POST
    $posVars = $request->getPostVars();

    //NOVA ISNTANCIA DE DEPARTAMENTO
    $obDepartamento = new EntityDepartamento;
    $obDepartamento->departamento_nm = $posVars['nome'];
    $obDepartamento->departamento_sg = $posVars['sigla'];
    $obDepartamento->departamento_des = $posVars['descricao'];
    $obDepartamento->cod_dep_super = $posVars['cod_dep_super'];
    $obDepartamento->cadastrar();

    //RETORNA A PAGINA DE LISTAGEM DE DEPARTAMENTOS
    return self::getDepartamento($request);
  }

}
