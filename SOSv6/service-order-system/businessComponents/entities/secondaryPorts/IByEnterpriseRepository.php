<?php

interface IByEnterpriseRepository{
    public function insertChild($entity);
    public function getChild($entity);
    public function getChildrenByEnterForSelect($entity);
    public function getChildrenByEnterprise($entity);
    public function updateChild($entity);
    public function updateVisibility($model);
}