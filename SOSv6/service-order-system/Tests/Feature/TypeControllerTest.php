<?php

/* 
class TypeController{

    private $typeDTO, $usrDTO, $typeService, $usrParticularSrv; 
    public function __construct($typeDTO, $usrDTO, $typeService, $usrParticularSrv){
        $this->typeDTO = $typeDTO;
        $this->usrDTO = $usrDTO;
        $this->typeService = $typeService;
        $this->usrParticularSrv = $usrParticularSrv;
    }

    este método controla la generación de la vista del formulario de creación de tipo de equipo, si
    un usuario no ha accedido a la aplicación, el método invocará un header
    tipo location que enviará al usuario a la raíz del index "home", en este caso, la raíz index será el login. 
    Se define $_SESSION['LAST_ACTIVITY'] con el tiempo actual en 
    cada vista ya que en esta aplicación esta pensado para que la sesión expire después de 30 minutos.

    public function index(){
        if(!empty($_SESSION["identity"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            
            require_once '../views/userLayouts/newTypeForm.php';
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    este método controla la generación de la vista de los formularios de actualización de tipos de equipo, si
    un usuario no ha accedido a la aplicación y/o no es adiministrador, el método invocará un header
    tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login, si lo está y no es administrador, la raíz index 
    será el menú para "user". $types_arr contiene todas las filas de la tabla Tipos gracias 
    al adaptador primario CommonService para Tipos (typeService);
    $types_arr es necesario para generar n cantidad de formularios en la vista dependiendo de la n cantidad 
    de indices que tenga este arreglo.
    si el try fue exitoso o no, el método importará la vista de los formularios de actualización de tipos de 
    equipo donde podrá ver los formularios de los tipos o los flags de errores y excepciones.
    Este método puede interceptar cualquier excepción de la clase PDO.

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

    Este método es invocado dentro del action del formulario de la vista de creación de tipo de equipo, por lo que la variable 
    superglobal post debe estar definida con un número de indices mayor a cero, en caso contrario, el método invocará un header
    tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login. los indices de post son usados para definir los campos del 
    dto (typeDTO) relacionados a estos indices, después, el dto es evaluado por un método estatico de una de nuestras clases de 
    varificación de datos, si el dto pasa todas las verificaciones, la variable $errorArr será un array vacío.
    En dado caso de que $errorArr sea un array vacío, se usará el adaptador primario CommonService de 
    Tipos (typeService) para crear el registro en la base de datos, en caso contrario, se definirá la sesión errors con lo que
    contiene $errorArr, si la inserción fue satisfactoria, se inicializará la sesión "success"; si el try fue exitoso o no, el 
    método invocará el header location que enviará al usuario al formulario de creación de tipo de equipo donde podrá ver el flag de 
    success o los flags de errores y excepciones.
    Puede interceptar 3 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, TiposEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.

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

    Este método es invocado dentro del action de uno de los formularios de la vista de actualización de tipos de equipo, por 
    lo que la variable superglobal post debe estar definida con un número de indices mayor a cero, en caso contrario, el método 
    invocará un header tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login, si lo está puede enviar al logeado a
    la vista del menu de "user" o el mensaje de bienvenida de "Admin". los indices de post son usados para definir los campos del 
    dto (typeDTO y usrDTO) relacionados a estos indices, después, los dtos son evaluados por un método estatico de una de nuestras clases de 
    varificación de datos, si los dos pasan todas las verificaciones, la variable $errorArr será un array vacío.
    En este caso se esta inicializando la propiedad admin_pwd de usrDTO el cual será usado en el helper setAdminVerification 
    junto con el puerto primario UserService llamado en la inyección de dependencias como "usrParticularSrv", este helper puede
    devolver un array vacío o un array con el indice "adminPWDRejected", si $isRejection tiene ese array, entonces se definirá a
    $errorArr con ese array, en dado caso de que $errorArr sea un array vacío, se usará el adaptador primario CommonService de 
    Tipos (typeService) para actualizar un registro en especifico en la base de datos, en caso contrario, se definirá la sesión 
    errors con lo que contiene $errorArr, si la actualización fue satisfactoria, se inicializará la sesión "success"; si el try fue 
    exitoso o no, el método invocará el header location que enviará a los formularios de la vista de actualización de tipos de equipo 
    donde podrá ver el flag de success o los flags de errores y excepciones.
    Puede interceptar 4 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; UnknownInDataBaseException es si el método adminPwdConfirmation arrojó esta 
    excepción en el caso de que la petición a la base de datos para obtener el registro del usuario por su alias 
    devuelva un array vacío; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, UsuariosEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.

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

    Este método es invocado dentro del action del formulario de la ventana emergente para habilitar/inhabilitar un tipo en especifico 
    de la vista de actualización de tipos de equipo, por lo que la variable superglobal post debe estar definida con un número de indices 
    mayor a cero, en caso contrario, el método invocará un header tipo location que enviará al usuario a la raíz del index "home", si el 
    usuario no está logeado, la raíz index será el login, si lo está puede enviar al logeado a
    la vista del menu de "user" o el mensaje de bienvenida de "Admin". los indices de post son usados para definir los campos del 
    dto (typeDTO) relacionados a estos indices, después, el dto es evaluado por un método estatico de una de nuestras clases de 
    varificación de datos, si el dto pasa todas las verificaciones, la variable $errorArr será un array vacío.
    En dado caso de que $errorArr sea un array vacío, se usará el adaptador primario CommonService de 
    Tipos (typeService) para actualizar la visibilidad de un registro en especifico en la base de datos, en caso contrario, se 
    definirá la sesión errors con lo que contiene $errorArr, si la actualización fue satisfactoria, se inicializará la sesión 
    "success"; si el try fue exitoso o no, el método invocará el header location que enviará al usuario a los formularios de actualización 
    de tipos de equipo donde podrá ver el flag de success o los flags de errores y excepciones.
    Puede interceptar 4 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; AutomaticValueException es si en el método updateVisibility de CommonService 
    arroja esta excepción en dado caso de que el valor de la propiedad visibilidad sea diferente a "ENABLED" o "DISABLED"; EntityException 
    es si se envió datos que no pasa las evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, 
    UsuariosEntity; finalmente, Exception es para interceptar excepciones de la clase PDO.

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
*/

beforeEach(function(){
    $this->container = testContainerFactory();
    $this->base_url = 'http://localhost:8081/SOSv6/service-order-system/';
});

afterEach(function(){
    $_SESSION = [];
    $_POST = [];
    $_GET = [];
    cleanTable($this->container->make('SOSTestDatabase'), "Tipos");
    cleanTable($this->container->make('SOSTestDatabase'), "Usuarios");
});

test('prueba método index', function(){

    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'user'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $value = mockTypeIndex();

    expect(intval($_SESSION['LAST_ACTIVITY']))->toBeInt();
    expect($value)->toBe('../views/userLayouts/newTypeForm.php');
});

test('prueba método editTypes', function(){

    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
        
        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $values = mockEditTypes($this->container);

    expect(intval($_SESSION["LAST_ACTIVITY"]))->toBeInt();
    expect($values['types_arr'])->toHaveLength(3);
    expect($values['result'])->toBe('../views/adminLayouts/typesEditForms.php');
});

test('prueba método insertType, caso satisfactorio', function(){

    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'user'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST["tipo"] = "impresora";

    $value = mockInsertType($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect(["Tipo" => "Impresora"])->toBeInDatabase($this->container->make('SOSTestDatabase'), "Tipos");
    expect($value['result'])->toBe("Location: ".$this->base_url."home/?homeController=type&homeAction=index");
});

test('prueba método insertType, caso campo invalido', function(){

    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'user'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST["tipo"] = "09impresora%#";

    $values = mockInsertType($this->container);

    expect(isset($values['errorArr']["tipo"]))->toBeTrue();
    expect(["Tipo" => "09impresora%#"])->toBeUnknownInDatabase($this->container->make('SOSTestDatabase'), "Tipos");
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=type&homeAction=index");
});

test('prueba método updateTypeInfo, caso satisfactorio', function(){

    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
        $getting_types = $this->container->make('typService')->getAllInfo();
    }catch(Exception $ex){
        echo $ex->getMessage();
    }


    $_POST = [
        "tipoId" => $getting_types[1]["Id"],
        "tipo" => "switch",
        "adminContrasena" => "elRojoQueNoEsRojo"
    ];

    $value = mockUpdateTypeInfo($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Id" => $getting_types[1]["Id"],
        "Tipo" => "Switch"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), "Tipos");
    expect($value['result'])->toBe("Location: ".$this->base_url."home/?homeController=type&homeAction=editTypes");
});

test('prueba método updateTypeInfo, caso contraseña de administrador incorrecta', function(){
    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
        $getting_types = $this->container->make('typService')->getAllInfo();
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "tipoId" => $getting_types[1]["Id"],
        "tipo" => "switch",
        "adminContrasena" => "123456789"
    ];

    $value = mockUpdateTypeInfo($this->container);

    expect(isset($_SESSION["errors"]["adminPWDRejected"]))->toBeTrue();
    expect([
        "Id" => $getting_types[1]["Id"],
        "Tipo" => "Switch"
    ])->toBeUnknownInDatabase($this->container->make('SOSTestDatabase'), "Tipos");
    expect($value['result'])->toBe("Location: ".$this->base_url."home/?homeController=type&homeAction=editTypes");
});

test('prueba método updateTypeInfo, caso campos invalidos', function(){

    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "tipoId" => "qwertyuiop",
        "tipo" => "switch :)",
        "adminContrasena" => "elRojoQueNoEsRojo"
    ];

    $values = mockUpdateTypeInfo($this->container);

    expect($values['errorArr'])->toHaveLength(2);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=type&homeAction=editTypes");
});

test('prueba método enableOrDisableType, caso satisfactorio', function(){

    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
        $getting_types = $this->container->make('typService')->getAllInfo();
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "tipoId" => $getting_types[1]["Id"],
        "visibilidad" => "DISABLED"
    ];

    $value = mockEnableOrDisableType($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Id" => $getting_types[1]["Id"],
        "Visibilidad" => "DISABLED"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), "Tipos");
    expect($value['result'])->toBe("Location: ".$this->base_url."home/?homeController=type&homeAction=editTypes");
});

test('prueba método enableOrDisableType, caso campos invalidos', function(){

    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "tipoId" => 'qwertyuiop',
        "visibilidad" => "algo"
    ];

    $values = mockEnableOrDisableType($this->container);

    expect($values['errorArr'])->toHaveLength(2);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=type&homeAction=editTypes");
});