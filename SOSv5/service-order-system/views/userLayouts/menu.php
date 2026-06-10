<!-- este html es la vista de menú de usuarios (técnicos); las vistas de contenido como este se utiliza la etiqueta <main>, si se quiere crear otras vistas es 
recomendable poner esta etiqueta como el padre de los elementos html, también se utiliza etiquetas PHP evaluando sesiones y variables inicializadas 
por los controladores en sus métodos de vistas, esto con el fin de determinar qué elementos html mostrar al usuario -->
<main class="menu-main">
    
    <!-- Los controladores inicializan sesiones con strings que necesitan ser mostradas al usuario, se utilizan etiquetas PHP para evaluar 
    si existen estas sesiones, si existen, entonces se utiliza los valores de estas sesiones con un elemento html el cual muestra al usuario 
    el mensaje en forma de flags -->
    <?php if(!empty($_SESSION["remindedOrConsentReportEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["remindedOrConsentReportEx"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["followUpQueryEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["followUpQueryEx"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["followUpNumRowsEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["followUpNumRowsEx"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["identitySessionUpdateEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["identitySessionUpdateEx"]?></div>
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
    
    <a class="menu-main__binnacle-link" href="<?=base_url;?>home/?homeController=user&homeAction=newbinnacle">
        <div class="binnacle-link__img"><img src="<?= base_url; ?>assets/img/clipboard_2921124.png"/></div>
        <h2 class="binnacle-link__subtitle">Nueva bitacora</h2>
    </a>
    
    <a class="menu-main__new-contact-link" href="<?= base_url; ?>home/?homeController=user&homeAction=newcontact">
        <div class="new-contact-link__img"><img src="<?= base_url; ?>assets/img/contact-book_12370535.png"/></div>
        <h2 class="new-contact-link__subtitle">Crear contacto</h2>
    </a>
    
    <a class="menu-main__new-type-link" href="<?= base_url; ?>home/?homeController=user&homeAction=newdevicetype">
        <div class="new-type-link__img"><img src="<?= base_url; ?>assets/img/shapes_16867372.png"/></div>
        <h2 class="new-type-link__subtitle">Crear tipo de equipo</h2>
    </a>
    
    <a class="menu-main__new-device-link" href="<?= base_url; ?>home/?homeController=user&homeAction=newdevice">
        <div class="new-device-link__img"><img src="<?= base_url; ?>assets/img/web_16029055.png"/></div>
        <h2 class="new-device-link__subtitle">Crear un equipo</h2>
    </a>

    <a class="menu-main__following-link" href="<?= base_url; ?>home/?homeController=user&homeAction=followuplist">
        <div class="following-link__img"><img src="<?= base_url; ?>assets/img/performance-appraisal_12773568.png"/></div>
        <h2 class="following-link__subtitle">Seguimiento de bitacora</h2>
    </a>
    
    <a class="menu-main__edit-Sign-link" href="<?= base_url; ?>home/?homeController=user&homeAction=editSign">
        <div class="edit-Sign-link__img"><img src="<?= base_url; ?>assets/img/cheque_6540342.png"/></div>
        <h2 class="edit-Sign-link__subtitle">Editar Firma</h2>
    </a>
    
    <a class="menu-main__exit-link" href="<?= base_url; ?>home/?homeController=user&homeAction=logout">
        <div class="exit-link__img"><img src="<?= base_url; ?>assets/img/exit_5392330.png"/></div>
        <h2 class="exit-link__subtitle">Salir de la aplicación</h2>
    </a>
</main>
<!-- generalmente, las vistas que muestran mensajes flags tienen una etiqueta PHP donde se utiliza el método estático unsetFlagsSessions el cual elimina las 
sesiones de mensajes de errores, excepciones y de exito en un proceso -->
<?php Utils::unsetFlagsSessions();?>
<!-- Este tipo de vista se usa tambien como comodín para poder eliminar las sesiones generadas por otros procesos, para más información sobre estos métodos estaticos, 
consulta el archivo Utils.php-->
<?php Utils::unsetJsonDecodedSession();?>
<?php Utils::unsetFormSessions();