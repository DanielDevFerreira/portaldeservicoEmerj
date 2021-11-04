<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE PÁGINAS
$obRouter->get('/admin/subsessoes',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Subsessoes::getListSubsessoes($request));
  }
]);

//ROTA DE UPLOAD TEMPORÁRIO DE ARQUIVOS
$obRouter->post('/admin/subsessoes/uploadajax',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Subsessoes::setUploadAjax($request));
  }
]);

//ROTA DE UPLOAD TEMPORÁRIO DE ARQUIVOS
$obRouter->post('/admin/subsessoes/removeuploadajax',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Subsessoes::setRemoveUploadAjax($request));
  }
]);

//ROTA DE CADASTRO DE UMA NOVA PÁGINA
$obRouter->get('/admin/subsessoes/nova',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Subsessoes::getNovaSubsessao($request));
  }
]);

//ROTA DE CADASTRO DE UMA NOVA PÁGINA (POST)
$obRouter->post('/admin/subsessoes/nova',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Subsessoes::setNovaSubsessao($request));
  }
]);

//ROTA DE EDIÇÃO DE UMA PÁGINA
$obRouter->get('/admin/subsessoes/{subsessao_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$subsessao_id){
    return new Response(200,Admin\Subsessoes::getEditSubsessao($request,$subsessao_id));
  }
]);

//ROTA DE EDIÇÃO DE UMA PÁGINA (POST)
$obRouter->post('/admin/subsessoes/{subsessao_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$subsessao_id){
    return new Response(200,Admin\Subsessoes::setEditSubsessao($request,$subsessao_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UMA PÁGINA
$obRouter->get('/admin/subsessoes/{subsessao_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$subsessao_id){
    return new Response(200,Admin\Subsessoes::getAltStatusSubsessao($request,$subsessao_id));
  }
]);


//ROTA DE ALTERAÇÂO DE STATUS DE UMA PÁGINA
$obRouter->get('/admin/subsessoes/{subsessao_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$subsessao_id){
    return new Response(200,Admin\Subsessoes::getDeleteSubsessao($request,$subsessao_id));
  }
]);
