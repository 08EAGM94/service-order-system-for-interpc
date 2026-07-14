<?php

class UnknownInDataBaseException extends Exception{
    public function __construct($message){
        parent::__construct($message);
    }
}