<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Usuario;
use \App\Model\Entity\Link as EntityLink;
use \App\Session\Admin\Login as SessionAdminLogin;
use \App\Communication\Email;
use \App\Utils\Environment;

Environment::load(__DIR__.'/../');

//DEFINE AS CONFIGURAÇÕES DE BANCO DE DADOS
define('URL_',getenv('URL'));

class Login extends PageLogin{

  const URL = URL_;

  /**
   * Método responsável pela renderização da página de login
   * @param Request
   * @param string
   * @return string
   */
  public static function getLogin($request,$errorMessage = null){

    //CONTEÚDO DO STATUS
    $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';

    //CONTEÚDO DA PÁGINA DE LOGIN
    $content = View::render('admin/login',[
      'status' => $status
    ]);

    $currentModule = '';
    $currentDepartamento = '';
    $currentPerfil = '';

    //RETORNA A PÁGINA COMPLETA
    return parent::getPage('EMERJ - Portal de Serviços DETEC - Login',$content,$currentModule,$currentDepartamento,$currentPerfil);
  }


  /**
   * Método responsável pela renderização da página de login
   * @param Request
   * @param string
   * @return string
   */
  public static function getRecuperar($request,$errorMessage = null){

    //CONTEÚDO DO STATUS
    $status = self::getStatus($request);

    //CONTEÚDO DA PÁGINA DE LOGIN
    $content = View::render('admin/recuperarsenha',[
      'status' => $status
    ]);

    $currentModule = '';
    $currentDepartamento = '';
    $currentPerfil = '';

    //RETORNA A PÁGINA COMPLETA
    return parent::getPage('LOGIN - Recuperar senha',$content,$currentModule,$currentDepartamento,$currentPerfil);
    //REDIRECIONA O USUÁRIO PARA A HOME DO ADMIN
    $request->getRouter()->redirect('/admin/recuperarsenha');
  }

  /**
   * Método responsável pela renderização da página de login
   * @param Request
   * @param string
   * @return string
   */
  public static function setRecuperar($request,$errorMessage = null){

    $status = self::getStatus($request);

    //CONTEÚDO DA PÁGINA DE LOGIN
    $content = View::render('admin/recuperar',[
      'status' => $status
    ]);

    date_default_timezone_set('America/Sao_Paulo');

    $postVars = $request->getPostVars();

    $email = filter_input(INPUT_POST, 'emailrecupera', FILTER_SANITIZE_EMAIL) ?? '';
    $acao = filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_STRING) ?? '';

    if(isset($email) && $acao == 'recuperar'){

      $obUsuario = Usuario::getUsuarioPorEmail($email);
      if(!$obUsuario instanceof Usuario) {
        $request->getRouter()->redirect('/admin/recuperar?status=usuarionaoencontrado');
      }

      if($obUsuario instanceof Usuario) {

          $nome = $obUsuario->usuario_nm;
          $codigo = base64_encode($email);
          //$codigo = hash('sha512', $email);
          $data_expirar = date('Y-m-d H:i:s', strtotime('+1 day'));

          $enderecoEmail = 'emerj.chamados@gmail.com';
          $assuntoEmail = 'Solicitação de Recuperação de senha - EMERJ';
          $corpoEmail = '<h3>Solicitação de recuperação de senha</h3><hr><p>Prezado(a) '.$nome.',<br> o Sistema de Chamados da EMERJ recebeu uma solicitação de recuperação de senha para este email, caso não tenha sido solicitado, favor desconciderar este e-mail. Caso contrário, clique no link abaixo para ser direcionado à página para prosseguir com a escolha da nova senha.</p><br><a href="'.URL_.'/admin/alterarsenha?linkalteracaosenha='.$codigo.'">Recuperar Senha</a><hr>';

          $obEmail = new Email;

          $obLink = new EntityLink;

          $obLink->link_recuperacao = $codigo;
          $obLink->data_link = $data_expirar;
          $obLink->id_usuario = $obUsuario->usuario_id;
          $obLink->ativo_fl = 's';
          $obLink->cadastrar();

          if($obLink){

            $sucesso = $obEmail->sendEmail($email,$assuntoEmail,$corpoEmail);

            if(!$sucesso){
              $strmsn = $obEmail->getError();
              $request->getRouter()->redirect('/admin/recuperar?status=falhanoenvio&strmsn='.$strmsn);
            } else {
              $request->getRouter()->redirect('/admin/recuperar?status=emailenviado');
            }

          }else{
            $request->getRouter()->redirect('/admin/recuperar?status=linknaogerado');
          }
        }

    }
  }

  /**
   * Método responsável pela renderização da página de login
   * @param Request
   * @param string
   * @return string
   */
  public static function getAlterarSenha($request,$errorMessage = null){

    //CONTEÚDO DO STATUS
    $status = self::getStatus($request);

    $currentModule = '';
    $currentDepartamento = '';
    $currentPerfil = '';

    //CONTEÚDO DA PÁGINA DE LOGIN
    $content = View::render('admin/recuperar',[
      'status' => $status
    ]);

    $queryParams = $request->getQueryParams();
    $codigo = filter_input(INPUT_GET, 'linkalteracaosenha', FILTER_SANITIZE_STRING) ?? '';

    $emailcodigo = base64_decode($codigo);

    $obUsuario = Usuario::getUsuarioPorEmail($emailcodigo);
    if(!$obUsuario instanceof Usuario) {
      $request->getRouter()->redirect('/admin/recuperar?status=usuarionaoencontrado');
    }

    if(isset($emailcodigo)){
      $obLink = EntityLink::getLink($codigo);
      if(!$obLink instanceof EntityLink) {
        return self::getLogin($request,'Link inválido.');
      }
    }

    $acao = filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_STRING) ?? '';




    //RETORNA A PÁGINA COMPLETA
    return parent::getPage('LOGIN - Nova senha',$content,$currentModule,$currentDepartamento,$currentPerfil);
    //REDIRECIONA O USUÁRIO PARA A HOME DO ADMIN
    $request->getRouter()->redirect('/admin/recuperar');
  }


  /**
   * Método responsável pela renderização da página de login
   * @param Request
   * @param string
   * @return string
   */
  public static function setAlterarSenha($request,$errorMessage = null){

    $queryParams = $request->getQueryParams();
    $codigo = filter_input(INPUT_GET, 'linkalteracaosenha', FILTER_SANITIZE_STRING) ?? '';
    $emailcodigo = base64_decode($codigo);
    if(isset($emailcodigo)){
      $obLink = EntityLink::getLink($codigo);
      if(!$obLink instanceof EntityLink) {
        return self::getLogin($request,'Link inválido.');
      }
    }

    //CONTEÚDO DO STATUS
    $status = self::getStatus($request);

    $currentModule = '';
    $currentDepartamento = '';
    $currentPerfil = '';

    //CONTEÚDO DA PÁGINA DE LOGIN
    $content = View::render('admin/recuperar',[
      'status' => $status
    ]);

    $postVars = $request->getPostVars();

    $novasenha = filter_input(INPUT_POST, 'novasenha', FILTER_SANITIZE_STRING) ?? '';
    $novasenha2 = filter_input(INPUT_POST, 'novasenhaconfirma', FILTER_SANITIZE_STRING) ?? '';

    if ($novasenha != $novasenha2) {
      $request->getRouter()->redirect('/admin/alterarsenha?linkalteracaosenha='.$codigo.'&status=emailsdiferentes');
    } else {

      $obUsuario = Usuario::getUsuarioPorEmail($emailcodigo);
      if(!$obUsuario instanceof Usuario) {
        $request->getRouter()->redirect('/admin/recuperar?status=usuarionaoencontrado');
      } else {

        //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
        $obUsuario->senha = password_hash($novasenha,PASSWORD_DEFAULT);
        $obUsuario->atualizar();

        if($obUsuario){
          $obLink->ativo_fl = 'n';
          $obLink->atualizar();

          $request->getRouter()->redirect('/admin/login?status=senhaalterada');

        }
      }
    }
  }




  /**
   * Método responsável por definir o login do usuário
   * @param Request
   * @return string
   */
  public static function setLogin($request){

    $postVars = $request->getPostVars();

    $email = $postVars['email'] ?? '';
    $senha = $postVars['senha'] ?? '';

    $obUsuario = Usuario::getUsuarioPorEmail($email);

    //echo "<pre>"; print_r($obUsuario); echo "</pre>"; exit;

    if(!$obUsuario instanceof Usuario) {
      return self::getLogin($request,'E-mail ou senha inválidos.');
    }

    if (!password_verify($senha,$obUsuario->senha)){
      return self::getLogin($request,'E-mail ou senha inválidos.');
    }

    //CRIA A SESSÃO DE LOGIN
    SessionAdminLogin::login($obUsuario);

    //REDIRECIONA O USUÁRIO PARA A HOME DO ADMIN
    $request->getRouter()->redirect('/');
  }

  /**
   * Método responsável por definir o login do usuário
   * @param $request
   */
  public static function setLogout($request){
    //DESTROI A SESSÃO DE LOGIN
    SessionAdminLogin::logout();

    //REDIRECIONA O USUÁRIO PARA A TELA DE LOGIN
    $request->getRouter()->redirect('/admin/login');
  }


  /**
   * Método responsável por retornar a mensagem de status
   * @param Request $request
   * @return string
   */
  private static function getStatus($request){
    //QUERY PARAMS
    $queryParams = $request->getQueryParams();
    $strmsn = filter_input(INPUT_GET, 'strmsn', FILTER_SANITIZE_STRING) ?? '';

    //STATUS
    if(!isset($queryParams['status'])) return '';

   //MENSAGENS DE STATUS
   switch ($queryParams['status']) {
     case 'emailenviado':
       return Alert::getSuccess('Foi enviado um e-mail com um link para recuperação de senha para o endereço informado. Obs. verifique também sua caixa de span.');
       // code...
       break;
     case 'falhanoenvio':
       return Alert::getError('Ocorreu uma falha ao tentarmos enviar o e-mail com o link de recuperação de senha!<br>Erro:'.$strmsn);
       // code...
       break;
     case 'senhaalterada':
       return Alert::getSuccess('Senha alterada com sucesso!');
       // code...
       break;
     case 'usuarionaoencontrado':
       return Alert::getError('Não foi encontrado no sistema um usuário cadastrado com este e-mail!');
       // code...
       break;
     case 'linknaogerado':
       return Alert::getError('Ocorreu uma falha na geração do link de recuperação de senha!!');
       // code...
       break;
     case 'emailsdiferentes':
       return Alert::getError('As senhas não conferem!!');
       // code...
       break;
   }
  }

}
