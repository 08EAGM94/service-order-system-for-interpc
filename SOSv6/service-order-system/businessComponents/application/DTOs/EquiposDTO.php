<?php

class EquiposDTO{

    public $device_id, $empresa_id, $tipo_id, $marca, $modelo, $numero_serie,
        $numero_inventario, $visibilidad;
    public function __construct($device_id = null, $empresa_id = null, $tipo_id = null, 
        $marca = null, $modelo = null, $numero_serie = null, $numero_inventario = null, 
        $visibilidad = null){

        $this->device_id = $device_id; 
        $this->empresa_id = $empresa_id; 
        $this->tipo_id = $tipo_id; 
        $this->marca = $marca; 
        $this->modelo = $modelo; 
        $this->numero_serie = $numero_serie;
        $this->numero_inventario = $numero_inventario; 
        $this->visibilidad = $visibilidad;
    }       
}