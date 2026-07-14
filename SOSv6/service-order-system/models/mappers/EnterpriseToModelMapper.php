<?php

class EnterpriseToModelMapper implements IMapper{
    public function map($obj){
        if(get_class($obj) !== "EmpresasDTO" &&
            get_class($obj) !== "EmpresasEntity")
            throw new WrongObjectException("En el mapeador a modelo de objetos EmpresasDTO y EmpresasEntity no se está pasando los objetos compatibles para este mapeador");
        
        return new EmpresasModel(
            (method_exists($obj, 'getId')) ? $obj->getId() : $obj->enterprise_id,
            (method_exists($obj, 'getNombreComercial')) ? $obj->getNombreComercial() : $obj->nombre_comercial,
            (method_exists($obj, 'getRazonSocial')) ? $obj->getRazonSocial() : $obj->razon_social,
            (method_exists($obj, 'getCalleNumero')) ? $obj->getCalleNumero() : $obj->calle_numero,
            (method_exists($obj, 'getEntreCalles')) ? $obj->getEntreCalles() : $obj->entre_calles,
            (method_exists($obj, 'getDirigirseCon')) ? $obj->getDirigirseCon() : $obj->dirigirse_con,
            (method_exists($obj, 'getTelefonos')) ? $obj->getTelefonos() : $obj->telefonos,
            (method_exists($obj, 'getHorario')) ? $obj->getHorario() : $obj->horario,
            (method_exists($obj, 'getAtencion')) ? $obj->getAtencion() : $obj->atencion,
            (method_exists($obj, 'getColonia')) ? $obj->getColonia() : $obj->colonia,
            (method_exists($obj, 'getLocalidad')) ? $obj->getLocalidad() : $obj->localidad,
            (method_exists($obj, 'getEmail')) ? $obj->getEmail() : $obj->email,
            (property_exists($obj, 'visibilidad')) ? $obj->visibilidad : null
        );
    }
}