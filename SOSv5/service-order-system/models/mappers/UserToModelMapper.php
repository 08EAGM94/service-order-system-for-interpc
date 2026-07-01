<?php

class UserToModelMapper implements IMapper{
    public function map($obj){
        if(get_class($obj) !== "UsuariosDTO" && 
            get_class($obj) !== "UsuariosEntity")
            throw new WrongObjectException("En el mapeador a modelo de objetos usuariosDTO y UsuariosEntity no se está pasando los objetos compatibles para este mapeador");

        return new UsuariosModel(
            (method_exists($obj, 'getId')) ? $obj->getId() : $obj->user_id,
            (method_exists($obj, 'getNombre')) ? $obj->getNombre() : $obj->nombre,
            (method_exists($obj, 'getApellidos')) ? $obj->getApellidos() : $obj->apellidos,
            (method_exists($obj, 'getAlias')) ? $obj->getAlias() : $obj->alias,
            (method_exists($obj, 'getContrasena')) ? $obj->getContrasena() : $obj->contrasena,
            (method_exists($obj, 'getPrivilegio')) ? $obj->getPrivilegio() : $obj->privilegio,
            (method_exists($obj, 'getFirma')) ? $obj->getFirma() : $obj->firma,
            (property_exists($obj, 'visibilidad')) ? $obj->visibilidad : null
        );
    }
}