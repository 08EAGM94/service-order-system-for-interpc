<?php

class UsuariosDTO{

    public $user_id, $nombre, $apellidos, $alias, $contrasena, $firma, $privilegio, 
           $visibilidad, $conf_pwd, $admin_nickname, $admin_pwd;
    public function __construct($user_id = null, $nombre = null, $apellidos = null, 
        $alias = null, $contrasena = null, $firma = null, $privilegio = null, 
        $visibilidad = null, $conf_pwd = null, $admin_nickname = null, 
        $admin_pwd = null){

        $this->user_id = $user_id; 
        $this->nombre = $nombre; 
        $this->apellidos = $apellidos;
        $this->alias = $alias;
        $this->contrasena = $contrasena;
        $this->firma = $firma; 
        $this->privilegio = $privilegio; 
        $this->visibilidad = $visibilidad; 
        $this->conf_pwd = $conf_pwd; 
        $this->admin_nickname = $admin_nickname;
        $this->admin_pwd = $admin_pwd;    
    }   
}