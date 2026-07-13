<?php

class DeviceController{

    private $enterDTO, $dceDTO, $usrDTO, $typService, $typSelectSrv, $enterSelectSrv, 
            $enterService, $dceService, $usrParticularSrv;
    public function __construct($enterDTO, $dceDTO, $usrDTO, $typService, $typSelectSrv, 
        $enterSelectSrv, $enterService, $dceService, $usrParticularSrv){
        $this->enterDTO = $enterDTO;
        $this->dceDTO = $dceDTO;
        $this->usrDTO = $usrDTO;    
        $this->typService = $typService;
        $this->typSelectSrv = $typSelectSrv;
        $this->enterSelectSrv = $enterSelectSrv;
        $this->enterService = $enterService;
        $this->dceService = $dceService;
        $this->usrParticularSrv = $usrParticularSrv;
    }

    public function index(){
        if(!empty($_SESSION["identity"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            
            try{
                $enters = $this->enterSelectSrv->getInfoForSelects();
                $types = $this->typSelectSrv->getInfoForSelects();
            } catch (Exception $ex) {
                $_SESSION["exceptions"]["dataForSelectDceFormEx"] = "Se generó un "
                            ."error interactuando con la base de datos "
                            ."en cuanto a la obtención de datos para la"
                            ." caja de selección de tipos y empresas del formulario, lo más "
                            ."probable es que se haya cortado la conexión "
                            ."a la base de datos";
                $enters = [];            
                $types = [];
            }finally{
                require_once '../views/userLayouts/newDeviceForm.php';
            }
            
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function editDevice(){
        if(!empty($_SESSION["isAdmin"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            
            Utils::setIdSession();

            try {
                $enterprises = $this->enterSelectSrv->getInfoForSelects();

                if(!empty($_SESSION["idSession"]["devicesEdit_enterId"])){
                    $this->dceDTO->empresa_id = $_SESSION["idSession"]["devicesEdit_enterId"];
                    $devices_arr = $this->dceService->getChildrenByEnterprise($this->dceDTO);
                    $types_arr = $this->typSelectSrv->getInfoForSelects();
                }
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch (Exception $ex) {
                $_SESSION["exceptions"]["deviceInformationEx"] = "No se logró obtener la "
                                ."información de los equipos, posible corte "
                                ."de conexión a la base de datos";
                $enterprises = [];
                $devices_arr = [];
                $types_arr = [];
            }finally{
                require_once '../views/adminLayouts/devicesEditForms.php';
            }
            
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function devicesReport(){
        if(!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            
            Utils::setIdSession();
            
            try{
                $enters = $this->enterService->getAllInfo();

                if(!empty($_SESSION["idSession"]["devicesReport_enterId"])){
                    $this->enterDTO->enterprise_id = $_SESSION["idSession"]["devicesReport_enterId"];
                    $this->dceDTO->empresa_id = $_SESSION["idSession"]["devicesReport_enterId"];
                    $enter_info = $this->enterService->getInfo($this->enterDTO);
                    $enter_devices = $this->dceService->getChildrenByEnterprise($this->dceDTO);
                }
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch (Exception $ex) {
                $_SESSION["exceptions"]["gettingEntersException"] = "No se pudo conseguir "
                        ."el listado de empresas para la busqueda, posible "
                        ."corte de conexión a la base de datos";
                $enters = [];
                $enter_info = [];
                $enter_devices = [];
            }finally{
                require_once '../views/adminLayouts/devicesReport.php';
            }
            
        }else{
            header("Location: ".base_url."home/");
            exit; 
        }
    }
    public function insertDevice(){
        if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){

            $this->dceDTO->empresa_id = $_POST["empresas"];
            $this->dceDTO->tipo_id = $_POST["tipos"];
            $this->dceDTO->marca = $_POST["marca"];
            $this->dceDTO->modelo = $_POST["modelo"];
            $this->dceDTO->numero_serie = $_POST["ns"];
            $this->dceDTO->numero_inventario = $_POST["numeroInventario"];
            $errorArr = DeviceVerifications::verifyingInsertion($this->dceDTO);

            try{
                (sizeof($errorArr) === 0) ? $this->dceService->insertChild($this->dceDTO) :
                    $_SESSION["errors"] = $errorArr;
                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "Se realizó el registro del equipo con éxito";

            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["deviceInsertionException"] = "Se generó un "
                            ."error interactuando con la base de datos "
                            ."en cuanto a la inserción de un equipo, "
                            ."lo más probable es que se haya ingresado un número de serie "
                            ."existente en la base de datos o se haya cortado la conexión a la base de datos";
            }finally{
                header("Location: ".base_url."home/?homeController=device&homeAction=index");
                exit;
            }
            
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function updateDeviceInfo(){
        if(!empty($_SESSION["isAdmin"])  && sizeof($_POST) > 0){

            $this->usrDTO->admin_pwd = $_POST["adminContrasena"];
            $this->dceDTO->device_id = $_POST["dispositivoId"];
            $this->dceDTO->tipo_id = $_POST["tipos"];
            $this->dceDTO->marca = $_POST["marca"];
            $this->dceDTO->modelo = $_POST["modelo"];
            $this->dceDTO->numero_serie = $_POST["ns"];
            $this->dceDTO->numero_inventario = $_POST["numeroInventario"];
            $errorArr = DeviceVerifications::verifyingUpdate($this->dceDTO, $this->usrDTO);

            try{
                if(empty($errorArr["adminContrasena"]))
                    $isRejection = Utils::setAdminVerification($this->usrDTO, $this->usrParticularSrv);
                if(sizeof($isRejection) > 0)
                    $errorArr = $isRejection;

                (sizeof($errorArr) === 0) ? $this->dceService->updateChild($this->dceDTO) :
                    $_SESSION["errors"] = $errorArr;
                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "Se logró editar la información del dispositivo con ID ".$this->dceDTO->device_id." con éxito";

            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["updateDeviceInfoEx"] = "No se logró editar la información del dispositivo, "
                            ."lo más probable es que se haya registrado un número "
                            ."de serie que ya se encuentra en la base de datos. Otro problema posible "
                            ."es que se haya cortado la conexión a la base de datos";
            }finally{
                header("Location: ".base_url."home/?homeController=device&homeAction=editDevice");
                exit;
            }
            
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function enableOrDisableDevice(){
        if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

            $this->dceDTO->device_id = $_POST["equipoId"];
            $this->dceDTO->visibilidad = $_POST["visibilidad"];
            $errorArr = SwitchVerification::verifyingSwitch($this->dceDTO);
            $str_portion_one = ($this->dceDTO->visibilidad === "DISABLED") ? 
            "desactivó":"activó";
            $str_portion_two = ($this->dceDTO->visibilidad === "DISABLED") ? 
            "desactivar":"activar";
            try{
                (sizeof($errorArr) === 0) ?
                    $this->dceService->updateVisibility($this->dceDTO) :
                    $_SESSION["errors"] = $errorArr;

                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "Se "
                        .$str_portion_one." el equipo con ID ".$this->dceDTO->device_id." con éxito";
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(AutomaticValueException $ex){
                $_SESSION["exceptions"]["visibilityEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["disableTypeEx"] = "No se logró ".$str_portion_two." el equipo con ID ".$this->dceDTO->device_id.", posible corte de conexión a la base de datos";
            }finally{
                header("Location: ".base_url."home/?homeController=device&homeAction=editDevice");
                exit;
            }

        }else{
            header("Location: ".base_url."home/");
            exit; 
        }
    }
}