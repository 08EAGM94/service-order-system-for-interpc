<!-- estos div son ventanas emergentes, en esta ocasión su elemento html relativo es el <body> para que el fondo de estas ventanas cubran toda la vista-->
<div class="binnacleremindedfields__windowform-background hidThis" id="formBackWindow">
    <div class="binnacleremindedfields__form-window" id="formWindow">
        <div class="form-window__form-box">
            <form class="cancel-desc-form" action="<?= base_url; ?>finishing/?controller=form&action=cancellingBinn" method="POST">
                <input type="hidden" name="cancelwithid" value="<?=$_GET["id"]?>"/>
                <input type="hidden" name="cancelestatus" value="cancelado"/>
                <label class="cancel-desc-form__label" for="cancelDesc">Describe las razones de la cancelación</label>
                <textarea class="cancel-desc-form__desc-input" name="cancelacion" id="cancelDesc"></textarea>
                <div class="info-window__selectbuttons-box okCenter">
                    <input class="selectbuttons-box__button ok" id="remindedCancelSend" type="submit" value="Enviar"/>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="binnacleremindedfields__window-background hidThis" id="remindedBackWindow">
    <div class="binnacleremindedfields__info-window" id="remindedInfoWindow">
        <div class="info-window__text-box"><h3>¿Estas seguro de cancelar definitivamente la bitacora?</h3></div>
        <div class="info-window__selectbuttons-box">
            <button class="selectbuttons-box__button" id="remindedYes" type="button">Si</button>
            <button class="selectbuttons-box__button" id="remindedNo" type="button">No</button>
        </div>
    </div>
</div>

<!-- este html es la vista del formulario de seguimiento de una bitácora; las vistas de contenido como este se utiliza la etiqueta <main>, si se quiere crear otras 
vistas es recomendable poner esta etiqueta como el padre de los elementos html, también se utiliza etiquetas PHP evaluando sesiones y variables inicializadas 
por los controladores en sus métodos de vistas, esto con el fin de determinar qué elementos html mostrar al usuario -->
<main class="binnacleremindedfields-main">
    
    <!-- Los controladores inicializan sesiones con strings que necesitan ser mostradas al usuario, se utilizan etiquetas PHP para evaluar 
    si existen estas sesiones, si existen, entonces se utiliza los valores de estas sesiones con un elemento html el cual muestra al usuario 
    el mensaje en forma de flags -->
    <?php if(!empty($_SESSION["followupCancelErr"]) && !empty($_SESSION["followupCancelErr"]["cancelwithid"])):?>
    <div class="invalidinput-box"><?=$_SESSION["followupCancelErr"]["cancelwithid"];?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["followupCancelErr"]) && !empty($_SESSION["followupCancelErr"]["cancelestatus"])):?>
    <div class="invalidinput-box"><?=$_SESSION["followupCancelErr"]["cancelestatus"];?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["followupCancelErr"]) && !empty($_SESSION["followupCancelErr"]["cancelacion"])):?>
    <div class="invalidinput-box"><?=$_SESSION["followupCancelErr"]["cancelacion"];?></div>
    <?php endif; ?>
    
    <?php if(!empty($_SESSION["resetActivitiesSucceed"])):?>
    <div class="succeed-box"><?=$_SESSION["resetActivitiesSucceed"]?></div>
    <?php endif; ?>
    
    <?php if(!empty($_SESSION["followupErr"]) && !empty($_SESSION["followupErr"]["id"])):?>
    <div class="invalidinput-box"><?=$_SESSION["followupErr"]["id"];?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["followupErr"]) && !empty($_SESSION["followupErr"]["estatus"])):?>
    <div class="invalidinput-box"><?=$_SESSION["followupErr"]["estatus"];?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["followupErr"]) && !empty($_SESSION["followupErr"]["binnFecha"])):?>
    <div class="invalidinput-box"><?=$_SESSION["followupErr"]["binnFecha"];?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["followupErr"]) && !empty($_SESSION["followupErr"]["seHizo"])):?>
    <div class="invalidinput-box"><?=$_SESSION["followupErr"]["seHizo"];?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["followupErr"]) && !empty($_SESSION["followupErr"]["observaciones"])):?>
    <div class="invalidinput-box"><?=$_SESSION["followupErr"]["observaciones"];?></div>
    <?php endif; ?>
    
    <form action="<?= base_url;?>finishing/?controller=form&action=followupPartial" method="POST">
        <input type="hidden" name="id" value="<?=$_GET["id"]?>"/>
        <input type="hidden" name="estatus" value="falta confirmar"/>
        
        <div class="binnacleremindedfields__smallfieldsets-wrapper">
            
            <div class="smallfieldsets__logo">
                <img class="interpc-logo" src="<?= base_url; ?>assets/img/logo.png"/>
            </div>
            
            <div class="smallfieldsets__binnTitle">
                <h1>Seguimiento de bitacora</h1>
            </div>
            
            <div class="smallfieldsets__idanddate-wrapper">
                <fieldset class="binnacleremindedfields__binnid-wrapper">
                    <legend class="binnacleremindedfields__legend">Folio</legend>
                    <?=$info["Id"]?>
                </fieldset>

                <fieldset class="binnacleremindedfields__date-wrapper">
                    <legend class="binnacleremindedfields__legend">Inicio</legend>
                    <input class="binnacleremindedfields__date-input" id="clientDate" type="date" name="binnFecha" value="<?=$info["Inicio"]?>"/>
                </fieldset>
            </div>
            
        </div>
        
        <fieldset class="binnacleremindedfields__client-wrapper">
            <legend class="binnacleremindedfields__legend">Cliente</legend>

            <div class="binnacleremindedfields__first-half">
                <div class="binnacleremindedfields__labels-box">
                    <label class="labels-box__label" for="nombre">QUIÉN SOLICITA:</label>
                    <label class="labels-box__label" for="nombreComercial">NOMBRE COMERCIAL:</label>
                    <label class="labels-box__label" for="razonSocial">RAZÓN SOCIAL:</label>
                    <label class="labels-box__label" for="calleYNumero">CALLE Y NÚMERO:</label>
                    <label class="labels-box__label" for="entreCalles">ENTRE CALLES:</label>
                    <label class="labels-box__label" for="dirigirseCon">DIRIGIRSE CON:</label>  
                </div>

                <div class="binnacleremindedfields__inputs-box">
                    <input class="inputs-box__input" type="text" id="nombre" value="<?=$info["Nombre_completo"]?>" disabled=""/>
                    <input class="inputs-box__input" type="text" id="nombreComercial" value="<?=$info["Nombre_comercial"]?>" disabled=""/>
                    <input class="inputs-box__input" type="text" id="razonSocial" value="<?=(!empty($info["Razon_social"])) ? $info["Razon_social"] : "Sin asignar"?>" disabled=""/>
                    <input class="inputs-box__input" type="text" id="calleYNumero" value="<?=(!empty($info["Calle_numero"])) ? $info["Calle_numero"] : "Sin asignar"?>" disabled=""/>
                    <input class="inputs-box__input" type="text" id="entreCalles" value="<?=(!empty($info["Entre_calles"])) ? $info["Entre_calles"] : "Sin asignar"?>" disabled=""/>
                    <input class="inputs-box__input" type="text" id="dirigirseCon" value="<?=(!empty($info["Dirigirse_con"])) ? $info["Dirigirse_con"] : "Sin asignar"?>" disabled=""/>
                </div>
            </div>    

            <div class="binnacleremindedfields__second-half">
                <div class="binnacleremindedfields__labels-box">
                    <label class="labels-box__label" for="telefonos">TELÉFONO(S):</label>
                    <label class="labels-box__label" for="horario">HORARIO:</label>
                    <label class="labels-box__label" for="atencion">ATENCIÓN:</label>
                    <label class="labels-box__label" for="colonia">COLONIA:</label>
                    <label class="labels-box__label" for="localidad">LOCALIDAD:</label>
                    <label class="labels-box__label" for="email">E-MAIL:</label>
                </div>

                <div class="binnacleremindedfields__inputs-box">
                    <input class="inputs-box__input" type="text" id="telefonos" value="<?=$info["Telefonos"]?>" disabled=""/>
                    <input class="inputs-box__input" type="text" id="horario" value="<?=(!empty($info["Horario"])) ? $info["Horario"] : "Sin asignar"?>" disabled=""/>
                    <input class="inputs-box__input" type="text" id="atencion" value="<?=(!empty($info["Atencion"])) ? $info["Atencion"] : "Sin asignar"?>" disabled=""/>
                    <input class="inputs-box__input" type="text" id="colonia" value="<?=(!empty($info["Colonia"])) ? $info["Colonia"] : "Sin asignar"?>" disabled=""/>
                    <input class="inputs-box__input" type="text" id="localidad" value="<?=(!empty($info["Localidad"])) ? $info["Localidad"] : "Sin asignar"?>" disabled=""/>
                    <input class="inputs-box__input" type="email" id="email" value="<?=(!empty($info["Email"])) ? $info["Email"] : "Sin asignar"?>" disabled=""/>
                </div>
            </div>

        </fieldset>
        
        <?php if(!empty($info["Servicio"])): ?>
        
        <fieldset class="binnacleremindedfields__service-wrapper">
            <legend class="binnacleremindedfields__legend">Servicio</legend>
            <textarea class="service-textarea" disabled=""><?=$info["Servicio"]?></textarea>
        </fieldset>
        
        <?php else: ?>
        
        <fieldset class="binnacle-form__device-wrapper">
            <legend class="binnacleremindedfields__legend">Equipo</legend>
            <div class="binnacleremindedfields__labels-box">
                <label class="labels-box__label" for="tipoEquipo">TIPO:</label>
                <label class="labels-box__label" for="marca">MARCA:</label>
                <label class="labels-box__label" for="modelo">MODELO:</label>
                <label class="labels-box__label" for="ns">No.SERIE:</label>
                <label class="labels-box__label" for="numeroInventario">No.INVENTARIO:</label>
            </div>
            <div class="binnacleremindedfields__inputs-box">
                <input class="inputs-box__device-input" type="text" id="tipoEquipo" value="<?=$info["Tipo"]?>" disabled=""/>
                <input class="inputs-box__device-input" type="text" id="marca" value="<?=$info["Marca"]?>" disabled=""/>
                <input class="inputs-box__device-input" type="text" id="modelo" value="<?=$info["Modelo"]?>" disabled=""/>
                <input class="inputs-box__device-input" type="text" id="ns" value="<?=$info["Numero_serie"]?>" disabled=""/>
                <input class="inputs-box__device-input" type="text" id="numeroInventario" value="<?=($info["Numero_inventario"] !== '0') ? $info["Numero_inventario"] : "N/A"?>" disabled=""/>
            </div>
        </fieldset>
        
        <?php endif;?>
        
        
        <fieldset class="binnacleremindedfields__workmade-wrapper" id="serviceBox">
            <legend class="binnacleremindedfields__legend">Actividades realizadas</legend>
            <textarea class="workmade-textarea" name="seHizo"></textarea>
        </fieldset>

        <fieldset class="binnacleremindedfields__remark-wrapper" id="deviceBox">
            <legend class="binnacleremindedfields__legend">Observaciones</legend>
            <textarea class="remark-textarea" name="observaciones"></textarea>
        </fieldset>
        
        
        <div class="reminded-fields-buttonbox">
        <a class="binnacleremindedfields__consent-button-link" href="<?= base_url;?>home/?homeController=user&homeAction=followuplist">Regresar</a>
        <?php if(!empty($info) && !empty($info["Nombre_comercial"])): ?>
        <button class="consentcancel__button" id="remindedCancelBtn" type="button">Cancelar</button>
        <input class="consentcancel__button" type="submit" value="Siguiente"/>
        <?php endif; ?>
        </div>
    </form>
    
</main>
<!-- generalmente, las vistas que muestran mensajes flags tienen una etiqueta PHP donde se utiliza el método estático unsetFlagsSessions el cual elimina las 
sesiones de mensajes de errores, excepciones y de exito en un proceso -->
<?php Utils::unsetFlagsSessions();