<?php

class BinnacleDTOToEntityMapper implements IMapper{
    public function map($dto){
        
        if(get_class($dto) !== "BitacorasDTO")
            throw new WrongObjectException("En el mapeador a entidad de BitacorasDTO no se está pasando el objeto DTO indicado");

        return new BitacorasEntity(
            $dto->binnacle_id,
            $dto->usuario_id,
            $dto->contacto_id,
            $dto->servicio,
            $dto->equipo_id,
            $dto->monto,
            $dto->Actividades_realizadas,
            (isset($dto->cancel_desc)) ? $dto->cancel_desc : $dto->observaciones,
            $dto->firma_cliente,
            $dto->estatus,
            $dto->inicio,
            $dto->fin,
        );
    }
}