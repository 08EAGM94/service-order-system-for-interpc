<?php

/* 
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

    este método controla la generación de la vista de los formularios de edición de empresa y contactos, si
    un usuario no ha accedido a la aplicación y/o no es administrador, el método invocará un header
    tipo location que enviará al usuario a la raíz del index "home". Se define $_SESSION['LAST_ACTIVITY'] con 
    el tiempo actual en cada vista ya que en esta aplicación esta pensado para que la sesión expire después 
    de 30 minutos.
    se utiliza nuestro adaptador primario CommonService para empresas (enterService) para obtener todos los 
    registros de empresas de la base de datos para el elemento select de esta vista, El método usa el helper "setIdSession" 
    para generar una sesión con indice "idSession", esto para conservar la elección del usuario al momento de 
    ejecutar el formulario de búsqueda, en este caso, de algúna empresa; si $_SESSION["idSession"]["enterpriseEdit_enterId"]
    está definido entonces se utiliza su valor para asignarselo a las propiedades enterprise_id y empresa_id del dto de
    empresas y contactos respectivamente, después se usan estos dtos para obtener la información de la empresa y todos
    los registros de contactos relacionados a esta por medio de CommonService de empresas (enterService) y
    EnterpriseChildrenService (contService) respectivamente. tanto $ent_arr como $contacts_arr lo usa esta vista para dar
    forma a los formularios de empresa y sus contactos.
    Puede interceptar 3 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, TiposEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.

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

    Este método es invocado dentro del action del formulario de una empresa de la vista de edición de empresa y sus contactos, por 
    lo que la variable superglobal post debe estar definida con un número de indices mayor a cero, en caso contrario, el método 
    invocará un header tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login, si lo está puede enviar al logeado a
    la vista del menu de "user" o el mensaje de bienvenida de "Admin". los indices de post son usados para definir los campos del 
    dto (enterDTO y usrDTO) relacionados a estos indices, después, los dtos son evaluados por un método estatico de una de nuestras clases de 
    varificación de datos, si los dos pasan todas las verificaciones, la variable $errorArr será un array vacío.
    En este caso se esta inicializando la propiedad admin_pwd de usrDTO el cual será usado en el helper setAdminVerification 
    junto con el puerto primario UserService llamado en la inyección de dependencias como "usrParticularSrv", este helper puede
    devolver un array vacío o un array con el indice "adminPWDRejected", si $isRejection tiene ese array, entonces se definirá a
    $errorArr con ese array, en dado caso de que $errorArr sea un array vacío se usará el adaptador primario CommonService de 
    empresas (enterService) para actualizar un registro en especifico en la base de datos, en caso contrario, se definirá la sesión 
    errors con lo que contiene $errorArr, si la actualización fue satisfactoria, se inicializará la sesión "success"; si el try fue 
    exitoso o no, el método invocará el header location que enviará a los formularios de edición de empresa y sus contactos
    donde podrá ver el flag de success o de errores y excepciones.
    Puede interceptar 4 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; UnknownInDataBaseException es si el método adminPwdConfirmation arrojó esta 
    excepción en el caso de que la petición a la base de datos para obtener el registro del usuario por su alias 
    devuelva un array vacío; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, UsuariosEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.

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

    Este método es invocado dentro del action del formulario de la ventana emergente para habilitar/inhabilitar una empresa en especifico 
    de la vista de edición de empresa y sus contactos, por lo que la variable superglobal post debe estar definida con un número de indices 
    mayor a cero, en caso contrario, el método invocará un header tipo location que enviará al usuario a la raíz del index "home", si el 
    usuario no está logeado, la raíz index será el login, si lo está puede enviar al logeado a
    la vista del menu de "user" o el mensaje de bienvenida de "Admin". los indices de post son usados para definir los campos del 
    dto (enterDTO) relacionados a estos indices, después, el dto es evaluado por un método estatico de una de nuestras clases de 
    varificación de datos, si el dto pasa todas las verificaciones, la variable $errorArr será un array vacío.
    En dado caso de que $errorArr sea un array vacío, se usará el adaptador primario CommonService de 
    empresas (enterService) para actualizar la visibilidad de un registro en especifico en la base de datos, en caso contrario, se 
    definirá la sesión errors con lo que contiene $errorArr, si la actualización fue satisfactoria, se inicializará la sesión 
    "success"; si el try fue exitoso o no, el método invocará el header location que enviará al usuario a la vista de edición de 
    empresa y sus contactos donde podrá ver el flag de success o los flags de errores y excepciones.
    Puede interceptar 4 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; AutomaticValueException es si en el método updateVisibility de CommonService 
    arroja esta excepción en dado caso de que el valor de la propiedad visibilidad sea diferente a "ENABLED" o "DISABLED"; EntityException 
    es si se envió datos que no pasa las evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, 
    UsuariosEntity; finalmente, Exception es para interceptar excepciones de la clase PDO.

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
*/

beforeEach(function(){
    $this->container = testContainerFactory();
    $this->base_url = 'http://localhost:8081/SOSv6/service-order-system/';
});

afterEach(function(){
    $_SESSION = [];
    $_POST = [];
    $_GET = [];
    cleanTable($this->container->make('SOSTestDatabase'), "Contactos");
    cleanTable($this->container->make('SOSTestDatabase'), "Empresas");
    cleanTable($this->container->make('SOSTestDatabase'), "Usuarios");
});

test('prueba método index', function(){
    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de Empresas para el test
        $enterDTOsArr = mockEntersDTO($this->container);
        for($i = 0; $i < sizeof($enterDTOsArr); $i++){
            $this->container->make('enterService')->insertInfo($enterDTOsArr[$i][0], $enterDTOsArr[$i][1]);
        }
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $values = mockEnterpriseIndex($this->container);

    expect(intval($_SESSION["LAST_ACTIVITY"]))->toBeInt();
    expect($values['enterprises'])->toHaveLength(3);
    expect($values['result'])->toBe('../views/adminLayouts/enterAndContactsEditForms.php');
});

test('prueba método index, caso selección de empresa', function(){
    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de Empresas para el test
        $enterDTOsArr = mockEntersDTO($this->container);
        for($i = 0; $i < sizeof($enterDTOsArr); $i++){
            $this->container->make('enterService')->insertInfo($enterDTOsArr[$i][0], $enterDTOsArr[$i][1]);
        }

        $getting_enters = $this->container->make('enterService')->getAllInfo();
        $ids = [];
        foreach($getting_enters as $enter){
            $ids[] = $enter["Id"];
        }

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $ids);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }

        //simulando selección de empresa
        $_GET = [ 
            "homeController" => "enterprise",
            "homeAction" => "index"
        ];

        $_POST["empresas"] = $getting_enters[1]["Id"];
        mockSetIdSession();

    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $values = mockEnterpriseIndex($this->container);

    expect(intval($_SESSION["LAST_ACTIVITY"]))->toBeInt();
    expect($values['enterprises'])->toHaveLength(3);
    expect($values['ent_arr'])->toHaveLength(13);
    expect($values['contacts_arr'])->toHaveLength(3);
    expect($values['result'])->toBe($_SESSION["header"]);
});

test('prueba método updateEnterInfo, caso satisfactorio', function(){
    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de Empresas para el test
        $enterDTOsArr = mockEntersDTO($this->container);
        for($i = 0; $i < sizeof($enterDTOsArr); $i++){
            $this->container->make('enterService')->insertInfo($enterDTOsArr[$i][0], $enterDTOsArr[$i][1]);
        }

        $getting_enters = $this->container->make('enterService')->getAllInfo();

    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "adminContrasena" => "elRojoQueNoEsRojo",
        "empresaId" => $getting_enters[1]["Id"],
        "nombreComercial" => "empresa editada",
        "razonSocial" => "campo editado",
        "calleYNumero" => "campo editado",
        "entreCalles" => "campo editado",
        "dirigirseCon" => "campo editado",
        "telefonos" => "123456789",
        "horario" => "campo editado",
        "atencion" => "campo editado",
        "colonia" => "campo editado",
        "localidad" => "campo editado",
        "email" => "test@gmail.com",
    ];

    $values = mockUpdateEnterInfo($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Id" => $getting_enters[1]["Id"],
        "Nombre_comercial" => "empresa editada",
        "Razon_social" => "campo editado",
        "Calle_numero" => "campo editado",
        "Entre_calles" => "campo editado",
        "Dirigirse_con" => "campo editado",
        "Telefonos" => "123456789",
        "Horario" => "campo editado",
        "Atencion" => "campo editado",
        "Colonia" => "campo editado",
        "Localidad" => "campo editado",
        "Email" => "test@gmail.com",
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), "Empresas");
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=enterprise&homeAction=index");
});

test('prueba método updateEnterInfo, caso contraseña de administrador incorrecta', function(){
    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de Empresas para el test
        $enterDTOsArr = mockEntersDTO($this->container);
        for($i = 0; $i < sizeof($enterDTOsArr); $i++){
            $this->container->make('enterService')->insertInfo($enterDTOsArr[$i][0], $enterDTOsArr[$i][1]);
        }

        $getting_enters = $this->container->make('enterService')->getAllInfo();

    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "adminContrasena" => "qwertyuiop",
        "empresaId" => $getting_enters[1]["Id"],
        "nombreComercial" => "empresa editada",
        "razonSocial" => "campo editado",
        "calleYNumero" => "campo editado",
        "entreCalles" => "campo editado",
        "dirigirseCon" => "campo editado",
        "telefonos" => "123456789",
        "horario" => "campo editado",
        "atencion" => "campo editado",
        "colonia" => "campo editado",
        "localidad" => "campo editado",
        "email" => "test@gmail.com",
    ];

    $values = mockUpdateEnterInfo($this->container);

    expect(isset($_SESSION["errors"]["adminPWDRejected"]))->toBeTrue();
    expect([
        "Id" => $getting_enters[1]["Id"],
        "Nombre_comercial" => "empresa editada",
        "Razon_social" => "campo editado",
        "Calle_numero" => "campo editado",
        "Entre_calles" => "campo editado",
        "Dirigirse_con" => "campo editado",
        "Telefonos" => "123456789",
        "Horario" => "campo editado",
        "Atencion" => "campo editado",
        "Colonia" => "campo editado",
        "Localidad" => "campo editado",
        "Email" => "test@gmail.com",
    ])->toBeUnknownInDatabase($this->container->make('SOSTestDatabase'), "Empresas");
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=enterprise&homeAction=index");
});

test('prueba método updateEnterInfo, caso campos invalidos', function(){
    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "adminContrasena" => "elRojoQueNoEsRojo",
        "empresaId" => "qwertyuiop",
        "nombreComercial" => "<script></script>",
        "razonSocial" => "<script></script>",
        "calleYNumero" => "<script></script>",
        "entreCalles" => "<script></script>",
        "dirigirseCon" => "<script></script>",
        "telefonos" => "<script></script>",
        "horario" => "<script></script>",
        "atencion" => "<script></script>",
        "colonia" => "<script></script>",
        "localidad" => "<script></script>",
        "email" => "<script></script>",
    ];

    $values = mockUpdateEnterInfo($this->container);

    expect($values['errorArr'])->toHaveLength(12);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=enterprise&homeAction=index");
});

test('prueba método enableOrDisableEnterprise, caso satisfactorio', function(){
    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de Empresas para el test
        $enterDTOsArr = mockEntersDTO($this->container);
        for($i = 0; $i < sizeof($enterDTOsArr); $i++){
            $this->container->make('enterService')->insertInfo($enterDTOsArr[$i][0], $enterDTOsArr[$i][1]);
        }

        $getting_enters = $this->container->make('enterService')->getAllInfo();

    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "empresaId" => $getting_enters[1]["Id"],
        "visibilidad" => "DISABLED"
    ];

    $values = mockEnableOrDisableEnterprise($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Id" => $getting_enters[1]["Id"],
        "Visibilidad" => "DISABLED"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), "Empresas");
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=enterprise&homeAction=index");
});

test('prueba método enableOrDisableEnterprise, caso campos invalidos', function(){
    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "empresaId" => "qwertyuiop",
        "visibilidad" => "algo"
    ];

    $values = mockEnableOrDisableEnterprise($this->container);

    expect($values['errorArr'])->toHaveLength(2);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=enterprise&homeAction=index");
});