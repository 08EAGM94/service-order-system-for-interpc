<?php

interface ISignatureService{
    public function insertSignature($dto);
    public function getSignature($dto);
}