<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE LOCALIZAÇÕES
$obRouter->get('/admin/localizacoes',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Localizacoes::getListLocalizacoes($request));
  }
]);

//ROTA DE CADASTRO DE UMA NOVA LOCALIZAÇÃO
$obRouter->get('/admin/localizacoes/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Localizacoes::getNovaLocalizacao($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO LOCALIZAÇÕES (POST)
$obRouter->post('/admin/localizacoes/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Localizacoes::setNovaLocalizacao($request));
  }
]);

//ROTA DE EDIÇÃO DE UM LOCALIZAÇÕES
$obRouter->get('/admin/localizacoes/{localizacao_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$localizacao_id){
    return new Response(200,Admin\Localizacoes::getEditLocalizacao($request,$localizacao_id));
  }
]);

//ROTA DE EDIÇÃO DE UMA LOCALIZAÇÃO (POST)
$obRouter->post('/admin/localizacoes/{localizacao_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$localizacao_id){
    return new Response(200,Admin\Localizacoes::setEditLocalizacao($request,$localizacao_id));
  }
]);

//ROTA DE EXCLUSÃO DE UMA LOACALIZAÇÃO
$obRouter->get('/admin/localizacoes/{localizacao_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$localizacao_id){
    return new Response(200,Admin\Localizacoes::getDeleteLocalizacao($request,$localizacao_id));
  }
]);


//ROTA DE EXCLUSÃO DE UMA LOACALIZAÇÃO
$obRouter->post('/admin/localizacoes/{localizacao_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$localizacao_id){
    return new Response(200,Admin\Localizacoes::setDeleteLocalizacao($request,$localizacao_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UMA LOACALIZAÇÃO (POST)
$obRouter->get('/admin/localizacoes/{localizacao_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$localizacao_id){
    return new Response(200,Admin\Localizacoes::getAltStatusLocalizacao($request,$localizacao_id));
  }
]);
