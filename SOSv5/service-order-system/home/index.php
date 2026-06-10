<?php
/*en este caso particular, en este index se genera archivos pdf con el método estático de nuestra clase Utils llamado
 * reportPdfGenerator(), dentro de la estructura de control de ese método se evalua los parametros get "generateBinnacleReport"
 * o generateDevicesReport, si es que estos valores existen en el parametro get homeAction se importa una vista html para 
 * que la dependencia domPdf pueda procesarla, en el inició de ese html se guarda el renderizado html con ob_start, para 
 * evitar el duplicado de esa función por eso se creó esta estructura de control al momento de llamar la función ob_start()*/
if(!empty($_GET["homeAction"])){
    if($_GET["homeAction"] !== "generateBinnacleReport" || $_GET["homeAction"] !== "generateDevicesReport"){
        ob_start();
    }
}else{
    ob_start();
}
/*El index de la carpeta home es el "html" principal de esta aplicación web, html entre comillas ya 
 * que se utiliza PHP para importar dependencias como los controladores y archivos html que comienzan 
 * y finalizan la semantica html gracias a require_once, los elementos html que están "en el medio" los 
 * gestionan los controladores dentro de la estructura if de este documento. Se inicializa primero 
 * el renderizado html con ob_start() para evitar impresión en tiempo real de contenido html mientras 
 * se usan headers de php, esto evita el error "headers already sent"*/


/*Se define una cabecera http para manejar caracteres especiales que usamos en el español, algunos de 
 * los valores de campos html de los formularios de esta aplicación ($_POST) se usan para guardarse en variables 
 * o sesiones de métodos estiaticos de la clase Utils que se invocaron antes de iniciar la semantica html, 
 * esto hace que PHP pueda considerar estos simbolos especiales en lugar de reemplazarlos 
 * por sus respectivos valores UNICODE*/
header('Content-Type: text/html; charset=utf-8');
//----------------------------------------------------------------------------------------------------
/*Esta sección está dedicada a la importación de dependencias, el archivo vendor/autoload.php es el 
 * unico autoload que importa dependencias "externas" (zebraPagination y domPDF), lo demás son clases pertenecientes a este proyecto, 
 * nuestro autoload principal puede importar cualquier clase de la carpeta models (la carpeta con más 
 * clases en este proyecto), clases que representan las tablas de la base de datos de esta aplicación web.*/
    require_once '../config/DataBaseMssql.php';
    require_once '../autoload.php';
    require_once '../vendor/autoload.php';
    require_once '../config/params.php';
    require_once '../helper/Utils.php';
    //es aqui donde tambien se importan los controladores principales de este index para gestionar 
    //las acciones del usuario en la aplicación web
    require_once '../controllers/ErrorController.php';
    require_once '../controllers/UserController.php';
//---------------------------------------------------------------------------------------------------    
/*Hay algunos métodos estáticos de la clase Utils como estos que necesitan ser invocados antes de cualquier 
 * elemento html, para más información de estos métodos estaticos abra el archivo Utils.php de la carpeta helper*/    
    Utils::putSessionWithVerify();
    Utils::sessionLifetime();
    Utils::reportPdfGenerator();
    Utils::ajaxProcedure();
    
/*Se importa una vista donde inicia la semantica html, el archivo head.php indica que es HTML5, abre la etiqueta 
 * <html> y la etiqueta <head> donde se coloca estilos css y scripts de JavaScript para la funcionalidad del lado 
 * del cliente, finalmente se abre la etiqueta <body>*/
    require_once '../views/userLayouts/menuSides/head.php';
//este método estatico sigue la semantica html agregando un elemento <header> al body, en este caso un banner de bienvenida
     Utils::generateWelcomeBanner();

/*Esta aplicación web funciona con paramentros GET en el url del navegador, hay dos claves en el GET, "homeController" el 
 * cual necesita como valor el nombre parcial del controlador, en este caso "error" o "user" y la clave "homeAction" el cual 
 * necesita como valor el nombre de algún método que posee estos controladores, por lo general, no es necesario que el usuario escriba 
 * en la url para acceder a las diferentes vistas de la aplicación ya que la propia aplicación tiene links con los parametros 
 * GET ya definidos para que el usuario pueda navegar sin problemas, pero de todas formas se hace una verificación en dado caso 
 * de que el usuario modifique la url, la primera verificación se evalua si la clave "homeController" no esté vacía, luego se 
 * utiliza la función propia de PHP class_exists, como argumento se utiliza la propia clave "homeController" para acceder a su 
 * valor y capitalizarla con la función ucfirst ya que las clases de los controladores empiezan con mayuscula, luego se concatena 
 * con el string "Controller", al poner esto en class_exists la función podrá devolver un true si están estas posibilidades: 
 * (ErrorController o UserController) ya que estas clases si están en este archivo, por el contrario class_exists devolverá un 
 * false si se anota en la clave homeController algo diferente de "error" o "user" o si directamente la clave no tiene un valor*/     
    if (!empty($_GET["homeController"]) && class_exists(ucfirst($_GET["homeController"]) . "Controller")) {
        /*si entra en el bloque true de este if quiere decir que el indice "homeController" tiene los valores "error" o "user", por 
         * lo que se guarda el nombre del controlador en cuestión en la variable $controllerName usando la misma técnica usada en 
         * la función class_exists*/
        $controllerName = ucfirst($_GET["homeController"]) . "Controller";
        /*Se utiliza la variable $controllerName el cual contiene el nombre del controlador en cuastión para crear una instancia 
         * de esa clase*/
        $controlador = new $controllerName();
        
        /*se efectua otro if pero ahora evaluando la clave "homeAction", si esta existe y si su valor (nombre del método en 
         * cuestión) existe en la clase del controlador, si este if da true, entonces se utiliza la clave "homeAction" para guardar 
         * su valor en la variable $action, finalmente se utiliza la instancia del controlador accediendo al método cuyo nombre esta 
         * alojado en $action, si este if da false quiere decir que la clave "homeAction" no tiene un nombre de método valido o 
         * directamente no tiene valor, por lo que se utiliza el método estatico showError que muestra al usuario una vista html con 
         * el texto "LA PÁGINA QUE BUSCAS NO EXISTE"*/
        if (!empty($_GET["homeAction"]) && method_exists($controlador, $_GET["homeAction"])) {
            $action = $_GET["homeAction"];
            
            $controlador->$action();
            
        } else {
            Utils::showError();
        }
    } else {
        /*si se anota en la clave "homeController" algo diferente a "error" o "user" o si la clave no tiene un valor entonces 
         * entra en el bloque false, en el se utiliza el método estatico defaultUserPage para cargar la vista por defecto de 
         * este index,*/
        Utils::defaultUserPage();
    }
    
    /*en esta zona se genera el html de navegación, el método estático setAsideWithVerify genera el menú lateral del administrador y en 
     * el archivo footer.php genera el menú de navegación para móviles si existe la sesión "isAdmin", es necesario que estos controles de 
     * navegación estén despues de la llamada de algún controlador, sino se hace esto, PHP enviará el warning: Cannot modify header 
     * information - headers already sent y no dejará ver las vistas que gestionan los controladores*/
    Utils::setAsideWithVerify();
    require_once '../views/userLayouts/menuSides/footer.php';
    