<?php

class Utils{

    //Indexes utils
    public static function putSessionWithVerify(){
        if(empty($_SESSION)){
            session_start();
        }
    }
    public static function sessionLifetime(){
        if (!empty($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > 1800) {
            // Caducar sesión
            session_unset();
            session_destroy();
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public static function showError($container){
        $error = $container->get('ErrorController');
        $error->index();
    }

    public static function defaultHomePage($container){
        $controllerName = default_homeController;
        $defaultAction = default_action;
        $controlador = $container->get($controllerName);
        $controlador->$defaultAction();
    }
    public static function generateWelcomeBanner(){
        if (!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])) {
            require_once '../views/adminLayouts/menuSides/welcomeBanner.php';
        } else if (!empty($_SESSION["identity"])) {
            require_once '../views/userLayouts/menuSides/welcomeBanner.php';
        }
    }
    public static function setAsideWithVerify(){
        if(!empty($_SESSION["isAdmin"])){
            require_once '../views/adminLayouts/menuSides/aside.php';
        }
    }

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
    public static function unsetJsonDecodedSession(){
        if(!empty($_SESSION["jsondecoded"])){
            unset($_SESSION["jsondecoded"]);
        }
    }
    public static function unsetBinnFilterSession(){
        
        if(!empty($_SESSION["binnFilterSession"])){
            unset($_SESSION["binnFilterSession"]);
        }
    }
    public static function unsetSearchFormsIdSession(){
        
        if(!empty($_SESSION["idSession"])){
            unset($_SESSION["idSession"]);
        }
    }
    public static function unsetFormSessions(){
        
        if(!empty($_SESSION["formSession"])){
            unset($_SESSION["formSession"]);
        }
    }
    public static function reportPdfGenerator($dceDTO, $enterDTO, $binnDTO, 
        $dceService, $enterService, $binnService){
        
        if(!empty($_GET["homeAction"]) && $_GET["homeAction"] === "generateDevicesReport"){

            if(!empty($_SESSION["idSession"]["devicesReport_enterId"])){

                try{
                    
                    $dceDTO->empresa_id = $_SESSION["idSession"]["devicesReport_enterId"];
                    $enter_devices = $dceService->getChildrenByEnterprise($dceDTO);

                    if(sizeof($enter_devices) === 0)
                        throw new Exception();

                    $enterDTO->enterprise_id = $_SESSION["idSession"]["devicesReport_enterId"];
                    $enter_info = $enterService->getInfo($enterDTO);
                    $path = "../assets/img/logo.png";
                    $data = file_get_contents($path);
                    $logo_base64 = "data:image/png;base64,".base64_encode($data);

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
                } catch (Exception $ex) {
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
                if(!unlink("uploads/firmas/".$_SESSION["formSession"]["techSignature"])){
                        $_SESSION["exceptions"]["unlinkTechSignEx"] = "La supuesta firma del técnico no se encontró en la aplicación web";
                }
                Utils::unsetFormSessions();
            }finally{
                if(!empty($_SESSION["exceptions"])){
                    header("Location: ".base_url."home/");
                    exit;
                }
            
                try{

                    if(!empty($_SESSION["idSession"]["userSign_userId"])){
                        if($_SESSION["idSession"]["userSign_userId"] === 
                            $_SESSION["identity"]["Id"]){ 
                            $_SESSION["identity"] = $usrService->getInfo();
                        }else{
                            $_SESSION["identity"] = $usrService->getInfo();
                        }
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

    public static function setAdminWithVerify(){
        if(!empty($_SESSION["identity"])){
            if ($_SESSION["identity"]["Privilegio"] === "Admin") {
                $_SESSION["isAdmin"] = true;
            }
        }
    }
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

    public static function setIVAIfAmountIsNotNull($arr){
        $iva_format = null;
        if(!empty($arr["Monto"])){
            /*todo lo que la base de datos devuelve es información en forma de string, incluyendo valores numericos, entonces
                    * si Monto no es igual a "" entonces tiene un numero flotante, lo que se hace es convertir el indice "Monto" en un valor flotante y multiplicar 
                    * ese valor por 1.16 (calculo del IVA en México), el resultado se guardará en la variable $iva_result*/
                $iva_result = floatval($arr["Monto"]) * 1.16;
                /*en la variable $with_iva contendrá la cantidad contenida en $iva_result pero configurada para que este solo 
                    * tenga dos decimales, la variable $binn_info (y si entra en este if, tambien la variable $with_iva) lo 
                    * utilizará la vista binnacleInfoCanvas.php en el bloque if de $_GET["homeAction"] === "showBinnacle"*/
                $iva_format = sprintf("%.2f", $iva_result);
        }
        return $iva_format;
    }
}