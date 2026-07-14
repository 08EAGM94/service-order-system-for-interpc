<?php

/*
class Utils{

    //Indexes utils

    este helper es usado dentro de ambos index (finishing y home) para inicializar la sesión en el servidor

    public static function putSessionWithVerify(){
        if(empty($_SESSION)){
            session_start();
        }
    }

    
    Los métodos de controladores que importan vistas actualizan la sesión con la hora actual, si el usuario
    deja pasar más de 30 minutos dentro de alguna de estas vistas este helper caducará la sesión borrando 
    todos sus indices, lo usan tanto el index de home como el de finishing, el header location en este caso
    mandará al usuario a la vista del login.

    public static function sessionLifetime(){
        if (!empty($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > 1800) {
            // Caducar sesión
            session_unset();
            header("Location: ".base_url."home/");
            exit;
        }
    }

    Este helper utiliza nuestro objeto de inyección de dependencia para crear una instancia de la clase ErrorController
    y se utiliza el único método de este controlador.

    public static function showError($container){
        $error = $container->make('ErrorController');
        $error->index();
    }

    Este helper utiliza nuestro objeto de inyección de dependencia para crear una instancia de la clase UIController, se 
    utiliza constantes globales del archivo params.php que contienen el nombre de la clase y el unico método que contiene

    public static function defaultHomePage($container){
        $controllerName = default_homeController;
        $defaultAction = default_action;
        $controlador = $container->make($controllerName);
        $controlador->$defaultAction();
    }

    Este helper se encarga de importar banners de bienvenida (<header>) en el index de home, hay dos distintos en 
    la aplicación, para "user" o para "Admin", el helper evalúa si el usuario es administrador o no.

    public static function generateWelcomeBanner(){
        if (!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])) {
            require_once '../views/adminLayouts/menuSides/welcomeBanner.php';
        } else if (!empty($_SESSION["identity"])) {
            require_once '../views/userLayouts/menuSides/welcomeBanner.php';
        }
    }


    Este helper es usado dentro del index de home para importar el menú lateral para administradores, se evalua si la 
    sesión isAdmin está definida con true.

    public static function setAsideWithVerify(){
        if(!empty($_SESSION["isAdmin"])){
            require_once '../views/adminLayouts/menuSides/aside.php';
        }
    }

    Este helper es usado en las vistas para eliminar las sesiones inicializadas por los controladores, sesiones de éxito, 
    errores del usuario y excepciones, las vistas usan en su código los mensajes de estas sesiones para crear elementos 
    html que permiten la visualización de estos mensajes al usuario, al usar este helper, los mensajes desapareceran al 
    momento de recargar la página.

    public static function unsetFlagsSessions(){
        if(!empty($_SESSION["success"])){
            unset($_SESSION["success"]);
        }
        if(!empty($_SESSION["errors"])){
            unset($_SESSION["errors"]);
        }
        if(!empty($_SESSION["exceptions"])){
            unset($_SESSION["exceptions"]);
        }
    }

    Este helper se encarga de eliminar la sesión jsondecoded inicializada por el helper "ajaxProcedure".

    public static function unsetJsonDecodedSession(){
        if(!empty($_SESSION["jsondecoded"])){
            unset($_SESSION["jsondecoded"]);
        }
    }

    Este helper se encarga de eliminar la sesión binnFilterSession inicializada por el helper "setBinnFilterSessions".

    public static function unsetBinnFilterSession(){
        
        if(!empty($_SESSION["binnFilterSession"])){
            unset($_SESSION["binnFilterSession"]);
        }
    }

    Este helper se encarga de eliminar la sesión idSession inicializada por el helper "setIdSession".

    public static function unsetSearchFormsIdSession(){
        
        if(!empty($_SESSION["idSession"])){
            unset($_SESSION["idSession"]);
        }
    }

    Este helper se encarga de eliminar la sesión formSession inicializada al momento de interceptar el 
    post del formulario de confirmación de actividades en el index de finishing.

    public static function unsetFormSessions(){
        
        if(!empty($_SESSION["formSession"])){
            unset($_SESSION["formSession"]);
        }
    }

    Este helper se encarga de importar las vistas de reportes de dispositivos y el canvas de bitácoras en los 
    cuales, se activa la dependencia domPDF para generar los pdfs en base al html de estas posibles importaciones,
    por esta razón, este helper debe de ser invocado mucho antes de algún elemento html dentro del index de home.
    Evalúa el parametro get homeAction del url donde se está solicitando el reporte, si es igual a generateDevicesReport 
    y está definida la sesión idSession con su indice devicesReport_enterId,
    se va a generar un pdf con la información de los dispositivos de una empresa, pero si es igual a generateBinnacleReport, 
    existe el parametro get "id" y está definida la sesión binnFilterSession, se va a generar un pdf con la información de 
    la bitácora, dependiendo de su estatus, la estructura del reporte será diferente gracias al algoritmo de visualización 
    de elementos del DOM en el canvas de bitácoras. En ambas posibilidades se obtienen archivos de imagenes como recursos 
    visuales del pdf, el logo de la empresa en el caso de ambos reportes y las firmas de clientes y técnicos en el reporte 
    de bitácoras, no se optó por depender de la ruta relativa de estas imagenes dentro del html del pdf, en cambio, se 
    optó por obtener estos archivos dentro del algoritmo de este helper, serializarlos a base64 y usar las variables que 
    contienen esta serialización dentro del html del pdf.

    public static function reportPdfGenerator($dceDTO, $enterDTO, $binnDTO, 
        $dceService, $enterService, $binnService){
        
        if(!empty($_GET["homeAction"]) && $_GET["homeAction"] === "generateDevicesReport"){

            if(!empty($_SESSION["idSession"]["devicesReport_enterId"])){

                try{
                    
                    $dceDTO->empresa_id = $_SESSION["idSession"]["devicesReport_enterId"];
                    $enter_devices = $dceService->getChildrenByEnterprise($dceDTO);

                    if(sizeof($enter_devices) === 0)
                        throw new UnknownInDataBaseException("La empresa seleccionada no tiene dispositivos");

                    $enterDTO->enterprise_id = $_SESSION["idSession"]["devicesReport_enterId"];
                    $enter_info = $enterService->getInfo($enterDTO);
                    $path = "../assets/img/logo.png";
                    $data = file_get_contents($path);
                    $logo_base64 = "data:image/png;base64,".base64_encode($data);

                }catch(UnknownInDataBaseException $ex){
                    $_SESSION["exceptions"]["unknownInDataBaseEx"] = $ex->getMessage();
                }catch(EntityException $ex){
                    $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
                }catch(Exception $ex){
                    $_SESSION["exceptions"]["deviceReportException"] = "No se logró conseguir "
                                        ."la información para el reporte, posible corte "
                                        ."de conexión a la base de datos";
                }finally{

                    if(!empty($_SESSION["exceptions"])){
                        header("Location: ".base_url."home/?homeController=device&homeAction=devicesReport");
                        exit;
                    }

                    require_once '../views/adminLayouts/devicesPDF.php';
                    exit;
                }
            }
        }
        
        if(!empty($_SESSION["binnFilterSession"]) && !empty($_GET["homeAction"]) && !empty($_GET["homeId"])){
            
            if($_GET["homeAction"] === "generateBinnacleReport"){

                try{
                    $binnDTO->binnacle_id = $_GET["homeId"];
                    $binn_info = $binnService->getInfo($binnDTO);

                    if(empty($binn_info))
                        throw new UnknownInDataBaseException("El id de la bitácora no existe en la base de datos");

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
                    $with_iva = Utils::setIVAIfAmountIsNotNull($binn_info);
                    
                }catch(UnknownInDataBaseException $ex){
                    $_SESSION["exceptions"]["unknownInDataBaseEx"] = $ex->getMessage();
                }catch(EntityException $ex){
                    $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
                }catch(Exception $ex){
                    $_SESSION["exceptions"]["getBinnInfoEx"] = "No se logró obtener los "
                                    ."datos de la bitácora seleccionada, posible "
                                    ."corte de conexión a la base de datos";
                }finally{

                    if(!empty($_SESSION["exceptions"])){
                        header("Location: ".base_url."home/?homeController=binnacle&homeAction=binnaclesReport");
                        exit;
                    }

                    require_once '../views/adminLayouts/binnacleInfoCanvas.php';
                    exit;
                }
            }
        }
    }

    El helper ajaxProcedure solo lo utiliza el archivo home/index.php, este procedimiento maneja 
    * los JSON que JavaScript envió dentro de la comunicación asincrona con fetch, dependiendo del atributo 
    * del JSON enviado, PHP enviará los datos requeridos en forma de JSON gracias a json_encode, es importante que este 
    * método se invoque antes de que se importe elementos html en home/index.php, esto es debido a que se pueden colar 
    * strings html dentro de las respuestas de PHP, el objetivo de PHP es enviar solo arrays asociativos convertidos a JSON.
    Si la variable $input tiene el indice "number" la petición JS se hizo en la vista del listado de bitácoras a dar 
    seguimiento en el que existe un elemento select donde el usuario puede elegir la cantidad de elementos en pantalla,
    la sesión $_SESSION["jsondecoded"]["followUpNumKey"] será definida con ese valor y será usado en los parametros de 
    getAllInfo del adaptador primario CommonService de bitácoras el cual devuelve un array con los registros de 
    bitácoras fruto de la paginación dentro del indice "binns" y los controles del paginado en el indice "buttons";
    Si la variable $input tiene el indice "binnsFilterNumber" la petición JS se hizo en la vista de reporte de bitácoras
    donde se realizó una busqueda dentro de las opciones de filtrado y seleccionado un valor del select de elementos en pantalla
    a mostrar, el proceso de paginación es similar al caso de $input con indice "number" solo que la diferencia es que no se usa
    un dto (binnDTO) y se usa la sesión binnFilterSession en los parametros de getAllInfo del CommonService de bitácoras, en 
    ambos casos, el parametro url "homeAction" es el contexto donde se está haciendo las solicitudes http y sirve para indicar 
    que tipo de paginado se usará dentro del repositorio de bitácoras.
    Si $input tiene el indice "enterIdFromBinnFilter" la petición JS se hizo en la vista de reporte de bitácoras al momento de elegir
    un valor en el select de Empresas en las opciones de filtrado, se usan dtos de contacto y equipo para settear en sus respectivos
    atributos empresa_id el valor del indice enterIdFromBinnFilter, se usa el método getChildrenByEnterForSelect tanto de 
    EnterpriseChildrenService de contactos y de equipos, se obtendrán dos arrays de estas entidades de la base de datos de sus 
    respectivos registros vinculados al id de la empresa en cuestión, estos dos arrays serán guardados en sus respectivos indices
    de la sesión binnFilterSession para conservar los options de los select de contactos y equipos en la vista.
    Si $input tiene el indice "enterpriseId" la petición JS se hizo en la vista del formulario de nueva bitácora al momento de 
    seleccionar un valor en el select de empresas, el proceso es similar al caso del indice "enterIdFromBinnFilter" solo que aqui 
    los arrays no se guardan en indices de alguna sesión y de forma adicional se obtiene la información de la empresa por medio 
    del CommonService de empresas.
    Si $input tiene el indice "newContactEnterId" la petición JS se hizo en la vista de formulario de nuevo contacto al momento de 
    elegir un valor en el select de Empresas, se usa CommonService de empresas para obtener la información de la empresa en cuestión.
    Si $input tiene el indice "deviceId" la petición JS se hizo en la vista de formulario de nueva bitácora al momento de 
    elegir un valor en el select de Equipos, se usa EnterpriseChildrenService de equipos para obtener la información del equipo 
    en cuestión.
    En todas las evaluaciones se interceptan excepciones, EntityException en el caso de que se usen dtos y Exception en el caso de 
    que PDO lance una excepción.

    public static function ajaxProcedure($contDTO, $dceDTO, $enterDTO, $binnDTO, 
        $contService, $dceService, $enterService, $binnService){

        if(!empty($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            
            $data = file_get_contents("php://input");
            $input = json_decode($data, true);

            if(!empty($input["number"])){

                $binnDTO->usuario_id = $_SESSION["identity"]["Id"];
                $_SESSION["jsondecoded"]["followUpNumKey"] = intval($input["number"]);

                try {
                    $pagination_arr = $binnService->getAllInfo(
                        $binnDTO, 
                        $_SESSION["jsondecoded"]["followUpNumKey"],
                        null,
                        $_GET["homeAction"]
                        );
                }catch(EntityException $ex){
                    $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
                }catch (Exception $ex) {
                    $_SESSION["exceptions"]["followUpQueryEx"] = "Se generó un error al "
                                ."interactuar con la base de datos para la "
                                ."obtención de datos necesarios crear "
                                ."la paginación de seguimiento de bitácoras, "
                                ."lo más probable es que se haya cortado la "
                                ."conexión a la base de datos";
                }finally{

                    if(!empty($_SESSION["exceptions"])){
                        header("Location: ".base_url."home/");
                        exit;
                    }

                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($pagination_arr);
                    exit;
                }  
            }
            
            if(!empty($input["binnsFilterNumber"])){

                $_SESSION["jsondecoded"]["binnsReportNumKey"] = intval($input["binnsFilterNumber"]);
                
                try {    
                    $pagination_arr = $binnService->getAllInfo(
                        null,
                        $_SESSION["jsondecoded"]["binnsReportNumKey"],
                        $_SESSION["binnFilterSession"],
                        $_GET["homeAction"]
                    );
                } catch (Exception $ex) {
                    Utils::unsetBinnFilterSession();
                    $_SESSION["exceptions"]["binnsRowsPaginationEx"] = "Se generó un "
                                . "error interactuando con la base de datos "
                                . "en cuanto a la generación de paginación, posible falta de conexión";
                }finally{

                    if(!empty($_SESSION["exceptions"])){
                        header("Location: " . base_url . "home/user/?homeController=binnacle&homeAction=binnaclesReport");
                        exit;
                    }

                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($pagination_arr);
                    exit;
                }  
            }
            
            if(!empty($input["enterIdFromBinnFilter"])){
                
                $enter_id = $input["enterIdFromBinnFilter"];

                try{
                    $contDTO->empresa_id = $enter_id;
                    $dceDTO->empresa_id = $enter_id;
                    $dces_arr = [
                        "enterContactsToBinnsFilter"=> 
                            $contService->getChildrenByEnterForSelect($contDTO),
                        "enterDcesToBinnsFilter"    => 
                            $dceService->getChildrenByEnterForSelect($dceDTO)
                    ];
                    $_SESSION["binnFilterSession"]["enterpriseRelatedContacts"] = 
                        $dces_arr["enterContactsToBinnsFilter"];
                    $_SESSION["binnFilterSession"]["enterpriseRelatedDevices"] = 
                        $dces_arr["enterDcesToBinnsFilter"];
                }catch(EntityException $ex){
                    $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
                }catch (Exception $ex) {
                    $_SESSION["exceptions"]["dces_arrException"] = "Se generó un "
                                ."error interactuando con la base de datos "
                                ."en cuanto a la generación de las opciones de selección "
                                ."de dispositivos en el reporte de bitácoras, "
                                ."lo más probable es que se haya cortado la conexión a la base de datos";
                }finally{
                    if(!empty($_SESSION["exceptions"])){
                        header("Location: ".base_url."home/?homeController=binnacle&homeAction=binnaclesReport");
                        exit;
                    }

                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($dces_arr);
                    exit;
                }
            }
            
            if(!empty($input["enterpriseId"])){
                
                $enter_id = $input["enterpriseId"];
                
                try{
                    $enterDTO->enterprise_id = $enter_id;
                    $contDTO->empresa_id = $enter_id;
                    $dceDTO->empresa_id = $enter_id;
                    $enterprise_arr = [
                        "entInfo"           => 
                            $enterService->getInfo($enterDTO),
                        "enterpriseContacts"=> 
                            $contService->getChildrenByEnterForSelect($contDTO),
                        "enterpriseDevices" => 
                            $dceService->getChildrenByEnterForSelect($dceDTO)
                    ];
                }catch(EntityException $ex){
                    $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
                }catch (Exception $ex) {
                    $_SESSION["exceptions"]["enterpriseArrayException"] = "Se generó un "
                            ."error interactuando con la base de datos "
                            ."en cuanto a la generación de datos automaticos de una empresa "
                            ."en el formulario de registro de bitácoras, "
                            ."lo más probable es que se haya cortado la conexión a la base de datos";
                }finally{
                    if(!empty($_SESSION["exceptions"])){
                        header("Location: ".base_url."home/");
                        exit;
                    }

                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($enterprise_arr);
                    exit;
                }
            }
            
            if(!empty($input["newContactEnterId"])){
                
                $enter_id = $input["newContactEnterId"];

                try{
                    $enterDTO->enterprise_id = $enter_id;
                    $enterprise_arr = [
                        "entInfoForContactForm" => $enterService->getInfo($enterDTO)
                    ];
                }catch(EntityException $ex){
                    $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
                }catch (Exception $ex) {
                    $_SESSION["exceptions"]["enterpriseArrayException"] = "Se generó un "
                            ."error interactuando con la base de datos "
                            ."en cuanto a la generación de datos automaticos de una empresa "
                            ."en el formulario de registro de contactos, "
                            ."lo más probable es que se haya cortado la conexión a la base de datos";
                }finally{
                    if(!empty($_SESSION["exceptions"])){
                        header("Location: ".base_url."home/");
                        exit;
                    }

                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($enterprise_arr);
                    exit;
                }
            }
            
            if(!empty($input["deviceId"])){
                
                $device_id = $input["deviceId"];

                try{
                    $dceDTO->device_id = $device_id;
                    $device_arr = $dceService->getChild($dceDTO);
                }catch(EntityException $ex){
                    $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
                }catch (Exception $ex) {
                    $_SESSION["exceptions"]["deviceArrayException"] = "Se generó un "
                            ."error interactuando con la base de datos "
                            ."en cuanto a la generación de datos automaticos de dispositivos "
                            ."en el formulario de registro de bitácoras, "
                            ."lo más probable es que se haya cortado la conexión a la base de datos";
                    header("Location: ".base_url."home/");
                    exit;
                }finally{
                    if(!empty($_SESSION["exceptions"])){
                        header("Location: ".base_url."home/");
                        exit;
                    }

                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($device_arr);
                    exit;
                }
            }            
        }
    }

    Este helper se usa solo en el index de finishing para interceptar el post del formulario de confirmación de actividades
    de una bitácora o en el formulario de edición de firma de un usuario, si post tiene el indice binnId quiere decir que 
    viene del formulario de confirmación de actividades, en este caso se evaluá si el indice binnId es númerico y si el 
    indice userId es identico al indice Id de identity, en caso contrario, el helper invocará un header location que enviará 
    al usuario a la raiz del index de home, si es que pasa la evaluación los indices del post que contiene cadenas de texto
    propensas a tener acentos pasan por un proceso de reemplazo de estos caracteres especiales por un caracter valido para 
    los href en el DOM, en este caso el número 0, el resultado del reemplazo se guardarán en sus respectivas variables, estas
    variables finalmente se usarán para dar forma al indice dataSelectionForSigns de la sesión formSession.
    si post tiene el indice oldTechSign quiere decir que viene del formulario de edición de firma, en este caso se evaluá si 
    el indice userId es númerico, en caso contrario, el helper invocará un header location que enviará al usuario a la raiz 
    del index de home, si es que pasa la evaluación el proceso es similar al caso del indice binnId en el post.

    public static function setDataSelectionForSigns(){
        
        if (sizeof($_POST) > 0 && !empty($_POST["binnId"])) {
            
            if(preg_match('/[0-9]+/', $_POST["binnId"]) &&
                $_POST["userId"] === $_SESSION["identity"]["Id"]){
                
                $cliente_nombre = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["clientName"]);
                $commercial_nombre = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["clientEntName"]);
                $usuario_nombre = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["userName"]);
                $usuario_ape = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["userSurname"]);
                
                $_SESSION["formSession"]["dataSelectionForSigns"] = array(
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
        
        if(sizeof($_POST) > 0 && !empty($_POST["oldTechSign"])){
            
            if(preg_match('/[0-9]+/', $_POST["userId"])){
                
                    $usuario_nombre = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["userName"]);
                    $usuario_ape = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["userSurname"]);

                    $_SESSION["formSession"]["dataSelectionForSigns"] = array(
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

    Este helper se usa en el index de finishing para interceptar el FormData de JS en la vista del pad de firmas 
    el cual PHP interpreta como un archivo descargado ($_FILE),
    $_FILE en este contexto contiene el nombre del archivo png de la firma, el tipo del archivo y los datos de este png guardados en un 
    archivo temporal, el indice de esta variable superglobal fue el key que se le dio al FormData de JS, puede haber 3 indices, newTechSign,
    techSign y cliSign, repectivamente en el caso de una edición de firma de un técnico, la firma de un técnico en la confirmación de 
    actividades de una bitácora y la firma del cliente para finalizar una bitácora. En el caso particular de la edición de firma, se elimina
    la firma antigua del técnico usando la ruta relativa destinada para las firmas, en caso de que no se encuentre la firma, el helper 
    eliminará los datos de la sesión de formSession y redirigirá al usuario a la raíz del index de home; en las tres posibilidades se 
    extrae el array del respectivo indice de $_FILES en una variable y en otra el nombre del archivo, se evalua si existe la carpeta
    destinado a las firmas dentro de la ubicación de la carpeta finishing, si no lo está se generará la carpeta con permisos de escritura
    incluidos, finalmente se mueve los datos del archivo temporal a la ruta relativa de la carpeta para firmas y el nombre final del 
    archivo y se inicializa $_SESSION["formSession"]["techSignature"] o $_SESSION["formSession"]["clientSignature"] con el nombre del 
    archivo de la firma ya guardado en donde debería de ir, en el caso de que se haya guardado la firma de un cliente se le enviará dentro 
    de la comunicación asincrona con JS el mensaje de que la firma del cliente a sido guardado con éxito, mensaje que será visualizado 
    en la vista del pad de firma del cliente.

    public static function saveSignaturesFiles(){
        
        if(isset($_FILES["newTechSign"])){
            
            if(!unlink("uploads/firmas/".$_SESSION["formSession"]["dataSelectionForSigns"]["oldTechSign"])){
                $_SESSION["exceptions"]["unlinkTechSignEx"] = "La supuesta firma del técnico no se encontró en la aplicación web";
                Utils::unsetFormSessions();
                header("Location: " . base_url . "home/");
                exit;
            }
            
            $tech_sign_file = $_FILES["newTechSign"];
            $technician_name = $tech_sign_file["name"];
            
            if(!is_dir("uploads/firmas")){
                mkdir("uploads/firmas", 0777, true);
            }
            move_uploaded_file($tech_sign_file["tmp_name"], 
                        "uploads/firmas/".$technician_name);
            $_SESSION["formSession"]["techSignature"] = $technician_name;
        }
        
        if(isset($_FILES["techSign"])){
            $tech_sign_file = $_FILES["techSign"];
            $technician_name = $tech_sign_file["name"];
            if(!is_dir("uploads/firmas")){
                mkdir("uploads/firmas", 0777, true);
            }
            move_uploaded_file($tech_sign_file["tmp_name"], 
                        "uploads/firmas/".$technician_name);
            $_SESSION["formSession"]["techSignature"] = $technician_name;
        }
        
        if(isset($_FILES["cliSign"])){
            $cli_sign_file = $_FILES["cliSign"];
            $client_name = $cli_sign_file["name"];
            if(!is_dir("uploads/firmas")){
                mkdir("uploads/firmas", 0777, true);
            }
            move_uploaded_file($cli_sign_file["tmp_name"], 
                        "uploads/firmas/".$client_name);
            $_SESSION["formSession"]["clientSignature"] = $client_name;
            
            if((!empty($_SERVER['HTTP_X_REQUESTED_WITH'])                           && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')){                
                echo "Firma del cliente guardado con éxito";
                exit;
            }
        }
    }

    Este helper es usado en el index de finishing justo despues de haber guardado la firma del técnico, hace dos funciones, el primero
    evalúa si es que está definido $_SESSION["idSession"]["userSign_userId"], el cual solo se settea cuando el usuario de la aplicación 
    es administrador, entonces el dto de usuarios se settea con el valor de esta sesión, en caso de que esta sesión no exista, se usará
    el indice Id de la sesión identity, posteriormente se usa la sesión $_SESSION["formSession"]["techSignature"] (el cual contiene el 
    nombre del archivo de la firma del técnico) para settear la propiedad firma del dto de usuario, finalmente el dto es usado por el
    adaptador primario UserService para actualizar el campo "Firma" del registro en cuestión en la base de datos, en caso de que no
    se complete este proceso quiere decir que se arrojó tanto una excepción tipo EntityException o Exception contemplando lo que arroja
    la clase PDO, en ese caso el helper eliminará el archivo de la firma del técnico, eliminará la sesión formSession y redirigirá al 
    usuario a la raíz del index de home, en caso de que el proceso fue un éxito se entra a otro try-catch donde se hace el proceso de 
    actualización de la sesión identity; si $_SESSION["idSession"]["userSign_userId"] esta definido y es identico a 
    $_SESSION["identity"]["Id"], entonces se usará el CommonService para usuarios para obtener la información del usuario en custión y
    actualizar los datos de la sesión identity, en caso de que la sesión idSession no esté definida se usará el indice Id de identity
    para usar el servicio; este proceso puede arrojar una excepción de PDO, en ese caso se eliminaría la sesión formSession y
    redirigiría al usuario a la raíz del index de home, en caso de que el proceso sea satisfactorio se le enviará dentro de la 
    comunicación asincrona con JS el mensaje de que la firma del técnico a sido guardado con éxito, mensaje que será visualizado en la
    vista del pad de firma del técnico.

    public static function updateUserWithSignature($usrDTO, $usrSignService, $usrService){
        
        if(!empty($_SESSION["formSession"]["techSignature"])){

            try {

                (!empty($_SESSION["idSession"]["userSign_userId"])) ? 
                $usrDTO->user_id = $_SESSION["idSession"]["userSign_userId"] : 
                $usrDTO->user_id = $_SESSION["identity"]["Id"];
                
                $usrDTO->firma = $_SESSION["formSession"]["techSignature"];
                $usrSignService->insertSignature($usrDTO);

            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch (Exception $ex) {
                
                $_SESSION["exceptions"]["techSignInsertException"] = "No se logró guardar "
                        ."la firma del técnico en la base de datos, se cortó "
                        ."la conexión a la base de datos";
                
            }finally{
                if(!empty($_SESSION["exceptions"])){
                    if(!unlink("uploads/firmas/".$_SESSION["formSession"]["techSignature"])){
                            $_SESSION["exceptions"]["unlinkTechSignEx"] = "La supuesta firma del técnico no se encontró en la aplicación web";
                    }
                    Utils::unsetFormSessions();
                    header("Location: ".base_url."home/");
                    exit;
                }
            
                try{
                    if(!empty($_SESSION["idSession"]["userSign_userId"])){
                        if($_SESSION["idSession"]["userSign_userId"] === $_SESSION["identity"]["Id"]) 
                            $_SESSION["identity"] = $usrService->getInfo($usrDTO);
                    }else{
                        $_SESSION["identity"] = $usrService->getInfo($usrDTO);
                    }
                } catch (Exception $ex) {
                    $_SESSION["exceptions"]["identitySessionUpdateEx"] = "No se logró actualizar la "
                            ."sesión de la información del usuario, posible corte de "
                            ."conexión a la base de datos, se recomienda no generar "
                            ."firma del usuario en una bitácora una vez establecido "
                            ."conexión a la base de datos, cierre sesión y vuelva entrar "
                            ."para tener una sesión de datos de usuario correcta";
                    Utils::unsetFormSessions();
                }finally{
                    if(!empty($_SESSION["exceptions"])){
                        header("Location: ".base_url."home/");
                        exit;
                    }

                    if((!empty($_SERVER['HTTP_X_REQUESTED_WITH'])                           && 
                        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') &&
                        empty($_SESSION["clientSignature"])){
                        echo "Firma de ".$_SESSION["formSession"]["dataSelectionForSigns"]["userName"]." se ha guardado con éxito";
                        exit;
                    }
                }
            }  
        }
    }

    //controllers utils

    Este helper es usado en el proceso de login para identificar si el usario es administrador, si es el caso, se definirá la sesión
    isAdmin con true

    public static function setAdminWithVerify(){
        if(!empty($_SESSION["identity"])){
            if ($_SESSION["identity"]["Privilegio"] === "Admin") {
                $_SESSION["isAdmin"] = true;
            }
        }
    }

    Este helper es usado para definir la sesión idSession con el valor enviado por los formularios de busqueda de multiples 
    controladores, el parametro url homeAction indica los métodos que utilizan este helper, una vez definido idSession con su 
    respectivo indice, se utiliza la variable superglobal post para construir parametros url para integrarlos en el url del header
    location en cuestión. idSession es usado por algunos controladores para hacer peticiones a la base de datos con ayuda de 
    nuestras dependencias de servicios y dar forma a sus respectivas vistas luego de efectuar una busqueda.

    public static function setIdSession(){

        if($_GET["homeAction"] === "editSign" && !empty($_SESSION["isAdmin"]) && 
            sizeof($_POST) > 0){ 
            
            $_SESSION["idSession"]["userSign_userId"] = (!empty($_POST["usuarios"]) && (!preg_match('/[A-Za-z]+/', $_POST["usuarios"]) ||
                !preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $_POST["usuarios"]))) ? $_POST["usuarios"] : false;
        
            $get_params = http_build_query($_POST);
            header("Location: ". base_url."home/?homeController=user&homeAction=editSign&".$get_params);
            exit;
        }

        if($_GET["homeAction"] === "userNewPassword" && !empty($_SESSION["isAdmin"]) && 
            sizeof($_POST) > 0){
            $_SESSION["idSession"]["userNewPwd_userId"] = (!empty($_POST["usuarios"]) && (!preg_match('/[A-Za-z]+/', $_POST["usuarios"]) ||
                !preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $_POST["usuarios"]))) ? $_POST["usuarios"] : false;
            
            $get_params = http_build_query($_POST);
            header("Location: ". base_url."home/?homeController=user&homeAction=userNewPassword&".$get_params);
            exit;
        }

        if($_GET["homeAction"] === "index" && !empty($_SESSION["isAdmin"]) && 
            sizeof($_POST) > 0){

            if($_GET["homeController"] === "enterprise"){
                $_SESSION["idSession"]["enterpriseEdit_enterId"] = (!empty($_POST["empresas"]) && (!preg_match('/[A-Za-z]+/', $_POST["empresas"]) ||
                    !preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $_POST["empresas"]))) ? $_POST["empresas"] : false;
                
                $get_params = http_build_query($_POST);
                header("Location: ". base_url."home/?homeController=enterprise&homeAction=index&".$get_params);
                exit;
            }
        }

        if($_GET["homeAction"] === "editDevice" && !empty($_SESSION["isAdmin"]) && 
            sizeof($_POST) > 0){
            $_SESSION["idSession"]["devicesEdit_enterId"] = (!empty($_POST["empresas"]) && (!preg_match('/[A-Za-z]+/', $_POST["empresas"]) ||
                !preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $_POST["empresas"]))) ? $_POST["empresas"] : false;
            
            $get_params = http_build_query($_POST);
            header("Location: ". base_url."home/?homeController=device&homeAction=editDevice&".$get_params);
            exit;
        }

        if($_GET["homeAction"] === "devicesReport" && !empty($_SESSION["isAdmin"]) && 
            sizeof($_POST) > 0){
            $_SESSION["idSession"]["devicesReport_enterId"] = (!empty($_POST["empresas"]) && (!preg_match('/[A-Za-z]+/', $_POST["empresas"]) ||
                !preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $_POST["empresas"]))) ? $_POST["empresas"] : false;
            
            $get_params = http_build_query($_POST);
            header("Location: ". base_url."home/?homeController=device&homeAction=devicesReport&".$get_params);
            exit;
        }
    }

    Este helper es usado por algunos controladores en métodos propensos a hacer un cambio critico en la base de datos,
    verifica si la contraseña del administrador que estableció una sesión pasado en el formulario sea identico al que 
    se tiene en la base de datos. si no es el caso, el helper devolverá un array con el indice "adminPWDRejected", en 
    caso satisfactorio, mandará un array vacío.

    public static function setAdminVerification($dto, $service){
        $errorArr = [];
        $dto->admin_nickname = $_SESSION["identity"]["Alias"];
        $is_adminPWD = $service->adminPwdConfirmation($dto);
        if(empty($is_adminPWD)){
            $errorArr = ["adminPWDRejected" => "Administrador, la "
                    ."contraseña ingresada no coincide con el que "
                    ."se tiene en la base de datos, en dado caso "
                    ."que ingresara su contraseña correctamente, "
                    ."en ese caso entonces se cortó la conexión a "
                    ."la base de datos"];
        }
        $dto->admin_nickname = null;
        $dto->admin_pwd = null;
        return $errorArr;
    }

    Este helper se usa para settear atributos del dto de bitácoras destinadas para definir la sesión binnFilterSession,
    intercepta la variable superglobal post y sus indices son asignados a los respectivos atributos del dto de bitácoras,
    después, el dto es evaluado por un método estatico de una de nuestras clases de 
    varificación de datos, si el dto pasa todas las verificaciones, la variable $errorsArr será un array vacío.
    En caso de que $errorsArr no sea un array vacío se lanzará la excepción UnauthorizedDataException, caso contrario, se usará
    post para convertir sus datos en parametros url y se definirá la sesión binnFilterSession y sus indices, indices que aluden
    a las propiedades del dto de bitácoras, algunos indices pueden ser null (lo que vendría a ser campos opcionales), finalmente,
    los parametros get se integran a la url del header location en cuestión. binnFilterSession se usa en uno de los parametros
    opcionales del método getAllInfo del CommonService de bitácoras en el caso de que se esté solicitando paginación en la url 
    del reporte de bitácoras.

    public static function setBinnFilterSessions($dto){

        if(sizeof($_POST) > 0){
            $dto->empresa_id = $_POST["empresaId"];
            $dto->contacto_id = $_POST["contactoId"];
            $dto->actividad = $_POST["servicioOEquipo"];
            $dto->equipo_id = (isset($_POST["equipoId"])) ? $_POST["equipoId"] : '';
            $dto->estatus = $_POST["estatus"];
            $dto->dates_type = $_POST["startedOrEnded"];
            $dto->left_day = $_POST["leftDay"];
            $dto->right_day = $_POST["rightDay"];
            $dto->visibilidad = $_POST["visible"]; 
            $errorsArr = BinnacleVerifications::verifyingFilterOptions($dto);

            if(sizeof($errorsArr) > 0)
                throw new UnauthorizedDataException("filtrado no valido, si elegiste solo una fecha, hay que indicar la otra fecha para calcular el rango de tiempo");
            
            $queryString = http_build_query($_POST);    
            
            $_SESSION["binnFilterSession"]["Empresa_id"] = (!empty($dto->empresa_id)) ? $dto->empresa_id : null;
            $_SESSION["binnFilterSession"]["Contacto_id"] = (!empty($dto->contacto_id)) ? $dto->contacto_id : null;
            $_SESSION["binnFilterSession"]["IsServiceOrDevice"] = $dto->actividad;
            $_SESSION["binnFilterSession"]["Equipo_id"] = (!empty($dto->equipo_id)) ? $dto->equipo_id : null;
            $_SESSION["binnFilterSession"]["Estatus"] = $dto->estatus;
            $_SESSION["binnFilterSession"]["StartedOrEnded"] = $dto->dates_type;
            $_SESSION["binnFilterSession"]["LeftDay"] = (!empty($dto->left_day)) ? $dto->left_day : null;
            $_SESSION["binnFilterSession"]["RightDay"] = (!empty($dto->right_day)) ? $dto->right_day : null;
            $_SESSION["binnFilterSession"]["Visible"] = $dto->visibilidad;

            header("Location:". base_url."home/?homeController=binnacle&homeAction=binnaclesReport&" .$queryString);
            exit;
        }
    }

    Este helper es usado cuando se esta solicitando información de una bitácora, evalúa si el indice "Monto" del array producto 
    de la solicitud no sea falsy, en ese caso se harán los calculos del impuesto, puede devolver el string producto de la operación,
    o puede devolver null.

    public static function setIVAIfAmountIsNotNull($arr){
        $iva_format = null;
        if(!empty($arr["Monto"])){
            todo lo que la base de datos devuelve es información en forma de string, incluyendo valores numericos, entonces
                    * si Monto no es igual a "" entonces tiene un numero flotante, lo que se hace es convertir el indice "Monto" en un valor flotante y multiplicar 
                    * ese valor por 1.16 (calculo del IVA en México), el resultado se guardará en la variable $iva_result
                $iva_result = floatval($arr["Monto"]) * 1.16;
                en la variable $with_iva contendrá la cantidad contenida en $iva_result pero configurada para que este solo 
                    * tenga dos decimales, la variable $binn_info (y si entra en este if, tambien la variable $with_iva) lo 
                    * utilizará la vista binnacleInfoCanvas.php en el bloque if de $_GET["homeAction"] === "showBinnacle"
                $iva_format = sprintf("%.2f", $iva_result);
        }
        return $iva_format;
    }

    Este helper es principalmente usado en situaciones donde la url tenga el parametro id y se modifique con algun Id de una 
    bitácora en el controlador FollowupformController, en este contexto, las bitácoras con estatus "cancelado" o "finalizado"
    no deben ser accedidas.

    public static function isAuthorizedBinnacle($arr){
        if($arr['Estatus'] === 'finalizado' || $arr['Estatus'] === 'cancelado')
            throw new UnauthorizedDataException('La bitácora que se intenta acceder está finalizada o cancelada');
    }
}
*/

beforeEach(function(){
    $this->container = testContainerFactory();
    $this->base_url = 'http://localhost:8081/SOSv5/service-order-system/';
    $this->buttons = '<div class="Zebra_Pagination"><ol class="pagination"><li class="page-item disabled"><a href="javascript:void(0)" class="page-link">&laquo;</a></li><li class="page-item active"><a href="/SOSv5/service-order-system/home" class="page-link">1</a></li><li class="page-item disabled"><a href="javascript:void(0)" class="page-link">&raquo;</a></li></ol></div>';
});

afterEach(function(){
    $_SESSION = [];
    $_POST = [];
    $_GET = [];
    $_SERVER = [];
    cleanTable($this->container->make('SOSTestDatabase'), "Bitacoras");
    cleanTable($this->container->make('SOSTestDatabase'), "Contactos");
    cleanTable($this->container->make('SOSTestDatabase'), "Equipos");
    cleanTable($this->container->make('SOSTestDatabase'), "Empresas");
    cleanTable($this->container->make('SOSTestDatabase'), "Tipos");
    cleanTable($this->container->make('SOSTestDatabase'), "Usuarios");
});

test('prueba método setAdminVerification, caso satisfactorio', function(){
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $dto = $this->container->make('usrDTO');
    $dto->admin_pwd = "elRojoQueNoEsRojo";
    $error_arr = Utils::setAdminVerification($dto, $this->container->make('usrParticularSrv'));

    expect(sizeof($error_arr))->toBeLessThanOrEqual(0);
});

test('prueba método setAdminVerification, caso contraseña de administrador incorrecta', function(){
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $dto = $this->container->make('usrDTO');
    $dto->admin_pwd = "qwertyuiop";
    $error_arr = Utils::setAdminVerification($dto, $this->container->make('usrParticularSrv'));

    expect($error_arr)->toHaveLength(1);
});

test('prueba método updateUserWithSignature, caso usuario "user"', function(){
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'user'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
    $_SESSION["formSession"] = [
        "techSignature" => "new_test_sign.png",
        "dataSelectionForSigns" => ["userName" => "Edgar Allan"]
    ];

    $value = mockUpdateUserWithSignature($this->container);

    expect($_SESSION["identity"]["Firma"])->toBe("new_test_sign.png");
    expect([
        "Id" => $_SESSION["identity"]["Id"],
        "Firma" => "new_test_sign.png"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), 'Usuarios');
    expect($value)->toBe("Firma de ".$_SESSION["formSession"]["dataSelectionForSigns"]["userName"]." se ha guardado con éxito");
});

test('prueba método updateUserWithSignature, caso usuario "admin"', function(){
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
        $getting_users = $this->container->make('usrService')->getAllInfo();
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
    $_SESSION["idSession"]["userSign_userId"] = $getting_users[2]["Id"];
    $_SESSION["formSession"] = [
        "techSignature" => "new_user_test_sign.png",
        "dataSelectionForSigns" => ["userName" => $getting_users[2]["Nombre"]." ".$getting_users[2]["Apellidos"]]
    ];

    $value = mockUpdateUserWithSignature($this->container);

    expect($_SESSION["identity"]["Id"] === $getting_users[2]["Id"])->toBeFalse();
    expect([
        "Id" => $getting_users[2]["Id"],
        "Firma" => "new_user_test_sign.png"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), 'Usuarios');
    expect($value)->toBe("Firma de ".$_SESSION["formSession"]["dataSelectionForSigns"]["userName"]." se ha guardado con éxito");
});

test('prueba método updateUserWithSignature, caso usuario "admin" autoactualización de firma', function(){
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
        $getting_users = $this->container->make('usrService')->getAllInfo();
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
    $_SESSION["idSession"]["userSign_userId"] = $_SESSION["identity"]["Id"];
    $_SESSION["formSession"] = [
        "techSignature" => "new_test_self_sign.png",
        "dataSelectionForSigns" => ["userName" => $_SESSION["identity"]["Nombre"]." ".$_SESSION["identity"]["Apellidos"]]
    ];

    $value = mockUpdateUserWithSignature($this->container);

    expect($_SESSION["identity"]["Firma"])->toBe("new_test_self_sign.png");
    expect([
        "Id" => $_SESSION["identity"]["Id"],
        "Firma" => "new_test_self_sign.png"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), 'Usuarios');
    expect($value)->toBe("Firma de ".$_SESSION["formSession"]["dataSelectionForSigns"]["userName"]." se ha guardado con éxito");
});

test('prueba método reportPdfGenerator, caso reporte de dispositivos', function(){
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de Empresas para el test
        $enterDTOsArr = mockEntersDTO($this->container);
        for($i = 0; $i < sizeof($enterDTOsArr); $i++){
            $this->container->make('enterService')->insertInfo($enterDTOsArr[$i][0], $enterDTOsArr[$i][1]);
        }
        $getting_enters = $this->container->make('enterService')->getAllInfo();
        $enterIds = [];
        foreach($getting_enters as $enter){
            $enterIds[] = $enter["Id"];
        }

        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
        $getting_typs = $this->container->make('typService')->getAllInfo();
        $typIds = [];
        foreach($getting_typs as $typ){
            $typIds[] = $typ["Id"];
        }

        //creación de equipos para el test
        $dceDTOsArr = mockDevicesDTO($this->container, $enterIds, $typIds);
        foreach($dceDTOsArr as $dto){
            $this->container->make('dceService')->insertChild($dto);
        }
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_GET["homeAction"] = "generateDevicesReport";
    $_SESSION["idSession"]["devicesReport_enterId"] = $getting_enters[1]["Id"];

    $values = mockReportPdfGenerator($this->container);

    expect($values['enter_devices'])->toHaveLength(2);
    expect($values['enter_info'])->toHaveLength(13);
    expect(substr($values['logo_base64'], 15, 6))->toBe("base64");
    expect($values['result'])->toBe('../views/adminLayouts/devicesPDF.php');
});

test('prueba método reportPdfGenerator, caso reporte de dispositivos (empresa sin equipos)', function(){
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de Empresas para el test
        $enterDTOsArr = mockEntersDTO($this->container);
        for($i = 0; $i < sizeof($enterDTOsArr); $i++){
            $this->container->make('enterService')->insertInfo($enterDTOsArr[$i][0], $enterDTOsArr[$i][1]);
        }
        $getting_enters = $this->container->make('enterService')->getAllInfo();
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_GET["homeAction"] = "generateDevicesReport";
    $_SESSION["idSession"]["devicesReport_enterId"] = $getting_enters[1]["Id"];

    $values = mockReportPdfGenerator($this->container);

    expect(isset($_SESSION["exceptions"]["unknownInDataBaseEx"]))->toBeTrue();
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=device&homeAction=devicesReport");
});

test('prueba método reportPdfGenerator, caso reporte de bitácora', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=reportPdfGenerator';
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de Empresas para el test
        $enterDTOsArr = mockEntersDTO($this->container);
        for($i = 0; $i < sizeof($enterDTOsArr); $i++){
            $this->container->make('enterService')->insertInfo($enterDTOsArr[$i][0], $enterDTOsArr[$i][1]);
        }
        $getting_enters = $this->container->make('enterService')->getAllInfo();
        $enterIds = [];
        foreach($getting_enters as $enter){
            $enterIds[] = $enter["Id"];
        }

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
        }

        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
        $getting_typs = $this->container->make('typService')->getAllInfo();
        $typIds = [];
        foreach($getting_typs as $typ){
            $typIds[] = $typ["Id"];
        }

        //creación de equipos para el test
        $dceDTOsArr = mockDevicesDTO($this->container, $enterIds, $typIds);
        foreach($dceDTOsArr as $dto){
            $this->container->make('dceService')->insertChild($dto);
        }
        $getting_dces = $this->container->make('dceService')->getChildrenByEnterprise($dceDTOsArr[5]);
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
        $binnIds = getBinnIds($this->container->make('SOSTestDatabase'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_SESSION["binnFilterSession"] = true;
    $_GET["homeAction"] = "generateBinnacleReport";
    $_GET["homeId"] = $binnIds[5]["Id"];

    $values = mockReportPdfGenerator($this->container);

    expect($values['binn_info'])->toHaveLength(29);
    expect($values['with_iva'])->toBe("2974.82");
    expect(substr($values['logo_base64'], 15, 6))->toBe("base64");
    expect(substr($values['no_img_base64'], 15, 6))->toBe("base64");
    expect($values['result'])->toBe('../views/adminLayouts/binnacleInfoCanvas.php');
});

test('prueba método reportPdfGenerator, caso reporte de bitácora (bitácora inexistente)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=reportPdfGenerator';
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_SESSION["binnFilterSession"] = true;
    $_GET["homeAction"] = "generateBinnacleReport";
    $_GET["homeId"] = "9999";

    $values = mockReportPdfGenerator($this->container);

    expect(isset($_SESSION["exceptions"]["unknownInDataBaseEx"]))->toBeTrue();
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=binnaclesReport");
});

test('prueba método ajaxProcedure, caso petición JS en followuplist', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=followuplist';
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de Empresas para el test
        $enterDTOsArr = mockEntersDTO($this->container);
        for($i = 0; $i < sizeof($enterDTOsArr); $i++){
            $this->container->make('enterService')->insertInfo($enterDTOsArr[$i][0], $enterDTOsArr[$i][1]);
        }
        $getting_enters = $this->container->make('enterService')->getAllInfo();
        $enterIds = [];
        foreach($getting_enters as $enter){
            $enterIds[] = $enter["Id"];
        }

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
        }

        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
        $getting_typs = $this->container->make('typService')->getAllInfo();
        $typIds = [];
        foreach($getting_typs as $typ){
            $typIds[] = $typ["Id"];
        }

        //creación de equipos para el test
        $dceDTOsArr = mockDevicesDTO($this->container, $enterIds, $typIds);
        foreach($dceDTOsArr as $dto){
            $this->container->make('dceService')->insertChild($dto);
        }
        $getting_dces = $this->container->make('dceService')->getChildrenByEnterprise($dceDTOsArr[5]);
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_SERVER['CONTENT_TYPE'] = 'application/json';
    $_GET["homeAction"] = 'followuplist';
    $_POST["number"] = '5';

    $values = mockAjaxProcedure($this->container);

    expect($values['pagination_arr']['binns'])->toHaveLength(4);
    expect($values['pagination_arr']['buttons'])->toBe($this->buttons);
});

test('prueba método ajaxProcedure, caso petición JS en binnaclesFilter (datos de paginación)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=binnaclesFilter';
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de Empresas para el test
        $enterDTOsArr = mockEntersDTO($this->container);
        for($i = 0; $i < sizeof($enterDTOsArr); $i++){
            $this->container->make('enterService')->insertInfo($enterDTOsArr[$i][0], $enterDTOsArr[$i][1]);
        }
        $getting_enters = $this->container->make('enterService')->getAllInfo();
        $enterIds = [];
        foreach($getting_enters as $enter){
            $enterIds[] = $enter["Id"];
        }

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
        }

        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
        $getting_typs = $this->container->make('typService')->getAllInfo();
        $typIds = [];
        foreach($getting_typs as $typ){
            $typIds[] = $typ["Id"];
        }

        //creación de equipos para el test
        $dceDTOsArr = mockDevicesDTO($this->container, $enterIds, $typIds);
        foreach($dceDTOsArr as $dto){
            $this->container->make('dceService')->insertChild($dto);
        }
        $getting_dces = $this->container->make('dceService')->getChildrenByEnterprise($dceDTOsArr[5]);
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_SERVER['CONTENT_TYPE'] = 'application/json';
    $_GET["homeAction"] = 'binnaclesReport';
    $_SESSION["binnFilterSession"] = [
        "Empresa_id" => null,
        "Contacto_id" => null,
        "IsServiceOrDevice" => "Equipo_id",
        "Equipo_id" => null,
        "Estatus" => "falta confirmar",
        "StartedOrEnded" => "Inicio",
        "LeftDay" => null,
        "RightDay" => null,
        "Visible" => "ENABLED"
    ];
    $_POST["binnsFilterNumber"] = '50';

    $values = mockAjaxProcedure($this->container);

    expect($values['pagination_arr']['binns'])->toHaveLength(2);
    expect($values['pagination_arr']['buttons'])->toBe($this->buttons);
});

test('prueba método ajaxProcedure, caso petición JS en binnaclesFilter (datos para selects)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=binnaclesFilter';
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de Empresas para el test
        $enterDTOsArr = mockEntersDTO($this->container);
        for($i = 0; $i < sizeof($enterDTOsArr); $i++){
            $this->container->make('enterService')->insertInfo($enterDTOsArr[$i][0], $enterDTOsArr[$i][1]);
        }
        $getting_enters = $this->container->make('enterService')->getAllInfo();
        $enterIds = [];
        foreach($getting_enters as $enter){
            $enterIds[] = $enter["Id"];
        }

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
        }

        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
        $getting_typs = $this->container->make('typService')->getAllInfo();
        $typIds = [];
        foreach($getting_typs as $typ){
            $typIds[] = $typ["Id"];
        }

        //creación de equipos para el test
        $dceDTOsArr = mockDevicesDTO($this->container, $enterIds, $typIds);
        foreach($dceDTOsArr as $dto){
            $this->container->make('dceService')->insertChild($dto);
        }
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_SERVER['CONTENT_TYPE'] = 'application/json';
    $_POST["enterIdFromBinnFilter"] = $getting_enters[0]["Id"];

    $values = mockAjaxProcedure($this->container);

    expect($_SESSION["binnFilterSession"]["enterpriseRelatedContacts"])->toHaveLength(3);
    expect($_SESSION["binnFilterSession"]["enterpriseRelatedDevices"])->toHaveLength(2);
});

test('prueba método ajaxProcedure, caso petición JS en index de BinnacleController (datos para selects)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=index';
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de Empresas para el test
        $enterDTOsArr = mockEntersDTO($this->container);
        for($i = 0; $i < sizeof($enterDTOsArr); $i++){
            $this->container->make('enterService')->insertInfo($enterDTOsArr[$i][0], $enterDTOsArr[$i][1]);
        }
        $getting_enters = $this->container->make('enterService')->getAllInfo();
        $enterIds = [];
        foreach($getting_enters as $enter){
            $enterIds[] = $enter["Id"];
        }

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
        }

        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
        $getting_typs = $this->container->make('typService')->getAllInfo();
        $typIds = [];
        foreach($getting_typs as $typ){
            $typIds[] = $typ["Id"];
        }

        //creación de equipos para el test
        $dceDTOsArr = mockDevicesDTO($this->container, $enterIds, $typIds);
        foreach($dceDTOsArr as $dto){
            $this->container->make('dceService')->insertChild($dto);
        }
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_SERVER['CONTENT_TYPE'] = 'application/json';
    $_POST["enterpriseId"] = $getting_enters[0]["Id"];

    $values = mockAjaxProcedure($this->container);

    expect($values['enterprise_arr']['entInfo'])->toHaveLength(13);
    expect($values['enterprise_arr']['enterpriseContacts'])->toHaveLength(3);
    expect($values['enterprise_arr']['enterpriseDevices'])->toHaveLength(2);
});

test('prueba método ajaxProcedure, caso petición JS en index de ContactController', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=contact&homeAction=index';
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de Empresas para el test
        $enterDTOsArr = mockEntersDTO($this->container);
        for($i = 0; $i < sizeof($enterDTOsArr); $i++){
            $this->container->make('enterService')->insertInfo($enterDTOsArr[$i][0], $enterDTOsArr[$i][1]);
        }
        $getting_enters = $this->container->make('enterService')->getAllInfo();
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_SERVER['CONTENT_TYPE'] = 'application/json';
    $_POST["newContactEnterId"] = $getting_enters[0]["Id"];

    $values = mockAjaxProcedure($this->container);

    expect($values['enterprise_arr']['entInfoForContactForm'])->toHaveLength(13);
});

test('prueba método ajaxProcedure, caso petición JS en index de BinnacleController (datos de un equipo)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=index';
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de Empresas para el test
        $enterDTOsArr = mockEntersDTO($this->container);
        for($i = 0; $i < sizeof($enterDTOsArr); $i++){
            $this->container->make('enterService')->insertInfo($enterDTOsArr[$i][0], $enterDTOsArr[$i][1]);
        }
        $getting_enters = $this->container->make('enterService')->getAllInfo();
        $enterIds = [];
        foreach($getting_enters as $enter){
            $enterIds[] = $enter["Id"];
        }

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
        }

        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
        $getting_typs = $this->container->make('typService')->getAllInfo();
        $typIds = [];
        foreach($getting_typs as $typ){
            $typIds[] = $typ["Id"];
        }

        //creación de equipos para el test
        $dceDTOsArr = mockDevicesDTO($this->container, $enterIds, $typIds);
        foreach($dceDTOsArr as $dto){
            $this->container->make('dceService')->insertChild($dto);
        }
        $getting_dces = $this->container->make('dceService')->getChildrenByEnterprise($dceDTOsArr[5]);
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_SERVER['CONTENT_TYPE'] = 'application/json';
    $_POST["deviceId"] = $getting_dces[0]["Id"];

    $values = mockAjaxProcedure($this->container);

    expect($values['device_arr'])->toHaveLength(7);
});