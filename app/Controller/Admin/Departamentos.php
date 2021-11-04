<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Departamento as EntityDepartamento;
use \App\Model\Entity\Atendimento as EntityAtendimento;
use \App\Controller\Admin\Departamentos as AdminDepartamentos;
use \App\Controller\Admin\Atendimento as AdminAtendimento;
use \App\Db\Pagination;

const DIR_DEPARTAMENTO = 'departamento';
const ROTA_DEPARTAMENTO = 'departamentos';
const ICON_DEPARTAMENTO = 'house-fill';
const TITLE_DEPARTAMENTO = 'Departamentos';
const TITLELOW_DEPARTAMENTO = 'o departamento';


class Departamentos extends Page{

    /**
     * Método responsável pela renderização da view de listagem de departamentos
     * @param Request $request
     * @return string
     */
     public static function getListDepartamentos($request,$errorMessage = null){

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

      $obDepartamento = AdminDepartamentos::getDepartamentoItensSelect($request,$id);

      //CONTEÚDO DA HOME
      $content = View::render('admin/modules/departamento/index',[
        'icon' => ICON_DEPARTAMENTO,
        'title' =>TITLE_DEPARTAMENTO,
        'titlelow' => TITLELOW_DEPARTAMENTO,
        'direntity' => ROTA_DEPARTAMENTO,
        'itens' => self::getDepartamentoItens($request,$obPagination),
        'status' => self::getStatus($request),
        'optionsDepartamento' => $obDepartamento
      ]);
      //RETORNA A PÁGINA COMPLETA
      return parent::getPanel('Departamentos - EMERJ',$content,'departamentos',$currentDepartamento,$currentPerfil);
    }

    /**
     * Método responsável por obter a renderização dos itens de departamentos para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
     private static function getDepartamentoItens($request,&$obPagination){

      $itens = '';

      //RESONSÁVEL PELA RENDERIZAÇÃO DOS MODAIS DE EDIÇÃO NA PÁGINA DE LISTAGEM DE LOCALIZAÇÕES
      $strEditModal = View::render('admin/modules/'.DIR_DEPARTAMENTO.'/editmodal',[]);
      $strAddModal = View::render('admin/modules/'.DIR_DEPARTAMENTO.'/addmodal',[]);
      $strAtivaModal = View::render('admin/modules/'.DIR_DEPARTAMENTO.'/ativamodal',[]);
      $strDeleteModal = View::render('admin/modules/'.DIR_DEPARTAMENTO.'/deletemodal',[]);

      //RESULTADO DA PAGINA
      $results = EntityDepartamento::getDepartamentos();

      //MONTA E RENDERIZA OS ITENS DE DEPARTAMENTO
      while($obDepartamento = $results->fetchObject(EntityDepartamento::class)){
         $itens .= View::render('admin/modules/departamento/item',[
           'id' => $obDepartamento->departamento_id,
           'nome' => $obDepartamento->departamento_nm,
           'sigla' => $obDepartamento->departamento_sg,
           'prot_nr' => $obDepartamento->prot_nr,
           'descricao' => $obDepartamento->departamento_des,
           'departamento_pai_sg' => EntityDepartamento::getDepartamentoPorId($obDepartamento->cod_dep_super)->departamento_sg,
           'departamento_pai_nm' => EntityDepartamento::getDepartamentoPorId($obDepartamento->cod_dep_super)->departamento_nm,
           'departamento_pai_id' => $obDepartamento->cod_dep_super,
           'texto_ativo' => ('s' == $obDepartamento->ativo_fl) ? 'Desativar' : 'Ativar',
           'class_ativo' => ('s' == $obDepartamento->ativo_fl) ? 'btn-warning' : 'btn-success',
           'style_ativo' => ('s' == $obDepartamento->ativo_fl) ? 'table-active' : 'table-danger',
           'icon' => ICON_DEPARTAMENTO,
           'title' =>TITLE_DEPARTAMENTO,
           'titlelow' => TITLELOW_DEPARTAMENTO,
           'direntity' => ROTA_DEPARTAMENTO
         ]);
       }
       $itens .= $strDeleteModal;
       $itens .= $strAtivaModal;
       $itens .= $strEditModal;
       $itens .= $strAddModal;
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
         $itensSelect .= View::render('admin/modules/departamento/itemselect',[
           'idSelect' => $obDepartamento->departamento_id,
           'selecionado' => ($id == $obDepartamento->departamento_id) ? 'selected' : '',
           'siglaSelect' => $obDepartamento->departamento_sg.' - '.$obDepartamento->departamento_nm
         ]);
       }
       return $itensSelect;
     }


    /**
     * Método responsável por montar a renderização do select de departamentos para o formulário
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function getDepartamentoItensSelectChamados($request,$id){
        $itensSelect = '';
        $where = '';
        $departamento = '';

        $queryParams = $request->getQueryParams();
        $departamento = $queryParams['departamento'] ?? 0;

        if($id <> 0){
          $where = 'id_departamento = '.$id;
        }

        $resultsSelect = EntityAtendimento::getAtendimentos($where,'atendimento_id ',null,'DISTINCT id_departamento');

        while($obAtendimento = $resultsSelect->fetchObject(EntityDepartamento::class)){
          $itensSelect .= View::render('admin/modules/departamento/itemselect',[
            'idSelect' => $obAtendimento->id_departamento,
            'selecionado' => ($departamento == $obAtendimento->id_departamento) ? 'selected' : '',
            'siglaSelect' => EntityDepartamento::getDepartamentoPorId($obAtendimento->id_departamento)->departamento_nm
          ]);
        }
        return $itensSelect;
    }

     /**
      * Método responsável por cadastro de um novo usuário no banco
      * @param Request $request
      * @return string
      */
      public static function setNovoDepartamento($request){

        //DADOS DO POST
        $posVars = $request->getPostVars();
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING) ?? '';
        $sigla = filter_input(INPUT_POST, 'sigla', FILTER_SANITIZE_STRING) ?? '';
        $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING) ?? '';
        $departamento = filter_input(INPUT_POST, 'departamento', FILTER_SANITIZE_STRING) ?? '';
        $prot_nr = filter_input(INPUT_POST, 'prot_nr', FILTER_SANITIZE_STRING) ?? '';

        if ($prot_nr <> ''){
          $obDepartamentoVerProt = EntityDepartamento::getDepartamentoPorProt($prot_nr);;

          if($obDepartamentoVerProt instanceof EntityDepartamento){
            //REDIRECIONA SE O E-MAIL JÁ FOR CADASTRADO NO BANCO
            $request->getRouter()->redirect('/admin/departamentos?status=ptduplicado&nm='.$prot_nr.'&acao=gravar');
          }
        }

        //NOVA ISNTANCIA DE DEPARTAMENTO
        $obDepartamento = new EntityDepartamento;

        $obDepartamento->departamento_nm = $nome;
        $obDepartamento->departamento_sg = strtoupper($sigla);
        $obDepartamento->departamento_des = $descricao;
        $obDepartamento->cod_dep_super = $departamento;
        $obDepartamento->prot_nr = $prot_nr;
        $obDepartamento->cadastrar();

        //REDIRECIONA O DEPARTAMENTO
        $request->getRouter()->redirect('/admin/departamentos?status=gravado&nm='.$nome.'&acao=alter');

      }

       /**
        * Método responsável por gravar a edição de um usuário
        * @param Request $request
        * @param integer $id
        * @return string
        */
        public static function setEditDepartamento($request, $id){

          //DADOS DO POST
          $posVars = $request->getPostVars();
          $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT) ?? '';
          $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING) ?? '';
          $sigla = filter_input(INPUT_POST, 'sigla', FILTER_SANITIZE_STRING) ?? '';
          $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING) ?? '';
          $departamento = filter_input(INPUT_POST, 'departamento', FILTER_SANITIZE_STRING) ?? '';
          $prot_nr = filter_input(INPUT_POST, 'prot_nr', FILTER_SANITIZE_STRING) ?? '';

          if ($prot_nr <> ''){
            $obDepartamentoVerProt = EntityDepartamento::getDepartamentoPorProt($prot_nr);



            if($obDepartamentoVerProt instanceof EntityDepartamento) {
              if ($obDepartamentoVerProt->departamento_id != $id) {
                //REDIRECIONA SE O E-MAIL JÁ FOR CADASTRADO NO BANCO
                $request->getRouter()->redirect('/admin/departamentos?status=ptduplicado&nm='.$prot_nr.'&acao=gravar');
              }
            }
          }

          //OBTÉM O DEPARTAMENTO DO BANCO DE DADOS
          $obDepartamento = EntityDepartamento::getDepartamentoPorId($id);

          if(!$obDepartamento instanceof EntityDepartamento){
            $request->getRouter()->redirect('/admin/departamentos?status=updatefail');
          }

          //ATUALIZA A INSTANCIA
          $obDepartamento->departamento_nm = $nome ?? $obDepartamento->departamento_nm;
          $obDepartamento->departamento_sg = strtoupper($sigla) ?? strtoupper($obDepartamento->departamento_sg);
          $obDepartamento->departamento_des = $descricao ?? $obDepartamento->departamento_des;
          $obDepartamento->prot_nr = $prot_nr ?? $obDepartamento->prot_nr;
          $obDepartamento->cod_dep_super = $departamento ?? $obDepartamento->cod_dep_super;
          $obDepartamento->atualizar();

          //REDIRECIONA O DEPARTAMENTO
          $request->getRouter()->redirect('/admin/departamentos?status=alterado');

       }

    /**
     * Método responsável por retornar o formulário de alteração de status de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function getAltStatusDepartamentoModal($request,$id){

       //OBTÉM O DEPARTAMENTO DO BANCO DE DADOS
       $obDepartamento = EntityDepartamento::getDepartamentoPorId($id);

       //PÁGINA ATUAL
       $queryParams = $request->getQueryParams();
       $paginaAtual = $queryParams['pagina'] ?? 1;

       if(!$obDepartamento instanceof EntityDepartamento){
         $request->getRouter()->redirect('/admin/departamentos?status=updatefail');
       }

       //CONTEÚDO DO FORMULÁRIO
       $content = View::render('admin/modules/departamentos/alterastatus',[
         'status' => self::getStatus($request),
         'paginaAtual' => $paginaAtual
       ]);

       //OBTÉM O DEPARTAMENTO DO BANCO DE DADOS
       if($obDepartamento->ativo_fl == 's'){
         $altStatus = 'n';
       } elseif ($obDepartamento->ativo_fl == 'n') {
         $altStatus = 's';
       } elseif ($obDepartamento->ativo_fl == '') {
         $altStatus = 's';
       }

       //ATUALIZA A INSTANCIA (RESETA A SENHA DO DEPARTAMENTO)
       $obDepartamento->ativo_fl = $altStatus;
       $obDepartamento->atualizar();

       //REDIRECIONA O DEPARTAMENTO
       $request->getRouter()->redirect('/admin/departamentos?status=statusupdate');
     }


    /**
     * Método responsável por retornar o formulário de exclusão de um usuário atraves de um Modal
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function getDeleteDepartamentoModal($request,$id){

       //OBTÉM O DEPARTAMENTO DO BANCO DE DADOS
       $obDepartamento = EntityDepartamento::getDepartamentoPorId($id);

       $queryParams = $request->getQueryParams();
       $paginaAtual = $queryParams['pagina'] ?? 1;

       if(!$obDepartamento instanceof EntityDepartamento){
         $request->getRouter()->redirect('/admin/departamentos');
       }
      //EXCLUI O DEPARTAMENTO
       $obDepartamento->excluir();
       //REDIRECIONA O DEPARTAMENTO
       $request->getRouter()->redirect('/admin/departamentos?status=deletado');
     }

    /**
     * Método responsável por excluir um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function setDeleteDepartamento($request,$id){

       //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
       $obDepartamento = EntityDepartamento::getDepartamentoPorId($id);

       if(!$obDepartamento instanceof EntityDepartamento){
         $request->getRouter()->redirect('/admin/departamentos');
       }
       //EXCLUI O DEPARTAMENTO
       $obDepartamento->excluir();
       //REDIRECIONA O DEPARTAMENTO
       $request->getRouter()->redirect('/admin/departamentos?status=deletado');

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

    //STATUS
    if(!isset($queryParams['status'])) return '';

   //MENSAGENS DE STATUS
   switch ($queryParams['status']) {
     case 'gravado':
       return Alert::getSuccess('Departamento cadastrado com sucesso!');
       // code...
       break;
     case 'alterado':
       return Alert::getSuccess('Dados do departamento alterados com sucesso!');
       // code...
       break;
     case 'deletado':
       return Alert::getSuccess('Departamento deletado com sucesso!');
       // code...
       break;
     case 'ptduplicado':
       return Alert::getError('Já existe um departamento cadastrado com este número de PROT (<strong>'.$nm.'</strong>)!');
       // code...
       break;
     case 'duplicado':
       return Alert::getError('Já existe um departamento cadastrado com este e-mail!');
       // code...
       break;
     case 'senharesetada':
       return Alert::getSuccess('Senha do departamento resetada com sucesso!');
       // code...
       break;
     case 'statusupdate':
       return Alert::getSuccess('Status do departamento alterado com sucesso!');
       // code...
       break;
   }
  }

}
