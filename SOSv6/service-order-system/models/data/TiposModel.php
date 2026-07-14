<?php

class TiposModel{

    public $id, $tipo, $visibilidad;

    public function __construct($id = null, $tipo = null, $visibilidad = null){
        $this->id = $id;
        $this->tipo = $tipo;
        $this->visibilidad = $visibilidad;
    }
}