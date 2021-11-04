<?php

namespace App\Controller\Admin;

use \DateTime;

use \App\Utils\View;
use \App\Model\Entity\Sessao as EntitySessao;
use \App\Model\Entity\Pagina as EntityPagina;
use \App\Controller\Admin\Servicos as AdminServico;
use \App\Controller\Admin\Paginas as AdminPaginas;
use \App\Model\Entity\Arquivo as EntityArquivo;
use \App\File\Upload;
use \App\Db\Sessaotion;

const DIR_SESSAO = 'sessao';
const FIELD_SESSAO = 'sessao';
const ROTA_SESSAO = 'sessoes';
const ICON_SESSAO = 'book';
const TITLE_SESSAO = 'Sessões';
const TITLELOW_SESSAO = 'a Sessão';

class Sessoes extends Page{

  /**
   * Método responsável pela renderização da view de listagem de Localizacões
   * @param Request $request
   * @return string
   */
    public static function getListSessoes($request,$errorMessage = null){

      $permissao = false;
      $status = self::getStatus($request);
      $currentDepartamento = $_SESSION['admin']['usuario']['departamento'];
      $currentPerfil = $_SESSION['admin']['usuario']['id_perfil'];

      //STATUS
      if(!isset($currentDepartamento)) return $permissao = false;

     //MENSAGENS DE STATUS
     switch ($currentDepartamento) {
       case 'DECOM':
         $permissao = false;
         break;
       case 'DETEC':
         $permissao = true;
         break;
       case 'EMERJ':
         $permissao = false;
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
      $content = View::render('admin/modules/'.DIR_SESSAO.'/index',[
        'icon' => ICON_SESSAO,
        'title' =>TITLE_SESSAO,
        'titlelow' => TITLELOW_SESSAO,
        'direntity' => ROTA_SESSAO,
        'itens' => self::getSessaoItens($request,$obSessaotion),
        'status' => $status
      ]);
      //RETORNA A PÁGINA COMPLETA
      return parent::getPanel(TITLE_SESSAO.' - EMERJ',$content,ROTA_SESSAO,$currentDepartamento,$currentPerfil);
  }

  /**
   * Método responsável por obter a renderização das Conteúdos do Sessao para a página
   * @param Request $request
   * @param Sessaotion $obSessaotion
   * @return string
   */
   private static function getSessaoItens($request,&$obSessaotion){

     $itens = '';

     //RESONSÁVEL PELA RENDERIZAÇÃO DOS MODAIS DE EDIÇÃO NA PÁGINA DE LISTAGEM DE LOCALIZAÇÕES
     $strAtivaModal = View::render('admin/modules/'.DIR_SESSAO.'/ativamodal',[]);
     $strDeleteModal = View::render('admin/modules/'.DIR_SESSAO.'/deletemodal',[]);

     //RESULTADO DA SESSAO
     $results = EntitySessao::getSessoes();

     //MONTA E RENDERIZA OS ITENS DE Sessao
     while($obSessao = $results->fetchObject(EntitySessao::class)){
       $itens .= View::render('admin/modules/'.DIR_SESSAO.'/item',[
        'id' => $obSessao->sessao_id,
        'sessao_nm' => $obSessao->sessao_nm,
        'sessao_titulo' => $obSessao->sessao_titulo ?? '',
        'sessao_conteudo' => $obSessao->sessao_conteudo ?? '',
        'sessao_des' => $obSessao->sessao_des ?? '',
        'pagina' => EntityPagina::getPaginaPorId($obSessao->id_pagina)->pagina_nm ?? '',
        'sessao_icon' => $obSessao->sessao_icon ?? '',
        'sessao_style' => $obSessao->sessao_style ?? '',
        'texto_ativo' => ('s' == $obSessao->ativo_fl) ? 'Desativar' : 'Ativar',
        'class_ativo' => ('s' == $obSessao->ativo_fl) ? 'btn-warning' : 'btn-success',
        'style_ativo' => ('s' == $obSessao->ativo_fl) ? 'table-active' : 'table-danger',
        'icon' => ICON_SESSAO,
        'title' =>TITLE_SESSAO,
        'titlelow' => TITLELOW_SESSAO,
        'direntity' => ROTA_SESSAO
      ]);
    }
    $itens .= $strDeleteModal;
    $itens .= $strAtivaModal;
    return $itens;
  }

  /**
   * Método responsável por montar a renderização do select de Conteúdos do Sessao para o formulário
   * @param Request $request
   * @param integer $id
   * @return string
   */
   public static function getSessaoItensSelect($request,$id){
      $itensSelect = '';
      $resultsSelect = EntitySessao::getSessoes(null,'sessao_id ASC');

      while($obSessao = $resultsSelect->fetchObject(EntitySessao::class)){
        $itensSelect .= View::render('admin/modules/'.DIR_SESSAO.'/itemselect',[
          'idSelect' => $obSessao->sessao_id,
          'selecionado' => ($id == $obSessao->sessao_id) ? 'selected' : '',
          'nomeSelect' => $obSessao->sessao_titulo
        ]);
      }
      return $itensSelect;
    }


    /**
     * Método responsável por retornar o formulário de cadastro de um novo Chamado
     * @param Request $request
     * @return string
     */
     public static function getNovaSessao($request){

       $id = '';
       $status = '';
       $currentDepartamento = $_SESSION['admin']['usuario']['departamento'];
       $currentPerfil = $_SESSION['admin']['usuario']['id_perfil'];
       $currentUsuario = $_SESSION['admin']['usuario']['usuario_id'];

       $paginaSelecionada = AdminPaginas::getPaginaItensSelect($request,$id);

       //CONTEÚDO DA NOTÍCIA
       $content = View::render('admin/modules/'.DIR_SESSAO.'/form',[
         'icon' => ICON_SESSAO,
         'title' =>TITLE_SESSAO,
         'titlelow' => TITLELOW_SESSAO,
         'direntity' => ROTA_SESSAO,
         'itens' => self::getSessaoItens($request,$obSessaotion),
         'sessao_nm' => '',
         'sessao_titulo' => '',
         'sessao_conteudo' => '',
         'optionsPaginas' => $paginaSelecionada,
         'descricao' => '',
         'id_usuario' => $currentUsuario ?? '',
         'status' => $status
       ]);

       //RETORNA A PÁGINA COMPLETA
       return parent::getPanel('Cadastrar Sessão - EMERJ',$content,'sessoes',$currentDepartamento,$currentPerfil);
     }

   /**
    * Método responsável por cadastro de uma nova Localização no banco
    * @param Request $request
    * @return string
    */
    public static function setNovaSessao($request){

      date_default_timezone_set('America/Sao_Paulo');

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $id_pagina = filter_input(INPUT_POST, 'pagina', FILTER_SANITIZE_NUMBER_INT);
      $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $sessao_titulo = filter_input(INPUT_POST, 'sessao_titulo', FILTER_SANITIZE_STRING);
      $conteudo = $posVars['sessao_conteudo'];
      $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_SANITIZE_NUMBER_INT);

      //NOVA ISNTANCIA DE DEPARTAMENTO
      $obSessao = new EntitySessao;

      $obSessao->sessao_nm = $nome;
      $obSessao->sessao_titulo = $sessao_titulo;
      $obSessao->sessao_conteudo = html_entity_decode($conteudo);
      $obSessao->id_pagina = $id_pagina;
      $obSessao->id_usuario = $id_usuario ?? $_SESSION['admin']['usuario']['usuario_id'];
      $obSessao->cadastrar();

      $idSessao = $obSessao->sessao_id;

      //REDIRECIONA O DEPARTAMENTO
      $request->getRouter()->redirect('/admin/sessoes?status=gravado&nm='.$nome.'&acao=alter');
    }

    /**
     * Método responsável por gravar a edição de uma Localização
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function getEditSessao($request,$id){

       $status = '';
       $currentDepartamento = $_SESSION['admin']['usuario']['departamento'];
       $currentPerfil = $_SESSION['admin']['usuario']['id_perfil'];
       $currentUsuario = $_SESSION['admin']['usuario']['usuario_id'];

        $obSessao = EntitySessao::getSessaoPorId($id);

        $paginaSelecionada = AdminPaginas::getPaginaItensSelect($request,$obSessao->id_pagina);

       //CONTEÚDO DA NOTÍCIA
       $content = View::render('admin/modules/'.DIR_SESSAO.'/form',[
         'icon' => ICON_SESSAO,
         'title' => 'Alterar '.TITLE_SESSAO,
         'titlelow' => TITLELOW_SESSAO,
         'direntity' => ROTA_SESSAO,
         'itens' => self::getSessaoItens($request,$obSessaotion),
         'optionsPaginas' => $paginaSelecionada,
         'sessao_nm' => $obSessao->sessao_nm ?? '',
         'sessao_titulo' => $obSessao->sessao_titulo ?? '',
         'sessao_conteudo' => $obSessao->sessao_conteudo ?? '',
         'id_usuario' => $currentUsuario ?? '',
         'status' => $status
       ]);

       return parent::getPanel('Alterar Sessão - EMERJ',$content,'sessoes',$currentDepartamento,$currentPerfil);


     }

   /**
    * Método responsável por gravar a edição de uma Localização
    * @param Request $request
    * @param integer $id
    * @return string
    */
    public static function setEditSessao($request,$id){

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $id_pagina = filter_input(INPUT_POST, 'pagina', FILTER_SANITIZE_NUMBER_INT);
      $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $sessao_titulo = filter_input(INPUT_POST, 'sessao_titulo', FILTER_SANITIZE_STRING);
      $conteudo = $posVars['sessao_conteudo'];
      $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_SANITIZE_NUMBER_INT);

      //OBTÉM O USUÁRIO DO BANCO DE DADOS
      $obSessao = EntitySessao::getSessaoPorId($id);

      if(!$obSessao instanceof EntitySessao){
        $request->getRouter()->redirect('/admin/sessoes?status=updatefail');
      }

      //ATUALIZA A INSTANCIA
      $obSessao->sessao_id = $id;
      $obSessao->sessao_nm = $nome;
      $obSessao->sessao_titulo = $sessao_titulo;
      $obSessao->sessao_conteudo = html_entity_decode($conteudo);
      $obSessao->id_usuario = $id_usuario;
      $obSessao->id_pagina = $id_pagina;
      $obSessao->atualizar();

      //REDIRECIONA O USUÁRIO PARA A SESSAO INICIAL DE LISTAR CATEGORIAS DE IC's
      $request->getRouter()->redirect('/admin/sessoes?status=alterado&nm='.$nome.'&acao=alter');
    }

    /**
     * Método responsável por retornar o formulário de alteração de status de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function getAltStatusSessao($request,$id){

       //OBTÉM A CATEGORIA DO IC PELO SEU ID DO BANCO DE DADOS
       $obSessao = EntitySessao::getSessaoPorId($id);
       $strNome = $obSessao->sessao_nm;



       if(!$obSessao instanceof EntitySessao){
         $request->getRouter()->redirect('/admin/sessoes?status=updatefail');
       }

       //OBTÉM O USUÁRIO DO BANCO DE DADOS
       if($obSessao->ativo_fl == 's'){
         $altStatus = 'n';
         $strMsn = ' DESATIVADO ';
       } elseif (($obSessao->ativo_fl == 'n') || ($obSessao->ativo_fl == '')) {
         $strMsn = ' ATIVADO ';
         $altStatus = 's';
       }

       //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
       $obSessao->ativo_fl = $altStatus;
       $obSessao->atualizar();

       //REDIRECIONA O USUÁRIO
       $request->getRouter()->redirect('/admin/sessoes?status=statusupdate&nm='.$strNome.$strMsn);

     }

   /**
      * Método responsável por retornar o formulário de exclusão de um usuário atraves de um Modal
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function getDeleteSessao($request,$id){

       //OBTÉM O LOCALIZAÇÃO DO BANCO DE DADOS
        $obSessao = EntitySessao::getSessaoPorId($id);
        $strNome = $obSessao->sessao_nm;

        if(!$obSessao instanceof EntitySessao){
          $request->getRouter()->redirect('/admin/sessoes');
        }

       //EXCLUI O USUÁRIO
        $obSessao->excluir();
        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/sessoes?status=deletado&nm='.$strNome.$strMsn.'&acao=alter');
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
         return Alert::getSuccess('Dados d'.TITLE_SESSAO.' <strong>'.$nm.'</strong> cadastrados com sucesso!');
         // code...
         break;
       case 'alterado':
         return Alert::getSuccess('Dados d'.TITLELOW_SESSAO.' <strong>'.$nm.'</strong> alterados com sucesso!');
         // code...
         break;
       case 'deletado':
         return Alert::getSuccess('Registro d'.TITLE_SESSAO.'  <strong>'.$nm.'</strong> deletado com sucesso!');
         // code...
         break;
       case 'updatefaildatediff':
         return Alert::getError('A diferença entre as datas fim e a início não pode ser inferior a um dia!');
         // code...
         break;
       case 'updatefaildateAgora':
         return Alert::getError('A data de início não pode ser anterior a data/hora de agora!');
         // code...
         break;

       case 'duplicado':
         return Alert::getError('Já existe '.TITLELOW_SESSAO.' com este nome (<strong>'.$nm.'</strong>) para outr'.TITLELOW_SESSAO.'!');
         // code...
         break;
       case 'statusupdate':
         return Alert::getSuccess('Status d'.TITLELOW_SESSAO.' <strong>'.$nm.'</strong> com sucesso!');
         // code...
         break;
     }
   }
}
