<?php

class ContactDTOToEntityMapper implements IMapper{
    public function map($dto){
        if(get_class($dto) !== "ContactosDTO")
            throw new WrongObjectException("En el mapeador a entidad de ContactosDTO no se está pasando el objeto DTO indicado");

        return new ContactosEntity(
            $dto->contact_id,
            $dto->empresa_id,
            $dto->nombre_completo
        );
    }
}