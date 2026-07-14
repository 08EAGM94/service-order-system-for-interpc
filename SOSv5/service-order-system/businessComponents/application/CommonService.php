<?php

class CommonService implements ICommonService{
    
    private $repository, $mapperEntity, $mapperEntity2, $mapperModel;

    public function __construct($repository, $mapperEntity, 
        $mapperModel, $mapperEntity2 = null){
        $this->repository = $repository;
        $this->mapperEntity = $mapperEntity;
        $this->mapperEntity2 = $mapperEntity2;
        $this->mapperModel = $mapperModel;
    }
    public function insertInfo($dto, $dto2 = null){
        return $this->repository->insertInfo($this->mapperEntity->map($dto),
        (isset($dto2)) ? $this->mapperEntity2->map($dto2) : null);
    }
    public function getInfo($dto){
        return $this->repository->getInfo($this->mapperEntity->map($dto));
    }
    public function getAllInfo(
        $dto = null,
        $elemsKey = null,
        $binnsFiltArr = null,
        $controllerAction = null
    ){
        return $this->repository->getAllInfo(
            (isset($dto)) ? $this->mapperEntity->map($dto) : null,
            (isset($elemsKey)) ? $elemsKey : null,
            (isset($binnsFiltArr)) ? $binnsFiltArr : null,
            (isset($controllerAction)) ? $controllerAction : null
        );
    }
    public function updateInfo($dto){
        return $this->repository->updateInfo($this->mapperEntity->map($dto));
    }
    public function updateVisibility($dto){
        if($dto->visibilidad !== "ENABLED" && 
        $dto->visibilidad !== "DISABLED")
            throw new AutomaticValueException("No se debe manipular las cadenas de texto de la visibilidad");
        return $this->repository->updateVisibility($this->mapperModel->map($dto));
    }
}