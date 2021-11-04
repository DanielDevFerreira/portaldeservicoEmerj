<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE ICS
$obRouter->get('/admin/itensconfs',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Itensconfs::getListItensconfs($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO IC
$obRouter->get('/admin/itensconfs/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Itensconfs::getNovoItensconf($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO ICS (POST)
$obRouter->post('/admin/itensconfs/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Itensconfs::setNovoItensconf($request));
  }
]);

//ROTA DE EDIÇÃO DE UM ICS
$obRouter->get('/admin/itensconfs/{itensconf_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$itensconf_id){
    return new Response(200,Admin\Itensconfs::getEditItensconf($request,$itensconf_id));
  }
]);

//ROTA DE EDIÇÃO DE UM ICS (POST)
$obRouter->post('/admin/itensconfs/{itensconf_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$itensconf_id){
    return new Response(200,Admin\Itensconfs::setEditItensconf($request,$itensconf_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM ICS
$obRouter->get('/admin/itensconfs/{itensconf_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$itensconf_id){
    return new Response(200,Admin\Itensconfs::getDeleteItensconf($request,$itensconf_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM IC VIA MODAL
$obRouter->get('/admin/itensconfs/{itensconf_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$itensconf_id){
    return new Response(200,Admin\Itensconfs::getDeleteItensconfModal($request,$itensconf_id));
  }
]);

//ROTA DE EXCLUSÃO DE UM ICS (POST)
$obRouter->post('/admin/itensconfs/{itensconf_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$itensconf_id){
    return new Response(200,Admin\Itensconfs::setDeleteItensconf($request,$itensconf_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UM IC
$obRouter->get('/admin/itensconfs/{itensconf_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$itensconf_id){
    return new Response(200,Admin\Itensconfs::getAltStatusItensconfModal($request,$itensconf_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UM IC (POST)
$obRouter->post('/admin/itensconfs/{itensconf_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$itensconf_id){
    return new Response(200,Admin\Itensconfs::setAltStatusItensconf($request,$itensconf_id));
  }
]);
