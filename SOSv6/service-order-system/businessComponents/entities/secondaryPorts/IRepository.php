<?php

interface IRepository{
    public function insertInfo($entity, $entity2);
    public function getInfo($entity);
    public function getAllInfo($entity, $elemsKey, $binnsFiltArr, $controllerAction);
    public function updateInfo($entity);
    public function updateVisibility($model);
}