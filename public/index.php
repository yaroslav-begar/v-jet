<?php

require dirname(__DIR__) . '/vendor/autoload.php';

define('BASE_URL', "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);

error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

$router = new Core\Router();
$router->add('', ['controller' => 'blog', 'action' => 'show-posts']);
$router->add('{controller}/{action}');
$router->add('{controller}/{action}/{id}');
$router->dispatch($_SERVER['QUERY_STRING']);
