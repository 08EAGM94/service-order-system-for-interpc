<?php

class SignatureService implements ISignatureService{

    private $repository, $mapperEntity;

    public function __construct($repository, $mapperEntity){
        $this->repository = $repository;
        $this->mapperEntity = $mapperEntity;
    }
    public function insertSignature($dto){
        return $this->repository->insertSignature($this->mapperEntity->map($dto));
    }
    public function getSignature($dto){
        return $this->repository->getSignature($this->mapperEntity->map($dto));
    }
}