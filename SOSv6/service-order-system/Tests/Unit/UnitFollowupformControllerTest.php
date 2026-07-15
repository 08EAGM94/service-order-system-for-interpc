<?php

beforeEach(function(){
    $this->base_url = 'http://localhost:8081/SOSv6/service-order-system/';
});

test('prueba método index, acceso denegado', function(){
    $value = mockFollowupformIndex();
    expect($value)->toBe("Location: ".$this->base_url."home/");
});

test('prueba método techsign, acceso denegado', function(){
    $value = mockTechsign();
    expect($value)->toBe("Location: ".$this->base_url."home/");
});

test('prueba método clientsign, acceso denegado', function(){
    $value = mockClientsign();
    expect($value)->toBe("Location: ".$this->base_url."home/");
});

test('prueba método followupPartial, acceso denegado', function(){
    $value = mockFollowupPartial();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método resetActivitiesDescriptions, acceso denegado', function(){
    $value = mockResetActivitiesDescriptions();
    expect($value)->toBe("Location: ".$this->base_url."home/");
});

test('prueba método cancellingBinn, acceso denegado', function(){
    $value = mockCancellingBinn();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/");
});

test('prueba método finishbinnacle, acceso denegado', function(){
    $value = mockFinishbinnacle();
    expect($value)->toBe("Location: ".$this->base_url."home/");
});