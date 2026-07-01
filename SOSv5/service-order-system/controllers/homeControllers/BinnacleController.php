<?php

class BinnacleController{
    private $binnDTO, $usrDTO, $enterSelectSrv, 
            $binnService, $usrService, $usrParticularSrv;
    public function __construct($binnDTO, $usrDTO, $enterSelectSrv, 
        $binnService, $usrService, $usrParticularSrv){
        $this->binnDTO = $binnDTO;
        $this->usrDTO = $usrDTO;
        $this->enterSelectSrv = $enterSelectSrv;
        $this->binnService = $binnService;
        $this->usrService = $usrService;
        $this->usrParticularSrv = $usrParticularSrv;
    }

    public function index(){
        if(!empty($_SESSION["identity"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            
            try{
                $enterprises = $this->enterSelectSrv->getInfoForSelects();
            } catch (Exception $ex) {
                $_SESSION["exceptions"]["getInfoForSelectsException"] = "Se generó un "
                        ."error interactuando con la base de datos "
                        ."en cuanto a la obtención de datos para la"
                        ." caja de selección de empresas de la bitácora, lo más "
                        ."probable es que se haya cortado la conexión "
                        ."a la base de datos";
                $enterprises = [];
            }finally{
                require_once '../views/userLayouts/firstForm.php';
            }    
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function followuplist(){
        if(!empty($_SESSION["identity"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            
            $this->binnDTO->usuario_id = $_SESSION["identity"]["Id"];
            try {
                (!empty($_SESSION["jsondecoded"]["followUpNumKey"])) ?
                    $page_elem = $_SESSION["jsondecoded"]["followUpNumKey"] :
                    $page_elem = 1;
                
                $binn_pagination = $this->binnService->getAllInfo(
                    $this->binnDTO,
                    $page_elem,
                    null,
                    $_GET["homeAction"]
                );    
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            } catch (Exception $ex) {
                $_SESSION["exceptions"]["paginationArrException"] = "Se generó un "
                        ."error interactuando con la base de datos "
                        ."en cuanto a la generación de paginación".$ex;
            }finally{
                if(!empty($_SESSION["exceptions"])){
                    header("Location: ".base_url."home/");
                    exit;
                }

                require_once '../views/userLayouts/followup.php';    
            }
                
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
        
    }
    public function binnaclesReport(){
        if(!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            
            $binn_pagination = [];
            try{
                $empresas = $this->enterSelectSrv->getInfoForSelects();
                Utils::setBinnFilterSessions($this->binnDTO);

                if(!empty($_SESSION["binnFilterSession"])){
                    (!empty($_SESSION["jsondecoded"]["binnsReportNumKey"])) ?
                    $page_elem = $_SESSION["jsondecoded"]["binnsReportNumKey"] :
                    $page_elem = 5;
                
                    $binn_pagination = $this->binnService->getAllInfo(
                        null,
                        $page_elem,
                        $_SESSION["binnFilterSession"],
                        $_GET["homeAction"]
                    );
                }   

            }catch(UnauthorizedDataException $ex){
                $_SESSION["exceptions"]["unauthEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["binnsRowsPaginationEx"] = "Se generó un "
                        . "error interactuando con la base de datos "
                        . "en cuanto a la generación de paginación, posible falta de conexión a la base de datos";
                Utils::unsetBinnFilterSession();
            }finally{
                require_once '../views/adminLayouts/binnaclesFilter.php';
            }
            
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function showBinnacle(){
        if(!empty($_SESSION["identity"]) && 
            !empty($_SESSION["isAdmin"])  && 
            !empty($_GET["homeId"])){
            $_SESSION['LAST_ACTIVITY'] = time();

            try{
                $this->binnDTO->binnacle_id = $_GET["homeId"];
                $binn_info = $this->binnService->getInfo($this->binnDTO);
                $with_iva = Utils::setIVAIfAmountIsNotNull($binn_info);
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unKnownEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["getBinnInfoEx"] = "No se logró obtener los "
                            ."datos de la bitácora seleccionada, posible "
                            ."corte de conexión a la base de datos";
            }finally{
                if(!empty($_SESSION["exceptions"]["unKnownEx"])){
                    header("Location: ".base_url."home/?homeController=error&homeAction=index");
                    exit;
                }

                if(!empty($_SESSION["exceptions"]["getBinnInfoEx"]) ||
                    !empty($_SESSION["exceptions"]["entitiesEx"])){
                    header("Location: ".base_url."home/?homeController=user&homeAction=binnaclesReport");
                    exit;
                }

                require_once '../views/adminLayouts/binnacleInfoCanvas.php';
            }
            
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function editBinnacle(){
        if(!empty($_SESSION["identity"]) && 
            !empty($_SESSION["isAdmin"])  && 
            !empty($_GET["homeId"])){
            $_SESSION['LAST_ACTIVITY'] = time();    
              
            try{
                $this->binnDTO->binnacle_id = $_GET["homeId"];
                $binn_info = $this->binnService->getInfo($this->binnDTO);

                if($binn_info["Estatus"] === "en proceso" || $binn_info["Estatus"] === "falta confirmar")
                    $usuarios = $this->usrService->getAllInfo();
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unKnownEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["getBinnInfoEx"] = "No se logró obtener los "
                            ."datos de la bitácora seleccionada, posible "
                            ."corte de conexión a la base de datos";
            }finally{
                if(!empty($_SESSION["exceptions"]["unKnownEx"])){
                    header("Location: ".base_url."home/?homeController=error&homeAction=index");
                    exit;
                }

                if(!empty($_SESSION["exceptions"]["getBinnInfoEx"]) ||
                    !empty($_SESSION["exceptions"]["entitiesEx"])){
                    header("Location: ".base_url."home/?homeController=binnacle&homeAction=binnaclesReport");
                    exit;
                }

                require_once '../views/adminLayouts/binnacleInfoCanvas.php';
            }

        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function binninsertion(){
        if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){
            
            $this->binnDTO->usuario_id = $_POST["userId"];
            $this->binnDTO->contacto_id = $_POST["contactos"];
            $this->binnDTO->actividad = $_POST["tipoActividades"];
            $this->binnDTO->servicio = (!empty($_POST["servicio"])) ? $_POST["servicio"] : '';
            $this->binnDTO->equipo_id = (!empty($_POST["equipos"])) ? $_POST["equipos"] : '';
            $errorArr = BinnacleVerifications::verifyingInsertion($this->binnDTO);
            
            try{
                (sizeof($errorArr) === 0) ? $this->binnService->insertInfo($this->binnDTO) :
                    $_SESSION["errors"] = $errorArr;
                
                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "La bitácora se a creado con éxito";
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["binnDataException"] = "Acción fallida, probable falta de conexión a la base de datos".$ex;
            }finally{
                header("Location: ".base_url."home/?homeController=binnacle&homeAction=index");
                exit;
            }

        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function updateBinnacleInfo(){
        if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){
            
            $this->binnDTO->binnacle_id = $_POST["bitacoraId"];
            $this->binnDTO->estatus = $_POST["estatus"];
            $this->usrDTO->admin_pwd = $_POST["adminContrasena"];
            $this->binnDTO->inicio = $_POST["fechaInicio"];
            if(isset($_POST["usuario"]))
                $this->binnDTO->usuario_id = $_POST["usuario"];
            if(isset($_POST["servicio"]))
                $this->binnDTO->servicio = $_POST["servicio"];
            if(isset($_POST["precio"]))
                $this->binnDTO->monto = $_POST["precio"];
            if(isset($_POST["seHizo"]))
                $this->binnDTO->Actividades_realizadas = $_POST["seHizo"];
            if(isset($_POST["motivoCancelacion"]))
                $this->binnDTO->cancel_desc = $_POST["motivoCancelacion"];
            if(isset($_POST["observaciones"]))
                $this->binnDTO->observaciones = $_POST["observaciones"];
            if(isset($_POST["fechaFin"]))
                $this->binnDTO->fin = $_POST["fechaFin"];
            $errorArr = BinnacleVerifications::verifyingUpdate($this->binnDTO, $this->usrDTO);

            try{
                if(empty($errorArr["adminContrasena"]))
                    $isRejection = Utils::setAdminVerification($this->usrDTO, $this->usrParticularSrv);
                if(sizeof($isRejection) > 0)
                    $errorArr = $isRejection;

                (sizeof($errorArr) === 0) ? $this->binnService->updateInfo($this->binnDTO) :
                    $_SESSION["errors"] = $errorArr;
                   
                if(empty($_SESSION["errors"])) 
                       $_SESSION["success"] = "Se logró editar la bitácora con éxito";
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["binnacleUpdateEx"] = "No se logró editar la bitácora, posible corte de conexión a la base de datos";
            }finally{
                if(!empty($_SESSION["exceptions"]) ||
                    !empty($_SESSION["errors"])){
                    header("Location: ".base_url."home/?homeController=binnacle&homeAction=binnaclesReport");
                    exit;
                }

                header("Location: ".base_url."home/?homeController=binnacle&homeAction=editBinnacle&homeId=".$this->binnDTO->binnacle_id);
                exit;
            }
            
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function enableOrDisableBinn(){
        if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

            $this->binnDTO->binnacle_id = $_POST["bitacoraId"];
            $this->binnDTO->visibilidad = $_POST["visibilidad"];
            $errorArr = SwitchVerification::verifyingSwitch($this->binnDTO);
            $str_portion_one = ($this->binnDTO->visibilidad === "DISABLED") ? 
            "desactivó":"activó";
            $str_portion_two = ($this->binnDTO->visibilidad === "DISABLED") ? 
            "desactivar":"activar";
            
            try{
                (sizeof($errorArr) === 0) ?
                    $this->binnService->updateVisibility($this->binnDTO) :
                    $_SESSION["errors"] = $errorArr;

                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "Se "
                        .$str_portion_one." la bitácora con ID ".$this->binnDTO->binnacle_id." con éxito";
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(AutomaticValueException $ex){
                $_SESSION["exceptions"]["visibilityEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["disableTypeEx"] = "No se logró ".$str_portion_two." la bitácora con ID ".$this->binnDTO->binnacle_id.", posible corte de conexión a la base de datos";
            }finally{
                header("Location: ".base_url."home/?homeController=binnacle&homeAction=binnaclesReport");
                exit;
            }
             
        }else{
            header("Location: ".base_url."home/");
            exit; 
        }
    }
}