<?php

beforeEach(function(){
    $this->base_url = 'http://localhost:8081/SOSv5/service-order-system/';
});

test('prueba método index, acceso denegado', function(){
    $value = mockContactIndex();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método insertContact, acceso denegado', function(){
    $value = mockInsertContact();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método updateContactInfo, acceso denegado', function(){
    $value = mockUpdateContactInfo();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método enableOrDisableContact, acceso denegado', function(){
    $value = mockEnableOrDisableContact();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});