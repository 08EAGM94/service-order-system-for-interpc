<?php

class SelectService implements ISelectService{

    private $repository;

    public function __construct($repository){
        $this->repository = $repository;
    }

    public function getInfoForSelects(){
        return $this->repository->getInfoForSelects();
    }
}