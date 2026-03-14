<?php
    /*El controlador FormController es el controlador principal del archivo index de la carpeta finishing, en el 
     * se gestionan las vistas del "formulario extendido" de una bitácora (donde se llenan los campos "actividades 
     * realizadas", "observaciones", fecha de finalización y llenado de firmas), esta clase está unicamente 
     * conformada por métodos publicos*/
    class FormController{
        
        //--------------------------------------MÉTODOS DE VISTAS----------------------------------------------------
        
        /*el método index maneja la vista por defecto del formulario extendido de una bitácora, tiene un if principal 
         * que evalúa si no está vacía la sesión del usuario "identity" y si no está vacía la clave get "id", si la 
         * evaluación da false, PHP redirigirá al usuario a la vista por defecto del index de home*/
        public function index(){
            if(!empty($_SESSION["identity"]) && !empty($_GET["id"])){
                /*Si la evaluación da true, entonces lo primero que se hace es verificar si la clave get "id" tiene un 
                 * valor númerico, si la evaluación da false, PHP redirigirá al usuario a la vista por defecto del index 
                 * de home y cortará el flujo del código de este contexto con exit*/
                    if(preg_match('/[0-9]+/', $_GET["id"])){
                        /*Si la evaluación da true, entonces lo primero que se hace es guardar el tiempo actual en la 
                         * sesión "LAST_ACTIVITY" (la sesión del usuario tiene un tiempo de caducidad de 30 minutos)*/
                        $_SESSION['LAST_ACTIVITY'] = time();
                        
                        /*NOTA: PUEDE QUE SE PRECINDA DE LA VARIABLE $consent_post_path SI SE DECIDE ELIMINAR EL CAMPO 
                         * FIRMA DE LA TABLA CLIENTES Y MEJOR PONER ESE CAMPO EN LA TABLA BITACORAS*/
                        $consent_post_path = "";
                        
                        //Se crea una instancia de la clase Bitacoras
                        $binn_obj = new Bitacoras();
                        //se añade el valor de la clave get "id" en la propiedad Id del objeto gracias al método setter setId()
                        $binn_obj->setId($_GET["id"]);
                        //se añade el valor del indice "id" de la sesión del usuario en la propiedad usuario_id del objeto gracias al método setter setUsuario_id()
                        $binn_obj->setUsuario_id($_SESSION["identity"]["Id"]);
                        try{
                            /*El método getServicioFieldById efectua una conexión a sql server para hacer una petición, por lo tanto, se crea un try-catch 
                             * para intentar la conexión, getServicioFieldById devuelve el campo "Servicio" de una bitácora dependiendo del Id de esta y el 
                             * id del usuario vinculado, los cuales se pasaron en los setter setId() y setUsuario_id() respectivamente, $service se inicializa 
                             * con el array que devuelve el método*/
                            $service = $binn_obj->getServicioFieldById();
                            /*en la variable $info se efectúa un operador ternario, este evalua si el indice "Servicio" del array $service no está vacío, si es true 
                             * $info contendrá el array que devolverá el método getInfoIfServicioIsNotNull, este devuelve los campos de una bitácora (por su id y id 
                             * del usuario) si en dado caso de que el campo "Servicio" tiene un valor, si el ternario da false, $info contendrá el array que devolvera 
                             * el método getInfoIfServicioIsNull, este método devuelve las columnas de una bitácora (por su id y id del usuario) considerando la 
                             * información del equipo vinculado a esa bitácora*/
                            $info = (!empty($service["Servicio"])) ? $binn_obj->getInfoIfServicioIsNotNull() :
                                $binn_obj->getInfoIfServicioIsNull();
                            
                            /*puede que el usuario en la barra del navegador cambie la clave get "id" con un numero Id que no exista en la base de datos, en ese caso,
                             * el método getServicioFieldById devolverá un array vacío en $service, por consiguiente $info tambien contendrá un array vacío, es por eso 
                             * que se hace una verificación con un if, si info esta vacío entonces PHP redirigirá al usuario a la vista de error y 
                             * cortará el flujo del código de este contexto con exit*/
                            if(empty($info)){
                                header("Location: ".base_url."home/?homeController=error&homeAction=index");
                                exit;
                            }
                            
                            /*Puede que se modifique la clave get "id" en la barra del navegador con un numero id que posiblemente exista en la base de datos, sin 
                             * embargo, estamos hablando de un seguimiento de bitácora, el usuario no debe acceder a bitácoras con estatus 'cancelado' o 'finalizado' 
                             * por lo tanto, se evalua el indice "Estatus" del array $info si es que tiene alguno de estos dos valores, si es que los tiene, PHP 
                             * redirigirá al usuario a la vista error y cortará el flujo del código de este contexto con exit*/
                            if(!empty($info)){
                                if($info["Estatus"] === "cancelado" || $info["Estatus"] === "finalizado"){
                                    header("Location: ".base_url."home/?homeController=error&homeAction=index");
                                    exit;
                                }
                            }
                        } catch (Exception $ex) {
                            /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "remindedOrConsentReportEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                            $_SESSION["remindedOrConsentReportEx"] = "No se logró obtener "
                                    ."la información necesaria para el seguimiento de "
                                    ."bitácoras, se cortó la conexión a la base de datos.";
                            /*PHP redirigirá al usuario a la vista por defecto de home, en el html de esa vista 
                             * se utilizará la sesión "remindedOrConsentReportEx", también php cortará el flujo del código de este contexto con exit*/
                            header("Location: ".base_url."home/");
                            exit;
                        }
                        
                        
                        /*Este operador ternario evalúa el indice "Actividades_realizadas" del array $info, si ese indice no está vacío, entonces se inicializa 
                         * la variable $info_verified con el string de la ruta de la vista consentInfo.php, si el indice "Actividades_realizadas" está vacío, 
                         * entonces se inicializa la variable $info_verified con el string de la ruta de la vista remindedfields.php*/
                        (!empty($info["Actividades_realizadas"])) ?
                            $info_verified = '../views/finishingLayouts/consentInfo.php' :
                            $info_verified = '../views/finishingLayouts/remindedfields.php';

                        
                    }else{
                        header("Location: ".base_url."home/");
                        exit;
                    }
                    /*si el if donde se evalua $_GET["id"] transcurrió todo el código del bloque true, quiere decir que la variable $info_verified está inicializada, 
                     * por lo tanto se importa la vista cuyo string de ruta está contenida en $info_verified*/
                    require_once $info_verified;
                    
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*El método techsign importa la vista del pad de firma del usuario (técnico) y los botones de opciones del pad absoluteElems.php, pero antes de eso, se hace 
         * una verificación, se evalúa si la sesión del usuario "identity" no está vacía y si está vacío el indice "Firma" de la sesión del usuario "identity" y si 
         * no está vacía la sesión con los datos del usuario (técnico) y el cliente (contacto) "binnacleSelection", si la condición no se cumple, entonces PHP redirigirá 
         * al usuario a la vista por defecto de home*/
        public function techsign(){
            
            if(!empty($_SESSION["identity"]) && !empty($_SESSION["dataSelectionForSigns"])){ 
                    /*Si la evaluación da true, entonces lo primero que se hace es guardar el tiempo actual en la 
                         * sesión "LAST_ACTIVITY" (la sesión del usuario tiene un tiempo de caducidad de 30 minutos)*/
                    $_SESSION['LAST_ACTIVITY'] = time();

                    require_once '../views/finishingLayouts/technicianCanvas.php';
                    require_once '../views/finishingLayouts/absoluteElems.php';
 
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*El método clientsign importa la vista del pad de firma del cliente (contacto) y los botones de opciones del pad absoluteElems.php, pero antes de eso, se hace 
         * una verificación, se evalúa si la sesión del usuario "identity" no está vacía y si no está vacía la sesión "binnacleSelection" con el indice "binnId", si la 
         * condición no se cumple, entonces PHP redirigirá al usuario a la vista por defecto de home*/
        public function clientsign(){
            if(!empty($_SESSION["identity"]) && 
                   !empty($_SESSION["dataSelectionForSigns"]["binnId"])){

                    /*se guarda el tiempo actual en la sesión "LAST_ACTIVITY" (la sesión del usuario tiene un tiempo de caducidad de 30 minutos)*/
                    $_SESSION['LAST_ACTIVITY'] = time();
                    require_once '../views/finishingLayouts/clientCanvas.php';
                    require_once '../views/finishingLayouts/absoluteElems.php';
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        //--------------------------------------MÉTODOS DE VISTAS----------------------------------------------------
        
        //--------------------------------------MÉTODOS DE GESTIÓN DE DATOS------------------------------------------
        /*los métodos de gestión de datos recepcionan los datos que se enviaron por un formulario, es decir, el array 
         * superglobal $_POST (hay un caso especial como el método insertsigns donde no recepciona datos de formulario 
         * $_POST sino datos de sesiones)*/
        
        /*followupPartial recepciona el $_POST del formulario del seguimiento de una bitácora (remindedfields.php), pero primero 
         * evalúa si la sesión del usuario "identity" no está vacía y si $_POST no es un array vacío, si no se cumple la condición, 
         * entonces PHP redirigirá al usuario a la vista por defecto de home*/
        public function followupPartial(){
            if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){
                
                    /*al cumplirse la condición, se utiliza el método estático verifyPostData para evaluar los datos del formulario 
                     * que contiene $_POST, este método puede devolver un array vacío o un array con indices de los distintos datos 
                     * no validos, se inicializa $errors_arr con el array que devuelve verifyPostData*/
                    $errors_arr = Utils::verifyPostData($_POST, "followupPartial");
                    if(sizeof($errors_arr) === 0){
                        /*si $errors_arr es un array vacío entonces se crea una instancia de la clase Bitacoras y se usan métodos 
                         * setter para llenar las propiedades privadas con sus respectivos valores de $_POST (y de la sesion del usuario "identity")*/
                        $binn_obj = new Bitacoras();
                        $binn_obj->setId($_POST["id"]);
                        $binn_obj->setUsuario_id($_SESSION["identity"]["Id"]);
                        $binn_obj->setActividades_realizadas(trim($_POST["seHizo"]));
                        $binn_obj->setObservaciones(trim($_POST["observaciones"]));
                        $binn_obj->setInicio($_POST["binnFecha"]);
                        $binn_obj->setEstatus($_POST["estatus"]);
                        try{
                            /*el metodo insertFollowupPartial utiliza un objeto de la clase PDO para conectarse a sql server, es por eso que se utiliza 
                             * un try-catch para intentar la conexión, este método hace una petición de actualización de una bitácora de acuerdo al id 
                             * de esta y el id del usuario pasados en sus respectivos setters*/
                            $binn_obj->insertFollowupPartial();
                        } catch (Exception $ex) {
                            /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "followupExeption" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                            $_SESSION["followupExeption"] = "No se actualizó la "
                                    ."bitacora con Id: ".$binn_obj->getId().
                                    ", probable corte de conexión a la base de datos";
                            /*PHP redirigirá al usuario a la vista de seguimiento de bitácoras (followUp.php), en el html de esa vista 
                             * se utilizará la sesión "followupExeption", también php cortará el flujo del código de este contexto con exit*/
                            header("Location: ".base_url."home/?homeController=user&homeAction=followuplist");
                            exit;
                        }
                        /*si se realizó la petición de actualización entonces PHP redirigirá al usuario a la vista por defecto de finishing, en este 
                         * caso sería en la vista de consentimiento de actividades (consentInfo.php), finalmente, PHP cortará el flujo del código de 
                         * este contexto con exit*/
                        header("Location: ".base_url."finishing/?controller=form&action=index&id=".$binn_obj->getId());
                        exit;
                    }else{
                        /*Si $errors_arr no es un array vacío, entonces se inicializa la sesión followupErr con este array*/
                        $_SESSION["followupErr"] = $errors_arr;
                    }
                    /*si se utiliza este header, quiere decir que $errors_arr no es un array vacío, entonces, PHP redirigirá al usuario a la vista 
                     * por defecto de finishing, en este caso, la vista de seguimiento de la bitácora (remindedFields.php), en esa vista se utiliza la 
                     * sesión followupErr para mostrarle al usuario los datos no validos al momento de hacer el formulario*/
                    header("Location: ".base_url."finishing/?controller=form&action=index&id=".
                            $_POST["id"]);
                    exit;
            }else{
                header("Location: ".base_url."home/");
                exit;
            }    
        }
        
        /*El método resetActivitiesDescriptions se utiliza cuando el usuario confirma la cancelación de una bitácora en la vista de consentimiento de 
         * actividades (consentInfo.php), haciendo una petición de actualización a la base de datos, elimina la información de los campos "Actividades 
         * realizadas" y "Observaciones" de una bitácora y le cambia el estatus a "en proceso", el método evalúa si no está vacía la sesión del usuario 
         * y si la calve get "id" no está vacía, si la condición no se cumple, PHP redirigirá al usuario a la vista por defecto de home*/
        public function resetActivitiesDescriptions(){
            if(!empty($_SESSION["identity"]) && !empty($_GET["id"])){
               
                    /*se hace una evaluación a la clave get "id", si es que su valor es númerico, si no lo es, PHP redirigirá al usuario a la vista por 
                     * defecto de home*/
                    if(preg_match('/[0-9]+/', $_GET["id"])){
                        /*Se crea una instancia de la clase Bitacoras, en los setters se agregan el id de la bitácora contenida en la clave get "id" y 
                         * el id del usuario proporcionado por la sesión "identity"*/
                        $binn_obj = new Bitacoras();
                        $binn_obj->setId($_GET["id"]);
                        $binn_obj->setUsuario_id($_SESSION["identity"]["Id"]);
                        
                        /*Este try es una evaluación en dado caso de que el usuario modifique la clave get "id" en la barra url del navegador*/
                        try{
                            /*El método getServicioFieldById efectua una conexión a sql server para hacer una petición, por lo tanto, se crea un try-catch 
                             * para intentar la conexión, getServicioFieldById devuelve el campo "Servicio" de una bitácora dependiendo del Id de esta y el 
                             * id del usuario vinculado, los cuales se pasaron en los setter setId() y setUsuario_id() respectivamente, $service se inicializa 
                             * con el array que devuelve el método*/
                            $service = $binn_obj->getServicioFieldById();
                            /*en la variable $info se efectúa un operador ternario, este evalua si el indice "Servicio" del array $service no está vacío, si es true 
                             * $info contendrá el array que devolverá el método getInfoIfServicioIsNotNull, este devuelve los campos de una bitácora (por su id y id 
                             * del usuario) si en dado caso de que el campo "Servicio" tiene un valor, si el ternario da false, $info contendrá el array que devolvera 
                             * el método getInfoIfServicioIsNull, este método devuelve las columnas de una bitácora (por su id y id del usuario) considerando la 
                             * información del equipo vinculado a esa bitácora*/
                            $info = (!empty($service["Servicio"])) ? $binn_obj->getInfoIfServicioIsNotNull() :
                                $binn_obj->getInfoIfServicioIsNull();
                            
                            /*puede que el usuario en la barra del navegador cambie la clave get "id" con un numero Id que no exista en la base de datos, en ese caso,
                             * el método getServicioFieldById devolverá un array vacío en $service, por consiguiente $info tambien contendrá un array vacío, es por eso 
                             * que se hace una verificación con un if, si info esta vacío entonces PHP redirigirá al usuario a la vista de error y cortará el flujo del 
                             * código de este contexto con exit*/
                            if(empty($info)){
                                header("Location: ".base_url."home/?homeController=error&homeAction=index");
                                exit;
                            }
                            
                            /*Puede que se modifique la clave get "id" en la barra del navegador con un numero id que posiblemente exista en la base de datos, sin 
                             * embargo, estamos hablando de un seguimiento de bitácora, el usuario no debe acceder a bitácoras con estatus 'cancelado' o 'finalizado' 
                             * por lo tanto, se evalua el indice "Estatus" del array $info si es que tiene alguno de estos dos valores, si es que los tiene, PHP 
                             * redirigirá al usuario a la vista de error y cortará el flujo del código de este contexto con exit*/
                            if(!empty($info)){
                                if($info["Estatus"] === "cancelado" || $info["Estatus"] === "finalizado"){
                                    header("Location: ".base_url."home/?homeController=error&homeAction=index");
                                    exit;
                                }
                            }
                        } catch (Exception $ex) {
                            /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "resetActivitiesIdEvaluationEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                            $_SESSION["resetActivitiesIdEvaluationEx"] = "No se logró evaluar el Id "
                                    ."de la bitácora para el proceso de reinicio, "
                                    ."se cortó la conexión a la base de datos.";
                            /*PHP redirigirá al usuario a la vista de seguimiento de bitácoras (followUp.php), en el html de esa vista 
                             * se utilizará la sesión "resetActivitiesIdEvaluationEx", también php cortará el flujo del código de este contexto con exit*/
                            header("Location: ".base_url."home/?homeController=user&homeAction=followuplist");
                            exit;
                        }
                        
                        try{
                            /*el metodo resetActivitiesDesc utiliza un objeto de la clase PDO para conectarse a sql server, es por eso que se utiliza 
                             * un try-catch para intentar la conexión, este método hace una petición de actualización de una bitácora de acuerdo al id 
                             * de esta y el id del usuario pasados en sus respectivos setters (da valor NULL a los campos Actividades_realizadas y 
                             * Observaciones y cambia el campo Estatus a "en proceso")*/
                            $binn_obj->resetActivitiesDesc();
                            $_SESSION["resetActivitiesSucceed"] = "Puedes actualizar las actividades después";
                        } catch (Exception $ex) {
                            /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "resetActivitiesException" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                            $_SESSION["resetActivitiesException"] = "No se pudo "
                                    . "reiniciar las actividades en la bitacora con Id: "
                                    .$binn_obj->getId().", probable corte de conexión a la base de datos";
                            /*PHP redirigirá al usuario a la vista de seguimiento de bitácoras (followUp.php), en el html de esa vista 
                             * se utilizará la sesión "resetActivitiesException", también php cortará el flujo del código de este contexto con exit*/
                            header("Location: ".base_url."home/?homeController=user&homeAction=followuplist");
                            exit;
                        }
                        /*Si se pudo realizar la petición de actualización, entonces, PHP redirigirá al usuario a la vista por defecto de finishing, 
                         * en este caso, el seguimiento de una bitácora (remindedFields.php), en el html de esa vista se utilizará la sesión 
                         * "resetActivitiesSucceed"*/
                        header("Location: ".base_url."finishing/?controller=form&action=index&id=".$binn_obj->getId());
                        exit;
                    }else{
                        header("Location: ".base_url."home/");
                        exit;
                    }
                
            }else{
                header("Location: ".base_url."home/");
                exit;
            }    
        }
        
        /*Se utiliza cancellingBinn después de que el usuario anota los motivos del por qué se canceló definitivamente una bitácora en la vista de seguimiento de 
         * una bitácora (remindedFields.php), este método evalua si la sesión del usuario no está vacía y si $_POST no es un array vacío, si no se cumple esta 
         * condición, PHP redirigirá al usuario a la vista por defecto de home*/
        public function cancellingBinn(){
            if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){
                
                    /*al cumplirse la condición, se utiliza el método estático verifyPostData para evaluar los datos del formulario 
                     * que contiene $_POST, este método puede devolver un array vacío o un array con indices de los distintos datos 
                     * no validos, se inicializa $errors_arr con el array que devuelve verifyPostData*/
                    $errors_arr = Utils::verifyPostData($_POST, "cancelDesc");
                    if(sizeof($errors_arr) === 0){
                        /*si $errors_arr es un array vacío entonces se crea una instancia de la clase Bitacoras y se usan métodos 
                         * setter para llenar las propiedades privadas con sus respectivos valores de $_POST (y de la sesion del usuario "identity")*/
                        $binn_obj = new Bitacoras();
                        $binn_obj->setId($_POST["cancelwithid"]);
                        $binn_obj->setUsuario_id($_SESSION["identity"]["Id"]);
                        $binn_obj->setEstatus($_POST["cancelestatus"]);
                        $binn_obj->setObservaciones(trim($_POST["cancelacion"]));
                        try{
                            /*el metodo cancelBinnacle utiliza un objeto de la clase PDO para conectarse a sql server, es por eso que se utiliza 
                             * un try-catch para intentar la conexión, este método hace una petición de actualización de una bitácora de acuerdo al id 
                             * de esta y el id del usuario pasados en sus respectivos setters*/
                            $binn_obj->cancelBinnacle();
                            $_SESSION["followUpCancelSucceed"] = "La bitacora con "
                                    ."Id: ".$binn_obj->getId()." Se canceló con éxito.";
                        } catch (Exception $ex) {
                            /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "followupCancelExeption" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                            $_SESSION["followupCancelExeption"] = "No se pudo cancelar"
                                    . " la bitacora con Id: ".$binn_obj->getId()." probable"
                                    . " corte de conexión a la base de datos";
                        }
                        /*PHP redirigirá al usuario a la vista de seguimiento de bitácoras (followUp.php), en el html de esa vista 
                         * se utilizará la sesión "followupCancelExeption" en dado caso de que PDO devolviera una excepción en la petición de actualización, 
                         * en dado caso de que la petición de actualización fue un exito, se utilizará la sesión "followUpCancelSucceed"*/
                        header("Location: ".base_url."home/?homeController=user&homeAction=followuplist");
                        exit;
                    }else{
                        /*Si $errors_arr no es un array vacío, entonces se inicializa la sesión followupErr con este array*/
                        $_SESSION["followupCancelErr"] = $errors_arr;
                        /*PHP redirigirá al usuario a la vista por defecto de finishing, en este caso, la vista de seguimiento de la bitácora (remindedFields.php), 
                         * en esa vista se utiliza la sesión followupCancelErr para mostrarle al usuario los datos no validos al momento de hacer el formulario*/
                        header("Location: ".base_url."finishing/?controller=form&action=index&id=".$_POST["cancelwithid"]);
                        exit;
                    }
                    
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        
        
        /*Se utiliza insertsign después de haber generado la firma del cliente*/
        /*ATENCIÓN: ESTE MÉTODO PUEDE CAMBIAR CONSIDERABLEMENTE SI SE DECIDE ELIMINAR EL CAMPO FIRMA 
         * DE LA TABLA CLIENTES (CONTACTOS) Y PONER EL CAMPO FIRMA MEJOR EN LA BITÁCORA*/
        public function finishbinnacle(){
            if(!empty($_SESSION["identity"])                &&
               !empty($_SESSION["dataSelectionForSigns"])   &&     
               !empty($_SESSION["clientSignature"])         && 
               !empty($_SESSION["identity"]["Firma"])){
                    
                    /*Se crea una instancia de la clase Bitacoras, se utilizan setter para pasar el id de la bitácora 
                     * contenido en la sesión "binnacleSelection" en la propiedad privada $id y el id del usuario contenido en la sesión "identity" 
                     * en la propiedad privada $usuario_id*/
                    $binnacle_obj = new Bitacoras();
                    $binnacle_obj->setId($_SESSION["dataSelectionForSigns"]["binnId"]);
                    $binnacle_obj->setUsuario_id($_SESSION["identity"]["Id"]);
                    $binnacle_obj->setFirma_cliente($_SESSION["clientSignature"]);

                    try {
                        /*el metodo finishBinnacle utiliza un objeto de la clase PDO para conectarse a sql server, es por eso que se utiliza 
                         * un try-catch para intentar la conexión, este método hace una petición de actualización de una bitácora de acuerdo al id 
                         * de esta y el id del usuario pasados en sus respectivos setters (se obtiene la fecha actual del servidor y lo aloja en el campo 
                         * Fecha_fin y cambia el campo Estatus a "finalizado")*/
                        $binnacle_obj->finishBinnacle();
                        $_SESSION["binnFinishingsucceed"] = "La bitacora con Id: "
                                . $binnacle_obj->getId() . " ha sido finalizada correctamente";
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                         * llamado "binnFinishingException" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                         * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["binnFinishingException"] = "no se pudo finalizar la bitacora"
                                . " con Id: " . $binnacle_obj->getId() . " probable falta de conexión";
                        if(!unlink("uploads/firmas/".$_SESSION["clientSignature"])){
                            $_SESSION["unlinkClientSignEx"] = "La supuesta firma del cliente no se encontró en la aplicación web";
                        }
                    }
                    
                    

                    /*Se utiliza el metodo estático unsetFormSessions de la clase Utils para eliminar las sesiones creadas en los métodos utilizados 
                     * en el index de finishing saveSignaturesFiles y setbinnacleSelection (de la clase Utils) respectivamente*/
                    Utils::unsetFormSessions();
                    /*PHP redirigirá al usuario a la vista de seguimiento de bitácoras (followUp.php), en el html de esa vista 
                     * se utilizará la sesión "binnFinishingException" en dado caso de que PDO devolviera una excepción en la petición de actualización, 
                     * en dado caso de que la petición de actualización fue un exito, se utilizará la sesión "binnFinishingsucceed"*/
                    header("Location: ".base_url."home/?homeController=user&homeAction=followuplist");
                    exit;
            } else {
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        //--------------------------------------MÉTODOS DE GESTIÓN DE DATOS------------------------------------------
        
    }