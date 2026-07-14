<?php

class UsuariosModel{

    public $id, $nombre, $apellidos, $alias, $contrasena, $privilegio, 
        $firma, $visibilidad;
    
    public function __construct($id = null, $nombre = null, $apellidos = null, $alias = null, 
        $contrasena = null, $privilegio = null, $firma = null, $visibilidad = null){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->alias = $alias;
        $this->contrasena = $contrasena;
        $this->privilegio = $privilegio;
        $this->firma = $firma;
        $this->visibilidad = $visibilidad;
    }
}