<?php

/* 
class FollowupformController{
    private $binnDTO, $binnService, $binnParticularSrv;
    public function __construct($binnDTO, $binnService, $binnParticularSrv){
        $this->binnDTO = $binnDTO;
        $this->binnService = $binnService;
        $this->binnParticularSrv = $binnParticularSrv;
    }

    este método controla la generación de la vista del formulario de seguimiento de bitácora o confirmación de actividades, si
    un usuario no ha accedido a la aplicación y/o el parametro "id" de la url no está definido, la raíz index será el login, 
    si lo está puede enviar al logeado a la vista del menu de "user" o el mensaje de bienvenida de "Admin". 
    Se define $_SESSION['LAST_ACTIVITY'] con el tiempo actual en cada vista ya que en esta aplicación esta pensado para que la 
    sesión expire después de 30 minutos. Se pasa el valor del parametro url "id" y el id del usuario responsable de una bitácora
    (el cual es el que inicializó la sesión identity)
    en sus respectivos atributos del objeto dto binnDTO, se usa el adaptador primario CommonService de Bitácoras (binnService) 
    para obtener los datos de la bitácora, a pesar de que el id se puede escribir en la url, la entidad de bitácoras evalúa si 
    el id que se está pasando es apto para pasarlo en el gestor de sql server, en caso contrario arrojará una excepción abortando
    el proceso, en dado caso que se pase un valor valido en la url pero resulta en un id inexistente, la clase queries de bitácoras
    arrojará una excepción en dado caso de que PDO devuelva un array vacio. Si la variable $info tiene información de una bitácora, 
    entonces se hace uso del helper isAuthorizedBinnacle en el cual se evalúa el indice "Estatus" de $info, si el valor de este 
    indice es diferente a "cancelado" o "finalizado" no arrojará excepción, en ese caso se evaluará el indice "Actividades_realizadas" 
    de $info, si este no tiene algún valor falsy se inicializará la variable $info_verified con la ruta relativa de la vista de 
    confirmación de actividades, en caso contrario, será la ruta relativa de la vista de seguimiento de bitácora. si el try fue 
    exitoso o no, el método invocará el header location de la raíz del index de home en caso de que $_SESSION["exceptions"] esté 
    definida, si esa sesión no está definida entonces se importará la vista de la ruta relativa que contenga la variable $info_verified.
    Puede interceptar 4 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; UnknownInDataBaseException es si el método getInfo arrojó esta 
    excepción en el caso de que la petición a la base de datos para obtener el registro de la bitácora 
    devuelva un array vacío; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, BitacorasEntity; UnauthorizedDataException 
    es si el helper isAuthorizedBinnacle detectó que el indice "Estatus" de $info es igual a "cancelado" o "finalizado"; 
    finalmente, Exception es para interceptar excepciones de la clase PDO.

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

    este método controla la generación de la vista del pad de firmas de técnicos, si
    un usuario no ha accedido a la aplicación y/o la $_SESSION["formSession"]["dataSelectionForSigns"] no está 
    definida (sesión que se define interceptando la variable superglobal post después del action del formulario 
    de confirmación de actividades o de la edición de firma de un usuario), la raíz index será el login, 
    si lo está puede enviar al logeado a la vista del menu de "user" o el mensaje de bienvenida de "Admin".

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

    este método controla la generación de la vista del pad de firmas del cliente, si
    un usuario no ha accedido a la aplicación y/o la $_SESSION["formSession"]["dataSelectionForSigns"]["binnId"] no está 
    definida (sesión que se define interceptando la variable superglobal post después del action del formulario 
    de confirmación de actividades), la raíz index será el login, 
    si lo está puede enviar al logeado a la vista del menu de "user" o el mensaje de bienvenida de "Admin".

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

    Este método es invocado dentro del action del formulario de la vista de seguimiento de bitácora, por 
    lo que la variable superglobal post debe estar definida con un número de indices mayor a cero, en caso contrario, el método 
    invocará un header tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login, si lo está puede enviar al logeado a
    la vista del menu de "user" o el mensaje de bienvenida de "Admin". los indices de post son usados para definir los campos del 
    dto (binnDTO) relacionados a estos indices, después, el dto es evaluado por un método estatico de una de nuestras clases de 
    varificación de datos, si el dto pasa todas las verificaciones, la variable $errorArr será un array vacío.
    En dado caso de que $errorArr sea un array vacío, se usará el adaptador primario BinnacleService (binnParticularSrv) para 
    actualizar campos parciales de un registro en especifico de bitácora en la base de datos, en caso contrario, se 
    definirá la sesión errors con lo que contiene $errorArr.
    si el try fue exitoso o no, el método invocará el header location de la vista de lista de bitácoras a dar seguimiento de home en 
    caso de que $_SESSION["exceptions"] esté definida, si esa sesión no está definida entonces se invocará el header location de la vista 
    del index de followupformController junto al parametro get "id" de la bitácora en cuestión.
    Puede interceptar 3 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, BitacorasEntity; finalmente, Exception 
    es para interceptar excepciones de la clase PDO.

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

    Este método es invocado en el href de la ventana emergente de cancelación del progreso de la bitácora en la vista de 
    confirmación de actividades, si un usuario no ha accedido a la aplicación y/o el parametro "id" de la url no está definido, 
    el método invocará un header tipo location que enviará al usuario a la raíz del index "home", 
    si el usuario no está logeado, la raíz index será el login, si lo está puede enviar al logeado a
    la vista del menu de "user" o el mensaje de bienvenida de "Admin". Se pasa el valor del parametro url "id" y el id del usuario 
    responsable de una bitácora (el cual es el que inicializó la sesión identity) en sus respectivos atributos del objeto dto binnDTO.
    como los dtos como binnDTO inicializan sus atributos por defecto en null dentro de sus constructores
    el adaptador primario BinnacleService (binnParticularSrv) actualizará el campo "Actividades_realizadas" y "Observaciones" con 
    valores tipo null, la proxima vez que este Id de bitácora sea evaluada en el método index será visualizada en la vista de 
    seguimiento de bitácora.
    si el try fue exitoso o no, el método invocará el header location de la vista de lista de bitácoras a dar seguimiento de home en 
    caso de que $_SESSION["exceptions"] esté definida, si esa sesión no está definida entonces se invocará el header location de la vista 
    del index de followupformController junto al parametro get "id" de la bitácora en cuestión.
    Puede interceptar 4 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, BitacorasEntity; UnauthorizedDataException 
    es si la clase queries de bitácoras en su método resetActivitiesDesc detectó que el indice "Estatus" del resultado del query es 
    igual a "cancelado" o "finalizado"; finalmente, Exception es para interceptar excepciones de la clase PDO.

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

    Este método es invocado dentro del action del formulario secundario de cancelación definitiva de bitácora de la vista de 
    seguimiento de bitácora, por lo que la variable superglobal post debe estar definida con un número de indices mayor a cero, 
    en caso contrario, el método invocará un header tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login, si lo está puede enviar al logeado a
    la vista del menu de "user" o el mensaje de bienvenida de "Admin". los indices de post son usados para definir los campos del 
    dto (binnDTO) relacionados a estos indices, después, el dto es evaluado por un método estatico de una de nuestras clases de 
    varificación de datos, si el dto pasa todas las verificaciones, la variable $errorArr será un array vacío.
    En dado caso de que $errorArr no sea un array vacío, se definirá la sesión errors con lo que contiene $errorArr, en caso 
    contrario, se usará el adaptador primario BinnacleService (binnParticularSrv) para 
    actualizar campos "Estatus" y "Observaciones" en "cancelado" y el motivo de la cancelación respectivamente, en BitacorasEntity 
    se evalua si el atributo cancel_desc del dto está definido, si lo está, entonces se le asignará al atributo observaciones de la entidad, 
    en caso contrario se tomará en cuenta el atributo observaciones del dto para ser setteado al de la Entidad.
    si el try fue exitoso o no, el método invocará el header location de la vista del index de followupformController junto al parametro 
    get "id" de la bitácora en cuestión en caso de que $_SESSION["exceptions"] esté definida, si esa sesión no está definida entonces 
    se invocará el header location de la vista de lista de bitácoras a dar seguimiento de home.
    Puede interceptar 3 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, BitacorasEntity; finalmente, Exception es 
    para interceptar excepciones de la clase PDO.

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
    
    Este método es invocado en el href de la ventana emergente de "firma de cliente guardado con éxito" del pad de 
    firmas del cliente; debe de existir la sesión "identity", los indices "dataSelectionForSigns" y "clientSignature" 
    de la sesión formSession y el indice "Firma" de identity no debe ser falsy, en caso que no se cumpla la condición,
    el método invocará un header tipo location que enviará al usuario a la raíz del index "home", si el usuario
    no está logeado, la raíz index será el login, si lo está puede enviar al logeado a la vista del menu de "user" o el 
    mensaje de bienvenida de "Admin". Una bitácora será finalizada una vez que se registre la firma de consentimiento 
    del cliente por lo que se le asignan a las propiedades necesarias para esta funcionalidad de binnDTO los valores 
    repectivos tanto de los indices de formSession como de identity, este dto es necesario para utilizar el método 
    finishBinnacle del adaptador primario BinnacleService (binnParticularSrv), el cual actualiza la bitácora en cuestión
    dandole valores a los campos "Fin" (con GETDATE de sql server) y el "Estatus" en finalizado junto con el nombre del 
    archivo de la firma del cliente en el campo "Firma_cliente". en caso de que try sea satisfactorio o no, se eliminará
    el archivo de la firma del cliente en caso de que $_SESSION["exceptions"] esté definida, se elimina la sesión "formSession"
    y se invoca el header location de la vista de la lista de bitácoras a dar seguimiento de home.
    Puede interceptar 3 excepciones, WrongObjectException es si se está pasando un dto distinto al solicitado por
    los mappeadores del servicio que se está usando; EntityException es si se envió datos que no pasa las 
    evaluaciones de la clase entidad en cuestión dentro del mappeador a entity, en este caso, BitacorasEntity; finalmente, Exception es 
    para interceptar excepciones de la clase PDO.


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

Index finishing:

<?php
    ob_start();
    El index de la carpeta finishing es el "html" del formulario extendido de una bitácora, html entre comillas ya 
    * que se utiliza PHP para importar dependencias como los controladores y archivos html que comienzan 
    * y finalizan la semantica html gracias a require_once, los elementos html que están "en el medio" los 
    * gestionan los controladores dentro de la estructura if de este documento, y en cuanto a "formulario extendido" de una bitácora, es 
    * porque en el index de home se tiene el registro de bitácoras, sin embargo faltaría llenar los campos 
    * "actividades realizadas" y "observaciones" así como la fecha de finalización (por eso lo del "finishing") y el llenado de firmas, como el 
    * index de home tiene elementos html invasivos como el banner de bienvenida, se necesita de un espacio completo 
    * de la ventana del navegador para poder pintar las firmas, por eso es que se optó otro index en el proyecto, 
    * para tener un espacio considerable para firmar comodamente. Se inicializa primero 
    * el renderizado html con ob_start() para evitar impresión en tiempo real de contenido html mientras 
    * se usan headers de php, esto evita el error "headers already sent"

    Se define una cabecera http para manejar caracteres especiales que usamos en el español, algunos de 
    * los valores de campos html de los formularios de esta aplicación ($_POST) se usan para guardarse en variables 
    * o sesiones de métodos estiaticos de la clase Utils que se invocaron antes de iniciar la semantica html, 
    * esto hace que PHP pueda considerar estos simbolos especiales en lugar de reemplazarlos 
    * por sus respectivos valores UNICODE
    header('Content-Type: text/html; charset=utf-8');
    //----------------------------------------------------------------------------------------------------
     Esta sección está dedicada a la importación de dependencias, el archivo vendor/autoload.php contiene la 
    información de todas las clases propias de la aplicación a parte de dependencias externas como 
    (Zebra_pagination, PHP-DI, etc..), params contiene contantes globales que utilizan los controladores y los 
    helpers asi como en las vistas donde se define el head del cuerpo html.
    require_once '../config/params.php';
    require_once '../vendor/autoload.php';
    //---------------------------------------------------------------------------------------------------    
    Hay algunos métodos estáticos de la clase Utils como estos que necesitan ser invocados antes de cualquier 
    * elemento html, para más información de estos métodos estaticos abra el archivo Utils.php de la carpeta helper    
    $container = FinishingContainerFactory::build();
    FinishingUtils::setUtils($container);
    Se importa una vista donde inicia la semantica html, el archivo head.php indica que es HTML5, abre la etiqueta 
    * <html> y la etiqueta <head> donde se coloca estilos css y scripts de JavaScript para la funcionalidad del lado 
    * del cliente, finalmente se abre la etiqueta <body>    
    require_once '../views/finishingLayouts/htmlSides/head.php';
    
    Esta aplicación web funciona con paramentros GET en el url del navegador, hay dos claves en el GET, "controller" el 
    * cual necesita como valor el nombre parcial del controlador, en este caso "error" o "form" y la clave "action" el cual 
    * necesita como valor el nombre de algún método que posee estos controladores, por lo general, no es necesario que el usuario escriba 
    * en la url para acceder a las diferentes vistas de la aplicación ya que la propia aplicación tiene links con los parametros 
    * GET ya definidos para que el usuario pueda navegar sin problemas, pero de todas formas se hace una verificación en dado caso 
    * de que el usuario modifique la url, la primera verificación se evalua si la clave "controller" no esté vacía, luego se 
    * utiliza la función propia de PHP class_exists, como argumento se utiliza la propia clave "controller" para acceder a su 
    * valor y capitalizarla con la función ucfirst ya que las clases de los controladores empiezan con mayuscula, luego se concatena 
    * con el string "Controller", al poner esto en class_exists la función podrá devolver un true si están contempladas por el autoload de 
    * la carpeta vendor, por el contrario class_exists devolverá un 
    * false si se anota en la clave homeController algo diferente del registro del autoloaad o si directamente la clave no tiene un valor    
    if((!empty($_GET["controller"]) && $_GET["controller"] === "followupform") && class_exists(ucfirst($_GET["controller"])."Controller")){
        si entra en el bloque true de este if quiere decir que el indice "controller" solo tiene el controlador valido para este index 
        followupform, por 
         * lo que se guarda el nombre del controlador en cuestión en la variable $controllerName usando la misma técnica usada en 
         * la función class_exists
        $controllerName = ucfirst($_GET["controller"])."Controller";
        Se utiliza la variable $controllerName el cual contiene el nombre del controlador en cuastión para crear una instancia 
         * de esa clase. Se usa $container el cual contiene la instancia de nuestro inyector de dependencia de PHP-DI, el método
         * make es para conseguir un comportamiento tipo "transient" y no "singleton" de la dependencia a obtener.
        $controlador = $container->make($controllerName);
        
        se efectua otro if pero ahora evaluando la clave "action", si esta existe y si su valor (nombre del método en 
         * cuestión) existe en la clase del controlador, si este if da true, entonces se utiliza la clave "action" para guardar 
         * su valor en la variable $action, finalmente se utiliza la instancia del controlador accediendo al método cuyo nombre esta 
         * alojado en $action, si este if da false quiere decir que la clave "action" no tiene un nombre de método valido o 
         * directamente no tiene valor, por lo que se utiliza el método estatico showError que muestra al usuario una vista html con 
         * el texto "LA PÁGINA QUE BUSCAS NO EXISTE"
        if(!empty($_GET["action"]) && method_exists($controlador, $_GET["action"])){
            $action = $_GET["action"];
            $controlador->$action();
        }else{
            Utils::showError($container);
        }
    }else{
        Se necesita una tercer clave "id" del GET para poder acceder a la vista por defecto de este index (la clave "id" lo proporcionan 
         * los elementos link de la vista de seguimiento de bitácoras del index de home followUp.php), por lo tanto, si no se 
         * anota ningun parametro get en la url, entonces la vista "por defecto" de este index será el mensaje "LA PÁGINA QUE BUSCAS NO EXISTE"
        Utils::showError($container);
    }
    
    Finalmente se importa la vista html footer.php para poner fin a la semantica html (se cierra la etiqueta <body> y <html>)
    require_once '../views/finishingLayouts/htmlSides/footer.php';
*/

beforeEach(function(){
    $this->container = testContainerFactory();
    $this->base_url = 'http://localhost:8081/SOSv5/service-order-system/';
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

test('prueba método index, caso bitácora con estatus "en proceso"', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=index';
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

    
    $_GET["id"] = $binnIds[0]["Id"];
    
    $value = mockFollowupformIndex($this->container);

    expect(intval($_SESSION['LAST_ACTIVITY']))->toBeInt();
    expect($value)->toBe('../views/finishingLayouts/remindedfields.php');
});

test('prueba método index, caso bitácora con estatus "falta confirmar"', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=index';
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

    
    $_GET["id"] = $binnIds[3]["Id"];
    
    $value = mockFollowupformIndex($this->container);

    expect(intval($_SESSION['LAST_ACTIVITY']))->toBeInt();
    expect($value)->toBe('../views/finishingLayouts/consentInfo.php');
});

test('prueba método index, caso bitácora no autorizada (cancelada o finalizada)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=index';
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

    
    $_GET["id"] = $binnIds[5]["Id"];
    
    $value = mockFollowupformIndex($this->container);

    expect(intval($_SESSION['LAST_ACTIVITY']))->toBeInt();
    expect(isset($_SESSION["exceptions"]["unauthorizedEx"]))->toBeTrue();
    expect($value)->toBe('Location: '.$this->base_url.'home/');
});

test('prueba método index, caso bitácora inexistente', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=index';
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    
    $_GET["id"] = '9999';
    
    $value = mockFollowupformIndex($this->container);

    expect(intval($_SESSION['LAST_ACTIVITY']))->toBeInt();
    expect(isset($_SESSION["exceptions"]["unKnownEx"]))->toBeTrue();
    expect($value)->toBe('Location: '.$this->base_url.'home/');
});

test('prueba método techsign', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=techsign';
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'user'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }
    
    $_SESSION["formSession"]["dataSelectionForSigns"] = true;
    $value = mockTechsign();

    expect(intval($_SESSION['LAST_ACTIVITY']))->toBeInt();
    expect($value[0])->toBe('../views/finishingLayouts/technicianCanvas.php');
    expect($value[1])->toBe('../views/finishingLayouts/absoluteElems.php');
});

test('prueba método clientsign', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=clientsign';
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'user'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }
    
    $_SESSION["formSession"]["dataSelectionForSigns"]["binnId"] = true;
    $value = mockClientsign();

    expect(intval($_SESSION['LAST_ACTIVITY']))->toBeInt();
    expect($value[0])->toBe('../views/finishingLayouts/clientCanvas.php');
    expect($value[1])->toBe('../views/finishingLayouts/absoluteElems.php');
});

test('prueba método followupPartial, caso satisfactorio', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=followupPartial';
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

    
    $_POST = [
        "id" => $binnIds[0]["Id"],
        "seHizo" => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus repudiandae pariatur perferendis, earum placeat aut inventore. Illo provident dolor obcaecati officiis minus labore, aperiam earum expedita eligendi ab dignissimos ipsum?',
        "observaciones" => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Odio omnis corporis totam reprehenderit quibusdam dolores tempore. Laudantium, ipsa fugiat nam laborum, sit nostrum minima reprehenderit officia magnam obcaecati ad rerum.',
        "binnFecha" => "2026-07-08",
        "estatus" => "falta confirmar"
    ];
    
    $values = mockFollowupPartial($this->container);

    expect([
        "Id" => $binnIds[0]["Id"],
        "Actividades_realizadas" => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus repudiandae pariatur perferendis, earum placeat aut inventore. Illo provident dolor obcaecati officiis minus labore, aperiam earum expedita eligendi ab dignissimos ipsum?',
        "Observaciones" => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Odio omnis corporis totam reprehenderit quibusdam dolores tempore. Laudantium, ipsa fugiat nam laborum, sit nostrum minima reprehenderit officia magnam obcaecati ad rerum.',
        "Inicio" => "2026-07-08",
        "Estatus" => "falta confirmar"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), 'Bitacoras');
    expect($values['result'])->toBe("Location: ".$this->base_url."finishing/?controller=followupform&action=index&id=".$binnIds[0]["Id"]);
});

test('prueba método followupPartial, caso campos invalidos', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=followupPartial';
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    
    $_POST = [
        "id" => '$binnIds[0]["Id"]',
        "seHizo" => '<script></script>',
        "observaciones" => '<script></script>',
        "binnFecha" => "2026/07/08",
        "estatus" => "<script></script>"
    ];
    
    $values = mockFollowupPartial($this->container);

    expect($values['errorArr'])->toHaveLength(5);
    expect($values['result'])->toBe("Location: ".$this->base_url."finishing/?controller=followupform&action=index&id=".'$binnIds[0]["Id"]');
});

test('prueba método resetActivitiesDescriptions, caso satisfactorio', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=resetActivitiesDescriptions';
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

    
    $_GET = [
        "id" => $binnIds[3]["Id"]
    ];
    
    $value = mockResetActivitiesDescriptions($this->container);

    expect([
        "Id" => $binnIds[3]["Id"],
        "Estatus" => "en proceso"
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), 'Bitacoras');
    expect($value)->toBe("Location: ".$this->base_url."finishing/?controller=followupform&action=index&id=".$binnIds[3]["Id"]);
});

test('prueba método resetActivitiesDescriptions, caso campo Id invalido', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=resetActivitiesDescriptions';
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    $_GET = [
        "id" => '$binnIds[3]["Id"]'
    ];
    
    $value = mockResetActivitiesDescriptions($this->container);

    expect(isset($_SESSION["exceptions"]["entitiesEx"]))->toBeTrue();
    expect($value)->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=followuplist");
});

test('prueba método resetActivitiesDescriptions, caso bitácora no autorizada (cancelados o finalizados)', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=resetActivitiesDescriptions';
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

    
    $_GET = [
        "id" => $binnIds[5]["Id"]
    ];
    
    $value = mockResetActivitiesDescriptions($this->container);

    expect(isset($_SESSION["exceptions"]["unauthorizedEx"]))->toBeTrue();
    expect($value)->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=followuplist");
});

test('prueba método cancellingBinn, caso satisfactorio', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=cancellingBinn';
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

    
    $_POST = [
        "cancelwithid" => $binnIds[0]["Id"],
        "cancelestatus" => "cancelado",
        "cancelacion" => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. A, alias voluptatibus quisquam consectetur reiciendis magni repellendus, libero in, nesciunt esse quas? Nostrum vel tempora dolorum error harum non voluptatum temporibus!'
    ];
    
    $values = mockCancellingBinn($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Id" => $binnIds[0]["Id"],
        "Estatus" => "cancelado",
        "Observaciones" => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. A, alias voluptatibus quisquam consectetur reiciendis magni repellendus, libero in, nesciunt esse quas? Nostrum vel tempora dolorum error harum non voluptatum temporibus!'
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), 'Bitacoras');
    expect($values['result'])->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=followuplist");
});

test('prueba método cancellingBinn, caso campos invalido', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=cancellingBinn';
    try{
        $usrService = $this->container->make('usrService');
        $usrService->insertInfo(mockUserDTO($this->container->make('usrDTO'), 'admin'));
        $_SESSION["identity"] = setIdentitySession($this->container->make('usrDTO'), $this->container->make('usrParticularSrv'));
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    
    $_POST = [
        "cancelwithid" => '$binnIds[0]["Id"]',
        "cancelestatus" => "<script></script>",
        "cancelacion" => '<script></script>'
    ];
    
    $values = mockCancellingBinn($this->container);

    expect($values['errorArr'])->toHaveLength(3);
    expect($values['result'])->toBe("Location: ".$this->base_url."finishing/?controller=followupform&action=index&id=".'$binnIds[0]["Id"]');
});

test('prueba método finishbinnacle', function(){
    $_SERVER['REQUEST_URI'] = 'http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=finishbinnacle';
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

    
    $_SESSION["formSession"]["dataSelectionForSigns"]["binnId"] = $binnIds[3]["Id"];
    $_SESSION["formSession"]["clientSignature"] = "client_sign_test.png";
    
    $value = mockFinishbinnacle($this->container);

    expect(isset($_SESSION["success"]))->toBeTrue();
    expect([
        "Id" => $binnIds[3]["Id"],
        "Estatus" => 'finalizado',
        "Firma_cliente" => 'client_sign_test.png',
        "Fin" => date('Y-m-d')
    ])->toBeInDatabase($this->container->make('SOSTestDatabase'), 'Bitacoras');
    expect($value)->toBe("Location: ".$this->base_url."home/?homeController=binnacle&homeAction=followuplist");
});