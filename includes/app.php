<?php
require __DIR__.'/../vendor/autoload.php';

use \App\Utils\View;
use \App\Utils\Environment;
use \App\Http\Middleware\Queue as MiddlewareQueue;
//use \App\Db\Database;

//GARREGA AS VARIÁVEIS DE AMBIENTE
Environment::load(__DIR__.'/../');

//DEFINE AS CONFIGURAÇÕES DE BANCO DE DADOS


//DEFINE A CONSTANTE DE URL
define('URL',getenv('URL'));
//echo "<pre>";    print_r(getenv('URL'));    echo "</pre>"; exit;
View::init([
  'URL' => URL
]);

//DEFINE O MAPEAMENTO DE MIDDLEWARES
MiddlewareQueue::setMap([
'maintenance' => \App\Http\Middleware\Maintenance::class,
'require-admin-logout' => \App\Http\Middleware\RequireAdminLogout::class,
'require-admin-login' => \App\Http\Middleware\RequireAdminLogin::class
]);

//DEFINE O MAPEAMENTO DE MIDDLEWARES
MiddlewareQueue::setDefault([
'maintenance'
]);
