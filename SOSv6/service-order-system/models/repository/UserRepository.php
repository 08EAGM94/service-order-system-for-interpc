<?php

class UserRepository implements IRepository, ISignatureRepository, 
    IUserRepository{

    private $queries, $mapperModel;

    public function __construct($queries, $mapperModel){
        $this->queries = $queries;
        $this->mapperModel = $mapperModel;
    }
    public function insertInfo($entity, $entity2){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->insertUser();
        $this->queries->closeConnection();
        return $result;
    }
    public function getInfo($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->getUser();
        $this->queries->closeConnection();
        return $result;
    }
    public function getAllInfo($dto, $elemsKey, $binnsFiltArr, $controllerAction){
        $this->queries->setConnection();
        $result = $this->queries->getUsers();
        $this->queries->closeConnection();
        return $result;
    }
    public function updateInfo($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->updatePassword();
        $this->queries->closeConnection();
        return $result;
    }
    public function updateVisibility($model){
        $this->queries->setConnection();
        $this->queries->setModel($model);
        $result = $this->queries->updateVisibilityById();
        $this->queries->closeConnection();
        return $result;
    }
    public function insertSignature($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->insertSignature();
        $this->queries->closeConnection();
        return $result;
    }
    public function getSignature($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->getUserSign();
        $this->queries->closeConnection();
        return $result;
    }
    public function login($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->login();
        $this->queries->closeConnection();
        return $result;
    }
    public function adminPwdConfirmation($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->adminPwdConfirmation();
        $this->queries->closeConnection();
        return $result;
    }
}