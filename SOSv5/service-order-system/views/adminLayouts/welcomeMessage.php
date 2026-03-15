<!-- este html es la vista del mensaje de bienvenida del administrador; las vistas de contenido como este se utiliza la etiqueta <main>, 
si se quiere crear otras vistas es recomendable poner esta etiqueta como el padre de los elementos html, también se utiliza etiquetas PHP evaluando 
sesiones y variables inicializadas por los controladores en sus métodos de vistas, esto con el fin de determinar qué elementos html mostrar al usuario -->
<main class="welcome-message-main">
    
    <!-- Los controladores inicializan sesiones con strings que necesitan ser mostradas al usuario, se utilizan etiquetas PHP para evaluar 
    si existen estas sesiones, si existen, entonces se utiliza los valores de estas sesiones con un elemento html el cual muestra al usuario 
    el mensaje en forma de flags -->
    <?php if(!empty($_SESSION["identitySessionUpdateEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["identitySessionUpdateEx"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["remindedOrConsentReportEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["remindedOrConsentReportEx"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["followUpQueryEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["followUpQueryEx"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["followUpNumRowsEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["followUpNumRowsEx"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["paginationArrException"])):?>
    <div class="invalidinput-box"><?=$_SESSION["paginationArrException"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["clientArrayException"])):?>
    <div class="invalidinput-box"><?=$_SESSION["clientArrayException"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["enterpriseArrayException"])):?>
    <div class="invalidinput-box"><?=$_SESSION["enterpriseArrayException"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["deviceArrayException"])):?>
    <div class="invalidinput-box"><?=$_SESSION["deviceArrayException"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["typeArrayException"])):?>
    <div class="invalidinput-box"><?=$_SESSION["typeArrayException"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["unlinkTechSignEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["unlinkTechSignEx"]?></div>
    <?php endif; ?>
    
    
    <div class="welcome-message__message-box">
        <div class="message-box__img-box"><img src="<?= base_url;?>assets/img/logo.png" style="width: 90px;"/></div>
        <div class="message-box__title-box"><h2>Buen día estimado administrador <?=$_SESSION["identity"]["Nombre"]." ".$_SESSION["identity"]["Apellidos"]?></h2></div>
        <div class="message-box__message-box">
            Bienvenido al home del lado del administrador. Como podrá ver, su menú de navegación se encuentra en el lado izquierdo,
            sí minimiza la ventana del navegador o se encuentra usando el navegador de su móvil, el menú de navegación se activa presionando el
            botón situado al lado derecho del logo de interpc en la parte de arriba de la aplicación web. Tiene a su disposición todas las opciones 
            disponibles de los técnicos, lo cual permite la creación y seguimiento de bitácoras, también tiene a su disposición opciones de 
            configuración de usuarios y apartados para generar reportes pdf de dispositivos de una empresa en especifico o reportes de bitácoras. 
            Ningún registro en esta aplicación web se puede eliminar, en opciones de edición de registros (y en reportes de bitácoras) se puede 
            "desactivar/activar" registros, en el caso de registros como empresas, contactos, tipos y equipos, sí se desactivan, no serán visibles en 
            las cajas de selección de los formularios "Nueva bitácora", "Crear contacto" y "Crear un equipo" o tambien en reportes de bitácoras (cajas de selección de filtrado), el administrador puede revertir la 
            visibilidad de estos registros. Los registros de usuarios por ejemplo, su desactivación es definitiva, la única forma de volver a activar 
            un usuario es via gestor de bases de datos con la autorización del personal encargado de administrar las bases de datos en la empresa. 
        </div>
    </div>
    <!-- generalmente, las vistas que muestran mensajes flags tienen una etiqueta PHP donde se utiliza el método estático unsetFlagsSessions el cual elimina las 
sesiones de mensajes de errores, excepciones y de exito en un proceso -->
    <?php Utils::unsetFlagsSessions();?>
    <!-- Este tipo de vista se usa tambien como comodín para poder eliminar las sesiones generadas por otros procesos, para más información sobre estos métodos estaticos, 
consulta el archivo Utils.php-->
    <?php Utils::unsetFormSessions();?>
    <?php Utils::unsetBinnFilterSessions();?>
    <?php Utils::unsetIdSessionsOfSearchForms();?>
</main>