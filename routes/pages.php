<?php

use \App\Http\Response;
use \App\Controller\Pages;

//ROTA HOME
$obRouter->get('/',[
  function(){
    return new Response(200,Pages\Home::getHome());
  }
]);

//ROTA SOBRE
$obRouter->get('/sobre',[
  function(){
    return new Response(200,Pages\About::getAbout());
  }
]);

//ROTA DE NOTICIAS
$obRouter->get('/noticias',[
  function($request,$obPagination){
    return new Response(200,Pages\Site::getNoticiasCapa($request,$obPagination));
  }
]);

//ROTA DA PÁGINA DE DETALHES DA NOTICIA
$obRouter->get('/noticia/{id}',[
  function($request,$id){
    return new Response(200,Pages\Site::getNoticiaDetalhe($request,$id));
  }
]);

//ROTA DE EVENTOS
$obRouter->get('/eventos',[
  function($request,$obPagination){
    return new Response(200,Pages\Site::getEventosCapa($request,$obPagination));
  }
]);

//ROTA DA PÁGINA DE DETALHES DA EVENTO
$obRouter->get('/evento/{codigo}',[
  function($request,$codigo){
    return new Response(200,Pages\Site::getEventoDetalhe($request,$codigo));
  }
]);

//ROTA DEPARTAMENTOS (INSERT)
$obRouter->get('/decom',[
  function($request){
    return new Response(200,Pages\Decom::getDecom($request));
  }
]);


//ROTA REQUISIÇÕES
$obRouter->get('/site',[
  function(){
    return new Response(200,Pages\Site::getSite());
  }
]);

//ROTA REQUISIÇÕES (INSERT)
$obRouter->post('/requisicoes',[
  function($request){
    return new Response(200,Pages\Requisicao::postRequisicao());
  }
]);

//ROTA USUÁRIOS
$obRouter->get('/usuario',[
  function(){
    return new Response(200,Pages\Usuario::getUsuario());
  }
]);

//ROTA USUÁRIOS (INSERT)
$obRouter->post('/usuario',[
  function($request){
    return new Response(200,Pages\Usuario::getUsuario());
  }
]);

//ROTA DINÂMICA
$obRouter->get('/pagina/{pagina}/{sessao}/{subsessao}',[
  function($pagina,$sessao,$subsessao){
    return new Response(200,Pages\Site::getConteudoSubsessao($pagina,$sessao,$subsessao));
  }
]);

//ROTA DINÂMICA
$obRouter->get('/pagina/{pagina}/{sessao}',[
  function($pagina,$sessao){
    return new Response(200,Pages\Site::getConteudoSessao($pagina,$sessao));
  }
]);

//ROTA PROCESSA A PESQUISA DE USUÁRIOS POR E-MAIL
$obRouter->get('/proc_pesq_msg',[
  function($request){
    return new Response(200,Pages\Usuario::getUsuario($request));
  }
]);

// ROTA PARA RETORNAR UM JSON COM TODAS AS DATAS CADASTRADAS NO BANCO PARA O CALENDARIO DO SITE
$obRouter->get('/calendario', [
  function($request){
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/json; charset=utf-8');
    return new Response(200, json_encode(Pages\Calendario::getEventosCalendario($request)));
  }
]);

//ROTA DINÂMICA
$obRouter->get('/tjrj',[
  function(){
    header('location:http://www.tjrj.jus.br/');
  }
]);
