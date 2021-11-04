<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Servico as EntityServico;
use \App\Model\Entity\Tipodeservico as EntityTipodeservico;
use \App\Model\Entity\Atendimento as EntityAtendimento;
use \App\Db\Pagination;
//use \App\Http\Request;

const DIR_TIPOSERV = 'tipodeservico';
const ROTA_TIPOSERV = 'tipodeservicos';
const ICON_TIPOSERV = 'bag-check';
const TITLE_TIPOSERV = 'Tipo de Serviços';
const TITLELOW_TIPOSERV = 'o tipo de serviço';

class Tipodeservicos extends Page{

  /**
   * Método responsável pela renderização da view de listagem de Tipos de Serviço
   * @param Request $request
   * @return string
   */
  public static function getListTipodeservicos($request,$errorMessage = null){

    $status = self::getStatus($request);
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

    //CONTEÚDO DA HOME
    $content = View::render('admin/modules/'.DIR_TIPOSERV.'/index',[
      'icon' => ICON_TIPOSERV,
      'title' =>TITLE_TIPOSERV,
      'titlelow' => TITLELOW_TIPOSERV,
      'direntity' => ROTA_TIPOSERV,
      'itens' => self::getTipodeservicoItens($request,$obPagination),
      'status' => $status
    ]);

    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel(TITLE_TIPOSERV.' - EMERJ',$content,ROTA_TIPOSERV,$currentDepartamento,$currentPerfil);

  }

  /**
   * Método responsável por obter a renderização dos itens de Tipos de Serviço para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getTipodeservicoItens($request,&$obPagination){

    $itens = '';

    //RESONSÁVEL PELA RENDERIZAÇÃO DO MODAL DE EDIÇÃO NA PÁGINA DE LISTAGEM DE LOCALIZAÇÕES
    $strEditModal = View::render('admin/modules/'.DIR_TIPOSERV.'/editmodal',[]);
    $strAddModal = View::render('admin/modules/'.DIR_TIPOSERV.'/addmodal',[]);
    $strAtivaModal = View::render('admin/modules/'.DIR_TIPOSERV.'/ativamodal',[]);
    $strDeleteModal = View::render('admin/modules/'.DIR_TIPOSERV.'/deletemodal',[]);

    //RESULTADO DA PAGINA
    $results = EntityTipodeservico::getTipodeservicos();

    //MONTA E RENDERIZA OS ITENS DE Tipodeservico
    while($obTipodeservico = $results->fetchObject(EntityTipodeservico::class)){
      $itens .= View::render('admin/modules/'.DIR_TIPOSERV.'/item',[
        'id' => $obTipodeservico->tipodeservico_id,
        'nome' => $obTipodeservico->tipodeservico_nm,
        'descricao' => $obTipodeservico->tipodeservico_des,
        'texto_ativo' => ('s' == $obTipodeservico->ativo_fl) ? 'Desativar' : 'Ativar',
        'class_ativo' => ('s' == $obTipodeservico->ativo_fl) ? 'btn-warning' : 'btn-success',
        'style_ativo' => ('s' == $obTipodeservico->ativo_fl) ? 'table-active' : 'table-danger',
        'icon' => ICON_TIPOSERV,
        'title' =>TITLE_TIPOSERV,
        'titlelow' => TITLELOW_TIPOSERV,
        'direntity' => ROTA_TIPOSERV
      ]);
    }
    $itens .= $strDeleteModal;
    $itens .= $strAtivaModal;
    $itens .= $strEditModal;
    $itens .= $strAddModal;
    return $itens;
  }

  /**
   * Método responsável por montar a renderização do select de Tipo de Serviço para os formulários
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getTipodeservicoItensSelect($request,$id){
    $itensSelect = '';
    $resultsSelect = EntityTipodeservico::getTipodeservicos(null,'tipodeservico_nm ');
    //echo "<pre>"; print_r($resultsSelect); echo "<pre>"; exit;

    while($obTipodeservico = $resultsSelect->fetchObject(EntityTipodeservico::class)){
      $itensSelect .= View::render('admin/modules/tipodeservico/itemselect',[
        'idSelect' => $obTipodeservico->tipodeservico_id,
        'selecionado' => ($id == $obTipodeservico->tipodeservico_id) ? 'selected' : '',
        'nomeSelect' => $obTipodeservico->tipodeservico_nm
      ]);
    }
    return $itensSelect;
  }

  /**
   * Método responsável por montar a renderização do select de Tipo de Serviço para os formulários
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getTipodeservicoItensSelectChamados($request,$id){
    $itensSelect = '';
    $where = '';
    $departamento = '';
    $servico = '';

    $queryParams = $request->getQueryParams();
    $id_departamento = $queryParams['departamento'] ?? 0;
    $id_servico = $queryParams['servico'] ?? 0;
    $id_tipodeservico = $queryParams['tipodeservico'] ?? 0;

    if($id_servico <> 0){
      $where = 'id_servico = '.$id_servico;
      if($id_departamento <> 0){
        $where = $where .' AND id_departamento = '.$id_departamento;
      }
    } else {
      if($id_departamento <> 0){
        $where = 'id_servico = servico_id AND id_departamento = '.$id_departamento;
      }
    }

    $resultsSelect = EntityAtendimento::getAtendimentos2($where,'atendimento_id ','tb_servico, tb_atendimento',null,'DISTINCT id_tipodeservico');

    //$resultsSelect = EntityTipodeservico::getTipodeservicos(null,'tipodeservico_nm ');

    while($obTipodeservico = $resultsSelect->fetchObject(EntityAtendimento::class)){
      $itensSelect .= View::render('admin/modules/tipodeservico/itemselect',[
        'idSelect' => $obTipodeservico->id_tipodeservico,
        'selecionado' => ($id_tipodeservico == $obTipodeservico->id_tipodeservico) ? 'selected' : '',
        'nomeSelect' => EntityTipodeservico::getTipodeservicoPorId($obTipodeservico->id_tipodeservico)->tipodeservico_nm
      ]);
    }
    return $itensSelect;
  }


  //*** Método responsável por retornar o formulário de cadastro de um novo Tipo de Serviço *** EXCLUIDO

   /**
    * Método responsável por cadastro de um novo Tipo de Serviço no banco
    * @param Request $request
    * @return string
    */
    public static function setNovoTipodeservico($request){

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);

      //VERIFICA SE JÁ EXISTE O TIPO de Serviço com mesmo nome CADASTRADO NO BANCO
      $obTipodeservico = EntityTipodeservico::getTipodeservicoPorNome($nome);

      if($obTipodeservico instanceof EntityTipodeservico){

        //REDIRECIONA SE O E-MAIL JÁ FOR CADASTRADO NO BANCO
        $request->getRouter()->redirect('/admin/tipodeservicos?status=duplicado');
      }

      //NOVA ISNTANCIA DE Serviço
      $obTipodeservico = new EntityTipodeservico;

      ////$obTipodeservico::getTipodeservicoPorEmail($posVars['email']);
      $obTipodeservico->tipodeservico_nm = $nome;
      $obTipodeservico->tipodeservico_des = $descricao;
      $obTipodeservico->ativo_fl = 's';
      $obTipodeservico->cadastrar();

      //REDIRECIONA O USUÁRIO
      $request->getRouter()->redirect('/admin/tipodeservicos?status=gravado&nm='.$nome.'&acao=grava');

    }


     /**
      * Método responsável por gravar a edição de um Tipo de Serviço
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function setEditTipodeservico($request,$id){

      //  echo "<pre>"; print_r($id); echo "<pre>"; exit;

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);;
      $tipodeservico_nm = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $tipodeservico_des = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);

        //OBTÉM O USUÁRIO DO BANCO DE DADOS
        $obTipodeservico = EntityTipodeservico::getTipodeservicoPorId($id);

        if(!$obTipodeservico instanceof EntityTipodeservico){

          //echo "<pre>"; print_r($id); echo "<pre>"; exit;
          $request->getRouter()->redirect('/admin/tipodeservicos?status=updatefail');
        }

        //echo "<pre>"; print_r($id_tipodeservico); echo "<pre>"; exit;

        //ATUALIZA A INSTANCIA
        $obTipodeservico->tipodeservico_nm = $tipodeservico_nm ?? $obTipodeservico->tipodeservico_nm;
        $obTipodeservico->tipodeservico_des = $tipodeservico_des  ?? $obTipodeservico->tipodeservico_des;
        $obTipodeservico->atualizar();

        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/tipodeservicos?status=alterado&nm='.$tipodeservico_nm.'&acao=alter');
      }

      /**
       * Método responsável por retornar o formulário de alteração de status de um Tipo de Serviço
       * @param Request $request
       * @param integer $id
       * @return string
       */
       public static function getAltStatusTipodeservicoModal($request,$id){

         //OBTÉM O USUÁRIO DO BANCO DE DADOS
         $obTipodeservico = EntityTipodeservico::getTipodeservicoPorId($id);
         $strNome = $obTipodeservico->tipodeservico_nm;


         if(!$obTipodeservico instanceof EntityTipodeservico){
           $request->getRouter()->redirect('/admin/tipodeservicos?status=updatefail');
         }


         //OBTÉM O TIPO DE SERVIÇO DO BANCO DE DADOS
         if($obTipodeservico->ativo_fl == 's'){
           $altStatus = 'n';
           $strMsn = ' DESATIVADO ';
         } elseif ($obTipodeservico->ativo_fl == 'n') {
           $altStatus = 's';
           $strMsn = ' ATIVADO ';
         }

         //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
         $obTipodeservico->ativo_fl = $altStatus;
         $obTipodeservico->atualizar();

         //REDIRECIONA O USUÁRIO
         $request->getRouter()->redirect('/admin/tipodeservicos?status=statusupdate&nm='.$strNome.$strMsn.'&acao=alter');

       }


    // Método responsável por retornar o formulário de exclusão de um Tipo de Serviço***** METODO XCLUIDO


     /**
      * Método responsável por retornar o formulário de exclusão de um Tipo de Serviço atraves de um Modal
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function getDeleteTipodeservicoModal($request,$id){

        $obTipodeservico = EntityTipodeservico::getTipodeservicoPorId($id);
        $strNome = $obTipodeservico->tipodeservico_nm;

        if(!$obTipodeservico instanceof EntityTipodeservico){
          $request->getRouter()->redirect('/admin/tipodeservicos?status=updatefail');
        }

       //EXCLUI O USUÁRIO
        $obTipodeservico->excluir();
        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/tipodeservicos?status=deletado&nm='.$strNome.$strMsn.'&acao=excluir');
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
    if(!isset($queryParams['status'])) return '';

   //MENSAGENS DE STATUS
   switch ($queryParams['status']) {
     case 'gravado':
       return Alert::getSuccess('Dados d'.TITLE_TIPOSERV .' <strong>'.$nm.'</strong> cadastrados com sucesso!');
       // code...
       break;
     case 'alterado':
       return Alert::getSuccess('Dados d'.TITLELOW_TIPOSERV .' <strong>'.$nm.'</strong> alterados com sucesso!');
       // code...
       break;
     case 'deletado':
       return Alert::getSuccess('Registro d'.TITLE_TIPOSERV .'  <strong>'.$nm.'</strong> deletado com sucesso!');
       // code...
       break;
     case 'duplicado':
       return Alert::getError('Já existe '.TITLELOW_TIPOSERV .' com este nome (<strong>'.$nm.'</strong>) para outr'.TITLELOW_TIPOSERV.'!');
       // code...
       break;
     case 'statusupdate':
       return Alert::getSuccess('Status d'.TITLELOW_TIPOSERV .' <strong>'.$nm.'</strong> com sucesso!');
       // code...
       break;
   }
  }
}
