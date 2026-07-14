<?php

class DeviceVerifications{
    public static function verifyingInsertion($dto){
        $errors = [];
        $empresas = (!empty(trim($dto->empresa_id))) ? trim($dto->empresa_id) :
            null;
        $tipos = (!empty(trim($dto->tipo_id))) ? trim($dto->tipo_id) :
            null;
        $marca = (!empty(trim($dto->marca))) ? trim($dto->marca) :
            null;
        $modelo = (!empty(trim($dto->modelo))) ? trim($dto->modelo) :
            null;
        $ns = (!empty(trim($dto->numero_serie))) ? trim($dto->numero_serie) :
            null;
        $numeroInventario = (!empty(trim($dto->numero_inventario))) ? trim($dto->numero_inventario) :
            null;            
        
        if(!empty($empresas)){
            if(preg_match('/[A-Za-z]+/', $empresas) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $empresas)){
                $errors["empresas"] = "Eres un pillín, deja de moverle a lo prohibido...";
            }
        }else{
            $errors["empresas"] = 'Campo "EMPRESA" obligatorio';
        }
        
        if(!empty($tipos)){
            if(preg_match('/[A-Za-z]+/', $tipos) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $tipos)){
                $errors["tipos"] = "Eres un pillín, deja de moverle a lo prohibido...";
            }
        }else{
            $errors["tipos"] = 'Campo "TIPO" obligatorio';
        }
            
        if(!empty($marca)){
            if (preg_match('/[<>]+/', $marca)) {
            $errors["marca"] = 'Campo "MARCA" no valido, se admiten valores alfanuméricos y algunos símbolos';
            }
        } else {
            $errors["marca"] = 'Campo "MARCA" obligatorio';
        }

        if (!empty($modelo)) {
            if (preg_match('/[<>]+/', $modelo)) {
                $errors["modelo"] = 'Campo "MODELO" no valido, se admiten valores alfanuméricos y algunos símbolos';
            }
        } else {
            $errors["modelo"] = 'Campo "MODELO" obligatorio';
        }

        if (!empty($ns)) {
            if (preg_match('/[<>]+/', $ns)) {
                $errors["ns"] = 'Campo "No.SERIE" no valido, se admiten valores alfanuméricos y algunos símbolos';
            }
        } else {
            $errors["ns"] = 'Campo "No.SERIE" obligatorio';
        }

        if (!empty($numeroInventario)) {
            if (preg_match('/[A-Za-z]+/', $numeroInventario) ||
                    preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $numeroInventario)) {
                $errors["numeroInventario"] = 'Campo "No.INVENTARIO" no valido, solo datos numéricos';
            }
        }
        return $errors;
    }
    public static function verifyingUpdate($dto, $usrDTO){
        $errors = [];
        $id = (!empty(trim($dto->device_id))) ? trim($dto->device_id) :
            null;
        $tipoId = (!empty(trim($dto->tipo_id))) ? trim($dto->tipo_id) :
            null;    
        $adminContrasena = (!empty(trim($usrDTO->admin_pwd))) ? $usrDTO->admin_pwd :
            null;
        $marca = (!empty(trim($dto->marca))) ? trim($dto->marca) :
            null;
        $modelo = (!empty(trim($dto->modelo))) ? trim($dto->modelo) :
            null;
        $ns = (!empty(trim($dto->numero_serie))) ? trim($dto->numero_serie) :
            null;
        $numeroInventario = (!empty(trim($dto->numero_inventario))) ? trim($dto->numero_inventario) :
            null;
        
        if(!empty($id)){
            if(preg_match('/[A-Za-z]+/', $id) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $id)){
                $errors["id"] = "Eres un pillín, deja de moverle a lo prohibido...";
            }
        }else{
            $errors["id"] = "Eres un pillín, deja de moverle a lo prohibido...";
        }

        if(!empty($tipoId)){
            if(preg_match('/[A-Za-z]+/', $tipoId) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $tipoId)){
                $errors["tipoId"] = "Eres un pillín, deja de moverle a lo prohibido...";
            }
        }else{
            $errors["tipoId"] = "Eres un pillín, deja de moverle a lo prohibido...";
        }

        if(!empty($adminContrasena)){
            if(preg_match('/[<>]+/', $adminContrasena)){
                $errors["adminContrasena"] = 'Administrador, su "Contraseña" no es valida';
            }
        }else{
            $errors["adminContrasena"] = 'Administrador, debe colocar su contraseña para poder cambiar la información';
        }    
        
        if(!empty($marca)){
            if (preg_match('/[<>]+/', $marca)) {
            $errors["marca"] = 'Campo "MARCA" no valido, se admiten valores alfanuméricos y algunos símbolos';
            }
        } else {
            $errors["marca"] = 'Campo "MARCA" obligatorio';
        }

        if (!empty($modelo)) {
            if (preg_match('/[<>]+/', $modelo)) {
                $errors["modelo"] = 'Campo "MODELO" no valido, se admiten valores alfanuméricos y algunos símbolos';
            }
        } else {
            $errors["modelo"] = 'Campo "MODELO" obligatorio';
        }

        if (!empty($ns)) {
            if (preg_match('/[<>]+/', $ns)) {
                $errors["ns"] = 'Campo "No.SERIE" no valido, se admiten valores alfanuméricos y algunos símbolos';
            }
        } else {
            $errors["ns"] = 'Campo "No.SERIE" obligatorio';
        }

        if (!empty($numeroInventario)) {
            if (preg_match('/[A-Za-z]+/', $numeroInventario) ||
                    preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $numeroInventario)) {
                $errors["numeroInventario"] = 'Campo "No.INVENTARIO" no valido, solo datos numéricos';
            }
        }            
        return $errors;            
    }
}