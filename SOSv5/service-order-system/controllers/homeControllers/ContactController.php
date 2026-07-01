<?php

class ContactController{
    private $contDTO, $usrDTO, $enterDTO, $enterSelectSrv, 
            $contService, $enterService, $usrParticularSrv;
    public function __construct($contDTO, $usrDTO, $enterDTO, $enterSelectSrv, 
        $contService, $enterService, $usrParticularSrv){
        $this->contDTO = $contDTO;
        $this->enterDTO = $enterDTO;
        $this->usrDTO = $usrDTO;
        $this->enterSelectSrv = $enterSelectSrv;
        $this->contService = $contService; 
        $this->enterService = $enterService;
        $this->usrParticularSrv = $usrParticularSrv;
    }

    public function index(){
        if(!empty($_SESSION["identity"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            
            try{
                $enterprises = $this->enterSelectSrv->getInfoForSelects();
            } catch (Exception $ex) {
                $_SESSION["exceptions"]["getInfoForSelectException"] = "Se generó un "
                        ."error interactuando con la base de datos "
                        ."en cuanto a la obtención de datos para la"
                        ." caja de selección de empresas del formulario, lo más "
                        ."probable es que se haya cortado la conexión "
                        ."a la base de datos";
                $enterprises = [];
            }finally{
                require_once '../views/userLayouts/newContactForm.php';
            }
            
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function insertContact(){
        
        if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){
                
            $hidden_ent_id = (!empty($_POST["hiddenEntId"])) ? $_POST["hiddenEntId"] : null;
            
            try{
                if(isset($hidden_ent_id)){
                    $this->contDTO->empresa_id = $hidden_ent_id;
                    $this->contDTO->nombre_completo = $_POST["contacto"];
                    $errorsArr = ContactVerifications::verifyingInsertion($this->contDTO);

                    (sizeof($errorsArr) === 0) ? $this->contService->insertInfo($this->contDTO) :
                        $_SESSION["errors"] = $errorsArr;
                    
                    if(empty($_SESSION["errors"]))
                        $_SESSION["success"] = "Se realizó el registro del contacto con éxito";    
                }else{
                    $this->contDTO->nombre_completo = $_POST["contacto"];
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
                    $errorsArr = EnterpriseVerifications::verifyingInsertion($this->enterDTO, $this->contDTO);

                    (sizeof($errorsArr) === 0) ? $this->enterService->insertInfo($this->enterDTO, $this->contDTO) :
                        $_SESSION["errors"] = $errorsArr;
                    
                    if(empty($_SESSION["errors"]))
                        $_SESSION["success"] = "Se realizó el registro del contacto con éxito";

                }
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                if(isset($hidden_ent_id)){
                    $_SESSION["exceptions"]["contactWithEnterIdInsertionException"] = "Se generó un "
                        ."error interactuando con la base de datos "
                        ."en cuanto a la inserción de un contacto vinculado a una empresa "
                        . "existente, posible falta de conexión";
                }else{
                    $_SESSION["exceptions"]["contactTotalInsertionException"] = "Se generó un "
                        ."error interactuando con la base de datos "
                        ."en cuanto a la inserción total de un contacto, "
                        . "lo más probable es que se haya ingresado un nombre de empresa ya existente "
                        . "en la base de datos, o se haya cortado la conexión a la base de datos";
                }
            }finally{
                header("Location: ".base_url."home/?homeController=contact&homeAction=index");
                exit;
            }
                
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function updateContactInfo(){
        if(!empty($_SESSION["isAdmin"])  && sizeof($_POST) > 0){

            $this->contDTO->contact_id = $_POST["contactoId"];
            $this->contDTO->nombre_completo = $_POST["nombre"];
            $this->usrDTO->admin_pwd = $_POST["adminContrasena"];
            $errorsArr = ContactVerifications::verifyingUpdate($this->contDTO, $this->usrDTO);
            
            try{
                if(empty($errorArr["adminContrasena"]))
                    $isRejection = Utils::setAdminVerification($this->usrDTO, $this->usrParticularSrv);
                if(sizeof($isRejection) > 0)
                    $errorsArr = $isRejection;

                (sizeof($errorsArr) === 0) ? $this->contService->updateChild($this->contDTO) :
                    $_SESSION["errors"] = $errorsArr;

                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "Se modificó al contacto con ID ".$this->contDTO->contact_id." con éxito";

            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["updateClientException"] = "Hubo un problema dentro del proceso de modificación del contacto, posible corte de conexión a la base de datos";
            }finally{
                header("Location: ".base_url."home/?homeController=enterprise&homeAction=index");
                exit;
            }

        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function enableOrDisableContact(){
        if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

            $this->contDTO->contact_id = $_POST["contactoId"];
            $this->contDTO->visibilidad = $_POST["visibilidad"];
            $errorArr = SwitchVerification::verifyingSwitch($this->contDTO);
            $str_portion_one = ($this->contDTO->visibilidad === "DISABLED") ? 
            "desactivó":"activó";
            $str_portion_two = ($this->contDTO->visibilidad === "DISABLED") ? 
            "desactivar":"activar";
            
            try{
                (sizeof($errorArr) === 0) ?
                    $this->contService->updateVisibility($this->contDTO) :
                    $_SESSION["errors"] = $errorArr;

                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "Se "
                        .$str_portion_one." el contacto con ID ".$this->contDTO->contact_id." con éxito";
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(AutomaticValueException $ex){
                $_SESSION["exceptions"]["visibilityEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["disableTypeEx"] = "No se logró ".$str_portion_two." el contacto con ID ".$this->contDTO->contact_id.", posible corte de conexión a la base de datos";
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