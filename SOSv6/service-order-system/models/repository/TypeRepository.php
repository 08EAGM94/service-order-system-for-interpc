<?php

class TypeRepository implements IRepository, ISelectRepository{

    private $queries, $mapperModel;

    public function __construct($queries, $mapperModel){
        $this->queries = $queries;
        $this->mapperModel = $mapperModel;
    }

    public function insertInfo($entity, $entity2){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->insertType();
        $this->queries->closeConnection();
        return $result;
    }
    public function getInfo($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->getType();
        $this->queries->closeConnection();
        return $result;
    }
    public function getAllInfo($dto, $elemsKey, $binnsFiltArr, $controllerAction){
        $this->queries->setConnection();
        $result = $this->queries->getTypes();
        $this->queries->closeConnection();
        return $result;
    }
    public function updateInfo($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->updateType();
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
    public function getInfoForSelects(){
        $this->queries->setConnection();
        $result = $this->queries->getTypeForSelect();
        $this->queries->closeConnection();
        return $result;
    }
}