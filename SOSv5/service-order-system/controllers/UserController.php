<?php
    /*El controlador UserController es el controlador principal del archivo index de la carpeta home, se gestionan las vistas 
     * del lado del usuario y del administrador, a parte de gestionar datos pasados por un formulario ($_POST), esta clase 
     * solo esta conformada por métodos publicos*/
    class UserController{
        
        //--------------------------------------MÉTODOS DE VISTAS----------------------------------------------------
        
        /*El método index importa la vista por defecto del archivo index de home, evalúa si existe la sesión usuario "identity", en 
         * dado caso que exista se evalúa si el indice "Privilegio" de la sesión del usuario sea igual a "user", en ese caso se importará 
         * la vista de menú de usuarios (técnicos), en cambio, si no está vacía la sesión "isAdmin", entonces se importará la vista de 
         * mensaje de bienvenida del administrador; si está vacía la sesión del usuario "identity", entonces la vista por defecto será 
         * la del login; en cuanto a las vistas menu.php y welcomeMessage.php se guarda el tiempo actual con time() en la sesión "LAST_ACTIVITY" 
         * (la sesión del usuario tiene un tiempo de caducidad de 30 minutos)*/
        public function index(){
            if(!empty($_SESSION["identity"])){
                if($_SESSION["identity"]["Privilegio"] === "user"){
                    $_SESSION['LAST_ACTIVITY'] = time();
                    require_once '../views/userLayouts/menu.php';
                }else if(!empty($_SESSION["isAdmin"])){
                    $_SESSION['LAST_ACTIVITY'] = time();
                    require_once '../views/adminLayouts/welcomeMessage.php';
                }
            }else if(empty($_SESSION["identity"])){
                require_once '../views/login.php';
            }
        }
        
        /*newbinnacle importa la vista del formulario de registro de bitácoras, evalúa si no está vacía la sesión del usuario "identity", 
         * si no se cumple la condición, PHP redirigirá al usuario a la vista por defecto de home*/
        public function newbinnacle(){
            if(!empty($_SESSION["identity"])){
                    /*Si la evaluación da true, entonces lo primero que se hace es guardar el tiempo actual en la 
                     * sesión "LAST_ACTIVITY" (la sesión del usuario tiene un tiempo de caducidad de 30 minutos)*/
                    $_SESSION['LAST_ACTIVITY'] = time();
                    
                    /*se crea una instancia de la clase Empresas ya que en el registro de bitácoras hay un select de empresas*/
                    
                    $ent_obj = new Empresas();
                    
                    
                    try{
                        /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql 
                         * server, por eso se hace un try-catch para intentar la conexión, aqui se están obteniendo los registros de 
                         * empresas en forma de arrays asociativos, el array $enterprises posteriormente se usará en la vista firstForm.php 
                         * para dar forma a su respectivo select*/
                        $enterprises = $ent_obj->getEnterprisesForSelect();
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "getInfoForSelectsException" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["getInfoForSelectsException"] = "Se generó un "
                                ."error interactuando con la base de datos "
                                ."en cuanto a la obtención de datos para la"
                                ." caja de selección de empresas de la bitácora, lo más "
                                ."probable es que se haya cortado la conexión "
                                ."a la base de datos";
                        $enterprises = array();
                    }
                    /*Si la petición de obtención de datos de la entidad Empresas de la base de datos fue un exito, entonces se utilizará el array 
                     * $enterprises en la vista firstForm.php para generar las opciones de su respectivo select, si PDO arrojó una 
                     * excepción, entonces se usuará la sesión "getInfoForSelectsException" en la vista*/
                    require_once '../views/userLayouts/firstForm.php';
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*este método importa la vista del formulario de registro de contacto, evalúa si la sesión del usuario no está vacía, si no cumple la condición 
         * PHP redirigirá al usuario a la vista por defecto del home*/
        public function newcontact(){
            if(!empty($_SESSION["identity"])){
                $_SESSION['LAST_ACTIVITY'] = time();
                /*Esta vista tiene una caja de selección de empresas, por lo que es necesario crear una instancia de la clase Empresas para utilizar 
                 * su método de petición getEnterprisesForSelect*/
                $ent_obj = new Empresas();
                    try{
                        /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql 
                         * server, por eso se hace un try-catch para intentar la conexión, aqui se están obteniendo los registros de 
                         * empresas en forma de arrays asociativos, el array $enterprises posteriormente se usará en la vista newContactForm.php 
                         * para dar forma a su respectivo select*/
                        $enterprises = $ent_obj->getEnterprisesForSelect();
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "getInfoForSelectException" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["getInfoForSelectException"] = "Se generó un "
                                ."error interactuando con la base de datos "
                                ."en cuanto a la obtención de datos para la"
                                ." caja de selección de empresas del formulario, lo más "
                                ."probable es que se haya cortado la conexión "
                                ."a la base de datos";
                        $enterprises = array();
                    }
                require_once '../views/userLayouts/newContactForm.php';
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        /*El método newdevicetype importa la vista del formulario de registro de un tipo de equipo (newContactForm.php), evalúa si la sesión del usuario no está vacía, 
         * si no se cumple la condición entonces PHP redirigirá al usuario a la vista por defecto del home*/
        public function newdevicetype(){
            if(!empty($_SESSION["identity"])){
                $_SESSION['LAST_ACTIVITY'] = time();
                
                require_once '../views/userLayouts/newTypeForm.php';
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*El método newdevice importa la vista del formulario de registro de un equipo (newContactForm.php), evalúa si la sesión del usuario no está vacía, 
         * si no se cumple la condición entonces PHP redirigirá al usuario a la vista por defecto del home*/
        public function newdevice(){
            if(!empty($_SESSION["identity"])){
                $_SESSION['LAST_ACTIVITY'] = time();
                $enter_obj = new Empresas();
                $type_obj = new Tipos();
                try{
                    $enters = $enter_obj->getEnterprisesForSelect();
                    $types = $type_obj->getTypeForSelect();
                } catch (Exception $ex) {
                    $_SESSION["dataForSelectDceFormEx"] = "Se generó un "
                                ."error interactuando con la base de datos "
                                ."en cuanto a la obtención de datos para la"
                                ." caja de selección de tipos del formulario, lo más "
                                ."probable es que se haya cortado la conexión "
                                ."a la base de datos";
                    $types = array();
                }
                require_once '../views/userLayouts/newDeviceForm.php';
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*El método followuplist importa la vista del seguimiento de bitácoras (followup.php), primero evalúa si la sesión del usuario no está vacía, 
         * si no se cumple la condición entonces PHP redirigirá al usuario a la vista por defecto del home*/
        public function followuplist(){
            
            if(!empty($_SESSION["identity"])){
                    
                    /*Si la evaluación da true, entonces lo primero que se hace es guardar el tiempo actual en la 
                     * sesión "LAST_ACTIVITY" (la sesión del usuario tiene un tiempo de caducidad de 30 minutos)*/
                    $_SESSION['LAST_ACTIVITY'] = time();
                    
                    /*A continuación se prepara los primeros pasos para utilizar la dependencia zabraPagination, primero necesitamos el número de 
                     * registros que necesitamos de una entidad de la base de datos, en este caso de la tabla Bitacoras, creamos una instancia de la 
                     * clase Bitacoras y le añadimos a su atributo privado usuario_id el id del usuario contenido en la sesión "identity" con un setter*/
                    $binnacle_obj = new Bitacoras();
                    $binnacle_obj->setUsuario_id($_SESSION["identity"]["Id"]);
                    try {
                        /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                         * try-catch para intentar la conexión; convertimos el string numerico que devuelve la función getBinnCountByUserAndStatus (devuelve el 
                         * numero de registros de bitácoras vinculadas al id del usuario y con estatus "en proceso" o "falta confirmar") en un valor entero  
                         * con la función propia de PHP intval y lo contenemos en la variable $num_rows*/
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
                        /*PHP redirigirá al usuario a la vista por defecto de home, en el html de esa vista 
                             * se utilizará la sesión "followUpNumRowsEx", también php cortará el flujo del código de este contexto con exit*/
                        header("Location: ".base_url."home/");
                        exit;
                    }

                    /*el método getBinnCountByUserAndStatus puede devolver un cero si no encuantra registros con la condición dada en la tabla Bitacoras, 
                     * este if evalúa si el valor contenido en $num_rows es mayor a cero, si se cumple la condición, se hará los pasos a seguir para calcular 
                     * la paginación con la dependencia Zabra_pagination*/
                    if($num_rows > 0){
                        
                        /*La sesión "jsondecoded" se crea después de haber seleccionado un número en el elemento html select para seleccionar el numero de 
                         * elementos en pantalla (en la vista followup.php), este ternario evalua si la sesión no está vacía, si cumple la condición, entonces 
                         * se utiliza el valor de esta sesión para inicializar la variable $page_elem, si no cumple la condición, entonces la variable 
                         * $page_elem se inicializa con el valor númerico por defecto: 1*/
                        (!empty($_SESSION["jsondecoded"])) ?
                            $page_elem = $_SESSION["jsondecoded"] :
                            $page_elem = 1;
                        
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
                        try{
                            /*Usamos el método execute de PDO para enviar la sentencia sql para que SQL Server lo procese, es una petición tal cual, por lo que 
                             * se hace un try-cath*/
                            $stmt_binns->execute();
                        } catch (Exception $ex) {
                            /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "paginationArrException" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                            $_SESSION["paginationArrException"] = "Se generó un "
                                ."error interactuando con la base de datos "
                                ."en cuanto a la generación de paginación";
                            /*PHP redirigirá al usuario a la vista por defecto de home, en el html de esa vista 
                             * se utilizará la sesión "paginationArrException", también php cortará el flujo del código de este contexto con exit*/
                            header("Location: ".base_url."home/");
                            exit;
                        }
                        
                    }
                    /*Si $num_rows es mayor a cero, entonces se utiliza el objeto PDO $stmt_binns en el bucle for de la vista followup.php, accediendo a 
                     * cada registro con el metodo fetch, si $num_rows es igual a cero, entonces se mostrará un mensaje notificando al usuario que no 
                     * tiene bitácoras pendientes*/
                    require_once '../views/userLayouts/followup.php';
                    
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
            
        }
        
        /*El método editSign importa la vista de edición de firma (editSign.php), primero evalúa si la sesión del usuario no está vacía, 
         * si no se cumple la condición entonces PHP redirigirá al usuario a la vista por defecto del home*/
        public function editSign(){
            if(!empty($_SESSION["identity"])){
                
                if(!empty($_SESSION["isAdmin"])){
                        /*la vista editSign.php tiene un formulario de busqueda si hay un administrador usando la aplicación, dentro de ese formulario existe un elemento select en donde se selecciona el id de un 
                     * usuario, por lo tanto, se necesita generar las opciónes de ese select, lo primero es crear una instancia de la clase Usuarios*/
                    $usr_obj = new Usuarios();
                    try{
                        /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                         * try-catch para intentar la conexión; getUsers devuelve en forma de array asociativo los registros de la tabla Usuarios con los 
                         * campos necesarios para crear las opciones del select de usuarios, $users se inicializa con ese array*/
                        $users = $usr_obj->getUsers();
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                                 * llamado "gettingUsersException" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                                 * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["gettingUsersException"] = "No se pudo conseguir "
                                ."el listado de usuarios para la busqueda, posible "
                                ."corte de conexión a la base de datos";
                        $users = array();
                    }
                }
                
                if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){ 
                    
                    $_SESSION["userSign_userId"] = (!empty($_POST["usuarios"]) && (!preg_match('/[A-Za-z]+/', $_POST["usuarios"]) ||
                        !preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $_POST["usuarios"]))) ? $_POST["usuarios"] : false;
                
                    $get_params = http_build_query($_POST);
                    header("Location: ". base_url."home/?homeController=user&homeAction=editSign&".$get_params);
                    exit;
                }

                if(!empty($_SESSION["userSign_userId"])){
                    
                    $usr_obj->setId($_SESSION["userSign_userId"]);
                    try {
                        $user_info = $usr_obj->getUser();
                        
                        if(!empty($user_info["Firma"])){
                            if(!file_exists("../finishing/uploads/firmas/".$user_info["Firma"])){
                                $usr_obj->insertSignature();
                                $user_info = $usr_obj->getUser();
                            }
                        }
                    } catch (Exception $ex) {
                        $_SESSION["userInfoForEditSignEx"] = "No se logró obtener la información del usuario, probable corte de conexión a la base de datos";
                        $user_info = array();
                    }
                }
                
                if($_SESSION["identity"]["Privilegio"] === "user"){
                    $usr_obj = new Usuarios();
                    $usr_obj->setId($_SESSION["identity"]["Id"]);
                    try {
                        if(!empty($_SESSION["identity"]["Firma"])){
                            if(!file_exists("../finishing/uploads/firmas/".$_SESSION["identity"]["Firma"])){
                                $usr_obj->insertSignature();
                                $_SESSION["identity"]["Firma"] = null;
                            }
                        }
                    } catch (Exception $ex) {
                        $_SESSION["deleteInexistentUserSignEx"] = "No se logró borrar la información del campo 'Firma' del usuario, probable corte "
                                . "de conexión a la base de datos";
                    }
                }
                
                require_once '../views/userLayouts/editSign.php';
                
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
            
        }
        
        /*el método createUser importa la vista del formulario de creación de usuario, primero evalúa si no está vacía la sesión del usuario "identity" y si la 
         * sesión "isAdmin" no está vacía, si no se cumple la condición entonces PHP redirigirá al usuario a la vista por defecto del home*/
        public function createUser(){
            
            if(!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])){
                /*Si la evaluación da true, entonces lo primero que se hace es guardar el tiempo actual en la 
                     * sesión "LAST_ACTIVITY" (la sesión del usuario tiene un tiempo de caducidad de 30 minutos)*/
                $_SESSION['LAST_ACTIVITY'] = time();
                require_once '../views/adminLayouts/userInsertForm.php';
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*el método userNewPassword importa la vista del formulario de cambio de contraseña de un usuario, primero evalúa si no está vacía la sesión del usuario 
         * "identity" y si la sesión "isAdmin" no está vacía, si no se cumple la condición entonces PHP redirigirá al usuario a la vista por defecto del home*/
        public function userNewPassword(){
            if(!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])){
                /*Si la evaluación da true, entonces lo primero que se hace es guardar el tiempo actual en la 
                     * sesión "LAST_ACTIVITY" (la sesión del usuario tiene un tiempo de caducidad de 30 minutos)*/
                $_SESSION['LAST_ACTIVITY'] = time();
                
                /*la vista userNewPwd.php tiene un formulario de busqueda, dentro de ese formulario existe un elemento select en donde se selecciona el id de un 
                 * usuario, por lo tanto, se necesita generar las opciónes de ese select, lo primero es crear una instancia de la clase Usuarios*/
                $user_obj = new Usuarios();
                try{
                    /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                     * try-catch para intentar la conexión; getUsers devuelve en forma de array asociativo los registros de la tabla Usuarios con los 
                     * campos necesarios para crear las opciones del select de usuarios, $users se inicializa con ese array*/
                    $users = $user_obj->getUsers();
                } catch (Exception $ex) {
                    /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "gettingUsersException" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                    $_SESSION["gettingUsersException"] = "No se pudo conseguir "
                            ."el listado de usuarios para la busqueda, posible "
                            ."corte de conexión a la base de datos";
                    $users = array();
                }
                
                if(sizeof($_POST) > 0){
                    $_SESSION["userNewPwd_userId"] = (!empty($_POST["usuarios"]) && (!preg_match('/[A-Za-z]+/', $_POST["usuarios"]) ||
                        !preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $_POST["usuarios"]))) ? $_POST["usuarios"] : false;
                    
                    $get_params = http_build_query($_POST);
                    header("Location: ". base_url."home/?homeController=user&homeAction=userNewPassword&".$get_params);
                    exit;
                }
                
                /*Este if evalúa los campos del formulario ($_POST) de busqueda, la condición da true si $_POST no es un array vacío*/
                if(!empty($_SESSION["userNewPwd_userId"])){
                    
                    /*Si se envíó datos del formulario de busqueda entonces se crean las instancias de las clases Usuarios y Bitacoras, en el caso del 
                     * objeto de Usuarios se le añade al atributo privado id el valor del campo "usuarios" con un setter, en el caso del objeto de Bitacoras, se le añade 
                     * a su atributo privado usuario_id el valor del campo "usuarios" con un setter*/
                    $user_obj->setId($_SESSION["userNewPwd_userId"]);
                    try{
                        /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                         * try-catch para intentar la conexión; getUser devuelve en forma de array asociativo los campos de un registro de la tabla Usuarios dependiendo 
                         * del id del usuario que se pasó en el setter del objeto Usuarios, se inicializa la variable $user_info con lo que devuelve este método*/
                        $user_info = $user_obj->getUser();
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "userInfoException" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["userInfoException"] = "No se logró conseguir "
                                ."la información del usuario, posible corte "
                                ."de conexión a la base de datos";
                        
                    }
                    
                }
                /*Si se inicializarón las sesiones de excepciones entonces se utilizan en la vista userNewPwd.php para mostrar el mensaje de 
                 * estas sesiones al usuario, por el contrario, si se inicializaron arrays con información de la base de datos entonces se utilizan en la vista 
                 * para determinar la aparición de elementos html*/
                require_once '../views/adminLayouts/userNewPwd.php';
            }else{
               header("Location: ".base_url."home/");
               exit; 
            }
        }
        
        /*El método editEnterprise importa la vista del formulario de edición de empresa y los clientes (contactos) vinculados a esta enterpriseForms.php, 
         * lo primero que hace es evaluar si la sesión "isAdmin" no esta vacía y si la sesión, si no se cumple la condición, PHP redirigirá al usuario a la 
         * vista por defecto del home*/
        public function editEnterprise(){
            if(!empty($_SESSION["isAdmin"])){
                /*Si la evaluación da true, entonces lo primero que se hace es guardar el tiempo actual en la 
                     * sesión "LAST_ACTIVITY" (la sesión del usuario tiene un tiempo de caducidad de 30 minutos)*/
                $_SESSION['LAST_ACTIVITY'] = time();
                
                /*Se obtienen registros para el select de empresas*/
                $ent_obj = new Empresas();
                try {
                    $enterprises = $ent_obj->getEnterprisesForEditSelect();
                } catch (Exception $ex) {
                    $_SESSION["selectDataEnterEditEx"] = "No se logró conseguir los datos para dar forma a la caja de "
                            . "selección de empresas en el formulario de busqueda, posible corte de conexión a la base "
                            . "de datos";
                    $enterprises = array();
                }
                /*Si se envian datos del formulario de busqueda de la vista inicializará la sesión "enterprise_id"*/
                if(sizeof($_POST) > 0){
                    $_SESSION["enterpriseEdit_enterId"] = (!empty($_POST["empresas"]) && (!preg_match('/[A-Za-z]+/', $_POST["empresas"]) ||
                        !preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $_POST["empresas"]))) ? $_POST["empresas"] : false;
                    
                    $get_params = http_build_query($_POST);
                    header("Location: ". base_url."home/?homeController=user&homeAction=editEnterprise&".$get_params);
                    exit;
                }
                
                if(!empty($_SESSION["enterpriseEdit_enterId"])){
                    /*Se necesita acceder a los contactos vinculados a la empresa, primero hay que hacer una instancia de la clase Contactos 
                     * y añadir en su atributo privado empresa_id el id de la empresa contenida en la sesión "enterInfo" con un setter*/
                    $contact_obj = new Contactos();
                    $ent_obj->setId($_SESSION["enterpriseEdit_enterId"]);
                    $contact_obj->setEmpresaId($_SESSION["enterpriseEdit_enterId"]);
                    try{
                        /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                         * try-catch para intentar la conexión; convertimos el string numerico que devuelve la función getContactCountByEnterprise (devuelve el 
                         * numero de registros de la tabla Contactos vinculados al id de la empresa) en un valor entero con la función propia de PHP intval y lo contenemos en 
                         * la variable $num_of_contacts*/
                        $num_of_contacts = intval($contact_obj->getContactCountByEnterprise());
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                                 * llamado "num_of_clientsEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                                 * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["num_of_clientsEx"] = "No se logró obtener los datos "
                                ."necesarios para generar los datos de los contactos, "
                                ."probable corte de conexión a la base de datos, o también puede "
                                ."que se haya modificado el id de la empresa con un id inexistente en la base de datos";
                        $num_of_contacts = 0;
                    }
                    
                        try{
                            /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                            * try-catch para intentar la conexión; getEnterpriseById devuelve en forma de array asociativo los campos de un registro de la tabla Empresas 
                             * de acuerdo al id de la empresa pasado en el setter del objeto de Empresas, $ent_arr se inicializa con el array que devuelve este método*/
                            $ent_arr = $ent_obj->getEnterpriseById();
                        } catch (Exception $ex) {
                            /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                                 * llamado "clients_arrEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                                 * base de datos con un lenguaje que pueda entender el usuario*/
                            $_SESSION["ent_arrEx"] = "No se logró obtener la "
                                    ."información de la empresa, posible corte "
                                    ."de conexión a la base de datos o también puede "
                                    ."que se haya modificado el id de la empresa con un id inexistente en la base de datos";
                            $ent_arr = false;
                        }   
                    

                    if($num_of_contacts > 0){
                        /*Si la empresa tiene contactos entonces se hace una petición a la base de datos para obtener los registros de contactos*/
                        try{
                            /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                            * try-catch para intentar la conexión; getContactsByEnterprise devuelve en forma de array asociativo los registros de la tabla Clientes 
                             * vinculados al id de la empresa pasado en el setter del objeto de Clientes, $clients_arr se inicializa con el array que devuelve este método*/
                            $contacts_arr = $contact_obj->getContactsByEnterprise();
                        } catch (Exception $ex) {
                            /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                                 * llamado "clients_arrEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                                 * base de datos con un lenguaje que pueda entender el usuario*/
                            $_SESSION["clients_arrEx"] = "No se logró obtener la "
                                    ."información de los contactos, posible corte "
                                    ."de conexión a la base de datos";
                            $contacts_arr = false;
                        }

                    }
                }
                /*Si se inicializarón las sesiones de excepciones entonces se utilizan en la vista enterAndContactsEditForms.php para mostrar el mensaje de 
                 * estas sesiones al usuario, por el contrario, si se inicializaron arrays con información de la base de datos entonces se utilizan en la vista 
                 * para determinar la aparición de elementos html*/
                require_once '../views/adminLayouts/enterAndContactsEditForms.php';
                
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        public function editTypes(){
            if(!empty($_SESSION["isAdmin"])){
                
                $_SESSION['LAST_ACTIVITY'] = time();
                /*obtenemos la información de todos los registros de la tabla Tipos*/
                $type_obj = new Tipos();
                try {
                    $types_arr = $type_obj->getTypes();
                } catch (Exception $ex) {
                    $_SESSION["typesDataForEditionEx"] = "No se logró conseguir los datos de los registros de tipos, lo más probable es que se haya "
                            . "cortado la conexión a la base de datos";
                    $types_arr = array();
                }
                /*Si se inicializarón las sesiones de excepciones entonces se utilizan en la vista typesEditForms.php para mostrar el mensaje de 
                 * estas sesiones al usuario, por el contrario, si se inicializaron arrays con información de la base de datos entonces se utilizan en la vista 
                 * para determinar la aparición de elementos html*/
                require_once '../views/adminLayouts/typesEditForms.php';
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*El método editDevice importa la vista del formulario de edición de dispositivos (devicesEditForms.php), lo primero que hace es evaluar si la sesión "isAdmin" 
         * no está vacía, si no se cumple la condición entonces PHP redirigirá al usuario a la vista por defecto del home*/
        public function editDevice(){
            if(!empty($_SESSION["isAdmin"])){
                
                /*Si la evaluación da true, entonces lo primero que se hace es guardar el tiempo actual en la 
                     * sesión "LAST_ACTIVITY" (la sesión del usuario tiene un tiempo de caducidad de 30 minutos)*/
                $_SESSION['LAST_ACTIVITY'] = time();
                /*Se obtienen registros para el select de empresas*/
                $ent_obj = new Empresas();
                try {
                    $enterprises = $ent_obj->getEnterprisesForEditSelect();
                } catch (Exception $ex) {
                    $_SESSION["selectDataEnterEditEx"] = "No se logró conseguir los datos para dar forma a la caja de "
                            . "selección de empresas en el formulario de busqueda, posible corte de conexión a la base "
                            . "de datos";
                    $enterprises = array();
                }
                /*Si se envian datos del formulario de busqueda de la vista inicializará la sesión "enterprise_id"*/
                if(sizeof($_POST) > 0){
                    $_SESSION["devicesEdit_enterId"] = (!empty($_POST["empresas"]) && (!preg_match('/[A-Za-z]+/', $_POST["empresas"]) ||
                        !preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $_POST["empresas"]))) ? $_POST["empresas"] : false;
                    
                    $get_params = http_build_query($_POST);
                    header("Location: ". base_url."home/?homeController=user&homeAction=editDevice&".$get_params);
                    exit;
                }
                
                if(!empty($_SESSION["devicesEdit_enterId"])){
                    /*Se necesita acceder a los equipos vinculados a la empresa, primero hay que hacer una instancia de la clase Equipos 
                     * y añadir en su atributo privado empresa_id el id de la empresa contenida en la sesión "devicesEdit_enterId" con un setter*/
                    $devices_arr = array();
                    $device_obj = new Equipos();
                    $device_obj->setEmpresa_id($_SESSION["devicesEdit_enterId"]);
                    try{
                        /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                         * try-catch para intentar la conexión; convertimos el string numerico que devuelve la función getDeviceCountByEnterprise (devuelve el 
                         * numero de registros de la tabla Equipos vinculados al id de la empresa) en un valor entero con la función propia de PHP intval y lo contenemos en 
                         * la variable $num_of_devices*/
                        $num_of_devices = intval($device_obj->getDeviceCountByEnterprise());
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                                 * llamado "num_of_devicesEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                                 * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["num_of_devicesEx"] = "No se logró obtener los datos "
                                ."necesarios para generar los datos de los equipos, "
                                ."probable corte de conexión a la base de datos o también puede "
                                ."que se haya modificado el id de la empresa con un id inexistente en la base de datos";
                        $num_of_devices = 0;
                    }
                    
                    if($num_of_devices > 0){
                        /*Si la empresa tiene equipos entonces se hace una petición a la base de datos para obtener los registros de equipos*/
                        
                        $type_obj = new Tipos();
                        
                        try{
                            /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                            * try-catch para intentar la conexión; getDevicesByEnterprise devuelve en forma de array asociativo los registros de la tabla Equipos 
                             * vinculados al id de la empresa pasado en el setter del objeto de Equipos, $devices_arr se inicializa con el array que devuelve este método, 
                             * tambien se obtienen los registros de la tabla Tipos para los select de los formularios de los equipos*/
                            $devices_arr = $device_obj->getDevicesByEnterprise();
                            $types_arr = $type_obj->getTypeForSelect();
                        } catch (Exception $ex) {
                            /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                                 * llamado "deviceInformationEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                                 * base de datos con un lenguaje que pueda entender el usuario*/
                            $_SESSION["deviceInformationEx"] = "No se logró obtener la "
                                    ."información de los equipos, posible corte "
                                    ."de conexión a la base de datos";
                            $devices_arr = array();
                            $types_arr = array();
                        }

                    }
                }
                /*Si se inicializarón las sesiones de excepciones entonces se utilizan en la vista devicesEditForms.php para mostrar el mensaje de 
                 * estas sesiones al usuario, por el contrario, si se inicializaron arrays con información de la base de datos entonces se utilizan en la vista 
                 * para determinar la aparición de elementos html*/
                require_once '../views/adminLayouts/devicesEditForms.php';
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*El método devicesReport importa la vista de reporte de dispositivos (devicesReport.php), primero evalúa si no está vacía la sesión del usuario "identity" y si 
         * la sesión "isAdmin" no está vacía, si no se cumple con la condición, PHP redirigirá al usuario a la vista por defecto del home*/
        public function devicesReport(){
            if(!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])){
                /*Si la evaluación da true, entonces lo primero que se hace es guardar el tiempo actual en la 
                     * sesión "LAST_ACTIVITY" (la sesión del usuario tiene un tiempo de caducidad de 30 minutos)*/
                $_SESSION['LAST_ACTIVITY'] = time();
                
                /*La vista devicesReport.php contiene un formulario de busqueda, dentro de ahí hay un elemento html select donde se selecciona el id de una empresa 
                 * por lo tanto, se necesita generar las opciónes de ese select, lo primero es crear una instancia de la clase Empresas*/
                $enter_obj = new Empresas();
                
                try{
                    /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                     * try-catch para intentar la conexión; getEnterprisesForSelect devuelve en forma de array asociativo los registros de la tabla Empresas con los 
                     * campos necesarios para crear las opciones del select de empresas, $enters se inicializa con ese array*/
                    $enters = $enter_obj->getEnterprisesForSelect();
                } catch (Exception $ex) {
                    /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "gettingEntersException" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                    $_SESSION["gettingEntersException"] = "No se pudo conseguir "
                            ."el listado de empresas para la busqueda, posible "
                            ."corte de conexión a la base de datos";
                    $enters = array();
                }
                
                /*Si se envian datos del formulario de busqueda de la vista inicializará la sesión "enterprise_id"*/
                if(sizeof($_POST) > 0){
                    $_SESSION["devicesReport_enterId"] = (!empty($_POST["empresas"]) && (!preg_match('/[A-Za-z]+/', $_POST["empresas"]) ||
                        !preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $_POST["empresas"]))) ? $_POST["empresas"] : false;
                    
                    $get_params = http_build_query($_POST);
                    header("Location: ". base_url."home/?homeController=user&homeAction=devicesReport&".$get_params);
                    exit;
                }
                
                /*Este if evalúa los campos del formulario ($_POST) de busqueda, la condición da true si $_POST no es un array vacío*/
                if(!empty($_SESSION["devicesReport_enterId"])){
                    /*si se envíó datos en el formulario de busqueda entonces lo primero que se hace es verificar si la empresa esta vinculada a varios equipos 
                     * por lo que necesitamos crear una instancia de la clase Equipos y le añadimos a su atributo privado empresa_id el id de la empresa contenido 
                     * en la sesión "devicesReport_enterId"*/
                    $enter_devices = array();
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
                    }

                    if($num_of_devices > 0){
                        $enter_obj->setId($_SESSION["devicesReport_enterId"]);
                        try{
                            /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                            * try-catch para intentar la conexión;el método getDevicesByEnterprise devuelve en forma de array 
                            * asociativo los registros de la tabla Equipos vinculados al id de una empresa pasado en el setter del objeto Equipos y getEnterpriseById 
                            * devuelve en forma de array asociativo los campos de un registro de la tabla Empresas 
                            * dependiendo del id de la empresa que se pasó en el setter del objeto Empresas*/
                            $enter_info = $enter_obj->getEnterpriseById();
                            $enter_devices = $device_obj->getDevicesByEnterprise();
                        } catch (Exception $ex) {
                            /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "deviceReportException" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                            $_SESSION["deviceReportException"] = "No se logró conseguir "
                                    ."la información para el reporte, posible corte "
                                    ."de conexión a la base de datos";
                            $enter_devices = array();  
                        }
                    }
                }
                
                /*Si se inicializarón las sesiones de excepciones entonces se utilizan en la vista devicesReport.php para mostrar el mensaje de 
                 * estas sesiones al usuario, por el contrario, si se inicializaron arrays con información de la base de datos entonces se utilizan en la vista 
                 * para determinar la aparición de elementos html*/
                require_once '../views/adminLayouts/devicesReport.php';
            }else{
                header("Location: ".base_url."home/");
                exit; 
            }
        }
        
        /*El método binnaclesReport importa la vista de reporte de bitácoras binnaclesFilter.php, lo primero que hace es evalúar si la sesión del usuario "identity" 
         * no está vacía y si la sesión "isAdmin" no está vacía, si no se cumple con la condición entonces PHP redirigirá al usuario a la vista por defecto del home*/
        public function binnaclesReport(){
            if(!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])){
                /*Si la evaluación da true, entonces lo primero que se hace es guardar el tiempo actual en la 
                     * sesión "LAST_ACTIVITY" (la sesión del usuario tiene un tiempo de caducidad de 30 minutos)*/
                $_SESSION['LAST_ACTIVITY'] = time();
                /*La vista binnaclesFilter.php tiene un formulario de busqueda, este if evalúa si el array de campos de formulario $_POST no es un array vacío*/
                if(sizeof($_POST) > 0){
                    /*Si se envío datos del formulario de busqueda, lo primero que se hace es evaluar los campos de post en el método estatico verifyPostData, este 
                     * método puede devolver un array vacío o un array asociativo con indices que representan los diferentes campos invalidos, $errors_arr se inicializa 
                     * con el array que devuelve este método*/
                    $errors_arr = Utils::verifyPostData($_POST, "binnsFilter");
                    

                    if(sizeof($errors_arr) === 0){
                        /*Si $errors_arr es un array vacío, entonces lo primero que se hace es inicializar la sesión "filtValidated" con el valor booleano true*/
                        $_SESSION["filtValidated"] = true;
                        // Construir la query string automáticamente, http_build_query convierte el array asociativo de post en parametros GET para una URL, internamente 
                        // esta función aplica urlencode a cada valor, así se evitan problemas con espacios o caracteres especiales. 
                        $queryString = http_build_query($_POST);
                        
                        /*Luego, se utilizan los valores de los campos para inicializar sesiones de campos, hay campos que pueden estar vacios, se utiliza un operador 
                         * ternario evaluando si no están vacíos, si se cumple la condición se usará el valor del post, si no se cumple, la sesión en cuestión tendrá 
                         * valor null, estas sesiones lo utiliza la vista binnaclesFilter.php para mantener la selección del usuario de las opciones del formulario de 
                         * busqueda, tambien estas sesiones son importantes para crear porciones de sentencias sql*/
                        $_SESSION["Empresa_id"] = (!empty($_POST["empresaId"])) ? $_POST["empresaId"] : null;
                        $_SESSION["Contacto_id"] = (!empty($_POST["contactoId"])) ? $_POST["contactoId"] : null;
                        $_SESSION["isServiceOrDevice"] = $_POST["servicioOEquipo"];
                        $_SESSION["Equipo_id"] = (!empty($_POST["equipoId"])) ? $_POST["equipoId"] : null;
                        $_SESSION["Estatus"] = $_POST["estatus"];
                        $_SESSION["StartedOrEnded"] = $_POST["startedOrEnded"];
                        $_SESSION["LeftDay"] = (!empty($_POST["leftDay"])) ? $_POST["leftDay"] : null;
                        $_SESSION["RightDay"] = (!empty($_POST["rightDay"])) ? $_POST["rightDay"] : null;
                        $_SESSION["Visible"] = $_POST["visible"];
                        
                        /*Estas sesiones SQL son importantes para realizar una petición a la base de datos para generar la paginación, cada sesión tiene una porción de sentencia sql, 
                         * estas porciones representan las condiciones dadas después de un WHERE, las sesiones SQL se apoyan de las sesiones de los campos de busqueda, hay tres 
                         * sesiones de campos que siempre van a tener un valor como "isServiceOrDevice", "Estatus" y "StartedOrEnded", pero los que no, se utiliza un operador 
                         * ternario, si esas sesiones no están vacías (no tienen el valor null) se crea una porción sql correspondiente, si esas sesiones tienen valor null, 
                         * entonces no abrá una porcion de sentencia sql (un string vacío)*/
                        $_SESSION["SQL_Contacto_id"] = (!empty($_SESSION["Contacto_id"])) ? 'b.Contacto_id = '.$_SESSION["Contacto_id"].' AND ' : '';
                        $_SESSION["SQL_isServiceOrDevice"] = 'b.'.$_SESSION["isServiceOrDevice"].' IS NOT NULL AND ';
                        $_SESSION["SQL_Equipo_id"] = (!empty($_SESSION["Equipo_id"])) ? 'b.Equipo_id = '.$_SESSION["Equipo_id"].' AND ' : '';
                        $_SESSION["SQL_Estatus"] = "b.Estatus = '".$_SESSION["Estatus"]."'";
                        $_SESSION["SQL_betweenDays"] = (!empty($_SESSION["LeftDay"]) && !empty($_SESSION["RightDay"])) ? " AND b."
                                                        .$_SESSION["StartedOrEnded"]." BETWEEN '".$_SESSION["LeftDay"]."' AND '".$_SESSION["RightDay"]."'" : '';
                        $_SESSION["SQL_visible"] = " AND b.Visibilidad = '".$_SESSION["Visible"]."'";
                        
                        // una vez teniendo nuestras sesiones listas, Redirigir con todos los parámetros del POST como GET contenidos en nuestra variable $queryString, esto 
                        // con el fin de que si el usuario entra a una bitácora y quiera regresar al filtrado de bitácoras no le salga la ventana de "docuemnto expirado" 
                        header("Location:". base_url."home/?homeController=user&homeAction=binnaclesReport&" .$queryString);
                        die;
                        
                    }else{
                        /*Si $errors_arr no es un array vacío entonces se inicializa la sesión "binnFilterErr" con ese array*/
                        $_SESSION["binnFilterErr"] = $errors_arr;
                        /*si hubo sesiones de campos, sesiones sql y la sesión "filtValidated" pertenecientes a una busqueda anterior, estas se eliminan 
                         * gracias al método estatico unsetBinnFilterSessions*/
                        Utils::unsetBinnFilterSessions();
                    }
                }
                
                /*antes de calcular la paginación, hay que verificar si la sesión "filtValidated" esté inicializada con true*/
                if(!empty($_SESSION["filtValidated"])){
                    /*Si la sesión "filtValidated" tiene el valor true, lo primero que se hace son los primeros pasos para generar una paginación; la variable 
                     * $query_for_rows_calc se inicializa con una sentencia sql para obtener el numero de resgistros de bitácoras que cumplan con la condición 
                     * utilizando nuestras sesiones sql*/
                    $query_for_rows_calc = "SELECT COUNT(Id) AS 'total' FROM Bitacoras b WHERE "
                                                .$_SESSION["SQL_Contacto_id"]
                                                .$_SESSION["SQL_isServiceOrDevice"]
                                                .$_SESSION["SQL_Equipo_id"]
                                                .$_SESSION["SQL_Estatus"]
                                                .$_SESSION["SQL_betweenDays"]
                                                .$_SESSION["SQL_visible"]
                                                .";";
                    /*luego creamos una instancia de la clase Bitacoras añadiendo a la propiedad privada external_query de esta nuestra variable $query_for_rows_calc 
                     * con un setter*/
                    $binnacle_obj = new Bitacoras();
                    $binnacle_obj->setExternal_query($query_for_rows_calc);

                    try {
                        /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                         * try-catch para intentar la conexión; convertimos el string numerico que devuelve la función getBinnCountByFilterSessions (devuelve el 
                         * numero de registros de bitácoras de acuedo a la sentencia sql pasada a la propiedad external_query) en un valor entero  
                         * con la función propia de PHP intval y lo contenemos en la variable $num_rows*/
                        $num_rows = intval($binnacle_obj->getBinnCountByFilterSessions());
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "num_rowsEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["num_rowsEx"] = "No se logró obtener los datos para calcular la paginación, posible corte de conexión a la base de datos";
                        /*si hubo sesiones de campos, sesiones sql y la sesión "filtValidated" pertenecientes a una busqueda anterior, estas se eliminan 
                         * gracias al método estatico unsetBinnFilterSessions*/
                        Utils::unsetBinnFilterSessions();
                        $num_rows = 0;
                    }

                    if ($num_rows > 0) {
                        /*si $num_rows es mayor a cero, enotnces lo primero que se hace es un operador ternario evaluando la sesión "binnsFilterNumRows", esta 
                         * sesión se crea despues de que el usuario haya seleccionado un numero en el select de cantidad de elementos en pantalla si es que 
                         * existe una paginación en la vista binnaclesFilter.php, también sirve para mantener la selección del usuario es ese select; si la 
                         * sesión no está vacía, entonces la variable $page_elem se inicializará con el valor de esta sesión, si la sesión está vacía, entonces 
                         * la variable $page_elem se inicializará con el valor por defecto: 5*/
                        
                        (!empty($_SESSION["binnsFilterNumRows"])) ?
                                        $page_elem = $_SESSION["binnsFilterNumRows"] :
                                        $page_elem = 5;

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
                         * dadas gracias a las sesiones sql generadas al enviar datos del formulario de busqueda, tambien se ordenan los registros 
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
                        } catch (Exception $ex) {
                            /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                             * llamado "binnsRowsPaginationEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                             * base de datos con un lenguaje que pueda entender el usuario*/
                            $_SESSION["binnsRowsPaginationEx"] = "Se generó un "
                                    . "error interactuando con la base de datos "
                                    . "en cuanto a la generación de paginación, posible falta de conexión";
                            /*si hubo sesiones de campos, sesiones sql y la sesión "filtValidated" pertenecientes a una busqueda anterior, estas se eliminan 
                             * gracias al método estatico unsetBinnFilterSessions*/
                            Utils::unsetBinnFilterSessions();
                            
                        }
                    }
                }
                
                /*dentro del formulario de busqueda hay un elemento html select de selección de Empresas el cual requiere que se genere sus 
                 * opciones por medio de PHP, por lo que se crea una instancia de la clase Empresas*/
                $enter_obj = new Empresas();
                try{
                    /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                     * try-catch para intentar la conexión; getEnterprisesForSelect devuelve en forma de array asociativo todos los registros de la tabla Empresas con sus 
                     * campos necesarios para crear las opciones del elemento html select de empresas de la vista (deviceForm.php), $empresas se inicializa con el array 
                     * que devuelve este método*/
                    $empresas = $enter_obj->getEnterprisesForSelect();
                } catch (Exception $ex) {
                    /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                     * llamado "binnReportgetClientsForSelectEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                     * base de datos con un lenguaje que pueda entender el usuario*/
                    $_SESSION["binnReportgetClientsForSelectEx"] = "No se logró conseguir "
                            ."los datos de las empresas necesarios para generar la caja de "
                            ."busqueda de empresas en las opciones de filtrado, posible "
                            ."corte de conexión a la base de datos";
                    $empresas = array();
                }
                /*Si se inicializarón las sesiones de excepciones entonces se utilizan en la vista binnaclesFilter.php para mostrar el mensaje de 
                 * estas sesiones al usuario, por el contrario, si se inicializaron arrays con información de la base de datos entonces se utilizan en la vista 
                 * para determinar la aparición de elementos html*/
                require_once '../views/adminLayouts/binnaclesFilter.php';
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*El método showBinnacle activa la vista de muestra de bitácora (binnacleInfoCanvas.php en el bloque if del get $_GET["homeAction"] === "showBinnacle"), 
         * primero evalúa si la sesión del usuario "identity" no está vacía, también si la sesión "isAdmin" no está vacía y si no está vacía la clave get "homeId", 
         * si no se cumple con la condición, PHP redirigirá al usuario a la vista por defecto del home*/
        public function showBinnacle(){
            if(!empty($_SESSION["identity"]) && 
               !empty($_SESSION["isAdmin"])  && 
               !empty($_GET["homeId"])){
                /*Se hace una verificación si la clave get "homeId" tiene un valor númerico, si no se cumple con la condición entonces PHP redirigirá al usuario a 
                 * la vista por defecto del home*/
                if(preg_match('/[0-9]+/', $_GET["homeId"])){
                    /*si la clave get tiene un valor númerico entonces se crea una instancia de la clase Bitacoras y se le añade a la propiedad privada $id de esta 
                     * el valor de la clave get con un setter*/
                    $binn_obj = new Bitacoras();
                    $binn_obj->setId($_GET["homeId"]);
                    try{
                        /*El método getServicioFieldById efectua una conexión a sql server para hacer una petición, por lo tanto, se crea un try-catch 
                         * para intentar la conexión, getServicioFieldById devuelve el campo "Servicio" de una bitácora dependiendo del Id de esta pasado 
                         * en el setter del objeto Bitacoras, $is_service se inicializa con el array que devuelve el método*/
                        $is_service = $binn_obj->getServicioFieldById();
                        /*en la variable $info se efectúa un operador ternario, este evalua si el indice "Servicio" del array $service no está vacío, si es true 
                         * $info contendrá el array que devolverá el método getInfoIfServicioIsNotNull, este devuelve los campos de una bitácora (por su id) si 
                         * en dado caso de que el campo "Servicio" tiene un valor, si el ternario da false, $info contendrá el array que devolvera 
                         * el método getInfoIfServicioIsNull, este método devuelve las columnas de una bitácora (por su id) considerando la información del equipo 
                         * vinculado a esa bitácora*/
                        $binn_info = (!empty($is_service["Servicio"])) ? $binn_obj->getInfoIfServicioIsNotNull() :
                            $binn_obj->getInfoIfServicioIsNull();
                        /*puede que el usuario en la herramienta de desarrolladores del navegador cambie la clave get "id" del link id del registro de una bitácora 
                         * en la vista de reporte de bitácoras con un numero Id que no exista en la base de datos, en ese caso, el método getServicioFieldById devolverá 
                         * un array vacío en $service, por consiguiente $info tambien contendrá un array vacío, es por eso que se hace una verificación con un if, si info 
                         * esta vacío entonces PHP redirigirá al usuario a la vista de seguimiento de bitácoras y cortará el flujo del código de este contexto con exit*/
                        if(empty($binn_info)){
                            header("Location: ".base_url."home/?homeController=error&homeAction=index");
                            exit;
                        }
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                         * llamado "getBinnInfoEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                         * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["getBinnInfoEx"] = "No se logró obtener los "
                                ."datos de la bitácora seleccionada, posible "
                                ."corte de conexión a la base de datos";
                        /*PHP redirigirá al usuario a la vista de reporte de bitácoras, en el html de esa vista 
                             * se utilizará la sesión "getBinnInfoEx", también php cortará el flujo del código de este contexto con exit*/
                        header("Location: ".base_url."home/?homeController=user&homeAction=binnaclesReport");
                        exit;
                    }
                    /*Si el array $binn_info tiene información de una bitácora, entonces se va a evaluar el indice "Monto" de ese array si es que no está vacío*/
                    if(!empty($binn_info["Monto"])){
                        /*todo lo que la base de datos devuelve es información en forma de string, incluyendo valores numericos, entonces
                             * si Monto no es igual a "" entonces tiene un numero flotante, lo que se hace es convertir el indice "Monto" en un valor flotante y multiplicar 
                             * ese valor por 1.16 (calculo del IVA en México), el resultado se guardará en la variable $iva_result*/
                            $iva_result = floatval($binn_info["Monto"]) * 1.16;
                            /*en la variable $with_iva contendrá la cantidad contenida en $iva_result pero configurada para que este solo 
                             * tenga dos decimales, la variable $binn_info (y si entra en este if, tambien la variable $with_iva) lo 
                             * utilizará la vista binnacleInfoCanvas.php en el bloque if de $_GET["homeAction"] === "showBinnacle"*/
                            $with_iva = sprintf("%.2f", $iva_result);
                    }
                }else{
                    header("Location: ".base_url."home/");
                    exit;
                }
                /*la vista binnacleInfoCanvas.php en su bloque if de $_GET["homeAction"] === "showBinnacle" se utilizará el array $binn_info para dar forma a la información 
                 * de la plantilla de una bitácora*/
                require_once '../views/adminLayouts/binnacleInfoCanvas.php';
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*El método showBinnacle activa la vista de edición de una bitácora (binnacleInfoCanvas.php en el bloque if del get $_GET["homeAction"] === "editBinnacle"), 
         * primero evalúa si la sesión del usuario "identity" no está vacía, también si la sesión "isAdmin" no está vacía y si no está vacía la clave get "homeId", 
         * si no se cumple con la condición, PHP redirigirá al usuario a la vista por defecto del home*/
        public function editBinnacle(){
            if(!empty($_SESSION["identity"]) && 
               !empty($_SESSION["isAdmin"])  && 
               !empty($_GET["homeId"])){
                /*Se hace una verificación si la clave get "homeId" tiene un valor númerico, si no se cumple con la condición entonces PHP redirigirá al usuario a 
                 * la vista por defecto del home*/
                if(preg_match('/[0-9]+/', $_GET["homeId"])){
                    /*si la clave get tiene un valor númerico entonces se crea una instancia de la clase Bitacoras y se le añade a la propiedad privada $id de esta 
                     * el valor de la clave get con un setter*/
                    $binn_obj = new Bitacoras();
                    $binn_obj->setId($_GET["homeId"]);
                    /*en este try-catch se hace una petición a la base de datos similar al método showBinnacle*/
                    try{
                        $is_service = $binn_obj->getServicioFieldById();
                        $binn_info = (!empty($is_service["Servicio"])) ? $binn_obj->getInfoIfServicioIsNotNull() :
                            $binn_obj->getInfoIfServicioIsNull();
                        if(empty($binn_info)){
                            header("Location: ".base_url."home/?homeController=error&homeAction=index");
                            exit;
                        }
                    } catch (Exception $ex) {
                        $_SESSION["getBinnInfoEx"] = "No se logró obtener los "
                                ."datos de la bitácora seleccionada, posible "
                                ."corte de conexión a la base de datos";
                        header("Location: ".base_url."home/?homeController=user&homeAction=binnaclesReport");
                        exit;
                    }
                    /*Al tener $binn_info la información de una bitácora se hace una evaluación del valor del campo Estatus, si este es igual a "en proceso" o 
                     * "falta confirmar" se hace los pasos necesarios para crear un array de registros de la tabla Usuarios, ese array se utilizará en la vista 
                     * de edición de una bitácora para dar forma al select de selección de usuario (se puede cambiar al responsable solo si se tiene estos estatus)*/
                    if($binn_info["Estatus"] === "en proceso" || $binn_info["Estatus"] === "falta confirmar"){
                        $user_obj = new Usuarios();
                        try{
                            $usuarios = $user_obj->getUsers();
                        } catch (Exception $ex) {
                            $_SESSION["gettingUsersException"] = "No se pudo conseguir "
                                    ."el listado de usuarios para la busqueda, posible "
                                    ."corte de conexión a la base de datos";
                            header("Location: ".base_url."home/?homeController=user&homeAction=binnaclesReport");
                            exit;
                        }
                    }
                }else{
                    header("Location: ".base_url."home/");
                    exit;
                }
                /*la vista binnacleInfoCanvas.php en su bloque if de $_GET["homeAction"] === "editBinnacle" se utilizará el array $binn_info para dar forma a la información 
                 * de la sección de edición de una bitácora*/
                require_once '../views/adminLayouts/binnacleInfoCanvas.php';
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        //--------------------------------------MÉTODOS DE VISTAS----------------------------------------------------
        
        //--------------------------------------MÉTODOS DE GESTIÓN DE DATOS------------------------------------------
        /*los métodos de gestión de datos recepcionan los datos que se enviaron por un formulario, es decir, el array 
         * superglobal $_POST (hay un caso especial como el método logout donde no recepciona datos de formulario 
         * $_POST sino datos de sesiones)*/
        
        /*el método login recepciona los datos enviados del formulario del login, evalua si $_POST no es un array vacío, si no se cumple con la condición 
         * se redirigirá al usuario a la vista por defecto del home (en este caso el propio login)*/
        public function login(){
            
            if(sizeof($_POST) > 0){
                /*si $_POST no es un array vacío entonces se utiliza el método estático verifyPostData para evaluar los campos del post, este método puede 
                 * devolver un array vacío o un array asociativo cuyos indices representan a los diferentes campos invalidos, $errorArr se inicializa con 
                 * lo que devuelve este método*/
                $errorArr = Utils::verifyPostData($_POST, "login");
                
                
                if(sizeof($errorArr) === 0){
                    
                    /*si $errorArr es un array vacío, entonces lo primero que se hace es crear una instancia de la clase Usuarios y se le añade a sus propiedades 
                     * privadas alias y contraseña sus respectivos campos del post con un setter, luego, se utiliza el método login del objeto de Usuarios*/
                    $user_obj = new Usuarios();
                    $user_obj->setAlias($_POST["user"]);
                    $user_obj->setContrasena($_POST["pwd"]);
                    $possible_user = $user_obj->login();
                    /*el método login puede devolver un array asociativo con algunos campos de un registro de la tabla Usuarios o un false, si contiene el array se 
                     * inicializa la sesión del usuario "identity" con el array asociativo que contiene $possible_user, si $possible_user tiene el valor false, se 
                     * inicializará la sesión "loginErrors" con el indice "logFailed" el cual tiene un mensaje explicando al usuario lo que pasó*/
                    if(!empty($possible_user)){
                        $_SESSION["identity"] = $possible_user;
                    }else{
                        $_SESSION["loginErrors"]["logFailed"] = "El usuario no existe o falta de conexión a la base de datos";
                    }
                    /*se utiliza el método estatico setAdminWithVerify el cual varifica el indice "Privilegio" de la sesión "identity", si contiene el valor Admin, 
                     * entonces se inicializará la sesión "isAdmin"*/
                    Utils::setAdminWithVerify();
                }else{
                    /*Si $errorArr no es un array vacío, entonces se le agregará a la sesión loginErrors este array*/
                    $_SESSION["loginErrors"] = $errorArr;
                }
                /*Si está inicializada la sesión "loginErrors" PHP redirigira a la vista por defecto de home (en este caso la vista del login) mostrando al usuario 
                 * los mensajes que contiene esa sesión, por el contrario, si se inicializa la sesión "identity" (y tambien "isAdmin"), PHP redirigira al usuario a 
                 * la vista por defecto del home que en este caso es el menú de usuarios (técnicos) (en el caso de que "Privilegio" sea "user") o la vista de 
                 * bienvenida del administrador (si es que está inicializada la sesión "isAdmin")*/
                header("Location: ".base_url."home/");
                exit;
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*El método logout verifica si no está vacía la sesión del usuario "identity", si no cumple la condición, PHP redirigirá al usuario a la vista por defecto 
         * del home (en ese caso en la vista del login)*/
        public function logout(){
            
            if(!empty($_SESSION["identity"])){
                /*Si existe la sesión del usuario entonces se elimina el indice "identity" de $_SESSION y se vacía todo $_SESSION con ayuda de la función propia de 
                 * PHP session_destroy*/
                unset($_SESSION["identity"]);
                session_destroy();
                header("Location: ". base_url."home/");
                exit;
            }
            header("Location: ". base_url."home/");
            exit;
        }
        
        /*el método binninsertion recepciona los datos enviados del formulario de registro de bitácora, evalua si "identity" no es una sesión vacía, si no se cumple 
         * con la condición se redirigirá al usuario a la vista por defecto del home*/
        public function binninsertion(){
            if(!empty($_SESSION["identity"])){
                if(sizeof($_POST) > 0){
                    /*si $_POST no es un array vacío entonces se utiliza el método estático verifyPostData para evaluar los campos del post, este método puede 
                    * devolver un array vacío o un array asociativo cuyos indices representan a los diferentes campos invalidos, $errorArr se inicializa con 
                    * lo que devuelve este método*/
                    $errorArr = Utils::verifyPostData($_POST, "new-binnacle");
                    
                    if(sizeof($errorArr) === 0){
                        /*si $errorArr es un array vacío, entonces lo primero que se hace es inicializar las variables $servicio y $equipo_id, el registro de 
                         * una bitácora tiene dos posibilidades, ser de servicio o trabajar en un equipo en especifico, por lo tanto, los campos del post 
                         * "servicio" y "equipos" puede que uno tenga un valor y el otro no, por eso se utilizan operadores ternarios para determinar el 
                         * valor de estas dos variables*/
                        $servicio = (!empty($_POST["servicio"])) ? $_POST["servicio"] : null;
                        $equipo_id = (!empty($_POST["equipos"])) ? $_POST["equipos"] : null;
                        
                        /*Se crea una instancia de la clase Bitacoras, en el constructor ponemos los valores de los campos de post, estos estan ordenados de 
                         * acuerdo al orden de los parametros de esta clase (los parametros restantes tendrán valor null), para conocer los parametros de 
                         * esta clase ve al archivo Bitacoras.php*/
                        $binnacle_obj = new Bitacoras(
                            $_POST["userId"],
                            $_POST["contactos"],
                            $servicio,
                            $equipo_id
                        );
                        try {
                            /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                            * try-catch para intentar la conexión; insertBinnacle hace una petición de creación de un registro de la tabla Bitacoras de acuerdo a los valores 
                            * que se pasaron en el constructor de la clase Bitacoras*/
                            $binnacle_obj->insertBinnacle();
                            $_SESSION["binnDataSucceed"] = "succeed";
                        } catch (Exception $ex) {
                            $_SESSION["binnDataException"] = "Acción fallida, probable falta de conexión";
                        }
                    }else{
                        $_SESSION["binnDataErr"] = $errorArr;
                    }
                    /*PHP redirigirá al usuario a la vista de nueva bitácora, en el html de esta vista se utilizarán las posibles sesiones que se inicializarón, "binnDataErr" 
                     * en el caso de que el usuario envió datos de formulario no validos, "binnDataException" en el caso de que PDO arrojó una excepción y "binnDataSucceed" 
                     * en el caso de que se pudo hacer un registro en la tabla Bitacoras en la base de datos*/
                    header("Location: ".base_url."home/?homeController=user&homeAction=newbinnacle");
                    exit;
                }
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*el método insertContact recepciona los datos enviados del formulario de registro de un contacto, evalua si "identity" no es una sesión vacía y si $_POST no es un 
         * array vacío, si no se cumple con la condición se redirigirá al usuario a la vista por defecto del home*/
        public function insertContact(){
            
            if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){
                $errors_arr = Utils::verifyPostData($_POST, "newContact");
                if(sizeof($errors_arr) === 0){
                    /*En el formulario de registro de contacto puede haber dos posibilidades de registro, escribir todos los datos tanto del contacto como la empresa en 
                     * la que pertenece o elegir una empresa existente y escribir solo el nombre del contacto (el apartado QUIÉN SOLICITA), este if evalúa si el campo del 
                     * post "hiddenEntId" no está vacía, si cumple la condición, la variable $hidden_ent_id se inicializa con el valor de ese campo, si no cumple la 
                     * condición, $hidden_ent_id se inicializa en null (hiddenEntId tiene como valor el id de la empresa que se seleccionó en el formulario, este se 
                     * origina dentro de la comunicación asincrona de javascript con PHP, en la función dataManagmentProcedure del archivo user.home.js es donde se 
                     * genera este input tipo "hidden")*/
                    
                    $hidden_ent_id = (!empty($_POST["hiddenEntId"])) ? $_POST["hiddenEntId"] : null;
                    
                    if(!empty($hidden_ent_id)){
                        $contact_obj = new Contactos(
                                $hidden_ent_id,
                                trim($_POST["contacto"])
                        );
                        
                        try{
                            $contact_obj->insertContact();
                            $_SESSION["insertContactSucceed"] = "Se realizó el registro del contacto con éxito";
                        } catch (Exception $ex) {
                            $_SESSION["contactWithEnterIdInsertionException"] = "Se generó un "
                                ."error interactuando con la base de datos "
                                ."en cuanto a la inserción de un contacto vinculado a una empresa "
                                . "existente, posible falta de conexión";
                        }
                    }else{
                        $enterprise_obj = new Empresas(
                                ucwords(strtolower(trim($_POST["nombreComercial"]))),
                                trim($_POST["razonSocial"]),
                                $_POST["calleYNumero"],
                                $_POST["entreCalles"],
                                $_POST["dirigirseCon"],
                                $_POST["telefonos"],
                                $_POST["horario"],
                                $_POST["atencion"],
                                $_POST["colonia"],
                                $_POST["localidad"],
                                trim($_POST["email"])
                        );
                       
                        try{
                            if($enterprise_id = $enterprise_obj->insertEnterprise()){
                                $contact_obj = new Contactos(
                                        $enterprise_id,
                                        trim($_POST["contacto"])
                                );
                                $contact_obj->insertContact();
                                $_SESSION["insertContactSucceed"] = "Se realizó el registro del contacto con éxito";
                            }else{
                                $_SESSION["contactTotalInsertionException"] = "Se generó un "
                                ."error interactuando con la base de datos "
                                ."en cuanto a la inserción total de un contacto, "
                                . "lo más probable es que se haya ingresado un nombre de empresa ya existente "
                                . "en la base de datos, busca esa empresa en la caja de selección de empresa de este formulario";
                            }
                        } catch (Exception $ex) {
                            $_SESSION["contactTotalInsertionException"] = "Se generó un "
                                ."error interactuando con la base de datos "
                                ."en cuanto a la inserción total de un contacto, "
                                . "lo más probable es que se haya cortado la conexión a la base de datos";
                        }
                    }
                }else{
                    $_SESSION["contactFormErr"] = $errors_arr;
                }
                header("Location: ".base_url."home/?homeController=user&homeAction=newcontact");
                exit;
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*el método insertType recepciona los datos enviados del formulario de registro de un tipo de equipo, evalua si "identity" no es una sesión vacía y si $_POST no es un 
         * array vacío, si no se cumple con la condición se redirigirá al usuario a la vista por defecto del home*/
        public function insertType(){
            if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){
                $errors_arr = Utils::verifyPostData($_POST, "newType");
                if(sizeof($errors_arr) === 0){
                    $type_obj = new Tipos(
                            strtolower($_POST["tipo"])
                    );
                    try {
                        ($type_obj->insertType()) ? $_SESSION["insertTypeSucceed"] = "Se realizó el registro del tipo con éxito" :
                        $_SESSION["typeInsertionException"] = "Se generó un "
                                ."error interactuando con la base de datos "
                                ."en cuanto a la inserción de un tipo de equipo, "
                                ."lo más probable es que se haya ingresado un tipo "
                                ."existente en la base de datos,";
                    } catch (Exception $ex) {
                        $_SESSION["typeInsertionException"] = "Se generó un "
                                ."error interactuando con la base de datos "
                                ."en cuanto a la inserción de un tipo de equipo, "
                                ."lo más probable es que se haya cortado la conexión a "
                                . "la base de datos";
                    }
                }else{
                    $_SESSION["typeFormErr"] = $errors_arr;
                }
                header("Location: ".base_url."home/?homeController=user&homeAction=newdevicetype");
                exit;
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*el método insertDevice recepciona los datos enviados del formulario de registro de un equipo, evalua si "identity" no es una sesión vacía y si $_POST no es un 
         * array vacío, si no se cumple con la condición se redirigirá al usuario a la vista por defecto del home*/
        public function insertDevice(){
            if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){
                $errors_arr = Utils::verifyPostData($_POST, "newDevice");
                if(sizeof($errors_arr) === 0){
                    $dce_obj = new Equipos(
                            $_POST["empresas"],
                            $_POST["tipos"],
                            $_POST["marca"],
                            $_POST["modelo"],
                            strtoupper($_POST["ns"]),
                            $_POST["numeroInventario"]
                    );
                    try{
                        ($dce_obj->insertDevice()) ? $_SESSION["insertDeviceSucceed"] = "Se realizó el registro del equipo con éxito" 
                            : $_SESSION["deviceInsertionException"] = "Se generó un "
                                ."error interactuando con la base de datos "
                                ."en cuanto a la inserción de un equipo, "
                                ."lo más probable es que se haya ingresado un número de serie "
                                ."existente en la base de datos";
                    } catch (Exception $ex) {
                        $_SESSION["deviceInsertionException"] = "Se generó un "
                                ."error interactuando con la base de datos "
                                ."en cuanto a la inserción de un equipo, "
                                ."lo más probable es que se haya cortado la conexión a la base de datos";
                    }
                }else{
                    $_SESSION["deviceFormErr"] = $errors_arr;
                }
                header("Location: ".base_url."home/?homeController=user&homeAction=newdevice");
                exit;
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*el método insertDBUser recepciona los datos enviados del formulario de creación de un usuario (userInsertForm.php), evalua si "isAdmin" no es una sesión vacía y si $_POST no es 
         * un array vacío, si no se cumple con la condición se redirigirá al usuario a la vista por defecto del home*/
        public function insertDBUser(){
            if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){
                /*si $_POST no es un array vacío entonces se utiliza el método estático verifyPostData para evaluar los campos del post, este método puede 
                 * devolver un array vacío o un array asociativo cuyos indices representan a los diferentes campos invalidos, $errorArr se inicializa con 
                 * lo que devuelve este método*/
                $errorArr = Utils::verifyPostData($_POST, "user");
                
                /*A continuación se hace una verificación a la contraseña que el administrador ingresó en la ventana emergente de confirmación de creación de 
                 * usuario en la vista userInsertForm.php, lo primero que se hace es verificar si el array $errorArr con indice "adminContrasena" esté vacía*/
                if(empty($errorArr["adminContrasena"])){
                    /*Si el campo adminContrasena quedó excento en el método verifyPostData, entonces se crea una instancia de la clase Usuarios y se le añade a 
                     * sus propiedades privadas $alias y $contrasena el valor del indice "Alias" de la sesión del usuario en el caso de la propiedad $alias con 
                     * un setter y el campo "adminContrasena" en el caso de la propiedad $contrasena con un setter, finalmente, se utiliza el método AdminPwdConfirmation 
                     * del objeto de Usuarios, se inicializa la variable $is_adminPWD con lo que devuelve ese método*/
                    $user_obj = new Usuarios();
                    $user_obj->setAlias($_SESSION["identity"]["Alias"]);
                    $user_obj->setContrasena($_POST["adminContrasena"]);
                    $is_adminPWD = $user_obj->AdminPwdConfirmation();
                    /*$is_adminPWD puede contener un valor booleano true o false, si $is_adminPWD es false se añadirá el indice "adminPWDRejected" al array $errorArr 
                     * el cual contiene un mensaje notificando al usuario que la contraseña que insertó no es valida*/
                    if(empty($is_adminPWD)){
                        $errorArr["adminPWDRejected"] = "Administrador, la "
                                ."contraseña ingresada no coincide con el que "
                                ."se tiene en la base de datos, en dado caso "
                                ."que ingresara su contraseña correctamente, "
                                ."en ese caso entonces se cortó la conexión a "
                                ."la base de datos";
                    }
                }
                
                
                if(sizeof($errorArr) === 0){
                    /*Si $errorArr es un array vacío, entonces se crea una instancia de la clase Usuarios, en su constructor utilizaremos el valor de los campos de post 
                     * (hay que poner los datos del post con su parametro de constructor correspondiente, para más información sobre el posicionamiento de parametros del 
                     * constructor consulta el archivo Usuarios.php de la carpeta models)*/
                    $user_obj = new Usuarios(
                            trim($_POST["nombre"]),
                            trim($_POST["apellidos"]),
                            trim($_POST["alias"]),
                            $_POST["contrasena"],
                            $_POST["privilegio"]
                    );
                
                    try {
                        /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                         * try-catch para intentar la conexión; insertUser hace una petición de creación de un registro de la tabla Usuarios de acuerdo a los valores 
                         * que se pasaron en el constructor de la clase Usuarios*/
                        ($user_obj->insertUser()) ? $_SESSION["userDataSucceded"] = "Usuario insertado con exito" : 
                            $_SESSION["userDataException"] = "Acción fallida, probable falta de conexión o nombre de usuario existente...";
                        
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                         * llamado "userDataException" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                         * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["userDataException"] = "Acción fallida, probable falta de conexión o nombre de usuario existente...";
                    }
                    
                }else{
                    /*Si $errorArr no es un array vacío, entonces inicializamos la sesión "userDataErr" con ese array*/
                    $_SESSION["userDataErr"] = $errorArr;
                    
                }
                /*PHP redirigirá al usuario a la vista del formulario de creación de usuarios, en el html de esa vista se utilizará la sesión "userDataErr" en caso de que 
                 * $errorArr no sea un array vacío, si insertUser arroja una excepción entonces se utilizará la sesión "userDataException" para mostrar al usuario el mensaje 
                 * de lo que pasó, si el método insertUser efectuó la petición de creación con éxito, entonces se usuará la sesión "userDataSucceded" para notificar al usuario*/
                header("Location: ".base_url."home/?homeController=user&homeAction=createUser");
                exit;
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*el método insertDBUser recepciona los datos enviados del formulario de cambio de contraseña de un usuario (userNewPwd.php), evalua si "isAdmin" no es una sesión 
         * vacía y si $_POST no es un array vacío, si no se cumple con la condición se redirigirá al usuario a la vista por defecto del home*/
        public function updateUserPassword(){
            if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){
                /*si $_POST no es un array vacío entonces se utiliza el método estático verifyPostData para evaluar los campos del post, este método puede 
                 * devolver un array vacío o un array asociativo cuyos indices representan a los diferentes campos invalidos, $errors_arr se inicializa con 
                 * lo que devuelve este método*/
                $errors_arr = Utils::verifyPostData($_POST, 'userUpdatePWD');
                
                /*A continuación se hace una verificación a la contraseña que el administrador ingresó en la ventana emergente de confirmación de cambio de contraseña del 
                 * usuario en la vista userNewPwd.php, lo primero que se hace es verificar si el array $errors_arr con indice "adminContrasena" esté vacía*/
                if(empty($errors_arr["adminContrasena"])){
                    /*Si el campo adminContrasena quedó excento en el método verifyPostData, entonces se crea una instancia de la clase Usuarios y se le añade a 
                     * sus propiedades privadas $alias y $contrasena el valor del indice "Alias" de la sesión del usuario en el caso de la propiedad $alias con 
                     * un setter y el campo "adminContrasena" en el caso de la propiedad $contrasena con un setter, finalmente, se utiliza el método AdminPwdConfirmation 
                     * del objeto de Usuarios, se inicializa la variable $is_adminPWD con lo que devuelve ese método*/
                    $user_obj = new Usuarios();
                    $user_obj->setAlias($_SESSION["identity"]["Alias"]);
                    $user_obj->setContrasena($_POST["adminContrasena"]);
                    $is_adminPWD = $user_obj->AdminPwdConfirmation();
                    /*$is_adminPWD puede contener un valor booleano true o false, si $is_adminPWD es false se añadirá el indice "adminPWDRejected" al array $errors_arr 
                     * el cual contiene un mensaje notificando al usuario que la contraseña que insertó no es valida*/
                    if(empty($is_adminPWD)){
                        $errors_arr["adminPWDRejected"] = "Administrador, la "
                                ."contraseña ingresada no coincide con el que "
                                ."se tiene en la base de datos";
                    }
                }
                if (sizeof($errors_arr) === 0) {
                    /*si $errors_arr es un array vacío, entonces se crea una instancia de la clase Usuarios y se añade en sus propiedades privadas $id y $contrasena 
                     * los campos del post con sus respectivos setters*/
                    $usuario_id = new Usuarios();
                    $usuario_id->setId($_POST["usuarioId"]);
                    $usuario_id->setAndEncryptContrasena($_POST["contrasena"]);
                    try{
                        /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                         * try-catch para intentar la conexión; updatePassword hace una petición de actualización de un registro de la tabla Usuarios de acuerdo a los valores 
                         * que se pasaron en los setters del objeto de Usuarios*/
                        $usuario_id->updatePassword();
                        $_SESSION["userPWDSucceed"] = "La contraseña se reestableció "
                                . "con éxito";
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                         * llamado "userPWDException" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                         * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["userPWDException"] = "No se pudo reestablecer "
                                ."la contraseña, posible corte de conexión a la base de datos";
                    }
                }else{
                    /*Si $errorArr no es un array vacío, entonces inicializamos la sesión "userDataErr" con ese array*/
                    $_SESSION["userPWDErr"] = $errors_arr;
                }
                /*PHP redirigirá al usuario a la vista del formulario de cambio de contraseña de un usuario, en el html de esa vista se utilizará la sesión "userPWDErr" 
                 * en caso de que $errors_arr no sea un array vacío, si updatePassword arroja una excepción entonces se utilizará la sesión "userDataException" para mostrar 
                 * al usuario el mensaje de lo que pasó, si el método updatePassword efectuó la petición de creación con éxito, entonces se usuará la sesión "userDataSucceded" 
                 * para notificar al usuario*/
                header("Location: ".base_url."home/?homeController=user&homeAction=userNewPassword");
                exit; 
            }else{
                header("Location: ".base_url."home/");
                exit; 
            }
        }
        
        /*el método updateEnterInfo recepciona los datos enviados del formulario de edición de una empresa (enterpriseForms.php), evalua si "isAdmin" no es una sesión 
         * vacía y si $_POST no es un array vacío, si no se cumple con la condición se redirigirá al usuario a la vista por defecto del home*/
        public function updateEnterInfo(){
            if(!empty($_SESSION["isAdmin"])  && sizeof($_POST) > 0){
                /*si $_POST no es un array vacío entonces se utiliza el método estático verifyPostData para evaluar los campos del post, este método puede 
                 * devolver un array vacío o un array asociativo cuyos indices representan a los diferentes campos invalidos, $errors_arr se inicializa con 
                 * lo que devuelve este método*/
                $error_arr = Utils::verifyPostData($_POST, "updateEnterpriseInfo");
                
                /*se hace una verificación de la contraseña de administrador si el campo "adminContrasena" está correctamente escrito*/
                if(empty($error_arr["adminContrasena"])){
                    $user_obj = new Usuarios();
                    $user_obj->setAlias($_SESSION["identity"]["Alias"]);
                    $user_obj->setContrasena($_POST["adminContrasena"]);
                    $is_adminPWD = $user_obj->AdminPwdConfirmation();
                    if (empty($is_adminPWD)) {
                        $error_arr["adminPWDRejected"] = "Administrador, la "
                                . "contraseña ingresada no coincide con el que "
                                . "se tiene en la base de datos, en dado caso "
                                ."que ingresara su contraseña correctamente, "
                                ."en ese caso entonces se cortó la conexión a "
                                ."la base de datos";
                    }
                }
                
                if(sizeof($error_arr) === 0){
                    /*Si $error_arr es un array vacío, entonces se crea una instancia de la clase Empresas, en el constructor de esta clase utilizamos los valores de 
                     * los campos de formulario del post (hay que poner los datos del post con su parametro de constructor correspondiente, para más información 
                     * sobre el posicionamiento de parametros del constructor consulta el archivo Empresas.php de la carpeta models)*/
                    $enter_obj = new Empresas(
                            ucwords(strtolower(trim($_POST["nombreComercial"]))),
                            trim($_POST["razonSocial"]),
                            $_POST["calleYNumero"],
                            $_POST["entreCalles"],
                            $_POST["dirigirseCon"],
                            $_POST["telefonos"],
                            $_POST["horario"],
                            $_POST["atencion"],
                            $_POST["colonia"],
                            $_POST["localidad"],
                            trim($_POST["email"])
                    );
                    /*despues de haber creado el objeto de Empresas hay que añadir a su propiedad privada $id el id de la empresa contenida en el campo "empresaId" 
                     * del post con un setter*/
                    $enter_obj->setId($_POST["empresaId"]);
                    try{
                        /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                         * try-catch para intentar la conexión; updateEnterpriseInfo hace una petición de actualización de un registro de la tabla Empresas de acuerdo 
                         * a los valores que se pasaron en el constructor de la clase Empresas y el setter*/
                        $enter_obj->updateEnterpriseInfo();
                        $_SESSION["updateEnterInfoSucceed"] = "Se actualizaron los datos de la empresa con éxito";
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                         * llamado "updateEnterInfoEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                         * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["updateEnterInfoEx"] = "Hubo una excepción en "
                                ."el proceso de actualización de datos de la "
                                ."empresa, posible corte de conexión a la base de datos";
                    }
                    
                }else{
                    /*si $error_arr no es un array vacío, se inicializa la sesión "updateEnterpriseInfoErr" con ese array*/
                    $_SESSION["updateEnterpriseInfoErr"] = $error_arr;
                }
                /*Si se inicializarón las sesiones de excepciones entonces se utilizan en la vista enterAndContactsEditForms.php para mostrar el mensaje de 
                 * estas sesiones al usuario, por el contrario, si se inicializaron arrays con información de la base de datos entonces se utilizan en la vista 
                 * para determinar la aparición de elementos html*/
                header("Location: ".base_url."home/?homeController=user&homeAction=editEnterprise");
                exit;
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*el método updateClientInfo recepciona los datos enviados del formulario de edición de un cliente (contacto) vinculado a una empresa (enterpriseForms.php), 
         * evalua si "isAdmin" no es una sesión vacía y si $_POST no es un array vacío, si no se cumple con la condición se redirigirá al usuario a la vista por 
         * defecto del home*/
        public function updateContactInfo(){
            if(!empty($_SESSION["isAdmin"])  && sizeof($_POST) > 0){
                /*si $_POST no es un array vacío entonces se utiliza el método estático verifyPostData para evaluar los campos del post, este método puede 
                 * devolver un array vacío o un array asociativo cuyos indices representan a los diferentes campos invalidos, $errors_arr se inicializa con 
                 * lo que devuelve este método*/
                $error_arr = Utils::verifyPostData($_POST, "updateContactInfo");
                
                /*se hace una verificación de la contraseña de administrador si el campo "adminContrasena" está correctamente escrito*/
                if(empty($error_arr["adminContrasena"])){
                    $user_obj = new Usuarios();
                    $user_obj->setAlias($_SESSION["identity"]["Alias"]);
                    $user_obj->setContrasena($_POST["adminContrasena"]);
                    $is_adminPWD = $user_obj->AdminPwdConfirmation();
                    if (empty($is_adminPWD)) {
                        $error_arr["adminPWDRejected"] = "Administrador, la "
                                . "contraseña ingresada no coincide con el que "
                                . "se tiene en la base de datos, en dado caso "
                                ."que ingresara su contraseña correctamente, "
                                ."en ese caso entonces se cortó la conexión a "
                                ."la base de datos";
                    }
                }
                
                if(sizeof($error_arr) === 0){
                    /*si $errors_arr es un array vacío, entonces se crea una instancia de la clase Contactos y se añade a la propiedad privada de este objeto 
                     * ($id) el valor del campo "clienteId" de post con un setter*/
                    $contact_obj = new Contactos(
                            null,
                            trim($_POST["nombre"])
                    );
                    $contact_obj->setId($_POST["contactoId"]);
                    try{
                        /*se utiliza el método insertSignatureById para actualizar el campo Nombre_completo 
                         * (el nombre del cliente (contacto)), este método hace una petición de actualización a un registro de la tabla Contactos de acuerdo al Id 
                         * pasado en el setter*/
                        $contact_obj->updateContactNameById();
                        $_SESSION["updateClientSucceed"] = "Se modificó al contacto con ID ".$_POST["contactoId"]." con éxito";
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                         * llamado "updateClientException" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                         * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["updateClientException"] = "Hubo un problema dentro del proceso de modificación del contacto, posible corte de conexión a la base de datos";
                    }
                }else{
                    /*si $errors_arr no es un array vacío, entonces se inicializa la sesión "updateClientInfoErr" con este array*/
                   $_SESSION["updateClientInfoErr"] = $error_arr;
                }
                /*Si se inicializarón las sesiones de excepciones entonces se utilizan en la vista enterAndContactsEditForms.php para mostrar el mensaje de 
                 * estas sesiones al usuario, por el contrario, si se inicializaron arrays con información de la base de datos entonces se utilizan en la vista 
                 * para determinar la aparición de elementos html*/
                header("Location: ".base_url."home/?homeController=user&homeAction=editEnterprise");
                exit;
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*el método updateTypeInfo recepciona los datos enviados del formulario de edición de un tipo (typesEditForms.php), 
         * evalua si "isAdmin" no es una sesión vacía y si $_POST no es un array vacío, si no se cumple con la condición se redirigirá al usuario a la vista por 
         * defecto del home*/
        public function updateTypeInfo(){
            if(!empty($_SESSION["isAdmin"])  && sizeof($_POST) > 0){
                /*si $_POST no es un array vacío entonces se utiliza el método estático verifyPostData para evaluar los campos del post, este método puede 
                 * devolver un array vacío o un array asociativo cuyos indices representan a los diferentes campos invalidos, $errors_arr se inicializa con 
                 * lo que devuelve este método*/
                $error_arr = Utils::verifyPostData($_POST, "updateTypeInfo");
                
                /*se hace una verificación de la contraseña de administrador si el campo "adminContrasena" está correctamente escrito*/
                if(empty($error_arr["adminContrasena"])){
                    $user_obj = new Usuarios();
                    $user_obj->setAlias($_SESSION["identity"]["Alias"]);
                    $user_obj->setContrasena($_POST["adminContrasena"]);
                    $is_adminPWD = $user_obj->AdminPwdConfirmation();
                    if (empty($is_adminPWD)) {
                        $error_arr["adminPWDRejected"] = "Administrador, la "
                                . "contraseña ingresada no coincide con el que "
                                . "se tiene en la base de datos, en dado caso "
                                ."que ingresara su contraseña correctamente, "
                                ."en ese caso entonces se cortó la conexión a "
                                ."la base de datos";
                    }
                }
                
                if(sizeof($error_arr) === 0){
                    /*si $errors_arr es un array vacío, entonces se crea una instancia de la clase Tipos y se añade a la propiedad privada de este objeto 
                     * ($id) el valor del campo "tipoId" de post con un setter*/
                    $typ_obj = new Tipos(
                            strtolower(trim($_POST["tipo"]))
                    );
                    $typ_obj->setId($_POST["tipoId"]);
                    try{
                        /*se utiliza el método insertSignatureById para actualizar el campo Tipo 
                         *, este método hace una petición de actualización a un registro de la tabla Tipos de acuerdo al Id 
                         * pasado en el setter*/
                        $typ_obj->updateTypeById();
                        $_SESSION["updateTypeSucceed"] = "Se modificó el tipo con ID ".$_POST["tipoId"]." con éxito";
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                         * llamado "updateClientException" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                         * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["updateTypeException"] = "Hubo un problema dentro del proceso de modificación del contacto, posible corte de conexión a la base de datos";
                    }
                }else{
                    /*si $errors_arr no es un array vacío, entonces se inicializa la sesión "updateClientInfoErr" con este array*/
                   $_SESSION["updateTypeInfoErr"] = $error_arr;
                }
                /*Si se inicializarón las sesiones de excepciones entonces se utilizan en la vista typesEditForms.php para mostrar el mensaje de 
                 * estas sesiones al usuario, por el contrario, si se inicializaron arrays con información de la base de datos entonces se utilizan en la vista 
                 * para determinar la aparición de elementos html*/
                header("Location: ".base_url."home/?homeController=user&homeAction=editTypes");
                exit;
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*el método updateDeviceInfo recepciona los datos enviados del formulario de edición de un dispositivo (devicesEditForm.php), 
         * evalua si "isAdmin" no es una sesión vacía, también si la sesión "enterInfo" no está vacía y si $_POST no es un array vacío, 
         * si no se cumple con la condición se redirigirá al usuario a la vista por defecto del home*/
        public function updateDeviceInfo(){
            if(!empty($_SESSION["isAdmin"])  && sizeof($_POST) > 0){
                /*si $_POST no es un array vacío entonces se utiliza el método estático verifyPostData para evaluar los campos del post, este método puede 
                 * devolver un array vacío o un array asociativo cuyos indices representan a los diferentes campos invalidos, $errors_arr se inicializa con 
                 * lo que devuelve este método*/
                $error_arr = Utils::verifyPostData($_POST, "updateDeviceInfo");
                
                /*se hace una verificación de la contraseña de administrador si el campo "adminContrasena" está correctamente escrito*/
                if(empty($error_arr["adminContrasena"])){
                    $user_obj = new Usuarios();
                    $user_obj->setAlias($_SESSION["identity"]["Alias"]);
                    $user_obj->setContrasena($_POST["adminContrasena"]);
                    $is_adminPWD = $user_obj->AdminPwdConfirmation();
                    if (empty($is_adminPWD)) {
                        $error_arr["adminPWDRejected"] = "Administrador, la "
                                . "contraseña ingresada no coincide con el que "
                                . "se tiene en la base de datos, en dado caso "
                                ."que ingresara su contraseña correctamente, "
                                ."en ese caso entonces se cortó la conexión a "
                                ."la base de datos";
                    }
                }
                
                if(sizeof($error_arr) === 0){
                    /*Si $error_arr es un array vacío, entonces se crea una instancia de la clase Equipos, en el constructor de esta clase utilizamos los valores de 
                     * los campos de formulario del post*/
                    $device_obj = new Equipos(
                            null,
                            null,
                            $_POST["marca"],
                            $_POST["modelo"],
                            trim($_POST["ns"]),
                            $_POST["numeroInventario"]
                    );
                    /*también se le añade el valor del campo "dispositivoId" a la propiedad privada $id del objeto de Equipos*/
                    $device_obj->setId($_POST["dispositivoId"]);
                    try{
                        /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                         * try-catch para intentar la conexión; updateDeviceInfoById hace una petición de actualización de un registro de la tabla Equipos de acuerdo 
                         * a los valores que se pasaron en el constructor de la clase Empresas y el setter*/
                        $device_obj->updateDeviceInfoById();
                        $_SESSION["updateDeviceInfoSucceed"] = "Se logró editar la información del dispositivo con ID ".$_POST["dispositivoId"]." con éxito";
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                         * llamado "updateDeviceInfoEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                         * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["updateDeviceInfoEx"] = "No se logró editar la información del dispositivo, "
                                ."lo más probable es que se haya registrado un número "
                                ."de serie que ya se encuentra en la base de datos. Otro problema posible "
                                ."es que se haya cortado la conexión a la base de datos";
                    }

                }else{
                    /*Si $errors_arr no es un array vacío, entonces se inicializa la sesión "updateDeviceInfoErr" con este array*/
                    $_SESSION["updateDeviceInfoErr"] = $error_arr;
                }
                /*Si se inicializarón las sesiones de excepciones entonces se utilizan en la vista devicesEditForms.php para mostrar el mensaje de 
                 * estas sesiones al usuario, por el contrario, si se inicializaron arrays con información de la base de datos entonces se utilizan en la vista 
                 * para determinar la aparición de elementos html*/
                header("Location: ".base_url."home/?homeController=user&homeAction=editDevice");
                exit;
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*el método updateBinnacleInfo recepciona los datos enviados del formulario de edición de una bitácora (binnacleInfoCanvas.php en el bloque if de $_GET["homeAction"] 
         * === "editBinnacle") vinculado a una empresa (enterpriseForms.php), evalua si "isAdmin" no es una sesión vacía y si $_POST no es un array vacío, si no se cumple 
         * con la condición se redirigirá al usuario a la vista por defecto del home*/
        public function updateBinnacleInfo(){
            if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){
                /*si $_POST no es un array vacío entonces se utiliza el método estático verifyPostData para evaluar los campos del post, este método puede 
                 * devolver un array vacío o un array asociativo cuyos indices representan a los diferentes campos invalidos, $errorArr se inicializa con 
                 * lo que devuelve este método*/
                $errorArr = Utils::verifyPostData($_POST, "updateBinnacleInfo");
                /*esta estructura if evalúa el campo "precio" del post el cual es un string de numero flotante*/
                if(isset($_POST["precio"])){
                    if(trim($_POST["precio"]) !== ""){
                        /*Si el campo del formulario "precio" está definido y no es un string vacío, entonces lo primero que se hace es evaluar el valor del 
                         * campo, filter_var de PHP puede aplicar filtros númericos incluso si el valor es un string númerico (normalmente, los valores de 
                         * los campos de un formulario son strings)*/
                        if (filter_var(trim($_POST["precio"]), FILTER_VALIDATE_FLOAT) === false) {
                           /*Si filter_var con la constante FILTER_VALIDATE_FLOAT da false significa que el valor del campo no tiene un formato de número flotante, 
                            * por lo que se le agrega al arreglo $errorArr el indice "precio" con un string indicando que el campo no es valido*/ 
                           $errorArr["precio"] = "el precio que se ingresó no es un formato valido, hay que poner punto en lugar de coma en el precio...Ejemplo: 2345.75";
                        }else{
                            /*Si el campo "precio" tiene un formato de número flotante, entonces entra al bloque falso del if, lo primero que se hace es inicializar 
                             * la variable $pos con lo que devuelve strpos, strpos de PHP devuelve el numero del indice donde se encuentra la coincidencia en este caso 
                             * el punto flotante "." (los string tambien son arrays, cada caracter es un indice)*/
                            $pos = strpos(trim($_POST["precio"]), ".");
                            /*Los numeros enteros tambien son considerados flotantes en el filter_var, por lo que strpos puede devolver un false al no encontrar el 
                             * punto flotante*/
                            if(!empty($pos)){
                                /*Si $pos tiene el numero del indice donde aparece el punto flotante, entonces se inicializa la variable $decimal_parts con el calculo 
                                 * para conseguir los decimales, esto se consigue obteniendo la cantidad de caracteres del array (el string del campo "precio") con 
                                 * strlen de PHP, esa función cuenta los caracteres a partir del numero 1, los arrays empiezan con el numero 0 y strpos devuelve un 
                                 * numero conciderando el indice 0, asi que se tiene que restar el numero que devuelve strlen con 1, despues se resta el valor que 
                                 * tiene $pos, de esa forma se obtiene los decimales (la cantidad de caracteres despues del punto flotante)*/
                                $decimal_parts = strlen(trim($_POST["precio"])) - 1 - $pos;
                                if($decimal_parts !== 2){
                                   /*Si $decimal_parts tiene un valor diferente de dos entonces se le añade al array $errorArr el indice "precio" con un string 
                                    * indicando que el campo no es valido*/ 
                                   $errorArr["precio"] = "El precio debe ser de dos decimales...Ejemplo: 2345.75";
                                }
                            }
                        }
                    }
                }
                /*A continuación se hace una verificación a la contraseña que el administrador ingresó en la ventana emergente de confirmación de edición de una 
                 * bitácora en la vista binnacleInfoCanvas.php (en el bloque if de $_GET["homeAction"] === "editBinnacle"), lo primero que se hace es verificar 
                 * si el array $errors_arr con indice "adminContrasena" esté vacía*/
                if(empty($errorArr["adminContrasena"])){
                    /*Si el campo adminContrasena quedó excento en el método verifyPostData, entonces se crea una instancia de la clase Usuarios y se le añade a 
                     * sus propiedades privadas $alias y $contrasena el valor del indice "Alias" de la sesión del usuario en el caso de la propiedad $alias con 
                     * un setter y el campo "adminContrasena" en el caso de la propiedad $contrasena con un setter, finalmente, se utiliza el método AdminPwdConfirmation 
                     * del objeto de Usuarios, se inicializa la variable $is_adminPWD con lo que devuelve ese método*/
                    $user_obj = new Usuarios();
                    $user_obj->setAlias($_SESSION["identity"]["Alias"]);
                    $user_obj->setContrasena($_POST["adminContrasena"]);
                    $is_adminPWD = $user_obj->AdminPwdConfirmation();
                    if(empty($is_adminPWD)){
                        /*$is_adminPWD puede contener un valor booleano true o false, si $is_adminPWD es false se añadirá el indice "adminPWDRejected" al array $errors_arr 
                         * el cual contiene un mensaje notificando al usuario que la contraseña que insertó no es valida*/
                        $errorArr["adminPWDRejected"] = "Administrador, la "
                                ."contraseña ingresada no coincide con el que "
                                ."se tiene en la base de datos, en dado caso "
                                ."que ingresara su contraseña correctamente, "
                                ."en ese caso entonces se cortó la conexión a "
                                ."la base de datos";
                    }
                }
                
                if(sizeof($errorArr) === 0){
                    /*Si $errorArr es un array vacío entonces se entra este bloque de código. Las Bitacorás dependiendo de su estatus tendrán disponible 
                     * algunos campos en la edición; si la bitácora tiene el estatus "en proceso" va a tener disponible estos campos para la edición: "fechaInicio", 
                     * "usuario", "servicio" (si es que la bitacora es de servicio) y "precio"; si la bitácora tiene el estatus "falta confirmar", va a tener disponible 
                     * estos campos para la edición: "fechaInicio", "usuario", "servicio" (si es que la bitacora es de servicio), "seHizo", "observaciones" y "precio"; 
                     * si la bitácora tiene estatus "cancelado", va a tener disponible estos campos para la edición: "fechaInicio", "servicio" (si es que la bitacora es 
                     * de servicio) y "motivoCancelacion"; si la bitácora tiene estatus "finalizado", va a tener disponible estos campos para la edición: "fechaInicio", 
                     * "fechaFin", "servicio" (si es que la bitacora es de servicio), "seHizo", "observaciones" y "precio". Visto lo anteriormente mencionado, puede haber 
                     * campos no definidos en la edición de una bitácora dependiendo de su estatus (no todos los campos de una bitácora están disponibles en el formulario, 
                     * NOTA: CAMPOS PROPIOS DE LA BITÁCORA, NO DE OTRAS ENTIDADES COMO LA DE EMPRESAS, CLIENTES(CONTACTOS), EQUIPOS O TIPOS, AUNQUE SUS DATOS SE MUESTREN 
                     * EN UNA BITÁCORA, LA TABLA BITÁCORAS SOLO REQUIERE LAS RESPECTIVAS REFERENCIAS DE LOS REGISTROS DE ESTAS ENTIDADES), 
                     * por eso utilizamos la función propia de PHP isset, esta función identifica si una variable (en este caso un indice de $_POST) está o no definida, a 
                     * diferencia de la función empty, para esa función es lo mismo si la variable no está definida o si esa variable tiene un string vacío "" (Dato: si una 
                     * variable tiene un string vacío, la función isset dará true ya que la variable en si misma está definida, con un string vacío pero definida a final de 
                     * cuentas)*/
                    /*Antes de crear una instancia de la clase Bitacoras primero se efectuan dos operadores ternarios, si una bitácora está cancelada, a nivel de SQL se 
                     * utiliza el campo "Observaciones" para alojar el texto del motivo de la cancelación, el primer ternario evalua si existe el campo de formulario 
                     * "motivoCancelacion", si existe, entonces la variable $cancelation se inicializará con el valor que contiene el indice "motivoCancelacion" de post, 
                     * si el indice "motivoCancelacion" de post no está definida, entonces la variable $cancelation se iniciará con el valor null; el otro operador 
                     * ternario evalúa si existe el campo de formulario "observaciones", si existe, entonces la variable $hints se inicializará con el valor que contiene 
                     * el indice "observaciones" de post, si el indice "observaciones" de post no está definida, entonces la variable $hints se iniciará con el valor null*/
                    (isset($_POST["motivoCancelacion"])) ? $cancelation = trim($_POST["motivoCancelacion"]) : $cancelation = null;
                    (isset($_POST["observaciones"])) ? $hints = trim($_POST["observaciones"]) : $hints = null;
                    /*Se crea entonces la instancia de la clase Bitacoras, en el constructor consideramos todos los campos de una Bitácora, solo que se utilizan operadores 
                     * ternarios para determinar los valores de los parametros del constructor (hay que poner los posibles datos del post con su parametro de constructor 
                     * correspondiente, para más información sobre el posicionamiento de parametros del constructor consulta el archivo Bitacoras.php de la carpeta models)*/
                    $binn_obj = new Bitacoras(
                            (isset($_POST["usuario"])) ? trim($_POST["usuario"]) : null,
                            null,
                            (isset($_POST["servicio"])) ? trim($_POST["servicio"]) : null,
                            null,
                            (!empty($_POST["precio"])) ? trim($_POST["precio"]) : null,
                            (isset($_POST["seHizo"])) ? trim($_POST["seHizo"]) : null,
                            /*A nivel SQL, el campo "Observaciones" en un registro de la tabla Bitacoras sirve tambien para guardar el texto de motivo de cancelación, con empty 
                             * verificamos si $cancelation no está vacío (no tiene el valor null) con un ternario (el campo de formulario "motivoCancelacion" no debe estár 
                             * vacío, así lo determina el método estatico verifyPostData, si embargo, ese campo de formulario puede no existir, en ese caso, la variable 
                             * $cancelation se inicializa con valor null, la función empty da true si esa variable tiene ese valor), si la condición da true, utilizamos la 
                             * misma variable $cancelation, si la condición da false, se utilizará la variable $hints*/
                            (!empty($cancelation)) ? $cancelation : $hints,
                            $_POST["fechaInicio"],
                            (isset($_POST["fechaFin"])) ? $_POST["fechaFin"] : null,
                            $_POST["estatus"]
                    );
                    /*Por ultimo le añadimos a la propiedad privada $id del objeto de Bitacoras el id de la bitácora a modificar contenida en el indice "bitacoraId" de post 
                     * por medio de un setter*/
                    $binn_obj->setId($_POST["bitacoraId"]);
                    try{
                        /*los métodos de petición de las clases de modelos utilizan una instancia de la clase PDO para conectarse a sql server, por eso se usa un 
                         * try-catch para intentar la conexión; updateBinnacleInfo se apoya de una estructura de control evaluando el valor de la propiedad privada $estatus 
                         * el cual se inicializó con lo que se pasó en el constructor, cada estatus tiene su propia oración sql, se utiliza una de estas oraciones de esas 
                         * cuatro posibilidades (solo hay 4 estatus de Bitácora), esa oración hace una petición de actualización de un registro de la tabla Bitacoras de acuerdo 
                         * a los valores que se pasaron en el constructor de la clase Bitacoras y el setter, se usa dentro de un if, si por alguna razón el usuario modificó 
                         * en el formulario el input hidden del id de la bitácora y coloca un id que no existe en la base de datos, el método updateBinnacleInfo devolverá 
                         * un false, para la función empty esto da true, por lo tanto, PHP redirigirá al usuario al mensaje de error del controlador ErroController y se 
                         * cortará el flujo del código de este contexto con exit*/
                        if(empty($binn_obj->updateBinnacleInfo())){
                            header("Location: ".base_url."home/?homeController=error&homeAction=index");
                            exit;
                        }
                        
                        $_SESSION["binnacleUpdateSucceed"] = "Se logró editar la bitácora con éxito";
                        
                    } catch (Exception $ex) {
                        /*Si entra en el catch, quiere decir que PDO devolvió una excepción, en este caso, creamos un indice de $_SESSION 
                         * llamado "binnacleUpdateEx" en el escribimos los posibles motivos del por qué no se pudo hacer la petición a la 
                         * base de datos con un lenguaje que pueda entender el usuario*/
                        $_SESSION["binnacleUpdateEx"] = "No se logró editar la bitácora, posible corte de conexión a la base de datos";
                        /*PHP redirigirá al usuario a la vista de reporte de bitácoras (binnaclesFilter.php), en el html de esa vista se utilizará la sesión 
                         * "binnacleUpdateEx" para mostrarle al usuario el mensaje de la excepción, tambíén se corta el flujo del código de este contexto con 
                         * exit*/
                        header("Location: ".base_url."home/?homeController=user&homeAction=binnaclesReport");
                        exit;
                    }
                }else{
                    /*Si $errorArr no es un array vacío entonces se inicializa la sesión "updateBinnacleInfoErr" con ese array*/
                    $_SESSION["updateBinnacleInfoErr"] = $errorArr;
                    /*PHP redirigirá al usuario a la vista de reporte de bitácoras (binnaclesFilter.php), en el html de esa vista se utilizará la sesión 
                     * "updateBinnacleInfoErr" para mostrarle al usuario los diferentes datos invalidos del formulario que envío, tambíén se corta el flujo 
                     * del código de este contexto con exit*/
                    header("Location: ".base_url."home/?homeController=user&homeAction=binnaclesReport");
                    exit;
                }
                /*PHP redirigirá al usuario a la vista de edición de una bitácora solo si la petición del método updateBinnacleInfo fue un éxito y en el html de 
                 * esta vista se utilizará la sesión "binnacleUpdateSucceed" notificando al usuario que se logró hacer los cambios de la bitácora*/
                header("Location: ".base_url."home/?homeController=user&homeAction=editBinnacle&homeId=".$binn_obj->getId());
                exit;
            }else{
                header("Location: ".base_url."home/");
                exit;
            }
        }
        
        /*Todos los métodos a partir de este comentario se cambia el campo Visibilidad de los registros de todas las entidades de esta aplicación web*/
        
        
        public function disableUser(){
            if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

                $errors_arr = Utils::verifyPostData($_POST, "enableOrDisable");
                if(empty($errors_arr["adminContrasena"])){
                    $user_obj = new Usuarios();
                    $user_obj->setAlias($_SESSION["identity"]["Alias"]);
                    $user_obj->setContrasena($_POST["adminContrasena"]);
                    $is_adminPWD = $user_obj->AdminPwdConfirmation();
                    if (empty($is_adminPWD)) {
                        $errors_arr["adminPWDRejected"] = "Administrador, la "
                                . "contraseña ingresada no coincide con el que "
                                . "se tiene en la base de datos, en dado caso "
                                ."que ingresara su contraseña correctamente, "
                                ."en ese caso entonces se cortó la conexión a "
                                ."la base de datos";
                        
                    }
                }
                
                if(sizeof($errors_arr) === 0){
                    
                    $user_obj->setId($_POST["usuarioId"]);
                    $user_obj->setVisibilidad($_POST["visibilidad"]);
                    try{
                        $user_obj->updateVisibilityById();
                        $_SESSION["disableUserSuccess"] = "Se desactivó al usuario con éxito".
                        $_SESSION["userNewPwd_userId"] = false;        
                    } catch (Exception $ex) {
                        $_SESSION["disableUserEx"] = "No se logró desactivar al usuario, posible corte "
                                . "de conexión a la base de datos";
                    }
                    
                }else{
                    $_SESSION["userPWDErr"] = $errors_arr;
                }
                header("Location: ".base_url."home/?homeController=user&homeAction=userNewPassword");
                exit; 
            }else{
                header("Location: ".base_url."home/");
                exit; 
            }
        }
        
        public function enableOrDisableEnterprise(){
            if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

                $errors_arr = Utils::verifyPostData($_POST, "enableOrDisable");
                
                if(sizeof($errors_arr) === 0){
                    $str_portion_one = ($_POST["visibilidad"] === "DISABLED") ? "desactivó":"activó";
                    $str_portion_two = ($_POST["visibilidad"] === "DISABLED") ? "desactivar":"activar";
                    $enter_obj = new Empresas();
                    $enter_obj->setId($_POST["empresaId"]);
                    $enter_obj->setVisibilidad($_POST["visibilidad"]);
                    try{
                        $enter_obj->updateVisibilityById();
                        $_SESSION["disableEnterpriseSuccess"] = "Se ".$str_portion_one." la empresa con éxito";       
                    } catch (Exception $ex) {
                        $_SESSION["disableEnterpriseEx"] = "No se logró ".$str_portion_two." la empresa, posible corte de conexión a la base de datos";
                    }
                    
                }else{
                    $_SESSION["disableEnterErr"] = $errors_arr;
                }
                header("Location: ".base_url."home/?homeController=user&homeAction=editEnterprise");
                exit; 
            }else{
                header("Location: ".base_url."home/");
                exit; 
            }
        }
        
        public function enableOrDisableContact(){
            if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

                $errors_arr = Utils::verifyPostData($_POST, "enableOrDisable");
                
                if(sizeof($errors_arr) === 0){
                    $str_portion_one = ($_POST["visibilidad"] === "DISABLED") ? "desactivó":"activó";
                    $str_portion_two = ($_POST["visibilidad"] === "DISABLED") ? "desactivar":"activar";
                    $cont_obj = new Contactos();
                    $cont_obj->setId($_POST["contactoId"]);
                    $cont_obj->setVisibilidad($_POST["visibilidad"]);
                    try{
                        $cont_obj->updateVisibilityById();
                        $_SESSION["disableContactSuccess"] = "Se "
                                .$str_portion_one." el contacto con ID ".$_POST["contactoId"]." con éxito";       
                    } catch (Exception $ex) {
                        $_SESSION["disableContactEx"] = "No se logró ".$str_portion_two." el contacto, posible corte "
                                . "de conexión a la base de datos";
                    }
                    
                }else{
                    $_SESSION["disableContactErr"] = $errors_arr;
                }
                header("Location: ".base_url."home/?homeController=user&homeAction=editEnterprise");
                exit; 
            }else{
                header("Location: ".base_url."home/");
                exit; 
            }
        }
        
        public function enableOrDisableType(){
            if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

                $errors_arr = Utils::verifyPostData($_POST, "enableOrDisable");
                
                if(sizeof($errors_arr) === 0){
                    $str_portion_one = ($_POST["visibilidad"] === "DISABLED") ? "desactivó":"activó";
                    $str_portion_two = ($_POST["visibilidad"] === "DISABLED") ? "desactivar":"activar";
                    $type_obj = new Tipos();
                    $type_obj->setId($_POST["tipoId"]);
                    $type_obj->setVisibilidad($_POST["visibilidad"]);
                    try{
                        $type_obj->updateVisibilityById();
                        $_SESSION["disableTypeSuccess"] = "Se "
                                .$str_portion_one." el tipo con ID ".$_POST["tipoId"]." con éxito";       
                    } catch (Exception $ex) {
                        $_SESSION["disableTypeEx"] = "No se logró ".$str_portion_two." el tipo, posible corte de conexión a la base de datos";
                    }
                    
                }else{
                    $_SESSION["disableTypeErr"] = $errors_arr;
                }
                header("Location: ".base_url."home/?homeController=user&homeAction=editTypes");
                exit; 
            }else{
                header("Location: ".base_url."home/");
                exit; 
            }
        }
        
        public function enableOrDisableDevice(){
            if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

                $errors_arr = Utils::verifyPostData($_POST, "enableOrDisable");
                
                if(sizeof($errors_arr) === 0){
                    $str_portion_one = ($_POST["visibilidad"] === "DISABLED") ? "desactivó":"activó";
                    $str_portion_two = ($_POST["visibilidad"] === "DISABLED") ? "desactivar":"activar";
                    $device_obj = new Equipos();
                    $device_obj->setId($_POST["equipoId"]);
                    $device_obj->setVisibilidad($_POST["visibilidad"]);
                    try{
                        $device_obj->updateVisibiliyById();
                        $_SESSION["disableDeviceSuccess"] = "Se "
                                .$str_portion_one." el equipo con ID ".$_POST["equipoId"]." con éxito";       
                    } catch (Exception $ex) {
                        $_SESSION["disableDeviceEx"] = "No se logró ".$str_portion_two." el equipo, posible corte "
                                . "de conexión a la base de datos";
                    }
                    
                }else{
                    $_SESSION["disableDeviceErr"] = $errors_arr;
                }
                header("Location: ".base_url."home/?homeController=user&homeAction=editDevice");
                exit; 
            }else{
                header("Location: ".base_url."home/");
                exit; 
            }
        }
        
        public function enableOrDisableBinn(){
            if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

                $errors_arr = Utils::verifyPostData($_POST, "enableOrDisable");
                
                if(sizeof($errors_arr) === 0){
                    $str_portion_one = ($_POST["visibilidad"] === "DISABLED") ? "desactivó":"activó";
                    $str_portion_two = ($_POST["visibilidad"] === "DISABLED") ? "desactivar":"activar";
                    $binn_obj = new Bitacoras();
                    $binn_obj->setId($_POST["bitacoraId"]);
                    $binn_obj->setVisibility($_POST["visibilidad"]);
                    try{
                        $binn_obj->updateVisibilityById();
                        $_SESSION["disableBinnSuccess"] = "Se "
                                .$str_portion_one." ls bitácora con ID ".$_POST["bitacoraId"]." con éxito";       
                    } catch (Exception $ex) {
                        $_SESSION["disableBinnEx"] = "No se logró ".$str_portion_two." la bitácora, posible corte "
                                . "de conexión a la base de datos";
                    }
                    
                }else{
                    $_SESSION["disableBinnErr"] = $errors_arr;
                }
                header("Location: ".base_url."home/?homeController=user&homeAction=binnaclesReport");
                exit; 
            }else{
                header("Location: ".base_url."home/");
                exit; 
            }
        }
        
       //--------------------------------------MÉTODOS DE GESTIÓN DE DATOS------------------------------------------
    }