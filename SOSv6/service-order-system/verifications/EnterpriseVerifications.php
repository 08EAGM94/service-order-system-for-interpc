<?php

class EnterpriseVerifications{
    public static function verifyingInsertion($enter_dto, $contact_dto){
        $errors = [];
        $contacto = (!empty(trim($contact_dto->nombre_completo))) ? trim($contact_dto->nombre_completo) :
        null;
        $nombreComercial = (!empty(trim($enter_dto->nombre_comercial))) ? trim($enter_dto->nombre_comercial) :
                    null;
        $razonSocial = (!empty(trim($enter_dto->razon_social))) ? trim($enter_dto->razon_social) :
            null;
        $calleYNumero = (!empty(trim($enter_dto->calle_numero))) ? trim($enter_dto->calle_numero) :
            null;
        $entreCalles = (!empty(trim($enter_dto->entre_calles))) ? trim($enter_dto->entre_calles) :
            null;
        $dirigirseCon = (!empty(trim($enter_dto->dirigirse_con))) ? trim($enter_dto->dirigirse_con) :
            null;
        $telefonos = (!empty(trim($enter_dto->telefonos))) ? trim($enter_dto->telefonos) :
            null;
        $horario = (!empty(trim($enter_dto->horario))) ? trim($enter_dto->horario) :
            null;
        $atencion = (!empty(trim($enter_dto->atencion))) ? trim($enter_dto->atencion) :
            null;
        $colonia = (!empty(trim($enter_dto->colonia))) ? trim($enter_dto->colonia) :
            null;
        $localidad = (!empty(trim($enter_dto->localidad))) ? trim($enter_dto->localidad) :
            null;
        $email = (!empty(trim($enter_dto->email))) ? trim($enter_dto->email) :
            null;            
        
        if(!empty($contacto)){
            if(preg_match('/[0-9]+/', $contacto) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $contacto)){
                $errors["contacto"] = "El campo 'QUIEN SOLICITA' no es valido, solo escribe letras";
            }
        }else{
            $errors["contacto"] = "El campo 'QUIEN SOLICITA' se encuentra vacío";
        }
        
        if(!empty($nombreComercial)){
            if(preg_match('/[<>]+/', $nombreComercial)){
                $errors["nombreComercial"] = "El nombre comercial no es valido, permitido datos alfanumericos y algunos símbolos";
            }
        }else{
            $errors["nombreComercial"] = "El campo de nombre comercial se encuentra vacío";
        }
        
        if(!empty($razonSocial)){
            if(preg_match('/[<>]+/', $razonSocial)){
                $errors["razonSocial"] = "La razón social no es valida, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        
        if(!empty($calleYNumero)){
            if(preg_match('/[<>]+/', $calleYNumero)){
                $errors["calleYNumero"] = "Calle y número no valido, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        
        if(!empty($entreCalles)){
            if(preg_match('/[<>]+/', $entreCalles)){
                $errors["entreCalles"] = "Entre calles no valido, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        
        if(!empty($dirigirseCon)){
            if(preg_match('/[<>]+/', $dirigirseCon)){
                $errors["dirigirseCon"] = "Dirigirse con no valido, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        
        if(!empty($telefonos)){
            if(preg_match('/[A-Za-z]+/', $telefonos) || 
                    preg_match('/[<>]+/', $telefonos)){
                $errors["telefonos"] = "Teléfono(s) no valido(s), permitido solo números y algunos símbolos";
            }
        }else{
            $errors["telefonos"] = "Inserta al menos un numero teléfonico para comunicarnos con el cliente";
        }
        
        if(!empty($horario)){
            if(preg_match('/[<>]+/', $horario)){
                $errors["horario"] = "Horario no valido, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        
        if(!empty($atencion)){
            if(preg_match('/[<>]+/', $atencion)){
                $errors["atencion"] = "Atención no valida, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        
        if(!empty($colonia)){
            if(preg_match('/[<>]+/', $colonia)){
                $errors["colonia"] = "Colonia no valida, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        
        if(!empty($localidad)){
            if(preg_match('/[<>]+/', $localidad)){
                $errors["localidad"] = "Localidad no valida, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        
        if(!empty($email)){
            if(filter_var($email, FILTER_VALIDATE_EMAIL,
            ['flags' => FILTER_NULL_ON_FAILURE]) == null || preg_match('/[<>]+/', $email)){
                $errors["email"] = "Email no valido, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        return $errors;
    }
    public static function verifyingUpdate($dto, $usrDTO){
        $errors = [];
        $id = (!empty(trim($dto->enterprise_id))) ? trim($dto->enterprise_id) :
            null;
        $adminContrasena = (!empty(trim($usrDTO->admin_pwd))) ? $usrDTO->admin_pwd :
            null;
        $nombreComercial = (!empty(trim($dto->nombre_comercial))) ? trim($dto->nombre_comercial) :
            null;
        $razonSocial = (!empty(trim($dto->razon_social))) ? trim($dto->razon_social) :
            null;
        $calleYNumero = (!empty(trim($dto->calle_numero))) ? trim($dto->calle_numero) :
            null;
        $entreCalles = (!empty(trim($dto->entre_calles))) ? trim($dto->entre_calles) :
            null;
        $dirigirseCon = (!empty(trim($dto->dirigirse_con))) ? trim($dto->dirigirse_con) :
            null;
        $telefonos = (!empty(trim($dto->telefonos))) ? trim($dto->telefonos) :
            null;
        $horario = (!empty(trim($dto->horario))) ? trim($dto->horario) :
            null;
        $atencion = (!empty(trim($dto->atencion))) ? trim($dto->atencion) :
            null;
        $colonia = (!empty(trim($dto->colonia))) ? trim($dto->colonia) :
            null;
        $localidad = (!empty(trim($dto->localidad))) ? trim($dto->localidad) :
            null;
        $email = (!empty(trim($dto->email))) ? trim($dto->email) :
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
            $errors["adminContrasena"] = 'Administrador, debe colocar su contraseña para cambiar la contraseña de un usuario';
        }
        
        if(!empty($nombreComercial)){
            if(preg_match('/[<>]+/', $nombreComercial)){
                $errors["nombreComercial"] = "El nombre comercial no es valido, permitido datos alfanumericos y algunos símbolos";
            }
        }else{
            $errors["nombreComercial"] = "El campo de nombre comercial se encuentra vacío";
        }
        
        if(!empty($razonSocial)){
            if(preg_match('/[<>]+/', $razonSocial)){
                $errors["razonSocial"] = "La razón social no es valida, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        
        if(!empty($calleYNumero)){
            if(preg_match('/[<>]+/', $calleYNumero)){
                $errors["calleYNumero"] = "Calle y número no valido, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        
        if(!empty($entreCalles)){
            if(preg_match('/[<>]+/', $entreCalles)){
                $errors["entreCalles"] = "Entre calles no valido, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        
        if(!empty($dirigirseCon)){
            if(preg_match('/[<>]+/', $dirigirseCon)){
                $errors["dirigirseCon"] = "Dirigirse con no valido, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        
        if(!empty($telefonos)){
            if(preg_match('/[A-Za-z]+/', $telefonos) || 
                    preg_match('/[<>]+/', $telefonos)){
                $errors["telefonos"] = "Teléfono(s) no valido(s), permitido solo números y algunos símbolos";
            }
        }else{
            $errors["telefonos"] = "Inserta al menos un numero teléfonico para comunicarnos con el cliente";
        }
        
        if(!empty($horario)){
            if(preg_match('/[<>]+/', $horario)){
                $errors["horario"] = "Horario no valido, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        
        if(!empty($atencion)){
            if(preg_match('/[<>]+/', $atencion)){
                $errors["atencion"] = "Atención no valida, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        
        if(!empty($colonia)){
            if(preg_match('/[<>]+/', $colonia)){
                $errors["colonia"] = "Colonia no valida, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        
        if(!empty($localidad)){
            if(preg_match('/[<>]+/', $localidad)){
                $errors["localidad"] = "Localidad no valida, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        
        if(!empty($email)){
            if(filter_var($email, FILTER_VALIDATE_EMAIL,
            ['flags' => FILTER_NULL_ON_FAILURE]) == null || preg_match('/[<>]+/', $email)){
                $errors["email"] = "Email no valido, permitido datos alfanúmericos y algunos símbolos";
            }
        }
        return $errors;
    }
}