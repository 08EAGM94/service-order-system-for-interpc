<!-- este html es la vista de seguimiento de bitácoras; las vistas de contenido como este se utiliza la etiqueta <main>, si se quiere crear otras vistas es 
recomendable poner esta etiqueta como el padre de los elementos html, también se utiliza etiquetas PHP evaluando sesiones y variables inicializadas 
por los controladores en sus métodos de vistas, esto con el fin de determinar qué elementos html mostrar al usuario -->
<main class="followup-main">
    
    <!-- Los controladores inicializan sesiones con strings que necesitan ser mostradas al usuario, se utilizan etiquetas PHP para evaluar 
    si existen estas sesiones, si existen, entonces se utiliza los valores de estas sesiones con un elemento html el cual muestra al usuario 
    el mensaje en forma de flags -->
    <?php if(!empty($_SESSION["resetActivitiesIdEvaluationEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["resetActivitiesIdEvaluationEx"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["followupExeption"])):?>
    <div class="invalidinput-box"><?=$_SESSION["followupExeption"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["followupCancelExeption"])):?>
    <div class="invalidinput-box"><?=$_SESSION["followupCancelExeption"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["resetActivitiesException"])):?>
    <div class="invalidinput-box"><?=$_SESSION["resetActivitiesException"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["binnFinishingException"])):?>
    <div class="invalidinput-box"><?=$_SESSION["binnFinishingException"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["unlinkClientSignEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["unlinkClientSignEx"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["clientSignInsertException"])):?>
    <div class="invalidinput-box"><?=$_SESSION["clientSignInsertException"]?></div>
    <?php endif; ?>
    
    
    <?php if(!empty($_SESSION["binnSignsInsertSucceed"])):?>
    <div class="succeed-box"><?=$_SESSION["binnSignsInsertSucceed"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["followupSucceed"])):?>
    <div class="succeed-box"><?=$_SESSION["followupSucceed"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["followUpCancelSucceed"])):?>
    <div class="succeed-box"><?=$_SESSION["followUpCancelSucceed"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["binnFinishingsucceed"])):?>
    <div class="succeed-box"><?=$_SESSION["binnFinishingsucceed"]?></div>
    <?php endif; ?>
    
    
    <?php if($num_rows > 0): ?>
    <div class="main__numkey-box" id="numkeyBox">
        Número de elementos en pantalla:
        <select class="numkey-box__select" name="pagElem" id="numkeySelect">
            <option value="1" <?=($page_elem === 1) ? "selected" : ""?>>1</option>
            <option value="5" <?=($page_elem === 5) ? "selected" : ""?>>5</option>
            <option value="10" <?=($page_elem === 10) ? "selected" : ""?>>10</option>
        </select>
        <?php if(empty($_SESSION["isAdmin"])):?>
        <a class="numkey-box__return-link" href="<?= base_url; ?>home/">Regresar</a>
        <?php endif;?>
    </div>
    
    <div id="linksArea">
    <?php while ($binn = $stmt_binns->fetch()):?>    
    <a class="binn-row" href="<?= base_url;?>finishing/?controller=form&action=index&id=<?=$binn["Id"];?>">
        <div class="binn-row__id">Id - <?=$binn["Id"];?></div>
        <div class="binn-row__comm-name"><?=$binn["Nombre_comercial"];?></div>
        <div class="binn-row__status">
            <div class="status-img"><img src="<?= base_url;?>assets/img/alert_16750344.png"/></div>
            <?=($binn["Estatus"] === "falta confirmar") ? $binn["Estatus"] : "Sin terminar"?>
        </div>
    </a>
    <?php endwhile;?>    
    </div>
    <div class="follow-up__pagination-control-box" id="paginationBox">    
    <?php $pagination->render();?>
    </div>
    <?php else: ?>
    <div class="withoutPendingBinns">
        <h1>¡No tienes ninguna bitacora pendiente!</h1>
        <?php if(empty($_SESSION["isAdmin"])):?>
        <a class="numkey-box__return-link increseHeight" href="<?= base_url; ?>home/">Regresar</a>
        <?php endif;?>
    </div>
    <?php endif; ?>
</main>
<!-- generalmente, las vistas que muestran mensajes flags tienen una etiqueta PHP donde se utiliza el método estático unsetFlagsSessions el cual elimina las 
sesiones de mensajes de errores, excepciones y de exito en un proceso -->
<?php Utils::unsetFlagsSessions();?>
<?php Utils::unsetIdSessionsOfSearchForms();?>
