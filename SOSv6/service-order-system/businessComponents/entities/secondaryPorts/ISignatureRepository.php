<?php

interface ISignatureRepository{
    public function insertSignature($entity);
    public function getSignature($entity);
}