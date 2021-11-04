<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE PÁGINAS
$obRouter->get('/admin/sessoes',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Sessoes::getListSessoes($request));
  }
]);

//ROTA DE UPLOAD TEMPORÁRIO DE ARQUIVOS
$obRouter->post('/admin/sessoes/uploadajax',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Sessoes::setUploadAjax($request));
  }
]);

//ROTA DE UPLOAD TEMPORÁRIO DE ARQUIVOS
$obRouter->post('/admin/sessoes/removeuploadajax',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Sessoes::setRemoveUploadAjax($request));
  }
]);

//ROTA DE CADASTRO DE UMA NOVA PÁGINA
$obRouter->get('/admin/sessoes/nova',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Sessoes::getNovaSessao($request));
  }
]);

//ROTA DE CADASTRO DE UMA NOVA PÁGINA (POST)
$obRouter->post('/admin/sessoes/nova',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Sessoes::setNovaSessao($request));
  }
]);

//ROTA DE EDIÇÃO DE UMA PÁGINA
$obRouter->get('/admin/sessoes/{sessao_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$sessao_id){
    return new Response(200,Admin\Sessoes::getEditSessao($request,$sessao_id));
  }
]);

//ROTA DE EDIÇÃO DE UMA PÁGINA (POST)
$obRouter->post('/admin/sessoes/{sessao_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$sessao_id){
    return new Response(200,Admin\Sessoes::setEditSessao($request,$sessao_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UMA PÁGINA
$obRouter->get('/admin/sessoes/{sessao_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$sessao_id){
    return new Response(200,Admin\Sessoes::getAltStatusSessao($request,$sessao_id));
  }
]);


//ROTA DE ALTERAÇÂO DE STATUS DE UMA PÁGINA
$obRouter->get('/admin/sessoes/{sessao_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$sessao_id){
    return new Response(200,Admin\Sessoes::getDeleteSessao($request,$sessao_id));
  }
]);
