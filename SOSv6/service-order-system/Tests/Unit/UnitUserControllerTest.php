<?php

beforeEach(function(){
    $this->base_url = 'http://localhost:8081/SOSv6/service-order-system/';
});

test('prueba método index, acceso denegado', function(){
    $value = mockUserIndex();
    expect($value)->toBe("Location: ".$this->base_url."home/");
});

test('prueba método editSign, acceso denegado', function(){
    $value = mockEditSign();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método userNewPassword, acceso denegado', function(){
    $value = mockUserNewPassword();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método insertDBUser, acceso denegado', function(){
    $value = mockInsertDBUser();
    expect($value)->toBe("Location: ".$this->base_url."home/");
});

test('prueba método updateUserPassword, acceso denegado', function(){
    $value = mockUpdateUserPassword();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método disableUser, acceso denegado', function(){
    $value = mockDisableUser();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});