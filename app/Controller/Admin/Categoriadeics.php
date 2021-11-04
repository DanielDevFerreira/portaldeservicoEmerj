<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Categoriadeic as EntityCategoriadeic;
use \App\Controller\Admin\Servicos as AdminServico;
use \App\Controller\Admin\Itensconfs as AdminItensconfs;
use \App\Db\Pagination;

const DIR_CATEGORIADEIC = 'categoriadeic';
const FIELD_CATEGORIADEIC = 'categoria_ic';
const ROTA_CATEGORIADEIC = 'categoriadeics';
const ICON_CATEGORIADEIC = 'signpost';
const TITLE_CATEGORIADEIC = 'Categorias de ICs';
const TITLELOW_CATEGORIADEIC = 'a categorias de IC';

class Categoriadeics extends Page{

  /**
   * Método responsável pela renderização da view de listagem de Localizacões
   * @param Request $request
   * @return string
   */
    public static function getListCategoriadeics($request,$errorMessage = null){

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
      $content = View::render('admin/modules/'.DIR_CATEGORIADEIC.'/index',[
        'icon' => ICON_CATEGORIADEIC,
        'title' =>TITLE_CATEGORIADEIC,
        'titlelow' => TITLELOW_CATEGORIADEIC,
        'direntity' => ROTA_CATEGORIADEIC,
        'itens' => self::getCategoriadeicItens($request,$obPagination),
        'status' => $status
      ]);
      //RETORNA A PÁGINA COMPLETA
      return parent::getPanel(TITLE_CATEGORIADEIC.' - EMERJ',$content,ROTA_CATEGORIADEIC,$currentDepartamento,$currentPerfil);
  }

  /**
   * Método responsável por obter a renderização das Categorias de ICs para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
   private static function getCategoriadeicItens($request,&$obPagination){

     $itens = '';
     $tipodeic = '';
     $idreferenciado = '';

     //RESONSÁVEL PELA RENDERIZAÇÃO DOS MODAIS DE EDIÇÃO NA PÁGINA DE LISTAGEM DE LOCALIZAÇÕES
     $strEditModal = View::render('admin/modules/'.DIR_CATEGORIADEIC.'/editmodal',[]);
     $strAddModal = View::render('admin/modules/'.DIR_CATEGORIADEIC.'/addmodal',[]);
     $strAtivaModal = View::render('admin/modules/'.DIR_CATEGORIADEIC.'/ativamodal',[]);
     $strDeleteModal = View::render('admin/modules/'.DIR_CATEGORIADEIC.'/deletemodal',[]);

     //RESULTADO DA PAGINA
     $results = EntityCategoriadeic::getCategoriadeics();

     //MONTA E RENDERIZA OS ITENS DE Categoriadeic
     while($obCategoriadeic = $results->fetchObject(EntityCategoriadeic::class)){
       $itens .= View::render('admin/modules/'.DIR_CATEGORIADEIC.'/item',[
        'id' => $obCategoriadeic->categoria_ic_id,
        'nome' => $obCategoriadeic->categoria_ic_nm,
        'titulo' => $obCategoriadeic->categoria_ic_titulo,
        'descricao' => $obCategoriadeic->categoria_ic_descricao,
        'icone' => $obCategoriadeic->categoria_ic_icon,
        'estilo' => $obCategoriadeic->categoria_ic_style,
        'texto_ativo' => ('s' == $obCategoriadeic->ativo_fl) ? 'Desativar' : 'Ativar',
        'class_ativo' => ('s' == $obCategoriadeic->ativo_fl) ? 'btn-warning' : 'btn-success',
        'style_ativo' => ('s' == $obCategoriadeic->ativo_fl) ? 'table-active' : 'table-danger',
        'icon' => ICON_CATEGORIADEIC,
        'title' =>TITLE_CATEGORIADEIC,
        'titlelow' => TITLELOW_CATEGORIADEIC,
        'direntity' => ROTA_CATEGORIADEIC
      ]);
    }
    $itens .= $strDeleteModal;
    $itens .= $strAtivaModal;
    $itens .= $strEditModal;
    $itens .= $strAddModal;
    return $itens;
  }

  /**
   * Método responsável por montar a renderização do select de Categorias de ICs para o formulário
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getCategoriadeicItensRadio($request,$id){
    $itensSelect = '';
    $resultsSelect = EntityCategoriadeic::getCategoriadeics(null,'categoria_ic_id ASC');

    while($obCategoriadeic = $resultsSelect->fetchObject(EntityCategoriadeic::class)){
      $itensSelect .= View::render('admin/modules/'.DIR_CATEGORIADEIC.'/itemradio',[
        'idSelect' => $obCategoriadeic->categoria_ic_id,
        'checked' => ($id == $obCategoriadeic->categoria_ic_id) ? 'checked' : '',
        'icon' => $obCategoriadeic->categoria_ic_icon,
        'style' => $obCategoriadeic->categoria_ic_style,
        'titulo' => $obCategoriadeic->categoria_ic_titulo,
        'descricao' => $obCategoriadeic->categoria_ic_descricao
      ]);
    }
    return $itensSelect;
  }

  /**
   * Método responsável por montar a renderização do select de Categorias de ICs para o formulário
   * @param Request $request
   * @param integer $id
   * @return string
   */
   public static function getCategoriadeicItensSelect($request,$id){
      $itensSelect = '';
      $resultsSelect = EntityCategoriadeic::getCategoriadeics(null,'categoria_ic_id ASC');

      while($obCategoriadeic = $resultsSelect->fetchObject(EntityCategoriadeic::class)){
        $itensSelect .= View::render('admin/modules/'.DIR_CATEGORIADEIC.'/itemselect',[
          'idSelect' => $obCategoriadeic->categoria_ic_id,
          'selecionado' => ($id == $obCategoriadeic->categoria_ic_id) ? 'selected' : '',
          'nomeSelect' => $obCategoriadeic->categoria_ic_titulo
        ]);
      }
      return $itensSelect;
    }

   /**
    * Método responsável por cadastro de uma nova Localização no banco
    * @param Request $request
    * @return string
    */
    public static function setNovaCategoriadeic($request){

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
      //NOVA ISNTANCIA DE DEPARTAMENTO
      $obCategoriadeic = new EntityCategoriadeic;

      $obCategoriadeic->categoria_ic_nm = $nome;
      $obCategoriadeic->categoria_ic_descricao = $descricao;
      $obCategoriadeic->cadastrar();

      //REDIRECIONA O DEPARTAMENTO
      $request->getRouter()->redirect('/admin/categoriadeics?status=gravado&nm='.$nome.'&acao=alter');
    }

   /**
    * Método responsável por gravar a edição de uma Localização
    * @param Request $request
    * @param integer $id
    * @return string
    */
    public static function setEditCategoriadeic($request,$id){

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
      $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
      $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
      $icone = filter_input(INPUT_POST, 'icone', FILTER_SANITIZE_STRING);
      $estilo = filter_input(INPUT_POST, 'estilo', FILTER_SANITIZE_STRING);

      //OBTÉM O USUÁRIO DO BANCO DE DADOS
      $obCategoriadeic = EntityCategoriadeic::getCategoriadeicPorId($id);

      if(!$obCategoriadeic instanceof EntityCategoriadeic){
        $request->getRouter()->redirect('/admin/categoriadeics/'.$id.'/edit?status=updatefail');
      }

      //ATUALIZA A INSTANCIA
      $obCategoriadeic->categoria_ic_id = $id;
      $obCategoriadeic->categoria_ic_nm = $nome;
      $obCategoriadeic->categoria_ic_tilulo = $titulo;
      $obCategoriadeic->categoria_ic_descricao = $descricao;
      $obCategoriadeic->categoria_ic_icon = $icone;
      $obCategoriadeic->categoria_ic_style = $estilo;
      $obCategoriadeic->atualizar();

      //REDIRECIONA O USUÁRIO PARA A PAGINA INICIAL DE LISTAR CATEGORIAS DE IC's
      $request->getRouter()->redirect('/admin/categoriadeics?status=alterado&nm='.$nome.'&acao=alter');
    }

    /**
     * Método responsável por retornar o formulário de alteração de status de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function getAltStatusCategoriadeic($request,$id){

       //OBTÉM A CATEGORIA DO IC PELO SEU ID DO BANCO DE DADOS
       $obCategoriadeic = EntityCategoriadeic::getCategoriadeicPorId($id);
       $strNome = $obCategoriadeic->categoria_ic_nm;

       if(!$obCategoriadeic instanceof EntityCategoriadeic){
         $request->getRouter()->redirect('/admin/categoriadeics?status=updatefail');
       }

       //OBTÉM O USUÁRIO DO BANCO DE DADOS
       if($obCategoriadeic->ativo_fl == 's'){
         $altStatus = 'n';
         $strMsn = ' DESATIVADO ';
       } elseif (($obCategoriadeic->ativo_fl == 'n') || ($obCategoriadeic->ativo_fl == '')) {
         $strMsn = ' ATIVADO ';
         $altStatus = 's';
       }

       //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
       $obCategoriadeic->ativo_fl = $altStatus;
       $obCategoriadeic->atualizar();

       //REDIRECIONA O USUÁRIO
       $request->getRouter()->redirect('/admin/categoriadeics?status=statusupdate&nm='.$strNome.$strMsn);

     }

   /**
      * Método responsável por retornar o formulário de exclusão de um usuário atraves de um Modal
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function getDeleteCategoriadeic($request,$id){

       //OBTÉM O LOCALIZAÇÃO DO BANCO DE DADOS
        $obCategoriadeic = EntityCategoriadeic::getCategoriadeicPorId($id);
        $strNome = $obCategoriadeic->categoria_ic_nm;

        if(!$obCategoriadeic instanceof EntityCategoriadeic){
          $request->getRouter()->redirect('/admin/categoriadeics');
        }

       //EXCLUI O USUÁRIO
        $obCategoriadeic->excluir();
        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/categoriadeics?status=deletado&nm='.$strNome.$strMsn.'&acao=alter');
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
         return Alert::getSuccess('Dados d'.TITLE_CATEGORIADEIC.' <strong>'.$nm.'</strong> cadastrados com sucesso!');
         // code...
         break;
       case 'alterado':
         return Alert::getSuccess('Dados d'.TITLELOW_CATEGORIADEIC.' <strong>'.$nm.'</strong> alterados com sucesso!');
         // code...
         break;
       case 'deletado':
         return Alert::getSuccess('Registro d'.TITLE_CATEGORIADEIC.'  <strong>'.$nm.'</strong> deletado com sucesso!');
         // code...
         break;
       case 'duplicado':
         return Alert::getError('Já existe '.TITLELOW_CATEGORIADEIC.' com este nome (<strong>'.$nm.'</strong>) para outr'.TITLELOW_CATEGORIADEIC.'!');
         // code...
         break;
       case 'statusupdate':
         return Alert::getSuccess('Status d'.TITLELOW_CATEGORIADEIC.' <strong>'.$nm.'</strong> com sucesso!');
         // code...
         break;
     }
   }
}
