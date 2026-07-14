<?php

class ContactToModelMapper implements IMapper{
    public function map($obj){
        if(get_class($obj) !== "ContactosDTO" &&
            get_class($obj) !== "ContactosEntity")
            throw new WrongObjectException("En el mapeador a modelo de objetos ContactosDTO y ContactosEntity no se está pasando los objetos compatibles para este mapeador");
        
        return new ContactosModel(
            (method_exists($obj, 'getId')) ? $obj->getId() : $obj->contact_id,
            (method_exists($obj, 'getEmpresaId')) ? $obj->getEmpresaId() : $obj->empresa_id,
            (method_exists($obj, 'getNombreCompleto')) ? $obj->getNombreCompleto() : $obj->nombre_completo,
            (property_exists($obj, 'visibilidad')) ? $obj->visibilidad : null
        );
    }
}