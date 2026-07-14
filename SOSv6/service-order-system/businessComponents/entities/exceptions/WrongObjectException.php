<?php

class WrongObjectException extends Exception{
    public function __construct($message){
        parent::__construct($message);
    }
}