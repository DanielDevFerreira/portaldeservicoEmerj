<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA LOGIN
$obRouter->get('/admin/login',[
  'middlewares' => [
    'require-admin-logout'
  ],
  function($request){
    return new Response(200,Admin\Login::getLogin($request));
  }
]);

//ROTA LOGIN (POST)
$obRouter->post('/admin/login',[
  'middlewares' => [
    'require-admin-logout'
  ],
  function($request){
    //echo password_hash('123456',PASSWORD_DEFAULT); exit;
    return new Response(200,Admin\Login::setLogin($request));
  }
]);

//ROTA LOGOUT
$obRouter->get('/admin/logout',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Login::setLogout($request));
  }
]);

//ROTA RECUPERAR SENHA
$obRouter->get('/admin/recuperar',[
  'middlewares' => [
    'require-admin-logout'
  ],
  function($request){
    return new Response(200,Admin\Login::getRecuperar($request));
  }
]);

//ROTA RECUPERAR SENHA
$obRouter->post('/admin/recuperar',[
  'middlewares' => [
    'require-admin-logout'
  ],
  function($request){
    return new Response(200,Admin\Login::setRecuperar($request));
  }
]);

//ROTA RECUPERAR SENHA
$obRouter->get('/admin/alterarsenha',[
  'middlewares' => [
    'require-admin-logout'
  ],
  function($request){
    return new Response(200,Admin\Login::getAlterarSenha($request));
  }
]);

//ROTA RECUPERAR SENHA
$obRouter->post('/admin/alterarsenha',[
  'middlewares' => [
    'require-admin-logout'
  ],
  function($request){
    return new Response(200,Admin\Login::setAlterarSenha($request));
  }
]);
