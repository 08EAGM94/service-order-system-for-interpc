<?php

class BinnacleRepository implements IRepository, IBinnacleRepository{

    private $queries, $mapperModel;

    public function __construct($queries, $mapperModel){
        $this->queries = $queries;
        $this->mapperModel = $mapperModel;
    }

    public function insertInfo($entity, $entity2){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->insertBinnacle();
        $this->queries->closeConnection();
        return $result;
    }
    public function getInfo($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->getBinnacle();
        $this->queries->closeConnection();
        return $result;
    }
    public function getAllInfo($entity, $elemsKey, $binnsFiltArr, $controllerAction){

        $this->queries->setConnection();
        if(isset($entity))
            $this->queries->setModel($this->mapperModel->map($entity));
        $result = [];
        if($controllerAction === "followuplist"){ 
            $this->queries->setModel($this->mapperModel->map($entity));
            $result = $this->queries->getBinnaclesFollowUpPagination($elemsKey);
        }
            
        if($controllerAction === "binnaclesReport")
            $result = $this->queries->getBinnaclesReportPagination($elemsKey, $binnsFiltArr);

        $this->queries->closeConnection();
        return $result;
    }
    public function updateInfo($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->updateBinnacle();
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
    public function followUpPartial($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->insertFollowupPartial();
        $this->queries->closeConnection();
        return $result;
    }
    public function resetActivities($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->resetActivitiesDesc();
        $this->queries->closeConnection();
        return $result;
    }
    public function cancelBinnacle($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->cancelBinnacle();
        $this->queries->closeConnection();
        return $result;
    }
    public function finishBinnacle($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->finishBinnacle();
        $this->queries->closeConnection();
        return $result;
    }
}