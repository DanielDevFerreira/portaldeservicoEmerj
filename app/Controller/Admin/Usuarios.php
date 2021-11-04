<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Utils\Geralog;
use \App\Model\Entity\Usuario as EntityUsuario;
use \App\Model\Entity\Cargo as EntityCargo;
use \App\Model\Entity\Perfil as EntityPerfil;
use \App\Model\Entity\Departamento as EntityDepartamento;
use \App\Model\Entity\Localizacao as EntityLocalizacao;
use \App\Controller\Pages\Perfil as PagesPerfil;
use \App\Controller\Pages\Cargo as PagesCargo;
use \App\Controller\Admin\Departamentos as AdminDepartamentos;
use \App\Controller\Pages\Departamento as PagesDepartamento;
use \App\Controller\Admin\Localizacoes as AdminLocalizacoes;
use \App\Db\Pagination;
use \App\Http\Request;

const DIR_USUARIO = 'usuarios';
const ROTA_USUARIO = 'usuarios';
const ICON_USUARIO = 'people-fill';
const TITLE_USUARIO = 'Usuários';
const TITLELOW_USUARIO = 'o usuário';

class Usuarios extends Page{

  /**
   * Método responsável pela renderização da view de listagem de usuários
   * @param string $dep
   * @param string $perf
   * @return boolean
   */
   public static function validaPermissao($dep,$perf){

     $currentDepartamento = $dep ?? '';
     $currentPerfil = $perf ?? '';

     //STATUS
     if(!isset($currentDepartamento)) return false;

     //MENSAGENS DE STATUS
     switch ($currentDepartamento) {
       case 'EMERJ':
        return true;
        break;
      case 'DETEC':
        return true;
        break;
      case 'DEADM':
        return true;
        break;
     }
     //STATUS
     if(!isset($currentPerfil)) return false;

     //MENSAGENS DE STATUS
     switch ($currentPerfil) {
       case 1:
        return true;
        break;
      case 2:
        return true;
        break;
    }
    return false;
  }

  /**
   * Método responsável pela renderização da view de listagem de usuários
   * @param Request $request
   * @return string
   */
  public static function getListUsuarios($request,$errorMessage = null){

    $currentDepartamento = $_SESSION['admin']['usuario']['departamento'] ?? '';
    $currentPerfil = $_SESSION['admin']['usuario']['id_perfil'] ?? '';
    $strPerfilDesenvDisable = 'disabled';
    $strPerfilDesenvHide = 'hide';
    $id_perfil = '5';

    if ($currentPerfil == 1) {
      $strPerfilDesenvHide = '';
      $strPerfilDesenvDisable = '';
      $id_perfil = null;
    }

    if (!self::validaPermissao($currentDepartamento,$currentPerfil)) {
      $request->getRouter()->redirect('/?status=sempermissao');
    }

    $id = filter_input(INPUT_GET, 'departamento', FILTER_SANITIZE_STRING) ?? '';

    $status = self::getStatus($request);

    //$optionsDepartamento = PagesDepartamento::getDepartamentoItensSelect($request,0);
    $optionsPerfil = PagesPerfil::getPerfilItensSelect($request,$id_perfil);
    $optionsDepartamento = AdminDepartamentos::getDepartamentoItensSelect($request,$id);
    $optionsCargo = PagesCargo::getCargoItensSelect($request,$id);
    $optionsLocalizacao = AdminLocalizacoes::getLocalizacaoItensSelect($request,$id);

    //CONTEÚDO DA HOME
    $content = View::render('admin/modules/usuarios/index',[
      'icon' => ICON_USUARIO,
      'title' =>TITLE_USUARIO,
      'titlelow' => TITLELOW_USUARIO,
      'direntity' => ROTA_USUARIO,
      'itens' => self::getUsuarioItens($request,$obPagination),
      'status' => self::getStatus($request),
      'optionsDepartamento' => $optionsDepartamento,
      'optionsPerfil'  => $optionsPerfil,
      'optionsCargo'  => $optionsCargo,
      'optionsLocalizacao'  => $optionsLocalizacao,
      'perfilDesenvDisable' => $strPerfilDesenvDisable,
      'perfilDesenvHide' => $strPerfilDesenvHide

    ]);

    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel('Usuários - EMERJ',$content,'usuarios',$currentDepartamento,$currentPerfil);
  }


  /**
   * Método responsável por obter a renderização dos itens de Usuarios para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getUsuarioItens($request,&$obPagination){

    $itens = '';

    //RESONSÁVEL PELA RENDERIZAÇÃO DOS MODAIS DE EDIÇÃO NA PÁGINA DE LISTAGEM DE LOCALIZAÇÕES
    $strEditModal = View::render('admin/modules/'.DIR_USUARIO.'/editmodal',[]);
    $strAddModal = View::render('admin/modules/'.DIR_USUARIO.'/addmodal',[]);
    $strAtivaModal = View::render('admin/modules/'.DIR_USUARIO.'/ativamodal',[]);
    $strDeleteModal = View::render('admin/modules/'.DIR_USUARIO.'/deletemodal',[]);
    $strResetModal = View::render('admin/modules/'.DIR_USUARIO.'/resetmodal',[]);

    //RESULTADO DA PAGINA
    $results = EntityUsuario::getUsuarios();

    //MONTA E RENDERIZA OS ITENS DE Usuario
    while($obUsuario = $results->fetchObject(EntityUsuario::class)){
      $itens .= View::render('admin/modules/usuarios/item',[
        'icon' => ICON_USUARIO,
        'title' =>TITLE_USUARIO,
        'titlelow' => TITLELOW_USUARIO,
        'direntity' => ROTA_USUARIO,
        'id' => $obUsuario->usuario_id,
        'nome' => $obUsuario->usuario_nm,
        'email' => $obUsuario->email,
        'contato' => $obUsuario->usuario_fone,
        'cargo' => EntityCargo::getCargo($obUsuario->id_cargo)->cargo_nm,
        'perfil' => EntityPerfil::getPerfil($obUsuario->id_perfil)->perfil_nm,
        'sala' => EntityLocalizacao::getLocalizacaoPorId($obUsuario->sala)->localizacao_nm,
        'departamento_sigla' => EntityDepartamento::getDepartamentoPorId($obUsuario->id_departamento)->departamento_sg,
        'departamento' => EntityDepartamento::getDepartamentoPorId($obUsuario->id_departamento)->departamento_nm,
        'cargo_id' => $obUsuario->id_cargo,
        'perfil_id' => $obUsuario->id_perfil,
        'sala_id' => $obUsuario->sala,
        'departamento_id' => $obUsuario->id_departamento,
        'texto_ativo' => ('s' == $obUsuario->ativo_fl) ? 'Desativar' : 'Ativar',
        'class_ativo' => ('s' == $obUsuario->ativo_fl) ? 'btn-warning' : 'btn-success',
        'style_ativo' => ('s' == $obUsuario->ativo_fl) ? 'table-active' : 'table-danger'
      ]);
    }
    $itens .= $strDeleteModal;
    $itens .= $strAtivaModal;
    $itens .= $strEditModal;
    $itens .= $strAddModal;
    $itens .= $strResetModal;
    return $itens;
  }



  /**
   * Método responsável por montar a renderização do select de usuários para o formulário
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getUsuarioItensSelect($request,$id){
    $itensSelect = '';
    $resultsSelect = EntityUsuario::getUsuarios(null,'usuario_nm ASC');

    while($obUsuario = $resultsSelect->fetchObject(EntityUsuario::class)){
      $itensSelect .= View::render('admin/modules/usuarios/itemselect',[
        'idSelect' => $obUsuario->usuario_id,
        'selecionado' => ($id == $obUsuario->usuario_id) ? 'selected' : '',
        'siglaSelect' => $obUsuario->usuario_nm
      ]);
    }
    return $itensSelect;
  }

  /**
   * Método responsável por montar a renderização do select de usuários para o formulário
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getUsuarioItensSelectEmail($request,$id){
    $itensSelect = '';
    $resultsSelect = EntityUsuario::getUsuarios(null,'email ASC');

    while($obUsuario = $resultsSelect->fetchObject(EntityUsuario::class)){
      $itensSelect .= View::render('admin/modules/usuarios/itemselect',[
        'idSelect' => $obUsuario->usuario_id,
        'selecionado' => ($id == $obUsuario->usuario_id) ? 'selected' : '',
        'siglaSelect' => $obUsuario->email
      ]);
    }
    return $itensSelect;
  }

 /**
  * Método responsável por retornar o formulário de cadastro de um novo usuário
  * @param Request $request
  * @return string
  */
  public static function getNovoUsuario($request){

    $optionsDepartamento = AdminDepartamentos::getDepartamentoItensSelect($request,0);
    $optionsPerfil = PagesPerfil::getPerfilItensSelect($request,0);
    $optionsCargo = PagesCargo::getCargoItensSelect($request,0);
    $optionsLocali = PagesCargo::getCargoItensSelect($request,0);
    $optionsLocalizacao = AdminLocalizacoes::getLocalizacaoItensSelect($request,0);

    //CONTEÚDO DO FORMULÁRIO
    $content = View::render('admin/modules/usuarios/form',[
      'title' => 'Cadastrar Usuário',
      'nome' => '',
      'email' => '',
      'cargo' => '',
      'sala' => '',
      'contato' => '',
      'optionsDepartamento' => $optionsDepartamento,
      'optionsPerfil' => $optionsPerfil,
      'optionsCargo' => $optionsCargo,
      'optionsLocalizacao' => $optionsLocalizacao,
      'status' => self::getStatus($request)
    ]);

    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel('Cadastrar Usuário',$content,'usuarios');
  }

  /**
   * Método responsável por cadastro de um novo usuário no banco
   * @param Request $request
   * @return string
   */
   public static function setNovoUsuario($request){

     //DADOS DO POST
     $posVars = $request->getPostVars();
     $nome = $posVars['nome'] ?? '';
     $email = $posVars['email'] ?? '';
     $senha = $posVars['senha'] ?? '';

     //VERIFICA SE JÁ EXISTE O E-MAIL INFORMADO CADASTRADO NO BANCO
     $obUsuario = EntityUsuario::getUsuarioPorEmail($email);
     if($obUsuario instanceof EntityUsuario){
       //REDIRECIONA SE O E-MAIL JÁ FOR CADASTRADO NO BANCO
       $request->getRouter()->redirect('/admin/usuarios/novo?status=duplicado');
     }
     //echo "<pre>"; print_r($email); echo "<pre>"; exit;


     //NOVA ISNTANCIA DE DEPARTAMENTO
     $obUsuario = new EntityUsuario;

     ////$obUsuario::getUsuarioPorEmail($posVars['email']);

     $obUsuario->usuario_nm = $nome;
     $obUsuario->email = $email;
     $obUsuario->senha = password_hash('123456',PASSWORD_DEFAULT);
     $obUsuario->id_perfil = $posVars['perfil'];
     $obUsuario->id_cargo = $posVars['cargo'];
     $obUsuario->id_departamento = $posVars['departamento'];
     $obUsuario->sala = $posVars['sala'];
     $obUsuario->usuario_fone = $posVars['contato'];
     $obUsuario->ativo_fl = 's';
     $obUsuario->cadastrar();

     //GERA O LOG de INSERT
     Geralog::getInstance()->inserirLog("insert","O usuário ".$_SESSION['admin']['usuario']['usuario_nm']." cadastrou o usuário: " . $nome. " de e-mail ". $email);

     //REDIRECIONA O USUÁRIO
     $request->getRouter()->redirect('/admin/usuarios?status=gravado');

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
        return Alert::getSuccess('Usuário cadastrado com sucesso!');
        // code...
        break;
      case 'alterado':
        return Alert::getSuccess('Dados do usuário alterados com sucesso!');
        // code...
        break;
      case 'deletado':
        return Alert::getSuccess('Usuário deletado com sucesso!');
        // code...
        break;
      case 'duplicado':
        return Alert::getError('Já existe um usuário cadastrado com este e-mail!');
        // code...
        break;
      case 'sempermissao':
        return Alert::getError('Sem permissão para acessar está página!');
        // code...
        break;
      case 'senharesetada':
        return Alert::getSuccess('Senha do usuário resetada com sucesso!');
        // code...
        break;
      case 'statusupdate':
        return Alert::getSuccess('Status do usuário alterado com sucesso!');
        // code...
        break;
    }
   }

   /**
    * Método responsável por retornar o formulário de cadastro de um novo usuário
    * @param Request $request
    * @param integer $id
    * @return string
    */
    public static function getEditUsuario($request,$id){

      //OBTÉM O USUÁRIO DO BANCO DE DADOS
      $obUsuario = EntityUsuario::getUsuarioPorId($id);

      if(!$obUsuario instanceof EntityUsuario){
        $request->getRouter()->redirect('/admin/usuarios/novo?status=updatefail');
      }

      $optionsDepartamento = AdminDepartamentos::getDepartamentoItensSelect($request,$obUsuario->id_departamento);
      $optionsPerfil = PagesPerfil::getPerfilItensSelect($request,$obUsuario->id_perfil);
      $optionsCargo = PagesCargo::getCargoItensSelect($request,$obUsuario->id_cargo);
      $optionsLocalizacao = AdminLocalizacoes::getLocalizacaoItensSelect($request,$obUsuario->sala);

      //CONTEÚDO DO FORMULÁRIO
      $content = View::render('admin/modules/usuarios/form',[
        'title' => 'Editar Usuário',
        'nome' => $obUsuario->usuario_nm,
        'email' => $obUsuario->email,
        'sala' => $obUsuario->sala,
        'contato' => $obUsuario->usuario_fone,
        'optionsDepartamento' => $optionsDepartamento,
        'optionsPerfil' => $optionsPerfil,
        'optionsCargo' => $optionsCargo,
        'optionsLocalizacao' => $optionsLocalizacao,
        'status' => self::getStatus($request)
      ]);

      //RETORNA A PÁGINA COMPLETA
      return parent::getPanel('Editar Usuário',$content,'usuarios');
    }

    /**
     * Método responsável por gravar a edição de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function setEditUsuario($request,$id){

       //DADOS DO POST
       $posVars = $request->getPostVars();
       $nome = $posVars['nome'] ?? '';
       $email = $posVars['email'] ?? '';
       $senha = $posVars['senha'] ?? '';

       //VERIFICA SE JÁ EXISTE O E-MAIL INFORMADO CADASTRADO NO BANCO
       $obUsuarioPorEmail = EntityUsuario::getUsuarioPorEmail($email);
       $obUsuarioPorEmail->email;
       $id_a = $obUsuarioPorEmail->usuario_id;
       $id_b = $id;
       $email_a = $obUsuarioPorEmail->email;
       $email_b = $posVars['email'];

       if(strcmp($id_a,$id_b)){
         if(!strcmp($email_a,$email_b)){
           $request->getRouter()->redirect('/admin/usuarios?status=duplicado');
         }
       }

       //OBTÉM O USUÁRIO DO BANCO DE DADOS
       $obUsuario = EntityUsuario::getUsuarioPorId($id);

       if(!$obUsuario instanceof EntityUsuario){
         $request->getRouter()->redirect('/admin/usuarios?status=updatefail');
       }

       //ATUALIZA A INSTANCIA
       $obUsuario->usuario_nm = $posVars['nome'] ?? $obUsuario->usuario_nm;
       $obUsuario->email = $posVars['email'] ?? $obUsuario->email;
       $obUsuario->id_perfil = $posVars['perfil'] ?? $obUsuario->id_perfil;
       $obUsuario->id_cargo = $posVars['cargo'] ?? $obUsuario->id_cargo;
       $obUsuario->id_departamento = $posVars['departamento'] ?? $obUsuario->id_departamento;
       $obUsuario->sala = $posVars['sala'] ?? $obUsuario->sala;
       $obUsuario->usuario_fone = $posVars['contato'] ?? $obUsuario->usuario_fone;
       $obUsuario->atualizar();

       //REDIRECIONA O USUÁRIO
       $request->getRouter()->redirect('/admin/usuarios?status=alterado');

    }

    /**
      * Método responsável por retornar a página de confirmação de reset de senha de um usuário
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function getResetSenhaUsuarioModal($request,$id){

        //OBTÉM O USUÁRIO DO BANCO DE DADOS
        $obUsuario = EntityUsuario::getUsuarioPorId($id);

        //PÁGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['pagina'] ?? 1;
        $pag = '?pagina='.$paginaAtual;

        //CAMINHO ATUAL
        $uri=strstr("$_SERVER[REQUEST_URI]", '?');
        if($uri == ''){
          $uri = $pag;
        }

        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['pagina'] ?? 1;
        $busca = $queryParams['busca'] ?? '';
        $departamento = $queryParams['departamento'] ?? '';

        if(!$obUsuario instanceof EntityUsuario){
          $request->getRouter()->redirect('/admin/usuarios?status=updatefail');
        }

        //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
        $obUsuario->senha = password_hash('123456',PASSWORD_DEFAULT);
        $obUsuario->atualizar();

        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/usuarios?status=senharesetada');
      }


      /**
       * Método responsável por alterar o status (ativo/inativo) de um usuário por meio de um Modal
       * @param Request $request
       * @param integer $id
       * @return string
       */
       public static function setResetSenhaUsuario($request,$id){

         //OBTÉM O USUÁRIO DO BANCO DE DADOS
         $obUsuario = EntityUsuario::getUsuarioPorId($id);

         $uri=strstr("$_SERVER[REQUEST_URI]", '?');

         $queryParams = $request->getQueryParams();
         $paginaAtual = $queryParams['pagina'] ?? 1;

         if(!$obUsuario instanceof EntityUsuario){
           $request->getRouter()->redirect('/admin/usuarios?status=updatefail');
         }

         //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
         $obUsuario->senha = password_hash('123456',PASSWORD_DEFAULT);
         $obUsuario->atualizar();

         //REDIRECIONA O USUÁRIO
         $request->getRouter()->redirect('/admin/usuarios?status=senharesetada');

      }

      /**
       * Método responsável por retornar o formulário de alteração de status de um usuário
       * @param Request $request
       * @param integer $id
       * @return string
       */
       public static function getAltStatusUsuario($request,$id){

         //OBTÉM O USUÁRIO DO BANCO DE DADOS
         $obUsuario = EntityUsuario::getUsuarioPorId($id);

         if(!$obUsuario instanceof EntityUsuario){
           $request->getRouter()->redirect('/admin/usuarios?status=updatefail');
         }

         //CONTEÚDO DO FORMULÁRIO
         $content = View::render('admin/modules/usuarios/alterastatus',[
           'title' => 'Editar Usuário',
           'nome' => $obUsuario->usuario_nm,
           'email' => $obUsuario->email,
           'status' => self::getStatus($request),
           'departamento' => EntityDepartamento::getDepartamentoPorId($obUsuario->id_departamento)->departamento_nm,
           'texto_ativo' => ('s' == $obUsuario->ativo_fl) ? 'Desativar' : 'Ativar',
           'class_ativo' => ('s' == $obUsuario->ativo_fl) ? 'btn-warning' : 'btn-success'
         ]);

         //RETORNA A PÁGINA COMPLETA
         return parent::getPanel('Alterar Status do Usuário',$content,'usuarios');
       }

       /**
        * Método responsável por retornar o formulário de alteração de status de um usuário
        * @param Request $request
        * @param integer $id
        * @return string
        */
        public static function getAltStatusUsuarioModal($request,$id){

          //OBTÉM O USUÁRIO DO BANCO DE DADOS
          $obUsuario = EntityUsuario::getUsuarioPorId($id);

          //PÁGINA ATUAL
          $uri=strstr("$_SERVER[REQUEST_URI]", '?');

          if(!$obUsuario instanceof EntityUsuario){
            $request->getRouter()->redirect('/admin/usuarios?status=updatefail');
          }

          //CONTEÚDO DO FORMULÁRIO
          $content = View::render('admin/modules/usuarios/alterastatus',[
            'status' => self::getStatus($request),
            'paginaAtual' => $paginaAtual

          ]);

          //OBTÉM O USUÁRIO DO BANCO DE DADOS
          if($obUsuario->ativo_fl == 's'){
            $altStatus = 'n';
          } elseif ($obUsuario->ativo_fl == 'n') {
            $altStatus = 's';
          }

          //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
          $obUsuario->ativo_fl = $altStatus;
          $obUsuario->atualizar();

          //REDIRECIONA O USUÁRIO
          $request->getRouter()->redirect('/admin/usuarios?status=statusupdate');

        }

       /**
        * Método responsável por alterar o status (ATIVO/INATIVO) de um usuário
        * @param Request $request
        * @param integer $id
        * @return string
        */
        public static function setAltStatusUsuario($request,$id){

          //OBTÉM O USUÁRIO DO BANCO DE DADOS
          $obUsuario = EntityUsuario::getUsuarioPorId($id);

          if(!$obUsuario instanceof EntityUsuario){
            $request->getRouter()->redirect('/admin/usuarios?status=updatefail');
          }

          if($obUsuario->ativo_fl == 's'){
            $altStatus = 'n';
          } elseif ($obUsuario->ativo_fl == 'n') {
            $altStatus = 's';
          }

          //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
          $obUsuario->ativo_fl = $altStatus;
          $obUsuario->atualizar();

          //REDIRECIONA O USUÁRIO
          $request->getRouter()->redirect('/admin/usuarios?status=statusupdate');

       }

    /**
     * Método responsável por retornar o formulário de exclusão de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function getDeleteUsuario($request,$id){

       //OBTÉM O USUÁRIO DO BANCO DE DADOS
       $obUsuario = EntityUsuario::getUsuarioPorId($id);

       if(!$obUsuario instanceof EntityUsuario){
         $request->getRouter()->redirect('/admin/usuarios');
       }

     //CONTEÚDO DA FORMULÁRIO
       $content = View::render('admin/modules/usuarios/delete',[
         'nome' => $obUsuario->usuario_nm,
         'email' => $obUsuario->email,
         'cargo' => $obUsuario->id_cargo,
         'status' => self::getStatus($request)
       ]);

       //RETORNA A PÁGINA COMPLETA
       return parent::getPanel('Exclir Usuário',$content,'usuarios');
     }

     /**
      * Método responsável por retornar o formulário de exclusão de um usuário atraves de um Modal
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function getDeleteUsuarioModal($request,$id){

        //OBTÉM O USUÁRIO DO BANCO DE DADOS
        $obUsuario = EntityUsuario::getUsuarioPorId($id);

        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['pagina'] ?? 1;

        if(!$obUsuario instanceof EntityUsuario){
          $request->getRouter()->redirect('/admin/usuarios');
        }
       //EXCLUI O USUÁRIO
        $obUsuario->excluir();

        //GERA O LOG de DELETE
        Geralog::getInstance()->inserirLog("delete","O usuário ".$_SESSION['admin']['usuario']['usuario_nm']." excluiu o usuário: " . $obUsuario->usuario_nm. " de e-mail ". $obUsuario->email);

        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/usuarios?status=deletado');
      }

     /**
      * Método responsável por excluir um usuário
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function setDeleteUsuario($request,$id){

        //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
        $obUsuario = EntityUsuario::getUsuarioPorId($id);

        if(!$obUsuario instanceof EntityUsuario){
          $request->getRouter()->redirect('/admin/usuarios');
        }
        //EXCLUI O USUÁRIO
        $obUsuario->excluir();
        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/usuarios?status=deletado');

     }

}
