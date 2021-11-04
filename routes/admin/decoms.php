<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE SERVIÇOS
$obRouter->get('/admin/decoms',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Decoms::getListDecoms($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO SERVIÇO
$obRouter->get('/admin/decoms/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Decoms::getNovoDecom($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO SERVIÇO (POST)
$obRouter->post('/admin/decoms/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Decoms::setNovoDecom($request));
  }
]);

//ROTA DE EDIÇÃO DE UM SERVIÇO
$obRouter->get('/admin/decoms/{decom_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$decom_id){
    return new Response(200,Admin\Decoms::getEditDecom($request,$decom_id));
  }
]);

//ROTA DE EDIÇÃO DE UM SERVIÇO (POST)
$obRouter->post('/admin/decoms/{decom_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$decom_id){
    return new Response(200,Admin\Decoms::setEditDecom($request,$decom_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UM SERVIÇO
$obRouter->get('/admin/decoms/{decom_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$decom_id){
    return new Response(200,Admin\Decoms::getAltStatusDecom($request,$decom_id));
  }
]);


//ROTA DE ALTERAÇÂO DE STATUS DE UM SERVIÇO
$obRouter->get('/admin/decoms/{decom_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$decom_id){
    return new Response(200,Admin\Decoms::getDeleteDecom($request,$decom_id));
  }
]);
