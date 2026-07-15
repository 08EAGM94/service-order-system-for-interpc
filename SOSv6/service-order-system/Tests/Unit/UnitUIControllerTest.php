<?php

beforeEach(function(){
    $this->base_url = 'http://localhost:8081/SOSv6/service-order-system/';
});

test('prueba método index, sin sesión definida', function(){
    $value = mockUIindex();
    expect($value)->toBe('../views/login.php');
});