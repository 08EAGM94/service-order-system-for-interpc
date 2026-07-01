<?php

class ContactVerifications{
    public static function verifyingInsertion($dto){
        $errors = [];
        $hiddenEntId = (!empty(trim($dto->empresa_id))) ? trim($dto->empresa_id) :
            null;
        $contacto = (!empty(trim($dto->nombre_completo))) ? trim($dto->nombre_completo) :
        null;            
        

        if(!empty($hiddenEntId)){
            if(preg_match('/[A-Za-z]+/', $hiddenEntId) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $hiddenEntId)){
                $errors["hiddenEntId"] = "Eres un pillín, deja de moverle a lo prohibido...";
            }
        }

        
        if(!empty($contacto)){
            if(preg_match('/[0-9]+/', $contacto) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $contacto)){
                $errors["contacto"] = "El campo 'QUIEN SOLICITA' no es valido, solo escribe letras";
            }
        }else{
            $errors["contacto"] = "El campo 'QUIEN SOLICITA' se encuentra vacío";
        }
        return $errors;
    }
    public static function verifyingUpdate($dto, $usrDTO){ 
        $errors = [];
        $id = (!empty(trim($dto->contact_id))) ? trim($dto->contact_id) :
            null;
        $nombre = (!empty(trim($dto->nombre_completo))) ? trim($dto->nombre_completo) :
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

        if(!empty($nombre)){
            if(preg_match('/[0-9]+/', $nombre) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $nombre)){
                $errors["nombre"] = "El nombre del cliente no es valido, solo escribe letras";
            }
        }else{
            $errors["nombre"] = "El campo del nombre del cliente se encuentra vacío";
        }
        
        if(!empty($adminContrasena)){
            if(preg_match('/[<>]+/', $adminContrasena)){
                $errors["adminContrasena"] = 'Administrador, su "Contraseña" no es valida';
            }
        }else{
            $errors["adminContrasena"] = 'Administrador, debe colocar su contraseña para poder cambiar la información';
        }            
        return $errors;            
    }
}