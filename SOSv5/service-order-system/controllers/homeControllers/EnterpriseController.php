<?php

class EnterpriseController{

    private $contDTO, $enterDTO, $usrDTO, $contService, $enterService, $usrParticularSrv;
    public function __construct($contDTO, $enterDTO, $usrDTO, 
        $contService, $enterService, $usrParticularSrv){
        $this->enterDTO = $enterDTO;
        $this->usrDTO = $usrDTO;
        $this->contDTO = $contDTO;
        $this->contService = $contService;
        $this->enterService = $enterService;
        $this->usrParticularSrv = $usrParticularSrv;
    }

    public function index(){
        if(!empty($_SESSION["isAdmin"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            
            Utils::setIdSession();

            try {
                $enterprises = $this->enterService->getAllInfo();

                if(!empty($_SESSION["idSession"]["enterpriseEdit_enterId"])){
                    $this->enterDTO->enterprise_id = $_SESSION["idSession"]["enterpriseEdit_enterId"];
                    $this->contDTO->empresa_id = $_SESSION["idSession"]["enterpriseEdit_enterId"];
                    $ent_arr = $this->enterService->getInfo($this->enterDTO);
                    $contacts_arr = $this->contService->getChildrenByEnterprise($this->contDTO);
                }
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch (Exception $ex) {
                $_SESSION["exceptions"]["selectDataEnterEditEx"] = "No se logró obtener la "
                                ."información de la empresa y sus contactos, posible corte "
                                ."de conexión a la base de datos";
                $enterprises = [];
                $ent_arr = [];
                $contacts_arr = [];
            }finally{
                require_once '../views/adminLayouts/enterAndContactsEditForms.php';
            }
                
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    public function updateEnterInfo(){
        if(!empty($_SESSION["isAdmin"])  && sizeof($_POST) > 0){
            
            $this->usrDTO->admin_pwd = $_POST["adminContrasena"];
            $this->enterDTO->enterprise_id = $_POST["empresaId"];
            $this->enterDTO->nombre_comercial = $_POST["nombreComercial"];
            $this->enterDTO->razon_social = $_POST["razonSocial"];
            $this->enterDTO->calle_numero = $_POST["calleYNumero"];
            $this->enterDTO->entre_calles = $_POST["entreCalles"];
            $this->enterDTO->dirigirse_con = $_POST["dirigirseCon"];
            $this->enterDTO->telefonos = $_POST["telefonos"];
            $this->enterDTO->horario = $_POST["horario"];
            $this->enterDTO->atencion = $_POST["atencion"];
            $this->enterDTO->colonia = $_POST["colonia"];
            $this->enterDTO->localidad = $_POST["localidad"];
            $this->enterDTO->email = $_POST["email"];
            $errorArr = EnterpriseVerifications::verifyingUpdate($this->enterDTO, $this->usrDTO);
            
            try{
                if(empty($errorArr["adminContrasena"]))
                    $isRejection = Utils::setAdminVerification($this->usrDTO, $this->usrParticularSrv);
                if(sizeof($isRejection) > 0)
                    $errorArr = $isRejection;

                (sizeof($errorArr) === 0) ?
                    $this->enterService->updateInfo($this->enterDTO) :
                    $_SESSION["errors"] = $errorArr;
                
                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "Se actualizaron los datos de la empresa con éxito";    
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["updateEnterInfoEx"] = "Hubo una excepción en "
                            ."el proceso de actualización de datos de la "
                            ."empresa, posible corte de conexión a la base de datos";
            }finally{
                header("Location: ".base_url."home/?homeController=enterprise&homeAction=index");
                exit;
            }

        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    public function enableOrDisableEnterprise(){
        if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){
            
            $this->enterDTO->enterprise_id = $_POST["empresaId"];
            $this->enterDTO->visibilidad = $_POST["visibilidad"];
            $errorArr = SwitchVerification::verifyingSwitch($this->enterDTO);
            $str_portion_one = ($this->enterDTO->visibilidad === "DISABLED") ? 
            "desactivó":"activó";
            $str_portion_two = ($this->enterDTO->visibilidad === "DISABLED") ? 
            "desactivar":"activar";
            
            try{
                (sizeof($errorArr) === 0) ?
                    $this->enterService->updateVisibility($this->enterDTO) :
                    $_SESSION["errors"] = $errorArr;

                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "Se "
                        .$str_portion_one." la empresa con ID ".$this->enterDTO->enterprise_id." con éxito";
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(AutomaticValueException $ex){
                $_SESSION["exceptions"]["visibilityEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["disableTypeEx"] = "No se logró ".$str_portion_two." la empresa con ID ".$this->enterDTO->enterprise_id.", posible corte de conexión a la base de datos";
            }finally{
                header("Location: ".base_url."home/?homeController=enterprise&homeAction=index");
                exit;
            }

        }else{
            header("Location: ".base_url."home/");
            exit; 
        }
    }
}