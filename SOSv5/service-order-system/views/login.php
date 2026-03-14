<!-- este html es la vista del login; las vistas de contenido como este se utiliza la etiqueta <main>, si se quiere crear otras vistas es 
recomendable poner esta etiqueta como el padre de los elementos html, también se utiliza etiquetas PHP evaluando sesiones y variables inicializadas 
por los controladores en sus métodos de vistas, esto con el fin de determinar qué elementos html mostrar al usuario -->
<main class="user-login">
    <div class="user-login__logobox-wrapper">
        <div class="user-login__logo-box"><img src="<?=base_url;?>assets/img/logo.png"/></div>
    </div>
    <!-- Los controaldores inicializan sesiones con strings que necesitan ser mostradas al usuario, se utilizan etiquetas PHP para evaluar 
    si existen estas sesiones, si existen, entonces se utiliza los valores de estas sesiones con un elemento html el cual muestra al usuario 
    el mensaje en forma de flags -->
    <?php if(!empty($_SESSION["loginErrors"]) && !empty($_SESSION["loginErrors"]["logFailed"])): ?>
        <div class="invalidinput-box"><?=$_SESSION["loginErrors"]["logFailed"];?></div>
    <?php endif; ?>
    <div class="user-login__title-box">
        <h2>Sistema de Ordenes de Servicio</h2>
    </div>
    <form class="user-login__form" action="<?= base_url; ?>home/?homeController=user&homeAction=login" method="POST">
        <?php if(!empty($_SESSION["loginErrors"]) && !empty($_SESSION["loginErrors"]["nombre"])): ?>
        <div class="invalidinput-box"><?=$_SESSION["loginErrors"]["nombre"];?></div>
        <?php endif; ?>
        <label for="user">Usuario</label>
        <input type="text" name="user" id="user"/>
        
        <?php if(!empty($_SESSION["loginErrors"]) && !empty($_SESSION["loginErrors"]["contrasena"])): ?>
        <div class="invalidinput-box"><?=$_SESSION["loginErrors"]["contrasena"];?></div>
        <?php endif; ?>
        <label for="pwd">Contraseña</label>
        <div class="user-login__pwd-box">
            <input class="user-login__pwd" type="password" name="pwd" id="pwd"/>
            <div id="icon">
                <i class="fa-solid fa-eye-slash"></i>
            </div>
        </div>
        <div class="user-login__submit-box">
            <input class="user-login__submit" type="submit" value="Login"/>
        </div>
    </form>
</main>
<div class="particles" id="particles-js"></div>
<!-- generalmente, las vistas que muestran mensajes flags tienen una etiqueta PHP donde se utiliza el método estático unsetFlagsSessions el cual elimina las 
sesiones de mensajes de errores, excepciones y de exito en un proceso -->
<?php Utils::unsetFlagsSessions();?>