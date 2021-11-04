<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE CATEGORIAS DE ICs
$obRouter->get('/admin/categoriadeics',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Categoriadeics::getListCategoriadeics($request));
  }
]);

//ROTA DE CADASTRO DE UMA NOVA CATEGORIA DE IC
$obRouter->get('/admin/categoriadeics/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Categoriadeics::getNovaCategoriadeic($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO CATEGORIAS DE ICs (POST)
$obRouter->post('/admin/categoriadeics/novo',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Categoriadeics::setNovaCategoriadeic($request));
  }
]);

//ROTA DE EDIÇÃO DE UM CATEGORIAS DE ICs
$obRouter->get('/admin/categoriadeics/{categoriadeic_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$categoriadeic_id){
    return new Response(200,Admin\Categoriadeics::getEditCategoriadeic($request,$categoriadeic_id));
  }
]);

//ROTA DE EDIÇÃO DE UMA CATEGORIA DE IC (POST)
$obRouter->post('/admin/categoriadeics/{categoriadeic_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$categoriadeic_id){
    return new Response(200,Admin\Categoriadeics::setEditCategoriadeic($request,$categoriadeic_id));
  }
]);

//ROTA DE EXCLUSÃO DE UMA LOACALIZAÇÃO
$obRouter->get('/admin/categoriadeics/{categoriadeic_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$categoriadeic_id){
    return new Response(200,Admin\Categoriadeics::getDeleteCategoriadeic($request,$categoriadeic_id));
  }
]);


//ROTA DE EXCLUSÃO DE UMA LOACALIZAÇÃO
$obRouter->post('/admin/categoriadeics/{categoriadeic_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$categoriadeic_id){
    return new Response(200,Admin\Categoriadeics::setDeleteCategoriadeic($request,$categoriadeic_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UMA LOACALIZAÇÃO (POST)
$obRouter->get('/admin/categoriadeics/{categoriadeic_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$categoriadeic_id){
    return new Response(200,Admin\Categoriadeics::getAltStatusCategoriadeic($request,$categoriadeic_id));
  }
]);
