<?php

class TypeDTOToEntityMapper implements IMapper{
    public function map($dto){
        if(get_class($dto) !== "TiposDTO")
            throw new WrongObjectException("En el mapeador a entidad de TiposDTO no se está pasando el objeto DTO indicado");
        
        return new TiposEntity(
            $dto->type_id,
            $dto->tipo
        );
    }
}