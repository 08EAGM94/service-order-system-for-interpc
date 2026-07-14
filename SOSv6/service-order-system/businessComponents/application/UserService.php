<?php

class UserService{

    private $repository, $mapperEntity;

    public function __construct($repository, $mapperEntity){
        $this->repository = $repository;
        $this->mapperEntity = $mapperEntity;
    }

    public function login($dto){
        return $this->repository->login($this->mapperEntity->map($dto));
    }
    public function adminPwdConfirmation($dto){
        return $this->repository->adminPwdConfirmation($this->mapperEntity->map($dto));
    }
}