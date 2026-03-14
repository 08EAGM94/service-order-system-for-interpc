<!-- este div es una ventana emergente, en esta ocasión su elemento html relativo es el <body> para que el fondo de esta ventana cubra toda la vista-->
<div class="binnacle-sign__window-background hidThis" id="backWindow">
    <div class="binnacle-sign__info-window" id="infoWindow">
        <div class="info-window__text-box"><h3>¿Estas seguro de cancelar el progreso de esta bitácora?</h3></div>
        <div class="info-window__selectbuttons-box">
            <a class="selectbuttons-box__button ok" id="yes" href="<?= base_url; ?>finishing/?controller=form&action=resetActivitiesDescriptions&id=<?=$_GET["id"]?>">Si</a>
            <button class="selectbuttons-box__button" id="consentNo" type="button">No</button>
        </div>
    </div>
</div>
<!-- este html es la vista del consentimiento de actividades de una bitácora; las vistas de contenido como este se utiliza la etiqueta <main>, si se quiere crear otras 
vistas es recomendable poner esta etiqueta como el padre de los elementos html, también se utiliza etiquetas PHP evaluando sesiones y variables inicializadas 
por los controladores en sus métodos de vistas, esto con el fin de determinar qué elementos html mostrar al usuario -->
<main class="binnacleremindedfields-main">
    
    <form action="<?=base_url;?>finishing/?controller=form&action=<?=(!empty($_SESSION["identity"]["Firma"])) ? "clientsign" : "techsign"?>" method="POST">

        <div class="binnacleremindedfields__smallfieldsets-wrapper">
            
            <div class="smallfieldsets__logo">
                <img class="interpc-logo" src="<?= base_url; ?>assets/img/logo.png"/>
            </div>
            
            <div class="smallfieldsets__binnTitle">
                <h1>Conformidad de actividades</h1>
            </div>
            
            <div class="smallfieldsets__idanddate-wrapper">
                <fieldset class="binnacleremindedfields__binnid-wrapper">
                    <legend class="binnacleremindedfields__legend">Folio</legend>
                    <h2><?=$info["Id"]?></h2>
                </fieldset>

                <fieldset class="binnacleremindedfields__date-wrapper">
                    <legend class="binnacleremindedfields__legend">Inicio definido</legend>
                    <h2><?=$info["Inicio"]?></h2>
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
            <textarea class="workmade-textarea" name="seHizo" disabled=""><?=(!empty($info)) ? $info["Actividades_realizadas"] : ""?></textarea>
        </fieldset>
        
        <?php if(!empty($info["Tecnico_firma"])): ?>
        <fieldset class="binnacleremindedfields__techsign-wrapper">
            <legend class="binnacleremindedfields__legend">Firma técnico</legend>
            <img class="sign-area" src="<?= base_url;?>finishing/uploads/firmas/<?=$info["Tecnico_firma"]?>"/>
            <div class="binnacleremindedfields__nameandsurname-area">
                <h3><?=$info["Nombre"]." ".$info["Apellidos"]?></h3>
            </div>
        </fieldset>
        <?php endif;?>

        <fieldset class="binnacleremindedfields__remark-wrapper" id="deviceBox">
            <legend class="binnacleremindedfields__legend">Observaciones</legend>
            <textarea class="remark-textarea" name="observaciones" disabled=""><?=(!empty($info)) ? $info["Observaciones"] : ""?></textarea>
        </fieldset>
        
        
        <input type="hidden" name="binnId" value="<?=$_GET["id"]?>"/>
        <input type="hidden" name="clientName" value="<?=$info["Nombre_completo"]?>"/>
        <input type="hidden" name="clientEntName" value="<?=$info["Nombre_comercial"]?>"/>
        <input type="hidden" name="userId" value="<?=$info["Usuario_id"]?>"/>
        <input type="hidden" name="userName" value="<?=$info["Nombre"]?>"/>
        <input type="hidden" name="userSurname" value="<?=$info["Apellidos"]?>"/>
        
        <div class="consent-buttonbox">
        <a class="binnacleremindedfields__consent-button-link" href="<?= base_url;?>home/?homeController=user&homeAction=followuplist">Regresar</a>
        <?php if(!empty($info) && !empty($info["Nombre_comercial"])): ?>
        <button class="consentcancel__button" id="consentCancelBtn" type="button">Cancelar</button>
        <input class="consentcancel__button" type="submit" value="Aceptar"/>
        <?php endif; ?>
        </div>
    </form>
    
</main>
