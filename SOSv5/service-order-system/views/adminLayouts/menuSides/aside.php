<!-- la barra lateral se coloca justo debajo de cualquier etiqueta <main>, lo primero que se hace es cerrar el div donde contiene los <main> luego se abre y cierra el 
div de la barra lateral y su contenido finalmente se cierra el div con la clase "adminViews-wrapper" -->
</div>

<div class="adminMenu-wrapper" id="adminMenuWrapper">
    <aside class="adminMenu-leftAside">
        <div class="leftAside__linkList-wrapper">
            <nav class="linkList-wrapper__nav">
            <ul class="linkList-wrapper__linkList">
                <li class="linkList__row"><a class="linkList__row-link" href="<?= base_url;?>home/?homeController=user&homeAction=newbinnacle">Nueva Bitácora</a></li>
                <li class="linkList__row"><a class="linkList__row-link" href="<?= base_url;?>home/?homeController=user&homeAction=followuplist">Seguimiento de Bitácoras</a>
                <li class="linkList__row">
                    <a class="linkList__row-link" href="#">Crear registros <div class="row-link__icon-wrapper"><i class="fa-solid fa-chevron-down"></i></div></a>
                    <ul class="linkList__submenu-links">
                        <li class="submenu-links__row"><a class="submenu-links__link" href="<?= base_url;?>home/?homeController=user&homeAction=newcontact">Crear contacto</a></li>
                        <li class="submenu-links__row"><a class="submenu-links__link" href="<?= base_url;?>home/?homeController=user&homeAction=newdevicetype">Crear tipo de equipo</a></li>
                        <li class="submenu-links__row"><a class="submenu-links__link" href="<?= base_url;?>home/?homeController=user&homeAction=newdevice">Crear un equipo</a></li>    
                    </ul>
                </li>
                <li class="linkList__row">
                    <a class="linkList__row-link" href="#">Editar registros <div class="row-link__icon-wrapper"><i class="fa-solid fa-chevron-down"></i></div></a>
                    <ul class="linkList__submenu-links">
                        <li class="submenu-links__row"><a class="submenu-links__link" href="<?= base_url;?>home/?homeController=user&homeAction=editEnterprise">Editar empresa y sus contactos</a></li>
                        <li class="submenu-links__row"><a class="submenu-links__link" href="<?= base_url;?>home/?homeController=user&homeAction=editTypes">Editar tipos de equipo</a></li>
                        <li class="submenu-links__row"><a class="submenu-links__link" href="<?= base_url;?>home/?homeController=user&homeAction=editDevice">Editar equipos de una empresa</a></li>    
                    </ul>
                </li>
                <li class="linkList__row">
                    <a class="linkList__row-link" href="#">Configuración de Usuarios <div class="row-link__icon-wrapper"><i class="fa-solid fa-chevron-down"></i></div></a>
                    <ul class="linkList__submenu-links">
                        <li class="submenu-links__row"><a class="submenu-links__link" href="<?= base_url;?>home/?homeController=user&homeAction=createUser">Crear Usuario</a></li>
                        <li class="submenu-links__row"><a class="submenu-links__link" href="<?= base_url;?>home/?homeController=user&homeAction=userNewPassword">Reestablecer contraseñas</a></li>
                        <li class="submenu-links__row"><a class="submenu-links__link" href="<?= base_url;?>home/?homeController=user&homeAction=editSign">Editar firmas</a></li>    
                    </ul>
                </li>
                <li class="linkList__row"><a class="linkList__row-link" href="<?= base_url;?>home/?homeController=user&homeAction=devicesReport">Reporte de Dispositivos</a></li>
                <li class="linkList__row"><a class="linkList__row-link" href="<?= base_url;?>home/?homeController=user&homeAction=binnaclesReport">Reportes de Bitácoras</a></li>
            </ul>
            </nav>
        </div>
        
        <div class="leftAside__logoutLink-wrapper">
            <a class="logoutLink-wrapper__link" href="<?= base_url;?>home/?homeController=user&homeAction=logout">Salir de la aplicación</a>
        </div>
    </aside>
</div>
</div>