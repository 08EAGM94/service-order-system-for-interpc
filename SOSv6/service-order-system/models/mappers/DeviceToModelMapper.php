<?php

class DeviceToModelMapper implements IMapper{
    public function map($obj){
        if(get_class($obj) !== "EquiposDTO" &&
            get_class($obj) !== "EquiposEntity")
            throw new WrongObjectException("En el mapeador a modelo de objetos EquiposDTO y EquiposEntity no se está pasando los objetos compatibles para este mapeador");
        
        return new EquiposModel(
            (method_exists($obj, 'getId')) ? $obj->getId() : $obj->device_id,
            (method_exists($obj, 'getEmpresaId')) ? $obj->getEmpresaId() : $obj->empresa_id,
            (method_exists($obj, 'getTipoId')) ? $obj->getTipoId() : $obj->tipo_id,
            (method_exists($obj, 'getMarca')) ? $obj->getMarca() : $obj->marca,
            (method_exists($obj, 'getModelo')) ? $obj->getModelo() : $obj->modelo,
            (method_exists($obj, 'getNumeroSerie')) ? $obj->getNumeroSerie() : $obj->numero_serie,
            (method_exists($obj, 'getNumeroInventario')) ? $obj->getNumeroInventario() : $obj->numero_inventario,
            (property_exists($obj, 'visibilidad')) ? $obj->visibilidad : null
        );
    }
}