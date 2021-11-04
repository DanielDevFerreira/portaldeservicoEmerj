<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE SERVIÇOS
$obRouter->get('/admin/servicos',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Servicos::getListServicos($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO SERVIÇO
$obRouter->get('/admin/servicos/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Servicos::getNovoServico($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO SERVIÇO (POST)
$obRouter->post('/admin/servicos/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Servicos::setNovoServico($request));
  }
]);

//ROTA DE EDIÇÃO DE UM SERVIÇO
$obRouter->get('/admin/servicos/{servico_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$servico_id){
    return new Response(200,Admin\Servicos::getEditServico($request,$servico_id));
  }
]);

//ROTA DE EDIÇÃO DE UM SERVIÇO (POST)
$obRouter->post('/admin/servicos/{servico_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$servico_id){
    return new Response(200,Admin\Servicos::setEditServico($request,$servico_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM SERVIÇO
$obRouter->get('/admin/servicos/{servico_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$servico_id){
    return new Response(200,Admin\Servicos::getDeleteServico($request,$servico_id));
  }
]);


//ROTA DE ALTERAÇÂO DE STATUS DE UM SERVIÇO
$obRouter->get('/admin/servicos/{servico_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$servico_id){
    return new Response(200,Admin\Servicos::getAltStatusServico($request,$servico_id));
  }
]);
