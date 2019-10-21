<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
preg_match('/.*public\//', $url, $matches);
define('BASE_URL', $matches[0]);

error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

$router = new Core\Router();
$router->add('', ['controller' => 'blog', 'action' => 'show-posts']);
$router->add('{controller}/{action}');
$router->add('{controller}/{action}/{id}');
$router->dispatch($_SERVER['QUERY_STRING']);
