<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE DEPARTAMENTOS
$obRouter->get('/admin/departamentos',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Departamentos::getListDepartamentos($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO DEPARTAMENTO
$obRouter->get('/admin/departamentos/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Departamentos::getNovoDepartamento($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO DEPARTAMENTO (POST)
$obRouter->post('/admin/departamentos/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Departamentos::setNovoDepartamento($request));
  }
]);

//ROTA DE EDIÇÃO DE UM DEPARTAMENTO
$obRouter->get('/admin/departamentos/{departamento_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$departamento_id){
    return new Response(200,Admin\Departamentos::getEditDepartamento($request,$departamento_id));
  }
]);

//ROTA DE EDIÇÃO DE UM DEPARTAMENTO VIA MODAL (POST)
$obRouter->get('/admin/departamentos/{departamento_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$departamento_id){
    return new Response(200,Admin\Departamentos::getEditDepartamentoModal($request,$departamento_id));
  }
]);

//ROTA DE EDIÇÃO DE UM DEPARTAMENTO (POST)
$obRouter->post('/admin/departamentos/{departamento_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$departamento_id){
    return new Response(200,Admin\Departamentos::setEditDepartamento($request,$departamento_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM DEPARTAMENTO
$obRouter->get('/admin/departamentos/{departamento_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$departamento_id){
    return new Response(200,Admin\Departamentos::getDeleteDepartamento($request,$departamento_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM DEPARTAMENTO VIA MODAL
$obRouter->get('/admin/departamentos/{departamento_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$departamento_id){
    return new Response(200,Admin\Departamentos::getDeleteDepartamentoModal($request,$departamento_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM DEPARTAMENTO (POST)
$obRouter->post('/admin/departamentos/{departamento_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$departamento_id){
    return new Response(200,Admin\Departamentos::setDeleteDepartamento($request,$departamento_id));
  }
]);

//ROTA DE EDIÇÃO DE UM DEPARTAMENTO VIA MODAL
$obRouter->get('/admin/departamentos/{departamento_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$departamento_id){
    return new Response(200,Admin\Departamentos::getAltStatusDepartamentoModal($request,$departamento_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UM DEPARTAMENTO (POST)
$obRouter->post('/admin/departamentos/{departamento_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$departamento_id){
    return new Response(200,Admin\Departamentos::setAltStatusDepartamento($request,$departamento_id));
  }
]);
