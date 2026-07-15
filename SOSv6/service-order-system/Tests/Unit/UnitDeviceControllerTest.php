<?php

beforeEach(function(){
    $this->base_url = 'http://localhost:8081/SOSv6/service-order-system/';
});

test('prueba método index, acceso denegado', function(){
    $value = mockDeviceIndex();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método editDevice, acceso denegado', function(){
    $value = mockEditDevice();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método devicesReport, acceso denegado', function(){
    $value = mockDevicesReport();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método insertDevice, acceso denegado', function(){
    $value = mockInsertDevice();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método updateDeviceInfo, acceso denegado', function(){
    $value = mockUpdateDeviceInfo();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método enableOrDisableDevice, acceso denegado', function(){
    $value = mockEnableOrDisableDevice();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});