<?php

class BinnacleService implements IBinnacleService{

    private $repository, $mapperEntity;

    public function __construct($repository, $mapperEntity){
        $this->repository = $repository;
        $this->mapperEntity = $mapperEntity;
    }
    public function getService($dto){
        return $this->repository->getService($this->mapperEntity->map($dto));
    }
    public function followUpPartial($dto){
        return $this->repository->followUpPartial($this->mapperEntity->map($dto));
    }
    public function resetActivities($dto){
        return $this->repository->resetActivities($this->mapperEntity->map($dto));
    }
    public function cancelBinnacle($dto){
        return $this->repository->cancelBinnacle($this->mapperEntity->map($dto));
    }
    public function finishBinnacle($dto){
        return $this->repository->finishBinnacle($this->mapperEntity->map($dto));
    }
}