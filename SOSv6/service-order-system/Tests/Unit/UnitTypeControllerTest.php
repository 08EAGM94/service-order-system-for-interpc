<?php

beforeEach(function(){
    $this->base_url = 'http://localhost:8081/SOSv6/service-order-system/';
});

test('prueba método index, acceso denegado', function(){
    $value = mockTypeIndex();
    expect($value)->toBe("Location: ".$this->base_url."home/");
});

test('prueba método editTypes, acceso denegado', function(){
    $value = mockEditTypes();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método insertType, acceso denegado', function(){
    $value = mockInsertType();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método updateTypeInfo, acceso denegado', function(){
    $value = mockUpdateTypeInfo();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método enableOrDisableType, acceso denegado', function(){
    $value = mockEnableOrDisableType();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});