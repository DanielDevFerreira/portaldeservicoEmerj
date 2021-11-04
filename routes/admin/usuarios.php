<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE USUÁRIOS
$obRouter->get('/admin/usuarios',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Usuarios::getListUsuarios($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO USUÁRIOS
$obRouter->get('/admin/usuarios/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Usuarios::getNovoUsuario($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO USUÁRIOS (POST)
$obRouter->post('/admin/usuarios/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Usuarios::setNovoUsuario($request));
  }
]);

//ROTA DE EDIÇÃO DE UM USUÁRIOS
$obRouter->get('/admin/usuarios/{usuario_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$usuario_id){
    return new Response(200,Admin\Usuarios::getEditUsuario($request,$usuario_id));
  }
]);

//ROTA DE EDIÇÃO DE UM USUÁRIOS (POST)
$obRouter->post('/admin/usuarios/{usuario_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$usuario_id){
    return new Response(200,Admin\Usuarios::setEditUsuario($request,$usuario_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM USUÁRIOS
$obRouter->get('/admin/usuarios/{usuario_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$usuario_id){
    return new Response(200,Admin\Usuarios::getDeleteUsuario($request,$usuario_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM USUÁRIO VIA MODAL
$obRouter->get('/admin/usuarios/{usuario_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$usuario_id){
    return new Response(200,Admin\Usuarios::getDeleteUsuarioModal($request,$usuario_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM USUÁRIOS (POST)
$obRouter->post('/admin/usuarios/{usuario_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$usuario_id){
    return new Response(200,Admin\Usuarios::setDeleteUsuario($request,$usuario_id));
  }
]);

//ROTA PARA RESETAR SENHA DE UM USUÁRIO
$obRouter->get('/admin/usuarios/{usuario_id}/reset',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$usuario_id){
    return new Response(200,Admin\Usuarios::getResetSenhaUsuarioModal($request,$usuario_id));
  }
]);

//ROTA PARA RESETAR SENHA DE UM USUÁRIO (POST)
$obRouter->post('/admin/usuarios/{usuario_id}/reset',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$usuario_id){
    return new Response(200,Admin\Usuarios::setResetSenhaUsuario($request,$usuario_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UM USUÁRIO
$obRouter->get('/admin/usuarios/{usuario_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$usuario_id){
    return new Response(200,Admin\Usuarios::getAltStatusUsuarioModal($request,$usuario_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UM USUÁRIO (POST)
$obRouter->post('/admin/usuarios/{usuario_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$usuario_id){
    return new Response(200,Admin\Usuarios::setAltStatusUsuario($request,$usuario_id));
  }
]);
