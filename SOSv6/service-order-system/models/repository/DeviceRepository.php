<?php

class DeviceRepository implements IByEnterpriseRepository{

    private $queries, $mapperModel;

    public function __construct($queries, $mapperModel){
        $this->queries = $queries;
        $this->mapperModel = $mapperModel;
    }

    public function insertChild($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->insertDevice();
        $this->queries->closeConnection();
        return $result;
    }
    public function getChild($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->getDevice();
        $this->queries->closeConnection();
        return $result;
    }
    public function getChildrenByEnterForSelect($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->getDevicesForSelectByEnterprise();
        $this->queries->closeConnection();
        return $result;
    }
    public function getChildrenByEnterprise($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->getDevicesByEnterprise();
        $this->queries->closeConnection();
        return $result;
    }
    public function updateChild($entity){
        $this->queries->setConnection();
        $this->queries->setModel($this->mapperModel->map($entity));
        $result = $this->queries->updateDevice();
        $this->queries->closeConnection();
        return $result;
    }
    public function updateVisibility($model){
        $this->queries->setConnection();
        $this->queries->setModel($model);
        $result = $this->queries->updateVisibiliyById();
        $this->queries->closeConnection();
        return $result;
    }
}