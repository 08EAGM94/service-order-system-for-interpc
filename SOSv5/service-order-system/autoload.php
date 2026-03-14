<?php
    function app_autoloader($class_name){
        $file = '../models/'.$class_name.".php";
        if(file_exists($file)){
            require_once $file;
        }
    }
    spl_autoload_register("app_autoloader");


