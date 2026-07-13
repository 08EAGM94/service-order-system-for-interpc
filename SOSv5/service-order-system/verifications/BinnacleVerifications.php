<?php

class BinnacleVerifications{
    public static function verifyingInsertion($dto){
        $errors = [];
        $userId = (!empty($dto->usuario_id)) ? $dto->usuario_id :
            null;
        $contactos = (!empty($dto->contacto_id)) ? $dto->contacto_id :
            null;
        $tipoActividades = (!empty($dto->actividad)) ? $dto->actividad :
            null;
        $servicio = (!empty($dto->servicio)) ? trim($dto->servicio) :
            null;
        $equipos = (!empty($dto->equipo_id)) ? trim($dto->equipo_id) :
            null;
        
        if(!empty($userId)){
            if(preg_match('/[A-Za-z]+/', $userId) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $userId) ||
                    $userId !== $_SESSION["identity"]["Id"]){
                $errors["userId"] = "Eres un pillín, deja de moverle a lo prohibido...";
            }
        }else{
            $errors["userId"] = "Eres un pillín, deja de moverle a lo prohibido...";
        }
        
        if(!empty($contactos)){
            if(preg_match('/[A-Za-z]+/', $contactos) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $contactos)){
                $errors["contactos"] = "Eres un pillín, deja de moverle a lo prohibido...";
            }
        }else{
            $errors["contactos"] = "Debes de elegir un contacto, si no hay opciones que elegir registra "
                    . "un contacto en su formulario correspondiente antes de entrar en 'Nueva bitácora'";
        }
        
        
        if(empty($tipoActividades)){
            $errors["tipoActividades"] = 'Tienes que elegir "Servicio" o "Equipo"';
        }
        
        if(!empty($tipoActividades)){
            
            if($tipoActividades === "servicio"){
                
                if(!empty($servicio)){
                    if(preg_match('/[<>]+/', $servicio)){
                        $errors["servicio"] = "Servicio no valido, permitido datos alfanúmericos y algunos símbolos";
                    }
                }else{
                    $errors["servicio"] = "Debes llenar el campo de Servicio para el establecimiento de actividades";
                }
                
            }
            
            if($tipoActividades === "equipo"){
                
                if(!empty($equipos)){
                    if(preg_match('/[A-Za-z]+/', $equipos) ||
                        preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $equipos)){
                        $errors["equipos"] = "Eres un pillín, deja de moverle a lo prohibido...";
                    }
                }else{
                    $errors["equipos"] = "Debes de elegir un equipo, si no hay opciones que elegir registra "
                    . "un equipo en su formulario correspondiente antes de entrar en 'Nueva bitácora'";
                }
            }
            
            if(!empty($servicio) && !empty($equipos)){
                $errors["bothServAndDce"] = 'No está permitido llenar el campo "Servicio" y elegir un equipo, solo elije uno de los dos';
            }
        }    
        return $errors;
    }
    public static function verifyingFollowUpPartial($dto){
        $errors = [];
        $id = (!empty(trim($dto->binnacle_id))) ? trim($dto->binnacle_id) :
            null;
        $estatus = (!empty(trim($dto->estatus))) ? trim($dto->estatus) :
            null;
        $binnFecha = (!empty(trim($dto->inicio))) ? $dto->inicio :
            null;
        $seHizo = (!empty(trim($dto->Actividades_realizadas))) ? $dto->Actividades_realizadas :
            null;
        $observaciones = (!empty(trim($dto->observaciones))) ? $dto->observaciones :
            null;
        
        if(!empty($id)){
            if(preg_match('/[A-Za-z]+/', $id) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $id)){
                $errors["id"] = "Eres un pillín, deja de moverle a lo prohibido...";
            }
        }else{
            $errors["id"] = "Eres un pillín, deja de moverle a lo prohibido...";
        }

        if(!empty($estatus)){
            if(preg_match('/[0-9]+/', $estatus) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $estatus) ||
                    $estatus !== "falta confirmar"){
                $errors["estatus"] = "Eres un pillín, deja de moverle a lo prohibido...";
            }
        }else{
            $errors["estatus"] = "Eres un pillín, deja de moverle a lo prohibido...";
        }
        
        if(!empty($binnFecha)){
            if(!preg_match('/^(\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/', $binnFecha)){
                $errors["binnFecha"] = "Formato de fecha no valido";
            }
        }
        
        if(!empty($seHizo)){
            if(preg_match('/[<>]+/', $seHizo)){
                $errors["seHizo"] = "La descripción de 'Actividades Realizadas' no es valida, permitido datos alfanumericos y algunos símbolos";
            }
        }else{
            $errors["seHizo"] = 'El campo "actividades realizadas" se encuentra vacío';
        }
        
        if(!empty($observaciones)){
            if(preg_match('/[<>]+/', $observaciones)){
                $errors["observaciones"] = "La descripción de 'Observaciones' no es valida, permitido datos alfanúmericos y algunos símbolos";
            }
        }    
        return $errors;
    }
    public static function verifyingCancelDescription($dto){
        $errors = [];
        $cancelwithid = (!empty(trim($dto->binnacle_id))) ? trim($dto->binnacle_id) :
            null;
        $cancelestatus = (!empty(trim($dto->estatus))) ? trim($dto->estatus) :
            null;
        $cancelacion = (!empty(trim($dto->cancel_desc))) ? $dto->cancel_desc :
            null;
        
        if(!empty($cancelwithid)){
            if(preg_match('/[A-Za-z]+/', $cancelwithid) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $cancelwithid)){
                $errors["cancelwithid"] = "Eres un pillín, deja de moverle a lo prohibido...";
            }
        }else{
            $errors["cancelwithid"] = "Eres un pillín, deja de moverle a lo prohibido...";
        }

        if(!empty($cancelestatus)){
            if(preg_match('/[0-9]+/', $cancelestatus) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $cancelestatus) ||
                    $cancelestatus !== "cancelado"){
                $errors["cancelestatus"] = "Eres un pillín, deja de moverle a lo prohibido...";
            }
        }else{
            $errors["cancelestatus"] = "Eres un pillín, deja de moverle a lo prohibido...";
        }
        
        if(!empty($cancelacion)){
            if(preg_match('/[<>]+/', $cancelacion)){
                $errors["cancelacion"] = "La descripción no es valida, permitido datos alfanumericos y algunos símbolos";
            }
        }else{
            $errors["cancelacion"] = 'Tienes que añadir el porqué de la cancelación';
        }    
        return $errors;
    }
    public static function verifyingFilterOptions($dto){
        $errors = [];
        $empresaId = (!empty($dto->empresa_id)) ? $dto->empresa_id : null;
        $contactoId = (!empty($dto->contacto_id)) ? $dto->contacto_id : null;
        $serviciOEquipo = (!empty($dto->actividad)) ? $dto->actividad : null;
        $equipoId = (!empty($dto->equipo_id)) ? $dto->equipo_id : null;
        $estatus = (!empty($dto->estatus)) ? $dto->estatus : null;
        $startedOrEnded = (!empty($dto->dates_type)) ? $dto->dates_type : null;
        $leftDay = (!empty($dto->left_day)) ? $dto->left_day : null;
        $rightDay = (!empty($dto->right_day)) ? $dto->right_day : null;
        $visible = (!empty($dto->visibilidad)) ? $dto->visibilidad : null;
        
        if(!empty($empresaId)){
            if(preg_match('/[A-Za-z]+/', $empresaId) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $empresaId)){
                $errors["empresaId"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
        }
        
        if(!empty($contactoId)){
            if(preg_match('/[A-Za-z]+/', $contactoId) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $contactoId)){
                $errors["contactoId"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
        }
        
        if(!empty($serviciOEquipo)){
            if(preg_match('/[0-9]+/', $serviciOEquipo) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $serviciOEquipo)){
                $errors["servicioOEquipo"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
        }else{
            $errors["servicioOEquipo"] = "Eres un pillín, no le muevas a lo prohibido...";
        }
        
        if(!empty($equipoId)){
            if(preg_match('/[A-Za-z]+/', $equipoId) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $equipoId)){
                $errors["equipoId"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
        }
        
        if(!empty($estatus)){
            if(preg_match('/[0-9]+/', $estatus) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $estatus)){
                $errors["estatus"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
        }else{
            $errors["estatus"] = "Eres un pillín, no le muevas a lo prohibido...";
        }
        
        if(!empty($startedOrEnded)){
            if(preg_match('/[0-9]+/', $startedOrEnded) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $startedOrEnded)){
                $errors["startedOrEnded"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
        }else{
            $errors["startedOrEnded"] = "Eres un pillín, no le muevas a lo prohibido...";
        }
        
        if((!empty($leftDay) && empty($rightDay)) || (!empty($rightDay) && empty($leftDay))){
            $errors["oneDayOnly"] = "Si se filtran las bitacoras por fechas de inicio o fin, "
                    ."esto se calcula entre rangos de fechas, se debe poner las dos fechas "
                    ."para que sea valido el filtro";
        }
        
        if(!empty($leftDay)){
            if(!preg_match('/^(\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/', $leftDay)){
                $errors["leftDay"] = "Formato de fecha no valido";
            }
        }
        
        if(!empty($rightDay)){
            if(!preg_match('/^(\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/', $rightDay)){
                $errors["rightDay"] = "Formato de fecha no valido";
            }
        }
        
        if(!empty($visible)){
            if(preg_match('/[0-9]+/', $visible) ||
                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $visible)){
                $errors["estatus"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
        }else{
            $errors["visible"] = "Eres un pillín, no le muevas a lo prohibido...";
        }    
        return $errors;
    }
    public static function verifyingUpdate($dto, $usrDTO){
        $errors = [];
        $bitacoraId = (!empty(trim($dto->binnacle_id))) ? $dto->binnacle_id : false;
        $estatus = (!empty(trim($dto->estatus))) ? $dto->estatus : false;
        $adminContrasena = (!empty(trim($usrDTO->admin_pwd))) ? $usrDTO->admin_pwd : false;
        $fechaInicio = (!empty(trim($dto->inicio))) ? $dto->inicio : false;
        if(isset($dto->usuario_id))
            $usuario = (!empty(trim($dto->usuario_id))) ? $dto->usuario_id : false;
        if(isset($dto->servicio))
            $servicio = (!empty(trim($dto->servicio))) ? $dto->servicio : false;
        if(isset($dto->Actividades_realizadas))
            $seHizo = (!empty(trim($dto->Actividades_realizadas))) ? $dto->Actividades_realizadas : false;
        if(isset($dto->cancel_desc))
            $motivoCancelacion = (!empty(trim($dto->cancel_desc))) ? $dto->cancel_desc : false;
        if(isset($dto->observaciones))
            $observaciones = (!empty(trim($dto->observaciones))) ? $dto->observaciones : false;
        if(isset($dto->fin))
            $fechaFin = (!empty(trim($dto->fin))) ? $dto->fin : false;
        if(isset($dto->monto))
            $monto = (!empty(trim($dto->monto))) ? trim($dto->monto) : false;
            
        
        if(!empty($bitacoraId)){
            if(preg_match('/[A-Za-z]+/', $bitacoraId) ||
                preg_match('/[=!@#$%^&*(),.?":{}|<>]+/', $bitacoraId)){
                $errors["bitacoraId"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
        }else{
            $errors["bitacoraId"] = "Eres un pillín, no le muevas a lo prohibido...";
        }
        
        if(!empty($estatus)){
            if(preg_match('/[0-9]+/', $estatus) ||
                preg_match('/[=!@#$%^&*(),.?":{}|<>]+/', $estatus)){
                $errors["estatus"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
            if($estatus !== "en proceso"       && 
                $estatus !== "falta confirmar"  &&
                $estatus !== "cancelado"        &&
                $estatus !== "finalizado"){
                $errors["estatus"] = "Eres un pillín, no le muevas a lo prohibido...";
            }
        }else{
            $errors["estatus"] = "Eres un pillín, no le muevas a lo prohibido...";
        }
        
        if(!empty($adminContrasena)){
            if(preg_match('/[<>]+/', $adminContrasena)){
                $errors["adminContrasena"] = 'Administrador, su "Contraseña" no es valida';
            }
        }else{
            $errors["adminContrasena"] = 'Administrador, debe colocar su contraseña para editar una bitácora';
        }
        
        if(!empty($fechaInicio)){
            if(!preg_match('/^(\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/', $fechaInicio)){
                $errors["fechaInicio"] = "Formato de fecha no valido";
            }
        }else{
            $errors["fechaInicio"] = "La fecha de inicio no puede estar vacía...";
        }
        
        if(isset($usuario)){
            if(empty(trim($usuario))){
                $errors["usuario"] = "Eres un pillín, no le muevas a lo prohibido...";
            }else{
                if(preg_match('/[A-Za-z]+/', $usuario) ||
                    preg_match('/[=!@#$%^&*(),.?":{}|<>]+/', $usuario)){
                    $errors["usuario"] = "Eres un pillín, no le muevas a lo prohibido...";
                }
            }
        }
        
        if(isset($servicio)){
            if(empty(trim($servicio))){
                    $errors["servicio"] = "El servicio no puede estar vacío...";
            }else{
                if(preg_match('/[<>=]+/', $servicio)){
                    $errors["servicio"] = 'Su descripción de servicio tiene símbolos no válidos, ejemplo: <> o =';
                }
            }
        }
        
        if(isset($seHizo)){
            if(empty(trim($seHizo))){
                $errors["servicio"] = "El campo actividades realizadas no puede estar vacío...";
            }else{
                if(preg_match('/[<>=]+/', $seHizo)){
                    $errors["seHizo"] = 'Su descripción de actividades realizadas tiene símbolos no válidos, ejemplo: <> o =';
                }
            }
        }
        
        if(isset($motivoCancelacion)){
            if(empty(trim($motivoCancelacion))){
                $errors["motivoCancelacion"] = "El campo motivo de cancelación no puede estar vacío...";
            }else{
                if(preg_match('/[<>=]+/', $motivoCancelacion)){
                    $errors["motivoCancelacion"] = 'Su descripción de motivo de cancelación tiene símbolos no válidos, ejemplo: <> o =';
                }
            }
        }
        
        if(isset($observaciones)){
            if(preg_match('/[<>=]+/', $observaciones)){
                    $errors["observaciones"] = 'Su descripción de observaciones tiene símbolos no válidos, ejemplo: <> o =';
            }
        }

        if(isset($fechaFin)){
            if(empty(trim($fechaFin))){
                    $errors["fechaFin"] = "La fecha de finalización no puede estar vacía...";
            }else{
                if(!preg_match('/^(\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/', $fechaFin)){
                    $errors["fechaFin"] = "Formato de fecha no valido";
                }
            }
        }
        
        if(isset($monto)){
            if(!empty($monto)){
                /*Si el campo del formulario "precio" está definido y no es un string vacío, entonces lo primero que se hace es evaluar el valor del 
                    * campo, filter_var de PHP puede aplicar filtros númericos incluso si el valor es un string númerico (normalmente, los valores de 
                    * los campos de un formulario son strings)*/
                if(!filter_var($monto, FILTER_VALIDATE_FLOAT)) {
                    /*Si filter_var con la constante FILTER_VALIDATE_FLOAT da false significa que el valor del campo no tiene un formato de número flotante, 
                    * por lo que se le agrega al arreglo $errorArr el indice "precio" con un string indicando que el campo no es valido*/ 
                    $errors["monto"] = "el precio que se ingresó no es un formato valido, hay que poner punto en lugar de coma en el precio...Ejemplo: 2345.75";
                }else{
                    /*Si el campo "monto" tiene un formato de número flotante, entonces entra al bloque falso del if, lo primero que se hace es inicializar 
                        * la variable $pos con lo que devuelve strpos, strpos de PHP devuelve el numero del indice donde se encuentra la coincidencia en este caso 
                        * el punto flotante "." (los string tambien son arrays, cada caracter es un indice)*/
                    $pos = strpos($monto, ".");
                    /*Los numeros enteros tambien son considerados flotantes en el filter_var, por lo que strpos puede devolver un false al no encontrar el 
                        * punto flotante*/
                    if(!empty($pos)){
                        /*Si $pos tiene el numero del indice donde aparece el punto flotante, entonces se inicializa la variable $decimal_parts con el calculo 
                            * para conseguir los decimales, esto se consigue obteniendo la cantidad de caracteres del array (el string del campo "precio") con 
                            * strlen de PHP, esa función cuenta los caracteres a partir del numero 1, los arrays empiezan con el numero 0 y strpos devuelve un 
                            * numero conciderando el indice 0, asi que se tiene que restar el numero que devuelve strlen con 1, despues se resta el valor que 
                            * tiene $pos, de esa forma se obtiene los decimales (la cantidad de caracteres despues del punto flotante)*/
                        $decimal_parts = strlen(trim($monto)) - 1 - $pos;
                        if($decimal_parts !== 2){
                            /*Si $decimal_parts tiene un valor diferente de dos entonces se le añade al array $errorArr el indice "precio" con un string 
                            * indicando que el campo no es valido*/ 
                            $errors["monto"] = "El precio debe ser de dos decimales...Ejemplo: 2345.75";
                        }
                    }
                }
            }
        }
        return $errors;            
    }
}