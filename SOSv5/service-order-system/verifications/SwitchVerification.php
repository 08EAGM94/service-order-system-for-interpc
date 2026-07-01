<?php

class SwitchVerification{
    public static function verifyingSwitch($dto){
        $errors = [];
        if(property_exists($dto, 'admin_pwd')) $adminContrasena = (!empty(trim($dto->admin_pwd))) ? 
                $dto->admin_pwd : false;
        
        if(property_exists($dto, 'user_id')) $usuarioId = (!empty(trim($dto->user_id))) ? 
                $dto->user_id : false;
        
        if(property_exists($dto, 'enterprise_id')) $empresaId = (!empty($dto->enterprise_id)) ? 
                $dto->enterprise_id : false;
        
        if(property_exists($dto, 'contact_id')) $contactoId = (!empty(trim($dto->contact_id))) ? 
                $dto->contact_id : false;
        
        if(property_exists($dto, 'type_id')) $tipoId = (!empty(trim($dto->type_id))) ? 
                $dto->type_id : false;
        
        if(property_exists($dto, 'device_id')) $equipoId = (!empty(trim($dto->device_id))) ? 
                $dto->device_id : false;
        
        if(property_exists($dto, 'binnacle_id')) $bitacoraId = (!empty(trim($dto->binnacle_id))) ? 
                $dto->binnacle_id : false;
        
        $visibilidad = (!empty(trim($dto->visibilidad))) ? $dto->visibilidad : false;
        
        if(isset($adminContrasena)){
            if(!empty($adminContrasena)){
                if(preg_match('/[<>]+/', $adminContrasena)){
                    $errors["adminContrasena"] = 'Administrador, su "Contraseña" no es valida';
                }
            }else{
                $errors["adminContrasena"] = 'Administrador, debe colocar su contraseña para realizar la activación/desactivación de este registro';
            }
        }
        
        if(isset($usuarioId)){
            if(!empty($usuarioId)){
                if(preg_match('/[A-Za-z]+/', $usuarioId) ||
                    preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $usuarioId)){
                    $errors["usuarioId"] = "Eres un pillín, no le muevas a lo prohibido...";
                }
            }else{
                $errors["usuarioId"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
        }
        
        if(isset($empresaId)){
            if(!empty($empresaId)){
                if(preg_match('/[A-Za-z]+/', $empresaId) ||
                    preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $empresaId)){
                    $errors["empresaId"] = "Eres un pillín, no le muevas a lo prohibido...";
                }
            }else{
                $errors["empresaId"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
        }
        
        if(isset($contactoId)){
            if(!empty($contactoId)){
                if(preg_match('/[A-Za-z]+/', $contactoId) ||
                    preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $contactoId)){
                    $errors["contactoId"] = "Eres un pillín, no le muevas a lo prohibido...";
                }
            }else{
                $errors["contactoId"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
        }
        
        if(isset($tipoId)){
            if(!empty($tipoId)){
                if(preg_match('/[A-Za-z]+/', $tipoId) ||
                    preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $tipoId)){
                    $errors["tipoId"] = "Eres un pillín, no le muevas a lo prohibido...";
                }
            }else{
                $errors["tipoId"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
        }
        
        if(isset($equipoId)){
            if(!empty($equipoId)){
                if(preg_match('/[A-Za-z]+/', $equipoId) ||
                    preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $equipoId)){
                    $errors["equipoId"] = "Eres un pillín, no le muevas a lo prohibido...";
                }
            }else{
                $errors["equipoId"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
        }
        
        if(isset($bitacoraId)){
            if(!empty($bitacoraId)){
                if(preg_match('/[A-Za-z]+/', $bitacoraId) ||
                    preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $bitacoraId)){
                    $errors["bitacoraId"] = "Eres un pillín, no le muevas a lo prohibido...";
                }
            }else{
                $errors["bitacoraId"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
        }
        
        if(!empty($visibilidad)){
            if(preg_match('/[0-9]+/', $visibilidad) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $visibilidad)){
                $errors["estatus"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
            if($visibilidad !== "ENABLED" && $visibilidad !== "DISABLED"){
                $errors["estatus"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
        }else{
            $errors["estatus"] = "Eres un pillín, no le muevas a lo prohibido...";
        }
        return $errors;
    }
}