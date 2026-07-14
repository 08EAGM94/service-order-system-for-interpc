<?php

if(!empty($_GET["homeAction"])){
    if($_GET["homeAction"] !== "generateBinnacleReport" || $_GET["homeAction"] !== "generateDevicesReport"){
        ob_start();
    }
}else{
    ob_start();
}

header('Content-Type: text/html; charset=utf-8');

require_once '../config/params.php';
require_once '../vendor/autoload.php';

$container = HomeContainerFactory::build();
HomeUtils::setUtils($container);

require_once '../views/userLayouts/menuSides/head.php';

Utils::generateWelcomeBanner();
   
if (!empty($_GET["homeController"]) && class_exists(ucfirst($_GET["homeController"]) . "Controller")) {

    $controllerName = ucfirst($_GET["homeController"]) . "Controller";
    $controlador = $container->make($controllerName);
    
    if (!empty($_GET["homeAction"]) && method_exists($controlador, $_GET["homeAction"])) {
        $action = $_GET["homeAction"];
        $controlador->$action();
        
    } else {
        Utils::showError($container);
    }
} else {
    Utils::defaultHomePage($container);
}

Utils::setAsideWithVerify();
require_once '../views/userLayouts/menuSides/footer.php';