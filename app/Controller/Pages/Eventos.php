<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Evento as EntityEvento;
use \App\Controller\Admin\Servicos as AdminServico;
use \App\Controller\Admin\Itensconfs as AdminItensconfs;
use \App\Db\Pagination;

const DIR_EVENTO = 'evento';
const FIELD_EVENTO = 'categoria_ic';
const ROTA_EVENTO = 'eventos';
const ICON_EVENTO = 'signpost';
const TITLE_EVENTO = 'Eventos';
const TITLELOW_EVENTO = 'o evento';

class Eventos extends Page{

  /**
   * Método responsável pela renderização da view de listagem de Localizacões
   * @param Request $request
   * @return string
   */
    public static function getListEventos($request,$errorMessage = null){

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
      $content = View::render('admin/modules/'.DIR_EVENTO.'/index',[
        'icon' => ICON_EVENTO,
        'title' =>TITLE_EVENTO,
        'titlelow' => TITLELOW_EVENTO,
        'direntity' => ROTA_EVENTO,
        'itens' => self::getEventoItens($request,$obPagination),
        'status' => $status
      ]);
      //RETORNA A PÁGINA COMPLETA
      return parent::getPanel(TITLE_EVENTO.' - EMERJ',$content,ROTA_EVENTO,$currentDepartamento,$currentPerfil);
  }

  /**
   * Método responsável por obter a renderização das Categorias de ICs para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
   private static function getEventoItens($request,&$obPagination){

     $itens = '';
     $tipodeic = '';
     $idreferenciado = '';

     //RESONSÁVEL PELA RENDERIZAÇÃO DOS MODAIS DE EDIÇÃO NA PÁGINA DE LISTAGEM DE LOCALIZAÇÕES
     $strEditModal = View::render('admin/modules/'.DIR_EVENTO.'/editmodal',[]);
     $strAddModal = View::render('admin/modules/'.DIR_EVENTO.'/addmodal',[]);
     $strAtivaModal = View::render('admin/modules/'.DIR_EVENTO.'/ativamodal',[]);
     $strDeleteModal = View::render('admin/modules/'.DIR_EVENTO.'/deletemodal',[]);

     //RESULTADO DA PAGINA
     $results = EntityEvento::getEventos();

     //MONTA E RENDERIZA OS ITENS DE Evento
     while($obEvento = $results->fetchObject(EntityEvento::class)){
       $itens .= View::render('admin/modules/'.DIR_EVENTO.'/item',[
        'id' => $obEvento->codigo,
        'nome' => $obEvento->nome,
        'local' => $obEvento->local,
        'icone' => $obEvento->evento_icon,
        'estilo' => $obEvento->evento_style,
        'texto_ativo' => ('s' == $obEvento->ativo_fl) ? 'Desativar' : 'Ativar',
        'class_ativo' => ('s' == $obEvento->ativo_fl) ? 'btn-warning' : 'btn-success',
        'style_ativo' => ('s' == $obEvento->ativo_fl) ? 'table-active' : 'table-danger',
        'icon' => ICON_EVENTO,
        'title' =>TITLE_EVENTO,
        'titlelow' => TITLELOW_EVENTO,
        'direntity' => ROTA_EVENTO
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
  public static function getEventoItensRadio($request,$id){
    $itensSelect = '';
    $resultsSelect = EntityEvento::getEventos(null,'codigo ASC');

    while($obEvento = $resultsSelect->fetchObject(EntityEvento::class)){
      $itensSelect .= View::render('admin/modules/'.DIR_EVENTO.'/itemradio',[
        'idSelect' => $obEvento->codigo,
        'checked' => ($id == $obEvento->codigo) ? 'checked' : '',
        'icon' => $obEvento->evento_icon,
        'style' => $obEvento->evento_style,
        'nome' => $obEvento->nome,
        'local' => $obEvento->local
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
   public static function getEventoItensSelect($request,$id){
      $itensSelect = '';
      $resultsSelect = EntityEvento::getEventos(null,'codigo ASC');

      while($obEvento = $resultsSelect->fetchObject(EntityEvento::class)){
        $itensSelect .= View::render('admin/modules/'.DIR_EVENTO.'/itemselect',[
          'idSelect' => $obEvento->codigo,
          'selecionado' => ($id == $obEvento->codigo) ? 'selected' : '',
          'nomeSelect' => $obEvento->nome
        ]);
      }
      return $itensSelect;
    }

   /**
    * Método responsável por cadastro de uma nova Localização no banco
    * @param Request $request
    * @return string
    */
    public static function setNovaEvento($request){

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $local = filter_input(INPUT_POST, 'local', FILTER_SANITIZE_STRING);
      //NOVA ISNTANCIA DE DEPARTAMENTO
      $obEvento = new EntityEvento;

      $obEvento->nome = $nome;
      $obEvento->local = $local;
      $obEvento->cadastrar();

      //REDIRECIONA O DEPARTAMENTO
      $request->getRouter()->redirect('/admin/eventos?status=gravado&nm='.$nome.'&acao=alter');
    }

   /**
    * Método responsável por gravar a edição de uma Localização
    * @param Request $request
    * @param integer $id
    * @return string
    */
    public static function setEditEvento($request,$id){

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
      $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $local = filter_input(INPUT_POST, 'local', FILTER_SANITIZE_STRING);
      $icone = filter_input(INPUT_POST, 'icone', FILTER_SANITIZE_STRING);
      $estilo = filter_input(INPUT_POST, 'estilo', FILTER_SANITIZE_STRING);

      //OBTÉM O USUÁRIO DO BANCO DE DADOS
      $obEvento = EntityEvento::getEventoPorId($id);

      if(!$obEvento instanceof EntityEvento){
        $request->getRouter()->redirect('/admin/eventos/'.$id.'/edit?status=updatefail');
      }

      //ATUALIZA A INSTANCIA
      $obEvento->codigo = $id;
      $obEvento->nome = $nome;
      $obEvento->local = $local;
      $obEvento->evento_icon = $icone;
      $obEvento->evento_style = $estilo;
      $obEvento->atualizar();

      //REDIRECIONA O USUÁRIO PARA A PAGINA INICIAL DE LISTAR CATEGORIAS DE IC's
      $request->getRouter()->redirect('/admin/eventos?status=alterado&nm='.$nome.'&acao=alter');
    }

    /**
     * Método responsável por retornar o formulário de alteração de status de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function getAltStatusEvento($request,$id){

       //OBTÉM A CATEGORIA DO IC PELO SEU ID DO BANCO DE DADOS
       $obEvento = EntityEvento::getEventoPorId($id);
       $strNome = $obEvento->nome;

       if(!$obEvento instanceof EntityEvento){
         $request->getRouter()->redirect('/admin/eventos?status=updatefail');
       }

       //OBTÉM O USUÁRIO DO BANCO DE DADOS
       if($obEvento->ativo_fl == 's'){
         $altStatus = 'n';
         $strMsn = ' DESATIVADO ';
       } elseif (($obEvento->ativo_fl == 'n') || ($obEvento->ativo_fl == '')) {
         $strMsn = ' ATIVADO ';
         $altStatus = 's';
       }

       //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
       $obEvento->ativo_fl = $altStatus;
       $obEvento->atualizar();

       //REDIRECIONA O USUÁRIO
       $request->getRouter()->redirect('/admin/eventos?status=statusupdate&nm='.$strNome.$strMsn);

     }

   /**
      * Método responsável por retornar o formulário de exclusão de um usuário atraves de um Modal
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function getDeleteEvento($request,$id){

       //OBTÉM O LOCALIZAÇÃO DO BANCO DE DADOS
        $obEvento = EntityEvento::getEventoPorId($id);
        $strNome = $obEvento->nome;

        if(!$obEvento instanceof EntityEvento){
          $request->getRouter()->redirect('/admin/eventos');
        }

       //EXCLUI O USUÁRIO
        $obEvento->excluir();
        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/eventos?status=deletado&nm='.$strNome.$strMsn.'&acao=alter');
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
         return Alert::getSuccess('Dados d'.TITLE_EVENTO.' <strong>'.$nm.'</strong> cadastrados com sucesso!');
         // code...
         break;
       case 'alterado':
         return Alert::getSuccess('Dados d'.TITLELOW_EVENTO.' <strong>'.$nm.'</strong> alterados com sucesso!');
         // code...
         break;
       case 'deletado':
         return Alert::getSuccess('Registro d'.TITLE_EVENTO.'  <strong>'.$nm.'</strong> deletado com sucesso!');
         // code...
         break;
       case 'duplicado':
         return Alert::getError('Já existe '.TITLELOW_EVENTO.' com este nome (<strong>'.$nm.'</strong>) para outr'.TITLELOW_EVENTO.'!');
         // code...
         break;
       case 'statusupdate':
         return Alert::getSuccess('Status d'.TITLELOW_EVENTO.' <strong>'.$nm.'</strong> com sucesso!');
         // code...
         break;
     }
   }
}
