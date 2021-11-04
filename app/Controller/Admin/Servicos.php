<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Servico as EntityServico;
use \App\Model\Entity\Tipodeservico as EntityTipodeservico;
use \App\Model\Entity\Atendimento as EntityAtendimento;
use \App\Controller\Admin\Tipodeservicos as AdminTipodeservico;
use \App\Db\Pagination;

const DIR_SERVICO = 'servico';
const ROTA_SERVICO = 'servicos';
const ICON_SERVICO = 'basket';
const TITLE_SERVICO = 'Serviços';
const TITLELOW_SERVICO = 'o serviço';

class Servicos extends Page{

  /**
   * Método responsável pela renderização da view de listagem de Serviços
   * @param Request $request
   * @return string
   */
  public static function getListServicos($request,$errorMessage = null){

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

    $status = self::getStatus($request);

    $tipodeservicoSelecionado = AdminTipodeservico::getTipodeservicoItensSelect($request,$id) ?? '';

    //CONTEÚDO DA HOME
    $content = View::render('admin/modules/'.DIR_SERVICO.'/index',[
      'icon' => ICON_SERVICO,
      'title' =>TITLE_SERVICO,
      'titlelow' => TITLELOW_SERVICO,
      'direntity' => ROTA_SERVICO,
      'itens' => self::getServicoItens($request,$obPagination),
      'status' => self::getStatus($request),
      'optionsTipodeservico' => $tipodeservicoSelecionado
    ]);

    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel(TITLE_SERVICO.' - EMERJ',$content,ROTA_SERVICO,$currentDepartamento,$currentPerfil);
  }

  /**
   * Método responsável por obter a renderização dos itens de Servicos para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getServicoItens($request,&$obPagination){

    $itens = '';

    //RESONSÁVEL PELA RENDERIZAÇÃO DOS MODAIS DE EDIÇÃO NA PÁGINA DE LISTAGEM DE LOCALIZAÇÕES
    $strEditModal = View::render('admin/modules/'.DIR_SERVICO.'/editmodal',[]);
    $strAddModal = View::render('admin/modules/'.DIR_SERVICO.'/addmodal',[]);
    $strAtivaModal = View::render('admin/modules/'.DIR_SERVICO.'/ativamodal',[]);
    $strDeleteModal = View::render('admin/modules/'.DIR_SERVICO.'/deletemodal',[]);

    //RESULTADO DA PAGINA
    $results = EntityServico::getServicos();

    //MONTA E RENDERIZA OS ITENS DE Servico
    while($obServico = $results->fetchObject(EntityServico::class)){
      $itens .= View::render('admin/modules/'.DIR_SERVICO.'/item',[
        'id' => $obServico->servico_id,
        'nome' => $obServico->servico_nm,
        'descricao' => $obServico->servico_des,
        'tipodeservico' => EntityTipodeservico::getTipodeservicoPorId($obServico->id_tipodeservico)->tipodeservico_nm,
        'id_tipodeservico' => $obServico->id_tipodeservico,
        'texto_ativo' => ('s' == $obServico->ativo_fl) ? 'Desativar' : 'Ativar',
        'class_ativo' => ('s' == $obServico->ativo_fl) ? 'btn-warning' : 'btn-success',
        'style_ativo' => ('s' == $obServico->ativo_fl) ? 'table-active' : 'table-danger',
        'icon' => ICON_SERVICO,
        'title' =>TITLE_SERVICO,
        'titlelow' => TITLELOW_SERVICO,
        'direntity' => ROTA_SERVICO
      ]);
    }
    $itens .= $strDeleteModal;
    $itens .= $strAtivaModal;
    $itens .= $strEditModal;
    $itens .= $strAddModal;
    return $itens;
  }

   /**
    * Método responsável por cadastro de um novo item de configuração no banco
    * @param Request $request
    * @return string
    */
    public static function setNovoServico($request){

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
      $tipodeservico = filter_input(INPUT_POST, 'tipodeservico', FILTER_SANITIZE_NUMBER_INT);

      $where = ' servico_nm = "'.$nome.'" AND id_tipodeservico = '.$tipodeservico;

      //VERIFICA SE JÁ EXISTE O IC com mesmo nome e tipo CADASTRADO NO BANCO
      $obServicoVer = EntityServico::getServicos($where)->fetchColumn();

      if($obServicoVer > 0){
        //REDIRECIONA SE O E-MAIL JÁ FOR CADASTRADO NO BANCO
        $request->getRouter()->redirect('/admin/'.ROTA_SERVICO.'?status=duplicado&nm='.$nome.'&acao=alter');
      }

      //NOVA ISNTANCIA DE IC
      $obServico = new EntityServico;

      ////$obServico::getServicoPorEmail($posVars['email']);
      $obServico->servico_nm = $nome;
      $obServico->servico_des = $descricao;
      $obServico->ativo_fl = 's';
      $obServico->id_tipodeservico = $tipodeservico;
      $obServico->cadastrar();


      //REDIRECIONA O USUÁRIO
      $request->getRouter()->redirect('/admin/'.ROTA_SERVICO.'?pagina='.$paginaAtual.'&status=gravado&nm='.$nome.'&acao=alter');

    }




     /**
      * Método responsável por gravar a edição de um item de configuração
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function setEditServico($request,$id){

        //DADOS DO POST
        $posVars = $request->getPostVars();
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);;
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
        $tipodeservico = filter_input(INPUT_POST, 'tipodeservico', FILTER_SANITIZE_STRING);

        $where = ' servico_nm = "'.$nome.'" AND id_tipodeservico = '.$tipodeservico;

        //VERIFICA SE JÁ EXISTE O IC com mesmo nome e tipo CADASTRADO NO BANCO
        $obServicoVer = EntityServico::getServicos($where)->fetchColumn();

        if($obServicoVer > 0){
          //echo "<pre>"; print_r($id); echo "<pre>"; exit;
          $request->getRouter()->redirect('/admin/servicos?status=updatefail');
        }

        //OBTÉM O USUÁRIO DO BANCO DE DADOS
        $obServico = EntityServico::getServicoPorId($id);

        if(!$obServico instanceof EntityServico){
          $request->getRouter()->redirect('/admin/'.ROTA_SERVICO.'?status=updatefail');
        }

        //ATUALIZA A INSTANCIA
        $obServico->servico_nm = $nome;
        $obServico->servico_des = $descricao;
        $obServico->id_tipodeservico = $tipodeservico;
        $obServico->atualizar();

        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/'.ROTA_SERVICO.'?status=alterado&nm='.$nome.'&acao=alter');
      }

      /**
       * Método responsável por retornar o formulário de alteração de status de um usuário
       * @param Request $request
       * @param integer $id
       * @return string
       */
       public static function getAltStatusServico($request,$id){

         //PÁGINA ATUAL
         $queryParams = $request->getQueryParams();
         $paginaAtual = $queryParams['pagina'] ?? 1;

         //OBTÉM O USUÁRIO DO BANCO DE DADOS
         $obServico = EntityServico::getServicoPorId($id);
         $strNome = $obServico->servico_nm;


         //PÁGINA ATUAL
         $uri=strstr("$_SERVER[REQUEST_URI]", '?');

         if(!$obServico instanceof EntityServico){
           $request->getRouter()->redirect('/admin/servicos?status=updatefail');
         }

         //CONTEÚDO DO FORMULÁRIO
         $content = View::render('admin/modules/servico/alterastatus',[
           'status' => self::getStatus($request),
           'paginaAtual' => $paginaAtual

         ]);

         //OBTÉM O USUÁRIO DO BANCO DE DADOS
         if($obServico->ativo_fl == 's'){
           $altStatus = 'n';
           $strMsn = ' DESATIVADO ';
         } elseif (($obServico->ativo_fl == 'n') || ($obServico->ativo_fl == '')) {
           $strMsn = ' ATIVADO ';
           $altStatus = 's';
         }

         //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
         $obServico->ativo_fl = $altStatus;
         $obServico->atualizar();

         //REDIRECIONA O USUÁRIO
         $request->getRouter()->redirect('/admin/'.ROTA_SERVICO.'?status=statusupdate&nm='.$strNome.$strMsn.'&acao=alter');

       }

       /**
        * Método responsável por montar a renderização do select de Tipo de Serviço para os formulários
        * @param Request $request
        * @param integer $id
        * @return string
        */
       public static function getTipodeservicoItensSelect($request,$id){


         $resultsSelect = EntityTipodeservico::getServicos(null,'servico_nm',null,null);

         while($obTipodeservico = $resultsSelect->fetchObject(EntityAtendimento::class)){
           $itensSelect .= View::render('admin/modules/tipodeic/itemselect',[
             'idSelect' => $obTipodeservico->id_tipodeservico,
             'selecionado' => ($id_tipodeservico == $obTipodeservico->id_tipodeservico) ? 'selected' : '',
             'nomeSelect' => EntityTipodeservico::getTipodeservicoPorId($obTipodeservico->id_tipodeservico)->tipodeservico_nm
           ]);
         }
         //echo "<pre>"; print_r($itensSelect); echo "<pre>"; exit;
         return $itensSelect;
       }


       /**
        * Método responsável por montar a renderização do select de Tipo de Serviço para os formulários
        * @param Request $request
        * @param integer $id
        * @return string
        */
       public static function getServicoItensSelectChamados($request,$tipodeservico,$departamento){
         $itensSelect = '';
         $where = null;
         $id_tipodeservico = $tipodeservico;
         $id_departamento = $departamento;


         $queryParams = $request->getQueryParams();
         $id_tipodeservico = $queryParams['tipodeservico'] ?? 1;
         $id_servico = $queryParams['servico'] ?? 0;
         $id_departamento = $queryParams['departamento'] ?? 0;


         if($id_tipodeservico <> 0){
           $where = 'tipodeservico_id = '.$id_tipodeservico;
           if($id_departamento <> 0){
             $where = $where .' AND id_departamento = '.$id_departamento;
           }
         } else {
           if($id_departamento <> 0){
             $where = 'id_departamento = '.$id_departamento;
           }
         }

         $where = ' id_tipodeservico = '.$id_tipodeservico;



         //echo "<pre>"; print_r($where); echo "<pre>";

         //SELECT * FROM tb_tipodeservico, tb_atendimento, tb_servico WHERE tb_atendimento.id_servico = tb_servico.servico_id AND tipodeservico_id = id_tipodeservico AND tipodeservico_id = 1 AND  id_departamento = 5 ORDER BY atendimento_id

         $resultsSelect = EntityServico::getServicos($where,null);

         while($obServico = $resultsSelect->fetchObject(EntityAtendimento::class)){
           $itensSelect .= View::render('admin/modules/servico/itemselect',[
             'idSelect' => $obServico->id_servico,
             'selecionado' => ($id_servico == $obServico->id_servico) ? 'selected' : '',
             'nomeSelect' => $obServico->servico_nm
           ]);
         }
         //echo "<pre>"; print_r($itensSelect); echo "<pre>"; exit;
         return $itensSelect;
       }


       /**
        * Método responsável por montar a renderização do select de Tipo de Serviço para os formulários
        * @param Request $request
        * @param integer $id
        * @return string
        */
       public static function getServicoItensSelect($request,$id){
         $itensSelect = '';
         $resultsSelect = EntityServico::getServicos(null,'servico_nm ASC ');

         while($obServico = $resultsSelect->fetchObject(EntityServico::class)){
           $itensSelect .= View::render('admin/modules/servico/itemselect',[
             'idSelect' => $obServico->servico_id,
             'selecionado' => ($id == $obServico->servico_id) ? 'selected' : '',
             'nomeSelect' => $obServico->servico_nm.' - ('.EntityTipodeservico::getTipodeservicoPorId($obServico->id_tipodeservico)->tipodeservico_nm.')'
           ]);
         }
         return $itensSelect;
       }

     /**
      * Método responsável por retornar o formulário de exclusão de um usuário atraves de um Modal
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function getDeleteServico($request,$id){

        //OBTÉM O USUÁRIO DO BANCO DE DADOS
        $obServico = EntityServico::getServicoPorId($id);
        $strNome = $obServico->servico_nm;

        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['pagina'] ?? 1;

        if(!$obServico instanceof EntityServico){
          $request->getRouter()->redirect('/admin/servicos');
        }

       //EXCLUI O USUÁRIO
        $obServico->excluir();
        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/'.ROTA_SERVICO.'?status=deletado&nm='.$strNome.'&acao=alter');
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
        return Alert::getSuccess('Dados d'.TITLE_SERVICO.' <strong>'.$nm.'</strong> cadastrados com sucesso!');
        // code...
        break;
      case 'alterado':
        return Alert::getSuccess('Dados d'.TITLELOW_SERVICO.' <strong>'.$nm.'</strong> alterados com sucesso!');
        // code...
        break;
      case 'deletado':
        return Alert::getSuccess('Registro d'.TITLE_SERVICO.'  <strong>'.$nm.'</strong> deletado com sucesso!');
        // code...
        break;
      case 'duplicado':
        return Alert::getError('Já existe '.TITLELOW_SERVICO.' com este nome (<strong>'.$nm.'</strong>) para outr'.TITLELOW_SERVICO.' do mesmo tipo de serviço!');
        // code...
        break;
      case 'statusupdate':
        return Alert::getSuccess('Status d'.TITLELOW_SERVICO.' <strong>'.$nm.'</strong> alterado com sucesso!');
        // code...
        break;
   }
  }
}
