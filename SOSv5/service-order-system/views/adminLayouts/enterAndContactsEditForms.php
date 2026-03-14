<!-- este html es la vista de edición de una empresa y sus contactos; las vistas de contenido como este se utiliza la etiqueta <main>, si se quiere crear otras vistas es 
recomendable poner esta etiqueta como el padre de los elementos html, también se utiliza etiquetas PHP evaluando sesiones y variables inicializadas 
por los controladores en sus métodos de vistas, esto con el fin de determinar qué elementos html mostrar al usuario -->
<main class="enterprise-forms-main" id="enterpriseFormsMain">
    
    <div class="enter-or-client-delete__window-background hidThis" id="enterOrClientDeletebackWindow"></div>
    
    <!-- Los controladores inicializan sesiones con strings que necesitan ser mostradas al usuario, se utilizan etiquetas PHP para evaluar 
    si existen estas sesiones, si existen, entonces se utiliza los valores de estas sesiones con un elemento html el cual muestra al usuario 
    el mensaje en forma de flags -->
    <?php if (!empty($_SESSION["updateEnterInfoSucceed"])): ?>
        <div class="succeed-box"><?= $_SESSION["updateEnterInfoSucceed"]; ?></div>
    <?php endif; ?>  
    <?php if (!empty($_SESSION["updateClientSucceed"])): ?>
        <div class="succeed-box"><?= $_SESSION["updateClientSucceed"]; ?></div>
    <?php endif; ?> 
    <?php if (!empty($_SESSION["disableEnterpriseSuccess"])): ?>
        <div class="succeed-box"><?= $_SESSION["disableEnterpriseSuccess"]; ?></div>
    <?php endif; ?>   
    <?php if (!empty($_SESSION["disableContactSuccess"])): ?>
        <div class="succeed-box"><?= $_SESSION["disableContactSuccess"]; ?></div>
    <?php endif; ?>     
    
            
    <?php if (!empty($_SESSION["num_of_clientsEx"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["num_of_clientsEx"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["ent_arrEx"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["ent_arrEx"]; ?></div>
    <?php endif; ?>     
    <?php if (!empty($_SESSION["clients_arrEx"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["clients_arrEx"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["updateEnterInfoEx"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["updateEnterInfoEx"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["updateClientException"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["updateClientException"]; ?></div>
    <?php endif; ?>   
    <?php if (!empty($_SESSION["selectDataEnterEditEx"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["selectDataEnterEditEx"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["disableEnterpriseEx"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["disableEnterpriseEx"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["disableContactEx"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["disableContactEx"]; ?></div>
    <?php endif; ?>    
        
    <?php if(!empty($_SESSION["updateEnterpriseInfoErr"])):?>
        <?php foreach($_SESSION["updateEnterpriseInfoErr"] as $err):?>
            <div class="invalidinput-box"><?= $err;?></div>
        <?php endforeach;?>
    <?php endif;?>
    <?php if(!empty($_SESSION["updateClientInfoErr"])):?>
        <?php foreach($_SESSION["updateClientInfoErr"] as $err):?>
            <div class="invalidinput-box"><?= $err;?></div>
        <?php endforeach;?>
    <?php endif;?>         
    <?php if(!empty($_SESSION["disableEnterErr"])):?>
        <?php foreach($_SESSION["disableEnterErr"] as $err):?>
            <div class="invalidinput-box"><?= $err;?></div>
        <?php endforeach;?>
    <?php endif;?>
    <?php if(!empty($_SESSION["disableContactErr"])):?>
        <?php foreach($_SESSION["disableContactErr"] as $err):?>
            <div class="invalidinput-box"><?= $err;?></div>
        <?php endforeach;?>
    <?php endif;?>         
    
    <div class="searchForm-wrapper">
        <form class="searchForm" action="<?= base_url; ?>home/?homeController=user&homeAction=editEnterprise" method="POST">
            <div class="searchForm__select-wrapper">
                <select class="js-example-placeholder-single" name="empresas" id="editEnterSelect">
                    <option></option>
                    <?php if (sizeof($enterprises) > 0): ?>
                        <?php foreach ($enterprises as $ent): ?>
                            <option value="<?= $ent["Id"] ?>"><?= $ent["Nombre_comercial"] ?> - <?= $ent["Razon_social"] ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>      
                </select>
            </div>    
            <input class="userform__submit without-marginTop" type="submit" value="Buscar"/>
        </form>
    </div>    
       
    <?php if(!empty($_SESSION["enterpriseEdit_enterId"])):?>
        
    <form class="enterprise-forms__form" action="<?= base_url;?>home/?homeController=user&homeAction=updateEnterInfo" method="POST">
        <div class="enter-edit__background hidThis" id="enterpriseEditConfirmationBackground">
            <div class="enter-edit__info-window">
                <div class="info-window__text-box"><h3>¿Está seguro de editar esta empresa?, verifique su contraseña antes de continuar</h3></div>
                <input class="adminpwdfield" type="password" name="adminContrasena"/>
                <div class="info-window__selectbuttons-box">
                    <input class="selectbuttons-box__button" type="submit" value="Guardar"/>
                    <button class="selectbuttons-box__button" id="enterpriseEditeCancelBtn" type="button">Cancelar</button>
                </div>    
            </div>
        </div>
        <input type="hidden" name="empresaId" value="<?=$ent_arr["Id"];?>"/>

        <fieldset class="enterprise-forms__fieldset">
            <legend class="enterprise-forms__legend">Empresa</legend>
            <div class="enterprise-forms__labels-box">
                <label class="enterprise-forms__label" for="nombreComercial">NOMBRE COMERCIAL:</label>
                <label class="enterprise-forms__label" for="razonSocial">RAZÓN SOCIAL:</label>
                <label class="enterprise-forms__label" for="calleYNumero">CALLE Y NÚMERO:</label>
                <label class="enterprise-forms__label" for="entreCalles">ENTRE CALLES:</label>
                <label class="enterprise-forms__label" for="dirigirseCon">DIRIGIRSE CON:</label>
                <label class="enterprise-forms__label" for="telefonos">TELÉFONO(S):</label>
                <label class="enterprise-forms__label" for="horario">HORARIO:</label>
                <label class="enterprise-forms__label" for="atencion">ATENCIÓN:</label>
                <label class="enterprise-forms__label" for="colonia">COLONIA:</label>
                <label class="enterprise-forms__label" for="localidad">LOCALIDAD:</label>
                <label class="enterprise-forms__label" for="email">E-MAIL:</label>
            </div>
            <div class="enterprise-forms__inputs-box">
                <input class="enterprise-forms__input" type="text" id="nombreComercial" name="nombreComercial" value="<?=$ent_arr["Nombre_comercial"];?>"/>
                <input class="enterprise-forms__input" type="text" id="razonSocial" name="razonSocial" value="<?= (!empty($ent_arr["Razon_social"])) ? $ent_arr["Razon_social"] : ""; ?>"/>
                <input class="enterprise-forms__input" type="text" id="calleYNumero" name="calleYNumero" value="<?= (!empty($ent_arr["Calle_numero"])) ? $ent_arr["Calle_numero"] : ""; ?>"/>
                <input class="enterprise-forms__input" type="text" id="entreCalles" name="entreCalles" value="<?= (!empty($ent_arr["Entre_calles"])) ? $ent_arr["Entre_calles"] : ""; ?>"/>
                <input class="enterprise-forms__input" type="text" id="dirigirseCon" name="dirigirseCon" value="<?= (!empty($ent_arr["Dirigirse_con"])) ? $ent_arr["Dirigirse_con"] : ""; ?>"/>
                <input class="enterprise-forms__input" type="text" id="telefonos" name="telefonos" value="<?=$ent_arr["Telefonos"];?>"/>
                <input class="enterprise-forms__input" type="text" id="horario" name="horario" value="<?= (!empty($ent_arr["Horario"])) ? $ent_arr["Horario"] : ""; ?>"/>
                <input class="enterprise-forms__input" type="text" id="atencion" name="atencion" value="<?= (!empty($ent_arr["Atencion"])) ? $ent_arr["Atencion"] : ""; ?>"/>
                <input class="enterprise-forms__input" type="text" id="colonia" name="colonia" value="<?= (!empty($ent_arr["Colonia"])) ? $ent_arr["Colonia"] : ""; ?>"/>
                <input class="enterprise-forms__input" type="text" id="localidad" name="localidad" value="<?= (!empty($ent_arr["Localidad"])) ? $ent_arr["Localidad"] : ""; ?>"/>
                <input class="enterprise-forms__input" type="email" id="email" name="email" value="<?= (!empty($ent_arr["Email"])) ? $ent_arr["Email"] : ""; ?>"/>
            </div>
        </fieldset>
        <div class="enterprise-forms__buttons-box">
            <button class="enterprise-forms__edit-button" type="button">Guardar</button>
            <button class="enterprise-forms__delete-button <?=($ent_arr["Visibilidad"] === "ENABLED") ? "" 
                : "activation-background"?>" type="button" data-id="<?=$ent_arr["Id"];?>" data-visibility="<?=$ent_arr["Visibilidad"];?>">
                <?=($ent_arr["Visibilidad"] === "ENABLED") ? "Desactivar empresa" : "Activar empresa"?></button>
        </div>
    </form>
    
    <?php if(!empty($contacts_arr)):?>
    <?php foreach ($contacts_arr as $contact):?>
    <form class="enterprise-forms__contact-form" action="<?= base_url;?>home/?homeController=user&homeAction=updateContactInfo" method="Post">
        <div class="contact-edit__background hidThis">
            <div class="contact-edit__info-window">
                <div class="info-window__text-box"><h3>¿Está seguro de editar el contacto con ID <?=$contact["Id"];?>?, verifique su contraseña antes de continuar</h3></div>
                <input class="adminpwdfield" type="password" name="adminContrasena"/>
                <div class="info-window__selectbuttons-box">
                    <input class="selectbuttons-box__button" type="submit" value="Guardar"/>
                    <button class="selectbuttons-box__cancelContact-edit-button" type="button">Cancelar</button>
                </div>    
            </div>
        </div>
        <input type="hidden" name="contactoId" value="<?=$contact["Id"];?>"/>

        <fieldset class="contact-form__fieldset">
            <legend class="contact-form__legend">Contacto ID - <?=$contact["Id"];?></legend>
            <div class="contact-form__labels-box">
                <label class="contact-form__label" for="nombreCompleto">NOMBRE:</label>
            </div>
            <div class="contact-form__inputs-box">
                <input class="contact-form__input" type="text" id="nombreCompleto" name="nombre" value="<?=$contact["Nombre_completo"];?>"/>
            </div>
        </fieldset>
        <div class="contact-form__buttons-box">
            <button class="contact-form__edit-button" type="button">Guardar</button>
            <button class="contact-form__delete-button <?=($contact["Visibilidad"] === "ENABLED") ? "" 
            : "activation-background"?>" type="button" data-id="<?=$contact["Id"];?>" data-visibility="<?=$contact["Visibilidad"];?>">
            <?=($contact["Visibilidad"] === "ENABLED") ? "Desactivar contacto" : "Activar contacto"?></button>
        </div>
    </form>
    <?php endforeach;?>
    <?php endif;?>
    
    <?php endif;?>    
</main>
<!-- generalmente, las vistas que muestran mensajes flags tienen una etiqueta PHP donde se utiliza el método estático unsetFlagsSessions el cual elimina las 
sesiones de mensajes de errores, excepciones y de exito en un proceso -->  
<?php Utils::unsetFlagsSessions();?>