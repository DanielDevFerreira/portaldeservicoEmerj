<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Andamento as EntityAndamento;
use \App\Model\Entity\Servico as EntityServico;
use \App\Model\Entity\Itensconf as EntityItensconf;
use \App\Model\Entity\Departamento as EntityDepartamento;
use \App\Controller\Pages\Departamento as PagesDepartamento;
use \App\Controller\Admin\Servicos as AdminServico;
use \App\Controller\Admin\Itensconfs as AdminItensconfs;
use \App\Db\Pagination;

class Andamentos extends Page{

  /**
   * Método responsável por obter a renderização dos itens de Andamentos para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getAndamentoItens($request,&$obPagination){
    $itens = '';

  }

  /**
   * Método responsável pela renderização da view de listagem de Andamentos
   * @param Request $request
   * @return string
   */
  public static function getListAndamentos($request,$errorMessage = null){

  }

  /**
   * Método responsável por retornar o formulário de cadastro de um novo Andamento
   * @param Request $request
   * @return string
   */
   public static function getNovoAndamento($request){

     
   }

   /**
    * Método responsável por cadastro de um novo item de configuração no banco
    * @param Request $request
    * @return string
    */
    public static function setNovoAndamento($request){


    }


    /**
     * Método responsável por retornar o formulário de cadastro de um novo item de configuração
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function getEditAndamento($request,$id){


     }

     /**
      * Método responsável por montar a renderização do checkbox de Itens de configuração para os formulários
      * @param Request $request
      * @param integer $id
      * @return string
      */
     public static function getAndamentoItensCheckbox($request,$id){


     }


     /**
      * Método responsável por gravar a edição de um item de configuração
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function setEditAndamento($request,$id){


      //  echo "<pre>"; print_r($id); echo "<pre>"; exit;

        //DADOS DO POST
        $posVars = $request->getPostVars();
        $servico_id = $posVars['servico_id'] ?? '';
        $itemdeconfiguracao_id = $posVars['itemdeconfiguracao_id'] ?? '';

        $where = " id_servico = ".$servico_id." AND id_itemdeconfiguracao = ".$itemdeconfiguracao_id;

        //echo "<pre>"; print_r($where); echo "<pre>"; exit;
        //VERIFICA SE JÁ EXISTE O IC com mesmo nome e tipo CADASTRADO NO BANCO
        $obAndamento = EntityAndamento::getAndamentos($where);

      //  echo "<pre>"; print_r($obAndamento); echo "<pre>"; exit;
        if($obAndamento instanceof EntityAndamento){
          //REDIRECIONA SE O E-MAIL JÁ FOR CADASTRADO NO BANCO
          $request->getRouter()->redirect('/admin/andamentos/novo?status=duplicado');
        }

        //OBTÉM O USUÁRIO DO BANCO DE DADOS
        $obAndamento = EntityAndamento::getAndamentoPorId($id);

        if(!$obAndamento instanceof EntityAndamento){

          //echo "<pre>"; print_r($id); echo "<pre>"; exit;
          $request->getRouter()->redirect('/admin/andamentos/'.$id.'/edit?status=updatefail');
        }

        //echo "<pre>"; print_r($id_servico); echo "<pre>"; exit;

        //ATUALIZA A INSTANCIA
        $obAndamento->id_servico = $posVars['servico_id'];
        $obAndamento->id_itemdeconfiguracao = $posVars['itemdeconfiguracao_id'];
        $obAndamento->id_departamento = $posVars['departamento_id'];
        $obAndamento->atualizar();

        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/andamentos/'.$obAndamento->andamento_id.'/edit?status=alterado');
      }

      /**
       * Método responsável por retornar o formulário de alteração de status de um usuário
       * @param Request $request
       * @param integer $id
       * @return string
       */
       public static function getAltStatusAndamentoModal($request,$id){

         //OBTÉM O USUÁRIO DO BANCO DE DADOS
         $obAndamento = EntityAndamento::getAndamentoPorId($id);

         //PÁGINA ATUAL
         $uri=strstr("$_SERVER[REQUEST_URI]", '?');

         if(!$obAndamento instanceof EntityAndamento){
           $request->getRouter()->redirect('/admin/andamentos?status=updatefail');
         }

         //CONTEÚDO DO FORMULÁRIO
         $content = View::render('admin/modules/andamento/alterastatus',[
           'status' => self::getStatus($request),
           'paginaAtual' => $paginaAtual

         ]);

         //OBTÉM O USUÁRIO DO BANCO DE DADOS
         if($obAndamento->ativo_fl == 's'){
           $altStatus = 'n';
         } elseif ($obAndamento->ativo_fl == 'n') {
           $altStatus = 's';
         }

         //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
         $obAndamento->ativo_fl = $altStatus;
         $obAndamento->atualizar();

         //REDIRECIONA O USUÁRIO
         $request->getRouter()->redirect('/admin/andamentos'.$uri.'&status=statusupdate');

       }


      /**
       * Método responsável por retornar o formulário de exclusão de um usuário
       * @param Request $request
       * @param integer $id
       * @return string
       */
       public static function getDeleteAndamento($request,$id){

      //   echo "<pre>BBBBB"; print_r($id); echo "<pre>"; exit;


         //OBTÉM O USUÁRIO DO BANCO DE DADOS
         $obAndamento = EntityAndamento::getAndamentoPorId($id);

         if(!$obAndamento instanceof EntityAndamento){
           $request->getRouter()->redirect('/admin/andamento');
         }

       //CONTEÚDO DA FORMULÁRIO
         $content = View::render('admin/modules/andamento/delete',[
           'andamento_id' => $obAndamento->andamento_id,
           'status' => self::getStatus($request)
         ]);


         //RETORNA A PÁGINA COMPLETA
         return parent::getPanel('Exclir IC',$content,'andamentos');
       }

       /**
        * Método responsável por retornar o formulário de exclusão de um usuário atraves de um Modal
        * @param Request $request
        * @param integer $id
        * @return string
        */
        public static function getDeleteAndamentoModal($request,$id){

        //  echo "<pre>ALOOIII"; print_r($id); echo "<pre>"; exit;

        $obAndamento = EntityAndamento::getAndamentoPorId($id);

        //CONTEÚDO DA FORMULÁRIO
          $content = View::render('admin/modules/andamento/delete',[
            'servico' => EntityServico::getServicoPorId($obAndamento->id_servico)->servico_nm,
            'itemdeconfiguracao' => EntityItensconf::getItensconfPorId($obAndamento->id_itemdeconfiguracao)->itemdeconfiguracao_nm,
            'departamento' => EntityDepartamento::getDepartamentoPorId($obAndamento->id_departamento)->departamento_sg,
            'status' => self::getStatus($request)
          ]);

          //OBTÉM O USUÁRIO DO BANCO DE DADOS
          $obAndamento = EntityAndamento::getAndamentoPorId($id);

          $queryParams = $request->getQueryParams();
          $paginaAtual = $queryParams['pagina'] ?? 1;

          if(!$obAndamento instanceof EntityAndamento){
            $request->getRouter()->redirect('/admin/andamentos');
          }

         //EXCLUI O USUÁRIO
          $obAndamento->excluir();
          //REDIRECIONA O USUÁRIO
          $request->getRouter()->redirect('/admin/andamentos?pagina='.$paginaAtual.'&status=deletado');
        }

       /**
        * Método responsável por excluir um usuário
        * @param Request $request
        * @param integer $id
        * @return string
        */
        public static function setDeleteAndamento($request,$id){



       }



  /**
   * Método responsável por retornar a mensagem de status
   * @param Request $request
   * @return string
   */
  private static function getStatus($request){
    //QUERY PARAMS
    $queryParams = $request->getQueryParams();

    //STATUS
    if(!isset($queryParams['status'])) return '';

   //MENSAGENS DE STATUS
   switch ($queryParams['status']) {
     case 'gravado':
       return Alert::getSuccess('Andamento cadastrado com sucesso!');
       // code...
       break;
     case 'alterado':
       return Alert::getSuccess('Dados do Andamento alterados com sucesso!');
       // code...
       break;
     case 'deletado':
       return Alert::getSuccess('Andamento deletado com sucesso!');
       // code...
       break;
     case 'duplicado':
       return Alert::getError('Já existe um Andamento com este nome para o mesmo Departamento!');
       // code...
       break;
     case 'statusupdate':
       return Alert::getSuccess('Status do Andamento alterado com sucesso!');
       // code...
       break;
   }
  }
}
