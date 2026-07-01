<?php

class EntityException extends Exception{
    public function __construct($message){
        parent::__construct($message);
    }
}