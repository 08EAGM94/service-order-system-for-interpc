<!--Este archivo a parte de concluir la semantica html, antes de eso hay un if donde se añade el html del menú del administrador para móbiles, este menú debe de 
ir despues de cualquier vista generada por controladores, si no se hace, PHP arroja el warning: Cannot modify header information - headers already sent-->
<?php if(!empty($_SESSION["isAdmin"])): ?>
    <div class="mobile-navbar" id="mobileNavBar">
    <header class="mobile-navbar__header">
        <div class="mobile-navbar__enterprise-logo">
            <img src="<?= base_url;?>assets/img/logo_interpc_letras_blancas-1.png"/>
        </div>
        
        <button class="mobile-navbar__close-button" type="button" id="mobileCancelBtn">X</button>
    </header>
    
    <div class="mobile-navbar__body">
        <nav class="mobile-navbar__nav">
            <ul class="mobile-navbar__linkList">
                <li class="mobile-navbar__row"><a class="mobile-navbar__row-link" href="<?= base_url;?>home/?homeController=user&homeAction=newbinnacle">Nueva Bitácora</a></li>
                <li class="mobile-navbar__row"><a class="mobile-navbar__row-link" href="<?= base_url;?>home/?homeController=user&homeAction=followuplist">Seguimiento de Bitácoras</a></li>
                <li class="mobile-navbar__row">
                    <a class="mobile-navbar__row-link" href="#">Crear registros <div class="mobile-navbar__icon-wrapper"><i class="fa-solid fa-chevron-down" style="top: 1.5rem;"></i></div></a>
                    <ul class="mobile-submenu__linkList">
                        <li class="mobile-submenu__row"><a class="mobile-submenu__link" href="<?= base_url;?>home/?homeController=user&homeAction=newcontact">Crear contacto</a></li>
                        <li class="mobile-submenu__row"><a class="mobile-submenu__link" href="<?= base_url;?>home/?homeController=user&homeAction=newdevicetype">Crear tipo de equipo</a></li>
                        <li class="mobile-submenu__row"><a class="mobile-submenu__link" href="<?= base_url;?>home/?homeController=user&homeAction=newdevice">Crear un equipo</a></li>    
                    </ul>
                </li>
                <li class="mobile-navbar__row">
                    <a class="mobile-navbar__row-link" href="#">Editar registros <div class="mobile-navbar__icon-wrapper"><i class="fa-solid fa-chevron-down" style="top: 1.5rem;"></i></div></a>
                    <ul class="mobile-submenu__linkList">
                        <li class="mobile-submenu__row"><a class="mobile-submenu__link" href="<?= base_url;?>home/?homeController=user&homeAction=editEnterprise">Editar empresa y sus contactos</a></li>
                        <li class="mobile-submenu__row"><a class="mobile-submenu__link" href="<?= base_url;?>home/?homeController=user&homeAction=editTypes">Editar tipos de equipo</a></li>
                        <li class="mobile-submenu__row"><a class="mobile-submenu__link" href="<?= base_url;?>home/?homeController=user&homeAction=editDevice">Editar equipos de una empresa</a></li>    
                    </ul>
                </li>
                <li class="mobile-navbar__row">
                    <a class="mobile-navbar__row-link" href="#" style="padding-right: 1rem;">Configuración de Usuarios <div class="mobile-navbar__icon-wrapper"><i class="fa-solid fa-chevron-down" style="top: 1.5rem;"></i></div></a>
                    <ul class="mobile-submenu__linkList">
                        <li class="mobile-submenu__row"><a class="mobile-submenu__link" href="<?= base_url;?>home/?homeController=user&homeAction=createUser">Crear Usuario</a></li>
                        <li class="mobile-submenu__row"><a class="mobile-submenu__link" href="<?= base_url;?>home/?homeController=user&homeAction=userNewPassword">Reestablecer contraseñas</a></li>
                        <li class="mobile-submenu__row"><a class="mobile-submenu__link" href="<?= base_url;?>home/?homeController=user&homeAction=editSign">Editar firmas</a></li>    
                    </ul>
                </li>
                <li class="mobile-navbar__row"><a class="mobile-navbar__row-link" href="<?= base_url;?>home/?homeController=user&homeAction=devicesReport">Reportes de Dispositivos</a></li>
                <li class="mobile-navbar__row"><a class="mobile-navbar__row-link" href="<?= base_url;?>home/?homeController=user&homeAction=binnaclesReport">Reportes de Bitácoras</a></li>
                <li class="mobile-navbar__row"><a class="mobile-navbar__row-link-logout" href="<?= base_url;?>home/?homeController=user&homeAction=logout">Salir de la aplicación</a></li>
            </ul>
        </nav>
    </div>
    </div>
<?php endif;?>
<?php if(!empty($_SESSION["identity"])):?>
    <?php if($_SESSION["identity"]["Privilegio"] === "user"):?>
    </div>
    <?php endif;?>
<?php endif;?>
</body>
</html>