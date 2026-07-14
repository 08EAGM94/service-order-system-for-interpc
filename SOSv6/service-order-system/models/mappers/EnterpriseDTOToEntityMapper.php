<?php

class EnterpriseDTOToEntityMapper implements IMapper{
    public function map($dto){
        if(get_class($dto) !== "EmpresasDTO")
            throw new WrongObjectException("En el mapeador a entidad de EmpresasDTO no se está pasando el objeto DTO indicado");
        
        return new EmpresasEntity(
            $dto->enterprise_id,
            $dto->nombre_comercial,
            $dto->razon_social,
            $dto->calle_numero,
            $dto->entre_calles,
            $dto->dirigirse_con,
            $dto->telefonos,
            $dto->horario,
            $dto->atencion,
            $dto->colonia,
            $dto->localidad,
            $dto->email
        );
    }
}