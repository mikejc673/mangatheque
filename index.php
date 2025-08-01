<?php

session_start();

require 'Vendor/autoload.php';
require 'Vendor/altorouter/altorouter/AltoRouter.php';

$router = new AltoRouter();
$router->setBasePath('/mangatheque');

$router->map('GET', '/', 'ControllerPage#homePage', 'homepage');
//user
$router->map('GET', '/user/[i:id]', 'ControllerUser#oneuserById', 'userPage');
$router->map('GET', '/user/delete/[i:id]', 'ControllerUser#oneuserById', 'userDelete');
$router->map('GET|POST', '/user/update/[i:id]', 'ControllerUser#updateUser', 'userUpdateForm');

//login register logout
$router->map('GET|POST', '/register', 'ControllerAuth#register', 'register');
$router->map('GET|POST','/login','ControllerAuth#login','login');
$router->map('GET','/logout','ControllerAuth#logout','logout');

$match = $router->match();

if(is_array($match)){
    list($controller, $action) = explode("#", $match['target']);
    $obj = new $controller();

    if(is_callable(array($obj, $action))){
        call_user_func_array(array($obj, $action), $match['params']);
    }
} else {
    http_response_code(404);
}

?>
