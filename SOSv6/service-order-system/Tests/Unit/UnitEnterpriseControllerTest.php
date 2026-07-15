<?php

beforeEach(function(){
    $this->base_url = 'http://localhost:8081/SOSv6/service-order-system/';
});

test('prueba método index, acceso denegado', function(){
    $value = mockEnterpriseIndex();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método updateEnterInfo, acceso denegado', function(){
    $value = mockUpdateEnterInfo();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método enableOrDisableEnterprise, acceso denegado', function(){
    $value = mockEnableOrDisableEnterprise();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});