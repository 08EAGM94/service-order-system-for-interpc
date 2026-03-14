<main class="typesEditForms-main">
    <div class="enable-or-disable__window-background hidThis" id="enableOrDisablebackWindow"></div>
    <!-- Los controladores inicializan sesiones con strings que necesitan ser mostradas al usuario, se utilizan etiquetas PHP para evaluar 
    si existen estas sesiones, si existen, entonces se utiliza los valores de estas sesiones con un elemento html el cual muestra al usuario 
    el mensaje en forma de flags -->
    <?php if (!empty($_SESSION["updateTypeSucceed"])): ?>
        <div class="succeed-box"><?= $_SESSION["updateTypeSucceed"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["disableTypeSuccess"])): ?>
        <div class="succeed-box"><?= $_SESSION["disableTypeSuccess"]; ?></div>
    <?php endif; ?>    
        
    <?php if (!empty($_SESSION["updateTypeException"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["updateTypeException"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["disableTypeEx"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["disableTypeEx"]; ?></div>
    <?php endif; ?>     
    
    <?php if(!empty($_SESSION["updateTypeInfoErr"])):?>
        <?php foreach($_SESSION["updateTypeInfoErr"] as $err):?>
            <div class="invalidinput-box"><?= $err;?></div>
        <?php endforeach;?>
    <?php endif;?>
    <?php if(!empty($_SESSION["disableTypeErr"])):?>
        <?php foreach($_SESSION["disableTypeErr"] as $err):?>
            <div class="invalidinput-box"><?= $err;?></div>
        <?php endforeach;?>
    <?php endif;?>        
        
    <?php if(sizeof($types_arr) > 0):?>
    <?php foreach ($types_arr as $type):?>
    <form class="edit-forms__type-form" action="<?= base_url;?>home/?homeController=user&homeAction=updateTypeInfo" method="Post">
        <div class="contact-edit__background hidThis">
            <div class="contact-edit__info-window">
                <div class="info-window__text-box"><h3>¿Está seguro de editar el tipo con ID <?=$type["Id"];?>?, verifique su contraseña antes de continuar</h3></div>
                <input class="adminpwdfield" type="password" name="adminContrasena"/>
                <div class="info-window__selectbuttons-box">
                    <input class="selectbuttons-box__button" type="submit" value="Guardar"/>
                    <button class="selectbuttons-box__cancelContact-edit-button" type="button">Cancelar</button>
                </div>    
            </div>
        </div>
        <input type="hidden" name="tipoId" value="<?=$type["Id"];?>"/>

        <fieldset class="contact-form__fieldset">
            <legend class="contact-form__legend">Tipo ID - <?=$type["Id"];?></legend>
            <div class="contact-form__labels-box">
                <label class="contact-form__label" for="nombreCompleto">TIPO:</label>
            </div>
            <div class="contact-form__inputs-box">
                <input class="contact-form__input" type="text" id="nombreCompleto" name="tipo" value="<?=ucfirst($type["Tipo"]);?>"/>
            </div>
        </fieldset>
        <div class="contact-form__buttons-box">
            <button class="contact-form__edit-button" type="button">Guardar</button>
            <button class="contact-form__delete-button <?=($type["Visibilidad"] === "ENABLED") ? "" 
            : "activation-background"?>" type="button" data-id="<?=$type["Id"];?>" data-visibility="<?=$type["Visibilidad"];?>">
            <?=($type["Visibilidad"] === "ENABLED") ? "Desactivar tipo" : "Activar tipo"?></button>
        </div>
    </form>
    <?php endforeach;?>
    <?php else:?>
    <div class="without-type-rows__message">
       <h1>No hay tipos de equipo registrados...</h1>
    </div>
    <?php endif;?>
    
</main>
<!-- generalmente, las vistas que muestran mensajes flags tienen una etiqueta PHP donde se utiliza el método estático unsetFlagsSessions el cual elimina las 
sesiones de mensajes de errores, excepciones y de exito en un proceso -->        
<?php Utils::unsetFlagsSessions();?>