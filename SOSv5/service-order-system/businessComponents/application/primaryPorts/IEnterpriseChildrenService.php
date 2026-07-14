<?php

interface IEnterpriseChildrenService{
    public function insertChild($dto);
    public function getChild($dto);
    public function getChildrenByEnterForSelect($dto);
    public function getChildrenByEnterprise($dto);
    public function updateChild($dto);
    public function updateVisibility($dto);
}