<?php

interface ICommonService{
    public function insertInfo($dto, $dto2);
    public function getInfo($dto);
    public function getAllInfo($dto, $elemsKey, $binnsFiltArr, $controllerAction);
    public function updateInfo($dto);
    public function updateVisibility($dto);
}