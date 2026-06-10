<!-- este html es la vista del formulario de registro de un contacto; las vistas de contenido como este se utiliza la etiqueta <main>, si se quiere crear otras vistas es 
recomendable poner esta etiqueta como el padre de los elementos html, también se utiliza etiquetas PHP evaluando sesiones y variables inicializadas 
por los controladores en sus métodos de vistas, esto con el fin de determinar qué elementos html mostrar al usuario -->
<main class="newContactForm-main">
    <!-- Los controladores inicializan sesiones con strings que necesitan ser mostradas al usuario, se utilizan etiquetas PHP para evaluar 
    si existen estas sesiones, si existen, entonces se utiliza los valores de estas sesiones con un elemento html el cual muestra al usuario 
    el mensaje en forma de flags -->
    <?php if(!empty($_SESSION["insertContactSucceed"])):?>
    <div class="succeed-box"><?=$_SESSION["insertContactSucceed"];?></div>
    <?php endif; ?>
    
        
    <?php if(!empty($_SESSION["getInfoForSelectException"])):?>
    <div class="invalidinput-box"><?=$_SESSION["getInfoForSelectException"];?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["contactWithEnterIdInsertionException"])):?>
    <div class="invalidinput-box"><?=$_SESSION["contactWithEnterIdInsertionException"];?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["contactTotalInsertionException"])):?>
    <div class="invalidinput-box"><?=$_SESSION["contactTotalInsertionException"];?></div>
    <?php endif; ?>
    
    <?php if(!empty($_SESSION["contactFormErr"])):?>
        <?php foreach($_SESSION["contactFormErr"] as $err):?>
            <div class="invalidinput-box"><?=$err;?></div>
        <?php endforeach;?>    
    <?php endif;?>
            
    <form class="newContactForm" action="<?= base_url;?>home/?homeController=user&homeAction=insertContact" method="POST">
        <div class="registration__window-background hidThis" id="backWindow">
            <div class="registration__info-window" id="infoWindow">
                <div class="pop-up-window-icon"><img class="pop-up-window-icon__img" src="<?= base_url;?>assets/img/caution-sign_75243.png"/></div>
                <div class="info-window__text-box"><h3>¿Estas seguro de registrar este contacto?</h3></div>
                <div class="info-window__selectbuttons-box">
                    <input class="selectbuttons-box__button" id="yes" type="submit" value="Si"/>
                    <button class="selectbuttons-box__button" id="no" type="button">No</button>
                </div>
            </div>
        </div>
        <fieldset class="newContactForm__fieldset">
            <legend class="newContactForm__legend">Contacto</legend>
            
            <table class="enterprise-select-table" cellspacing="0">
                <tbody>
                <tr>
                    <td class="enterprise-select-table__label-column" valign="middle">Empresa:</td>
                    <td class="enterprise-select-table__select-column">
                        <select class="js-example-placeholder-single" name="empresas" id="newContectFormEnterprisesSelect">
                            <option></option>
                            <?php if (sizeof($enterprises) > 0): ?>
                                <?php foreach ($enterprises as $ent): ?>
                                    <option value="<?= $ent["Id"] ?>"><?= $ent["Nombre_comercial"] ?> - <?= $ent["Razon_social"] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                    <td class="enterprise-select-table__button-column">
                        <button class="binnacle-form__button" type="button" id="newContactFormCancelSelectBtn">Cancelar Selección</button>  
                    </td>
                </tr>
                </tbody>
            </table>
        
            <table class="contact-form-table">
                <tbody id="contactFormTbody">
                    <tr>
                        <td class="contact-form-table__label-column">QUIÉN SOLICITA:</td>
                        <td class="contact-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="contacto" id="contacto"/>
                        </td>
                        <td class="contact-form-table__label-column">TELÉFONOS:</td>
                        <td class="contact-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="telefonos" id="telefonos"/>
                        </td>
                    </tr>

                    <tr>
                        <td class="contact-form-table__label-column">NOMBRE COMERCIAL:</td>
                        <td class="contact-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="nombreComercial" id="nombreComercial"/>
                        </td>
                        <td class="contact-form-table__label-column">HORARIO:</td>
                        <td class="contact-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="horario" id="horario"/>
                        </td>
                    </tr>

                    <tr>
                        <td class="contact-form-table__label-column">RAZÓN SOCIAL:</td>
                        <td class="contact-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="razonSocial" id="razonSocial"/>
                        </td>
                        <td class="contact-form-table__label-column">ATENCIÓN:</td>
                        <td class="contact-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="atencion" id="atencion"/>
                        </td>
                    </tr>

                    <tr>
                        <td class="contact-form-table__label-column">CALLE Y NÚMERO:</td>
                        <td class="contact-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="calleYNumero" id="calleYNumero"/>
                        </td>
                        <td class="contact-form-table__label-column">COLONIA:</td>
                        <td class="contact-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="colonia" id="colonia"/>
                        </td>
                    </tr>

                    <tr>
                        <td class="contact-form-table__label-column">ENTRE CALLES:</td>
                        <td class="contact-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="entreCalles" id="entreCalles"/>
                        </td>
                        <td class="contact-form-table__label-column">LOCALIDAD:</td>
                        <td class="contact-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="localidad" id="localidad"/>
                        </td>
                    </tr>

                    <tr>
                        <td class="contact-form-table__label-column">DIRIGIRSE CON:</td>
                        <td class="contact-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="dirigirseCon" id="dirigirseCon"/>
                        </td>
                        <td class="contact-form-table__label-column">EMAIL:</td>
                        <td class="contact-form-table__input-column">
                            <input class="inputs-box__input" type="email" name="email" id="email"/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
        <div class="registration__main-button-wrapper">
            <?php if(empty($_SESSION["isAdmin"])):?>
            <a class="registration__button-link" href="<?= base_url;?>home/">Regresar</a>
            <?php endif;?>
            <button class="registration__submit" id="niseSubmit" type="button">Crear</button>
        </div>
    </form>
</main>
<!-- generalmente, las vistas que muestran mensajes flags tienen una etiqueta PHP donde se utiliza el método estático unsetFlagsSessions el cual elimina las 
sesiones de mensajes de errores, excepciones y de exito en un proceso -->
<?php Utils::unsetFlagsSessions();?>