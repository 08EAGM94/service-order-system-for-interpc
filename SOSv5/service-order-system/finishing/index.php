<?php
ob_start();

header('Content-Type: text/html; charset=utf-8');
   
require_once '../config/params.php';
require_once '../vendor/autoload.php';

$container = FinishingContainerFactory::build();
FinishingUtils::setUtils($container);

require_once '../views/finishingLayouts/htmlSides/head.php';
    
if((!empty($_GET["controller"]) && $_GET["controller"] === "followupform") && class_exists(ucfirst($_GET["controller"])."Controller")){

    $controllerName = ucfirst($_GET["controller"])."Controller";
    $controlador = $container->make($controllerName);

    if(!empty($_GET["action"]) && method_exists($controlador, $_GET["action"])){
        $action = $_GET["action"];
        $controlador->$action();
    }else{
        Utils::showError($container);
    }
}else{
    Utils::showError($container);
}

require_once '../views/finishingLayouts/htmlSides/footer.php';