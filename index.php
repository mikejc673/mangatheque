<?php
require 'vendor/autoload.php';
require 'vendor/altorouter/altorouter/AltoRouter.php';

$router = new AltoRouter();

$router->setBasePath('/mangatheque');
$router->map('GET', '/', 'ControllerPage#homepage', 'homepage');

$match = $router->match();


if(is_array($match)){
    list($controller,$action) = explode("#",$match['target']);
    $obj = new $controller();
}

if(is_callable($obj,$action)){
    call_user_func_array(array($obj,$action), $match['params']);
} else{
    http_response_code(404);
}