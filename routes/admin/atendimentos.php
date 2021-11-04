<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE ATENDIMENTOS
$obRouter->get('/admin/atendimentos',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Atendimentos::getListAtendimentos($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO ATENDIMENTO
$obRouter->get('/admin/atendimentos/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Atendimentos::getNovoAtendimento($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO ATENDIMENTO (POST)
$obRouter->post('/admin/atendimentos/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Atendimentos::setNovoAtendimento($request));
  }
]);

//ROTA DE EDIÇÃO DE UM ATENDIMENTO
$obRouter->get('/admin/atendimentos/{atendimento_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$atendimento_id){
    return new Response(200,Admin\Atendimentos::getEditAtendimento($request,$atendimento_id));
  }
]);

//ROTA DE EDIÇÃO DE UM ATENDIMENTO (POST)
$obRouter->post('/admin/atendimentos/{atendimento_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$atendimento_id){
    return new Response(200,Admin\Atendimentos::setEditAtendimento($request,$atendimento_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM ATENDIMENTO (POST)
$obRouter->get('/admin/atendimentos/{atendimento_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$atendimento_id){
    return new Response(200,Admin\Atendimentos::getDeleteAtendimento($request,$atendimento_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UM ATENDIMENTO
$obRouter->get('/admin/atendimentos/{atendimento_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$atendimento_id){
    return new Response(200,Admin\Atendimentos::getAltStatusAtendimentoModal($request,$atendimento_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UM ATENDIMENTO (POST)
$obRouter->post('/admin/atendimentos/{atendimento_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$atendimento_id){
    return new Response(200,Admin\Atendimentos::setAltStatusAtendimento($request,$atendimento_id));
  }
]);
