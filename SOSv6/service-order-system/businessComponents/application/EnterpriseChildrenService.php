<?php

class EnterpriseChildrenService implements IEnterpriseChildrenService{

    private $repository, $mapperEntity, $mapperModel;

    public function __construct($repository, $mapperEntity, $mapperModel){
        $this->repository = $repository;
        $this->mapperEntity = $mapperEntity;
        $this->mapperModel = $mapperModel;
    }
    public function insertChild($dto){
        return $this->repository->insertChild($this->mapperEntity->map($dto));
    }
    public function getChild($dto){
        return $this->repository->getChild($this->mapperEntity->map($dto));
    }
    public function getChildrenByEnterForSelect($dto){
        return $this->repository->getChildrenByEnterForSelect($this->mapperEntity->map($dto));
    }
    public function getChildrenByEnterprise($dto){
        return $this->repository->getChildrenByEnterprise($this->mapperEntity->map($dto));
    }
    public function updateChild($dto){
        return $this->repository->updateChild($this->mapperEntity->map($dto));
    }
    public function updateVisibility($dto){
        if($dto->visibilidad !== "ENABLED" && 
        $dto->visibilidad !== "DISABLED")
            throw new Exception();
        return $this->repository->updateVisibility($this->mapperModel->map($dto));
    }
}