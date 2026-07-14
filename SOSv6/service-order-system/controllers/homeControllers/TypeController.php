<?php

class TypeController{

    private $typeDTO, $usrDTO, $typeService, $usrParticularSrv; 
    public function __construct($typeDTO, $usrDTO, $typeService, $usrParticularSrv){
        $this->typeDTO = $typeDTO;
        $this->usrDTO = $usrDTO;
        $this->typeService = $typeService;
        $this->usrParticularSrv = $usrParticularSrv;
    }

    public function index(){
        if(!empty($_SESSION["identity"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            
            require_once '../views/userLayouts/newTypeForm.php';
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    public function editTypes(){
        if(!empty($_SESSION["isAdmin"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            
            try {
                $types_arr = $this->typeService->getAllInfo();
            } catch (Exception $ex) {
                $_SESSION["exceptions"]["typesDataForEditionEx"] = "No se logró conseguir los datos de los registros de tipos, lo más probable es que se haya "
                        . "cortado la conexión a la base de datos";
                $types_arr = [];
            }finally{
                require_once '../views/adminLayouts/typesEditForms.php';
            }

        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    public function insertType(){
        if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){

            $this->typeDTO->tipo = $_POST["tipo"];
            $errorArr = TypeVerifications::verifyingInsertion($this->typeDTO);
            try{
                (sizeof($errorArr) === 0) ? $this->typeService->insertInfo($this->typeDTO) :
                    $_SESSION["errors"] = $errorArr;

                if(empty($_SESSION["errors"]))    
                    $_SESSION["success"] = "El tipo de equipo ha sido creado con éxito";
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["typeInsertionException"] = "Se generó un "
                            ."error interactuando con la base de datos "
                            ."en cuanto a la inserción de un tipo de equipo, "
                            ."lo más probable es que se haya ingresado un tipo "
                            ."existente en la base de datos o se haya cortado la conexión a "
                            . "la base de datos";
            }finally{
                header("Location: ".base_url."home/?homeController=type&homeAction=index");
                exit;
            }
            
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function updateTypeInfo(){
        if(!empty($_SESSION["isAdmin"])  && sizeof($_POST) > 0){
            
            $this->typeDTO->type_id = $_POST["tipoId"];
            $this->typeDTO->tipo = $_POST["tipo"];
            $this->usrDTO->admin_pwd = $_POST["adminContrasena"];
            $errorArr = TypeVerifications::verifyingUpdate($this->typeDTO, $this->usrDTO);
            
            try{
                if(empty($errorArr["adminContrasena"]))
                    $isRejection = Utils::setAdminVerification($this->usrDTO, $this->usrParticularSrv);
                if(sizeof($isRejection) > 0)
                    $errorArr = $isRejection;
                
                (sizeof($errorArr) === 0) ?
                    $this->typeService->updateInfo($this->typeDTO) :
                    $_SESSION["errors"] = $errorArr;
                
                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "Se modificó el tipo con ID ".$this->typeDTO->type_id." con éxito";    
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["updateTypeException"] = "Se generó un "
                            ."error interactuando con la base de datos "
                            ."en cuanto a la actualización de un tipo de equipo, "
                            ."lo más probable es que se haya ingresado un tipo "
                            ."existente en la base de datos o se haya cortado la conexión a "
                            . "la base de datos";
            }finally{
                header("Location: ".base_url."home/?homeController=type&homeAction=editTypes");
                exit;
            }

        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function enableOrDisableType(){
        if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

            $this->typeDTO->type_id = $_POST["tipoId"];
            $this->typeDTO->visibilidad = $_POST["visibilidad"];
            $errorArr = SwitchVerification::verifyingSwitch($this->typeDTO);
            $str_portion_one = ($this->typeDTO->visibilidad === "DISABLED") ? 
            "desactivó":"activó";
            $str_portion_two = ($this->typeDTO->visibilidad === "DISABLED") ? 
            "desactivar":"activar";
            
            try{
                (sizeof($errorArr) === 0) ?
                    $this->typeService->updateVisibility($this->typeDTO) :
                    $_SESSION["errors"] = $errorArr;

                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "Se "
                        .$str_portion_one." el tipo con ID ".$this->typeDTO->type_id." con éxito";
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(AutomaticValueException $ex){
                $_SESSION["exceptions"]["visibilityEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["disableTypeEx"] = "No se logró ".$str_portion_two." el tipo con ID ".$this->typeDTO->type_id.", posible corte de conexión a la base de datos";
            }finally{
                header("Location: ".base_url."home/?homeController=type&homeAction=editTypes");
                exit;
            }
             
        }else{
            header("Location: ".base_url."home/");
            exit; 
        }
    }
}