<?php

class UserController{
    private $usrDTO, $usrService, $usrSignService, $usrParticularSrv;
    public function __construct($usrDTO, $usrService, $usrSignService, $usrParticularSrv){
        $this->usrDTO = $usrDTO;
        $this->usrService = $usrService;
        $this->usrSignService = $usrSignService;
        $this->usrParticularSrv = $usrParticularSrv;                    
    }
    public function index(){
        if(!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            require_once '../views/adminLayouts/userInsertForm.php';
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    public function editSign(){
        if(!empty($_SESSION["identity"])){
            $_SESSION['LAST_ACTIVITY'] = time();

            $get_params = "";
            NewUtils::setIdSession();

            try{
                if(!empty($_SESSION["isAdmin"]))
                    $users = $this->usrService->getAllInfo();

                if(!empty($_SESSION["idSession"]["userSign_userId"])){

                    $this->usrDTO->user_id = $_SESSION["idSession"]["userSign_userId"];
                    $user_info = $this->usrService->getInfo($this->usrDTO);
                    
                    if(!empty($user_info["Firma"])){
                        if(!file_exists("../finishing/uploads/firmas/".$user_info["Firma"])){
                            $this->usrSignService->insertSignature($this->usrDTO);
                            $user_info = $this->usrService->getInfo($this->usrDTO);
                        }
                    }
                }

                if($_SESSION["identity"]["Privilegio"] === "user"){

                    $this->usrDTO->user_id = $_SESSION["identity"]["Id"];

                    if(!empty($_SESSION["identity"]["Firma"])){
                        if(!file_exists("../finishing/uploads/firmas/".$_SESSION["identity"]["Firma"])){
                            $this->usrSignService->insertSignature($this->usrDTO);
                            $_SESSION["identity"]["Firma"] = null;
                        }
                    }
                }
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["gettingUsersException"] = "No se pudo hacer comprobaciones necesarias ".
                "para la edición de firma, posible corte de conexión a la base de datos";
                $users = [];
                $user_info = [];                
            }finally{
                require_once '../views/userLayouts/editSign.php';
                exit;
            }

        }else{
            header("Location: ".base_url."home/");
            exit;
        }
        
    }

    public function userNewPassword(){
        if(!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            
            $get_params = "";
            NewUtils::setIdSession();

            try{

                $users = $this->usrService->getAllInfo();

                if(!empty($_SESSION["userNewPwd_userId"])){
                    $this->usrDTO->user_id = $_SESSION["userNewPwd_userId"];
                    $user_info = $this->usrService->getInfo($this->usrDTO);
                }
                
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage(); 
            }catch(Exception $ex){
                $_SESSION["exception"]["userInfoException"] = "No se logró conseguir "
                            ."la información del usuario, posible corte "
                            ."de conexión a la base de datos";
                $users = [];
                $user_info = [];             
            }finally{
                require_once '../views/adminLayouts/userNewPwd.php';
            }

        }else{
            header("Location: ".base_url."home/");
            exit; 
        }
    }

    public function login(){
        
        if(sizeof($_POST) > 0){

            $this->usrDTO->alias = $_POST["user"];
            $this->usrDTO->contrasena = $_POST["pwd"];
            $errorArr = UserVerifications::verifyingLogin($this->usrDTO);

            try{
                
                if(sizeof($errorArr) === 0){
                    
                    $possible_user = $this->usrParticularSrv->login($this->usrDTO);
                    
                    (empty($possible_user["loginFailed"])) ?
                        $_SESSION["identity"] = $possible_user :
                        $_SESSION["errors"] = $possible_user;
                    
                    if(!empty($_SESSION["identity"]))
                        Utils::setAdminWithVerify();
                }else{
                    $_SESSION["errors"] = $errorArr;
                }

            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["withoutConnextion"] = "Se ha cortado la conexión a la base de datos";
            }finally{
                header("Location: ".base_url."home/");
                exit;
            }

        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    public function logout(){
        
        if(!empty($_SESSION["identity"])){
            
            unset($_SESSION["identity"]);
            session_destroy();
            header("Location: ". base_url."home/");
            exit;
        }
        header("Location: ". base_url."home/");
        exit;
    }

    public function insertDBUser(){
        if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){
            
            $this->usrDTO->nombre = $_POST["nombre"];
            $this->usrDTO->apellidos = $_POST["apellidos"];
            $this->usrDTO->alias = $_POST["alias"];
            $this->usrDTO->contrasena = $_POST["contrasena"];
            $this->usrDTO->conf_pwd = $_POST["confContrasena"];
            $this->usrDTO->privilegio = $_POST["privilegio"];
            $this->usrDTO->admin_pwd = $_POST["adminContrasena"];
            $errorArr = UserVerifications::verifyingLogin($this->usrDTO);

            try{
                
                if(empty($errorArr["adminContrasena"]))
                    $isRejection = NewUtils::setAdminVerification($this->usrDTO, $this->usrParticularSrv);
                if(sizeof($isRejection) > 0)
                    $errorArr = $isRejection;

                (sizeof($errorArr) === 0) ?
                    $this->usrService->insertInfo($this->usrDTO) :
                    $_SESSION["errors"] = $errorArr;

            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["userDataException"] = "Acción fallida, probable nombre de usuario existente en la base de datos o falta de conexión a la base de datos";
            }finally{
                header("Location: ".base_url."home/?homeController=user&homeAction=createUser");
                exit;
            }

        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    public function updateUserPassword(){
        if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

            $this->usrDTO->user_id = $_POST["usuarioId"];
            $this->usrDTO->contrasena = $_POST["contrasena"];
            $this->usrDTO->conf_pwd = $_POST["confContrasena"];
            $this->usrDTO->admin_pwd = $_POST["adminContrasena"];
            $errorArr = UserVerifications::verifyingUpdate($this->usrDTO);

            try{
                if(empty($errorArr["adminContrasena"]))
                    $isRejection = NewUtils::setAdminVerification($this->usrDTO, $this->usrParticularSrv);
                if(sizeof($isRejection) > 0)
                    $errorArr = $isRejection;

                (sizeof($errorArr) === 0) ?
                    $this->usrService->updateInfo($this->usrDTO) :
                    $_SESSION["errors"] = $errorArr;

                if(empty($_SESSION["errors"]))
                    $_SESSION["success"]["userPWDSucceed"] = "La contraseña se reestableció "
                            . "con éxito";    
            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["userPWDException"] = "No se pudo reestablecer "
                            ."la contraseña, posible corte de conexión a la base de datos";
            }finally{
                header("Location: ".base_url."home/?homeController=user&homeAction=userNewPassword");
                exit;
            }

        }else{
            header("Location: ".base_url."home/");
            exit; 
        }
    }

    public function disableUser(){
        if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

            $this->usrDTO->user_id = $_POST["usuarioId"];
            $this->usrDTO->visibilidad = $_POST["visibilidad"];
            $this->usrDTO->admin_pwd = $_POST["adminContrasena"];
            $errorArr = SwitchVerification::verifyingSwitch($this->usrDTO);

            try{
                if(empty($errorArr["adminContrasena"]))
                    $isRejection = NewUtils::setAdminVerification($this->usrDTO, $this->usrParticularSrv);
                if(sizeof($isRejection) > 0)
                    $errorArr = $isRejection;

                (sizeof($errorArr) === 0) ?
                    $this->usrService->updateVisibility($this->usrDTO) :
                    $_SESSION["errors"] = $errorArr;
                
                if(empty($_SESSION["errors"])){
                    $_SESSION["success"]["disableUserSuccess"] = "Se desactivó al usuario con éxito".
                    $_SESSION["idSession"]["userNewPwd_userId"] = false;
                }    
            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
            }catch(AutomaticValueException $ex){
                $_SESSION["exceptions"]["visibilityEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["disableUserEx"] = "No se logró desactivar al usuario, posible corte "
                            . "de conexión a la base de datos";
            }finally{
                header("Location: ".base_url."home/?homeController=user&homeAction=userNewPassword");
                exit;
            }
             
        }else{
            header("Location: ".base_url."home/");
            exit; 
        }
    }
}