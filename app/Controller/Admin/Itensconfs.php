<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Itensconf as EntityItensconf;
use \App\Model\Entity\Tipodeic as EntityTipodeic;
use \App\Model\Entity\Departamento as EntityDepartamento;
use \App\Model\Entity\Usuario as EntityUsuario;
use \App\Model\Entity\Localizacao as EntityLocalizacao;
use \App\Controller\Admin\Tipodeics as AdminTipodeics;
use \App\Controller\Admin\Departamentos as AdminDepartamentos;
use \App\Controller\Admin\Usuarios as AdminUsuarios;
use \App\Controller\Admin\Localizacoes as AdminLocalizacoes;
use \App\Controller\Admin\Itensconfs as AdminItensconfs;
use \App\Db\Pagination;

const DIR_IC = 'itensconf';
const ROTA_IC = 'itensconfs';
const ICON_IC = 'printer';
const TITLE_IC = 'ICs';
const TITLELOW_IC = 'o IC';

class Itensconfs extends Page{

  /**
   * Método responsável pela renderização da view de listagem de IC's
   * @param Request $request
   * @return string
   */
  public static function getListItensconfs($request,$errorMessage = null){

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

    $tipodeicSelecionado = AdminTipodeics::getTipodeicItensSelect($request,$id);
    $departamentoSelecionado = AdminDepartamentos::getDepartamentoItensSelect($request,$id);
    $usuarioSelecionado = AdminUsuarios::getUsuarioItensSelect($request,$id);
    $localizacaoSelecionado = AdminLocalizacoes::getLocalizacaoItensSelect($request,$id);
    $icsSelecionado = AdminItensconfs::getItensconfItensSelect($request,$id);

    //CONTEÚDO DA HOME
    $content = View::render('admin/modules/itensconf/index',[
      'icon' => ICON_IC,
      'title' =>TITLE_IC,
      'titlelow' => TITLELOW_IC,
      'direntity' => ROTA_IC,
      'itens' => self::getItensconfItens($request,$obPagination),
      'status' => self::getStatus($request),
      'optionsBuscaTipodeic' => $tipodeicSelecionado,
      'optionsBuscaDepartamento' => $departamentoSelecionado,
      'optionsBuscaUsuario' => $usuarioSelecionado,
      'optionsBuscaLocalizacao' => $localizacaoSelecionado,
      'optionsBuscaICs' => $icsSelecionado
    ]);

    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel('Itens de Configuração - EMERJ',$content,'itensconfs',$currentDepartamento,$currentPerfil);
  }

  /**
   * Método responsável por obter a renderização dos itens de Itensconfs para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getItensconfItens($request,&$obPagination){

    $itens = '';

    //RESONSÁVEL PELA RENDERIZAÇÃO DO MODAL DE EDIÇÃO NA PÁGINA DE LISTAGEM DE LOCALIZAÇÕES
    $strEditModal = View::render('admin/modules/itensconf/editmodal',[]);
    $strAddModal = View::render('admin/modules/itensconf/addmodal',[]);
    $strAtivaModal = View::render('admin/modules/itensconf/ativamodal',[]);
    $strDeleteModal = View::render('admin/modules/itensconf/deletemodal',[]);

    //RESULTADO DA PAGINA
    $results = EntityItensconf::getItensconfs();

    //MONTA E RENDERIZA OS ITENS DE Itensconf
    while($obItensconf = $results->fetchObject(EntityItensconf::class)){
      $itens .= View::render('admin/modules/itensconf/item',[
        'id' => $obItensconf->itemdeconfiguracao_id,
        'nome' => $obItensconf->itemdeconfiguracao_nm,
        'descricao' => $obItensconf->itemdeconfiguracao_des,
        'patrimonio' => $obItensconf->patrimonio_nr ?? 'N/A',
        'ndgtec' => $obItensconf->dgtec_nr ?? 'N/A',
        'memoria' => $obItensconf->memoria,
        'hardisc' => $obItensconf->hardisc,
        'monitor' => $obItensconf->monitor_nm,
        'estabilizador' => $obItensconf->estabilizador,
        'so' => $obItensconf->sistema_ope,
        'rede' => $obItensconf->rede_nm,
        'office' => $obItensconf->office,
        'obs' => $obItensconf->obs,
        'tipodeic' => EntityTipodeic::getTipodeicPorId($obItensconf->id_tipodeic)->tipodeic_nm,
        'departamento' => EntityDepartamento::getDepartamentoPorId($obItensconf->id_departamento)->departamento_sg ?? '',
        'usuario' => EntityUsuario::getUsuarioPorId($obItensconf->id_usuario)->usuario_nm ?? 'Usuários EMERJ',
        'localizacao' => EntityLocalizacao::getLocalizacaoPorId($obItensconf->id_localizacao)->localizacao_nm ?? 'Toda EMERJ',
        'conectado' => EntityItensconf::getItensconfPorId($obItensconf->cod_itemdeconfiguracao_dep)->itemdeconfiguracao_nm ?? '',
        'id_tipodeic' => $obItensconf->id_tipodeic,
        'id_departamento' => $obItensconf->id_departamento,
        'id_usuario' => $obItensconf->id_usuario,
        'id_localizacao' => $obItensconf->id_localizacao,
        'itemdeconfiguracao' => $obItensconf->cod_itemdeconfiguracao_dep,
        'texto_ativo' => ('s' == $obItensconf->ativo_fl) ? 'Desativar' : 'Ativar',
        'class_ativo' => ('s' == $obItensconf->ativo_fl) ? 'btn-warning' : 'btn-success',
        'style_ativo' => ('s' == $obItensconf->ativo_fl) ? 'table-active' : 'table-danger'
      ]);
    }
    $itens .= $strDeleteModal;
    $itens .= $strAtivaModal;
    $itens .= $strEditModal;
    $itens .= $strAddModal;
    return $itens;
  }

  /**
   * Método responsável por montar a renderização do checkbox de Itens de configuração para os formulários
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getAtendimentoItensCheckbox($request,$id_servico,$id_departamento,$id_tipodeic){

    $itensCheckbox = '';
    $resultsCheckbox = EntityItensconf::getItensconfs('id_departamento = '.$id_departamento.' AND id_tipodeic = ' .$id_tipodeic.' ','id_departamento, id_tipodeic ');

    while($obItensconf = $resultsCheckbox->fetchObject(EntityItensconf::class)){
      $itensCheckbox .= View::render('admin/modules/itensconf/itemcheckbox',[
        'idSelect' => $obItensconf->itemdeconfiguracao_id,
 //         'selecionado' => ($id == $obItensconf->id_itemdeconfiguracao) ? 'checked' : '',
        'nomeSelect' => $obItensconf->itemdeconfiguracao_nm,
        'titulo' => (strlen($obItensconf->dgtec_nr) > 0) ? 'Patrimônio | Item' : '',
        'dgtec_nr' => $obItensconf->dgtec_nr,
        'patrimonio_nr' => $obItensconf->patrimonio_nr
      ]);
    }
    return $itensCheckbox;
  }

  /**
   * Método responsável por montar a renderização do select de Tipo de Serviço para os formulários
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getItensconfItensSelect($request,$id){

    $itensSelect = '';
    $resultsSelect = EntityItensconf::getItensconfs(null,'itemdeconfiguracao_nm, patrimonio_nr ASC ');

    while($obItensconf = $resultsSelect->fetchObject(EntityItensconf::class)){
      $itensSelect .= View::render('admin/modules/itensconf/itemselect',[
        'idSelect' => $obItensconf->itemdeconfiguracao_id,
        'selecionado' => ($id == $obItensconf->itemdeconfiguracao_id) ? 'selected' : '',
        'nomeSelect' => $obItensconf->itemdeconfiguracao_nm.' - '.$obItensconf->patrimonio_nr
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
  public static function getItensconfItensSelectAtendimento($request,$id){
    $itensSelect = '';
    $resultsSelect = EntityItensconf::getItemdeconfiguracaoDeAtendimento('tipodeic_id = id_tipodeic','tipodeic_nm ASC','tb_itemdeconfiguracao, tb_tipodeic',null,'distinct id_tipodeic, tipodeic_nm');

    while($obItensconf = $resultsSelect->fetchObject(EntityItensconf::class)){
      $itensSelect .= View::render('admin/modules/itensconf/itemselect',[
        'idSelect' => $obItensconf->id_tipodeic,
        'selecionado' => ($id == $obItensconf->id_tipodeic) ? 'selected' : '',
        'nomeSelect' => $obItensconf->tipodeic_nm
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
  public static function getItensconfItensCheckbox($request,$id){

    $itensCheckbox = '';
    //$resultsCheckbox = EntityItensconf::getItensconfPorIdServico($id);
    $resultsCheckbox = EntityItensconf::getItensconfs(null,'itemdeconfiguracao_nm ');

    //echo "<pre>"; print_r($resultsCheckbox); echo "<pre>"; exit;

    while($obItensconf = $resultsCheckbox->fetchObject(EntityItensconf::class)){
      $itensCheckbox .= View::render('admin/modules/itensconf/itemcheckbox',[
        'idSelect' => $obItensconf->itemdeconfiguracao_id,
        'selecionado' => ($id == $obItensconf->itemdeconfiguracao_id) ? 'selected' : '',
        'nomeSelect' => $obItensconf->itemdeconfiguracao_nm
      ]);
    }
    return $itensCheckbox;
  }

   /**
    * Método responsável por cadastro de um novo item de configuração no banco
    * @param Request $request
    * @return string
    */
    public static function setNovoItensconf($request){

      $patrimonio = NULL;

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING) ?? '';
      $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING) ?? '';
      $departamento = filter_input(INPUT_POST, 'departamento', FILTER_SANITIZE_NUMBER_INT) ?? 0;
      $localizacao = filter_input(INPUT_POST, 'localizacao', FILTER_SANITIZE_NUMBER_INT) ?? 0;
      $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_NUMBER_INT) ?? 0;
      $tipodeic = filter_input(INPUT_POST,'tipodeic', FILTER_SANITIZE_NUMBER_INT) ?? 0;
      $patrimonio = filter_input(INPUT_POST,'patrimonio', FILTER_SANITIZE_NUMBER_INT) ?? 'NULL';
      $ndgtec = filter_input(INPUT_POST,'ndgtec', FILTER_SANITIZE_NUMBER_INT) ?? 'NULL';
      $so = filter_input(INPUT_POST,'so', FILTER_SANITIZE_STRING) ?? '';
      $rede = filter_input(INPUT_POST,'rede', FILTER_SANITIZE_STRING) ?? '';
      $office = filter_input(INPUT_POST,'office', FILTER_SANITIZE_STRING) ?? '';
      $memoria = filter_input(INPUT_POST,'memoria', FILTER_SANITIZE_STRING) ?? '';
      $hardisc = filter_input(INPUT_POST,'hardisc', FILTER_SANITIZE_STRING) ?? '';
      $monitor = filter_input(INPUT_POST,'monitor', FILTER_SANITIZE_STRING) ?? '';
      $estabilizador = filter_input(INPUT_POST,'estabilizador', FILTER_SANITIZE_STRING) ?? '';
      $obs= filter_input(INPUT_POST,'obs', FILTER_SANITIZE_STRING) ?? '';
      $itemdeconfiguracao= filter_input(INPUT_POST,'itemdeconfiguracao', FILTER_SANITIZE_STRING) ?? '';

      if ($patrimonio <> ''){
        $obItensconfVerPatr = EntityItensconf::getItensconfPorPatrimonio($patrimonio);;

        if($obItensconfVerPatr instanceof EntityItensconf){
          //REDIRECIONA SE O E-MAIL JÁ FOR CADASTRADO NO BANCO
          $request->getRouter()->redirect('/admin/itensconfs?status=ptduplicado&nm='.$patrimonio.'&acao=gravar');
        }
      }
      //VERIFICA SE O N da DGTEC FOI INFORMADO
      if ($ndgtec <> ''){
        //VERIFICA SE JÁ EXISTE O N DGTEC INFORMADO, CADASTRADO NO BANCO
        $obItensconfVerNrDGTEC = EntityItensconf::getItensconfPorNrDGTEC($ndgtec);;

        if($obItensconfVerNrDGTEC instanceof EntityItensconf){
          //REDIRECIONA SE O N DGTEC JÁ FOR CADASTRADO NO BANCO (impedindo a duplicidade)
          $request->getRouter()->redirect('/admin/itensconfs?status=nrduplicado&nm='.$ndgtec.'&acao=gravar');
        }
      }

      //NOVA ISNTANCIA DE IC
      $obItensconf = new EntityItensconf;

      //ATUALIZA A INSTANCIA
      $obItensconf->itemdeconfiguracao_nm = $nome ?? $obItensconf->itemdeconfiguracao_nm;
      $obItensconf->itemdeconfiguracao_des = $descricao ?? $obItensconf->itemdeconfiguracao_des;
      $obItensconf->id_departamento= $departamento ?? $obItensconf->id_departamento;
      $obItensconf->id_localizacao = $localizacao ?? $obItensconf->id_localizacao;
      $obItensconf->id_usuario = $usuario ?? $obItensconf->id_usuario;
      $obItensconf->id_tipodeic = $tipodeic ?? $obItensconf->id_tipodeic;
      $obItensconf->patrimonio_nr = $patrimonio;
      $obItensconf->dgtec_nr = $ndgtec ?? $obItensconf->dgtec_nr;
      $obItensconf->sistema_ope = $so ?? $obItensconf->sistema_ope;
      $obItensconf->rede_nm = $rede ?? $obItensconf->rede_nm;
      $obItensconf->office = $office ?? $obItensconf->office;
      $obItensconf->memoria = $memoria ?? $obItensconf->memoria;
      $obItensconf->hardisc = $hardisc ?? $obItensconf->hardisc;
      $obItensconf->monitor_nm = $monitor ?? $obItensconf->monitor_nm;
      $obItensconf->estabilizador = $estabilizador ?? $obItensconf->estabilizador;
      $obItensconf->obs = $obs ?? $obItensconf->obs;
      $obItensconf->cod_itemdeconfiguracao_dep = $itemdeconfiguracao ?? $obItensconf->cod_itemdeconfiguracao_dep;

      $obItensconf->cadastrar();

      //REDIRECIONA O USUÁRIO
      $request->getRouter()->redirect('/admin/itensconfs?status=gravado&nm='.$nome.'&acao=alter');

    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo item de configuração
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function getEditItensconf($request,$id){

       //OBTÉM O USUÁRIO DO BANCO DE DADOS
       $obItensconf = EntityItensconf::getItensconfPorId($id);

       if(!$obItensconf instanceof EntityItensconf){
         $request->getRouter()->redirect('/admin/itensconfs/novo?status=updatefail');
       }

       $optionsTipodeic = AdminTipodeics::getTipodeicItensSelect($request,$obItensconf->id_tipodeic);

       //echo "<pre>"; print_r($obItensconf->id_tipodeic); echo "<pre>"; exit;

       //CONTEÚDO DO FORMULÁRIO
       $content = View::render('admin/modules/itensconf/form',[
         'title' => 'Editar Item de Configuração',
         'nome' => $obItensconf->itemdeconfiguracao_nm,
         'descricao' => $obItensconf->itemdeconfiguracao_des,
         'optionsTipodeic' => $optionsTipodeic,
         'status' => self::getStatus($request)
       ]);

       //RETORNA A PÁGINA COMPLETA
       return parent::getPanel('Editar Item de Configuração',$content,'itensconfs');
     }

     /**
      * Método responsável por gravar a edição de um item de configuração
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function setEditItensconf($request,$id){

        //DADOS DO POST
        $posVars = $request->getPostVars();
        $id = filter_input(INPUT_POST, 'id_edit', FILTER_SANITIZE_NUMBER_INT);;
        $nome = filter_input(INPUT_POST, 'nome_edit', FILTER_SANITIZE_STRING);;
        $descricao = filter_input(INPUT_POST, 'descricao_edit', FILTER_SANITIZE_STRING);
        $departamento = filter_input(INPUT_POST, 'departamento_edit', FILTER_SANITIZE_STRING);
        $localizacao = filter_input(INPUT_POST, 'localizacao_edit', FILTER_SANITIZE_STRING);
        $usuario = filter_input(INPUT_POST, 'usuario_edit', FILTER_SANITIZE_STRING);
        $tipodeic= filter_input(INPUT_POST,'tipodeic_edit', FILTER_SANITIZE_STRING);
        $patrimonio= filter_input(INPUT_POST,'patrimonio_edit', FILTER_SANITIZE_STRING) ?? 'NULL';
        $ndgtec= filter_input(INPUT_POST,'ndgtec_edit', FILTER_SANITIZE_STRING) ?? 'NULL';
        $so= filter_input(INPUT_POST,'so_edit', FILTER_SANITIZE_STRING);
        $rede= filter_input(INPUT_POST,'rede_edit', FILTER_SANITIZE_STRING);
        $office= filter_input(INPUT_POST,'office_edit', FILTER_SANITIZE_STRING);
        $memoria= filter_input(INPUT_POST,'memoria_edit', FILTER_SANITIZE_STRING);
        $hardisc= filter_input(INPUT_POST,'hardisc_edit', FILTER_SANITIZE_STRING);
        $monitor= filter_input(INPUT_POST,'monitor_edit', FILTER_SANITIZE_STRING);
        $estabilizador= filter_input(INPUT_POST,'estabilizador_edit', FILTER_SANITIZE_STRING);
        $obs= filter_input(INPUT_POST,'obs_edit', FILTER_SANITIZE_STRING);
        $itemdeconfiguracao= filter_input(INPUT_POST,'itemdeconfiguracao_edit', FILTER_SANITIZE_STRING) ?? '';

        //OBTÉM O USUÁRIO DO BANCO DE DADOS
        $obItensconf = EntityItensconf::getItensconfPorId($id);

        if(!$obItensconf instanceof EntityItensconf){
          $request->getRouter()->redirect('/admin/itensconfs?status=updatefail');
        }

        //ATUALIZA A INSTANCIA
        $obItensconf->itemdeconfiguracao_nm = $nome ?? $obItensconf->itemdeconfiguracao_nm;
        $obItensconf->itemdeconfiguracao_des = $descricao ?? $obItensconf->itemdeconfiguracao_des;
        $obItensconf->id_departamento= $departamento ?? $obItensconf->id_departamento;
        $obItensconf->id_localizacao = $localizacao ?? $obItensconf->id_localizacao;
        $obItensconf->id_usuario = $usuario ?? $obItensconf->id_usuario;
        $obItensconf->id_tipodeic = $tipodeic ?? $obItensconf->id_tipodeic;
        $obItensconf->patrimonio_nr = $patrimonio ?? '';
        $obItensconf->dgtec_nr = $ndgtec ?? '';
        $obItensconf->sistema_ope = $so ?? $obItensconf->sistema_ope;
        $obItensconf->rede_nm = $rede ?? $obItensconf->rede_nm;
        $obItensconf->office = $office ?? $obItensconf->office;
        $obItensconf->memoria = $memoria ?? $obItensconf->memoria;
        $obItensconf->hardisc = $hardisc ?? $obItensconf->hardisc;
        $obItensconf->monitor_nm = $monitor ?? $obItensconf->monitor_nm;
        $obItensconf->estabilizador = $estabilizador ?? $obItensconf->estabilizador;
        $obItensconf->obs = $obs ?? $obItensconf->obs;
        $obItensconf->cod_itemdeconfiguracao_dep = $itemdeconfiguracao ?? $obItensconf->cod_itemdeconfiguracao_dep;
        $obItensconf->atualizar();

        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/itensconfs?status=alterado&nm='.$nome.'&acao=alter');
      }

      /**
       * Método responsável por retornar o formulário de alteração de status de um usuário
       * @param Request $request
       * @param integer $id
       * @return string
       */
       public static function getAltStatusItensconfModal($request,$id){

         //OBTÉM O ITEM DO BANCO DE DADOS
         $obItensconf = EntityItensconf::getItensconfPorId($id);
         $strNome = $obItensconf->itemdeconfiguracao_nm;


         if(!$obItensconf instanceof EntityItensconf){
           $request->getRouter()->redirect('/admin/itensconfs?status=updatefail');
         }

         //OBTÉM O USUÁRIO DO BANCO DE DADOS
         if($obItensconf->ativo_fl == 's'){
           $altStatus = 'n';
         } elseif ($obItensconf->ativo_fl == 'n') {
           $altStatus = 's';
         }

         //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
         $obItensconf->ativo_fl = $altStatus;
         $obItensconf->atualizar();

         //REDIRECIONA O USUÁRIO
         $request->getRouter()->redirect('/admin/itensconfs?status=statusupdate&nm='.$strNome.$strMsn.'&acao=alter');

       }

      /**
       * Método responsável por retornar o formulário de exclusão de um usuário
       * @param Request $request
       * @param integer $id
       * @return string
       */
       public static function getDeleteItensconf($request,$id){

         //OBTÉM O ITEM DE CONFIGURAÇÃO DO BANCO DE DADOS
         $obItensconf = EntityItensconf::getItensconfPorId($id);
         $strNome = $obLocalizacao->localizacao_nm;

         if(!$obItensconf instanceof EntityItensconf){
           $request->getRouter()->redirect('/admin/itensconf');
         }

        //EXCLUI O USUÁRIO
         $obItensconf->excluir();

         //RETORNA A PÁGINA COMPLETA
           $request->getRouter()->redirect('/admin/itensconfs?status=deletado&nm='.$strNome.$strMsn.'&acao=alter');
       }

       /**
        * Método responsável por retornar o formulário de exclusão de um usuário atraves de um Modal
        * @param Request $request
        * @param integer $id
        * @return string
        */
        public static function getDeleteItensconfModal($request,$id){

          //OBTÉM O USUÁRIO DO BANCO DE DADOS
          $obItensconf = EntityItensconf::getItensconfPorId($id);

          if(!$obItensconf instanceof EntityItensconf){
            $request->getRouter()->redirect('/admin/itensconfs');
          }

         //EXCLUI O USUÁRIO
          $obItensconf->excluir();
          //REDIRECIONA O USUÁRIO
          $request->getRouter()->redirect('/admin/itensconfs?status=deletado');
        }

       /**
        * Método responsável por excluir um usuário
        * @param Request $request
        * @param integer $id
        * @return string
        */
        public static function setDeleteItensconf($request,$id){

          //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
          $obItensconf = EntityItensconf::getItensconfPorId($id);

        //  echo "<pre>"; print_r($id); echo "<pre>"; exit;

          if(!$obItensconf instanceof EntityItensconf){
            $request->getRouter()->redirect('/admin/itensconfs');
          }
          //EXCLUI O USUÁRIO
          $obItensconf->excluir();
          //REDIRECIONA O USUÁRIO
          $request->getRouter()->redirect('/admin/itensconfs?status=deletado');

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
        return Alert::getSuccess('Item <strong>'.$nm.'</strong> cadastrada com sucesso!');
        // code...
        break;
      case 'alterado':
        return Alert::getSuccess('Dados do item <strong>'.$nm.'</strong> alterados com sucesso!');
        // code...
        break;
      case 'deletado':
        return Alert::getSuccess('Item de configuração <strong>'.$nm.'</strong> deletado com sucesso!');
        // code...
        break;
      case 'nrduplicado':
        return Alert::getError('Já existe um item cadastrado com este número da DGTEC (<strong>'.$nm.'</strong>)!');
        // code...
        break;
      case 'ptduplicado':
        return Alert::getError('Já existe um item cadastrado com este número de Patrimônio (<strong>'.$nm.'</strong>)!');
        // code...
        break;
      case 'statusupdate':
        return Alert::getSuccess('Status do item <strong>'.$nm.'</strong> com sucesso!');
        // code...
        break;
   }
  }
}
