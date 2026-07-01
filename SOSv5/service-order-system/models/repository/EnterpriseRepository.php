<?php

class EnterpriseRepository implements IRepository, ISelectRepository{

    private $queries, $enterpriseMapperModel, $contactMapperModel;

    public function __construct($queries, $enterpriseMapperModel, $contactMapperModel){
        $this->queries = $queries;
        $this->enterpriseMapperModel = $enterpriseMapperModel;
        $this->contactMapperModel = $contactMapperModel;
    }

    public function insertInfo($enterpriseEntity, $contactEntity){
        $this->queries->setConnection();
        $this->queries->setModel($this->enterpriseMapperModel->map($enterpriseEntity));
        $this->queries->setContactModel($this->contactMapperModel
            ->map($contactEntity));
        $result = $this->queries->twoModelsTransaction();
        $this->queries->closeConnection();
        return $result;
    }
    public function getInfo($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->enterpriseMapperModel->map($entity));
        $result = $this->queries->getEnterprise();
        $this->queries->closeConnection();
        return $result;
    }
    public function getAllInfo($dto, $elemsKey, $binnsFiltArr, $controllerAction){
        $this->queries->setConnection();
        $result = $this->queries->getEnterprises();
        $this->queries->closeConnection();
        return $result;
    }
    public function updateInfo($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->enterpriseMapperModel->map($entity));
        $result = $this->queries->updateEnterprise();
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
        $result = $this->queries->getEnterprisesForSelect();
        $this->queries->closeConnection();
        return $result;
    }
}