<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE PÁGINAS
$obRouter->get('/admin/paginas',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Paginas::getListPaginas($request));
  }
]);

//ROTA DE UPLOAD TEMPORÁRIO DE ARQUIVOS
$obRouter->post('/admin/paginas/uploadajax',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Paginas::setUploadAjax($request));
  }
]);

//ROTA DE UPLOAD TEMPORÁRIO DE ARQUIVOS
$obRouter->post('/admin/paginas/removeuploadajax',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Paginas::setRemoveUploadAjax($request));
  }
]);

//ROTA DE CADASTRO DE UMA NOVA PÁGINA
$obRouter->get('/admin/paginas/nova',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Paginas::getNovaPagina($request));
  }
]);

//ROTA DE CADASTRO DE UMA NOVA PÁGINA (POST)
$obRouter->post('/admin/paginas/nova',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Paginas::setNovaPagina($request));
  }
]);

//ROTA DE EDIÇÃO DE UMA PÁGINA
$obRouter->get('/admin/paginas/{pagina_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$pagina_id){
    return new Response(200,Admin\Paginas::getEditPagina($request,$pagina_id));
  }
]);

//ROTA DE EDIÇÃO DE UMA PÁGINA (POST)
$obRouter->post('/admin/paginas/{pagina_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$pagina_id){
    return new Response(200,Admin\Paginas::setEditPagina($request,$pagina_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UMA PÁGINA
$obRouter->get('/admin/paginas/{pagina_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$pagina_id){
    return new Response(200,Admin\Paginas::getAltStatusPagina($request,$pagina_id));
  }
]);


//ROTA DE ALTERAÇÂO DE STATUS DE UMA PÁGINA
$obRouter->get('/admin/paginas/{pagina_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$pagina_id){
    return new Response(200,Admin\Paginas::getDeletePagina($request,$pagina_id));
  }
]);
