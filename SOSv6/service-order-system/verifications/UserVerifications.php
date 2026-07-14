<?php

class UserVerifications{
    public static function verifyingLogin($dto){
        $errors = [];
        $alias = (!empty(trim($dto->alias))) ? trim($dto->alias) :
            null;
        $contrasena = (!empty(trim($dto->contrasena))) ? $dto->contrasena :
            null;
        
        if(empty($alias) || preg_match('/[<>]+/', $alias)){
            $errors["alias"] = "El nombre no es valido";
        }
        
        if(empty($contrasena) || preg_match('/[<>]+/', $contrasena)){
            $errors["contrasena"] = "La contraseña no es valida";
        }
        return $errors;
    }
    public static function verifyingInsertion($dto){
        $errors = [];
        $nombre = (!empty(trim($dto->nombre))) ? trim($dto->nombre) :
            null;
        $apellidos = (!empty(trim($dto->apellidos))) ? trim($dto->apellidos) :
            null;
        $alias = (!empty(trim($dto->alias))) ? trim($dto->alias) :
            null;
        $contrasena = (!empty(trim($dto->contrasena))) ? $dto->contrasena :
            null;
        $confContrasena = (!empty(trim($dto->conf_pwd))) ? $dto->conf_pwd :
            null;
        $privilegio = (!empty(trim($dto->privilegio))) ? trim($dto->privilegio) :
            null;
        $adminContrasena = (!empty(trim($dto->admin_pwd))) ? $dto->admin_pwd :
            null;
        
        if(!empty($nombre)){
            if(preg_match('/[0-9]+/', $nombre) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $nombre)){
                $errors["nombre"] = "El nombre no es valido";
            }
        }else{
            $errors["nombre"] = "El nombre no es valido";
        }
        
        if(!empty($apellidos)){
            if(preg_match('/[0-9]+/', $apellidos) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $apellidos)){
                $errors["apellidos"] = "Los apellidos no son validos";
            }
        }
        
        if(!empty($alias)){
            if(preg_match('/[<>]+/', $alias)){
                $errors["alias"] = "El nombre de usuario no es valido";
            }
        }else{
            $errors["alias"] = "El nombre de usuario no es valido";
        }
        
        if(!empty($contrasena)){
            if(preg_match('/[<>]+/', $contrasena)){
                $errors["contrasena"] = 'El campo "Contraseña" no es valido';
            }
        }else{
            $errors["contrasena"] = 'El campo "Contraseña" no es valido';
        }
        
        if(!empty($confContrasena)){
            if(preg_match('/[<>]+/', $confContrasena)){
                $errors["confContrasena"] = 'El campo "Confirmar contraseña" no es valido';
            }
        }else{
            $errors["confContrasena"] = 'El campo "Confirmar contraseña" no es valido';
        }
        
        if(!empty($contrasena) && !empty($confContrasena)){
            if($contrasena !== $confContrasena){
                $errors["pwdFileds"] = 'Los campos "Contraseña" y "Confirmar contraseña" no coinciden';
            }
        }else{
            $errors["pwdFileds"] = 'Los campos "Contraseña" y "Confirmar contraseña" son obligatorios';
        }
        
        if(!empty($adminContrasena)){
            if(preg_match('/[<>]+/', $adminContrasena)){
                $errors["adminContrasena"] = 'Administrador, su "Contraseña" no es valida';
            }
        }else{
            $errors["adminContrasena"] = 'Administrador, debe colocar su contraseña para registrar un usuario';
        }
        
        if(!empty($privilegio)){
            if(preg_match('/[0-9]+/', $privilegio) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $privilegio)){
                $errors["privilegio"] = "Eres un pillín, deja de moverle a lo prohibido...";
            }
        }else{
            $errors["privilegio"] = "Eres un pillín, deja de moverle a lo prohibido...";
        }
        return $errors;
    }
    public static function verifyingUpdate($dto){
        $errors = [];
        $usuarioId = (!empty(trim($dto->user_id))) ? trim($dto->user_id) :
            null;
        $contrasena = (!empty(trim($dto->contrasena))) ? $dto->contrasena :
            null;
        $confContrasena = (!empty(trim($dto->conf_pwd))) ? $dto->conf_pwd :
            null;
        $adminContrasena = (!empty(trim($dto->admin_pwd))) ? $dto->admin_pwd :
            null;
        
        if(!empty($contrasena)){
            if(preg_match('/[<>]+/', $contrasena)){
                $errors["contrasena"] = 'El campo "Contraseña" no es valido';
            }
        }else{
            $errors["contrasena"] = 'El campo "Contraseña" no es valido';
        }
        
        if(!empty($confContrasena)){
            if(preg_match('/[<>]+/', $confContrasena)){
                $errors["confContrasena"] = 'El campo "Confirmar contraseña" no es valido';
            }
        }else{
            $errors["confContrasena"] = 'El campo "Confirmar contraseña" no es valido';
        }
        
        if(!empty($contrasena) && !empty($confContrasena)){
            if($contrasena !== $confContrasena){
                $errors["pwdFileds"] = 'Los campos "Contraseña" y "Confirmar contraseña" no coinciden';
            }
        }else{
            $errors["pwdFileds"] = 'Los campos "Contraseña" y "Confirmar contraseña" son obligatorios';
        }
        
        if(!empty($adminContrasena)){
            if(preg_match('/[<>]+/', $adminContrasena)){
                $errors["adminContrasena"] = 'Administrador, su "Contraseña" no es valida';
            }
        }else{
            $errors["adminContrasena"] = 'Administrador, debe colocar su contraseña para cambiar la contraseña de un usuario';
        }
        
        if(!empty($usuarioId)){
            if(preg_match('/[A-Za-z]+/', $usuarioId) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $usuarioId)){
                $errors["usuarioId"] = "Eres un pillín, deja de moverle a lo prohibido...";
            }
        }else{
            $errors["usuarioId"] = "Eres un pillín, deja de moverle a lo prohibido...";
        }
        return $errors;
    }
    
}