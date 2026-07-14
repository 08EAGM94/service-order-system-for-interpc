<?php

class FollowupformController{
    private $binnDTO, $binnService, $binnParticularSrv;
    public function __construct($binnDTO, $binnService, $binnParticularSrv){
        $this->binnDTO = $binnDTO;
        $this->binnService = $binnService;
        $this->binnParticularSrv = $binnParticularSrv;
    }

    public function index(){
        if(!empty($_SESSION["identity"]) && !empty($_GET["id"])){
            $_SESSION['LAST_ACTIVITY'] = time();

            try{
                $this->binnDTO->binnacle_id = $_GET["id"];
                $this->binnDTO->usuario_id = $_SESSION["identity"]["Id"];
                $info = $this->binnService->getInfo($this->binnDTO);
                Utils::isAuthorizedBinnacle($info);
                (!empty($info["Actividades_realizadas"])) ?
                        $info_verified = '../views/finishingLayouts/consentInfo.php' :
                        $info_verified = '../views/finishingLayouts/remindedfields.php';
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unKnownEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(UnauthorizedDataException $ex){
                $_SESSION["exceptions"]["unauthorizedEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["remindedOrConsentReportEx"] = "No se logró obtener "
                                ."la información necesaria para el seguimiento de "
                                ."bitácoras, se cortó la conexión a la base de datos.";                    
            }finally{

                if(!empty($_SESSION["exceptions"])){
                    header("Location: ".base_url."home/");
                    exit;        
                }
                           
                require_once $info_verified;                
            }
                
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function techsign(){
        
        if(!empty($_SESSION["identity"]) && !empty($_SESSION["formSession"]["dataSelectionForSigns"])){ 
            $_SESSION['LAST_ACTIVITY'] = time();

            require_once '../views/finishingLayouts/technicianCanvas.php';
            require_once '../views/finishingLayouts/absoluteElems.php';            
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function clientsign(){
        if(!empty($_SESSION["identity"]) && 
                !empty($_SESSION["formSession"]["dataSelectionForSigns"]["binnId"])){
            $_SESSION['LAST_ACTIVITY'] = time();

            require_once '../views/finishingLayouts/clientCanvas.php';
            require_once '../views/finishingLayouts/absoluteElems.php';
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    public function followupPartial(){
        if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){
            
            $this->binnDTO->binnacle_id = $_POST["id"];
            $this->binnDTO->usuario_id = $_SESSION["identity"]["Id"];
            $this->binnDTO->Actividades_realizadas = $_POST["seHizo"];
            $this->binnDTO->observaciones = $_POST["observaciones"];
            $this->binnDTO->inicio = $_POST["binnFecha"];
            $this->binnDTO->estatus = $_POST["estatus"];                
            $errorArr = BinnacleVerifications::verifyingFollowUpPartial($this->binnDTO);

            try{
                (sizeof($errorArr) === 0) ? $this->binnParticularSrv->followUpPartial($this->binnDTO) :
                    $_SESSION["errors"] = $errorArr;                           
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["followupExeption"] = "No se actualizó la "
                                ."bitacora con Id: ".$this->binnDTO->binnacle_id.
                                ", probable corte de conexión a la base de datos";                    
            }finally{
                if(!empty($_SESSION["exceptions"])){
                    header("Location: ".base_url."home/?homeController=binnacle&homeAction=followuplist");
                    exit;            
                }
                
                header("Location: ".base_url."finishing/?controller=followupform&action=index&id=".
                    $this->binnDTO->binnacle_id);
                exit;
            }

        }else{
            header("Location: ".base_url."home/");
            exit;
        }    
    }
    public function resetActivitiesDescriptions(){
        if(!empty($_SESSION["identity"]) && !empty($_GET["id"])){
            
            try{
                $this->binnDTO->binnacle_id = $_GET["id"];
                $this->binnDTO->usuario_id = $_SESSION["identity"]["Id"];
                $this->binnParticularSrv->resetActivities($this->binnDTO);
                $_SESSION["success"] = "Puedes actualizar las actividades después";                    
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(UnauthorizedDataException $ex){
                $_SESSION["exceptions"]["unauthorizedEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["resetActivitiesException"] = "No se pudo "
                                . "reiniciar las actividades en la bitacora con Id: "
                                .$this->binnDTO->binnacle_id.", probable corte de conexión a la base de datos";                
            }finally{
                if(!empty($_SESSION["exceptions"])){
                    header("Location: ".base_url."home/?homeController=binnacle&homeAction=followuplist");
                    exit;
                }
                
                header("Location: ".base_url."finishing/?controller=followupform&action=index&id=".$this->binnDTO->binnacle_id);
                exit;
            }

        }else{
            header("Location: ".base_url."home/");
            exit;
        }    
    }
    public function cancellingBinn(){
        if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){
            
            $this->binnDTO->binnacle_id = $_POST["cancelwithid"];
            $this->binnDTO->usuario_id = $_SESSION["identity"]["Id"];
            $this->binnDTO->estatus = $_POST["cancelestatus"];
            $this->binnDTO->cancel_desc = $_POST["cancelacion"];    
            $errorArr = BinnacleVerifications::verifyingCancelDescription($this->binnDTO);

            try{
                (sizeof($errorArr) === 0) ? $this->binnParticularSrv->cancelBinnacle($this->binnDTO) :
                    $_SESSION["errors"] = $errorArr;
                
                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "La bitacora con "
                                ."Id: ".$this->binnDTO->binnacle_id." Se canceló con éxito.";        
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["followupCancelExeption"] = "No se pudo cancelar"
                                . " la bitacora con Id: ".$this->binnDTO->binnacle_id." probable"
                                . " corte de conexión a la base de datos";
            }finally{
                if(!empty($_SESSION["errors"])){
                    header("Location: ".base_url."finishing/?controller=followupform&action=index&id=".$this->binnDTO->binnacle_id);
                    exit;
                }

                header("Location: ".base_url."home/?homeController=binnacle&homeAction=followuplist");
                exit;   
            }
                
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }
    public function finishbinnacle(){
        if(!empty($_SESSION["identity"])                &&
            !empty($_SESSION["formSession"]["dataSelectionForSigns"])   &&     
            !empty($_SESSION["formSession"]["clientSignature"])         && 
            !empty($_SESSION["identity"]["Firma"])){
                
                $this->binnDTO->binnacle_id = $_SESSION["formSession"]["dataSelectionForSigns"]["binnId"];
                $this->binnDTO->usuario_id = $_SESSION["identity"]["Id"];
                $this->binnDTO->firma_cliente = $_SESSION["formSession"]["clientSignature"];

                try {
                    $this->binnParticularSrv->finishBinnacle($this->binnDTO);
                    $_SESSION["success"] = "La bitacora con Id: "
                            .$this->binnDTO->binnacle_id. " ha sido finalizada correctamente";
                }catch(WrongObjectException $ex){
                    $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
                }catch(EntityException $ex){
                    $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
                }catch(Exception $ex) {
                    $_SESSION["exceptions"]["binnFinishingException"] = "no se pudo finalizar la bitacora"
                            . " con Id: " .$this->binnDTO->binnacle_id. " probable falta de conexión";
                }finally{
                    if(!empty($_SESSION["exceptions"]))
                        unlink("uploads/firmas/".$_SESSION["formSession"]["clientSignature"]);

                    Utils::unsetFormSessions();

                    header("Location: ".base_url."home/?homeController=binnacle&homeAction=followuplist");
                    exit;
                }

        } else {
            header("Location: ".base_url."home/");
            exit;
        }
    }
}