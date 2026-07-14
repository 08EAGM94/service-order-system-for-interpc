<?php

class TypeToModelMapper implements IMapper{
    public function map($obj){
        if(get_class($obj) !== "TiposDTO" &&
            get_class($obj) !== "TiposEntity")
            throw new WrongObjectException("En el mapeador a modelo de objetos TiposDTO y TiposEntity no se está pasando los objetos compatibles para este mapeador");
        
        return new TiposModel(
            (method_exists($obj, 'getId')) ? $obj->getId() : $obj->type_id,
            (method_exists($obj, 'getTipo')) ? $obj->getTipo() : $obj->tipo,
            (property_exists($obj, 'visibilidad')) ? $obj->visibilidad : null
        );
    }
}