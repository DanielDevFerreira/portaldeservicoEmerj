<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Categoriadeic as EntityCategoriadeic;
use \App\Model\Entity\Tipodeic as EntityTipodeic;
use \App\Model\Entity\Atendimento as EntityAtendimento;
use \App\Controller\Admin\Categoriadeics as AdminCategoriadeics;
use \App\Db\Pagination;

const DIR_TIPOIC = 'tipodeic';
const ROTA_TIPOIC = 'tipodeics';
const ICON_TIPOIC = 'signpost-split';
const TITLE_TIPOIC = 'Tipos de ICs';
const TITLELOW_TIPOIC = 'o tipo de item de configuração';

class Tipodeics extends Page{

  /**
   * Método responsável pela renderização da view de listagem de IC's
   * @param Request $request
   * @return string
   */
  public static function getListTipodeics($request,$errorMessage = null){

    $id = '';
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

    //DEFINE AS VARIÁVEIS BASE PARA MONTAR A ESTRUTURA DE RENDERIZAÇÃO DA PÁGINA (COM MODAIS) DE TIPO DE LOCALIZAÇÕES
    $status = self::getStatus($request);

    $categoriadeicSelecionado = AdminCategoriadeics::getCategoriadeicItensSelect($request,$id);;

    //CONTEÚDO DA HOME
    $content = View::render('admin/modules/'.DIR_TIPOIC.'/index',[
      'icon' => ICON_TIPOIC,
      'title' =>TITLE_TIPOIC,
      'titlelow' => TITLELOW_TIPOIC,
      'direntity' => ROTA_TIPOIC,
      'itens' => self::getTipodeicItens($request,$obPagination),
      'optionsCategoriadeic' => $categoriadeicSelecionado,
      'status' => $status
    ]);
    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel(TITLE_TIPOIC.' - EMERJ',$content,ROTA_TIPOIC,$currentDepartamento,$currentPerfil);
  }

  /**
   * Método responsável por obter a renderização dos itens de Tipodeics para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getTipodeicItens($request,&$obPagination){

    $itens = '';

    //RESONSÁVEL PELA RENDERIZAÇÃO DOS MODAIS DE EDIÇÃO NA PÁGINA DE LISTAGEM DE LOCALIZAÇÕES
    $strEditModal = View::render('admin/modules/'.DIR_TIPOIC.'/editmodal',[]);
    $strAddModal = View::render('admin/modules/'.DIR_TIPOIC.'/addmodal',[]);
    $strAtivaModal = View::render('admin/modules/'.DIR_TIPOIC.'/ativamodal',[]);
    $strDeleteModal = View::render('admin/modules/'.DIR_TIPOIC.'/deletemodal',[]);

    //RESULTADO DA PAGINA
    $results = EntityTipodeic::getTipodeics();

    //MONTA E RENDERIZA OS ITENS DE Tipodeic
    while($obTipodeic = $results->fetchObject(EntityTipodeic::class)){
      $itens .= View::render('admin/modules/'.DIR_TIPOIC.'/item',[
        'id' => $obTipodeic->tipodeic_id,
        'nome' => $obTipodeic->tipodeic_nm,
        'descricao' => $obTipodeic->tipodeic_des,
        'categoriadeic_id' => $obTipodeic->id_categoria_ic,
        'categoriadeic' => EntityCategoriadeic::getCategoriadeicPorId($obTipodeic->id_categoria_ic)->categoria_ic_nm.' - ('.EntityCategoriadeic::getCategoriadeicPorId($obTipodeic->id_categoria_ic)->categoria_ic_titulo.')' ?? '',
        'texto_ativo' => ('s' == $obTipodeic->ativo_fl) ? 'Desativar' : 'Ativar',
        'class_ativo' => ('s' == $obTipodeic->ativo_fl) ? 'btn-warning' : 'btn-success',
        'style_ativo' => ('s' == $obTipodeic->ativo_fl) ? 'table-active' : 'table-danger',
        'icon' => ICON_TIPOIC,
        'title' =>TITLE_TIPOIC,
        'titlelow' => TITLELOW_TIPOIC,
        'direntity' => ROTA_TIPOIC
      ]);
    }
    $itens .= $strDeleteModal;
    $itens .= $strAtivaModal;
    $itens .= $strEditModal;
    $itens .= $strAddModal;
    return $itens;
  }

  /**
   * Método responsável por montar a renderização do select de Tipo de IC's para os formulários
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getTipodeicItensSelect($request,$id){
    $itensSelect = '';
    $resultsSelect = EntityTipodeic::getTipodeics(null,'tipodeic_nm ');

    while($obTipodeic = $resultsSelect->fetchObject(EntityTipodeic::class)){
      $itensSelect .= View::render('admin/modules/'.DIR_TIPOIC.'/itemselect',[
        'idSelect' => $obTipodeic->tipodeic_id,
        'selecionado' => ($id == $obTipodeic->tipodeic_id) ? 'selected' : '',
        'nomeSelect' => $obTipodeic->tipodeic_nm
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
  public static function getTipodeicItensSelectChamados($request,$servico,$tipodeservico,$departamento){

    $itensSelect = '';
    $where = null;
    $id_servico = $servico;
    $id_tipodeservico = $tipodeservico;
    $id_departamento = $departamento;

    $queryParams = $request->getQueryParams();
    $id_tipodeservico = $queryParams['tipodeservico'] ?? 0;
    $id_servico = $queryParams['servico'] ?? 0;
    $id_departamento = $queryParams['departamento'] ?? 0;
    $id_tipodeic = $queryParams['tipodeic'] ?? 0;

    if($id_servico <> 0){
      $where = 'servico_id = '.$id_servico;
      if($id_departamento <> 0){
        $where = $where .' AND id_departamento = '.$id_departamento;
      }
    } else {
      if($id_departamento <> 0){
        $where = 'id_departamento = '.$id_departamento;
      }
    }

    $where = ' tipodeic_id = id_tipodeic AND id_departamento = '.$id_departamento.' AND id_servico = '.$id_servico;

    $resultsSelect = EntityAtendimento::getAtendimentos2($where,'tipodeic_id ','tb_tipodeic, tb_atendimento',null,'DISTINCT id_tipodeic');

    while($obTipodeic = $resultsSelect->fetchObject(EntityAtendimento::class)){
      $itensSelect .= View::render('admin/modules/tipodeic/itemselect',[
        'idSelect' => $obTipodeic->id_tipodeic,
        'selecionado' => ($id_tipodeic == $obTipodeic->id_tipodeic) ? 'selected' : '',
        'nomeSelect' => EntityTipodeic::getTipodeicPorId($obTipodeic->id_tipodeic)->tipodeic_nm
      ]);
    }
    return $itensSelect;
  }

   /**
    * Método responsável por cadastro de um novo item de configuração no banco
    * @param Request $request
    * @return string
    */
    public static function setNovoTipodeic($request){

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $tipodeic_nm = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING) ?? '';
      $tipodeic_des = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING) ?? '';
      $id_categoriadeic = filter_input(INPUT_POST, 'categoriadeic', FILTER_SANITIZE_NUMBER_INT) ?? '';

      //VERIFICA SE JÁ EXISTE O TIPO de IC com mesmo nome CADASTRADO NO BANCO
      $obTipodeic = EntityTipodeic::getTipodeicPorNome($nome);

      if($obTipodeic instanceof EntityTipodeic){
        //REDIRECIONA SE O E-MAIL JÁ FOR CADASTRADO NO BANCO
        $request->getRouter()->redirect('/admin/'.ROTA_TIPOIC.'/novo?status=duplicado');
      }

      //NOVA ISNTANCIA DE IC
      $obTipodeic = new EntityTipodeic;

      ////$obTipodeic::getTipodeicPorEmail($posVars['email']);
      $obTipodeic->tipodeic_nm = $tipodeic_nm;
      $obTipodeic->tipodeic_des = $tipodeic_des;
      $obTipodeic->id_categoria_ic = $id_categoriadeic;
      $obTipodeic->ativo_fl = 's';
      $obTipodeic->cadastrar();

      //REDIRECIONA O USUÁRIO
      $request->getRouter()->redirect('/admin/'.ROTA_TIPOIC.'?status=gravado&nm='.$nome.'&acao=grava');

    }

     /**
      * Método responsável por gravar a edição de um item de configuração
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function setEditTipodeic($request,$id){

        //DADOS DO POST
        $posVars = $request->getPostVars();
        $tipodeic_nm = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING) ?? '';
        $tipodeic_des = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING) ?? '';
        $id_categoriadeic = filter_input(INPUT_POST, 'categoriadeic', FILTER_SANITIZE_NUMBER_INT) ?? '';

        //OBTÉM O USUÁRIO DO BANCO DE DADOS
        $obTipodeic = EntityTipodeic::getTipodeicPorId($id);

        if(!$obTipodeic instanceof EntityTipodeic){
            $request->getRouter()->redirect('/admin/'.ROTA_TIPOIC.'/'.$id.'/edit?status=updatefail');
        }

        //ATUALIZA A INSTANCIA
        $obTipodeic->tipodeic_nm = $tipodeic_nm ?? $obTipodeic->tipodeic_nm;
        $obTipodeic->tipodeic_des = $tipodeic_des ?? $obTipodeic->tipodeic_des;
        $obTipodeic->id_categoria_ic = $id_categoriadeic ?? $obTipodeic->id_categoriadeic;
        $obTipodeic->atualizar();

        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/'.ROTA_TIPOIC.'?status=alterado&nm='.$tipodeic_nm.'&acao=alter');
      }

      /**
       * Método responsável por retornar o formulário de alteração de status de um usuário
       * @param Request $request
       * @param integer $id
       * @return string
       */
       public static function getAltStatusTipodeicModal($request,$id){

         //OBTÉM O USUÁRIO DO BANCO DE DADOS
         $obTipodeic = EntityTipodeic::getTipodeicPorId($id);
         $strNome = $obTipodeic->tipodeic_nm;

         if(!$obTipodeic instanceof EntityTipodeic){
           $request->getRouter()->redirect('/admin/'.ROTA_TIPOIC.'?status=updatefail');
         }

         //OBTÉM O USUÁRIO DO BANCO DE DADOS
         if($obTipodeic->ativo_fl == 's'){
           $altStatus = 'n';
           $strMsn = ' DESATIVADO ';
         } elseif ($obTipodeic->ativo_fl == 'n') {
           $altStatus = 's';
           $strMsn = ' ATIVADO ';
         }

         //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
         $obTipodeic->ativo_fl = $altStatus;
         $obTipodeic->atualizar();

         //REDIRECIONA O USUÁRIO
          $request->getRouter()->redirect('/admin/'.ROTA_TIPOIC.'?status=statusupdate&nm='.$strNome.$strMsn.'&acao=alter');
       }

       /**
        * Método responsável por retornar o formulário de exclusão de um usuário atraves de um Modal
        * @param Request $request
        * @param integer $id
        * @return string
        */
        public static function getDeleteTipodeicModal($request,$id){

          //OBTÉM O USUÁRIO DO BANCO DE DADOS
          $obTipodeic = EntityTipodeic::getTipodeicPorId($id);
          $strNome = $obTipodeic->tipodeic_nm;

          if(!$obTipodeic instanceof EntityTipodeic){
            $request->getRouter()->redirect('/admin/'.ROTA_TIPOIC);
          }

         //EXCLUI O USUÁRIO
          $obTipodeic->excluir();

          if(!$obTipodeic){
            $request->getRouter()->redirect('/admin/'.ROTA_TIPOIC.'/');
          }
          //REDIRECIONA O USUÁRIO
          $request->getRouter()->redirect('/admin/'.ROTA_TIPOIC.'?status=deletado&nm='.$strNome.$strMsn.'&acao=excluir');
        }

       /**
        * Método responsável por excluir um usuário
        * @param Request $request
        * @param integer $id
        * @return string
        */
        public static function setDeleteTipodeic($request,$id){

          //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
          $obTipodeic = EntityTipodeic::getTipodeicPorId($id);

          if(!$obTipodeic instanceof EntityTipodeic){
            $request->getRouter()->redirect('/admin/'.ROTA_TIPOIC);
          }
          //EXCLUI O USUÁRIO
          $obTipodeic->excluir();
          //REDIRECIONA O USUÁRIO
          $request->getRouter()->redirect('/admin/'.ROTA_TIPOIC.'?status=deletado&nm='.$strNome.$strMsn.'&acao=alter');

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
    switch ($status) {
      case 'gravado':
        return Alert::getSuccess('Dados d'.TITLE_TIPOIC.' <strong>'.$nm.'</strong> cadastrados com sucesso!');
        // code...
        break;
      case 'alterado':
        return Alert::getSuccess('Dados d'.TITLELOW_TIPOIC.' <strong>'.$nm.'</strong> alterados com sucesso!');
        // code...
        break;
      case 'deletado':
        return Alert::getSuccess('Registro d'.TITLE_TIPOIC.'  <strong>'.$nm.'</strong> deletado com sucesso!');
        // code...
        break;
      case 'duplicado':
        return Alert::getError('Já existe '.TITLELOW_TIPOIC.' com este nome (<strong>'.$nm.'</strong>) para outr'.TITLELOW_TIPOIC.'!');
        // code...
        break;
      case 'statusupdate':
        return Alert::getSuccess('Status d'.TITLELOW_TIPOIC.' <strong>'.$nm.'</strong> com sucesso!');
        // code...
        break;
    }
  }
}
