<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE NOTICÍAS
$obRouter->get('/admin/noticias',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Noticias::getListNoticias($request));
  }
]);

//ROTA DE UPLOAD TEMPORÁRIO DE ARQUIVOS
$obRouter->post('/admin/noticias/uploadajax',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Noticias::setUploadAjax($request));
  }
]);

//ROTA DE UPLOAD TEMPORÁRIO DE ARQUIVOS
$obRouter->post('/admin/noticias/removeuploadajax',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Noticias::setRemoveUploadAjax($request));
  }
]);

//ROTA DE CADASTRO DE UMA NOVA NOTICÍA
$obRouter->get('/admin/noticias/nova',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Noticias::getNovaNoticia($request));
  }
]);

//ROTA DE CADASTRO DE UMA NOVA NOTICÍA (POST)
$obRouter->post('/admin/noticias/nova',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request){
    return new Response(200,Admin\Noticias::setNovaNoticia($request));
  }
]);

//ROTA DE EDIÇÃO DE UMA NOTICÍA
$obRouter->get('/admin/noticias/{noticia_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$noticia_id){
    return new Response(200,Admin\Noticias::getEditNoticia($request,$noticia_id));
  }
]);

//ROTA DE EDIÇÃO DE UMA NOTICÍA (POST)
$obRouter->post('/admin/noticias/{noticia_id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$noticia_id){
    return new Response(200,Admin\Noticias::setEditNoticia($request,$noticia_id));
  }
]);

//ROTA DE ALTERAÇÂO DE STATUS DE UMA NOTICÍA
$obRouter->get('/admin/noticias/{noticia_id}/alterastatus',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$noticia_id){
    return new Response(200,Admin\Noticias::getAltStatusNoticia($request,$noticia_id));
  }
]);


//ROTA DE ALTERAÇÂO DE STATUS DE UMA NOTICÍA
$obRouter->get('/admin/noticias/{noticia_id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request,$noticia_id){
    return new Response(200,Admin\Noticias::getDeleteNoticia($request,$noticia_id));
  }
]);
