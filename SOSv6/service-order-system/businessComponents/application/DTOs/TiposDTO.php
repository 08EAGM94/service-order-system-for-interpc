<?php

class TiposDTO{

    public $type_id, $tipo, $visibilidad;
    public function __construct($type_id = null, $tipo = null, $visibilidad = null){
        $this->type_id = $type_id; 
        $this->tipo = $tipo;
        $this->visibilidad = $visibilidad;
    }
}