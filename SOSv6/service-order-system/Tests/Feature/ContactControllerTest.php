<?php

/* 
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

    este método controla la generación de la vista del formulario de nuevo contacto, si
    un usuario no ha accedido a la aplicación, el método invocará un header
    tipo location que enviará al usuario a la raíz del index "home", en este caso, la raíz index será el login. 
    Se define $_SESSION['LAST_ACTIVITY'] con el tiempo actual en 
    cada vista ya que en esta aplicación esta pensado para que la sesión expire después de 30 minutos.
    se utiliza nuestro adaptador primario SelectService para empresas (enterSelectSrv) para obtener todos los 
    registros de empresas de la base de datos unicamente con los campos que necesita el elemento select de la
    vista, si entra en la excepción, $enterprises se definirá como un array vacío, la vista evalúa si este 
    array está vacío o no.
    Este método puede interceptar cualquier excepción de la clase PDO.

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

    Este método es invocado dentro del action del formulario de la vista de nuevo contacto, por 
    lo que la variable superglobal post debe estar definida con un número de indices mayor a cero, en caso contrario, el método 
    invocará un header tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login. los indices de post son usados para definir los campos del 
    dto (contDTO o enterDTO) relacionados a estos indices, después, los dtos son evaluados por un método estatico de una de nuestras 
    clases de varificación de datos, si los dtos pasan todas las verificaciones, la variable $errorArr será un array vacío.
    Este caso particular de inserción a la base de datos involucra dos entidades en la base de datos, en el caso de que en el
    formulario se eliga algúna empresa, el indice hiddenEntId del post estará definido, en este caso el EnterpriseChildrenService para
    contactos (contService) será usado para insertar un registro en la base de datos; por otra parte, si en el formulario no se selecciona
    una empresa, quiere decir que se quiere registrar una empresa junto al contacto, en ese caso el indice hiddenEntId del post
    no estará definido y se usará el CommonService para empresas donde se usara el dto de la empresa y el dto del contacto, en este caso,
    dentro del segundo parametro opcional del método insertInfo en el cual, dentro de EnterpriseQueries, se hará una transacción intentando 
    insertar información en estas dos tablas, en ambos casos, si $errorArr no es un array vacío, se definirá la sesión 
    errors con lo que contiene $errorArr; si en ambos casos la inserción fue satisfactoria, se inicializará la sesión "success"; si el try 
    fue exitoso o no, el método invocará el header location que enviará al usuario al formulario de nuevo contacto donde podrá ver el 
    flag de success o los flags de errores y excepciones.
    Puede interceptar 3 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, TiposEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.

    public function insertContact(){
        
        if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){
                
            $hidden_ent_id = (!empty($_POST["hiddenEntId"])) ? $_POST["hiddenEntId"] : null;
            
            try{
                if(isset($hidden_ent_id)){
                    $this->contDTO->empresa_id = $hidden_ent_id;
                    $this->contDTO->nombre_completo = $_POST["contacto"];
                    $errorsArr = ContactVerifications::verifyingInsertion($this->contDTO);

                    (sizeof($errorsArr) === 0) ? $this->contService->insertChild($this->contDTO) :
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

    Este método es invocado dentro del action de uno de los formularios de la vista de edición de empresa y sus contactos, por 
    lo que la variable superglobal post debe estar definida con un número de indices mayor a cero, en caso contrario, el método 
    invocará un header tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login. los indices de post son usados para definir los campos del 
    dto (contDTO y usrDTO) relacionados a estos indices, después, los dtos son evaluados por un método estatico de una de nuestras clases de 
    varificación de datos, si los dos pasan todas las verificaciones, la variable $errorArr será un array vacío.
    En este caso se esta inicializando la propiedad admin_pwd de usrDTO el cual será usado en el helper setAdminVerification 
    junto con el puerto primario UserService llamado en la inyección de dependencias como "usrParticularSrv", este helper puede
    devolver un array vacío o un array con el indice "adminPWDRejected", si $isRejection tiene ese array, entonces se definirá a
    $errorArr con ese array, en dado caso de que $errorArr sea un array vacío se usará el adaptador primario EnterpriseChildrenService de 
    contactos (contService) para actualizar un registro en especifico en la base de datos, en caso contrario, se definirá la sesión 
    errors con lo que contiene $errorArr, si la actualización fue satisfactoria, se inicializará la sesión "success"; si el try fue 
    exitoso o no, el método invocará el header location que enviará a los formularios de edición de empresa y sus contactos
    donde podrá ver el flag de success o de errores y excepciones.
    Puede interceptar 4 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; UnknownInDataBaseException es si el método adminPwdConfirmation arrojó esta 
    excepción en el caso de que la petición a la base de datos para obtener el registro del usuario por su alias 
    devuelva un array vacío; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, UsuariosEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.

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

    Este método es invocado dentro del action del formulario de la ventana emergente para habilitar/inhabilitar un contacto en especifico 
    de la vista de edición de empresa y sus contactos, por lo que la variable superglobal post debe estar definida con un número de indices 
    mayor a cero, en caso contrario, el método invocará un header tipo location que enviará al usuario a la raíz del index "home", si el 
    usuario no está logeado, la raíz index será el login, si lo está puede enviar al logeado a
    la vista del menu de "user" o el mensaje de bienvenida de "Admin". los indices de post son usados para definir los campos del 
    dto (contDTO) relacionados a estos indices, después, el dto es evaluado por un método estatico de una de nuestras clases de 
    varificación de datos, si el dto pasa todas las verificaciones, la variable $errorArr será un array vacío.
    En dado caso de que $errorArr sea un array vacío, se usará el adaptador primario EnterpriseChildrenService de 
    contactos (contService) para actualizar la visibilidad de un registro en especifico en la base de datos, en caso contrario, se 
    definirá la sesión errors con lo que contiene $errorArr, si la actualización fue satisfactoria, se inicializará la sesión 
    "success"; si el try fue exitoso o no, el método invocará el header location que enviará al usuario a la vista de edición de empresa 
    y sus contactos donde podrá ver el flag de success o los flags de errores y excepciones.
    Puede interceptar 4 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; AutomaticValueException es si en el método updateVisibility de CommonService 
    arroja esta excepción en dado caso de que el valor de la propiedad visibilidad sea diferente a "ENABLED" o "DISABLED"; EntityException 
    es si se envió datos que no pasa las evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, 
    UsuariosEntity; finalmente, Exception es para interceptar excepciones de la clase PDO.

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
*/

beforeEach(function(){
    $this->container = testContainerFactory();
    $this->base_url = 'http://localhost:8081/SOSv5/service-order-system/';
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
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'user'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));

        //creación de Empresas para el test
        $enterDTOsArr = mockEntersDTO($this->container);
        for($i = 0; $i < sizeof($enterDTOsArr); $i++){
            $this->container->make('enterService')->insertInfo($enterDTOsArr[$i][0], $enterDTOsArr[$i][1]);
        }
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $values = mockContactIndex($this->container);

    expect($values['enterprises'])->toHaveLength(3);
    expect($values['result'])->toBe('../views/userLayouts/newContactForm.php');
});

test('prueba método insertContact, caso selección de una empresa (satisfactorio)', function(){
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
        $getting_enters = $this->container->make('enterService')->getAllInfo();
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "hiddenEntId" => $getting_enters[2]["Id"],
        "contacto" => "Alondra Sarahí Hernandez"
    ];

    $values = mockInsertContact($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Empresa_id" => $getting_enters[2]["Id"],
        "Nombre_completo" => "Alondra Sarahí Hernandez"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), "Contactos");
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=contact&homeAction=index");
});

test('prueba método insertContact, caso selección de una empresa (campos invalidos)', function(){
    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'user'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "hiddenEntId" => '$getting_enters[2]["Id"]',
        "contacto" => "9498Alondra Sarahí Hernandez%&"
    ];

    $values = mockInsertContact($this->container);

    expect($values['errorsArr'])->toHaveLength(2);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=contact&homeAction=index");
});

test('prueba método insertContact, caso nueva empresa (satisfactorio)', function(){
    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'user'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "contacto" => "Alondra Sarahí Hernandez",
        "nombreComercial" => "Farmacia La Salud",
        "razonSocial" => "Farmacia La Salud de Guanajuato S.A.",
        "calleYNumero" => "Calle Morelos 220",
        "entreCalles" => "Entre Juárez y 5 de Mayo",
        "dirigirseCon" => "Lic. Roberto Gómez",
        "telefonos" => "462-555-1122",
        "horario" => "Todos los días de 8:00 a 22:00",
        "atencion" => "Venta de medicamentos y consultas rápidas",
        "colonia" => "San Juan",
        "localidad" => "León",
        "email" => "ventas@farmaciasalud.com"
    ];

    $values = mockInsertContact($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Nombre_completo" => "Alondra Sarahí Hernandez"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), "Contactos");
    expect([
        "Nombre_comercial" => "Farmacia La Salud",
        "Razon_social" => "Farmacia La Salud de Guanajuato S.A.",
        "Calle_numero" => "Calle Morelos 220",
        "Entre_calles" => "Entre Juárez y 5 de Mayo",
        "Dirigirse_con" => "Lic. Roberto Gómez",
        "Telefonos" => "462-555-1122",
        "Horario" => "Todos los días de 8:00 a 22:00",
        "Atencion" => "Venta de medicamentos y consultas rápidas",
        "Colonia" => "San Juan",
        "Localidad" => "León",
        "Email" => "ventas@farmaciasalud.com"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), "Empresas");
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=contact&homeAction=index");
});

test('prueba método insertContact, caso nueva empresa (campos invalidos)', function(){
    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'user'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "contacto" => "2345Alondra Sarahí Hernandez$%&",
        "nombreComercial" => "<script></script>",
        "razonSocial" => "<script></script>",
        "calleYNumero" => "<script></script>",
        "entreCalles" => "<script></script>",
        "dirigirseCon" => "<script></script>",
        "telefonos" => "qwertyuiop",
        "horario" => "<script></script>",
        "atencion" => "<script></script>",
        "colonia" => "<script></script>",
        "localidad" => "<script></script>",
        "email" => "<script></script>"
    ];

    $values = mockInsertContact($this->container);

    expect($values['errorsArr'])->toHaveLength(12);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=contact&homeAction=index");
});

test('prueba método updateContactInfo, caso satisfactorio', function(){
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

        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[4]);
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "contactoId" => $getting_contacts[0]["Id"],
        "nombre" => "contacto editado",
        "adminContrasena" => "elRojoQueNoEsRojo"
    ];

    $values = mockUpdateContactInfo($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Id" => $getting_contacts[0]["Id"],
        "Nombre_completo" => "Contacto Editado"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), "Contactos");
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=enterprise&homeAction=index");
});

test('prueba método updateContactInfo, caso contraseña de administrador incorrecta', function(){
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

        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[4]);
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "contactoId" => $getting_contacts[0]["Id"],
        "nombre" => "contacto editado",
        "adminContrasena" => "qwertyuiop"
    ];

    $values = mockUpdateContactInfo($this->container);

    expect(isset($_SESSION["errors"]["adminPWDRejected"]))->toBeTrue();
    expect([
        "Id" => $getting_contacts[0]["Id"],
        "Nombre_completo" => "Contacto Editado"
    ])->toBeUnknownInDatabase($this->container->make('SOSTestDatabase'), "Contactos");
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=enterprise&homeAction=index");
});

test('prueba método updateContactInfo, caso campos invalidos', function(){
    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "contactoId" => '$getting_contacts[0]["Id"]',
        "nombre" => "<script>contacto editado</script>",
        "adminContrasena" => "elRojoQueNoEsRojo"
    ];

    $values = mockUpdateContactInfo($this->container);

    expect($values['errorsArr'])->toHaveLength(2);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=enterprise&homeAction=index");
});

test('prueba método enableOrDisableContact, caso satisfactorio', function(){
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

        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[4]);
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "contactoId" => $getting_contacts[0]["Id"],
        "visibilidad" => "DISABLED"
    ];

    $values = mockEnableOrDisableContact($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Id" => $getting_contacts[0]["Id"],
        "Visibilidad" => "DISABLED"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), "Contactos");
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=enterprise&homeAction=index");
});

test('prueba método enableOrDisableContact, caso campos invalidos', function(){
    //creación de usuario para el test
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "contactoId" => '$getting_contacts[0]["Id"]',
        "visibilidad" => "algo"
    ];

    $values = mockEnableOrDisableContact($this->container);

    expect($values['errorArr'])->toHaveLength(2);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=enterprise&homeAction=index");
});