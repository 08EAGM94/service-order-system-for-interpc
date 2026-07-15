<?php

/*

*NOTA: SelectService se usa para obtener registros de alguna tabla en la base de datos para los select de vistas donde el usuario 
tenga privilegio "user" ya que una de las condiciones para obtener estos registros es que el campo "Visibilidad"
sea igual a "ENABLED", en cambio, al usar CommonService para obtener todos los registros de alguna tabla en la
base de datos para dar forma a los select de algunas vistas es por que el privilegio del usuario es "Admin" ya 
no hay distinción en la visibilidad, esto es particularmente util para permitir al administrador gestionar la visibilidad de 
estos registros.

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

    este método controla la generación de la vista del formulario de registro de equipo, si
    un usuario no ha accedido a la aplicación, el método invocará un header
    tipo location que enviará al usuario a la raíz del index "home", en este caso, la raíz index será el login. 
    Se define $_SESSION['LAST_ACTIVITY'] con el tiempo actual en 
    cada vista ya que en esta aplicación esta pensado para que la sesión expire después de 30 minutos.
    se utiliza nuestro adaptador primario SelectService para empresas (enterSelectSrv) y tipos (typSelectSrv) con el fin de 
    obtener todos los registros de empresas y tipos de la base de datos para sus respectivos elementos select de 
    esta vista, en caso de que entre en la excepción se inicializarán las variables $enters y $types como arrays
    vacíos ya que la vista evalúa el tamaño de estos arrays.
    Este método puede interceptar cualquier excepción de la clase PDO.

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

    este método controla la generación de la vista de los formularios de edición de equipos de una empresa, si
    un usuario no ha accedido a la aplicación y/o no es administrador, el método invocará un header
    tipo location que enviará al usuario a la raíz del index "home".
    se utiliza nuestro adaptador primario SelectService para empresas (enterSelectSrv) para obtener todos los 
    registros de empresas de la base de datos para el elemento select de esta vista, El método usa el helper "setIdSession" 
    para generar una sesión con indice "idSession", esto para conservar la elección del usuario al momento de 
    ejecutar el formulario de búsqueda, en este caso, de algúna empresa; si $_SESSION["idSession"]["devicesEdit_enterId"]
    está definido entonces se utiliza su valor para asignarselo a la propiedad empresa_id del dto de equipos,
    después se usan este dto para obtener todos los registros de equipos relacionados a una empresa en especifico por medio 
    de EnterpriseChildrenService (dceService) y finalmente por medio de SelectService para tipos (typSelectSrv) se obtiene 
    todos los registros de tipos en el base de datos para ser usados por los selects de los formularios de edición de equipos. 
    tanto $devices_arr como $types_arr lo usa esta vista para dar forma a los formularios de edición de equipos de una empresa.
    Puede interceptar 3 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, TiposEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.

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

    este método controla la generación de la vista de los formularios de edición de equipos de una empresa, si
    un usuario no ha accedido a la aplicación y/o no es administrador, el método invocará un header
    tipo location que enviará al usuario a la raíz del index "home".
    se utiliza nuestro adaptador primario CommonService para empresas (enterService) para obtener todos los 
    registros de empresas de la base de datos para el elemento select de esta vista, El método usa el helper "setIdSession" 
    para generar una sesión con indice "idSession", esto para conservar la elección del usuario al momento de 
    ejecutar el formulario de búsqueda, en este caso, de algúna empresa; si $_SESSION["idSession"]["devicesReport_enterId"]
    está definido entonces se utiliza su valor para asignarselo a las propiedades enterprise_id y empresa_id del dto de
    empresas y dispositivos respectivamente, después se usan estos dtos para obtener la información de la empresa y todos
    los registros de equipos relacionados a esta por medio de CommonService de empresas (enterService) y
    EnterpriseChildrenService (dceService) respectivamente. tanto $enter_info como $enter_devices lo usa esta vista para dar
    forma al canvas informativo de la empresa y sus equipos. Adicionalmente la sesión $_SESSION["idSession"]["devicesReport_enterId"]
    lo usa el helper reportPdfGenerator para dar forma al pdf de reporte de dispositivos.
    Puede interceptar 3 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, TiposEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.

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

    Este método es invocado dentro del action del formulario de la vista de registro de equipo, por 
    lo que la variable superglobal post debe estar definida con un número de indices mayor a cero, en caso contrario, el método 
    invocará un header tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login. los indices de post son usados para definir los campos del 
    dto (dceDTO) relacionados a estos indices, después, el dto es evaluado por un método estatico de una de nuestras clases de 
    varificación de datos, si el dto pasa todas las verificaciones, la variable $errorArr será un array vacío.
    En dado caso de que $errorArr sea un array vacío, se usará el adaptador primario EnterpriseChildrenService de 
    equipos (dceService) para crear el registro en la base de datos, en caso contrario, se definirá la sesión errors con lo que
    contiene $errorArr, si la inserción fue satisfactoria, se inicializará la sesión "success"; si el try fue exitoso o no, el 
    método invocará el header location que enviará al usuario al formulario de registro de equipo donde podrá ver el flag de 
    success o los flags de errores y excepciones.
    Puede interceptar 3 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, TiposEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.

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

    Este método es invocado dentro del action de uno de los formularios de la vista de edición de equipos de una empresa, por 
    lo que la variable superglobal post debe estar definida con un número de indices mayor a cero, en caso contrario, el método 
    invocará un header tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login. los indices de post son usados para definir los campos del 
    dto (dceDTO y usrDTO) relacionados a estos indices, después, los dtos son evaluados por un método estatico de una de nuestras clases de 
    varificación de datos, si los dos pasan todas las verificaciones, la variable $errorArr será un array vacío.
    En este caso se esta inicializando la propiedad admin_pwd de usrDTO el cual será usado en el helper setAdminVerification 
    junto con el puerto primario UserService llamado en la inyección de dependencias como "usrParticularSrv", este helper puede
    devolver un array vacío o un array con el indice "adminPWDRejected", si $isRejection tiene ese array, entonces se definirá a
    $errorArr con ese array, en dado caso de que $errorArr sea un array vacío se usará el adaptador primario EnterpriseChildrenService de 
    equipos (dceService) para actualizar un registro en especifico en la base de datos, en caso contrario, se definirá la sesión 
    errors con lo que contiene $errorArr, si la actualización fue satisfactoria, se inicializará la sesión "success"; si el try fue 
    exitoso o no, el método invocará el header location que enviará a los formularios de edición de equipos de una empresa
    donde podrá ver el flag de success o de errores y excepciones.
    Puede interceptar 4 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; UnknownInDataBaseException es si el método adminPwdConfirmation arrojó esta 
    excepción en el caso de que la petición a la base de datos para obtener el registro del usuario por su alias 
    devuelva un array vacío; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, UsuariosEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.

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

    Este método es invocado dentro del action del formulario de la ventana emergente para habilitar/inhabilitar un equipo en especifico 
    de la vista de edición de equipos de una empresa, por lo que la variable superglobal post debe estar definida con un número de indices 
    mayor a cero, en caso contrario, el método invocará un header tipo location que enviará al usuario a la raíz del index "home", si el 
    usuario no está logeado, la raíz index será el login, si lo está puede enviar al logeado a
    la vista del menu de "user" o el mensaje de bienvenida de "Admin". los indices de post son usados para definir los campos del 
    dto (dceDTO) relacionados a estos indices, después, el dto es evaluado por un método estatico de una de nuestras clases de 
    varificación de datos, si el dto pasa todas las verificaciones, la variable $errorArr será un array vacío.
    En dado caso de que $errorArr sea un array vacío, se usará el adaptador primario EnterpriseChildrenService de 
    equipos (dceService) para actualizar la visibilidad de un registro en especifico en la base de datos, en caso contrario, se 
    definirá la sesión errors con lo que contiene $errorArr, si la actualización fue satisfactoria, se inicializará la sesión 
    "success"; si el try fue exitoso o no, el método invocará el header location que enviará al usuario a la vista de edición de equipos 
    de una empresa donde podrá ver el flag de success o los flags de errores y excepciones.
    Puede interceptar 4 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; AutomaticValueException es si en el método updateVisibility de CommonService 
    arroja esta excepción en dado caso de que el valor de la propiedad visibilidad sea diferente a "ENABLED" o "DISABLED"; EntityException 
    es si se envió datos que no pasa las evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, 
    UsuariosEntity; finalmente, Exception es para interceptar excepciones de la clase PDO.

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
*/

beforeEach(function(){
    $this->container = testContainerFactory();
    $this->base_url = 'http://localhost:8081/SOSv6/service-order-system/';
});

afterEach(function(){
    $_SESSION = [];
    $_POST = [];
    $_GET = [];
    cleanTable($this->container->make('SOSTestDatabase'), "Equipos");
    cleanTable($this->container->make('SOSTestDatabase'), "Contactos");
    cleanTable($this->container->make('SOSTestDatabase'), "Tipos");
    cleanTable($this->container->make('SOSTestDatabase'), "Empresas");
    cleanTable($this->container->make('SOSTestDatabase'), "Usuarios");
});

test('prueba método index', function(){
    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'user'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de Empresas para el test
        $enterDTOsArr = mockEntersDTO($this->container);
        for($i = 0; $i < sizeof($enterDTOsArr); $i++){
            $this->container->make('enterService')->insertInfo($enterDTOsArr[$i][0], $enterDTOsArr[$i][1]);
        }

        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $values = mockDeviceIndex($this->container);

    expect(intval($_SESSION["LAST_ACTIVITY"]))->toBeInt();
    expect($values['enters'])->toHaveLength(3);
    expect($values['types'])->toHaveLength(3);
    expect($values['result'])->toBe('../views/userLayouts/newDeviceForm.php');
});

test('prueba método editDevice', function(){
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

    $values = mockEditDevice($this->container);

    expect(intval($_SESSION["LAST_ACTIVITY"]))->toBeInt();
    expect($values['enterprises'])->toHaveLength(3);
    expect($values['result'])->toBe('../views/adminLayouts/devicesEditForms.php');
});

test('prueba método editDevice, caso selección de empresa', function(){
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
        $enterIds = [];
        foreach($getting_enters as $enter){
            $enterIds[] = $enter["Id"];
        }

        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
        $getting_typs = $this->container->make('typService')->getAllInfo();
        $typIds = [];
        foreach($getting_typs as $typ){
            $typIds[] = $typ["Id"];
        }

        //creación de equipos para el test
        $dceDTOsArr = mockDevicesDTO($this->container, $enterIds, $typIds);
        foreach($dceDTOsArr as $dto){
            $this->container->make('dceService')->insertChild($dto);
        }

        //simular selección de empresa
        $_GET["homeAction"] = "editDevice";
        $_POST["empresas"] = $getting_enters[1]["Id"];
        mockSetIdSession();
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $values = mockEditDevice($this->container);

    expect(intval($_SESSION["LAST_ACTIVITY"]))->toBeInt();
    expect($values['enterprises'])->toHaveLength(3);
    expect($values['devices_arr'])->toHaveLength(2);
    expect($values['types_arr'])->toHaveLength(3);
    expect($values['result'])->toBe($_SESSION["header"]);
});

test('prueba método devicesReport', function(){
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

    $values = mockDevicesReport($this->container);

    expect(intval($_SESSION["LAST_ACTIVITY"]))->toBeInt();
    expect($values['enters'])->toHaveLength(3);
    expect($values['result'])->toBe('../views/adminLayouts/devicesReport.php');
});

test('prueba método devicesReport, caso selección de empresa', function(){
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
        $enterIds = [];
        foreach($getting_enters as $enter){
            $enterIds[] = $enter["Id"];
        }

        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
        $getting_typs = $this->container->make('typService')->getAllInfo();
        $typIds = [];
        foreach($getting_typs as $typ){
            $typIds[] = $typ["Id"];
        }

        //creación de equipos para el test
        $dceDTOsArr = mockDevicesDTO($this->container, $enterIds, $typIds);
        foreach($dceDTOsArr as $dto){
            $this->container->make('dceService')->insertChild($dto);
        }

        //simular selección de empresa
        $_GET["homeAction"] = "devicesReport";
        $_POST["empresas"] = $getting_enters[1]["Id"];
        mockSetIdSession();
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $values = mockDevicesReport($this->container);

    expect(intval($_SESSION["LAST_ACTIVITY"]))->toBeInt();
    expect($values['enters'])->toHaveLength(3);
    expect($values['enter_info'])->toHaveLength(13);
    expect($values['enter_devices'])->toHaveLength(2);
    expect($values['result'])->toBe($_SESSION["header"]);
});

test('prueba método insertDevice, caso satisfactorio', function(){
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

        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
        $getting_typs = $this->container->make('typService')->getAllInfo();
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "empresas" => $getting_enters[2]["Id"],
        "tipos" => $getting_typs[2]["Id"],
        "marca" => "Apple",
        "modelo" => "iPhone 15 Pro Max",
        "ns" => "AP-MOB-2026-006",
        "numeroInventario" => "20",
    ];

    $values = mockInsertDevice($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Empresa_id" => $getting_enters[2]["Id"],
        "Tipo_id" => $getting_typs[2]["Id"],
        "Marca" => "APPLE",
        "Modelo" => "IPHONE 15 PRO MAX",
        "Numero_serie" => "AP-MOB-2026-006",
        "Numero_inventario" => "20"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), "Equipos");
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=device&homeAction=index");
});

test('prueba método insertDevice, caso campos invalidos', function(){
    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "empresas" => 'qwertyuiop',
        "tipos" => 'qwertyuiop',
        "marca" => "<script></script>",
        "modelo" => "<script></script>",
        "ns" => "<script></script>",
        "numeroInventario" => 'qwertyuiop',
    ];

    $values = mockInsertDevice($this->container);

    expect($values['errorArr'])->toHaveLength(6);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=device&homeAction=index");
});

test('prueba método updateDeviceInfo, caso satisfactorio', function(){
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
        $enterIds = [];
        foreach($getting_enters as $enter){
            $enterIds[] = $enter["Id"];
        }

        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
        $getting_typs = $this->container->make('typService')->getAllInfo();
        $typIds = [];
        foreach($getting_typs as $typ){
            $typIds[] = $typ["Id"];
        }

        //creación de equipos para el test
        $dceDTOsArr = mockDevicesDTO($this->container, $enterIds, $typIds);
        foreach($dceDTOsArr as $dto){
            $this->container->make('dceService')->insertChild($dto);
        }

        $getting_dces = $this->container->make('dceService')->getChildrenByEnterprise($dceDTOsArr[5]);
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "adminContrasena" => "elRojoQueNoEsRojo",
        "dispositivoId" => $getting_dces[1]["Id"],
        "tipos" => $getting_typs[2]["Id"],
        "marca" => "campo editado",
        "modelo" => "campo editado",
        "ns" => "campo editado",
        "numeroInventario" => "10",
    ];

    $values = mockUpdateDeviceInfo($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Id" => $getting_dces[1]["Id"],
        "Tipo_id" => $getting_typs[2]["Id"],
        "Marca" => "CAMPO EDITADO",
        "Modelo" => "CAMPO EDITADO",
        "Numero_serie" => "CAMPO EDITADO",
        "Numero_inventario" => "10"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), "Equipos");
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=device&homeAction=editDevice");
});

test('prueba método updateDeviceInfo, caso contraseña de administrador incorrecta', function(){
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
        $enterIds = [];
        foreach($getting_enters as $enter){
            $enterIds[] = $enter["Id"];
        }

        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
        $getting_typs = $this->container->make('typService')->getAllInfo();
        $typIds = [];
        foreach($getting_typs as $typ){
            $typIds[] = $typ["Id"];
        }

        //creación de equipos para el test
        $dceDTOsArr = mockDevicesDTO($this->container, $enterIds, $typIds);
        foreach($dceDTOsArr as $dto){
            $this->container->make('dceService')->insertChild($dto);
        }

        $getting_dces = $this->container->make('dceService')->getChildrenByEnterprise($dceDTOsArr[5]);
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "adminContrasena" => "qwertyuiop",
        "dispositivoId" => $getting_dces[1]["Id"],
        "tipos" => $getting_typs[2]["Id"],
        "marca" => "campo editado",
        "modelo" => "campo editado",
        "ns" => "campo editado",
        "numeroInventario" => "10",
    ];

    $values = mockUpdateDeviceInfo($this->container);

    expect(isset($_SESSION["errors"]["adminPWDRejected"]))->toBeTrue();
    expect([
        "Id" => $getting_dces[1]["Id"],
        "Tipo_id" => $getting_typs[2]["Id"],
        "Marca" => "CAMPO EDITADO",
        "Modelo" => "CAMPO EDITADO",
        "Numero_serie" => "CAMPO EDITADO",
        "Numero_inventario" => "10"
    ])->toBeUnknownInDatabase($this->container->make('SOSTestDatabase'), "Equipos");
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=device&homeAction=editDevice");
});

test('prueba método updateDeviceInfo, caso campos invalidos', function(){
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
        "dispositivoId" => '$getting_dces[1]["Id"]',
        "tipos" => '$getting_typs[2]["Id"]',
        "marca" => "<script></script>",
        "modelo" => "<script></script>",
        "ns" => "<script></script>",
        "numeroInventario" => "elRojoQueNoEsRojo",
    ];

    $values = mockUpdateDeviceInfo($this->container);

    expect($values['errorArr'])->toHaveLength(6);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=device&homeAction=editDevice");
});

test('prueba método enableOrDisableDevice, caso satisfactorio', function(){
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
        $enterIds = [];
        foreach($getting_enters as $enter){
            $enterIds[] = $enter["Id"];
        }

        //creación de tipos para el test
        $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        foreach($typDTOsArr as $dto){
            $this->container->make('typService')->insertInfo($dto);
        }
        $getting_typs = $this->container->make('typService')->getAllInfo();
        $typIds = [];
        foreach($getting_typs as $typ){
            $typIds[] = $typ["Id"];
        }

        //creación de equipos para el test
        $dceDTOsArr = mockDevicesDTO($this->container, $enterIds, $typIds);
        foreach($dceDTOsArr as $dto){
            $this->container->make('dceService')->insertChild($dto);
        }
        $getting_dces = $this->container->make('dceService')->getChildrenByEnterprise($dceDTOsArr[5]);
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "equipoId" => $getting_dces[1]["Id"],
        "visibilidad" => "DISABLED"
    ];

    $values = mockEnableOrDisableDevice($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Id" => $getting_dces[1]["Id"],
        "Visibilidad" => "DISABLED"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), "Equipos");
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=device&homeAction=editDevice");
});

test('prueba método enableOrDisableDevice, caso campos invalidos', function(){
    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "equipoId" => '$getting_dces[1]["Id"]',
        "visibilidad" => "algo"
    ];

    $values = mockEnableOrDisableDevice($this->container);

    expect($values['errorArr'])->toHaveLength(2);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=device&homeAction=editDevice");
});