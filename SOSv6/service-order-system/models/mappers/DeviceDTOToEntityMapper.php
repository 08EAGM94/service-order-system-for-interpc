<?php

class DeviceDTOToEntityMapper implements IMapper{
    public function map($dto){
        if(get_class($dto) !== "EquiposDTO")
            throw new WrongObjectException("En el mapeador a entidad de EquiposDTO no se está pasando el objeto DTO indicado");
        
        return new EquiposEntity(
            $dto->device_id,
            $dto->empresa_id,
            $dto->tipo_id,
            $dto->marca,
            $dto->modelo,
            $dto->numero_serie,
            $dto->numero_inventario
        );
    }
}