<?php

/* 
class BinnacleController{
    private $binnDTO, $usrDTO, $enterSelectSrv, 
            $binnService, $usrService, $usrParticularSrv;
    public function __construct($binnDTO, $usrDTO, $enterSelectSrv, 
        $binnService, $usrService, $usrParticularSrv){
        $this->binnDTO = $binnDTO;
        $this->usrDTO = $usrDTO;
        $this->enterSelectSrv = $enterSelectSrv;
        $this->binnService = $binnService;
        $this->usrService = $usrService;
        $this->usrParticularSrv = $usrParticularSrv;
    }

    este método controla la generación de la vista del formulario de nueva bitácora, si
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
                $_SESSION["exceptions"]["getInfoForSelectsException"] = "Se generó un "
                        ."error interactuando con la base de datos "
                        ."en cuanto a la obtención de datos para la"
                        ." caja de selección de empresas de la bitácora, lo más "
                        ."probable es que se haya cortado la conexión "
                        ."a la base de datos";
                $enterprises = [];
            }finally{
                require_once '../views/userLayouts/firstForm.php';
            }    
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    este método controla la generación de la vista del listado de bitácoras a dar seguimiento, si
    un usuario no ha accedido a la aplicación, el método invocará un header
    tipo location que enviará al usuario a la raíz del index "home", en este caso, la raíz index será el login.
    En este caso se necesita el dto de bitácoras para asignar el id del usuario que accedió a la aplicación 
    al atributo usuario_id del dto ya que se deben de mostrar las bitácoras relacionadas a este usuario;
    la comunicación asincrona JS <-> PHP en esta vista se define $_SESSION["jsondecoded"]["followUpNumKey"]
    con el numero de elementos que el usuario eligió en el select, si no es el caso, la variable $page_elem
    tendrá un valor por defecto, esta variable se usa para mantener la selección del usuario en el elemento
    select, finalmente, tanto el dto, como $page_elem y el parametro url homeAction se pasarán dentro de los
    parametros opcionales de getAllInfo de nuestro adaptador primario CommonService para bitácoras el cual 
    se encarga de realizar la respectiva paginación para esta vista.
    Puede interceptar 3 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, BitacorasEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.


    public function followuplist(){
        if(!empty($_SESSION["identity"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            
            $this->binnDTO->usuario_id = $_SESSION["identity"]["Id"];
            try {
                (!empty($_SESSION["jsondecoded"]["followUpNumKey"])) ?
                    $page_elem = $_SESSION["jsondecoded"]["followUpNumKey"] :
                    $page_elem = 1;
                
                $binn_pagination = $this->binnService->getAllInfo(
                    $this->binnDTO,
                    $page_elem,
                    null,
                    $_GET["homeAction"]
                );    
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            } catch (Exception $ex) {
                $_SESSION["exceptions"]["paginationArrException"] = "Se generó un "
                        ."error interactuando con la base de datos "
                        ."en cuanto a la generación de paginación".$ex;
            }finally{
                if(!empty($_SESSION["exceptions"])){
                    header("Location: ".base_url."home/");
                    exit;
                }

                require_once '../views/userLayouts/followup.php';    
            }
                
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
        
    }

    este método controla la generación de la vista del reporte de bitácoras, si un usuario no ha accedido 
    a la aplicación, el método invocará un header tipo location que enviará al usuario a la raíz del index 
    "home", si el usuario no está logeado, la raíz index será el login, si lo está puede enviar al logeado a
    la vista del menu de "user" o el mensaje de bienvenida de "Admin".
    En este método en particular se utiliza el helper setBinnFilterSessions en caso de que el usuario haya
    enviado datos para filtrar en el formulario de busqueda de esta vista, por lo que solo funciona si
    si la variable superglobal post tenga indices, si la sesión binnFilterSession está definida, entonces
    se efectuará el proceso de paginación. 
    La comunicación asincrona JS <-> PHP en esta vista se define $_SESSION["jsondecoded"]["binnsReportNumKey"]
    con el numero de elementos que el usuario eligió en el select, si no es el caso, la variable $page_elem
    tendrá un valor por defecto, esta variable se usa para mantener la selección del usuario en el elemento
    select, finalmente, tanto $page_elem, como la sesión binnFilterSession y el parametro url homeAction se pasarán 
    dentro de los parametros opcionales de getAllInfo de nuestro adaptador primario CommonService para bitácoras el cual 
    se encarga de realizar la respectiva paginación para esta vista.
    Puede interceptar 2 excepciones, UnauthorizedDataException es si el helper setBinnFilterSessions detectó que los 
    valores del formulario de busqueda son invalidos; finalmente, Exception es para interceptar excepciones de la clase PDO.

    public function binnaclesReport(){
        if(!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            
            $binn_pagination = [];
            try{
                Utils::setBinnFilterSessions($this->binnDTO);
                $empresas = $this->enterSelectSrv->getInfoForSelects();

                if(!empty($_SESSION["binnFilterSession"])){
                    (!empty($_SESSION["jsondecoded"]["binnsReportNumKey"])) ?
                    $page_elem = $_SESSION["jsondecoded"]["binnsReportNumKey"] :
                    $page_elem = 5;
                
                    $binn_pagination = $this->binnService->getAllInfo(
                        null,
                        $page_elem,
                        $_SESSION["binnFilterSession"],
                        $_GET["homeAction"]
                    );
                }   

            }catch(UnauthorizedDataException $ex){
                $_SESSION["exceptions"]["unauthEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["binnsRowsPaginationEx"] = "Se generó un "
                        . "error interactuando con la base de datos "
                        . "en cuanto a la generación de paginación, posible falta de conexión a la base de datos";
                Utils::unsetBinnFilterSession();
            }finally{
                require_once '../views/adminLayouts/binnaclesFilter.php';
            }
            
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    este método controla la generación de la vista de visualización de una bitácora accedido por medio de las 
    filas de la tabla de reporte de bitácoras, si
    un usuario no ha accedido a la aplicación y/o no es adiministrador y no existe el parametro url homeId, 
    el método invocará un header tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login, si lo está y no es administrador, la raíz index 
    será el menú para "user". por medio del parametro url homeId se setteará la propiedad binnacle_id del dto 
    de bitácoras, aunque se pase en el url un id invalido o un id valido pero inexistente en la base de datos
    se evaluarán por medio de BitácorasEntity y BinnacleQueries respectivamente, el dto finalmente será usado
    por CommonService para bitácoras para obtener la información de la bitácora en cuestión, se utiliza el helper
    setIVAIfAmountIsNotNull para aplicarle el impuesto del iva al campo "Monto" si es que existe, $with_iva tambien
    puede ser null en caso de que "Monto" sea un falsy. Tanto $binn_info como $with_iva se usarán en la vista para
    dar forma a la vista, esta vista puede variar dependiendo del estatus de la bitácora gracias al algoritmo de 
    vizualización de elementos DOM en la vista del canvas de bitácoras. Si el try fue exitoso o no, el método invocará el header location 
    del controllador ErrorController en caso de que $_SESSION["exceptions"]["unKnownEx"] esté definido, o se invocará 
    el header location de la vista del reporte de bitácoras si $_SESSION["exceptions"]["getBinnInfoEx"] o 
    $_SESSION["exceptions"]["entitiesEx"] están definidos, en caso contrario, se importará la vista del canvas de 
    bitácoras.
    Puede interceptar 4 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; UnknownInDataBaseException es si el método getInfo del CommonService 
    para bitácoras arrojó esta excepción en el caso de que la petición a la base de datos para obtener el registro de 
    la bitácora devuelva un array vacío; EntityException es si se envió datos que no pasa las evaluaciones de la clase 
    entidad en cuestión dentro del mappeador a entity, en este caso, BitacorasEntity; finalmente, Exception es para 
    interceptar excepciones de la clase PDO. 

    public function showBinnacle(){
        if(!empty($_SESSION["identity"]) && 
            !empty($_SESSION["isAdmin"])  && 
            !empty($_GET["homeId"])){
            $_SESSION['LAST_ACTIVITY'] = time();

            try{
                $this->binnDTO->binnacle_id = $_GET["homeId"];
                $binn_info = $this->binnService->getInfo($this->binnDTO);
                $with_iva = Utils::setIVAIfAmountIsNotNull($binn_info);
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unKnownEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["getBinnInfoEx"] = "No se logró obtener los "
                            ."datos de la bitácora seleccionada, posible "
                            ."corte de conexión a la base de datos";
            }finally{
                if(!empty($_SESSION["exceptions"]["unKnownEx"])){
                    header("Location: ".base_url."home/?homeController=error&homeAction=index");
                    exit;
                }

                if(!empty($_SESSION["exceptions"]["getBinnInfoEx"]) ||
                    !empty($_SESSION["exceptions"]["entitiesEx"])){
                    header("Location: ".base_url."home/?homeController=user&homeAction=binnaclesReport");
                    exit;
                }

                require_once '../views/adminLayouts/binnacleInfoCanvas.php';
            }
            
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    este método controla la generación de la vista de edición de una bitácora accedido por medio de las 
    filas de la tabla de reporte de bitácoras, si
    un usuario no ha accedido a la aplicación y/o no es adiministrador y no existe el parametro url homeId, 
    el método invocará un header tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login, si lo está y no es administrador, la raíz index 
    será el menú para "user". por medio del parametro url homeId se setteará la propiedad binnacle_id del dto 
    de bitácoras, aunque se pase en el url un id invalido o un id valido pero inexistente en la base de datos
    se evaluarán por medio de BitácorasEntity y BinnacleQueries respectivamente, el dto finalmente será usado
    por CommonService para bitácoras para obtener la información de la bitácora en cuestión, en caso de que 
    el valor del indice "Estatus" de $binn_info sea identico a "en proceso" o "falta confirmar" se usará el
    CommonService para usuarios para definir la variable $usuarios con un array con todos los registros de usuarios 
    en la base de datos, el cual será usado por el select de usuarios
    de la vista, esta vista puede variar dependiendo del estatus de la bitácora gracias al algoritmo de 
    vizualización de elementos DOM en la vista del canvas de bitácoras. Si el try fue exitoso o no, el método 
    invocará el header location del controllador ErrorController en caso de que $_SESSION["exceptions"]["unKnownEx"] 
    esté definido, o se invocará el header location de la vista del reporte de bitácoras si 
    $_SESSION["exceptions"]["getBinnInfoEx"] o $_SESSION["exceptions"]["entitiesEx"] están definidos, en caso contrario, 
    se importará la vista del canvas de bitácoras.
    Puede interceptar 4 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; UnknownInDataBaseException es si el método getInfo del CommonService 
    para bitácoras arrojó esta excepción en el caso de que la petición a la base de datos para obtener el registro de 
    la bitácora devuelva un array vacío; EntityException es si se envió datos que no pasa las evaluaciones de la clase 
    entidad en cuestión dentro del mappeador a entity, en este caso, BitacorasEntity; finalmente, Exception es para 
    interceptar excepciones de la clase PDO.

    public function editBinnacle(){
        if(!empty($_SESSION["identity"]) && 
            !empty($_SESSION["isAdmin"])  && 
            !empty($_GET["homeId"])){
            $_SESSION['LAST_ACTIVITY'] = time();    
              
            try{
                $this->binnDTO->binnacle_id = $_GET["homeId"];
                $binn_info = $this->binnService->getInfo($this->binnDTO);

                if($binn_info["Estatus"] === "en proceso" || $binn_info["Estatus"] === "falta confirmar")
                    $usuarios = $this->usrService->getAllInfo();
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unKnownEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["getBinnInfoEx"] = "No se logró obtener los "
                            ."datos de la bitácora seleccionada, posible "
                            ."corte de conexión a la base de datos";
            }finally{
                if(!empty($_SESSION["exceptions"]["unKnownEx"])){
                    header("Location: ".base_url."home/?homeController=error&homeAction=index");
                    exit;
                }

                if(!empty($_SESSION["exceptions"]["getBinnInfoEx"]) ||
                    !empty($_SESSION["exceptions"]["entitiesEx"])){
                    header("Location: ".base_url."home/?homeController=binnacle&homeAction=binnaclesReport");
                    exit;
                }

                require_once '../views/adminLayouts/binnacleInfoCanvas.php';
            }

        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    Este método es invocado dentro del action del formulario de la vista de nueva bitácora, por lo que la variable 
    superglobal post debe estar definida con un número de indices mayor a cero, en caso contrario, el método invocará un header
    tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login. los indices de post son usados para definir los campos del 
    dto (binnDTO) relacionados a estos indices, después, el dto es evaluado por un método estatico de una de nuestras clases de 
    varificación de datos, si el dto pasa todas las verificaciones, la variable $errorArr será un array vacío.
    En dado caso de que $errorArr sea un array vacío, se usará el adaptador primario CommonService de 
    bitácoras (binnService) para crear el registro en la base de datos, en caso contrario, se definirá la sesión errors con lo que
    contiene $errorArr, si la inserción fue satisfactoria, se inicializará la sesión "success"; si el try fue exitoso o no, el 
    método invocará el header location que enviará al usuario al formulario de nueva bitácora donde podrá ver el flag de 
    success o los flags de errores y excepciones.
    Puede interceptar 3 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, TiposEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.

    public function binninsertion(){
        if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){

            $this->binnDTO->usuario_id = $_POST["userId"];
            $this->binnDTO->contacto_id = $_POST["contactos"];
            $this->binnDTO->actividad = (isset($_POST["tipoActividades"])) ? $_POST["tipoActividades"] : false;
            $this->binnDTO->servicio = (!empty($_POST["servicio"])) ? $_POST["servicio"] : null;
            $this->binnDTO->equipo_id = (!empty($_POST["equipos"])) ? $_POST["equipos"] : null;
            $errorArr = BinnacleVerifications::verifyingInsertion($this->binnDTO);
            
            try{
                (sizeof($errorArr) === 0) ? $this->binnService->insertInfo($this->binnDTO) :
                    $_SESSION["errors"] = $errorArr;
                
                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "La bitácora se a creado con éxito";
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["binnDataException"] = "Acción fallida, probable falta de conexión a la base de datos".$ex;
            }finally{
                header("Location: ".base_url."home/?homeController=binnacle&homeAction=index");
                exit;
            }

        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    Este método es invocado dentro del action del formulario de la vista de edición de bitácora, por 
    lo que la variable superglobal post debe estar definida con un número de indices mayor a cero, en caso contrario, el método 
    invocará un header tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login, si lo está puede enviar al logeado a
    la vista del menu de "user" o el mensaje de bienvenida de "Admin". los indices de post son usados para definir los campos del 
    dto (binnDTO y usrDTO) relacionados a estos indices, después, los dtos son evaluados por un método estatico de una de nuestras clases de 
    varificación de datos, si los dos pasan todas las verificaciones, la variable $errorArr será un array vacío.
    En este caso se esta inicializando la propiedad admin_pwd de usrDTO el cual será usado en el helper setAdminVerification 
    junto con el puerto primario UserService llamado en la inyección de dependencias como "usrParticularSrv", este helper puede
    devolver un array vacío o un array con el indice "adminPWDRejected", si $isRejection tiene ese array, entonces se definirá a
    $errorArr con ese array, en dado caso de que $errorArr sea un array vacío. 
    En este caso en particular, los indices de post pueden estár o no definidos debido al dinámismo del formulario de edición de
    bitácora por su estatus, tanto este método como el método updateBinnacle de la clase BinnacleQueries, contemplan las distintas
    posibilidades de edición de una bitácora por lo que se usará el adaptador primario CommonService de 
    Bitácoras (binnService) para actualizar un registro en especifico en la base de datos, en caso contrario, se definirá la sesión 
    errors con lo que contiene $errorArr, si la actualización fue satisfactoria, se inicializará la sesión "success"; si el try fue 
    exitoso o no, el método invocará el header location que enviará al reporte de bitácoras en el caso de que las sesiónes de errores 
    o excepciones estén definidas, en caso contrario, se enviará al formulario de edición de bitácora con el id respectivo en 
    donde podrá ver el flag de success.
    Puede interceptar 4 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; UnknownInDataBaseException es si el método adminPwdConfirmation arrojó esta 
    excepción en el caso de que la petición a la base de datos para obtener el registro del usuario por su alias 
    devuelva un array vacío; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, UsuariosEntity;
    finalmente, Exception es para interceptar excepciones de la clase PDO.

    public function updateBinnacleInfo(){
        if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){
            
            $this->binnDTO->binnacle_id = $_POST["bitacoraId"];
            $this->binnDTO->estatus = $_POST["estatus"];
            $this->usrDTO->admin_pwd = $_POST["adminContrasena"];
            $this->binnDTO->inicio = $_POST["fechaInicio"];
            if(isset($_POST["usuario"]))
                $this->binnDTO->usuario_id = $_POST["usuario"];
            if(isset($_POST["servicio"]))
                $this->binnDTO->servicio = $_POST["servicio"];
            if(isset($_POST["precio"]))
                $this->binnDTO->monto = $_POST["precio"];
            if(isset($_POST["seHizo"]))
                $this->binnDTO->Actividades_realizadas = $_POST["seHizo"];
            if(isset($_POST["motivoCancelacion"]))
                $this->binnDTO->cancel_desc = $_POST["motivoCancelacion"];
            if(isset($_POST["observaciones"]))
                $this->binnDTO->observaciones = $_POST["observaciones"];
            if(isset($_POST["fechaFin"]))
                $this->binnDTO->fin = $_POST["fechaFin"];
            $errorArr = BinnacleVerifications::verifyingUpdate($this->binnDTO, $this->usrDTO);

            try{
                if(empty($errorArr["adminContrasena"]))
                    $isRejection = Utils::setAdminVerification($this->usrDTO, $this->usrParticularSrv);
                if(sizeof($isRejection) > 0)
                    $errorArr = $isRejection;

                (sizeof($errorArr) === 0) ? $this->binnService->updateInfo($this->binnDTO) :
                    $_SESSION["errors"] = $errorArr;
                   
                if(empty($_SESSION["errors"])) 
                       $_SESSION["success"] = "Se logró editar la bitácora con éxito";
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unKnownEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["binnacleUpdateEx"] = "No se logró editar la bitácora, posible corte de conexión a la base de datos";
            }finally{
                if(!empty($_SESSION["exceptions"]) ||
                    !empty($_SESSION["errors"])){
                    header("Location: ".base_url."home/?homeController=binnacle&homeAction=binnaclesReport");
                    exit;
                }

                header("Location: ".base_url."home/?homeController=binnacle&homeAction=editBinnacle&homeId=".$this->binnDTO->binnacle_id);
                exit;
            }
            
        }else{
            header("Location: ".base_url."home/");
            exit;
        }
    }

    Este método es invocado dentro del action del formulario de la ventana emergente para habilitar/inhabilitar una bitácora en especifico 
    de la tabla de la vista de reportes de bitácoras, por lo que la variable superglobal post debe estar definida con un número de indices 
    mayor a cero, en caso contrario, el método invocará un header tipo location que enviará al usuario a la raíz del index "home", si el 
    usuario no está logeado, la raíz index será el login, si lo está puede enviar al logeado a
    la vista del menu de "user" o el mensaje de bienvenida de "Admin". los indices de post son usados para definir los campos del 
    dto (binnDTO) relacionados a estos indices, después, el dto es evaluado por un método estatico de una de nuestras clases de 
    varificación de datos, si el dto pasa todas las verificaciones, la variable $errorArr será un array vacío.
    En dado caso de que $errorArr sea un array vacío, se usará el adaptador primario CommonService de 
    bitácoras (binnService) para actualizar la visibilidad de un registro en especifico en la base de datos, en caso contrario, se 
    definirá la sesión errors con lo que contiene $errorArr, si la actualización fue satisfactoria, se inicializará la sesión 
    "success"; si el try fue exitoso o no, el método invocará el header location que enviará al usuario a la vista de reporte de bitácoras 
    donde podrá ver el flag de success o los flags de errores y excepciones.
    Puede interceptar 4 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; AutomaticValueException es si en el método updateVisibility de CommonService 
    arroja esta excepción en dado caso de que el valor de la propiedad visibilidad sea diferente a "ENABLED" o "DISABLED"; EntityException 
    es si se envió datos que no pasa las evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, 
    UsuariosEntity; finalmente, Exception es para interceptar excepciones de la clase PDO.

    public function enableOrDisableBinn(){
        if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

            $this->binnDTO->binnacle_id = $_POST["bitacoraId"];
            $this->binnDTO->visibilidad = $_POST["visibilidad"];
            $errorArr = SwitchVerification::verifyingSwitch($this->binnDTO);
            $str_portion_one = ($this->binnDTO->visibilidad === "DISABLED") ? 
            "desactivó":"activó";
            $str_portion_two = ($this->binnDTO->visibilidad === "DISABLED") ? 
            "desactivar":"activar";
            
            try{
                (sizeof($errorArr) === 0) ?
                    $this->binnService->updateVisibility($this->binnDTO) :
                    $_SESSION["errors"] = $errorArr;

                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "Se "
                        .$str_portion_one." la bitácora con ID ".$this->binnDTO->binnacle_id." con éxito";
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(AutomaticValueException $ex){
                $_SESSION["exceptions"]["visibilityEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["disableTypeEx"] = "No se logró ".$str_portion_two." la bitácora con ID ".$this->binnDTO->binnacle_id.", posible corte de conexión a la base de datos";
            }finally{
                header("Location: ".base_url."home/?homeController=binnacle&homeAction=binnaclesReport");
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
    $this->buttons = '<div class="Zebra_Pagination"><ol class="pagination"><li class="page-item disabled"><a href="javascript:void(0)" class="page-link">&laquo;</a></li><li class="page-item active"><a href="/SOSv5/service-order-system/home" class="page-link">1</a></li><li class="page-item disabled"><a href="javascript:void(0)" class="page-link">&raquo;</a></li></ol></div>';
});

afterEach(function(){
    $_SESSION = [];
    $_POST = [];
    $_GET = [];
    $_SERVER = [];
    cleanTable($this->container->make('SOSTestDatabase'), "Bitacoras");
    cleanTable($this->container->make('SOSTestDatabase'), "Contactos");
    cleanTable($this->container->make('SOSTestDatabase'), "Equipos");
    cleanTable($this->container->make('SOSTestDatabase'), "Empresas");
    cleanTable($this->container->make('SOSTestDatabase'), "Tipos");
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

    $values = mockBinnIndex($this->container);

    expect(intval($_SESSION['LAST_ACTIVITY']))->toBeInt();
    expect($values['enterprises'])->toHaveLength(3);
    expect($values['result'])->toBe('../views/userLayouts/firstForm.php');
});

test('prueba followuplist', function(){
    
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=followuplist';
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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
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
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_SESSION["jsondecoded"]["followUpNumKey"] = 5;
    $_GET["homeAction"] = "followuplist";
    $values = mockFollowuplist($this->container);

    expect(intval($_SESSION['LAST_ACTIVITY']))->toBeInt();
    expect($values['binn_pagination']["binns"])->toHaveLength(4);
    expect($values['binn_pagination']["buttons"])->toBe($this->buttons);
    expect($values['result'])->toBe('../views/userLayouts/followup.php');
});

test('prueba binnaclesReport', function(){
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

    $values = mockBinnaclesReport($this->container);

    expect(intval($_SESSION['LAST_ACTIVITY']))->toBeInt();
    expect($values['empresas'])->toHaveLength(3);
    expect($values['result'])->toBe('../views/adminLayouts/binnaclesFilter.php');
});

test('prueba binnaclesReport, caso asignación de filtros', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=binnaclesFilter';
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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
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
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_GET["homeAction"] = "binnaclesReport";
    $_POST = [
        "empresaId" => $getting_enters[2]["Id"],
        "contactoId" => $getting_contacts[0]["Id"],
        "servicioOEquipo" => "Equipo_id",
        "equipoId" => $getting_dces[0]["Id"],
        "estatus" => "falta confirmar",
        "startedOrEnded" => "Inicio",
        "leftDay" => "2026-07-08",
        "rightDay" => "2026-07-20",
        "visible" => "ENABLED",
    ];
    $values = mockBinnaclesReport($this->container);

    expect(intval($_SESSION['LAST_ACTIVITY']))->toBeInt();
    expect($values['binn_pagination']["binns"])->toHaveLength(1);
    expect($values['binn_pagination']["buttons"])->toBe($this->buttons);
    expect($values['result'])->toBe($_SESSION["header"]);
});

test('prueba binnaclesReport, caso asignación de filtros por defecto', function(){
    
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=binnaclesFilter';
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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
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
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_GET["homeAction"] = "binnaclesReport";
    $_POST = [
        "empresaId" => "",
        "contactoId" => "",
        "servicioOEquipo" => "Equipo_id",
        "equipoId" => "",
        "estatus" => "falta confirmar",
        "startedOrEnded" => "Inicio",
        "leftDay" => "",
        "rightDay" => "",
        "visible" => "ENABLED",
    ];
    $values = mockBinnaclesReport($this->container);

    expect(intval($_SESSION['LAST_ACTIVITY']))->toBeInt();
    expect($values['binn_pagination']["binns"])->toHaveLength(2);
    expect($values['binn_pagination']["buttons"])->toBe($this->buttons);
    expect($values['result'])->toBe($_SESSION["header"]);
});

test('prueba binnaclesReport, caso asignación de filtros (campos invalidos)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=binnaclesReport';
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

    $_GET["homeAction"] = "binnaclesReport";
    $_POST = [
        "empresaId" => "qwerty",
        "contactoId" => "qwerty",
        "servicioOEquipo" => "<script></script>",
        "equipoId" => "qwerty",
        "estatus" => "<script></script>",
        "startedOrEnded" => "<script></script>",
        "leftDay" => "2026/07/08",
        "rightDay" => "2026/07/08",
        "visible" => "ENABLED",
    ];
    $values = mockBinnaclesReport($this->container);

    expect(intval($_SESSION['LAST_ACTIVITY']))->toBeInt();
    expect(isset($_SESSION["exceptions"]["unauthEx"]))->toBeTrue();
    expect($values['result'])->toBe('../views/adminLayouts/binnaclesFilter.php');
});

test('prueba showBinnacle, caso bitácora con dispositivo', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=showBinnacle';
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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
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
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
        $binnIds = getBinnIds($this->container->make('SOSTestDatabase'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_GET["homeId"] = $binnIds[2]["Id"];
    $values = mockShowBinnacle($this->container);

    expect(intval($_SESSION['LAST_ACTIVITY']))->toBeInt();
    expect($values['binn_info'])->toHaveLength(29);
    expect($values['result'])->toBe('../views/adminLayouts/binnacleInfoCanvas.php');
});

test('prueba showBinnacle, caso bitácora con servicio', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=showBinnacle';
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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
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
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
        $binnIds = getBinnIds($this->container->make('SOSTestDatabase'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_GET["homeId"] = $binnIds[0]["Id"];
    $values = mockShowBinnacle($this->container);

    expect(intval($_SESSION['LAST_ACTIVITY']))->toBeInt();
    expect($values['binn_info'])->toHaveLength(25);
    expect($values['result'])->toBe('../views/adminLayouts/binnacleInfoCanvas.php');
});

test('prueba editBinnacle, caso estatus igual a "en proceso" o "falta confirmar"', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=editBinnacle';
    //creación de usuarios para el test
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
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
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
        $binnIds = getBinnIds($this->container->make('SOSTestDatabase'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_GET["homeId"] = $binnIds[0]["Id"];
    $values = mockEditBinnacle($this->container);

    expect(intval($_SESSION['LAST_ACTIVITY']))->toBeInt();
    expect($values['binn_info'])->toHaveLength(25);
    expect($values['usuarios'])->toHaveLength(3);
    expect($values['result'])->toBe('../views/adminLayouts/binnacleInfoCanvas.php');
});

test('prueba editBinnacle, caso estatus diferente a "en proceso" o "falta confirmar"', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=editBinnacle';
    //creación de usuarios para el test
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
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
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
        $binnIds = getBinnIds($this->container->make('SOSTestDatabase'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_GET["homeId"] = $binnIds[4]["Id"];
    $values = mockEditBinnacle($this->container);

    expect(intval($_SESSION['LAST_ACTIVITY']))->toBeInt();
    expect($values['binn_info'])->toHaveLength(25);
    expect($values['usuarios'])->toBeNull();
    expect($values['result'])->toBe('../views/adminLayouts/binnacleInfoCanvas.php');
});

test('prueba binninsertion, caso actividad "servicio"', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=binninsertion';
    //creación de usuarios para el test
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);

        //creación de tipos para el test
        // $typDTOsArr = mockTypesDTO($this->container->make('typDTO'), $this->container->make('typDTO'), $this->container->make('typDTO'));
        // foreach($typDTOsArr as $dto){
        //     $this->container->make('typService')->insertInfo($dto);
        // }
        // $getting_typs = $this->container->make('typService')->getAllInfo();
        // $typIds = [];
        // foreach($getting_typs as $typ){
        //     $typIds[] = $typ["Id"];
        // }

        //creación de equipos para el test
        // $dceDTOsArr = mockDevicesDTO($this->container, $enterIds, $typIds);
        // foreach($dceDTOsArr as $dto){
        //     $this->container->make('dceService')->insertChild($dto);
        // }
        //$getting_dces = $this->container->make('dceService')->getChildrenByEnterprise($dceDTOsArr[5]);
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "userId" => $_SESSION["identity"]["Id"],
        "contactos" => $getting_contacts[2]["Id"],
        "tipoActividades" => "servicio",
        "servicio" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur non consequatur dolorem neque in nobis, facilis magni quod dolorum autem dolore accusantium ratione perferendis nostrum. Atque voluptas provident doloremque officiis?",
        "equipos" => "",
    ];
    $values = mockBinninsertion($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Usuario_id" => $_SESSION["identity"]["Id"],
        "Contacto_id" => $getting_contacts[2]["Id"],
        "Servicio" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur non consequatur dolorem neque in nobis, facilis magni quod dolorum autem dolore accusantium ratione perferendis nostrum. Atque voluptas provident doloremque officiis?"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), 'Bitacoras');
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=index");
});

test('prueba binninsertion, caso actividad "equipo"', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=binninsertion';
    //creación de usuarios para el test
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);

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
        "userId" => $_SESSION["identity"]["Id"],
        "contactos" => $getting_contacts[2]["Id"],
        "tipoActividades" => "equipo",
        "servicio" => "",
        "equipos" => $getting_dces[0]["Id"],
    ];
    $values = mockBinninsertion($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Usuario_id" => $_SESSION["identity"]["Id"],
        "Contacto_id" => $getting_contacts[2]["Id"],
        "Equipo_id" => $getting_dces[0]["Id"]
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), 'Bitacoras');
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=index");
});

test('prueba binninsertion, caso campos invalidos', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=binninsertion';
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
        "userId" => '$_SESSION["identity"]["Id"]',
        "contactos" => '$getting_contacts[2]["Id"]'
    ];
    $values = mockBinninsertion($this->container);

    expect($values['errorArr'])->toHaveLength(3);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=index");
});

test('prueba binninsertion, caso actividad "servicio" (campos invalidos)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=binninsertion';
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
        "userId" => '$_SESSION["identity"]["Id"]',
        "contactos" => '$getting_contacts[2]["Id"]',
        "tipoActividades" => "servicio",
        "servicio" => "<script></script>",
        "equipos" => '$getting_dces[0]["Id"]',
    ];
    $values = mockBinninsertion($this->container);

    expect($values['errorArr'])->toHaveLength(4);
    expect(isset($values['errorArr']['servicio']))->toBeTrue();
    expect(isset($values['errorArr']['equipos']))->toBeFalse();
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=index");
});

test('prueba binninsertion, caso actividad "equipo" (campos invalidos)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=binninsertion';
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
        "userId" => '$_SESSION["identity"]["Id"]',
        "contactos" => '$getting_contacts[2]["Id"]',
        "tipoActividades" => "equipo",
        "servicio" => "<script></script>",
        "equipos" => '$getting_dces[0]["Id"]',
    ];
    $values = mockBinninsertion($this->container);

    expect($values['errorArr'])->toHaveLength(4);
    expect(isset($values['errorArr']['servicio']))->toBeFalse();
    expect(isset($values['errorArr']['equipos']))->toBeTrue();
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=index");
});

test('prueba método updateBinnacleInfo, caso estatus "en proceso" (satisfactorio)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=updateBinnacleInfo';
    //creación de usuarios para el test
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
        $getting_users = $this->container->make('usrService')->getAllInfo();

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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
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
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
        $binnIds = getBinnIds($this->container->make('SOSTestDatabase'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "bitacoraId" => $binnIds[0]["Id"],
        "estatus" => "en proceso",
        "adminContrasena" => "elRojoQueNoEsRojo",
        "fechaInicio" => "2026-07-04",
        "usuario" => $getting_users[1]["Id"],
        "servicio" => "servicio editado"
    ];

    $values = mockUpdateBinnacleInfo($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Id" => $binnIds[0]["Id"],
        "Estatus" => "en proceso",
        "Inicio" => "2026-07-04",
        "Usuario_id" => $getting_users[1]["Id"],
        "Servicio" => "servicio editado"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), 'Bitacoras');
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=editBinnacle&homeId=".$binnIds[0]["Id"]);
});

test('prueba método updateBinnacleInfo, caso estatus "en proceso" (contraseña de administrador incorrecta)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=updateBinnacleInfo';
    //creación de usuarios para el test
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
        $getting_users = $this->container->make('usrService')->getAllInfo();

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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
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
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
        $binnIds = getBinnIds($this->container->make('SOSTestDatabase'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "bitacoraId" => $binnIds[0]["Id"],
        "estatus" => "en proceso",
        "adminContrasena" => "qwertyuiop",
        "fechaInicio" => "2026-07-04",
        "usuario" => $getting_users[1]["Id"],
        "servicio" => "servicio editado"
    ];

    $values = mockUpdateBinnacleInfo($this->container);

    expect(isset($_SESSION["errors"]["adminPWDRejected"]))->toBeTrue();
    expect([
        "Id" => $binnIds[0]["Id"],
        "Estatus" => "en proceso",
        "Inicio" => "2026-07-04",
        "Usuario_id" => $getting_users[1]["Id"],
        "Servicio" => "servicio editado"
    ])->toBeUnknownInDatabase($this->container->make('SOSTestDatabase'), 'Bitacoras');
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=editBinnacle&homeId=".$binnIds[0]["Id"]);
});

test('prueba método updateBinnacleInfo, caso estatus "en proceso" (campos invalidos)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=updateBinnacleInfo';
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
        "bitacoraId" => '$binnIds[0]["Id"]',
        "estatus" => "algo",
        "adminContrasena" => "elRojoQueNoEsRojo",
        "fechaInicio" => "2026/07/04",
        "usuario" => '$getting_users[1]["Id"]',
        "servicio" => "<script></script>"
    ];

    $values = mockUpdateBinnacleInfo($this->container);

    expect($values['errorArr'])->toHaveLength(5);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=editBinnacle&homeId=".'$binnIds[0]["Id"]');
});

test('prueba método updateBinnacleInfo, caso estatus "falta confirmar" (satisfactorio)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=updateBinnacleInfo';
    //creación de usuarios para el test
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
        $getting_users = $this->container->make('usrService')->getAllInfo();

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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
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
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
        $binnIds = getBinnIds($this->container->make('SOSTestDatabase'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "bitacoraId" => $binnIds[3]["Id"],
        "estatus" => "falta confirmar",
        "adminContrasena" => "elRojoQueNoEsRojo",
        "fechaInicio" => "2026-07-04",
        "usuario" => $getting_users[1]["Id"],
        "precio" => "2345.50",
        "seHizo" => "campo editado",
        "observaciones" => "campo editado"
    ];

    $values = mockUpdateBinnacleInfo($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Id" => $binnIds[3]["Id"],
        "Estatus" => "falta confirmar",
        "Inicio" => "2026-07-04",
        "Usuario_id" => $getting_users[1]["Id"],
        "Monto" => "2345.50",
        "Actividades_realizadas" => "campo editado",
        "Observaciones" => "campo editado"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), 'Bitacoras');
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=editBinnacle&homeId=".$binnIds[3]["Id"]);
});

test('prueba método updateBinnacleInfo, caso estatus "falta confirmar" (contraseña de administrador incorrecta)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=updateBinnacleInfo';
    //creación de usuarios para el test
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
        $getting_users = $this->container->make('usrService')->getAllInfo();

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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
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
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
        $binnIds = getBinnIds($this->container->make('SOSTestDatabase'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "bitacoraId" => $binnIds[3]["Id"],
        "estatus" => "falta confirmar",
        "adminContrasena" => "qwertyuiop",
        "fechaInicio" => "2026-07-04",
        "usuario" => $getting_users[1]["Id"],
        "precio" => "2345.50",
        "seHizo" => "campo editado",
        "observaciones" => "campo editado"
    ];

    $values = mockUpdateBinnacleInfo($this->container);

    expect(isset($_SESSION["errors"]["adminPWDRejected"]))->toBeTrue();
    expect([
        "Id" => $binnIds[3]["Id"],
        "Estatus" => "falta confirmar",
        "Inicio" => "2026-07-04",
        "Usuario_id" => $getting_users[1]["Id"],
        "Monto" => "2345.50",
        "Actividades_realizadas" => "campo editado",
        "Observaciones" => "campo editado"
    ])->toBeUnknownInDatabase($this->container->make('SOSTestDatabase'), 'Bitacoras');
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=editBinnacle&homeId=".$binnIds[3]["Id"]);
});

test('prueba método updateBinnacleInfo, caso estatus "falta confirmar" (campos invalidos)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=updateBinnacleInfo';
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
        "bitacoraId" => '$binnIds[3]["Id"]',
        "estatus" => "algo",
        "adminContrasena" => "elRojoQueNoEsRojo",
        "fechaInicio" => "2026/07/04",
        "usuario" => '$getting_users[1]["Id"]',
        "precio" => "2345.50.456.5778",
        "seHizo" => "<script></script>",
        "observaciones" => "<script></script>"
    ];

    $values = mockUpdateBinnacleInfo($this->container);

    expect($values['errorArr'])->toHaveLength(7);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=editBinnacle&homeId=".'$binnIds[3]["Id"]');
});

test('prueba método updateBinnacleInfo, caso estatus "cancelado" (satisfactorio)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=updateBinnacleInfo';
    //creación de usuarios para el test
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
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
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
        $binnIds = getBinnIds($this->container->make('SOSTestDatabase'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "bitacoraId" => $binnIds[4]["Id"],
        "estatus" => "cancelado",
        "adminContrasena" => "elRojoQueNoEsRojo",
        "fechaInicio" => "2026-07-04",
        "servicio" => "campo editado",
        "motivoCancelacion" => "campo editado"
    ];

    $values = mockUpdateBinnacleInfo($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Id" => $binnIds[4]["Id"],
        "Estatus" => "cancelado",
        "Inicio" => "2026-07-04",
        "Servicio" => "campo editado",
        "Observaciones" => "campo editado"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), 'Bitacoras');
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=editBinnacle&homeId=".$binnIds[4]["Id"]);
});

test('prueba método updateBinnacleInfo, caso estatus "cancelado" (contraseña de administrador incorrecta)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=updateBinnacleInfo';
    //creación de usuarios para el test
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
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
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
        $binnIds = getBinnIds($this->container->make('SOSTestDatabase'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "bitacoraId" => $binnIds[4]["Id"],
        "estatus" => "cancelado",
        "adminContrasena" => "qwertyuiop",
        "fechaInicio" => "2026-07-04",
        "servicio" => "campo editado",
        "motivoCancelacion" => "campo editado"
    ];

    $values = mockUpdateBinnacleInfo($this->container);

    expect(isset($_SESSION["errors"]["adminPWDRejected"]))->toBeTrue();
    expect([
        "Id" => $binnIds[4]["Id"],
        "Estatus" => "cancelado",
        "Inicio" => "2026-07-04",
        "Servicio" => "campo editado",
        "Observaciones" => "campo editado"
    ])->toBeUnknownInDatabase($this->container->make('SOSTestDatabase'), 'Bitacoras');
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=editBinnacle&homeId=".$binnIds[4]["Id"]);
});

test('prueba método updateBinnacleInfo, caso estatus "cancelado" (campos invalidos)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=updateBinnacleInfo';
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
        "bitacoraId" => '$binnIds[4]["Id"]',
        "estatus" => "algo",
        "adminContrasena" => "elRojoQueNoEsRojo",
        "fechaInicio" => "2026/07/04",
        "servicio" => "<script></script>",
        "motivoCancelacion" => "<script></script>"
    ];

    $values = mockUpdateBinnacleInfo($this->container);

    expect($values['errorArr'])->toHaveLength(5);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=editBinnacle&homeId=".'$binnIds[4]["Id"]');
});

test('prueba método updateBinnacleInfo, caso estatus "finalizado" (satisfactorio)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=updateBinnacleInfo';
    //creación de usuarios para el test
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
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
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
        $binnIds = getBinnIds($this->container->make('SOSTestDatabase'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "bitacoraId" => $binnIds[5]["Id"],
        "estatus" => "finalizado",
        "adminContrasena" => "elRojoQueNoEsRojo",
        "fechaInicio" => "2026-07-04",
        "fechaFin" => "2026-07-08",
        "seHizo" => "campo editado",
        "observaciones" => "campo editado"
    ];

    $values = mockUpdateBinnacleInfo($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Id" => $binnIds[5]["Id"],
        "Estatus" => "finalizado",
        "Inicio" => "2026-07-04",
        "Fin" => "2026-07-08",
        "Actividades_realizadas" => "campo editado",
        "Observaciones" => "campo editado"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), 'Bitacoras');
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=editBinnacle&homeId=".$binnIds[5]["Id"]);
});

test('prueba método updateBinnacleInfo, caso estatus "finalizado" (contraseña de administrador incorrecta)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=updateBinnacleInfo';
    //creación de usuarios para el test
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
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
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
        $binnIds = getBinnIds($this->container->make('SOSTestDatabase'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "bitacoraId" => $binnIds[5]["Id"],
        "estatus" => "finalizado",
        "adminContrasena" => "qwertyuiop",
        "fechaInicio" => "2026-07-04",
        "fechaFin" => "2026-07-08",
        "seHizo" => "campo editado",
        "observaciones" => "campo editado"
    ];

    $values = mockUpdateBinnacleInfo($this->container);

    expect(isset($_SESSION["errors"]["adminPWDRejected"]))->toBeTrue();
    expect([
        "Id" => $binnIds[5]["Id"],
        "Estatus" => "finalizado",
        "Inicio" => "2026-07-04",
        "Fin" => "2026-07-08",
        "Actividades_realizadas" => "campo editado",
        "Observaciones" => "campo editado"
    ])->toBeUnknownInDatabase($this->container->make('SOSTestDatabase'), 'Bitacoras');
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=editBinnacle&homeId=".$binnIds[5]["Id"]);
});

test('prueba método updateBinnacleInfo, caso estatus "finalizado" (campos invalidos)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=updateBinnacleInfo';
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
        "bitacoraId" => '$binnIds[5]["Id"]',
        "estatus" => "algo",
        "adminContrasena" => "elRojoQueNoEsRojo",
        "fechaInicio" => "2026/07/04",
        "fechaFin" => "2026/07/08",
        "seHizo" => "<script></script>",
        "observaciones" => "<script></script>"
    ];

    $values = mockUpdateBinnacleInfo($this->container);

    expect($values['errorArr'])->toHaveLength(6);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=editBinnacle&homeId=".'$binnIds[5]["Id"]');
});

test('prueba método enableOrDisableBinn, caso satisfactorio', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=enableOrDisableBinn';
    //creación de usuarios para el test
    try{
        $usrDTOsArr = mockUsersDTO($this->container->make('usrDTO'), $this->container->make('usrDTO'), $this->container->make('usrDTO'));
        foreach($usrDTOsArr as $dto){
            $this->container->make('usrService')->insertInfo($dto);
        }
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

        //creación de contactos para el test
        $contDTOsArr = mockContactsDTO($this->container, $enterIds);
        foreach($contDTOsArr as $dto){
            $this->container->make('contService')->insertChild($dto);
        }
        $getting_contacts = $this->container->make('contService')->getChildrenByEnterprise($contDTOsArr[5]);
        $contIds = [];
        foreach($getting_contacts as $cont){
            $contIds[] = $cont["Id"];
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
        $dceIds = [];
        foreach($getting_dces as $dce){
            $dceIds[] = $dce["Id"];
        }

        //creación de Bitácoras para el test
        $binnDTOsArr = mockBinnDTOs($this->container, $_SESSION["identity"]["Id"], $contIds, $dceIds);
        foreach($binnDTOsArr as $dto){
            $this->container->make('binnService')->insertInfo($dto);
        }
        $binnIds = getBinnIds($this->container->make('SOSTestDatabase'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_POST = [
        "bitacoraId" => $binnIds[5]["Id"],
        "visibilidad" => "DISABLED"
    ];

    $values = mockEnableOrDisableBinn($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Id" => $binnIds[5]["Id"],
        "Visibilidad" => "DISABLED"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), 'Bitacoras');
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=binnaclesReport");
});

test('prueba método enableOrDisableBinn, caso campos invalidos', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=enableOrDisableBinn';
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
        "bitacoraId" => '$binnIds[5]["Id"]',
        "visibilidad" => "algo"
    ];

    $values = mockEnableOrDisableBinn($this->container);

    expect($values['errorArr'])->toHaveLength(2);
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=binnaclesReport");
});