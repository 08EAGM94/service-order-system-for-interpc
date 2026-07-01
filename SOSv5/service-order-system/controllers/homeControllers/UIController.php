<?php

class UIController{
    public function index(){
        if(!empty($_SESSION["identity"])){
            if($_SESSION["identity"]["Privilegio"] === "user"){
                $_SESSION['LAST_ACTIVITY'] = time();
                require_once '../views/userLayouts/menu.php';
            }else if(!empty($_SESSION["isAdmin"])){
                $_SESSION['LAST_ACTIVITY'] = time();
                require_once '../views/adminLayouts/welcomeMessage.php';
            }
        }else if(empty($_SESSION["identity"])){
            require_once '../views/login.php';
        }
    }
}