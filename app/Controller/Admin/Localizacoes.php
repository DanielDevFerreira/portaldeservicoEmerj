<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Localizacao as EntityLocalizacao;
use \App\Model\Entity\Departamento as EntityDepartamento;
use \App\Controller\Admin\Servicos as AdminServico;
use \App\Controller\Admin\Itensconfs as AdminItensconfs;
use \App\Controller\Admin\Departamentos as AdminDepartamentos;
use \App\Db\Pagination;

const DIR_LOCALIZACAO = 'localizacao';
const ROTA_LOCALIZACAO = 'localizacoes';
const ICON_LOCALIZACAO = 'geo-alt-fill';
const TITLE_LOCALIZACAO = 'Localizações';
const TITLELOW_LOCALIZACAO = 'a localização';

class Localizacoes extends Page{

  /**
   * Método responsável pela renderização da view de listagem de Localizacões
   * @param Request $request
   * @return string
   */
    public static function getListLocalizacoes($request,$errorMessage = null){

      $id = '';
      $status = '';
      $currentDepartamento = $_SESSION['admin']['usuario']['departamento'];
      $currentPerfil = $_SESSION['admin']['usuario']['id_perfil'];

      //STATUS
      if(!isset($currentDepartamento)) return $permissao = false;

     //MENSAGENS DE STATUS
     switch ($currentDepartamento) {
       case 'EMERJ':
         $permissao = true;
         break;
       case 'DETEC':
         $permissao = true;
         break;
     }
     //STATUS
     if(!isset($currentPerfil)) return $permissao = false;

    //MENSAGENS DE STATUS
    switch ($currentPerfil) {
      case 1:
        $permissao = true;
        break;
      case 2:
        $permissao = true;
        break;
    }

      if (!$permissao) {
        $request->getRouter()->redirect('/?status=sempermissao');
      }

      $departamentoSelecionado = AdminDepartamentos::getDepartamentoItensSelect($request,$id);

      //CONTEÚDO DA HOME
      $content = View::render('admin/modules/'.DIR_LOCALIZACAO.'/index',[
        'icon' => ICON_LOCALIZACAO,
        'title' =>TITLE_LOCALIZACAO,
        'titlelow' => TITLELOW_LOCALIZACAO,
        'direntity' => ROTA_LOCALIZACAO,
        'itens' => self::getLocalizacaoItens($request,$obPagination),
        'optionsDepartamento' => $departamentoSelecionado,
        'status' => $status
      ]);
      //RETORNA A PÁGINA COMPLETA
      return parent::getPanel(TITLE_LOCALIZACAO.' - EMERJ',$content,ROTA_LOCALIZACAO,$currentDepartamento,$currentPerfil);
  }

  /**
   * Método responsável por obter a renderização das Localizações para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getLocalizacaoItens($request,&$obPagination){

    $itens = '';

    //RESONSÁVEL PELA RENDERIZAÇÃO DOS MODAIS DE EDIÇÃO NA PÁGINA DE LISTAGEM DE LOCALIZAÇÕES
   $strEditModal = View::render('admin/modules/'.DIR_LOCALIZACAO.'/editmodal',[]);
   $strAddModal = View::render('admin/modules/'.DIR_LOCALIZACAO.'/addmodal',[]);
   $strAtivaModal = View::render('admin/modules/'.DIR_LOCALIZACAO.'/ativamodal',[]);
   $strDeleteModal = View::render('admin/modules/'.DIR_LOCALIZACAO.'/deletemodal',[]);

    $results = EntityLocalizacao::getLocalizacoes();

    //MONTA E RENDERIZA OS ITENS DE Localizacao
    while($obLocalizacao = $results->fetchObject(EntityLocalizacao::class)){
      $itens .= View::render('admin/modules/'.DIR_LOCALIZACAO.'/item',[
        'id' => $obLocalizacao->localizacao_id,
        'nome' => $obLocalizacao->localizacao_nm,
        'descricao' => $obLocalizacao->localizacao_des,
        'id_departamento' => $obLocalizacao->id_departamento,
        'departamento_sigla' => EntityDepartamento::getDepartamentoPorId($obLocalizacao->id_departamento)->departamento_sg,
        'departamento' => EntityDepartamento::getDepartamentoPorId($obLocalizacao->id_departamento)->departamento_nm,
        'texto_ativo' => ('s' == $obLocalizacao->ativo_fl) ? 'Desativar' : 'Ativar',
        'class_ativo' => ('s' == $obLocalizacao->ativo_fl) ? 'btn-warning' : 'btn-success',
        'style_ativo' => ('s' == $obLocalizacao->ativo_fl) ? 'table-active' : 'table-danger',
        'icon' => ICON_LOCALIZACAO,
        'title' =>TITLE_LOCALIZACAO,
        'titlelow' => TITLELOW_LOCALIZACAO,
        'direntity' => ROTA_LOCALIZACAO
      ]);
    }
    $itens .= $strDeleteModal;
    $itens .= $strAtivaModal;
    $itens .= $strEditModal;
    $itens .= $strAddModal;
    return $itens;
  }

  /**
   * Método responsável por montar a renderização do select de Localizações para o formulário
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getLocalizacaoItensSelect($request,$id){
    $itensSelect = '';
    $resultsSelect = EntityLocalizacao::getLocalizacoes(null,'localizacao_nm ASC');

    while($obLocalizacao = $resultsSelect->fetchObject(EntityLocalizacao::class)){
      $itensSelect .= View::render('admin/modules/localizacao/itemselect',[
        'idSelect' => $obLocalizacao->localizacao_id,
        'selecionado' => ($id == $obLocalizacao->localizacao_id) ? 'selected' : '',
        'siglaSelect' => $obLocalizacao->localizacao_nm
      ]);
    }
    return $itensSelect;
  }


   /**
    * Método responsável por cadastro de uma nova Localização no banco
    * @param Request $request
    * @return string
    */
    public static function setNovaLocalizacao($request){

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
      $departamento_id = filter_input(INPUT_POST, 'departamento', FILTER_SANITIZE_NUMBER_INT) ?? 0;


      $where = " id_departamento = ".$departamento_id." AND localizacao_nm = '".$nome."'";

      //echo "<pre>"; print_r($where); echo "<pre>"; exit;
      //VERIFICA SE JÁ EXISTE O IC com mesmo nome e tipo CADASTRADO NO BANCO
      $obLocalizacaoVer = EntityLocalizacao::getLocalizacoes($where)->fetchColumn();

      if($obLocalizacaoVer > 0){
        //REDIRECIONA SE O E-MAIL JÁ FOR CADASTRADO NO BANCO
        $request->getRouter()->redirect('/admin/'.ROTA_LOCALIZACAO.'?status=duplicado&nm='.$where.'&acao=alter');
      }


      //NOVA ISNTANCIA DE DEPARTAMENTO
      $obLocalizacao = new EntityLocalizacao;

      $obLocalizacao->localizacao_nm = $nome;
      $obLocalizacao->localizacao_des = $descricao;
      $obLocalizacao->id_departamento = $departamento_id;
      $obLocalizacao->cadastrar();

      //REDIRECIONA O DEPARTAMENTO
      $request->getRouter()->redirect('/admin/localizacoes?status=gravado&nm='.$nome.'&acao=alter');
    }

     /**
      * Método responsável por gravar a edição de uma Localização
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function setEditLocalizacao($request,$id){

        //DADOS DO POST
        $posVars = $request->getPostVars();
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);;
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);;
        $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
        $departamento_id = filter_input(INPUT_POST, 'departamento', FILTER_SANITIZE_NUMBER_INT) ?? 0;

        //echo "<pre>"; print_r($id); echo "<pre>"; exit;

        //OBTÉM O USUÁRIO DO BANCO DE DADOS
        $obLocalizacao = EntityLocalizacao::getLocalizacaoPorId($id);

        //echo "<pre>"; print_r($departamento_id); echo "<pre>";

        if(!$obLocalizacao instanceof EntityLocalizacao){

          //echo "<pre>"; print_r($id); echo "<pre>"; exit;
          $request->getRouter()->redirect('/admin/localizacoes?status=updatefail');
        }

        //ATUALIZA A INSTANCIA
        $obLocalizacao->localizacao_nm = $nome;
        $obLocalizacao->localizacao_des = $descricao;
        $obLocalizacao->id_departamento = $departamento_id;
        $obLocalizacao->atualizar();

        //REDIRECIONA O USUÁRIO PARA A PAGINA INICIAL DE LISTAR LOCALIZAÇÕES
        $request->getRouter()->redirect('/admin/localizacoes?status=alterado&nm='.$nome);
      }

      /**
       * Método responsável por retornar o formulário de alteração de status de um usuário
       * @param Request $request
       * @param integer $id
       * @return string
       */
       public static function getAltStatusLocalizacao($request,$id){

         //OBTÉM O USUÁRIO DO BANCO DE DADOS
         $obLocalizacao = EntityLocalizacao::getLocalizacaoPorId($id);
         $strNome = $obLocalizacao->localizacao_nm;

         if(!$obLocalizacao instanceof EntityLocalizacao){
           $request->getRouter()->redirect('/admin/localizacoes?status=updatefail');
         }

         //OBTÉM O USUÁRIO DO BANCO DE DADOS
         if($obLocalizacao->ativo_fl == 's'){
           $altStatus = 'n';
           $strMsn = ' DESATIVADO ';
         } elseif (($obLocalizacao->ativo_fl == 'n') || ($obLocalizacao->ativo_fl == '')) {
           $strMsn = ' ATIVADO ';
           $altStatus = 's';
         }

         //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
         $obLocalizacao->ativo_fl = $altStatus;
         $obLocalizacao->atualizar();

         //REDIRECIONA O USUÁRIO
         $request->getRouter()->redirect('/admin/localizacoes?status=statusupdate&nm='.$strNome.$strMsn);

       }

     /**
        * Método responsável por retornar o formulário de exclusão de um usuário atraves de um Modal
        * @param Request $request
        * @param integer $id
        * @return string
        */
        public static function getDeleteLocalizacao($request,$id){

         //OBTÉM O LOCALIZAÇÃO DO BANCO DE DADOS
          $obLocalizacao = EntityLocalizacao::getLocalizacaoPorId($id);
          $strNome = $obLocalizacao->localizacao_nm;

          if(!$obLocalizacao instanceof EntityLocalizacao){
            $request->getRouter()->redirect('/admin/localizacoes');
          }

         //EXCLUI O USUÁRIO
          $obLocalizacao->excluir();
          //REDIRECIONA O USUÁRIO
          $request->getRouter()->redirect('/admin/localizacoes?status=deletado&nm='.$strNome.$strMsn.'&acao=alter');
        }


  /**
   * Método responsável por retornar a mensagem de status
   * @param Request $request
   * @return string
   */
  private static function getStatus($request){
    //QUERY PARAMS
    $queryParams = $request->getQueryParams();
    $nm = filter_input(INPUT_GET, 'nm', FILTER_SANITIZE_STRING) ?? '';
    $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);

    //STATUS
    if(!isset($status)) return '';

   //MENSAGENS DE STATUS
   switch ($status) {
     case 'gravado':
       return Alert::getSuccess('Dados d'.TITLELOW_LOCALIZACAO.' <strong>'.$nm.'</strong> cadastrados com sucesso!');
       // code...
       break;
     case 'alterado':
       return Alert::getSuccess('Dados d'.TITLELOW_LOCALIZACAO.' <strong>'.$nm.'</strong> alterados com sucesso!');
       // code...
       break;
     case 'deletado':
       return Alert::getSuccess('Registro d'.TITLE_LOCALIZACAO.'  <strong>'.$nm.'</strong> deletado com sucesso!');
       // code...
       break;
     case 'duplicado':
       return Alert::getError('Já existe '.TITLELOW_LOCALIZACAO.' com este nome (<strong>'.$nm.'</strong>) para outr'.TITLELOW_LOCALIZACAO.'!');
       // code...
       break;
     case 'statusupdate':
       return Alert::getSuccess('Status d'.TITLELOW_LOCALIZACAO.' <strong>'.$nm.'</strong> com sucesso!');
       // code...
       break;
   }
  }
}
