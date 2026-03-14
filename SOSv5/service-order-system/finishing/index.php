<?php
ob_start();
/*El index de la carpeta finishing es el "html" del formulario extendido de una bitácora, html entre comillas ya 
 * que se utiliza PHP para importar dependencias como los controladores y archivos html que comienzan 
 * y finalizan la semantica html gracias a require_once, los elementos html que están "en el medio" los 
 * gestionan los controladores dentro de la estructura if de este documento, y en cuanto a "formulario extendido" de una bitácora, es 
 * porque en el index de home se tiene el registro de bitácoras, sin embargo faltaría llenar los campos 
 * "actividades realizadas" y "observaciones" así como la fecha de finalización (por eso lo del "finishing") y el llenado de firmas, como el 
 * index de home tiene elementos html invasivos como el banner de bienvenida, se necesita de un espacio completo 
 * de la ventana del navegador para poder pintar las firmas, por eso es que se optó otro index en el proyecto, 
 * para tener un espacio considerable para firmar comodamente. Se inicializa primero 
 * el renderizado html con ob_start() para evitar impresión en tiempo real de contenido html mientras 
 * se usan headers de php, esto evita el error "headers already sent"*/

/*Se define una cabecera http para manejar caracteres especiales que usamos en el español, algunos de 
 * los valores de campos html de los formularios de esta aplicación ($_POST) se usan para guardarse en variables 
 * o sesiones de métodos estiaticos de la clase Utils que se invocaron antes de iniciar la semantica html, 
 * esto hace que PHP pueda considerar estos simbolos especiales en lugar de reemplazarlos 
 * por sus respectivos valores UNICODE*/
header('Content-Type: text/html; charset=utf-8');
//----------------------------------------------------------------------------------------------------
/*Esta sección está dedicada a la importación de dependencias, todas son clases pertenecientes a este proyecto, 
 * nuestro autoload principal puede importar cualquier clase de la carpeta models (la carpeta con más 
 * clases en este proyecto), clases que representan las tablas de la base de datos de esta aplicación web.*/    
    require_once '../config/DataBaseMssql.php';
    require_once '../autoload.php';
    require_once '../config/params.php';
    require_once '../helper/Utils.php';
    //es aqui donde tambien se importan los controladores principales de este index para gestionar 
    //las acciones del usuario en el formulario extendido de una bitácora
    require_once '../controllers/ErrorController.php';
    require_once '../controllers/FormController.php';
//---------------------------------------------------------------------------------------------------    
/*Hay algunos métodos estáticos de la clase Utils como estos que necesitan ser invocados antes de cualquier 
 * elemento html, para más información de estos métodos estaticos abra el archivo Utils.php de la carpeta helper*/    
    Utils::putSessionWithVerify();
    Utils::sessionLifetime();
    Utils::saveSignaturesFiles();
    Utils::updateUserWithSignature();
    Utils::setDataSelectionForSigns();

/*Se importa una vista donde inicia la semantica html, el archivo head.php indica que es HTML5, abre la etiqueta 
 * <html> y la etiqueta <head> donde se coloca estilos css y scripts de JavaScript para la funcionalidad del lado 
 * del cliente, finalmente se abre la etiqueta <body>*/    
    require_once '../views/finishingLayouts/htmlSides/head.php';
    
/*Esta aplicación web funciona con paramentros GET en el url del navegador, hay dos claves en el GET, "controller" el 
 * cual necesita como valor el nombre parcial del controlador, en este caso "error" o "form" y la clave "action" el cual 
 * necesita como valor el nombre de algún método que posee estos controladores, por lo general, no es necesario que el usuario escriba 
 * en la url para acceder a las diferentes vistas de la aplicación ya que la propia aplicación tiene links con los parametros 
 * GET ya definidos para que el usuario pueda navegar sin problemas, pero de todas formas se hace una verificación en dado caso 
 * de que el usuario modifique la url, la primera verificación se evalua si la clave "controller" no esté vacía, luego se 
 * utiliza la función propia de PHP class_exists, como argumento se utiliza la propia clave "controller" para acceder a su 
 * valor y capitalizarla con la función ucfirst ya que las clases de los controladores empiezan con mayuscula, luego se concatena 
 * con el string "Controller", al poner esto en class_exists la función podrá devolver un true si están estas posibilidades: 
 * (ErrorController o FormController) ya que estas clases si están en este archivo, por el contrario class_exists devolverá un 
 * false si se anota en la clave "controller" algo diferente de "error" o "form" o si directamente la clave no tiene un valor*/    
    if(!empty($_GET["controller"]) && class_exists(ucfirst($_GET["controller"])."Controller")){
        /*si entra en el bloque true de este if quiere decir que el indice "controller" tiene los valores "error" o "form", por 
         * lo que se guarda el nombre del controlador en cuestión en la variable $controllerName usando la misma técnica usada en 
         * la función class_exists*/
        $controllerName = ucfirst($_GET["controller"])."Controller";
        /*Se utiliza la variable $controllerName el cual contiene el nombre del controlador en cuastión para crear una instancia 
         * de esa clase*/
        $controlador = new $controllerName();
        
        /*se efectua otro if pero ahora evaluando la clave "action", si esta existe y si su valor (nombre del método en 
         * cuestión) existe en la clase del controlador, si este if da true, entonces se utiliza la clave "action" para guardar 
         * su valor en la variable $action, finalmente se utiliza la instancia del controlador accediendo al método cuyo nombre esta 
         * alojado en $action, si este if da false quiere decir que la clave "action" no tiene un nombre de método valido o 
         * directamente no tiene valor, por lo que se utiliza el método estatico showError que muestra al usuario una vista html con 
         * el texto "LA PÁGINA QUE BUSCAS NO EXISTE"*/
        if(!empty($_GET["action"]) && method_exists($controlador, $_GET["action"])){
            $action = $_GET["action"];
            $controlador->$action();
        }else{
            Utils::showError();
        }
    }else{
        /*Se necesita una tercer clave "id" del GET para poder acceder a la vista por defecto de este index (la clave "id" lo proporcionan 
         * los elementos link de la vista de seguimiento de bitácoras del index de home followUp.php), por lo tanto, si no se 
         * anota ningun parametro get en la url, entonces la vista "por defecto" de este index será el mensaje "LA PÁGINA QUE BUSCAS NO EXISTE"*/
        Utils::showError();
    }
    
    /*Finalmente se importa la vista html footer.php para poner fin a la semantica html (se cierra la etiqueta <body> y <html>)*/
    require_once '../views/finishingLayouts/htmlSides/footer.php';