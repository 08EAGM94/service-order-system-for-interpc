<?php
    /*La clase Utils solo está conformada por métodos estáticos (esto es que no 
     * es necesario instanciar un objeto de esta clase para acceder a los métodos), 
     * la gran mayoría de estos no devuelven un valor, son procedimientos, el 
     * proposito de estos métodos es evitar saturación de lineas de código en los 
     * archivos index.php y los controladores (FormController y UserController) y 
     * tener de forma más organizada funcionalidades clave (del lado del servidor) 
     * del proyecto*/
    class Utils{
        
        //------------------------general procedures----------------------------
        /*Esta sección pertenece a los métodos de uso "general", esto quiere decir 
         * que hay metodos que solo se usan en los archivos index, métodos
         * que se pueden usar en los archivos html de las vistas y métodos que 
         * pueden ser usados por caulquiera de nuestros controladores*/
        
        /*El método estático showError crea una instancias del controlador ErrorController
         * para poder utilizar su unico método "index" en el hay un html cuyo 
         * mensaje es "La pagina que buscas no existe". showError lo utiliza
         * la estructura de control principal de los archivos finishing/index.php
         * y home/index.php cuando no encuentra un 
         * metodo de un controlador de acuerdo a lo que el usuario ingresa en 
         * los paramentros GET en el URL del navegador web*/
        public static function showError(){
            $error = new ErrorController();
            $error->index();
        }
        
        /*El método estático putSessionWithVerify inicializa una sesión en dado caso de que 
         * la variable superglobal $_SESSION esté vacio (vacio en php puede significar: 
         * valor null, valor booleano false, valor integer 0 o valor string "" o si
         * una variable no está inicializada); putSessionWithVerify tiene que ser
         * el primer método en utilizarse en finishing/index.php y home/index.php 
         * esto debido a que otros métodos que se utilizan en esos index necesitan 
         * una sesión inicializada para poder utilizar la variable superglobal
         * $_SESSION*/
        public static function putSessionWithVerify(){
            if(empty($_SESSION)){
                session_start();
            }
        }
        
        /*la sesión "LAST_ACTIVITY" se inicializa cuando el usuario tuvo acceso a 
         * la aplicación web ingresando con exito los datos que pide el login, 
         * esa sesión se inicializa con el valor numerico que devuelve la función 
         * time. El método estático sessionLifetime varifica si existe la sesión 
         * "LAST_ACTIVITY" y resta el valor actual que devuelve time() por el 
         * valor en el que se inicializó la sesión "LAST_ACTIVITY" y si el 
         * resultado de esa operación es mayor a 1800 segundos (30 minutos), si esa 
         * estructura de control es verdadera, entonces se eliminan todos los 
         * indices de $_SESSION y luego, $_SESSION se "destruye" 
         * con session_destroy(), finalmente PHP envía al usuario a la vista por 
         * defecto del index de home, que en este caso es el login*/
        public static function sessionLifetime(){
            if (!empty($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > 1800) {
                // Caducar sesión
                session_unset();
                session_destroy();
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*Esta aplicación web utiliza "flags", recuadros de colores donde se le notifica al usuario 
         * el resultado de las peticiones a la base de datos o en el ingreso de información en 
         * los formularios, el texto de estos mensajes se guardan en indices de la variable superglobal 
         * $_SESSION, es en los controladores (FormController y UserController) donde se crean estos 
         * indices, tambien se utiliza el metodo verifyPostData de esta clase (Utils) en los controladores 
         * dentro de la inicialización de algunos indices, los mensajes pueden ser el informar sobre peticiones exitosas en 
         * la base de datos, también pueden ser mensajes de errores al momento de no ingresar correctamente 
         * información en algún formulario de la aplicación y tambien puede ser un mensaje de excepción 
         * cuando no se pudo realizar alguna petición a la base de datos. El método estático unsetFlagsSessions verifica si están 
         * inicilizaados cada uno de los indices "flags" generados en los controladores, si encuentra 
         * indices inicializados entonces se eliminan de la variable superglobal $_SESSION, esto con el 
         * fin de no saturar de información al servidor. El método unsetFlagsSessions se utiliza en 
         * el html de las vistas donde se muestrán los "flags" al usuario, por lo general en 
         * formularios de la aplicación o vistas donde se hacen peticiones de lectura a la base de datos*/
        /*NOTA: SI SE DECIDE NO ELIMINAR REGISTROS EN LAS ENTIDADES: EMPRESAS, CLIENTES (CONTACTOS), EQUIPOS 
         * USUARIOS Y BITACORAS, ENTONCES PUEDE QUE SE ELIMINEN ALGUNOS INDICES EN ESTE MÉTODO*/
        public static function unsetFlagsSessions(){
            
            if(!empty($_SESSION["loginErrors"])){
                unset($_SESSION["loginErrors"]);
            }
            
            if(!empty($_SESSION["userDataErr"])){
                unset($_SESSION["userDataErr"]);
            }
            
            if(!empty($_SESSION["userDataSucceded"])){
                unset($_SESSION["userDataSucceded"]);
            }
            
            if(!empty($_SESSION["userDataException"])){
                unset($_SESSION["userDataException"]);
            }
            
            if(!empty($_SESSION["binnDataErr"])){
                unset($_SESSION["binnDataErr"]);
            }
            
            if(!empty($_SESSION["binnDataException"])){
                unset($_SESSION["binnDataException"]);
            }
            
            if(!empty($_SESSION["techSignInsertSucceed"])){
                unset($_SESSION["techSignInsertSucceed"]);
            }
            
            if(!empty($_SESSION["techSignInsertException"])){
                unset($_SESSION["techSignInsertException"]);
            }
            
            if(!empty($_SESSION["binnSignsInsertSucceed"])){
                unset($_SESSION["binnSignsInsertSucceed"]);
            }
            
            if(!empty($_SESSION["binnSignsInsertException"])){
                unset($_SESSION["binnSignsInsertException"]);
            }
            
            if(!empty($_SESSION["binnDataSucceed"])){
                unset($_SESSION["binnDataSucceed"]);
            }
            
            if(!empty($_SESSION["followupExeption"])){
                unset($_SESSION["followupExeption"]);
            }
            
            if(!empty($_SESSION["followupSucceed"])){
                unset($_SESSION["followupSucceed"]);
            }
            
            if(!empty($_SESSION["binnFinishingsucceed"])){
                unset($_SESSION["binnFinishingsucceed"]);
            }
            
            if(!empty($_SESSION["followupErr"])){
                unset($_SESSION["followupErr"]);
            }
            
            if(!empty($_SESSION["followUpCancelSucceed"])){
                unset($_SESSION["followUpCancelSucceed"]);
            }
            
            if(!empty($_SESSION["followupCancelExeption"])){
                unset($_SESSION["followupCancelExeption"]);
            }
            
            if(!empty($_SESSION["followupCancelErr"])){
                unset($_SESSION["followupCancelErr"]);
            }
            
            if(!empty($_SESSION["resetActivitiesException"])){
                unset($_SESSION["resetActivitiesException"]);
            }
            
            if(!empty($_SESSION["resetActivitiesSucceed"])){
                unset($_SESSION["resetActivitiesSucceed"]);
            }
            
            
            if(!empty($_SESSION["binnSignsInsertSucceed"])){
                unset($_SESSION["binnSignsInsertSucceed"]);
            }
            
            if(!empty($_SESSION["binnSignsInsertException"])){
                unset($_SESSION["binnSignsInsertException"]);
            }
            
            
            if(!empty($_SESSION["clientArrayException"])){
                unset($_SESSION["clientArrayException"]);
            }
            
            if(!empty($_SESSION["enterpriseArrayException"])){
                unset($_SESSION["enterpriseArrayException"]);
            }
            
            if(!empty($_SESSION["deviceArrayException"])){
                unset($_SESSION["deviceArrayException"]);
            }
            
            if(!empty($_SESSION["typeArrayException"])){
                unset($_SESSION["typeArrayException"]);
            }
            
            if(!empty($_SESSION["getInfoForSelectsException"])){
                unset($_SESSION["getInfoForSelectsException"]);
            }
            
            
            if(!empty($_SESSION["deviceInsertionException"])){
                unset($_SESSION["deviceInsertionException"]);
            }
            
            
            if(!empty($_SESSION["userPWDSucceed"])){
                unset($_SESSION["userPWDSucceed"]);
            }
            
            if(!empty($_SESSION["userPWDErr"])){
                unset($_SESSION["userPWDErr"]);
            }
            
            if(!empty($_SESSION["gettingUsersException"])){
                unset($_SESSION["gettingUsersException"]);
            }
            
            if(!empty($_SESSION["userInfoException"])){
                unset($_SESSION["userInfoException"]);
            }
            
            if(!empty($_SESSION["gettingEntersException"])){
                unset($_SESSION["gettingEntersException"]);
            }
            
            if(!empty($_SESSION["deviceReportException"])){
                unset($_SESSION["deviceReportException"]);
            }
            
            if(!empty($_SESSION["enterpriseInfoException"])){
                unset($_SESSION["enterpriseInfoException"]);
            }
            
            if(!empty($_SESSION["updateEnterInfoSucceed"])){
                unset($_SESSION["updateEnterInfoSucceed"]);
            }
            
            if(!empty($_SESSION["updateEnterInfoEx"])){
                unset($_SESSION["updateEnterInfoEx"]);
            }
            
            if(!empty($_SESSION["updateEnterpriseInfoErr"])){
                unset($_SESSION["updateEnterpriseInfoErr"]);
            }
            
            if(!empty($_SESSION["updateClientInfoErr"])){
                unset($_SESSION["updateClientInfoErr"]);
            }
            
            if(!empty($_SESSION["updateClientSucceed"])){
                unset($_SESSION["updateClientSucceed"]);
            }
            
            if(!empty($_SESSION["updateClientException"])){
                unset($_SESSION["updateClientException"]);
            }
            
            if(!empty($_SESSION["updateClientException"])){
                unset($_SESSION["updateClientException"]);
            }
            
            if(!empty($_SESSION["updateDeviceInfoSucceed"])){
                unset($_SESSION["updateDeviceInfoSucceed"]);
            }
            
            if(!empty($_SESSION["editDeviceGetInfoEx"])){
                unset($_SESSION["editDeviceGetInfoEx"]);
            }
            
            if(!empty($_SESSION["updateDeviceInfoEx"])){
                unset($_SESSION["updateDeviceInfoEx"]);
            }
            
            if(!empty($_SESSION["updateDeviceInfoErr"])){
                unset($_SESSION["updateDeviceInfoErr"]);
            }

            
            if(!empty($_SESSION["dces_arrException"])){
                unset($_SESSION["dces_arrException"]);
            }
            
            if(!empty($_SESSION["num_rowsEx"])){
                unset($_SESSION["num_rowsEx"]);
            }
            
            if(!empty($_SESSION["binnsRowsPaginationEx"])){
                unset($_SESSION["binnsRowsPaginationEx"]);
            }
            
            if(!empty($_SESSION["binnFilterErr"])){
                unset($_SESSION["binnFilterErr"]);
            }
            
            if(!empty($_SESSION["remindedOrConsentReportEx"])){
                unset($_SESSION["remindedOrConsentReportEx"]);
            }
            
            if(!empty($_SESSION["binnFinishingException"])){
                unset($_SESSION["binnFinishingException"]);
            }
            
            if(!empty($_SESSION["clientSignInsertException"])){
                unset($_SESSION["clientSignInsertException"]);
            }
            
            if(!empty($_SESSION["followUpQueryEx"])){
                unset($_SESSION["followUpQueryEx"]);
            }
            
            if(!empty($_SESSION["followUpNumRowsEx"])){
                unset($_SESSION["followUpNumRowsEx"]);
            }
            
            if(!empty($_SESSION["num_of_devicesEx"])){
                unset($_SESSION["num_of_devicesEx"]);
            }
            
            if(!empty($_SESSION["binnReportgetClientsForSelectEx"])){
                unset($_SESSION["binnReportgetClientsForSelectEx"]);
            }
            
            if(!empty($_SESSION["num_of_clientsEx"])){
                unset($_SESSION["num_of_clientsEx"]);
            }
            
            if(!empty($_SESSION["clients_arrEx"])){
                unset($_SESSION["clients_arrEx"]);
            }
            
            if(!empty($_SESSION["resetActivitiesIdEvaluationEx"])){
                unset($_SESSION["resetActivitiesIdEvaluationEx"]);
            }
            
            if(!empty($_SESSION["getBinnInfoEx"])){
                unset($_SESSION["getBinnInfoEx"]);
            }
            
            if(!empty($_SESSION["updateBinnacleInfoErr"])){
                unset($_SESSION["updateBinnacleInfoErr"]);
            }
            
            if(!empty($_SESSION["binnacleUpdateEx"])){
                unset($_SESSION["binnacleUpdateEx"]);
            }
            
            if(!empty($_SESSION["binnacleUpdateSucceed"])){
                unset($_SESSION["binnacleUpdateSucceed"]);
            }

            
            if(!empty($_SESSION["identitySessionUpdateEx"])){
                unset($_SESSION["identitySessionUpdateEx"]);
            }
            
            if(!empty($_SESSION["getInfoForSelectException"])){
                unset($_SESSION["getInfoForSelectException"]);
            }
            
            if(!empty($_SESSION["contactFormErr"])){
                unset($_SESSION["contactFormErr"]);
            }
            
            if(!empty($_SESSION["insertContactSucceed"])){
                unset($_SESSION["insertContactSucceed"]);
            }
            
            if(!empty($_SESSION["contactWithEnterIdInsertionException"])){
                unset($_SESSION["contactWithEnterIdInsertionException"]);
            }
            
            if(!empty($_SESSION["contactTotalInsertionException"])){
                unset($_SESSION["contactTotalInsertionException"]);
            }
            
            if(!empty($_SESSION["typeFormErr"])){
                unset($_SESSION["typeFormErr"]);
            }
            
            if(!empty($_SESSION["deviceFormErr"])){
                unset($_SESSION["deviceFormErr"]);
            }
            
            if(!empty($_SESSION["typeInsertionException"])){
                unset($_SESSION["typeInsertionException"]);
            }
            
            if(!empty($_SESSION["dataForSelectDceFormEx"])){
                unset($_SESSION["dataForSelectDceFormEx"]);
            }
            
            if(!empty($_SESSION["deviceInsertionException"])){
                unset($_SESSION["deviceInsertionException"]);
            }
            
            if(!empty($_SESSION["insertDeviceSucceed"])){
                unset($_SESSION["insertDeviceSucceed"]);
            }
            
            if(!empty($_SESSION["insertTypeSucceed"])){
                unset($_SESSION["insertTypeSucceed"]);
            }
            
            if(!empty($_SESSION["ent_arrEx"])){
                unset($_SESSION["ent_arrEx"]);
            }
            
            if(!empty($_SESSION["selectDataEnterEditEx"])){
                unset($_SESSION["selectDataEnterEditEx"]);
            }
            
            if(!empty($_SESSION["updateTypeSucceed"])){
                unset($_SESSION["updateTypeSucceed"]);
            }
            
            if(!empty($_SESSION["updateTypeException"])){
                unset($_SESSION["updateTypeException"]);
            }
            
            if(!empty($_SESSION["updateTypeInfoErr"])){
                unset($_SESSION["updateTypeInfoErr"]);
            }
            
            if(!empty($_SESSION["unlinkClientSignEx"])){
                unset($_SESSION["unlinkClientSignEx"]);
            }
            
            if(!empty($_SESSION["unlinkTechSignEx"])){
                unset($_SESSION["unlinkTechSignEx"]);
            }
            
            if(!empty($_SESSION["disableUserSuccess"])){
                unset($_SESSION["disableUserSuccess"]);
            }
            
            if(!empty($_SESSION["disableUserEx"])){
                unset($_SESSION["disableUserEx"]);
            }
            
            if(!empty($_SESSION["disableEnterpriseSuccess"])){
                unset($_SESSION["disableEnterpriseSuccess"]);
            }
            
            if(!empty($_SESSION["disableEnterpriseEx"])){
                unset($_SESSION["disableEnterpriseEx"]);
            }
            
            if(!empty($_SESSION["disableEnterErr"])){
                unset($_SESSION["disableEnterErr"]);
            }
            
            if(!empty($_SESSION["disableContactSuccess"])){
                unset($_SESSION["disableContactSuccess"]);
            }
            
            if(!empty($_SESSION["disableContactEx"])){
                unset($_SESSION["disableContactEx"]);
            }
            
            if(!empty($_SESSION["disableContactErr"])){
                unset($_SESSION["disableContactErr"]);
            }
            
            if(!empty($_SESSION["disableTypeSuccess"])){
                unset($_SESSION["disableTypeSuccess"]);
            }
            
            if(!empty($_SESSION["disableTypeEx"])){
                unset($_SESSION["disableTypeEx"]);
            }
            
            if(!empty($_SESSION["disableTypeErr"])){
                unset($_SESSION["disableTypeErr"]);
            }
            
            if(!empty($_SESSION["disableDeviceSuccess"])){
                unset($_SESSION["disableDeviceSuccess"]);
            }
            
            if(!empty($_SESSION["disableDeviceEx"])){
                unset($_SESSION["disableDeviceEx"]);
            }
            
            if(!empty($_SESSION["disableDeviceErr"])){
                unset($_SESSION["disableDeviceErr"]);
            }
            
            if(!empty($_SESSION["disableBinnSuccess"])){
                unset($_SESSION["disableBinnSuccess"]);
            }
            
            if(!empty($_SESSION["disableBinnEx"])){
                unset($_SESSION["disableBinnEx"]);
            }
            
            if(!empty($_SESSION["disableBinnErr"])){
                unset($_SESSION["disableBinnErr"]);
            }
        }
        
        /*El método estático verifyPostData es utilizado por los métodos de gestion de datos 
         * $_POST de los controladores FormController y UserController, necesita dos 
         * argumentos, el primer argumento es poner la variable superglobal $_POST tal 
         * cual en el método y el segundo argumento es un string el cual representa 
         * el formulario en cuastión, al poner los argumentos requeridos, lo primero
         * que se hace es inicializar el arreglo $errors, luego se verifica el valor del 
         * argumento $entity_str, este método tiene varios bloques if verificando ese 
         * argumento, la cantidad de bloques es en proporcion a la cantidad de 
         * formularios en la aplicación web, sientete libre de crear un bloque if 
         * si se decide agregar otro formulario en esta aplicación web, o también 
         * puedes eliminar bloques (antes de eliminar, varifica los valores de 
         * $entity_str en los controladores FormController y UserController) y agregar 
         * tu propia lógica de verificación de datos, por ejemplo en el caso de que 
         * el argumento $entity_str sea exactamente igual a "login", lo primero que 
         * se hace es (con un ternario) verificar si los campos del formulario tienen 
         * algún valor, en dado caso que lo tengan, se guardarán en sus respectivas 
         * variables ($nombre y $contrasena en este caso), en dado caso de que no 
         * tengan algún valor, entonces las variables $nombre y $contrasena tendrán 
         * valor null, luego, cada variable se verifica con su respectivo if, 
         * por ejemplo, en el caso de la variable $nombre, se verifica si tiene valor 
         * null en este caso o si en su valor string tiene la coincidencia de tener 
         * simbolos "<>" gracias a preg_match, si alguno de los dos devuelve true, 
         * entonces al arreglo $errors se le va a añadir el indice del error encontrado,
         * en ese caso es el indice "nombre", ese indice contiene un string el cual es 
         * un mensaje que va a leer el usuario, finalmente, este método verifyPostData 
         * va a devolver el propio arreglo $errors, ese arreglo lo utilizan los controladores 
         * FormController y UserController para inicializar un indice de la variable 
         * superglobal $_SESSION, ese indice de sesión se utiliza en el html de las vistas 
         * para mostrarle al usuario el mensaje en forma de "flags"*/
        public static function verifyPostData($globalarr, $entity_str){
            
            $errors = array();
            
            /*Bloque perteneciente al formulario de la vista del login*/
            if($entity_str === "login"){
                $nombre = (!empty(trim($globalarr["user"]))) ? trim($globalarr["user"]) :
                    null;
                $contrasena = (!empty(trim($globalarr["pwd"]))) ? $globalarr["pwd"] :
                    null;
                
                if(empty($nombre) || preg_match('/[<>]+/', $nombre)){
                    $errors["nombre"] = "El nombre no es valido";
                }
                
                if(empty($contrasena) || preg_match('/[<>]+/', $contrasena)){
                    $errors["contrasena"] = "La contraseña no es valida";
                }
               
            }
            
            /*Bloque perteneciente al formulario de la vista de registro de usuarios*/
            if($entity_str === "user"){
                $nombre = (!empty(trim($globalarr["nombre"]))) ? trim($globalarr["nombre"]) :
                    null;
                $apellidos = (!empty(trim($globalarr["apellidos"]))) ? trim($globalarr["apellidos"]) :
                    null;
                $alias = (!empty(trim($globalarr["alias"]))) ? trim($globalarr["alias"]) :
                    null;
                $contrasena = (!empty(trim($globalarr["contrasena"]))) ? $globalarr["contrasena"] :
                    null;
                $confContrasena = (!empty(trim($globalarr["confContrasena"]))) ? $globalarr["confContrasena"] :
                    null;
                $privilegio = (!empty(trim($globalarr["privilegio"]))) ? trim($globalarr["privilegio"]) :
                    null;
                $adminContrasena = (!empty(trim($globalarr["adminContrasena"]))) ? $globalarr["adminContrasena"] :
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
            }
            
            /*Bloque perteneciente al formulario de la vista de reestablecer contraseña de un usuario*/
            if($entity_str === "userUpdatePWD"){
                
                $usuarioId = (!empty(trim($globalarr["usuarioId"]))) ? trim($globalarr["usuarioId"]) :
                    null;
                $contrasena = (!empty(trim($globalarr["contrasena"]))) ? ($globalarr["contrasena"]) :
                    null;
                $confContrasena = (!empty(trim($globalarr["confContrasena"]))) ? ($globalarr["confContrasena"]) :
                    null;
                $adminContrasena = (!empty(trim($globalarr["adminContrasena"]))) ? $globalarr["adminContrasena"] :
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
            }
            
            /*Bloque perteneciente al formulario de la vista de registro de bitácora*/
            if($entity_str === "new-binnacle"){
                
                $userId = (!empty($globalarr["userId"])) ? $globalarr["userId"] :
                    null;
                $contactos = (!empty($globalarr["contactos"])) ? $globalarr["contactos"] :
                    null;
                $tipoActividades = (!empty($globalarr["tipoActividades"])) ? $globalarr["tipoActividades"] :
                    null;
                $servicio = (!empty(trim($globalarr["servicio"]))) ? trim($globalarr["servicio"]) :
                    null;
                $equipos = (!empty(trim($globalarr["equipos"]))) ? trim($globalarr["equipos"]) :
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

            }
            
            /*Bloque perteneciente al formulario de la vista de seguimiento de bitácora donde se llenan los campos 
             * "Actividades realizadas" y "Observaciones"*/
            if($entity_str === "followupPartial"){
                
                $id = (!empty(trim($globalarr["id"]))) ? trim($globalarr["id"]) :
                    null;
                $estatus = (!empty(trim($globalarr["estatus"]))) ? trim($globalarr["estatus"]) :
                    null;
                $binnFecha = (!empty(trim($globalarr["binnFecha"]))) ? $globalarr["binnFecha"] :
                    null;
                $seHizo = (!empty(trim($globalarr["seHizo"]))) ? $globalarr["seHizo"] :
                    null;
                $observaciones = (!empty(trim($globalarr["observaciones"]))) ? $globalarr["observaciones"] :
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
            }
            
            /*Bloque perteneciente al formulario del motivo de cancelación de una bitácora en la vista de seguimiento de bitácora*/
            if($entity_str === "cancelDesc"){
                $cancelwithid = (!empty(trim($globalarr["cancelwithid"]))) ? trim($globalarr["cancelwithid"]) :
                    null;
                $cancelestatus = (!empty(trim($globalarr["cancelestatus"]))) ? trim($globalarr["cancelestatus"]) :
                    null;
                $cancelacion = (!empty(trim($globalarr["cancelacion"]))) ? $globalarr["cancelacion"] :
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
                
            }
            
            /*Bloque perteneciente al formulario de la vista de edición de Empresa y contactos o el formulario de creación de contacto (y la empresa 
             * que estará vinculada a este)*/
            if($entity_str === "updateEnterpriseInfo" || $entity_str === "newContact"){
                
                if($entity_str === "newContact"){
                    if(isset($globalarr["hiddenEntId"])){
                        $hiddenEntId = (!empty(trim($globalarr["hiddenEntId"]))) ? trim($globalarr["hiddenEntId"]) :
                        null;
                    }
                    $contacto = (!empty(trim($globalarr["contacto"]))) ? trim($globalarr["contacto"]) :
                    null;
                }
                
                if($entity_str === "updateEnterpriseInfo"){
                    $adminContrasena = (!empty(trim($globalarr["adminContrasena"]))) ? $globalarr["adminContrasena"] :
                    null;
                }
                $nombreComercial = (!empty(trim($globalarr["nombreComercial"]))) ? trim($globalarr["nombreComercial"]) :
                    null;
                $razonSocial = (!empty(trim($globalarr["razonSocial"]))) ? trim($globalarr["razonSocial"]) :
                    null;
                $calleYNumero = (!empty(trim($globalarr["calleYNumero"]))) ? trim($globalarr["calleYNumero"]) :
                    null;
                $entreCalles = (!empty(trim($globalarr["entreCalles"]))) ? trim($globalarr["entreCalles"]) :
                    null;
                $dirigirseCon = (!empty(trim($globalarr["dirigirseCon"]))) ? trim($globalarr["dirigirseCon"]) :
                    null;
                $telefonos = (!empty(trim($globalarr["telefonos"]))) ? trim($globalarr["telefonos"]) :
                    null;
                $horario = (!empty(trim($globalarr["horario"]))) ? trim($globalarr["horario"]) :
                    null;
                $atencion = (!empty(trim($globalarr["atencion"]))) ? trim($globalarr["atencion"]) :
                    null;
                $colonia = (!empty(trim($globalarr["colonia"]))) ? trim($globalarr["colonia"]) :
                    null;
                $localidad = (!empty(trim($globalarr["localidad"]))) ? trim($globalarr["localidad"]) :
                    null;
                $email = (!empty(trim($globalarr["email"]))) ? trim($globalarr["email"]) :
                    null;
                
                if($entity_str === "newContact"){
                    
                    if(isset($globalarr["hiddenEntId"])){
                        if(!empty($hiddenEntId)){
                            if(preg_match('/[A-Za-z]+/', $hiddenEntId) ||
                                preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $hiddenEntId)){
                                $errors["hiddenEntId"] = "Eres un pillín, deja de moverle a lo prohibido...";
                            }
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
                }
                
                if($entity_str === "updateEnterpriseInfo"){
                    if(!empty($adminContrasena)){
                        if(preg_match('/[<>]+/', $adminContrasena)){
                            $errors["adminContrasena"] = 'Administrador, su "Contraseña" no es valida';
                        }
                    }else{
                        $errors["adminContrasena"] = 'Administrador, debe colocar su contraseña para poder cambiar la información';
                    }
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
            }
            
            /*Bloque perteneciente al formulario de la vista de edición de empresa y sus contactos, (el formulario de un contacto de 
             * los muchos que puede haber en esa vista)*/
            if($entity_str === "updateContactInfo"){
                
                $nombre = (!empty(trim($globalarr["nombre"]))) ? trim($globalarr["nombre"]) :
                    null;
                $adminContrasena = (!empty(trim($globalarr["adminContrasena"]))) ? $globalarr["adminContrasena"] :
                    null;
                
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
            }
            
            /*Bloque perteneciente al formulario de la vista de edición de un tipo, o la vista del formulario de creación de un tipo*/
            if($entity_str === "updateTypeInfo" || $entity_str === "newType"){
                $tipo = (!empty(trim($globalarr["tipo"]))) ? trim($globalarr["tipo"]) :
                    null;
                
                if($entity_str === "updateTypeInfo"){
                    $adminContrasena = (!empty(trim($globalarr["adminContrasena"]))) ? $globalarr["adminContrasena"] :
                    null;
                    
                    if(!empty($adminContrasena)){
                        if(preg_match('/[<>]+/', $adminContrasena)){
                            $errors["adminContrasena"] = 'Administrador, su "Contraseña" no es valida';
                        }
                    }else{
                        $errors["adminContrasena"] = 'Administrador, debe colocar su contraseña para poder cambiar la información';
                    }
                }
                
                if(!empty($tipo)){
                    if(preg_match('/[0-9]+/', $tipo) ||
                        preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $tipo)){
                        $errors["tipo"] = "El tipo no es valido, solo escribe letras";
                    }
                }else{
                    $errors["tipo"] = "El campo del tipo se encuentra vacío";
                }
            }
            
            /*Bloque perteneciente al formulario de la vista de edición de equipo o el formulario de creación de equipo*/
            if($entity_str === "updateDeviceInfo" || $entity_str === "newDevice"){
                
                if($entity_str === "newDevice"){
                    $empresas = (!empty(trim($globalarr["empresas"]))) ? trim($globalarr["empresas"]) :
                    null;
                    $tipos = (!empty(trim($globalarr["tipos"]))) ? trim($globalarr["tipos"]) :
                    null;
                }
                
                if($entity_str === "updateDeviceInfo"){
                    $adminContrasena = (!empty(trim($globalarr["adminContrasena"]))) ? $globalarr["adminContrasena"] :
                    null;
                }
                $marca = (!empty(trim($globalarr["marca"]))) ? trim($globalarr["marca"]) :
                    null;
                $modelo = (!empty(trim($globalarr["modelo"]))) ? trim($globalarr["modelo"]) :
                    null;
                $ns = (!empty(trim($globalarr["ns"]))) ? trim($globalarr["ns"]) :
                    null;
                $numeroInventario = (!empty(trim($globalarr["numeroInventario"]))) ? trim($globalarr["numeroInventario"]) :
                    null;
                
                if($entity_str === "newDevice"){
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
                }
                
                if($entity_str === "updateDeviceInfo"){
                    if(!empty($adminContrasena)){
                        if(preg_match('/[<>]+/', $adminContrasena)){
                            $errors["adminContrasena"] = 'Administrador, su "Contraseña" no es valida';
                        }
                    }else{
                        $errors["adminContrasena"] = 'Administrador, debe colocar su contraseña para poder cambiar la información';
                    }
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
            }
            
            /*Bloque perteneciente al formulario de busqueda de la vista de reportes de bitácoras*/
            if($entity_str === "binnsFilter"){
                
                $empresaId = (!empty($globalarr["empresaId"])) ? $globalarr["empresaId"] : null;
                $contactoId = (!empty($globalarr["contactoId"])) ? $globalarr["contactoId"] : null;
                $serviciOEquipo = (!empty($globalarr["servicioOEquipo"])) ? $globalarr["servicioOEquipo"] : null;
                $equipoId = (!empty($globalarr["equipoId"])) ? $globalarr["equipoId"] : null;
                $estatus = (!empty($globalarr["estatus"])) ? $globalarr["estatus"] : null;
                $startedOrEnded = (!empty($globalarr["startedOrEnded"])) ? $globalarr["startedOrEnded"] : null;
                $leftDay = (!empty($globalarr["leftDay"])) ? $globalarr["leftDay"] : null;
                $rightDay = (!empty($globalarr["rightDay"])) ? $globalarr["rightDay"] : null;
                $visible = (!empty($globalarr["visible"])) ? $globalarr["visible"] : null;
                
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
            }
            
            /*Bloque perteneciente al formulario de la vista de edición de bitácora dentro del reporte de bitácoras*/
            if($entity_str === "updateBinnacleInfo"){
                
                $bitacoraId = (!empty(trim($globalarr["bitacoraId"]))) ? $globalarr["bitacoraId"] : false;
                $estatus = (!empty(trim($globalarr["estatus"]))) ? $globalarr["estatus"] : false;
                $adminContrasena = (!empty(trim($globalarr["adminContrasena"]))) ? $globalarr["adminContrasena"] : false;
                $fechaInicio = (!empty(trim($globalarr["fechaInicio"]))) ? $globalarr["fechaInicio"] : false;
                
                if(!empty($bitacoraId)){
                    if(preg_match('/[A-Za-z]+/', $bitacoraId) ||
                        preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $bitacoraId)){
                        $errors["bitacoraId"] = "Eres un pillín, no le muevas a lo prohibido...";
                    }
                }else{
                    $errors["bitacoraId"] = "Eres un pillín, no le muevas a lo prohibido...";
                }
                
                if(!empty($estatus)){
                    if(preg_match('/[0-9]+/', $estatus) ||
                        preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $estatus)){
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
                
                if(isset($globalarr["usuario"])){
                    if(trim($globalarr["usuario"]) === ""){
                        $errors["usuario"] = "Eres un pillín, no le muevas a lo prohibido...";
                    }else{
                        if(preg_match('/[A-Za-z]+/', $globalarr["usuario"]) ||
                            preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $globalarr["usuario"])){
                            $errors["usuario"] = "Eres un pillín, no le muevas a lo prohibido...";
                        }
                    }
                }
                
                if(isset($globalarr["servicio"])){
                    if(trim($globalarr["servicio"]) === ""){
                         $errors["servicio"] = "El servicio no puede estar vacío...";
                    }else{
                        if(preg_match('/[<>]+/', $globalarr["servicio"])){
                            $errors["servicio"] = 'Su descripción de servicio tiene símbolos no válidos, ejemplo: <>';
                        }
                    }
                }
                
                if(isset($globalarr["seHizo"])){
                    if(trim($globalarr["seHizo"]) === ""){
                        $errors["servicio"] = "El campo actividades realizadas no puede estar vacío...";
                    }else{
                        if(preg_match('/[<>]+/', $globalarr["seHizo"])){
                            $errors["seHizo"] = 'Su descripción de actividades realizadas tiene símbolos no válidos, ejemplo: <>';
                        }
                    }
                }
                
                if(isset($globalarr["motivoCancelacion"])){
                    if(trim($globalarr["motivoCancelacion"]) === ""){
                        $errors["motivoCancelacion"] = "El campo motivo de cancelación no puede estar vacío...";
                    }else{
                        if(preg_match('/[<>]+/', $globalarr["motivoCancelacion"])){
                            $errors["motivoCancelacion"] = 'Su descripción de motivo de cancelación tiene símbolos no válidos, ejemplo: <>';
                        }
                    }
                }
                
                if(isset($globalarr["observaciones"])){
                    if(preg_match('/[<>]+/', $globalarr["observaciones"])){
                            $errors["observaciones"] = 'Su descripción de observaciones tiene símbolos no válidos, ejemplo: <>';
                    }
                }

                if(isset($globalarr["fechaFin"])){
                    if($globalarr["fechaFin"] === ""){
                            $errors["fechaFin"] = "La fecha de finalización no puede estar vacía...";
                    }else{
                        if(!preg_match('/^(\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/', $globalarr["fechaFin"])){
                            $errors["fechaFin"] = "Formato de fecha no valido";
                        }
                    }
                }
            }
            
            /*Este bloque contempla los campos del formulario de activación o desactivación de un registro de cualquiera de los modelos 
             * de esta aplicación*/
            if($entity_str === "enableOrDisable"){
                
                if(isset($globalarr["adminContrasena"])) $adminContrasena = (!empty(trim($globalarr["adminContrasena"]))) ? 
                        $globalarr["adminContrasena"] : null;
                
                if(isset($globalarr["usuarioId"])) $usuarioId = (!empty(trim($globalarr["usuarioId"]))) ? 
                        $globalarr["usuarioId"] : null;
                
                if(isset($globalarr["empresaId"])) $empresaId = (!empty(trim($globalarr["empresaId"]))) ? 
                        $globalarr["empresaId"] : null;
                
                if(isset($globalarr["contactoId"])) $contactoId = (!empty(trim($globalarr["contactoId"]))) ? 
                        $globalarr["contactoId"] : null;
                
                if(isset($globalarr["tipoId"])) $tipoId = (!empty(trim($globalarr["tipoId"]))) ? 
                        $globalarr["tipoId"] : null;
                
                if(isset($globalarr["equipoId"])) $equipoId = (!empty(trim($globalarr["equipoId"]))) ? 
                        $globalarr["equipoId"] : null;
                
                if(isset($globalarr["bitacoraId"])) $bitacoraId = (!empty(trim($globalarr["bitacoraId"]))) ? 
                        $globalarr["bitacoraId"] : null;
                
                $visibilidad = (!empty(trim($globalarr["visibilidad"]))) ? $globalarr["visibilidad"] : null;
                
                if(isset($globalarr["adminContrasena"])){
                    if(!empty($adminContrasena)){
                        if(preg_match('/[<>]+/', $adminContrasena)){
                            $errors["adminContrasena"] = 'Administrador, su "Contraseña" no es valida';
                        }
                    }else{
                        $errors["adminContrasena"] = 'Administrador, debe colocar su contraseña para realizar la activación/desactivación de este registro';
                    }
                }
                
                if(isset($globalarr["usuarioId"])){
                    if(!empty($usuarioId)){
                        if(preg_match('/[A-Za-z]+/', $usuarioId) ||
                            preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $usuarioId)){
                            $errors["usuarioId"] = "Eres un pillín, no le muevas a lo prohibido...";
                        }
                    }else{
                        $errors["usuarioId"] = "Eres un pillín, no le muevas a lo prohibido...";
                    }
                }
                
                if(isset($globalarr["empresaId"])){
                    if(!empty($empresaId)){
                        if(preg_match('/[A-Za-z]+/', $empresaId) ||
                            preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $empresaId)){
                            $errors["empresaId"] = "Eres un pillín, no le muevas a lo prohibido...";
                        }
                    }else{
                        $errors["empresaId"] = "Eres un pillín, no le muevas a lo prohibido...";
                    }
                }
                
                if(isset($globalarr["contactoId"])){
                    if(!empty($contactoId)){
                        if(preg_match('/[A-Za-z]+/', $contactoId) ||
                            preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $contactoId)){
                            $errors["contactoId"] = "Eres un pillín, no le muevas a lo prohibido...";
                        }
                    }else{
                        $errors["contactoId"] = "Eres un pillín, no le muevas a lo prohibido...";
                    }
                }
                
                if(isset($globalarr["tipoId"])){
                    if(!empty($tipoId)){
                        if(preg_match('/[A-Za-z]+/', $tipoId) ||
                            preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $tipoId)){
                            $errors["tipoId"] = "Eres un pillín, no le muevas a lo prohibido...";
                        }
                    }else{
                        $errors["tipoId"] = "Eres un pillín, no le muevas a lo prohibido...";
                    }
                }
                
                if(isset($globalarr["equipoId"])){
                    if(!empty($equipoId)){
                        if(preg_match('/[A-Za-z]+/', $equipoId) ||
                            preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $equipoId)){
                            $errors["equipoId"] = "Eres un pillín, no le muevas a lo prohibido...";
                        }
                    }else{
                        $errors["equipoId"] = "Eres un pillín, no le muevas a lo prohibido...";
                    }
                }
                
                if(isset($globalarr["bitacoraId"])){
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
            }
            
            return $errors;
        }
        //------------------------general procedures----------------------------
        
        //---------------------home user index methods & procedures-------------
        /*Esta sección pertenece a métodos que solo utiliza el archivo home/index.php y 
         * el controlador UserController (controlador principal de home/index.php)*/
        
        /*El método estático reportPdfGenerator solo lo utiliza home/index.php, dentro de el hay 
         * un procesdimiento el cual se conforma por una estructura de control para 
         * activar vistas html que serán procesadas por nuestra dependencia "domPDF" (es necesario que este método sea invocado antes de 
         * que se importe la vista html que inicia la semantica html, estamos hablando del archivo head.php, dompdf require html muy simple, 
         * las vistas que se importan en reportPdfGenerator tienen html con estilos directamente colocados en las etiquetas, ni siquiera se 
         * esta creando una semantica html como tal ya que se está tratando de un archivo pdf y no de un navegador)*/
        public static function reportPdfGenerator(){
            
            /*Este bloque if corresponde al PDF de reportes de dispositivos, verifica si 
             * el parametro $_GET (en el url del navegador web) tenga la clave "homeAction" con el valor "generateDevicesReport"*/
            if(!empty($_GET["homeAction"]) && $_GET["homeAction"] === "generateDevicesReport"){
                /*En dado caso de que la evaluación del if sea verdadera entonces se verifica si el 
                 * parametro $_GET tiene un valor exactamente igual a "generateDevicesReport", si 
                 * resulta verdadero, entonces se cargará la vista donde se instancia un objeto 
                 * de la clase DomPdf*/
                
                    
                    if(!empty($_SESSION["devicesReport_enterId"])){
                        /*si se envíó datos en el formulario de busqueda entonces lo primero que se hace es verificar si la empresa esta vinculada a varios equipos 
                         * por lo que necesitamos crear una instancia de la clase Equipos y le añadimos a su atributo privado empresa_id el id de la empresa contenido 
                         * en la sesión "devicesReport_enterId"*/
                        $device_obj = new Equipos();
                        $device_obj->setEmpresa_id($_SESSION["devicesReport_enterId"]);
                        try{
                            /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                             * try-catch para intentar la conexión; convertimos el string numerico que devuelve la función getDeviceCountByEnterprise (devuelve el 
                             * numero de registros de equipos vinculados al id de la empresa) en un valor entero con la función propia de PHP intval y lo contenemos en 
                             * la variable $num_of_devices*/
                            $num_of_devices = intval($device_obj->getDeviceCountByEnterprise());
                        } catch (Exception $ex) {
                            /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                                 * llamado "num_of_devicesEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                                 * base de datos con un lenguaje que pueda entender el usuario*/
                            $_SESSION["num_of_devicesEx"] = "Se generó un error interactuando "
                                    ."con la base de datos para poder calcular la cantidad de "
                                    ."dispositivos que posee la empresa seleccionada, lo más "
                                    ."probable es que se haya cortado la conexión a la base de datos";
                            header("Location: ".base_url."home/?homeController=user&homeAction=devicesReport");
                            exit; 
                        }

                        if($num_of_devices > 0){
                            $enter_obj = new Empresas();
                            $enter_obj->setId($_SESSION["devicesReport_enterId"]);
                            try{
                                /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                                * try-catch para intentar la conexión;el método getDevicesByEnterprise devuelve en forma de array 
                                * asociativo los registros de la tabla Equipos vinculados al id de una empresa pasado en el setter del objeto Equipos y getEnterpriseById 
                                * devuelve en forma de array asociativo los campos de un registro de la tabla Empresas 
                                * dependiendo del id de la empresa que se pasó en el setter del objeto Empresas*/
                                $enter_info = $enter_obj->getEnterpriseById();
                                $enter_devices = $device_obj->getDevicesByEnterprise();
                                $path = "../assets/img/logo.png";
                                $data = file_get_contents($path);
                                $logo_base64 = "data:image/png;base64,".base64_encode($data);
                            } catch (Exception $ex) {
                                /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                                 * llamado "deviceReportException" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                                 * base de datos con un lenguaje que pueda entender el usuario*/
                                $_SESSION["deviceReportException"] = "No se logró conseguir "
                                        ."la información para el reporte, posible corte "
                                        ."de conexión a la base de datos";
                                header("Location: ".base_url."home/?homeController=user&homeAction=devicesReport");
                                exit;  
                            }
                            
                            require_once '../views/adminLayouts/devicesPDF.php';
                            exit;
                        }   
                    }
            }
            
            
            /*Este bloque if corresponde al PDF de una bitácora, se evalúa una sesión que se 
             * generó en la vista de reportes de bitácoras (vista de administrador), "filtValidated" 
             * contiene el valor true luego de hacer una busqueda de bitácoras, finalmente se verifica si 
             * el parametro $_GET (en el url del navegador web) tenga las claves "homeAction" y "homeId"*/
            if(!empty($_SESSION["filtValidated"]) && !empty($_GET["homeAction"]) && !empty($_GET["homeId"])){
                /*Si este if da true, entonces lo primero que se hace es validar si la calve "homeAction" 
                 * del $_GET tenga un valor exactamente igual a "generateBinnacleReport"*/
                if($_GET["homeAction"] === "generateBinnacleReport"){
                    /*Si la clave homeAction tiene el string "generateBinnacleReport", entonces se hace 
                     * otra validación, en este caso, de la clave homeId, si no es un valor numerico, 
                     * entonces PHP redireccionará al usuario a la vista por defecto de home/index.php, 
                     * en este caso sería el recuadro de bienvenida del lado del administrador*/
                    if(preg_match('/[0-9]+/', $_GET["homeId"])){
                        /*Si la clave homeId tiene un valor numérico, entonces se instancia un objeto de 
                         * la clase Bitacoras y se le agrega a su propiedad Id el valor de la clave 
                         * homeId del paramentro get*/
                        $binn_obj = new Bitacoras();
                        $binn_obj->setId($_GET["homeId"]);
                        try{
                            /*Se genera un try-catch para las peticiones a la base de datos, lo primero 
                             * que se intenta hacer aqui es obtener el valor del campo Servicio de la 
                             * tabla Bitacoras de acuerdo a lo que se pasó en la propiedad Id de la case Bitacoras*/
                            $is_service = $binn_obj->getServicioFieldById();
                            /*en $binn_info se efectúa un operador ternario, si el campo Servicio no 
                             * está vacío, entonces se hace una petición a la base de datos donde se 
                             * contemple el campo Servicio de acuerdo al Id que se pasó y lo que devuelve getInfoIfServicioIsNotNull 
                             * se guardará en $binn_info, si el campo "Servicio" está vació, entonces 
                             * se hace una petición a la base de datos donde se contemple los datos 
                             * del equipo que se vinculó en la bitácora de acuerdo al Id que se pasó, lo que devuelve getInfoIfServicioIsNull 
                             * se guardará en $binn_info*/
                            $binn_info = (!empty($is_service["Servicio"])) ? $binn_obj->getInfoIfServicioIsNotNull() :
                                $binn_obj->getInfoIfServicioIsNull();
                            /*Aqui se hace una pequeña verificación, en dado caso de que el usuario escriba un id 
                             * en el url del navegador, si el id no existe en la tabla Bitacoras, PDO devolverá un false 
                             * en el caso del método getServicioFieldById y el método getInfoIfServicioIsNull, por lo 
                             * tanto, en esos casos $binn_info tendrá el valor false, PHP va a interpretar a esa 
                             * variable como "vacía"*/
                            if(empty($binn_info)){
                                /*Si $binn_info esta vacía, entonces PHP redirigirá al usuario a la vista de ErrorController*/
                                header("Location: ".base_url."home/?homeController=error&homeAction=index");
                                exit;
                            }

                            $logo_path = "../assets/img/logo.png";
                            $logo_file = file_get_contents($logo_path);
                            $logo_base64 = "data:image/png;base64,".base64_encode($logo_file);

                            $without_img_path = "../assets/img/no-image-icon-23494.png";
                            $without_img_file = file_get_contents($without_img_path);
                            $no_img_base64 = "data:image/png;base64,".base64_encode($without_img_file);

                            ($binn_info["Estatus"] !== 'en proceso') ?
                                $tech_sign_path = "../finishing/uploads/firmas/".$binn_info["Tecnico_firma"] : $tech_sign_path = null;
                            ($binn_info["Estatus"] === 'finalizado') ?
                                $cli_sign_path = "../finishing/uploads/firmas/".$binn_info["Firma_cliente"] : $cli_sign_path = null;    
                            
                            if(!empty($tech_sign_path)){
                                ($tech_sign_file = file_get_contents($tech_sign_path)) ?
                                $tech_base64 = "data:image/png;base64,".base64_encode($tech_sign_file) : $tech_base64 = null;
                            }
                            
                            if(!empty($cli_sign_path)){
                                ($cli_sign_file = file_get_contents($cli_sign_path)) ?
                                $cli_base64 = "data:image/png;base64,".base64_encode($cli_sign_file) : $cli_base64 = null;
                            }

                        } catch (Exception $ex) {
                            /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "getBinnInfoEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                            $_SESSION["getBinnInfoEx"] = "No se logró obtener los "
                                    ."datos de la bitácora seleccionada, posible "
                                    ."corte de conexión a la base de datos";
                            /*PHP redirigirá al usuario a la vista de reportes de bitácoras (binnaclesFilter.php), en el html de esa vista 
                             * se utilizará la sesión "getBinnInfoEx", también php cortará el flujo del código de este contexto con exit*/
                            header("Location: ".base_url."home/?homeController=user&homeAction=binnaclesReport");
                            exit;
                        }
                        /*Si la petición a la base de datos fue un exito, entonces se accede al campo "Monto" de la información extraída de la 
                         * base de datos contenida en $binn_info y se valida si el campo "Monto" no esta vacía (tiene un string diferente de "")*/
                        if(!empty($binn_info["Monto"])){
                            /*todo lo que la base de datos devuelve es información en forma de string, incluyendo valores numericos, entonces
                             * si Monto no es igual a "" entonces tiene un numero flotante, lo que se hace es convertir el indice "Monto" en un valor flotante y multiplicar 
                             * ese valor por 1.16 (calculo del IVA en México), el resultado se guardará en la variable $iva_result*/
                            $iva_result = floatval($binn_info["Monto"]) * 1.16;
                            /*en la variable $with_iva contendrá la cantidad contenida en $iva_result pero configurada para que este solo 
                             * tenga dos decimales, la variable $binn_info (y si entra en este if, tambien la variable $with_iva) lo 
                             * utilizará la vista binnacleInfoCanvas.php para que posteriormente nuestra dependencia DomPdf genere el PDF 
                             * de la bitácora solicitada*/
                            $with_iva = sprintf("%.2f", $iva_result);
                        }
                    }else{
                        header("Location: ".base_url."home/");
                        exit;
                    }
                    /*Si para PHP el arreglo $binn_info no está vacío (o si también se inicializó la variable $with_iva), entonces se activa la vista 
                     * donde se crea una instancia de la clase DomPdf para generar el PDF de la bitácora solicitada*/
                    require_once '../views/adminLayouts/binnacleInfoCanvas.php';
                    exit;
                }
            }
        }
        
        /*El método estático defaultUserPage solo lo utiliza el archivo home/index.php dentro de su 
         * estructura de control principal, si el usuario en el url del navegador anota 
         * como valor de la clave controller (en los parametros get del url) un nombre de 
         * controlador que no existe en el index (en el index se importan las clases de 
         * los controladores que solo utiliza home/index.php en este caso UserController 
         * y ErrorController) entonces se va a llamar a este método donde se activa 
         * la vista por defecto del index de home, pueden ser tres, el login en el caso 
         * de que no este inicializado la sesión "identity", la vista del menú de los 
         * usuarios (técnicos) cuando esta inicializada la sesión "identity" y 
         * finalmente el recuadro de bienvenida del administrador cuando están 
         * inicializadas las sesiones "identity" y "isAdmin"*/
        public static function defaultUserPage(){
            /*se usan las constantes declaradas en el archivo params.php (el cual tambien 
             * se importa en el archivo home/index.php), default_userController tiene el 
             * string "UserController" y default_action tiene el string "index", cada 
             * string estarán contenidos en las variables $controllerName y $defaultAction 
             * respectivamente*/
            $controllerName = default_userController;
            $defaultAction = default_action;
            /*finalmente utilizamos nuestras variables, en este caso $controllerName para 
             * crear una instancia de la clase UserController y $defaultAction para 
             * acceder al metodo index del controlador*/
            $controlador = new $controllerName();
            $controlador->$defaultAction();
        }
        
        /*el método estático generateWelcomeBanner solo lo utiliza el archivo home/index.php, dentro 
         * del método hay una estructura de control donde se activa la vista del banner de 
         * bienvenida del administrador en caso de que existan las sesiones "identity" y 
         * "isAdmin", si solo existe la sesión "identity" entonces solo activa la vista 
         * del banner de bienvenida de los usuarios (técnicos)*/
        public static function generateWelcomeBanner(){
            if (!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])) {
                require_once '../views/adminLayouts/menuSides/welcomeBanner.php';
            } else if (!empty($_SESSION["identity"])) {
                require_once '../views/userLayouts/menuSides/welcomeBanner.php';
            }
        }
        
        /*El método estático setAsideWithVerify solo lo utiliza el archivo home/index.php, 
         * su función es activar la vista de la barra lateral izquierda del menú 
         * de administradores si es que existe la sesión "isAdmin"*/
        public static function setAsideWithVerify(){
            if(!empty($_SESSION["isAdmin"])){
                require_once '../views/adminLayouts/menuSides/aside.php';
            }
        }
        
        /*el método estático setAdminWithVerify solo utiliza el método login del controlador 
         * UserController, su función es verificar si se inicializó la sesión "identity" (el 
         * cual tiene todos los datos del usuario sacados de la base de datos en la tabla 
         * Usuarios), también verifica si el indice "Privilegio" tiene un valor exactamente 
         * igual a "Admin", si es el caso, entonces se inicializa la sesión "isAdmin" con 
         * el valor booleano true*/
        public static function setAdminWithVerify(){
            if(!empty($_SESSION["identity"])){
                if ($_SESSION["identity"]["Privilegio"] === "Admin") {
                    $_SESSION["isAdmin"] = true;
                }
            }
        }
        
        /*El método estático ajaxProcedure solo lo utiliza el archivo home/index.php, este procedimiento maneja 
         * los JSON que el JavaScript envió dentro de la comunicación asincrona con fetch, dependiendo del atributo 
         * del JSON enviado, PHP enviará los datos requeridos en forma de JSON gracias a json_encode, es importante que este 
         * método se invoque antes de que se importe elementos html en home/index.php, esto es debido a que se pueden colar 
         * strings html dentro de las respuestas de PHP, el objetivo de PHP es enviar solo arrays asociativos convertidos a JSON*/
        public static function ajaxProcedure(){
          /*Este if principal da true si JavaScript envió datos en formato JSON a la url donde se utiliza este método 
           * estatico (url: "http://192.168.1.135/SOSv4/service-order-system/home/")*/  
          if(!empty($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
                /*Una vez identificado el envio de datos por parte de JavaScript lo primero es capturar lo que se envía 
                 * utilizando la función propia de PHP file_get_contents y poniendo como argumento el string "php://input"*/
                $data = file_get_contents("php://input");
                /*Luego, se usa la función propia de PHP json_decode para convertir el JSON de JavaScript a un array asociativo 
                 * el cual estará contenida en $input*/
                $input = json_decode($data, true);
                /*Lo que sigue son bloques if evaluando el posible indice de $input, es en esos if donde PHP envia información 
                 * dependiendo del contexto en el que está JavaScript*/
                
                /*JavaScript envia un JSON con un solo atributo llamado "number" en el contexto del evento "change" del select 
                 * de selección de cantidad de elementos en pantalla en la vista de seguimiento de bitácoras (followUp.php)*/
                if(!empty($input["number"])){
                    /*si el usuario selecciona un numero en el select de la vista followUp.php, entonces se efectúa los pasos 
                     * para utilizar la dependencia zebraPagination, primero se crea una instancia de la clase Bitacoras*/
                    $binnacle_obj = new Bitacoras();
                    /*luego se ingresa el Id del usuario que en este momento está usando la aplicación web gracias a la sesión 
                     * "identity", ese Id se va a guardar en la propiedad usuario_id de la clase Bitacoras*/
                    $binnacle_obj->setUsuario_id($_SESSION["identity"]["Id"]);
                    try {
                        /*Se efectúa un try-catch en las peticiones a la base de datos, el metodo getBinnCountByUserAndStatus 
                         * de la clase Bitacoras contiene una sentencia sql donde se obtienen los registros de bitacoras que 
                         * tengan como coincidencia en sus campos Usuario_id el id del usuario que le pasamos en la propiedad
                         * de la clase Bitacoras y si el estatus es igual a "en proceso" o "falta confirmar", el método va a 
                         * devolver el numero de registros que cumplieron con la condición, se utiliza la función propia de PHP intval 
                         * para convertir el numero string devuelto a un valor entero*/
                        $num_rows = intval($binnacle_obj->getBinnCountByUserAndStatus());
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "followUpNumRowsEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["followUpNumRowsEx"] = "Se generó un error al "
                                ."interactuar con la base de datos para la "
                                ."obtención de datos necesarios para calcular "
                                ."la paginación de seguimiento de bitácoras, "
                                ."lo más probable es que se haya cortado la "
                                ."conexión a la base de datos";
                        /*PHP redirigirá al usuario a la vista de menú de usuarios (menu.php), en el html de esa vista 
                             * se utilizará la sesión "followUpNumRowsEx", también php cortará el flujo del código de este contexto con exit*/
                        header("Location: ".base_url."home/");
                        exit;
                    }
                    /*si la petición a la base de datos fue un exito, entonces el valor del indice de $input se guarda en la sesión "jsondecoded", 
                     * esa sesión también lo utilizará el metodo followuplist del controlador UserController, la función de esa sesión es 
                     * mantener la opción que se seleccionó en el select de la vista followUp.php*/
                    $_SESSION["jsondecoded"] = intval($input["number"]);
                    /*Luego de inicializar la sesión "jsondecoded", ahora toca inicializar la variable $page_elem en el cual se guardará el valor 
                     * de la sesión "jsondecoded"*/
                    $page_elem = $_SESSION["jsondecoded"];
                    
                    /*creamos una instancia de la clase Zebra_Pagination*/
                    $pagination = new Zebra_Pagination();
                    /*al objeto le indicamos el numero de registros de bitácoras contenido en nuestra variable $num_rows*/
                    $pagination->records($num_rows);
                    /*al obejto le indicamos el numero de elementos por pagina utilizando la variable $page_elem*/
                    $pagination->records_per_page($page_elem);
                    /*get_page retorna el numero de la pagina seleccionada (siempre se inicia con la pagina numero 1), ese valor lo guardamos en $page*/
                    $page = $pagination->get_page();
                    /*la variable $empieza_aqui contiene el resultado del calculo de la cantidad de registros a descartar dependiendo de la pagina 
                     * seleccionada*/
                    $empieza_aqui = (($page - 1) * $page_elem);
                    /*$stmt_binns contiene una conexión a la base de datos con PDO gracias al metodo estatico de la clase DataBaseMssql, se utiliza el
                     * método de PDO prepare para colocar una sentencia sql, en este caso se obtienen registros de bitácoras que tengan el id del 
                     * usuario que esta usando la aplicación web gracias a la sesión "identity" y si sus campos Estatus tienen el valor "en proceso" 
                     * o "falta confirmar", tambien se ordenan los registros que se van a obtener, es ahí donde utilizamos nuestras variables $empieza_aqui 
                     * y $page_elem, por lo tanto, en esta petición se van a descartar registros dependiendo del valor de 
                     * $empieza_aqui y a partir de descartar esos registros se devuelven la cantidad de registros que siguen dependiendo del valor de $page_elem*/
                    $stmt_binns = DataBaseMssql::getConnection()->prepare("SELECT "
                                    ."b.Id, e.Nombre_comercial, b.Estatus FROM Bitacoras b "
                                    ."INNER JOIN Contactos c ON b.Contacto_id = c.Id "
                                    ."INNER JOIN Empresas e ON c.Empresa_id = e.Id "
                                    ."WHERE b.Usuario_id = ".$_SESSION["identity"]["Id"]
                                    ." AND (b.Estatus = 'en proceso' OR b.Estatus = 'falta confirmar') "
                                    ."ORDER BY b.Id OFFSET $empieza_aqui ROWS "
                                    ."FETCH NEXT $page_elem ROWS ONLY;");
                    try {
                        /*Usamos el método execute de PDO para enviar la sentencia sql para que SQL Server lo procese, es una petición tal cual, por lo que 
                         * se hace un try-cath*/
                        $stmt_binns->execute();
                        /*si el execute fue exitoso, entonces se inicializa el array asociativo $pagination_arr, en su indice "binns" se guardarán todos los 
                         * registros obtenidos en la petición a la base de datos de las bitácoras*/
                        $pagination_arr = [
                            "binns"   => $stmt_binns->fetchAll(),
                        ];
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "followUpQueryEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["followUpQueryEx"] = "Se generó un error al "
                                ."interactuar con la base de datos para la "
                                ."obtención de datos necesarios crear "
                                ."la paginación de seguimiento de bitácoras, "
                                ."lo más probable es que se haya cortado la "
                                ."conexión a la base de datos";
                        /*PHP redirigirá al usuario a la vista de menú de usuarios (menu.php), en el html de esa vista 
                             * se utilizará la sesión "followUpQueryEx", también php cortará el flujo del código de este contexto con exit*/
                        header("Location: ".base_url."home/");
                        exit;
                    }
                    

                    ob_start();
                    $pagination->render();
                    $pagination_html = ob_get_clean();

                    /*
                     * ob_start() Le dice a PHP: “Todo lo que se imprima a partir de 
                     * ahora, guárdalo en memoria en lugar de enviarlo al navegador.”
                     * $pagination->render() Genera el HTML de los botones de 
                     * paginación (normalmente se imprimiría en pantalla).
                     * ob_get_clean() Recupera todo lo que se imprimió desde que se 
                     * inició el buffer, lo guarda en $pagination_html, y limpia el 
                     * buffer (ya no se imprimirá nada accidentalmente).
                     */
                    //Guardamos nuestro control de paginación contenida en $pagination_html 
                    //a nuestro array asociativo $pagination_arr, se guardará en el indice
                    //"buttons"
                    $pagination_arr["buttons"] = $pagination_html;
                    
                    //Finalmente se crea una cabecera http indicando que se va a enviar datos en la comunicación asincrona que inició Javascript
                    header('Content-Type: application/json; charset=utf-8');
                    /*con echo enviamos nuestro array asociativo $pagination_arr el cual JavaScript va a interpretar como un JSON gracias 
                     * a json_encode, luego, PHP cortará el flujo del código de este contexto con exit para que no envíe texto html por accidente*/
                    echo json_encode($pagination_arr);
                    exit;
                    
                }
                
                /*JavaScript envia un JSON con un solo atributo llamado "binnsFilterNumber" en el contexto del evento "change" del select 
                 * de selección de cantidad de elementos en pantalla en la vista de reportes de bitácoras (binnaclesFilter.php vista de administrador)*/
                if(!empty($input["binnsFilterNumber"])){
                    /*Si se elecciona un nuemro en el select de la vista binnaclesFilter.php, lo primero que se hace es guardar en la variable $query_for_rows_calc 
                     * una sentencia sql, esta sentencia se usa para obtener el numero de registros de bitácoras dependiendo de las condiciones dadas, esas 
                     * condiciones están contenidas en las sesiones creadas durante el proceso de selección de filtros en la vista binnaclesFilter.php, al dar click 
                     * al botón "Filtrar" se crean estas sesiones gracias al método binnaclesReport del controlador UserController, esas sesiones son pedazos de oración sql*/
                    $query_for_rows_calc = "SELECT COUNT(Id) AS 'total' FROM Bitacoras b WHERE "
                                                .$_SESSION["SQL_Contacto_id"]
                                                .$_SESSION["SQL_isServiceOrDevice"]
                                                .$_SESSION["SQL_Equipo_id"]
                                                .$_SESSION["SQL_Estatus"]
                                                .$_SESSION["SQL_betweenDays"]
                                                .$_SESSION["SQL_visible"]
                                                .";";
                    //se crea una instancia de la clase Bitacoras
                    $binnacle_obj = new Bitacoras();
                    //a la propiedad external_query de la clase Bitacoras se le agrega nuestra sentencia sql contenida en $query_for_rows_calc
                    $binnacle_obj->setExternal_query($query_for_rows_calc);

                    try {
                        /*Como se realiza una petición a la base de datos se crea un try catch para intentar hacer la petición, el método 
                         * getBinnCountByFilterSessions de la clase Bitacoras devolve el numero de registros de bitácoras de acuerdo a las condiciones dadas, 
                         * se utiliza la función propia de PHP intval para convertir el string numerico en un valor entero*/
                        $num_rows = intval($binnacle_obj->getBinnCountByFilterSessions());
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "num_rowsEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["num_rowsEx"] = "No se logró obtener los datos para calcular la paginación, posible corte de conexión a la base de datos";
                        /*Se utiliza el metodo estático unsetBinnFilterSessions de la clase Utils para eliminar las sesiones que creó el método binnaclesReport 
                         * del controlador UserController*/
                        Utils::unsetBinnFilterSessions();
                        /*PHP redirigirá al usuario a la vista de reporte de bitácoras (binnaclesFilter.php), en el html de esa vista 
                             * se utilizará la sesión "num_rowsEx", también php cortará el flujo del código de este contexto con exit*/
                        header("Location: " . base_url . "home/user/?homeController=user&homeAction=binnaclesReport");
                        exit;
                    }
                    
                    /*si la petición a la base de datos fue un exito, entonces el valor del indice de $input se guarda en la sesión "binnsFilterNumRows", 
                     * esa sesión también lo utilizará el metodo binnaclesReport del controlador UserController, la función de esa sesión es 
                     * mantener la opción que se seleccionó en el select de la vista binnaclesFilter.php*/
                    $_SESSION["binnsFilterNumRows"] = intval($input["binnsFilterNumber"]);
                    /*Luego de inicializar la sesión "binnsFilterNumRows", ahora toca inicializar la variable $page_elem en el cual se guardará el valor 
                     * de la sesión "binnsFilterNumRows"*/
                    $page_elem = $_SESSION["binnsFilterNumRows"];
                    
                    /*creamos una instancia de la clase Zebra_Pagination*/
                    $pagination = new Zebra_Pagination();
                    /*al objeto le indicamos el numero de registros de bitácoras contenido en nuestra variable $num_rows*/
                    $pagination->records($num_rows);
                    /*al obejto le indicamos el numero de elementos por pagina utilizando la variable $page_elem*/
                    $pagination->records_per_page($page_elem);
                    /*get_page retorna el numero de la pagina seleccionada (siempre se inicia con la pagina numero 1), ese valor lo guardamos en $page*/
                    $page = $pagination->get_page();
                    /*la variable $empieza_aqui contiene el resultado del calculo de la cantidad de registros a descartar dependiendo de la pagina 
                     * seleccionada*/
                    $empieza_aqui = (($page - 1) * $page_elem);
                    
                    /*$stmt_binns contiene una conexión a la base de datos con PDO gracias al metodo estatico de la clase DataBaseMssql, se utiliza el
                     * método de PDO prepare para colocar una sentencia sql, en este caso se obtienen registros de bitácoras dependiendo de las condiciones 
                     * dadas gracias a las sesiones generadas por el método binnaclesReport del controlador UserController, tambien se ordenan los registros 
                     * que se van a obtener, es ahí donde utilizamos nuestras variables $empieza_aqui y $page_elem, por lo tanto, en esta petición se van a 
                     * descartar registros dependiendo del valor de $empieza_aqui y a partir de descartar esos registros se devuelven la cantidad de registros 
                     * que siguen dependiendo del valor de $page_elem*/
                    $stmt_binns = DataBaseMssql::getConnection()->prepare("SELECT "
                                ."b.Id, u.Nombre, u.Apellidos, c.Nombre_completo, e.Nombre_comercial, "
                                ."b.Visibilidad FROM Bitacoras b INNER JOIN Usuarios u ON b.Usuario_id = u.Id "
                                ."INNER JOIN Contactos c ON b.Contacto_id = c.Id "
                                ."INNER JOIN Empresas e ON c.Empresa_id = e.Id WHERE "
                                .$_SESSION["SQL_Contacto_id"]
                                .$_SESSION["SQL_isServiceOrDevice"]
                                .$_SESSION["SQL_Equipo_id"]
                                .$_SESSION["SQL_Estatus"]
                                .$_SESSION["SQL_betweenDays"]
                                .$_SESSION["SQL_visible"]
                                ." ORDER BY b.Id OFFSET "
                                .$empieza_aqui
                                ." ROWS "
                                ."FETCH NEXT "
                                .$page_elem. " ROWS ONLY;");
                    try {
                        /*Usamos el método execute de PDO para enviar la sentencia sql para que SQL Server lo procese, es una petición tal cual, por lo que 
                         * se hace un try-cath*/
                        $stmt_binns->execute();
                        /*si el execute fue exitoso, entonces se inicializa el array asociativo $pagination_arr, en su indice "binns" se guardarán todos los 
                         * registros obtenidos en la petición a la base de datos de las bitácoras*/
                        $pagination_arr = [
                            "binns"   => $stmt_binns->fetchAll()
                         ];
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "binnsRowsPaginationEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["binnsRowsPaginationEx"] = "Se generó un "
                                . "error interactuando con la base de datos "
                                . "en cuanto a la generación de paginación, posible falta de conexión";
                        /*Se utiliza el metodo estático unsetBinnFilterSessions de la clase Utils para eliminar las sesiones que creó el método binnaclesReport 
                         * del controlador UserController*/
                        Utils::unsetBinnFilterSessions();
                        /*PHP redirigirá al usuario a la vista de reporte de bitácoras (binnaclesFilter.php), en el html de esa vista 
                             * se utilizará la sesión "binnsRowsPaginationEx", también php cortará el flujo del código de este contexto con exit*/
                        header("Location: " . base_url . "home/?homeController=user&homeAction=binnaclesReport");
                        exit;
                    }

                    ob_start();
                    $pagination->render();
                    $pagination_html = ob_get_clean();
                    
                    /*
                     * ob_start() Le dice a PHP: “Todo lo que se imprima a partir de 
                     * ahora, guárdalo en memoria en lugar de enviarlo al navegador.”
                     * $pagination->render() Genera el HTML de los botones de 
                     * paginación (normalmente se imprimiría en pantalla).
                     * ob_get_clean() Recupera todo lo que se imprimió desde que se 
                     * inició el buffer, lo guarda en $pagination_html, y limpia el 
                     * buffer (ya no se imprimirá nada accidentalmente).
                     */
                    //Guardamos nuestro control de paginación contenida en $pagination_html 
                    //a nuestro array asociativo $pagination_arr, se guardará en el indice
                    //"buttons"
                    $pagination_arr["buttons"] = $pagination_html;
                    
                    //Finalmente se crea una cabecera http indicando que se va a enviar datos en la comunicación asincrona que inició Javascript
                    header('Content-Type: application/json; charset=utf-8');
                    /*con echo enviamos nuestro array asociativo $pagination_arr el cual JavaScript va a interpretar como un JSON gracias 
                     * a json_encode, luego, PHP cortará el flujo del código de este contexto con exit para que no envíe texto html por accidente*/
                    echo json_encode($pagination_arr);
                    exit;
                }
                
                
                
                /*JavaScript envia un JSON con un solo atributo llamado "clientIdFromBinnFilter" en el contexto del evento "change" del select 
                 * de selección de contacto en la vista de reportes de bitácoras (binnaclesFilter.php)*/
                if(!empty($input["enterIdFromBinnFilter"])){
                    
                    $enter_id = $input["enterIdFromBinnFilter"];
                    
                    $cont_obj = new Contactos();
                    $cont_obj->setEmpresaId($enter_id);
                    
                    $device_obj = new Equipos();
                    $device_obj->setEmpresa_id($enter_id);
                    
                    try{
                        $dces_arr = [
                            "enterContactsToBinnsFilter"=> $cont_obj->getContactsByEnterForSelect(),
                            "enterDcesToBinnsFilter"    => $device_obj->getDevicesForSelectByEnterpriseId()
                        ];
                        /*Las sesiones "clientRelatedDevices" se utiliza en la vista binnaclesFilter.php para que PHP pueda generar el contenido del select 
                         * de equipos despues de haber hecho la comunicación asincrona con JavaScript*/
                        $_SESSION["enterpriseRelatedContacts"] = $dces_arr["enterContactsToBinnsFilter"];
                        $_SESSION["enterpriseRelatedDevices"] = $dces_arr["enterDcesToBinnsFilter"];
                    } catch (Exception $ex) {
                        $_SESSION["dces_arrException"] = "Se generó un "
                                ."error interactuando con la base de datos "
                                ."en cuanto a la generación de las opciones de selección "
                                ."de dispositivos en el reporte de bitácoras, "
                                ."lo más probable es que se haya cortado la conexión a la base de datos";
                        header("Location: ".base_url."home/?homeController=user&homeAction=binnaclesReport");
                        exit;
                    }
                    
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($dces_arr);
                    exit;
                }
                
                /*JavaScript envia un JSON con un solo atributo llamado "enterpriseId" en el contexto del evento "change" del select 
                 * de selección de empresa en la vista de registro de bitácora (firstForm.php), dentro de este if se 
                 * crea la instancia de la clase Empresas para ingresar en su atributo id el valor contenido en la variable $enter_id 
                 * el cual contiene el valor del indice "enterpriseId" de $input esto con el fin de hacer una petición a la base de 
                 * datos para obtener la información de la empresa gracias al método getEnterpriseById de la clase Empresas, también 
                 * se crea una instancia de la clase Equipos y se le añade a su atributo empresa_id el valor contenido en $enter_id, 
                 * esto con el fin de hacer una petición a la base de datos para obtener todos los 
                 * registros de equipos vinculados a la empresa que se seleccionó gracias al método 
                 * getDevicesForSelectByEnterpriseId de la clase Equipos, también se crea una instancia de la clase Contactos y se le añade 
                 * a su propiedad privada $empresa_id el id contenido en la variable $enter_id con un setter, esto con el fin de conseguir 
                 * todos los registros de contactos vinculados con el id de la empresa en cuestión, todo lo que devuelve getEnterpriseById, 
                 * getDevicesForSelectByEnterpriseId y getClientsForSelectByEnterId se guardará en un array asociativo que PHP enviará a 
                 * JavaScript en forma de JSON*/
                if(!empty($input["enterpriseId"])){
                    
                    $enter_id = $input["enterpriseId"];
                    
                    $ent_obj = new Empresas();
                    $ent_obj->setId($enter_id);
                    
                    $contact_obj = new Contactos();
                    $contact_obj->setEmpresaId($enter_id);
                    
                    $device_obj = new Equipos();
                    $device_obj->setEmpresa_id($enter_id);
                    
                    try{
                        $enterprise_arr = [
                            "entInfo"           => $ent_obj->getEnterpriseById(),
                            "enterpriseContacts"=> $contact_obj->getContactsByEnterForSelect(),
                            "enterpriseDevices" => $device_obj->getDevicesForSelectByEnterpriseId()
                        ];
                    } catch (Exception $ex) {
                        $_SESSION["enterpriseArrayException"] = "Se generó un "
                                ."error interactuando con la base de datos "
                                ."en cuanto a la generación de datos automaticos de una empresa "
                                ."en el formulario de registro de bitácoras, "
                                ."lo más probable es que se haya cortado la conexión a la base de datos";
                        header("Location: ".base_url."home/");
                        exit;
                    }
                    
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($enterprise_arr);
                    exit;
                    
                }
                
                /*JavaScript envia un JSON con un solo atributo llamado "newContactEnterId" en el contexto del evento "change" del select 
                 * de selección de empresa en la vista de registro de contactos (newContactForm.php), dentro de este if se 
                 * crea la instancia de la clase Empresas para ingresar en su atributo id el valor contenido en la variable $enter_id 
                 * el cual contiene el valor del indice "newContactEnterId" de $input esto con el fin de hacer una petición a la base de 
                 * datos para obtener la información de la empresa gracias al método getEnterpriseById de la clase Empresas, todo lo que 
                 * devuelve getEnterpriseById se guardará en un array asociativo que PHP enviará a JavaScript en forma de JSON*/
                if(!empty($input["newContactEnterId"])){
                    
                    $enter_id = $input["newContactEnterId"];
                    
                    $ent_obj = new Empresas();
                    $ent_obj->setId($enter_id);
                    
                    try{
                        $enterprise_arr = [
                            "entInfoForContactForm" => $ent_obj->getEnterpriseById()
                        ];
                    } catch (Exception $ex) {
                        $_SESSION["enterpriseArrayException"] = "Se generó un "
                                ."error interactuando con la base de datos "
                                ."en cuanto a la generación de datos automaticos de una empresa "
                                ."en el formulario de registro de bitácoras, "
                                ."lo más probable es que se haya cortado la conexión a la base de datos";
                        header("Location: ".base_url."home/");
                        exit;
                    }
                    
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($enterprise_arr);
                    exit;
                    
                }
                
                /*JavaScript envia un JSON con un solo atributo llamado "deviceId" en el contexto del evento "change" del select 
                 * de selección de equipos en la vista de registro de bitácora (firstForm.php), dentro de este if se 
                 * crea la instancia de la clase Equipos y se le añade a su atributo id lo que contiene la variable $device_id 
                 * (el valor del indice "deviceId" de $input), esto con el fin de hacer una petición a la base de datos para 
                 * conseguir la información del equipo seleccionado gracias al método getDeviceById de la clase Equipos, todo lo 
                 * que devuelve getDeviceById se guardará en un array asociativo que PHP enviará a JavaScript en forma de JSON*/
                if(!empty($input["deviceId"])){
                    
                    $device_id = $input["deviceId"];
                    $device_obj = new Equipos();
                    $device_obj->setId($device_id);
                    
                    try{
                        $device_arr = $device_obj->getDeviceById();
                    } catch (Exception $ex) {
                        $_SESSION["deviceArrayException"] = "Se generó un "
                                ."error interactuando con la base de datos "
                                ."en cuanto a la generación de datos automaticos de dispositivos "
                                ."en el formulario de registro de bitácoras, "
                                ."lo más probable es que se haya cortado la conexión a la base de datos";
                        header("Location: ".base_url."home/");
                        exit;
                    }
                    
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($device_arr);
                    exit;
                    
                }
                
                /*JavaScript envia un JSON con un solo atributo llamado "typeId" en el contexto del evento "change" del select 
                 * de selección de tipo en la vista de registro de bitácora (firstForm.php), dentro de este if se 
                 * crea la instancia de la clase Tipos y se le añade a su atributo id lo que contiene la variable $type_id 
                 * (el valor del indice "typeId" de $input), esto con el fin de hacer una petición a la base de datos para 
                 * conseguir la información del tipo seleccionado gracias al método getTypeById de la clase Tipos, todo lo 
                 * que devuelve getTypeById se guardará en un array asociativo que PHP enviará a JavaScript en forma de JSON*/
                if(!empty($input["typeId"])){
                    
                    $type_id = $input["typeId"];
                    $type_obj = new Tipos();
                    $type_obj->setId($type_id);
                    
                    try{
                        $type_arr = $type_obj->getTypeById();
                    } catch (Exception $ex) {
                        $_SESSION["typeArrayException"] = "Se generó un "
                                ."error interactuando con la base de datos "
                                ."en cuanto a la generación de datos automaticos de tipos de dispositivos "
                                ."en el formulario de registro de bitácoras, "
                                ."lo más probable es que se haya cortado la conexión a la base de datos";
                        header("Location: ".base_url."home/");
                        exit;
                    }
                    
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($type_arr);
                    exit;
                    
                }
                
            }
        }
        
        /*el método unsetJsonDecodedSession lo utiliza las vistas menu.php y welcomeMessage.php para eliminar la sesión "jsondecoded" 
         * en dado caso que exista*/
        public static function unsetJsonDecodedSession(){
            if(!empty($_SESSION["jsondecoded"])){
                unset($_SESSION["jsondecoded"]);
            }
        }
        
        
        
        /*unsetBinnFilterSessions lo utiliza la vista welcomeMessage.php para eliminar sesiones que se 
         * crearon en el método binnaclesReport del controlador UserController solo si es que existen*/
        public static function unsetBinnFilterSessions(){
            
            if(!empty($_SESSION["filtValidated"])){
                unset($_SESSION["filtValidated"]);
            }
            
            if(!empty($_SESSION["Contacto_id"])){
                unset($_SESSION["Contacto_id"]);
            }
            
            if(!empty($_SESSION["isServiceOrDevice"])){
                unset($_SESSION["isServiceOrDevice"]);
            }
            
            if(!empty($_SESSION["Equipo_id"])){
                unset($_SESSION["Equipo_id"]);
            }
            
            if(!empty($_SESSION["Estatus"])){
                unset($_SESSION["Estatus"]);
            }
            
            if(!empty($_SESSION["StartedOrEnded"])){
                unset($_SESSION["StartedOrEnded"]);
            }
            
            if(!empty($_SESSION["LeftDay"])){
                unset($_SESSION["LeftDay"]);
            }
            
            if(!empty($_SESSION["RightDay"])){
                unset($_SESSION["RightDay"]);
            }
            
            if(!empty($_SESSION["SQL_Cliente_id"])){
                unset($_SESSION["SQL_Cliente_id"]);
            }
            
            if(!empty($_SESSION["SQL_isServiceOrDevice"])){
                unset($_SESSION["SQL_isServiceOrDevice"]);
            }
            
            if(!empty($_SESSION["SQL_Equipo_id"])){
                unset($_SESSION["SQL_Equipo_id"]);
            }
            
            if(!empty($_SESSION["SQL_Estatus"])){
                unset($_SESSION["SQL_Estatus"]);
            }
            
            if(!empty($_SESSION["SQL_betweenDays"])){
                unset($_SESSION["SQL_betweenDays"]);
            }
            
            if(!empty($_SESSION["Empresa_id"])){
                unset($_SESSION["Empresa_id"]);
            }
            
            if(!empty($_SESSION["Visible"])){
                unset($_SESSION["Visible"]);
            }
            
            if(!empty($_SESSION["enterpriseRelatedContacts"])){
                unset($_SESSION["enterpriseRelatedContacts"]);
            }
            
            if(!empty($_SESSION["enterpriseRelatedDevices"])){
                unset($_SESSION["enterpriseRelatedDevices"]);
            }
        }
        
        public static function unsetIdSessionsOfSearchForms(){
            
            if(!empty($_SESSION["userSign_userId"])){
                unset($_SESSION["userSign_userId"]);
            }
            
            if(!empty($_SESSION["userNewPwd_userId"])){
                unset($_SESSION["userNewPwd_userId"]);
            }
            
            if(!empty($_SESSION["enterpriseEdit_enterId"])){
                unset($_SESSION["enterpriseEdit_enterId"]);
            }
            
            if(!empty($_SESSION["devicesEdit_enterId"])){
                unset($_SESSION["devicesEdit_enterId"]);
            }
            
            if(!empty($_SESSION["devicesReport_enterId"])){
                unset($_SESSION["devicesReport_enterId"]);
            }
        }

        //---------------------home user index methods & procedures-------------
        
        //----------------------finishing index procedures----------------------
        /*Esta sección corresponde a métodos que solo se utilizan en el index de la carpeta finishing y el 
         * controlador principal de ese index (FormController)*/
        
        /*setbinnacleSelection se utiliza solo en el archivo finishing/index.php, en el se encuantra bloques if 
         * evaluando datos de formularios que necesitan el pad de firmas para poder completar la acción*/
        public static function setDataSelectionForSigns(){
            
            /*Este if corresponde a la recepción de datos del formulario de consentimiento de actividades (consentInfo.php)*/
            if (sizeof($_POST) > 0 && !empty($_POST["binnId"])) {
                /*si el campo "binnId" no es un valor numerico o si el campo "userId" no coincide con 
                 * el indice "Id" de la sesión del usuario que está usando la aplicación web, entonces hubo una 
                 * alteración en la herramienta de desarrolladores en el navegador web, si se hace eso, 
                 * simplemente se redirige al usuario al home de la aplicación cortando el flujo del código con exit*/
                if(preg_match('/[0-9]+/', $_POST["binnId"]) &&
                   $_POST["userId"] === $_SESSION["identity"]["Id"]){
                    /*si no hubo alteración alguna entonces lo primero que se hace es gestionar los campos del post, 
                     * en este caso, se usa la función propia de PHP preg_replace, si los datos contienen caracteres 
                     * con acentos o diéresis, o la letra ñ, entonces esos caracteres se remplazan por un cero "0", 
                     * lo que devuelve cada preg_match se guardarán en sus respectivas variables*/

                    $cliente_nombre = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["clientName"]);
                    $commercial_nombre = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["clientEntName"]);
                    $usuario_nombre = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["userName"]);
                    $usuario_ape = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["userSurname"]);
                    
                    /*se crea la sesión "binnacleSelection", dentro tiene un array asociativo donde se coloca 
                     * información clave (pero no critica) de los usuarios (técnicos) y los clientes (contactos), 
                     * algunos datos se extraen diractamente del post en el caso de los Ids ya que son valores 
                     * númericos, sin embargo indices como, clientName, altClientName, userName y userSurname 
                     * necesitan de sus respectivas variables anteriormente inicializadas. Esta sesión se utiliza 
                     * en el archivo html views\userLayouts\finishingLayout\head.php ahí se crea el objeto window.serverData con 
                     * JavaScript sin embargo, en su declaración se utiliza una etiqueta PHP para convertir 
                     * el contenido de la sesión "binnacleSelection" en un JSON con la función propia de PHP json_encode, ese 
                     * objeto, JavaScript lo detecta en finishing.js despues de cargar el documento html, cada propiedad 
                     * de serverData son los mismos indices que se declararon en el contenido de la sesión "binnacleSelection", 
                     * finishing.js utiliza esas propiedades para guardarlas en sus respectivas constantes, posteriormente 
                     * dentro de la lógica del pad de firmas estas constantes se utilizan para crear el nombre de la imagen 
                     * de la firma, este nombre de la imagen no debe de tener simbolos raros como los acentos o diéresis, o 
                     * la letra ñ ya que esas imagenes de firmas se utilizarán con la etiqueta <img> en el html de 
                     * binnacleInfoCanvas.php, en la etiqueta <img> se necesita poner la propiedad "src=" que indica la ruta 
                     * de la imagen, por eso es que el nombre del archivo no debe de tener simbolos raros, el navegador 
                     * puede presentar problemas en cargar la imagen de las firmas*/
                    $_SESSION["dataSelectionForSigns"] = array(
                        "binnId"        => $_POST["binnId"],
                        "clientName"    => $cliente_nombre,
                        "altClientName" => $commercial_nombre,
                        "userId"        => $_POST["userId"],
                        "userName"      => $usuario_nombre,
                        "userSurname"   => $usuario_ape
                    );

                    
                }else{
                    header("Location: ".base_url."home/");
                    exit;
                }
            }
            
            /*Este if corresponde a la recepción de datos del formulario de actualización de firma de los técnicos (usuarios) */
            if(sizeof($_POST) > 0 && !empty($_POST["oldTechSign"])){
                
                if(preg_match('/[0-9]+/', $_POST["userId"])){
                    
                        $usuario_nombre = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["userName"]);
                        $usuario_ape = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["userSurname"]);

                        $_SESSION["dataSelectionForSigns"] = array(
                            "userId"        => $_POST["userId"],
                            "userName"      => $usuario_nombre,
                            "userSurname"   => $usuario_ape,
                            "oldTechSign"   => $_POST["oldTechSign"]
                        );
                }else{
                    header("Location: ".base_url."home/");
                    exit;
                }
            }
        }
        
        /*saveSignaturesFiles solo lo utiliza el archivo finishing/index.php, su función es recepcionar los datos de 
         * formulario que envía JavaScript en una comunicación asicnrona con fetch, PHP va a interpretar esos datos como 
         * archivos ($_FILES), en este caso archivo de imagen*/
        public static function saveSignaturesFiles(){
            /*Este método tiene tres bloques if, se evalua si la variable superglobal $_FILES esta definida como "newTechSign" 
             * o "techSign"(nombre de campo de formulario creado en JavaScript), el otro if evalua si $_FILES está definida como "cliSign"*/
            
            if(isset($_FILES["newTechSign"])){
                
                /*Se borra la imagen antigua de la firma del técnico*/
                if(!unlink("uploads/firmas/".$_SESSION["dataSelectionForSigns"]["oldTechSign"])){
                   $_SESSION["unlinkTechSignEx"] = "La supuesta firma del técnico no se encontró en la aplicación web";
                   /*Se utiliza el metodo estático unsetFormSessions de la clase Utils para eliminar las sesiones creadas en el método saveSignaturesFiles 
                     * y setbinnacleSelection (de esta clase Utils) respectivamente*/
                    Utils::unsetFormSessions();
                    /*PHP redirigirá al usuario a la vista de menú de usuarios (menu.php) o al mensaje de bienvenida del administrador (welcomeMessage.php), 
                     * en el html de estas vista se utilizará la sesión "unlinkTechSignEx", también php cortará el flujo del código de este contexto con exit*/
                    header("Location: " . base_url . "home/");
                    exit;
                }
                
                //se guarda el contenido del indice "newTechSign" (todos los datos del archivo) en $tech_sign_file
                $tech_sign_file = $_FILES["newTechSign"];
                //se accede al indice "name" (string del nombre del archivo) para guardar su valor en la variable $technician_name
                $technician_name = $tech_sign_file["name"];
                /*se evalúa si es que no existe la carpeta con la ruta "uploads/firmas" en la raiz, en este caso, la raíz es 
                la carpeta finishing del proyecto*/
                if(!is_dir("uploads/firmas")){
                    /*si no existe la carpeta entonces se crea en la raíz con la función propia de PHP mkdir con todos los permisos (0777) y 
                     * con opción de modificación (true)*/
                    mkdir("uploads/firmas", 0777, true);
                }
                /*al existir la carpeta "uploads/firmas" se utiliza la función propia de PHP move_uploaded_file para guardar la 
                 * imagen, esta función necesita por lo menos en este caso dos argumentos, el primer argumento es colocar la ruta del archivo temporal
                 * del sistema de archivos de PHP, esto es accediendo al indice "tmp_name" de nuestro array $tech_sign_file, el segundo argumento es la 
                 * ruta de nuestra carpeta de firmas concatenando el string del nombre del archivo contenido en nuestra variable $technician_name*/
                move_uploaded_file($tech_sign_file["tmp_name"], 
                            "uploads/firmas/".$technician_name);
                //finalmente guardamos el string del nombre de la imagen contenido en $technician_name en una sesión, en este caso la sesión "techSignature"
                $_SESSION["techSignature"] = $technician_name;
            }
            
            if(isset($_FILES["techSign"])){
                //se guarda el contenido del indice "techSign" (todos los datos del archivo) en $tech_sign_file
                $tech_sign_file = $_FILES["techSign"];
                //se accede al indice "name" (string del nombre del archivo) para guardar su valor en la variable $technician_name
                $technician_name = $tech_sign_file["name"];
                /*se evalúa si es que no existe la carpeta con la ruta "uploads/firmas" en la raiz, en este caso, la raíz es 
                la carpeta finishing del proyecto*/
                if(!is_dir("uploads/firmas")){
                    /*si no existe la carpeta entonces se crea en la raíz con la función propia de PHP mkdir con todos los permisos (0777) y 
                     * con opción de modificación (true)*/
                    mkdir("uploads/firmas", 0777, true);
                }
                /*al existir la carpeta "uploads/firmas" se utiliza la función propia de PHP move_uploaded_file para guardar la 
                 * imagen, esta función necesita por lo menos en este caso dos argumentos, el primer argumento es colocar la ruta del archivo temporal
                 * del sistema de archivos de PHP, esto es accediendo al indice "tmp_name" de nuestro array $tech_sign_file, el segundo argumento es la 
                 * ruta de nuestra carpeta de firmas concatenando el string del nombre del archivo contenido en nuestra variable $technician_name*/
                move_uploaded_file($tech_sign_file["tmp_name"], 
                            "uploads/firmas/".$technician_name);
                //finalmente guardamos el string del nombre de la imagen contenido en $technician_name en una sesión, en este caso la sesión "techSignature"
                $_SESSION["techSignature"] = $technician_name;
            }
            
            if(isset($_FILES["cliSign"])){
                $cli_sign_file = $_FILES["cliSign"];
                $client_name = $cli_sign_file["name"];
                if(!is_dir("uploads/firmas")){
                    mkdir("uploads/firmas", 0777, true);
                }
                move_uploaded_file($cli_sign_file["tmp_name"], 
                            "uploads/firmas/".$client_name);
                $_SESSION["clientSignature"] = $client_name;
                
                if((!empty($_SERVER['HTTP_X_REQUESTED_WITH'])                           && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')){                
                    echo "Firma del cliente guardado con éxito";
                    exit;
                }
            }
        }
        
        /*updateUserWithSignature tiene que usarse exactamente despues del método estático saveSignaturesFiles dentro del index de la carpeta finishing, 
         * updateUserWithSignature verifica si existe la sesión "techSignature", si es que existe entonces se utiliza el valor de esta sesión para hacer 
         * una petición a la base de datos para actualizar el campo "Firma" del usuario en cuestión, a parte de actualizar la sesión "identity" con la 
         * nueva información*/
        public static function updateUserWithSignature(){
            if(!empty($_SESSION["techSignature"])){
 
                $usuario_obj = new Usuarios();
                /*Id y Firma son propiedades privadas de la instancia $usuario_obj de la clase Usuarios, se usan metodos setter para poder añadir 
                 * un valor a esas propiedades, dentro del contexto de la instancia, estas propiedades configuradas las utilizan metodos publicos que 
                 * contienen sentencias sql (como insertSignature) para concatenar los valores de estas propiedades en las sentencias*/
                (!empty($_SESSION["userSign_userId"])) ? $usuario_obj->setId($_SESSION["userSign_userId"]) : $usuario_obj->setId($_SESSION["identity"]["Id"]);
                $usuario_obj->setFirma($_SESSION["techSignature"]);
                try {
                    /*el método insertSignature efectua una conexión a SQL server, por lo tanto, se usa un try-catch para intentar la conexión, tambien 
                     * ese método contiene una sentencia sql para actualizar el campo Firma de acuerdo al Id del usuario*/
                    $usuario_obj->insertSignature(); 
                } catch (Exception $ex) {
                    /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "techSignInsertException" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                    $_SESSION["techSignInsertException"] = "No se logró guardar "
                            ."la firma del técnico en la base de datos, se cortó "
                            ."la conexión a la base de datos";
                    /*Se borra la imagen que se generó de la firma del técnico*/
                    if(!unlink("uploads/firmas/".$_SESSION["techSignature"])){
                            $_SESSION["unlinkTechSignEx"] = "La supuesta firma del técnico no se encontró en la aplicación web";
                    }
                    /*Se utiliza el metodo estático unsetFormSessions de la clase Utils para eliminar las sesiones creadas en el método saveSignaturesFiles 
                     * y setbinnacleSelection (de esta clase Utils) respectivamente*/
                    Utils::unsetFormSessions();
                    /*PHP redirigirá al usuario a la vista de menú de usuarios (menu.php) o al mensaje de bienvenida del administrador (welcomeMessage.php), 
                     * en el html de estas vista se utilizará la sesión "techSignInsertException", también php cortará el flujo del código de este contexto con exit*/
                    header("Location: " . base_url . "home/");
                    exit;
                }
                
                    try{

                        if(!empty($_SESSION["userSign_userId"])){
                            if($_SESSION["userSign_userId"] === $_SESSION["identity"]["Id"]) $_SESSION["identity"] = $usuario_obj->getUser();
                        }else{
                            /*el método getUser efectua una conexión a SQL server, por lo tanto, se usa un try-catch para intentar la conexión, tambien 
                            * ese método contiene una sentencia sql para obtener los campos de un registro de la tabla Usuarios de acuerdo al Id del usuario que se pasó 
                            * en el método setter setId(), lo que devuelve ese método se guarda en la sesión del usuario "identity"*/
                            $_SESSION["identity"] = $usuario_obj->getUser();
                        }
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                                 * llamado "identitySessionUpdateEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                                 * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["identitySessionUpdateEx"] = "No se logró actualizar la "
                                ."sesión de la información del usuario, posible corte de "
                                ."conexión a la base de datos, se recomienda no generar "
                                ."firma del usuario en una bitácora una vez establecido "
                                ."conexión a la base de datos, cierre sesión y vuelva entrar "
                                ."para tener una sesión de datos de usuario correcta";
                        /*Se utiliza el metodo estático unsetFormSessions de la clase Utils para eliminar las sesiones creadas en el método saveSignaturesFiles 
                         * y setbinnacleSelection (de esta clase Utils) respectivamente*/
                        Utils::unsetFormSessions();
                        /*PHP redirigirá al usuario a la vista de menú de usuarios (menu.php) o al mensaje de bienvenida del administrador (welcomeMessage.php), 
                         * en el html de estas vista se utilizará la sesión "identitySessionUpdateEx", también php cortará el flujo del código de este contexto con exit*/
                        header("Location: " . base_url . "home/");
                        exit;
                    }
                /*Finalmente se va a concluir la comunicación asincrona con JavaScript, este if identifica si la sesión "clientSignature" está vacía y si existe 
                 * la cabecera http X-Requested-With y si su contenido es xmlhttprequest, esto es una cabecera definida en la función fetch de JavaScript al 
                 * momento de inicializar la comunicación asicnrona, en este caso, JavaScript envió un dato de formulario que PHP interpretó como un archivo $_FILES 
                 * (la imagen de una firma), si este if da true entonces se dará por concluida la comunicación enviando un string, un texto que fetch de JavaScript 
                 * espera como promesa*/
                if((!empty($_SERVER['HTTP_X_REQUESTED_WITH'])                           && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') &&
                    empty($_SESSION["clientSignature"])){
                    echo "Firma de ".$_SESSION["dataSelectionForSigns"]["userName"]." se ha guardado con éxito";
                    exit;
                }
            }
        }
        /*isAjaxWithClientSign tiene que usarse despues del método estático saveSignaturesFiles dentro del index de la carpeta finishing, 
         * isAjaxWithClientSign verifica si la sesión "clientSignature" no está vacía y si existe la cabecera http X-Requested-With y si su 
         * contenido es xmlhttprequest, esto es una cabecera definida en la función fetch de JavaScript al momento de inicializar la comunicación asicnrona, 
         * en este caso, JavaScript envió un dato de formulario que PHP interpretó como un archivo $_FILES (la imagen de una firma), si este if da true 
         * entonces se dará por concluida la comunicación enviando un string, un texto que fetch de JavaScript espera como promesa*/
        /*
        public static function isAjaxWithClientSign(){
            if((!empty($_SERVER['HTTP_X_REQUESTED_WITH'])                           && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') &&
                !empty($_SESSION["clientSignature"])){                
                echo "Firma del cliente guardado con éxito";
                exit;
            }
        }
         * 
         */
        
        /*el metodo estático unsetFormSessions se utiliza en el método insertsigns del controlador FormController y el método estatico 
         * updateUserWithSignature de esta clase Utils para eliminar las sesiones creadas en el método saveSignaturesFiles 
         * y setbinnacleSelection (de esta clase Utils) respectivamente si es que existen*/
        public static function unsetFormSessions(){
            
            if(!empty($_SESSION["techSignature"])){
                unset($_SESSION["techSignature"]);
            }
            
            if(!empty($_SESSION["clientSignature"])){
                unset($_SESSION["clientSignature"]);
            }
            
            if(!empty($_SESSION["dataSelectionForSigns"])){
                unset($_SESSION["dataSelectionForSigns"]);
            }
            
        }
        
        //----------------------finishing index procedures-----------------------
    }