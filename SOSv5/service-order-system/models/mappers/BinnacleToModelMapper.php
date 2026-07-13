<?php

class BinnacleToModelMapper implements IMapper{
    public function map($obj){
        if(get_class($obj) !== "BitacorasDTO" &&
            get_class($obj) !== "BitacorasEntity")
            throw new WrongObjectException("En el mapeador a modelo de objetos BitacorasDTO y BitacorasEntity no se está pasando los objetos compatibles para este mapeador");
        
            return new BitacorasModel(
            (method_exists($obj, 'getId')) ? $obj->getId() : $obj->binnacle_id,
            (method_exists($obj, 'getUsuarioId')) ? $obj->getUsuarioId() : $obj->usuario_id,
            (method_exists($obj, 'getContactoId')) ? $obj->getContactoId() : $obj->contacto_id,
            (method_exists($obj, 'getServicio')) ? $obj->getServicio() : $obj->servicio,
            (method_exists($obj, 'getEquipoId')) ? $obj->getEquipoId() : $obj->equipo_id,
            (method_exists($obj, 'getMonto')) ? $obj->getMonto() : $obj->monto,
            (method_exists($obj, 'getActividadesRealizadas')) ? $obj->getActividadesRealizadas() : $obj->Actividades_realizadas,
            (method_exists($obj, 'getObservaciones')) ? $obj->getObservaciones() : $obj->observaciones,
            (method_exists($obj, 'getFirmaCliente')) ? $obj->getFirmaCliente() : $obj->firma_cliente,
            (method_exists($obj, 'getEstatus')) ? $obj->getEstatus() : $obj->estatus,
            (method_exists($obj, 'getInicio')) ? $obj->getInicio() : $obj->inicio,
            (method_exists($obj, 'getFin')) ? $obj->getFin() : $obj->fin,
            (property_exists($obj, 'visibilidad')) ? $obj->visibilidad : null
        );
    }
}