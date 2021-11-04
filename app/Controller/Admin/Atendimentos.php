<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Atendimento as EntityAtendimento;
use \App\Model\Entity\Servico as EntityServico;
use \App\Model\Entity\Tipodeservico as EntityTipodeservico;
use \App\Model\Entity\Tipodeic as EntityTipodeic;
use \App\Model\Entity\Departamento as EntityDepartamento;
use \App\Model\Entity\Itensconf as EntityItensconf;
use \App\Controller\Admin\Departamentos as AdminDepartamentos;
use \App\Controller\Admin\Servicos as AdminServicos;
use \App\Controller\Admin\Tipodeservicos as AdminTipodeServicos;
use \App\Controller\Admin\Tipodeics as AdminTipodeics;
use \App\Controller\Admin\Itensconfs as AdminItensconfs;
use \App\Db\Pagination;


const DIR_ATENDIMENTO = 'atendimento';
const ROTA_ATENDIMENTO = 'atendimentos';
const ICON_ATENDIMENTO = 'chat-right';
const TITLE_ATENDIMENTO = 'Atendimentos';
const TITLELOW_ATENDIMENTO = 'o atendimento';

class Atendimentos extends Page{

  /**
   * Método responsável pela renderização da view de listagem de Atendimentos
   * @param Request $request
   * @return string
   */
  public static function getListAtendimentos($request,$errorMessage = null){

    $id = '';
    $id_departamento = '';
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
    $id_departamento = strlen($id_departamento) > 0 ? $id_departamento : $_SESSION['admin']['usuario']['id_departamento'];

    $tipodeICSelecionado = AdminItensconfs::getItensconfItensSelectAtendimento($request,$id);
    $servicoSelecionado = AdminServicos::getServicoItensSelect($request,$id);
    $tipoDeServicoSelecionado = AdminTipodeServicos::getTipodeservicoItensSelect($request,$id);
    $departamentoSelecionado = AdminDepartamentos::getDepartamentoItensSelect($request,$id);

    //CONTEÚDO DA HOME
    $content = View::render('admin/modules/'.DIR_ATENDIMENTO.'/index',[
      'icon' => ICON_ATENDIMENTO,
      'title' =>TITLE_ATENDIMENTO,
      'titlelow' => TITLELOW_ATENDIMENTO,
      'direntity' => ROTA_ATENDIMENTO,
      'departamento' => $id_departamento,
      'id_departamento_usuario_logado' => EntityDepartamento::getDepartamentoIdPorSigla($_SESSION['admin']['usuario']['departamento'])->departamento_id,
      'itens' => self::getAtendimentoItens($request,$obPagination),
      'status' => self::getStatus($request),
      'optionsServico' => $servicoSelecionado,
      'optionsTipoDeServico' => $tipoDeServicoSelecionado,
      'optionsTiposdeic' => $tipodeICSelecionado,
      'optionsDepartamento' => $departamentoSelecionado
    ]);

    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel(TITLE_ATENDIMENTO.' - EMERJ',$content,ROTA_ATENDIMENTO,$currentDepartamento,$currentPerfil);
  }

  /**
   * Método responsável por obter a renderização dos itens de Atendimentos para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getAtendimentoItens($request,&$obPagination){

    $itens = '';

    //RESONSÁVEL PELA RENDERIZAÇÃO DOS MODAIS DE EDIÇÃO NA PÁGINA DE LISTAGEM DE LOCALIZAÇÕES
    $strEditModal = View::render('admin/modules/'.DIR_ATENDIMENTO.'/editmodal',[]);
    $strAddModal = View::render('admin/modules/'.DIR_ATENDIMENTO.'/addmodal',[]);
    $strAtivaModal = View::render('admin/modules/'.DIR_ATENDIMENTO.'/ativamodal',[]);
    $strDeleteModal = View::render('admin/modules/'.DIR_ATENDIMENTO.'/deletemodal',[]);

    //RESULTADO DA PAGINA
    $results = EntityAtendimento::getAtendimentos();

    //MONTA E RENDERIZA OS ITENS DE Atendimento
    while($obAtendimento = $results->fetchObject(EntityAtendimento::class)){
      $itens .= View::render('admin/modules/'.DIR_ATENDIMENTO.'/item',[
        'id' => $obAtendimento->atendimento_id,
        'servico' => EntityServico::getServicoPorId($obAtendimento->id_servico)->servico_nm,
        'tipodeservico' => EntityTipodeservico::getTipodeservicoPorId(EntityServico::getServicoPorId($obAtendimento->id_servico)->id_tipodeservico)->tipodeservico_nm,
        'tipodeic' => EntityTipodeic::getTipodeicPorId($obAtendimento->id_tipodeic)->tipodeic_nm,
        'departamento' => EntityDepartamento::getDepartamentoPorId($obAtendimento->id_departamento)->departamento_sg,
        'sla' => $obAtendimento->sla.' horas',
        'id_servico' => $obAtendimento->id_servico,
        'id_tipodeic' => $obAtendimento->id_tipodeic,
        'id_departamento' => $obAtendimento->id_departamento,
        'id_sla' => $obAtendimento->sla,
        'texto_ativo' => ('s' == $obAtendimento->ativo_fl) ? 'Desativar' : 'Ativar',
        'class_ativo' => ('s' == $obAtendimento->ativo_fl) ? 'btn-warning' : 'btn-success',
        'style_ativo' => ('s' == $obAtendimento->ativo_fl) ? 'table-active' : 'table-danger',
        'icon' => ICON_ATENDIMENTO,
        'title' =>TITLE_ATENDIMENTO,
        'titlelow' => TITLELOW_ATENDIMENTO,
        'direntity' => ROTA_ATENDIMENTO
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
    public static function setNovoAtendimento($request){

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $servico_id = filter_input(INPUT_POST, 'servico', FILTER_SANITIZE_NUMBER_INT) ?? 0;
      $tipodeic_id = filter_input(INPUT_POST, 'tipodeic', FILTER_SANITIZE_NUMBER_INT) ?? 0;
      $departamento_id = filter_input(INPUT_POST, 'departamento', FILTER_SANITIZE_NUMBER_INT) ?? 0;
      $sla = filter_input(INPUT_POST, 'sla', FILTER_SANITIZE_NUMBER_INT) ?? 0;

      $where = " id_departamento = ".$departamento_id." AND id_servico = ".$servico_id." AND id_tipodeic = ".$tipodeic_id;

      //echo "<pre>"; print_r($where); echo "<pre>"; exit;
      //VERIFICA SE JÁ EXISTE O IC com mesmo nome e tipo CADASTRADO NO BANCO
      $obAtendimentoVer = EntityAtendimento::getAtendimentos($where)->fetchColumn();

      if($obAtendimentoVer > 0){
        //REDIRECIONA SE O E-MAIL JÁ FOR CADASTRADO NO BANCO
        $request->getRouter()->redirect('/admin/'.ROTA_ATENDIMENTO.'?status=duplicado&nm='.$where.'&acao=alter');
      }

      //NOVA ISNTANCIA DE IC
      $obAtendimento = new EntityAtendimento;

      ////$obAtendimento::getAtendimentoPorEmail($posVars['email']);
      $obAtendimento->id_servico = $servico_id;
      $obAtendimento->id_tipodeic = $tipodeic_id;
      $obAtendimento->id_departamento = $departamento_id;
      $obAtendimento->sla = $sla;
      $obAtendimento->cadastrar();

      //REDIRECIONA O USUÁRIO
      $request->getRouter()->redirect('/admin/'.ROTA_ATENDIMENTO.'?status=gravado&nm='.$nome.'&acao=alter');

    }


    /**
     * Método responsável por montar a renderização do select de Tipo de Serviço para os formulários
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function getAtendimentoItensSelect($request,$id){

      $where = '';
      $order = 'id_atendimento';

      //PODE SER UTILIZADO MAIS TARDE PARA CONSTRUIR UMA CLAUSULA where
      $departamento = '';
      $servico = '';

      $resultsSelect = EntityAtendimento::getAtendimentos(null,$order,null,null);

      while($obTipodeservico = $resultsSelect->fetchObject(EntityAtendimento::class)){
        $itensSelect .= View::render('admin/modules/tipodeservico/itemselect',[
          'idSelect' => $obTipodeservico->id_tipodeservico,
          'selecionado' => ($id_tipodeservico == $obTipodeservico->id_tipodeservico) ? 'selected' : '',
          'nomeSelect' => EntityTipodeservico::getTipodeservicoPorId($obTipodeservico->id_tipodeservico)->tipodeservico_nm
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
     public static function getAtendimentoItensSelectChamados($request,$id){
       $itensSelect = '';
       $where = '';
       $departamento = '';
       $servico = '';

       //echo "<pre>"; print_r($id); echo "<pre>"; exit;
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
       //echo "<pre>where="; print_r($resultsSelect); echo "<pre>";
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


     /**
      * Método responsável por montar a renderização do checkbox de Itens de configuração para os formulários
      * @param Request $request
      * @param integer $id
      * @return string
      */
     public static function getAtendimentoItensCheckbox($request,$id_servico,$id_departamento,$id_tipodeic){

       $itensCheckbox = '';
       $resultsCheckbox = EntityAtendimento::getAtendimentos('id_servico = '.$id_servico. ' AND id_departamento = '.$id_departamento.' AND id_tipodeic = ' .$id_tipodeic.' ','id_departamento, id_tipodeic, id_servico ');

       while($obItensconf = $resultsCheckbox->fetchObject(EntityAtendimento::class)){
         $itensCheckbox .= View::render('admin/modules/itensconf/itemcheckbox',[
           'idSelect' => $obItensconf->id_itemdeconfiguracao,
  //         'selecionado' => ($id == $obItensconf->id_itemdeconfiguracao) ? 'checked' : '',
           'nomeSelect' => EntityItensconf::getItensconfPorId($obItensconf->id_tipodeic)->itemdeconfiguracao_nm
         ]);
       }
       return $itensCheckbox;
     }


     /**
      * Método responsável por montar a renderização do checkbox de Itens de configuração específico para o formulário de Abrir Chamados
      * @param Request $request
      * @param integer $id
      * @param integer $id_servico
      * @param integer $id_departamento
      * @return string
      */
     public static function getAtendimentoItensCheckboxChamado($request,$id,$id_servico,$id_departamento){

       $itensCheckbox = '';
       $resultsAll = EntityAtendimento::getAtendimentos('id_servico = '.$id_servico.' AND id_departamento = '.$id_departamento.' ',' id_itemdeconfiguracao ',null,' id_itemdeconfiguracao ');
       $resultsSelected = EntityAtendimento::getAtendimentos2(' chamado_id = id_chamado AND id_atendimento = atendimento_id AND id_chamado = '.$id.' ',' id_itemdeconfiguracao ',' tb_chamado, tb_andamento, tb_atendimento ',null,' DISTINCT id_itemdeconfiguracao ');

       //Monta um array com os itens selecionados (retornado do banco)
       $itensSelected = array();
       while($obItensSelected = $resultsSelected->fetchObject(EntityAtendimento::class)){
              $itensSelected[] = $obItensSelected->id_itemdeconfiguracao;
       }

       while($obItensconf = $resultsAll->fetchObject(EntityAtendimento::class)){
         $itensCheckbox .= View::render('admin/modules/itensconf/itemcheckbox',[
            'idSelect' => $obItensconf->id_itemdeconfiguracao,
            'selecionado' => in_array($obItensconf->id_itemdeconfiguracao, $itensSelected) ? 'checked' : '',
            'nomeSelect' => EntityItensconf::getItensconfPorId($obItensconf->id_itemdeconfiguracao)->itemdeconfiguracao_nm
          ]);
       }
      return $itensCheckbox;
     }

      /**
      * Método responsável por gravar a edição de um item de configuração
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function setEditAtendimento($request,$id){

        //DADOS DO POST
        $posVars = $request->getPostVars();
        $departamento_id = filter_input(INPUT_POST, 'departamento', FILTER_SANITIZE_NUMBER_INT) ?? 0;
        $servico_id = filter_input(INPUT_POST, 'servico', FILTER_SANITIZE_NUMBER_INT) ?? 0;
        $tipodeic_id = filter_input(INPUT_POST, 'tipodeic', FILTER_SANITIZE_NUMBER_INT) ?? 0;
        $sla = filter_input(INPUT_POST, 'sla', FILTER_SANITIZE_NUMBER_INT) ?? 0;

        $where = ' id_servico = '.$servico_id.' AND id_tipodeic = '.$tipodeic_id.' AND id_departamento = '.$departamento_id;

        //echo "<pre>"; print_r($where); echo "<pre>"; exit;
        //VERIFICA SE JÁ EXISTE O IC com mesmo nome e tipo CADASTRADO NO BANCO
        $obAtendimento = EntityAtendimento::getAtendimentos($where)->fetchColumn();

      //  echo "<pre>"; print_r($obAtendimento); echo "<pre>"; exit;
        if($obAtendimento > 0){
          //REDIRECIONA SE O E-MAIL JÁ FOR CADASTRADO NO BANCO
          $request->getRouter()->redirect('/admin/atendimentos?status=duplicado');
        }

        //OBTÉM O USUÁRIO DO BANCO DE DADOS
        $obAtendimento = EntityAtendimento::getAtendimentoPorId($id);

        if(!$obAtendimento instanceof EntityAtendimento){

          //echo "<pre>"; print_r($id); echo "<pre>"; exit;
          $request->getRouter()->redirect('/admin/atendimentos?status=updatefail');
        }

        //echo "<pre>"; print_r($id_servico); echo "<pre>"; exit;

        //ATUALIZA A INSTANCIA
        $obAtendimento->id_servico = $servico_id;
        $obAtendimento->id_tipodeic = $tipodeic_id;
        $obAtendimento->id_departamento = $departamento_id;
        $obAtendimento->sla = $sla;
        $obAtendimento->atualizar();

        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/'.ROTA_ATENDIMENTO.'?status=alterado&nm=&acao=alter');
      }

      /**
       * Método responsável por retornar o formulário de alteração de status de um usuário
       * @param Request $request
       * @param integer $id
       * @return string
       */
       public static function getAltStatusAtendimentoModal($request,$id){

         //OBTÉM O USUÁRIO DO BANCO DE DADOS
         $obAtendimento = EntityAtendimento::getAtendimentoPorId($id);

         //PÁGINA ATUAL
         $uri=strstr("$_SERVER[REQUEST_URI]", '?');

         if(!$obAtendimento instanceof EntityAtendimento){
           $request->getRouter()->redirect('/admin/atendimentos?status=updatefail');
         }

         //CONTEÚDO DO FORMULÁRIO
         $content = View::render('admin/modules/atendimento/alterastatus',[
           'status' => self::getStatus($request),
           'paginaAtual' => $paginaAtual

         ]);

         //OBTÉM O USUÁRIO DO BANCO DE DADOS
         if($obAtendimento->ativo_fl == 's'){
           $altStatus = 'n';
         } elseif ($obAtendimento->ativo_fl == 'n') {
           $altStatus = 's';
         }

         //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
         $obAtendimento->ativo_fl = $altStatus;
         $obAtendimento->atualizar();

         //REDIRECIONA O USUÁRIO
         $request->getRouter()->redirect('/admin/atendimentos?status=statusupdate');

       }

       /**
        * Método responsável por excluir um usuário
        * @param Request $request
        * @param integer $id
        * @return string
        */
        public static function getDeleteAtendimento($request,$id){

          //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
          $obAtendimento = EntityAtendimento::getAtendimentoPorId($id);

          if(!$obAtendimento instanceof EntityAtendimento){
            $request->getRouter()->redirect('/admin/atendimentos');
          }
          //EXCLUI O USUÁRIO
          $obAtendimento->excluir();
          //REDIRECIONA O USUÁRIO
          $request->getRouter()->redirect('/admin/'.ROTA_ATENDIMENTO.'?status=deletado&nm=&acao=alter');
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
       return Alert::getSuccess('Atendimento cadastrado com sucesso!');
       // code...
       break;
     case 'alterado':
       return Alert::getSuccess('Dados do Atendimento alterados com sucesso!');
       // code...
       break;
     case 'deletado':
       return Alert::getSuccess('Atendimento deletado com sucesso!');
       // code...
       break;
     case 'duplicado':
       return Alert::getError('Já existe um Atendimento com este nome para o mesmo Departamento!');
       // code...
       break;
     case 'statusupdate':
       return Alert::getSuccess('Status do Atendimento alterado com sucesso!');
       // code...
       break;
   }
  }
}
