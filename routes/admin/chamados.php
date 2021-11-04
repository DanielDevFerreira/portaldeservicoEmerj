<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE CHAMADOS
$obRouter->get('/admin/chamados',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Chamados::getListChamados($request));
  }
]);

//ROTA DE LISTAGEM DE CHAMADOS
$obRouter->get('/admin/chamados_teste',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Chamados::getListChamadosTeste($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO CHAMADO
$obRouter->get('/admin/chamados_teste/json',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Chamados::getListJson($request));
  }
]);


//ROTA JSON PARA LISTAR DADOS DO USUÁRIO POR E-MAIL
$obRouter->get('/admin/chamados/jsonusuarioporid',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Chamados::getJsonUsuariosPorID($request));
  }
]);


//ROTA DE CADASTRO DE UM NOVO CHAMADO
$obRouter->get('/admin/chamados/jsoncategoria',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Chamados::getListJsonCategoria($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO CHAMADO
$obRouter->get('/admin/chamados/jsonservico',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Chamados::getListJsonServico($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO CHAMADO
$obRouter->get('/admin/chamados/jsonics',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Chamados::getListJsonIc($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO CHAMADO
$obRouter->get('/admin/chamados/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Chamados::getNovoChamado($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO CHAMADO (POST)
$obRouter->post('/admin/chamados/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Chamados::setNovoChamado($request));
  }
]);

//ROTA DE UPLOAD TEMPORÁRIO DE ARQUIVOS
$obRouter->post('/admin/chamados/uploadajax',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Chamados::setUploadAjax($request));
  }
]);

//ROTA DE UPLOAD TEMPORÁRIO DE ARQUIVOS
$obRouter->post('/admin/chamados/removeuploadajax',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Chamados::setRemoveUploadAjax($request));
  }
]);

//ROTA DE EDIÇÃO DE UM CHAMADO
$obRouter->get('/admin/chamados/{chamado_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$chamado_id){
    return new Response(200,Admin\Chamados::getEditChamado($request,$chamado_id));
  }
]);



//ROTA DE EDIÇÃO DE UM CHAMADO (POST)
$obRouter->post('/admin/chamados/{chamado_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$chamado_id){
    return new Response(200,Admin\Chamados::setEditChamado($request,$chamado_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM CHAMADO
$obRouter->get('/admin/chamados/{chamado_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$chamado_id){
    return new Response(200,Admin\Chamados::getDeleteChamado($request,$chamado_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM CHAMADO VIA MODAL
$obRouter->get('/admin/chamados/{chamado_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$chamado_id){
    return new Response(200,Admin\Chamados::getDeleteChamadoModal($request,$chamado_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM CHAMADO (POST)
$obRouter->post('/admin/chamados/{chamado_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$chamado_id){
    return new Response(200,Admin\Chamados::setDeleteChamado($request,$chamado_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UM CHAMADO
$obRouter->get('/admin/chamados/{chamado_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$chamado_id){
    return new Response(200,Admin\Chamados::getAltStatusChamadoModal($request,$chamado_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UM CHAMADO (POST)
$obRouter->post('/admin/chamados/{chamado_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$chamado_id){
    return new Response(200,Admin\Chamados::setAltStatusChamado($request,$chamado_id));
  }
]);
