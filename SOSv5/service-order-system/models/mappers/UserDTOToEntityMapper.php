<?php

class UserDTOToEntityMapper implements IMapper{
    public function map($dto){
        if(get_class($dto) !== "UsuariosDTO")
            throw new WrongObjectException("En el mapeador a entidad de usuariosDTO no se está pasando el objeto DTO indicado");

        return new UsuariosEntity(
            $dto->user_id,
            $dto->nombre,
            $dto->apellidos,
            (isset($dto->admin_nickname)) ? $dto->admin_nickname : $dto->alias,
            (isset($dto->admin_pwd)) ? $dto->admin_pwd : $dto->contrasena,
            $dto->privilegio,
            $dto->firma
        );
    }
}