<?php

class ContactRepository implements IByEnterpriseRepository, ISelectRepository{

    private $queries, $mapperModel;

    public function __construct($queries, $mapperModel){
        $this->queries = $queries;
        $this->mapperModel = $mapperModel;
    }

    public function insertChild($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->insertContact();
        $this->queries->closeConnection();
        return $result;
    }
    public function getChild($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->getContact();
        $this->queries->closeConnection();
        return $result;
    }
    public function getChildrenByEnterForSelect($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->getContactsByEnterForSelect();
        $this->queries->closeConnection();
        return $result;
    }
    public function getChildrenByEnterprise($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->getContactsByEnterprise();
        $this->queries->closeConnection();
        return $result;
    }
    public function updateChild($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->updateContact();
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
        $result = $this->queries->getContactsForSelect();
        $this->queries->closeConnection();
        return $result;
    }
}