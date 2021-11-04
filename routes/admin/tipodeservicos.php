<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE USUÁRIOS
$obRouter->get('/admin/tipodeservicos',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Tipodeservicos::getListTipodeservicos($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO USUÁRIOS
$obRouter->get('/admin/tipodeservicos/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Tipodeservicos::getNovoTipodeservico($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO USUÁRIOS (POST)
$obRouter->post('/admin/tipodeservicos/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Tipodeservicos::setNovoTipodeservico($request));
  }
]);

//ROTA DE EDIÇÃO DE UM USUÁRIOS
$obRouter->get('/admin/tipodeservicos/{tipodeservico_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$tipodeservico_id){
    return new Response(200,Admin\Tipodeservicos::getEditTipodeservico($request,$tipodeservico_id));
  }
]);

//ROTA DE EDIÇÃO DE UM USUÁRIOS (POST)
$obRouter->post('/admin/tipodeservicos/{tipodeservico_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$tipodeservico_id){
    return new Response(200,Admin\Tipodeservicos::setEditTipodeservico($request,$tipodeservico_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM TIPO DE IC
$obRouter->get('/admin/tipodeservicos/{tipodeservico_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$tipodeservico_id){
    return new Response(200,Admin\Tipodeservicos::getDeleteTipodeservico($request,$tipodeservico_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM TIPO DE IC VIA MODAL
$obRouter->get('/admin/tipodeservicos/{tipodeservico_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$tipodeservico_id){
    return new Response(200,Admin\Tipodeservicos::getDeleteTipodeservicoModal($request,$tipodeservico_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM TIPO DE IC (POST)
$obRouter->post('/admin/tipodeservicos/{tipodeservico_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$tipodeservico_id){
    return new Response(200,Admin\Tipodeservicos::setDeleteTipodeservico($request,$tipodeservico_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UM TIPO DE IC
$obRouter->get('/admin/tipodeservicos/{tipodeservico_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$tipodeservico_id){
    return new Response(200,Admin\Tipodeservicos::getAltStatusTipodeservicoModal($request,$tipodeservico_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UM TIPO DE IC (POST)
$obRouter->post('/admin/tipodeservicos/{tipodeservico_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$tipodeservico_id){
    return new Response(200,Admin\Tipodeservicos::setAltStatusTipodeservico($request,$tipodeservico_id));
  }
]);
