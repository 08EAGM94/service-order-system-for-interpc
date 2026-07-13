<?php
/* 
class UserController{
    private $usrDTO, $usrService, $usrSignService, $usrParticularSrv;
    public function __construct($usrDTO, $usrService, $usrSignService, $usrParticularSrv){
        $this->usrDTO = $usrDTO;
        $this->usrService = $usrService;
        $this->usrSignService = $usrSignService;
        $this->usrParticularSrv = $usrParticularSrv;                    
    }
    
    este método controla la generación de la vista del formulario de creación de usuario, si
    un usuario no ha accedido a la aplicación y/o no es administrador, el método invocará un header
    tipo location que enviará al usuario a la raíz del index "home", si el usuario
    se a logeado pero no es administrador, la raíz index será el menú principal para "user", si no está 
    logeado, la raíz index será el login. Se define $_SESSION['LAST_ACTIVITY'] con el tiempo actual en 
    cada vista ya que en esta aplicación esta pensado para que la sesión expire después de 30 minutos.     
    public function index(){
        if(!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            require_once '../views/adminLayouts/userInsertForm.php';
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    este método controla la generación de la vista del formulario de edición de firma, si
    un usuario no ha accedido a la aplicación, el método invocará un header
    tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login. El método usa el helper "setIdSession" 
    para generar una sesión con indice "idSession", esto para conservar la elección del 
    usuario al momento de ejecutar el formulario de búsqueda, en este caso, de algún usuario.
    $users contiene todas las filas de la tabla Usuarios gracias al adaptador primario
    CommonService cuyo constructor contiene dependencias relacionadas a Usuarios (usrService);
    $users es necesario para dar forma al select del formulario de busqueda de esta vista.
    Si $_SESSION["idSession"]["userSign_userId"] está definido, entonces se obtendrá la 
    información del usuario seleccionado gracias a usrService y la definición user_id de usrDTO, 
    si en la información del usuario el campo no tiene algún falsy (nulo o string vacío) entonces 
    se evalúa si la firma existe en nuestra ruta de carpetas destinadas a firmas, si no existe se 
    actualizará el campo firma del usuario con un valor null ya que todos las propiedades de los DTO 
    se inicializan con null en sus constructores, una vez actualizado el campo Firma, se vuelve a 
    obtener la información del usuario para actualizar la variable $user_info. Si el usuario logeado 
    tiene privilegio "user", se hará la misma evaluación de firma existente, si no existe,
    se actualiza el campo firma del usuario en null y el indice "Firma" de identity será setteado con null.
    $user_info es una variable definida si el usuario logeado es un administrador y se utilizará en la vista
    para definir los valores de los inputs ocultos del formulario de edición de firma; en dado caso de que 
    el privilegio sea "user", se usará los indices de la sesión identity para definir los valores de los inputs.
    Puede interceptar 3 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, UsuariosEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.

    public function editSign(){
        if(!empty($_SESSION["identity"])){
            $_SESSION['LAST_ACTIVITY'] = time();

            Utils::setIdSession();

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
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["gettingUsersException"] = "No se pudo hacer comprobaciones necesarias ".
                "para la edición de firma, posible corte de conexión a la base de datos";

                if(!empty($_SESSION["isAdmin"])){
                    $users = [];
                    $user_info = [];
                }
                                
            }finally{
                require_once '../views/userLayouts/editSign.php';
            }

        }else{
            header("Location: ".base_url."home/");
            exit;
        }
        
    }

    este método controla la generación de la vista del formulario de actualización de contraseña, si
    un usuario no ha accedido a la aplicación y/o no es adiministrador, el método invocará un header
    tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login, si lo está y no es administrador, la raíz index 
    será el menú para "user". El método usa el helper "setIdSession" para generar una sesión con indice 
    "idSession", esto para conservar la elección del usuario al momento de ejecutar el formulario de 
    búsqueda, en este caso, de algún usuario. $users contiene todas las filas de la tabla Usuarios gracias 
    al adaptador primario CommonService cuyo constructor contiene dependencias relacionadas a Usuarios (usrService);
    $users es necesario para dar forma al select del formulario de busqueda de esta vista. Si 
    $_SESSION["idSession"]["userNewPwd_userId"] está definida con algún valor, se usará ese valor para definir 
    la propiedad user_id de usrDTO para que el servicio (usrService) pueda obtener los datos del usuario en cuestión 
    y guardarlos en $user_info. $user_info lo usa la vista para definir los valores de los inputs deshabilitados 
    del formulario por motivos puramente informativos.
    Puede interceptar 3 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, UsuariosEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.

    public function userNewPassword(){
        if(!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            
            Utils::setIdSession();

            try{

                $users = $this->usrService->getAllInfo();

                if(!empty($_SESSION["idSession"]["userNewPwd_userId"])){
                    $this->usrDTO->user_id = $_SESSION["idSession"]["userNewPwd_userId"];
                    $user_info = $this->usrService->getInfo($this->usrDTO);
                }
                
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
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

    Este método es invocado dentro del action del formulario de la vista del login, por lo que la variable 
    superglobal post debe estar definida con un número de indices mayor a cero, en caso contrario, el método invocará un header
    tipo location que enviará al usuario a la raíz del index "home", en este caso en particular la raíz index será 
    el mismo formulario del login. los indices de post son usados para definir los campos del dto (usrDTO) relacionados a estos 
    indices, después, el dto es evaluado por un método estatico de una de nuestras clases de varificación de datos, si el dto pasa
    todas las verificaciones, la variable $errorArr será un array vacío. si $errorArr no es un array vacío, entonces la sesión "errors"
    se definirá con este array, en caso contrario se usará el adaptador primario UserService definido en el inyector de 
    dependencias como "usrParticularSrv", el método login de este servicio puede devolver un array con la información del usuario o un 
    array con el indice "loginFailed", en dado caso de que este indice no esté definido, los datos del usuario serán usados 
    para definir la sesión "identity", en caso contrario, se definirá la sesión errors con "loginFailed"; después, si la sesión 
    identity esta definida, entonces se usará el helper setAdminWithVerify el cual verifica el indice Privilegio de identity, en caso 
    de que sea "Admin", se definira la sesión "isAdmin" con true. si el try fue satisfactorio o entró en el catch, el método invocará
    el header tipo location el cual enviará al usuario a la raiz del index de "home", si el try fue satisfactorio puede enviar al logeado a
    la vista del menu de "user" o el mensaje de bienvenida de "Admin", en caso contrario, la vista será el formulario del login en el cual
    se mostrará flags de errores o excepciones.
    Puede interceptar 4 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; UnknownInDataBaseException es si el método login arrojó esta 
    excepción en el caso de que la petición a la base de datos para obtener el registro del usuario por su alias 
    devuelva un array vacío; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, UsuariosEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.

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

            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["withoutConnextion"] = "Se ha cortado la conexión a la base de datos". $ex;
            }finally{
                header("Location: ".base_url."home/");
                exit;
            }

        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    Este método elimina todos los indices de la sesión si se detecta si $_SESSION["identity"] está definido.

    public function logout(){
        
        if(!empty($_SESSION["identity"]))
            session_unset();

        header("Location: ". base_url."home/");
        exit;
    }

    Este método es invocado dentro del action del formulario de la vista de creación de usuario, por lo que la variable 
    superglobal post debe estar definida con un número de indices mayor a cero, en caso contrario, el método invocará un header
    tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login, si lo está puede enviar al logeado a
    la vista del menu de "user" o el mensaje de bienvenida de "Admin". los indices de post son usados para definir los campos del 
    dto (usrDTO) relacionados a estos indices, después, el dto es evaluado por un método estatico de una de nuestras clases de 
    varificación de datos, si el dto pasa todas las verificaciones, la variable $errorArr será un array vacío.
    En este caso se esta inicializando la propiedad admin_pwd de usrDTO el cual será usado en el helper setAdminVerification 
    junto con el puerto primario UserService llamado en la inyección de dependencias como "usrParticularSrv", este helper puede
    devolver un array vacío o un array con el indice "adminPWDRejected", si $isRejection tiene ese array, entonces se definirá a
    $errorArr con ese array, en dado caso de que $errorArr sea un array vacío, se usará el adaptador primario CommonService de 
    Usuarios (usrService) para crear el registro en la base de datos, en caso contrario, se definirá la sesión errors con lo que
    contiene $errorArr, si la inserción fue satisfactoria, se inicializará la sesión "success"; si el try fue exitoso o no, el 
    método invocará el header location que enviará al usuario al formulario de creación de usuario donde podrá ver el flag de 
    success o los flags de errores y excepciones.
    Puede interceptar 4 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; UnknownInDataBaseException es si el método adminPwdConfirmation arrojó esta 
    excepción en el caso de que la petición a la base de datos para obtener el registro del usuario por su alias 
    devuelva un array vacío; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, UsuariosEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.


    public function insertDBUser(){
        if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){
            
            $this->usrDTO->nombre = $_POST["nombre"];
            $this->usrDTO->apellidos = $_POST["apellidos"];
            $this->usrDTO->alias = $_POST["alias"];
            $this->usrDTO->contrasena = $_POST["contrasena"];
            $this->usrDTO->conf_pwd = $_POST["confContrasena"];
            $this->usrDTO->privilegio = $_POST["privilegio"];
            $this->usrDTO->admin_pwd = $_POST["adminContrasena"];
            $errorArr = UserVerifications::verifyingInsertion($this->usrDTO);

            try{
                
                if(empty($errorArr["adminContrasena"]))
                    $isRejection = Utils::setAdminVerification($this->usrDTO, $this->usrParticularSrv);
                if(sizeof($isRejection) > 0)
                    $errorArr = $isRejection;

                (sizeof($errorArr) === 0) ?
                    $this->usrService->insertInfo($this->usrDTO) :
                    $_SESSION["errors"] = $errorArr;

                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "El usuario ha sido creado con éxito";
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["userDataException"] = "Acción fallida, probable nombre de usuario existente en la base de datos o falta de conexión a la base de datos";
            }finally{
                header("Location: ".base_url."home/?homeController=user&homeAction=index");
                exit;
            }

        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    Este método es invocado dentro del action del formulario de la vista de actualización de contraseña, por lo que la variable 
    superglobal post debe estar definida con un número de indices mayor a cero, en caso contrario, el método invocará un header
    tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login, si lo está puede enviar al logeado a
    la vista del menu de "user" o el mensaje de bienvenida de "Admin". los indices de post son usados para definir los campos del 
    dto (usrDTO) relacionados a estos indices, después, el dto es evaluado por un método estatico de una de nuestras clases de 
    varificación de datos, si el dto pasa todas las verificaciones, la variable $errorArr será un array vacío.
    En este caso se esta inicializando la propiedad admin_pwd de usrDTO el cual será usado en el helper setAdminVerification 
    junto con el puerto primario UserService llamado en la inyección de dependencias como "usrParticularSrv", este helper puede
    devolver un array vacío o un array con el indice "adminPWDRejected", si $isRejection tiene ese array, entonces se definirá a
    $errorArr con ese array, en dado caso de que $errorArr sea un array vacío, se usará el adaptador primario CommonService de 
    Usuarios (usrService) para actualizar un registro en especifico en la base de datos, en caso contrario, se definirá la sesión 
    errors con lo que contiene $errorArr, si la actualización fue satisfactoria, se inicializará la sesión "success"; si el try fue 
    exitoso o no, el método invocará el header location que enviará al usuario al formulario de actualización de contraseña donde 
    podrá ver el flag de success o los flags de errores y excepciones.
    Puede interceptar 4 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; UnknownInDataBaseException es si el método adminPwdConfirmation arrojó esta 
    excepción en el caso de que la petición a la base de datos para obtener el registro del usuario por su alias 
    devuelva un array vacío; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, UsuariosEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.

    public function updateUserPassword(){
        if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

            $this->usrDTO->user_id = $_POST["usuarioId"];
            $this->usrDTO->contrasena = $_POST["contrasena"];
            $this->usrDTO->conf_pwd = $_POST["confContrasena"];
            $this->usrDTO->admin_pwd = $_POST["adminContrasena"];
            $errorArr = UserVerifications::verifyingUpdate($this->usrDTO);

            try{
                if(empty($errorArr["adminContrasena"]))
                    $isRejection = Utils::setAdminVerification($this->usrDTO, $this->usrParticularSrv);
                if(sizeof($isRejection) > 0)
                    $errorArr = $isRejection;

                (sizeof($errorArr) === 0) ?
                    $this->usrService->updateInfo($this->usrDTO) :
                    $_SESSION["errors"] = $errorArr;

                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "La contraseña se reestableció "
                            . "con éxito";    
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
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

    Este método es invocado dentro del action del formulario de la ventana emergente para inhabilitar de la vista de actualización 
    de contraseña, por lo que la variable superglobal post debe estar definida con un número de indices mayor a cero, en caso contrario, 
    el método invocará un header tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login, si lo está puede enviar al logeado a
    la vista del menu de "user" o el mensaje de bienvenida de "Admin". los indices de post son usados para definir los campos del 
    dto (usrDTO) relacionados a estos indices, después, el dto es evaluado por un método estatico de una de nuestras clases de 
    varificación de datos, si el dto pasa todas las verificaciones, la variable $errorArr será un array vacío.
    En este caso se esta inicializando la propiedad admin_pwd de usrDTO el cual será usado en el helper setAdminVerification 
    junto con el puerto primario UserService llamado en la inyección de dependencias como "usrParticularSrv", este helper puede
    devolver un array vacío o un array con el indice "adminPWDRejected", si $isRejection tiene ese array, entonces se definirá a
    $errorArr con ese array, en dado caso de que $errorArr sea un array vacío, se usará el adaptador primario CommonService de 
    Usuarios (usrService) para actualizar la visibilidad de un registro en especifico en la base de datos, en caso contrario, se 
    definirá la sesión errors con lo que contiene $errorArr, si la actualización fue satisfactoria, se inicializará la sesión 
    "success"; si el try fue exitoso o no, el método invocará el header location que enviará al usuario al formulario de actualización 
    de contraseña donde podrá ver el flag de success o los flags de errores y excepciones.
    Puede interceptar 5 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; UnknownInDataBaseException es si el método adminPwdConfirmation arrojó esta 
    excepción en el caso de que la petición a la base de datos para obtener el registro del usuario por su alias 
    devuelva un array vacío; AutomaticValueException es si en el método updateVisibility de CommonService arroja esta excepción 
    en dado caso de que el valor de la propiedad visibilidad sea diferente a "ENABLED" o "DISABLED"; EntityException es si se envió 
    datos que no pasa las evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, UsuariosEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.

    public function disableUser(){
        if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

            $this->usrDTO->user_id = $_POST["usuarioId"];
            $this->usrDTO->visibilidad = $_POST["visibilidad"];
            $this->usrDTO->admin_pwd = $_POST["adminContrasena"];
            $errorArr = SwitchVerification::verifyingSwitch($this->usrDTO);

            try{
                if(empty($errorArr["adminContrasena"]))
                    $isRejection = Utils::setAdminVerification($this->usrDTO, $this->usrParticularSrv);
                if(sizeof($isRejection) > 0)
                    $errorArr = $isRejection;

                (sizeof($errorArr) === 0) ?
                    $this->usrService->updateVisibility($this->usrDTO) :
                    $_SESSION["errors"] = $errorArr;
                
                if(empty($_SESSION["errors"])){
                    $_SESSION["success"] = "Se desactivó al usuario con éxito";
                    $_SESSION["idSession"]["userNewPwd_userId"] = false;
                }    
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
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
*/

beforeEach(function(){
    $this->container = testContainerFactory();
    $this->base_url = 'http://localhost:8081/SOSv5/service-order-system/';
});

afterEach(function(){
    $_SESSION = [];
    $_POST = [];
    $_GET = [];
    cleanTable($this->container->make('SOSTestDatabase'), "Usuarios");
});

test('prueba método index, caso usuario "admin"', function(){

    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $result = mockUserIndex();

    expect($_SESSION['LAST_ACTIVITY'])->toBeInt();
    expect($result)->toBe('../views/adminLayouts/userInsertForm.php');
});

test('prueba método index, caso usuario "user"', function(){

    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'user'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $result = mockUserIndex();

    expect(isset($_SESSION['LAST_ACTIVITY']))->toBeFalse();
    expect($result)->toBe("Location: ".$this->base_url."home/");
});

test('prueba método editSign, caso usuario "user"', function(){

    $usrDTO = $this->container->make('usrDTO');
    $usrSignService = $this->container->make('usrSignService');

    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'user'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }
    
    $values = mockEditSign($this->container, $usrDTO, $usrSignService);

    expect($_SESSION['LAST_ACTIVITY'])->toBeInt();
    expect($_SESSION["identity"]["Alias"])->toBe('theGEAr94');
    expect($_SESSION["identity"]["Firma"])->toBeNull();
    expect($values["result"])->toBe('../views/userLayouts/editSign.php');
});

test('prueba método editSign, caso usuario "admin"', function(){

    $usrDTO = $this->container->make('usrDTO');
    $usrSignService = $this->container->make('usrSignService');

    //creación de usuarios para el test
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        $getting_users = $this->container->make('usrService')->getAllInfo();
        
        //Simulando elección de un usuario
        $_GET["homeAction"] = "editSign";
        $_POST["usuarios"] = $getting_users[1]["Id"];
        mockSetIdSession();
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $values = mockEditSign($this->container, $usrDTO, $usrSignService);

    expect($_SESSION['LAST_ACTIVITY'])->toBeInt();
    expect($values["users"])->toHaveLength(3);
    expect(intval($_SESSION["idSession"]["userSign_userId"]))->toBeInt();
    expect($values["user_info"]["Firma"])->toBeNull();
    expect($values["result"])->toBe($_SESSION["header"]);
});

test('prueba método userNewPassword', function(){

    //creación de usuarios para el test
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        $getting_users = $this->container->make('usrService')->getAllInfo();
        
        //Simulando elección de un usuario
        $_GET["homeAction"] = "userNewPassword";
        $_POST["usuarios"] = $getting_users[1]["Id"];
        mockSetIdSession();
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $values = mockUserNewPassword($this->container);

    expect($_SESSION['LAST_ACTIVITY'])->toBeInt();
    expect(intval($_SESSION["idSession"]["userNewPwd_userId"]))->toBeInt();
    expect($values['result'])->toBe($_SESSION["header"]);
    expect($values['user_info']["Nombre"])->toBe("Elena Aurora");
    expect($values['user_info']["Apellidos"])->toBe("Rodriguez");
    expect($values['user_info']["Alias"])->toBe("nena86");
    expect($values['user_info']["Privilegio"])->toBe("user");
});

test('prueba método login, caso satisfactorio', function(){

    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'user'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST["user"] = "theGEAr94";
    $_POST["pwd"] = "elRojoQueNoEsRojo";

    mockLogin($this->container);

    expect($_SESSION["identity"])->toHaveLength(6);
});

test('prueba método login, caso contraseña incorrecta', function(){

    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'user'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST["user"] = "theGEAr94";
    $_POST["pwd"] = "123456789";

    mockLogin($this->container);

    expect(isset($_SESSION["errors"]["loginFailed"]))->toBeTrue();
});

test('prueba método login, caso usuario inexistente', function(){

    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'user'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST["user"] = "elRojo";
    $_POST["pwd"] = "123456789";

    mockLogin($this->container);

    expect(isset($_SESSION["exceptions"]["unknownInDB"]))->toBeTrue();
});

test('prueba método login, caso campos invalidos', function(){

    $_POST["user"] = "<script></script>";
    $_POST["pwd"] = "<script></script>";

    mockLogin($this->container);

    expect(isset($_SESSION["errors"]["alias"]))->toBeTrue();
    expect(isset($_SESSION["errors"]["contrasena"]))->toBeTrue();
});

test('prueba método logout', function(){

    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    mockLogout();

    expect(isset($_SESSION["identity"]))->toBeFalse();
});

test('prueba método insertDBUser, caso satisfactorio', function(){

    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "nombre" => "Elena Aurora",
        "apellidos" => "Rodriguez",
        "alias" => "nena86",
        "contrasena" => "mi_nena",
        "confContrasena" => "mi_nena",
        "privilegio" => "user",
        "adminContrasena" => "elRojoQueNoEsRojo"
    ];

    $value = mockInsertDBUser($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Nombre" => "Elena Aurora",
        "Apellidos" => "Rodriguez",
        "Alias" => "nena86",
        "Privilegio" => "user"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), 'Usuarios');
    expect($value)->toBe("Location: ".$this->base_url."home/?homeController=user&homeAction=index");
});

test('prueba método insertDBUser, caso contraseña de administrador incorrecta', function(){

    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "nombre" => "Elena Aurora",
        "apellidos" => "Rodriguez",
        "alias" => "nena86",
        "contrasena" => "mi_nena",
        "confContrasena" => "mi_nena",
        "privilegio" => "user",
        "adminContrasena" => "123456789"
    ];

    $value = mockInsertDBUser($this->container);

    expect(isset($_SESSION["errors"]['adminPWDRejected']))->toBeTrue();
    expect([
        "Nombre" => "Elena Aurora",
        "Apellidos" => "Rodriguez",
        "Alias" => "nena86",
        "Privilegio" => "user"
    ])->toBeUnknownInDatabase($this->container->make('SOSTestDatabase'), 'Usuarios');
    expect($value)->toBe("Location: ".$this->base_url."home/?homeController=user&homeAction=index");
});

test('prueba método insertDBUser, caso campos invalidos', function(){

    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "nombre" => "08Elena Aurora86",
        "apellidos" => "%Rodriguez%",
        "alias" => "<script></script>",
        "contrasena" => "<script></script>",
        "confContrasena" => "mi_nena95",
        "privilegio" => "98465",
        "adminContrasena" => "elRojoQueNoEsRojo"
    ];

    $value = mockInsertDBUser($this->container);

    expect($_SESSION["errors"])->toHaveLength(6);
    expect($value)->toBe("Location: ".$this->base_url."home/?homeController=user&homeAction=index");
});

test('prueba método updateUserPassword, caso satisfactorio', function(){

    //creación de usuarios para el test
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

    $_POST = [
        "usuarioId" => $getting_users[1]["Id"],
        "contrasena" => "%Rodriguez%",
        "confContrasena" => "%Rodriguez%",
        "adminContrasena" => "elRojoQueNoEsRojo"
    ];

    $value = mockUpdateUserPassword($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/?homeController=user&homeAction=userNewPassword");
});

test('prueba método updateUserPassword, caso contraseña de administrador incorrecta', function(){

    //creación de usuarios para el test
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

    $_POST = [
        "usuarioId" => $getting_users[1]["Id"],
        "contrasena" => "%Rodriguez%",
        "confContrasena" => "%Rodriguez%",
        "adminContrasena" => "12345678"
    ];

    $value = mockUpdateUserPassword($this->container);

    expect(isset($_SESSION["errors"]['adminPWDRejected']))->toBeTrue();
    expect($value['result'])->toBe("Location: ".$this->base_url."home/?homeController=user&homeAction=userNewPassword");
});

test('prueba método updateUserPassword, caso campos invalidos', function(){

    //creación de usuarios para el test
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "usuarioId" => "abcdefghi",
        "contrasena" => "<script></script>",
        "confContrasena" => "%rodriguez%",
        "adminContrasena" => "elRojoQueNoEsRojo"
    ];

    $values = mockUpdateUserPassword($this->container);

    expect($values['errorArr'])->toHaveLength(3);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=user&homeAction=userNewPassword");
});

test('prueba método disableUser, caso satisfactorio', function(){

    //creación de usuarios para el test
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

    $_POST = [
        "usuarioId" => $getting_users[1]["Id"],
        "visibilidad" => "DISABLED",
        "adminContrasena" => "elRojoQueNoEsRojo"
    ];

    $values = mockDisableUser($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Id" => $getting_users[1]["Id"],
        "Visibilidad" => "DISABLED"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), 'Usuarios');
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=user&homeAction=userNewPassword");
});

test('prueba método disableUser, caso contraseña de administrador incorrecta', function(){

    //creación de usuarios para el test
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

    $_POST = [
        "usuarioId" => $getting_users[1]["Id"],
        "visibilidad" => "DISABLED",
        "adminContrasena" => "123456789"
    ];

    $values = mockDisableUser($this->container);

    expect(isset($_SESSION["errors"]['adminPWDRejected']))->toBeTrue();
    expect([
        "Id" => $getting_users[1]["Id"],
        "Visibilidad" => "DISABLED"
    ])->toBeUnknownInDatabase($this->container->make('SOSTestDatabase'), 'Usuarios');
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=user&homeAction=userNewPassword");
});

test('prueba método disableUser, caso campos invalidos', function(){

    //creación de usuarios para el test
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "usuarioId" => "qwertyuiop",
        "visibilidad" => "algo",
        "adminContrasena" => "elRojoQueNoEsRojo"
    ];

    $values = mockDisableUser($this->container);

    expect($values['errorArr'])->toHaveLength(2);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=user&homeAction=userNewPassword");
});