<?php

class TypeVerifications{
    public static function verifyingInsertion($dto){
        $errors = [];
        $tipo = (!empty(trim($dto->tipo))) ? trim($dto->tipo) :
                    null;
        if(!empty($tipo)){
            if(preg_match('/[0-9]+/', $tipo) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $tipo)){
                $errors["tipo"] = "El tipo no es valido, solo escribe letras";
            }
        }else{
            $errors["tipo"] = "El campo del tipo se encuentra vacío";
        }            
        return $errors;            
    }
    public static function verifyingUpdate($dto, $usrDTO){
        $errors = [];
        $id = (!empty(trim($dto->type_id))) ? trim($dto->type_id) :
            null;
        $tipo = (!empty(trim($dto->tipo))) ? trim($dto->tipo) :
                    null;            
        $adminContrasena = (!empty(trim($usrDTO->admin_pwd))) ? $usrDTO->admin_pwd :
                    null;

        if(!empty($id)){
            if(preg_match('/[A-Za-z]+/', $id) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $id)){
                $errors["id"] = "Eres un pillín, deja de moverle a lo prohibido...";
            }
        }else{
            $errors["id"] = "Eres un pillín, deja de moverle a lo prohibido...";
        } 

        if(!empty($adminContrasena)){
            if(preg_match('/[<>]+/', $adminContrasena)){
                $errors["adminContrasena"] = 'Administrador, su "Contraseña" no es valida';
            }
        }else{
            $errors["adminContrasena"] = 'Administrador, debe colocar su contraseña para poder cambiar la información';
        } 

        if(!empty($tipo)){
            if(preg_match('/[0-9]+/', $tipo) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $tipo)){
                $errors["tipo"] = "El tipo no es valido, solo escribe letras";
            }
        }else{
            $errors["tipo"] = "El campo del tipo se encuentra vacío";
        }            
        return $errors;            
    }
}