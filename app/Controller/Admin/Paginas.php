<?php

namespace App\Controller\Admin;

use \DateTime;

use \App\Utils\View;
use \App\Model\Entity\Pagina as EntityPagina;
use \App\Controller\Admin\Servicos as AdminServico;
use \App\Controller\Admin\Itensconfs as AdminItensconfs;
use \App\Model\Entity\Arquivo as EntityArquivo;
use \App\File\Upload;
use \App\Db\Pagination;

const DIR_PAGINA = 'pagina';
const FIELD_PAGINA = 'pagina';
const ROTA_PAGINA = 'paginas';
const ICON_PAGINA = 'book';
const TITLE_PAGINA = 'Páginas';
const TITLELOW_PAGINA = 'a Página';

class Paginas extends Page{

  /**
   * Método responsável pela renderização da view de listagem de Localizacões
   * @param Request $request
   * @return string
   */
    public static function getListPaginas($request,$errorMessage = null){

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
      $content = View::render('admin/modules/'.DIR_PAGINA.'/index',[
        'icon' => ICON_PAGINA,
        'title' =>TITLE_PAGINA,
        'titlelow' => TITLELOW_PAGINA,
        'direntity' => ROTA_PAGINA,
        'itens' => self::getPaginaItens($request,$obPagination),
        'status' => $status
      ]);
      //RETORNA A PÁGINA COMPLETA
      return parent::getPanel(TITLE_PAGINA.' - EMERJ',$content,ROTA_PAGINA,$currentDepartamento,$currentPerfil);
  }

  /**
   * Método responsável por obter a renderização das Conteúdos do Pagina para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
   private static function getPaginaItens($request,&$obPagination){

     $itens = '';
     $tipodeic = '';
     $idreferenciado = '';

     //RESONSÁVEL PELA RENDERIZAÇÃO DOS MODAIS DE EDIÇÃO NA PÁGINA DE LISTAGEM DE LOCALIZAÇÕES
     $strEditModal = View::render('admin/modules/'.DIR_PAGINA.'/editmodal',[]);
     $strAddModal = View::render('admin/modules/'.DIR_PAGINA.'/addmodal',[]);
     $strAtivaModal = View::render('admin/modules/'.DIR_PAGINA.'/ativamodal',[]);
     $strDeleteModal = View::render('admin/modules/'.DIR_PAGINA.'/deletemodal',[]);

     //RESULTADO DA PAGINA
     $results = EntityPagina::getPaginas();

     //MONTA E RENDERIZA OS ITENS DE Pagina
     while($obPagina = $results->fetchObject(EntityPagina::class)){
       $itens .= View::render('admin/modules/'.DIR_PAGINA.'/item',[
        'id' => $obPagina->pagina_id,
        'pagina_nm' => $obPagina->pagina_nm,
        'pagina_label' => $obPagina->pagina_label ?? '',
        'pagina_des' => $obPagina->pagina_des ?? '',
        'pagina_icon' => $obPagina->pagina_icon ?? '',
        'pagina_style' => $obPagina->pagina_style ?? '',
        'texto_ativo' => ('s' == $obPagina->ativo_fl) ? 'Desativar' : 'Ativar',
        'class_ativo' => ('s' == $obPagina->ativo_fl) ? 'btn-warning' : 'btn-success',
        'style_ativo' => ('s' == $obPagina->ativo_fl) ? 'table-active' : 'table-danger',
        'id_usuário' => $obPagina->id_usuario,
        'icon' => ICON_PAGINA,
        'title' =>TITLE_PAGINA,
        'titlelow' => TITLELOW_PAGINA,
        'direntity' => ROTA_PAGINA
      ]);
    }
    $itens .= $strDeleteModal;
    $itens .= $strAtivaModal;
    $itens .= $strEditModal;
    $itens .= $strAddModal;
    return $itens;
  }

  /**
   * Método responsável por montar a renderização do select de Conteúdos do Pagina para o formulário
   * @param Request $request
   * @param integer $id
   * @return string
   */
   public static function getPaginaItensSelect($request,$id){
      $itensSelect = '';
      $resultsSelect = EntityPagina::getPaginas(null,'pagina_id ASC');

      while($obPagina = $resultsSelect->fetchObject(EntityPagina::class)){
        $itensSelect .= View::render('admin/modules/'.DIR_PAGINA.'/itemselect',[
          'idSelect' => $obPagina->pagina_id,
          'selecionado' => ($id == $obPagina->pagina_id) ? 'selected' : '',
          'nomeSelect' => $obPagina->pagina_nm
        ]);
      }
      return $itensSelect;
    }


    /**
     * Método responsável por retornar o formulário de cadastro de um novo Chamado
     * @param Request $request
     * @return string
     */
     public static function getNovaPagina($request){

       $status = '';
       $currentDepartamento = $_SESSION['admin']['usuario']['departamento'];
       $currentPerfil = $_SESSION['admin']['usuario']['id_perfil'];
       $currentUsuario = $_SESSION['admin']['usuario']['usuario_id'];

       //CONTEÚDO DA NOTÍCIA
       $content = View::render('admin/modules/'.DIR_PAGINA.'/form',[
         'icon' => ICON_PAGINA,
         'title' =>TITLE_PAGINA,
         'titlelow' => TITLELOW_PAGINA,
         'direntity' => ROTA_PAGINA,
         'itens' => self::getPaginaItens($request,$obPagination),
         'pagina_id' => '',
         'pagina_nm' => '',
         'pagina_label' => '',
         'pagina_des' => '',
         'id_usuario' => $currentUsuario ?? '',
         'status' => $status
       ]);

       //RETORNA A PÁGINA COMPLETA
       return parent::getPanel('Cadastrar Página - EMERJ',$content,'paginas',$currentDepartamento,$currentPerfil);
     }

   /**
    * Método responsável por cadastro de uma nova Localização no banco
    * @param Request $request
    * @return string
    */
    public static function setNovaPagina($request){

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $pagina_nm = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $pagina_label = filter_input(INPUT_POST, 'menu', FILTER_SANITIZE_STRING);
      $pagina_des = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
      $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_SANITIZE_NUMBER_INT);

      //NOVA ISNTANCIA DE DEPARTAMENTO
      $obPagina = new EntityPagina;

      $obPagina->pagina_nm = $pagina_nm;
      $obPagina->pagina_label = $pagina_label;
      $obPagina->pagina_des = $pagina_des;
      $obPagina->id_usuario = $id_usuario;
      $obPagina->cadastrar();

      //REDIRECIONA O DEPARTAMENTO
      $request->getRouter()->redirect('/admin/paginas?status=gravado&nm='.$nome.'&acao=alter');
    }

    /**
     * Método responsável por gravar a edição de uma Localização
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function getEditPagina($request,$id){

       $status = '';
       $currentDepartamento = $_SESSION['admin']['usuario']['departamento'];
       $currentPerfil = $_SESSION['admin']['usuario']['id_perfil'];

        $obPagina = EntityPagina::getPaginaPorId($id);

       //CONTEÚDO DA NOTÍCIA
       $content = View::render('admin/modules/'.DIR_PAGINA.'/form',[
         'icon' => ICON_PAGINA,
         'title' => 'Alterar '.TITLE_PAGINA,
         'titlelow' => TITLELOW_PAGINA,
         'direntity' => ROTA_PAGINA,
         'itens' => self::getPaginaItens($request,$obPagination),
         'pagina_id' => $obPagina->pagina_id ?? '',
         'pagina_nm' => $obPagina->pagina_nm ?? '',
         'pagina_label' => $obPagina->pagina_label ?? '',
         'pagina_des' => $obPagina->pagina_des ?? '',
         'id_usuario' => $obPagina->id_usuario ?? '',
         'status' => $status
       ]);

       return parent::getPanel('Alterar Página - EMERJ',$content,'paginas',$currentDepartamento,$currentPerfil);


     }

   /**
    * Método responsável por gravar a edição de uma Localização
    * @param Request $request
    * @param integer $id
    * @return string
    */
    public static function setEditPagina($request,$id){

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
      $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $label = filter_input(INPUT_POST, 'menu', FILTER_SANITIZE_STRING);
      $pagina_des = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
      $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_SANITIZE_NUMBER_INT);

        //OBTÉM O USUÁRIO DO BANCO DE DADOS
      $obPagina = EntityPagina::getPaginaPorId($id);

      if(!$obPagina instanceof EntityPagina){
        $request->getRouter()->redirect('/admin/paginas?status=updatefail');
      }

      //ATUALIZA A INSTANCIA
      $obPagina->pagina_id = $id;
      $obPagina->pagina_nm = $nome;
      $obPagina->pagina_label = $label;
      $obPagina->pagina_des = $pagina_des;
      $obPagina->id_usuario = $id_usuario;
      $obPagina->atualizar();

      //REDIRECIONA O USUÁRIO PARA A PAGINA INICIAL DE LISTAR CATEGORIAS DE IC's
      $request->getRouter()->redirect('/admin/paginas?status=alterado&nm='.$nome.'&acao=alter');
    }

    /**
     * Método responsável por retornar o formulário de alteração de status de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function getAltStatusPagina($request,$id){

       //OBTÉM A CATEGORIA DO IC PELO SEU ID DO BANCO DE DADOS
       $obPagina = EntityPagina::getPaginaPorId($id);
       $strNome = $obPagina->pagina_nm;

       if(!$obPagina instanceof EntityPagina){
         $request->getRouter()->redirect('/admin/paginas?status=updatefail');
       }

       //OBTÉM O USUÁRIO DO BANCO DE DADOS
       if($obPagina->ativo_fl == 's'){
         $altStatus = 'n';
         $strMsn = ' DESATIVADO ';
       } elseif (($obPagina->ativo_fl == 'n') || ($obPagina->ativo_fl == '')) {
         $strMsn = ' ATIVADO ';
         $altStatus = 's';
       }

       //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
       $obPagina->ativo_fl = $altStatus;
       $obPagina->atualizar();

       //REDIRECIONA O USUÁRIO
       $request->getRouter()->redirect('/admin/paginas?status=statusupdate&nm='.$strNome.$strMsn);

     }

   /**
      * Método responsável por retornar o formulário de exclusão de um usuário atraves de um Modal
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function getDeletePagina($request,$id){

       //OBTÉM O LOCALIZAÇÃO DO BANCO DE DADOS
        $obPagina = EntityPagina::getPaginaPorId($id);
        $strNome = $obPagina->pagina_nm;

        if(!$obPagina instanceof EntityPagina){
          $request->getRouter()->redirect('/admin/paginas');
        }

       //EXCLUI O USUÁRIO
        $obPagina->excluir();
        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/paginas?status=deletado&nm='.$strNome.$strMsn.'&acao=alter');
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
         return Alert::getSuccess('Dados d'.TITLE_PAGINA.' <strong>'.$nm.'</strong> cadastrados com sucesso!');
         // code...
         break;
       case 'alterado':
         return Alert::getSuccess('Dados d'.TITLELOW_PAGINA.' <strong>'.$nm.'</strong> alterados com sucesso!');
         // code...
         break;
       case 'deletado':
         return Alert::getSuccess('Registro d'.TITLE_PAGINA.'  <strong>'.$nm.'</strong> deletado com sucesso!');
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
         return Alert::getError('Já existe '.TITLELOW_PAGINA.' com este nome (<strong>'.$nm.'</strong>) para outr'.TITLELOW_PAGINA.'!');
         // code...
         break;
       case 'statusupdate':
         return Alert::getSuccess('Status d'.TITLELOW_PAGINA.' <strong>'.$nm.'</strong> com sucesso!');
         // code...
         break;
     }
   }
}
