<?php
    /*El controlador Error es el más simple del proyecto, solo contiene el método index el cual importa una vista 
     * html con el mensaje "La página que buscas no existe"*/
    class ErrorController{
        public function index(){
            require_once '../views/error.php';
        }
    }