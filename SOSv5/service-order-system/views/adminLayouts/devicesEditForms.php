<!-- este html es la vista de edición de un dispositivo; las vistas de contenido como este se utiliza la etiqueta <main>, si se quiere crear otras vistas es 
recomendable poner esta etiqueta como el padre de los elementos html, también se utiliza etiquetas PHP evaluando sesiones y variables inicializadas 
por los controladores en sus métodos de vistas, esto con el fin de determinar qué elementos html mostrar al usuario -->
<main class="devicesEditForms-main">
    <div class="enable-or-disable__window-background hidThis" id="enableOrDisablebackWindow"></div>
    <!-- Los controladores inicializan sesiones con strings que necesitan ser mostradas al usuario, se utilizan etiquetas PHP para evaluar 
    si existen estas sesiones, si existen, entonces se utiliza los valores de estas sesiones con un elemento html el cual muestra al usuario 
    el mensaje en forma de flags -->
    <?php if (!empty($_SESSION["updateDeviceInfoSucceed"])): ?>
        <div class="succeed-box"><?= $_SESSION["updateDeviceInfoSucceed"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["disableDeviceSuccess"])): ?>
        <div class="succeed-box"><?= $_SESSION["disableDeviceSuccess"]; ?></div>
    <?php endif; ?>    
    
        
    <?php if (!empty($_SESSION["editDeviceGetInfoEx"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["editDeviceGetInfoEx"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["updateDeviceInfoEx"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["updateDeviceInfoEx"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["disableDeviceEx"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["disableDeviceEx"]; ?></div>
    <?php endif; ?>
  
    <?php if(!empty($_SESSION["disableDeviceErr"])):?>
        <?php foreach($_SESSION["disableDeviceErr"] as $err):?>
            <div class="invalidinput-box"><?= $err;?></div>
        <?php endforeach;?>
    <?php endif;?>    
    <?php if(!empty($_SESSION["updateDeviceInfoErr"])):?>
        <?php foreach($_SESSION["updateDeviceInfoErr"] as $err):?>
            <div class="invalidinput-box"><?= $err;?></div>
        <?php endforeach;?>
    <?php endif;?>
    
    <div class="searchForm-wrapper">
        <form class="searchForm" action="<?= base_url; ?>home/?homeController=user&homeAction=editDevice" method="POST">
            <div class="searchForm__select-wrapper">
                <select class="js-example-placeholder-single editDevicesEnterSelect" name="empresas">
                    <option></option>
                    <?php if (sizeof($enterprises) > 0): ?>
                        <?php foreach ($enterprises as $ent): ?>
                            <?php if(!empty($_SESSION["devicesEdit_enterId"])):?>
                            <option value="<?= $ent["Id"] ?>" <?= ($_SESSION["devicesEdit_enterId"] === $ent["Id"]) ? 'selected' : '';?>><?= $ent["Nombre_comercial"] ?> - <?= $ent["Razon_social"] ?></option>
                            <?php else:?>
                            <option value="<?= $ent["Id"] ?>"><?= $ent["Nombre_comercial"] ?> - <?= $ent["Razon_social"] ?></option>
                            <?php endif;?>
                        <?php endforeach; ?>
                    <?php endif; ?>      
                </select>
            </div>    
            <input class="userform__submit without-marginTop" type="submit" value="Buscar"/>
        </form>
    </div>        
        
    <?php if(!empty($_SESSION["devicesEdit_enterId"])):?>
    <?php if(sizeof($devices_arr) > 0):?>        
    <?php foreach($devices_arr as $device):?>        
    <form class="device-form__form" action="<?= base_url;?>home/?homeController=user&homeAction=updateDeviceInfo" method="POST">
        <div class="contact-edit__background hidThis">
            <div class="contact-edit__info-window">
                <div class="info-window__text-box"><h3>¿Está seguro de editar el Equipo con ID <?=$device["Id"];?>?, verifique su contraseña antes de continuar</h3></div>
                <input class="adminpwdfield" type="password" name="adminContrasena"/>
                <div class="info-window__selectbuttons-box">
                    <input class="selectbuttons-box__button" type="submit" value="Guardar"/>
                    <button class="selectbuttons-box__cancelContact-edit-button" type="button">Cancelar</button>
                </div>    
            </div>
        </div>
        <input type="hidden" name="dispositivoId" value="<?=$device["Id"]?>"/>

        <fieldset class="device-form__fieldset">
            <legend class="device-form__legend">Equipo ID - <?=$device["Id"]?></legend>
            <div class="device-form__labels-box">
                <label class="device-form__label" for="tipoEquipo">TIPO:</label>
                <label class="device-form__label" for="marca">MARCA:</label>
                <label class="device-form__label" for="modelo">MODELO:</label>
                <label class="device-form__label" for="ns">No.SERIE:</label>
                <label class="device-form__label" for="numeroInventario">No.INVENTARIO:</label>
            </div>
            <div class="device-form__inputs-box">
                <input class="device-form__input" style="margin-bottom: 2.7rem;" type="text" value="<?= ucfirst($device['Tipo'])?>" disabled=""/>    
                <input class="device-form__input" type="text" name="marca" id="marca" value="<?=$device['Marca']?>"/>
                <input class="device-form__input" type="text" name="modelo" id="modelo" value="<?=$device['Modelo']?>"/>
                <input class="device-form__input" type="text" name="ns" id="ns" value="<?=$device['Numero_serie']?>"/>
                <input class="device-form__input" type="text" name="numeroInventario" id="numeroInventario" value="<?=($device['Numero_inventario'] !== '0') ? $device['Numero_inventario'] : ''?>"/>
            </div>
        </fieldset>
        <div class="contact-form__buttons-box">
            <button class="contact-form__edit-button" type="button">Guardar</button>
            <button class="contact-form__delete-button <?=($device["Visibilidad"] === "ENABLED") ? "" 
            : "activation-background";?>" type="button" data-id="<?=$device["Id"];?>" data-visibility="<?=$device["Visibilidad"];?>">
            <?=($device["Visibilidad"] === "ENABLED") ? "Desactivar equipo" : "Activar equipo"?></button>
        </div>
    </form>
    <?php endforeach;?>
    <?php else:?>
            <div class="without-rows__message">
                <h1>Esta empresa no tiene equipos registrados...</h1>
            </div>
    <?php endif;?>        
    <?php endif;?>
    
</main>
<!-- generalmente, las vistas que muestran mensajes flags tienen una etiqueta PHP donde se utiliza el método estático unsetFlagsSessions el cual elimina las 
sesiones de mensajes de errores, excepciones y de exito en un proceso -->        
<?php Utils::unsetFlagsSessions();?>