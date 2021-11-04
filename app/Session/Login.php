<?php

namespace App\Session;

class Login{


  /**
  * Método responsável por iniciar uma sessão
  */
    private static function init(){
      if(session_status() !== PHP_SESSION_ACTIVE){
        //INICIA A SESSÃO
        session_start();
      }
    }

    /**
    * Método responsável por retornar os dados do usuário logado
    * @return array
    */
    public static function getUsuarioLogado(){
      //INICIA A SESSÃO
      self::init();
      //RETORNA OS DADOS DO USUARIO
      return self::isLogged() ? $_SESSION['usuario'] : null;

    }


    /**
    * Método responsável por logar um usuário
    * @return Usuario
    */
    public static function login($obUsuario){
        //INICIA A SESSÃO
        self::init();

        //SESSÂO DE USUÁRIO
        $_SESSION['usuario'] = [
          'usuario_id' => $obUsuario->usuario_id,
          'usuario_nm' => $obUsuario->usuario_nm,
          'id_perfil' => $obUsuario->id_perfil,
          'email' => $obUsuario->email,
        ];
        //REDIRECIONA O USUARIO PARA INDEX
        header('location: principal.php');
        exit;
    }

    /**
    * Método responsável por deslogar um usuário
    * @return Usuario
    */
    public static function logout(){
        //INICIA A SESSÃO
        self::init();
        //REMOVE A SESSAO DO USUARIO
        unset($_SESSION['usuario']);
        //redireciona para login

        header('location: login.php');
        exit;
      }


    /**
    * Método que verifica se o usuário esta logado
    * @return boolean
    */
    public static function isLogged(){
      //INICIA A SESSÃO
      self::init();
        //VALIDAÇÃO DA SEEESO
        return isset($_SESSION['usuario']['usuario_id']);
    }


    /**
    * Método responsável por obrigar o usuário a estar logado para acessar
    */
    public static function requireLogin(){
       if(!self::isLogged()){
           header('location: login.php');
           exit;
       }
    }

    /**
    * Método responsável por obrigar o usuário a estar deslogado para acessar
    */
    public static function requireLogout(){
       if(self::isLogged()){
           header('location: principal.php');
           exit;
       }
    }



}

?>
