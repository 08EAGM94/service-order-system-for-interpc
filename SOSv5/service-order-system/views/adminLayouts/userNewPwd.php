<!-- este html es la vista del formulario de cambio de contraseña de un usuario; las vistas de contenido como este se utiliza la etiqueta <main>, 
si se quiere crear otras vistas es recomendable poner esta etiqueta como el padre de los elementos html, también se utiliza etiquetas PHP evaluando 
sesiones y variables inicializadas por los controladores en sus métodos de vistas, esto con el fin de determinar qué elementos html mostrar al usuario -->
<main class="userNewPwd-main">
    <div class="searchUserForm-wrapper">
        <form class="searchUserForm" action="<?= base_url; ?>home/?homeController=user&homeAction=userNewPassword" method="POST">
            <div class="searchUserForm__select-wrapper">
                <select class="js-example-placeholder-single" name="usuarios" id="usersSelect">
                    <option></option>
                    <?php if (sizeof($users) > 0): ?>
                        <?php foreach ($users as $usr): ?>
                            <option value="<?= $usr["Id"] ?>"><?= $usr["Nombre"].' '.$usr["Apellidos"]?> - <?= $usr["Alias"] ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>       
                </select>
            </div>    
            <input class="userform__submit without-marginTop" type="submit" value="Buscar"/>
        </form>
    </div
    <!-- Los controladores inicializan sesiones con strings que necesitan ser mostradas al usuario, se utilizan etiquetas PHP para evaluar 
    si existen estas sesiones, si existen, entonces se utiliza los valores de estas sesiones con un elemento html el cual muestra al usuario 
    el mensaje en forma de flags -->
    <?php if (!empty($_SESSION["userPWDSucceed"])): ?>
        <div class="succeed-box"><?= $_SESSION["userPWDSucceed"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["disableUserSuccess"])): ?>
        <div class="succeed-box"><?= $_SESSION["disableUserSuccess"]; ?></div>
    <?php endif; ?>
        

    <?php if (!empty($_SESSION["gettingUsersException"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["gettingUsersException"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["userInfoException"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["userInfoException"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["disableUserEx"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["disableUserEx"]; ?></div>
    <?php endif; ?>    
     
        
    <?php if(!empty($_SESSION["userPWDErr"])):?>
        <?php foreach($_SESSION["userPWDErr"] as $err):?>
            <div class="invalidinput-box"><?= $err;?></div>
        <?php endforeach;?>
    <?php endif;?>
            
    
    <?php if(!empty($_SESSION["userNewPwd_userId"])): ?>
    
    <div class="userDelete__window-background hidThis" id="userDeletebackWindow">
        <form class="userDelete__info-window" id="userDeleteinfoWindow" action="<?= base_url; ?>home/?homeController=user&homeAction=disableUser" method="POST">
            <div class="info-window__text-box"><h3>¿Está seguro de desactivar este usuario?, este usuario ya no tendrá más acceso a la aplicación y no será 
                    visible en el apartado de "Configuración de usuarios", confirme su 
                    contraseña antes de continuar</h3></div>
            <input type="hidden" value="<?=$_SESSION["userNewPwd_userId"]?>" name="usuarioId"/>
            <input type="hidden" value="DISABLED" name="visibilidad"/>
            <input class="adminpwdfield" type="password" name="adminContrasena"/>
            <div class="info-window__selectbuttons-box">
               <input class="selectbuttons-box__button" type="submit" value="Desactivar"/>
               <button class="selectbuttons-box__button" id="userDeleteCancel" type="button">Cancelar</button>
            </div>
        </form>
    </div>        
            
    <form class="userform__form" action="<?= base_url; ?>home/?homeController=user&homeAction=updateUserPassword" method="POST">
        <input type="hidden" value="<?=$user_info["Id"]?>" name="usuarioId"/>
        <div class="userCreationFileds__window-background hidThis" id="userCreationbackWindow">
            <div class="userCreationFileds__info-window" id="userCreationinfoWindow">
                <div class="info-window__text-box"><h3>Administrador, confirme su contraseña antes de continuar</h3></div>
                <input class="adminpwdfield" type="password" name="adminContrasena"/>
                <div class="info-window__selectbuttons-box">
                    <input class="selectbuttons-box__button" type="submit" value="Registrar"/>
                    <button class="selectbuttons-box__button" id="userCreationCancel" type="button">Cancelar</button>
                </div>
            </div>
        </div>
        
        <fieldset class="newDeviceForm__fieldset">
            <legend class="newDeviceForm__legend">Usuario con Id - <?=$user_info["Id"]?></legend>
            
            <table class="device-form-table" cellspacing="0">
                <tbody>
                    <tr>
                        <td class="device-form-table__label-column">NOMBRE:</td>
                        <td class="device-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="nombre" value="<?=$user_info["Nombre"]?>" disabled=""/>
                        </td>
                    </tr>
                    <tr>
                        <td class="device-form-table__label-column">APELLIDOS:</td>
                        <td class="device-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="apellidos" value="<?=$user_info["Apellidos"]?>" disabled=""/>
                        </td>
                    </tr>
                    <tr>
                        <td class="device-form-table__label-column">NICKNAME:</td>
                        <td class="device-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="alias" value="<?=$user_info["Alias"]?>" disabled=""/>
                        </td>
                    </tr>
                    <tr>
                        <td class="device-form-table__label-column">CONTRASEÑA:</td>
                        <td class="device-form-table__input-column">
                            <input class="inputs-box__input" type="password" name="contrasena"/>
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
                            <input class="inputs-box__input" type="text" name="privilegio" value="<?=($user_info["Privilegio"] === "user") ? 'Usuario' : 'Administrador'?>" disabled=""/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <div class="userform__buttons-wrapper">
            <button class="userform__submit without-marginTop" type="button" id="userCreationNiseSubmit">Cambiar contraseña</button>
            <?php if($_SESSION["identity"]["Id"] !== $user_info["Id"]):?>
            <button class="userform__submit without-marginTop-red" type="button" id="userDeleteNiseSubmit">Desactivar Usuario</button>
            <?php endif;?>
        </div>
    </form>
    <?php endif;?>    
</main>
<!-- generalmente, las vistas que muestran mensajes flags tienen una etiqueta PHP donde se utiliza el método estático unsetFlagsSessions el cual elimina las 
sesiones de mensajes de errores, excepciones y de exito en un proceso -->
<?php Utils::unsetFlagsSessions();?>