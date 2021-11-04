<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE USUÁRIOS
$obRouter->get('/admin/tipodeics',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Tipodeics::getListTipodeics($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO USUÁRIOS
$obRouter->get('/admin/tipodeics/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Tipodeics::getNovoTipodeic($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO USUÁRIOS (POST)
$obRouter->post('/admin/tipodeics/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Tipodeics::setNovoTipodeic($request));
  }
]);

//ROTA DE EDIÇÃO DE UM USUÁRIOS
$obRouter->get('/admin/tipodeics/{tipodeic_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$tipodeic_id){
    return new Response(200,Admin\Tipodeics::getEditTipodeic($request,$tipodeic_id));
  }
]);

//ROTA DE EDIÇÃO DE UM USUÁRIOS (POST)
$obRouter->post('/admin/tipodeics/{tipodeic_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$tipodeic_id){
    return new Response(200,Admin\Tipodeics::setEditTipodeic($request,$tipodeic_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM TIPO DE IC
$obRouter->get('/admin/tipodeics/{tipodeic_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$tipodeic_id){
    return new Response(200,Admin\Tipodeics::getDeleteTipodeic($request,$tipodeic_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM TIPO DE IC VIA MODAL
$obRouter->get('/admin/tipodeics/{tipodeic_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$tipodeic_id){
    return new Response(200,Admin\Tipodeics::getDeleteTipodeicModal($request,$tipodeic_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM TIPO DE IC (POST)
$obRouter->post('/admin/tipodeics/{tipodeic_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$tipodeic_id){
    return new Response(200,Admin\Tipodeics::setDeleteTipodeic($request,$tipodeic_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UM TIPO DE IC
$obRouter->get('/admin/tipodeics/{tipodeic_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$tipodeic_id){
    return new Response(200,Admin\Tipodeics::getAltStatusTipodeicModal($request,$tipodeic_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UM TIPO DE IC (POST)
$obRouter->post('/admin/tipodeics/{tipodeic_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$tipodeic_id){
    return new Response(200,Admin\Tipodeics::setAltStatusTipodeic($request,$tipodeic_id));
  }
]);
