<?php

beforeEach(function(){
    $this->base_url = 'http://localhost:8081/SOSv6/service-order-system/';
});

test('prueba método index, acceso denegado', function(){
    $value = mockBinnIndex();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método followuplist, acceso denegado', function(){
    $value = mockFollowuplist();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método binnaclesReport, acceso denegado', function(){
    $value = mockBinnaclesReport();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método showBinnacle, acceso denegado', function(){
    $value = mockShowBinnacle();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método editBinnacle, acceso denegado', function(){
    $value = mockEditBinnacle();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método binninsertion, acceso denegado', function(){
    $value = mockBinninsertion();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método updateBinnacleInfo, acceso denegado', function(){
    $value = mockUpdateBinnacleInfo();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método enableOrDisableBinn, acceso denegado', function(){
    $value = mockEnableOrDisableBinn();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});