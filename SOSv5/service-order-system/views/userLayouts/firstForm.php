<!-- este html es la vista del formulario de registro de una bitácora; las vistas de contenido como este se utiliza la etiqueta <main>, si se quiere crear otras vistas es 
recomendable poner esta etiqueta como el padre de los elementos html, también se utiliza etiquetas PHP evaluando sesiones y variables inicializadas 
por los controladores en sus métodos de vistas, esto con el fin de determinar qué elementos html mostrar al usuario -->
<main class="binnacle-main">
    
    <!-- Los controladores inicializan sesiones con strings que necesitan ser mostradas al usuario, se utilizan etiquetas PHP para evaluar 
    si existen estas sesiones, si existen, entonces se utiliza los valores de estas sesiones con un elemento html el cual muestra al usuario 
    el mensaje en forma de flags -->
    <?php if(!empty($_SESSION["getInfoForSelectsException"])):?>
    <div class="invalidinput-box"><?=$_SESSION["getInfoForSelectsException"];?></div>
    <?php endif; ?>
    
    <?php if(!empty($_SESSION["binnDataSucceed"])):?>
    <div class="succeed-box">Se guardaron todos los datos correctamente</div>
    <?php endif; ?>
   
    <?php if(!empty($_SESSION["binnDataException"])):?>
    <div class="invalidinput-box"><?=$_SESSION["binnDataException"];?></div>
    <?php endif; ?>

    <?php if(!empty($_SESSION["binnDataErr"])):?>
        <?php foreach($_SESSION["binnDataErr"] as $err):?>
            <div class="invalidinput-box"><?=$err;?></div>
        <?php endforeach;?>    
    <?php endif;?>    
    
    <form class="binnacle-form" action="<?= base_url; ?>home/?homeController=user&homeAction=binninsertion" method="POST">
    <input type="hidden" name="userId" value="<?=$_SESSION["identity"]["Id"]?>">
    <fieldset class="binnacle-form__client-wrapper">
        <legend class="binnacle-form__legend">Cliente</legend>
        
        <table class="enterprise-select-table" cellspacing="0">
            <tbody>
            <tr>
                <td class="enterprise-select-table__label-column" valign="middle">EMPRESA:</td>
                <td class="enterprise-select-table__select-column">
                    <select class="js-example-placeholder-single" name="empresas" id="firstFormenterprisesSelect">
                        <option></option>
                        <?php if (sizeof($enterprises) > 0): ?>
                            <?php foreach ($enterprises as $ent): ?>
                                <option value="<?= $ent["Id"] ?>"><?= $ent["Nombre_comercial"] ?> - <?= $ent["Razon_social"] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
                <td class="enterprise-select-table__button-column">
                    <button class="binnacle-form__button" type="button" id="firstFormCancelSelectBtn">Cancelar Selección</button>  
                </td>
            </tr>
            </tbody>
        </table>
        
        <table class="contact-form-table">
            <tbody id="contactFormTbody">
                <tr>
                    <td class="contact-form-table__label-column">QUIÉN SOLICITA:</td>
                    <td class="contact-form-table__input-column">
                        <select class="js-example-placeholder-single" name="contactos" id="firstFormContactSelect">
                            <option></option>
                        </select>
                    </td>
                    <td class="contact-form-table__label-column">TELÉFONOS:</td>
                    <td class="contact-form-table__input-column">
                        <div class="inputs-box__input"></div>
                    </td>
                </tr>
                
                <tr>
                    <td class="contact-form-table__label-column">NOMBRE COMERCIAL:</td>
                    <td class="contact-form-table__input-column">
                        <div class="inputs-box__input"></div>
                    </td>
                    <td class="contact-form-table__label-column">HORARIO:</td>
                    <td class="contact-form-table__input-column">
                        <div class="inputs-box__input"></div>
                    </td>
                </tr>
                
                <tr>
                    <td class="contact-form-table__label-column">RAZÓN SOCIAL:</td>
                    <td class="contact-form-table__input-column">
                        <div class="inputs-box__input"></div>
                    </td>
                    <td class="contact-form-table__label-column">ATENCIÓN:</td>
                    <td class="contact-form-table__input-column">
                        <div class="inputs-box__input"></div>
                    </td>
                </tr>
                
                <tr>
                    <td class="contact-form-table__label-column">CALLE Y NÚMERO:</td>
                    <td class="contact-form-table__input-column">
                        <div class="inputs-box__input"></div>
                    </td>
                    <td class="contact-form-table__label-column">COLONIA:</td>
                    <td class="contact-form-table__input-column">
                        <div class="inputs-box__input"></div>
                    </td>
                </tr>
                
                <tr>
                    <td class="contact-form-table__label-column">ENTRE CALLES:</td>
                    <td class="contact-form-table__input-column">
                        <div class="inputs-box__input"></div>
                    </td>
                    <td class="contact-form-table__label-column">LOCALIDAD:</td>
                    <td class="contact-form-table__input-column">
                        <div class="inputs-box__input"></div>
                    </td>
                </tr>
                
                <tr>
                    <td class="contact-form-table__label-column">DIRIGIRSE CON:</td>
                    <td class="contact-form-table__input-column">
                        <div class="inputs-box__input"></div>
                    </td>
                    <td class="contact-form-table__label-column">EMAIL:</td>
                    <td class="contact-form-table__input-column">
                        <div class="inputs-box__input"></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </fieldset>

    <fieldset class="binnacle-form__serviceOrDevice-wrapper">

        <input class="serviceOrDevice__radio" type="radio" id="activityChoice1" name="tipoActividades" value="servicio"/>
        <label class="serviceOrDevice__service-label" for="activityChoice1">Servicio</label>

        <input class="serviceOrDevice__radio" type="radio" id="activityChoice2" name="tipoActividades" value="equipo"/>
        <label for="activityChoice2">Equipo</label>

    </fieldset>

    <fieldset class="binnacle-form__service-wrapper hidThis" id="serviceBox">
        <legend class="binnacle-form__legend">Servicio</legend>
        <textarea class="service-textarea" name="servicio"></textarea>
    </fieldset>

    <fieldset class="binnacle-form__device-wrapper hidThis" id="deviceBox">
        <legend class="binnacle-form__legend">Equipo</legend>
        
        <table class="device-select-table" cellspacing="0">
            <tbody id="deviceSelectTbody">
            <tr>
                <td class="device-select-table__label-column" valign="middle">EQUIPO:</td>
                <td class="device-select-table__select-column">
                    <select class="js-example-placeholder-single" name="equipos" id="firstFormDeviceSelect">
                        <option></option>
                    </select>
                </td>
                <td class="device-select-table__button-column">
                    <button class="binnacle-form__button" type="button" id="firstFormCancelDeviceSelectBtn">Cancelar selección</button>  
                </td>
            </tr>
            </tbody>
        </table>
        
        <table class="device-form-table" cellspacing="0">
            <tbody id="deviceFormTbody">
                <tr>
                    <td class="device-form-table__label-column">TIPO:</td>
                    <td class="device-form-table__input-column"><div class="inputs-box__input"></div></td>
                </tr>
                <tr>
                    <td class="device-form-table__label-column">MARCA:</td>
                    <td class="device-form-table__input-column"><div class="inputs-box__input"></div></td>
                </tr>
                <tr>
                    <td class="device-form-table__label-column">MODELO:</td>
                    <td class="device-form-table__input-column"><div class="inputs-box__input"></div></td>
                </tr>
                <tr>
                    <td class="device-form-table__label-column">N.S:</td>
                    <td class="device-form-table__input-column"><div class="inputs-box__input"></div></td>
                </tr>
                <tr>
                    <td class="device-form-table__label-column">NO.INVENTARIO:</td>
                    <td class="device-form-table__input-column"><div class="inputs-box__input"></div></td>
                </tr>
            </tbody>
        </table>
    </fieldset>
    
    
    
    <div class="binnacleremindedfields__window-background hidThis" id="backWindow">
        <div class="binnacleremindedfields__info-window" id="infoWindow">
            <div class="info-window__text-box"><h3>¿Estas seguro de registrar esta bitácora?</h3></div>
            <div class="info-window__selectbuttons-box">
                <input class="selectbuttons-box__button" id="yes" type="submit" value="Si"/>
                <button class="selectbuttons-box__button" id="no" type="button">No</button>
            </div>
        </div>
    </div>
    
    <div class="binnacle-form__main-button-wrapper">
        <?php if(empty($_SESSION["isAdmin"])):?>
        <a class="binnacle-form__button-link" href="<?= base_url;?>home/">Regresar</a>
        <?php endif;?>
        <button class="binnacle-form__submit" id="niseSubmit" type="button">Crear</button>
    </div>
</form>
</main>
<!-- generalmente, las vistas que muestran mensajes flags tienen una etiqueta PHP donde se utiliza el método estático unsetFlagsSessions el cual elimina las 
sesiones de mensajes de errores, excepciones y de exito en un proceso -->
<?php Utils::unsetFlagsSessions();?>