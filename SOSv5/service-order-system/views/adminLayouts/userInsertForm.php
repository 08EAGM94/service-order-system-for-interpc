<!-- este html es la vista del formulario de creación de un usuario; las vistas de contenido como este se utiliza la etiqueta <main>, si se quiere crear otras vistas es 
recomendable poner esta etiqueta como el padre de los elementos html, también se utiliza etiquetas PHP evaluando sesiones y variables inicializadas 
por los controladores en sus métodos de vistas, esto con el fin de determinar qué elementos html mostrar al usuario -->
<main class="userform-main">
    
    <!-- Los controladores inicializan sesiones con strings que necesitan ser mostradas al usuario, se utilizan etiquetas PHP para evaluar 
    si existen estas sesiones, si existen, entonces se utiliza los valores de estas sesiones con un elemento html el cual muestra al usuario 
    el mensaje en forma de flags -->
    <?php if (!empty($_SESSION["userDataSucceded"])): ?>
        <div class="succeed-box"><?= $_SESSION["userDataSucceded"]; ?></div>
    <?php endif; ?>
        
    <?php if (!empty($_SESSION["userDataException"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["userDataException"]; ?></div>
    <?php endif; ?>    

    <?php if (!empty($_SESSION["userDataErr"]) && !empty($_SESSION["userDataErr"]["nombre"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["userDataErr"]["nombre"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["userDataErr"]) && !empty($_SESSION["userDataErr"]["apellidos"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["userDataErr"]["apellidos"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["userDataErr"]) && !empty($_SESSION["userDataErr"]["alias"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["userDataErr"]["alias"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["userDataErr"]) && !empty($_SESSION["userDataErr"]["contrasena"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["userDataErr"]["contrasena"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["userDataErr"]) && !empty($_SESSION["userDataErr"]["confContrasena"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["userDataErr"]["confContrasena"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["userDataErr"]) && !empty($_SESSION["userDataErr"]["pwdFileds"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["userDataErr"]["pwdFileds"]; ?></div>
    <?php endif; ?>         
    <?php if (!empty($_SESSION["userDataErr"]) && !empty($_SESSION["userDataErr"]["privilegio"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["userDataErr"]["privilegio"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["userDataErr"]) && !empty($_SESSION["userDataErr"]["adminContrasena"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["userDataErr"]["adminContrasena"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["userDataErr"]) && !empty($_SESSION["userDataErr"]["adminPWDRejected"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["userDataErr"]["adminPWDRejected"]; ?></div>
    <?php endif; ?>     
        
    <form class="userform__form" action="<?= base_url; ?>home/?homeController=user&homeAction=insertDBUser" method="POST">
        
        <div class="userCreationFileds__window-background hidThis" id="userCreationbackWindow">
            <div class="userCreationFileds__info-window" id="userCreationinfoWindow">
                <div class="pop-up-window-icon"><img class="pop-up-window-icon__img" src="<?= base_url;?>assets/img/caution-sign_75243.png"/></div>
                <div class="info-window__text-box"><h3>Administrador, confirme su contraseña antes de continuar</h3></div>
                <input class="adminpwdfield" type="password" name="adminContrasena"/>
                <div class="info-window__selectbuttons-box">
                    <input class="selectbuttons-box__button" type="submit" value="Registrar"/>
                    <button class="selectbuttons-box__button" id="userCreationCancel" type="button">Cancelar</button>
                </div>
            </div>
        </div>
        
        <fieldset class="newDeviceForm__fieldset">
            <legend class="newDeviceForm__legend">Crear usuario</legend>
            
            <table class="device-form-table" cellspacing="0">
                <tbody>
                    <tr>
                        <td class="device-form-table__label-column">NOMBRE:</td>
                        <td class="device-form-table__input-column">
                            <input class="inputs-box__input add-margin-on-input" type="text" name="nombre"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="device-form-table__label-column">APELLIDOS:</td>
                        <td class="device-form-table__input-column">
                            <input class="inputs-box__input add-margin-on-input" type="text" name="apellidos"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="device-form-table__label-column">NICKNAME:</td>
                        <td class="device-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="alias"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="device-form-table__label-column">CONTRASEÑA:</td>
                        <td class="device-form-table__input-column">
                            <input class="inputs-box__input add-margin-on-input" type="password" name="contrasena"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="device-form-table__label-column">CONFIRMAR CONTRASEÑA:</td>
                        <td class="device-form-table__input-column">
                            <input class="inputs-box__input" type="password" name="confContrasena"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="device-form-table__label-column">PRIVILEGIO:</td>
                        <td class="device-form-table__input-column">
                            <select class="inputs-box__input" name="privilegio" id="privilegio">
                                <option value="user" selected="selected">Usuario</option>
                                <option value="Admin">Administrador</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
        
        <button class="userform__submit" type="button" id="userCreationNiseSubmit">Crear</button>
    </form>
</main>
<!-- generalmente, las vistas que muestran mensajes flags tienen una etiqueta PHP donde se utiliza el método estático unsetFlagsSessions el cual elimina las 
sesiones de mensajes de errores, excepciones y de exito en un proceso -->
<?php Utils::unsetFlagsSessions();?>