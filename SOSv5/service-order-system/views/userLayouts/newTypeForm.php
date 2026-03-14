<!-- este html es la vista del formulario de registro de un tipo de equipo; las vistas de contenido como este se utiliza la etiqueta <main>, si se quiere crear otras vistas es 
recomendable poner esta etiqueta como el padre de los elementos html, también se utiliza etiquetas PHP evaluando sesiones y variables inicializadas 
por los controladores en sus métodos de vistas, esto con el fin de determinar qué elementos html mostrar al usuario -->
<main class="newTypeForm-main">
    
    <!-- Los controladores inicializan sesiones con strings que necesitan ser mostradas al usuario, se utilizan etiquetas PHP para evaluar 
    si existen estas sesiones, si existen, entonces se utiliza los valores de estas sesiones con un elemento html el cual muestra al usuario 
    el mensaje en forma de flags -->
    <?php if(!empty($_SESSION["insertTypeSucceed"])):?>
    <div class="succeed-box"><?=$_SESSION["insertTypeSucceed"]?></div>
    <?php endif; ?>
    
    <?php if(!empty($_SESSION["typeInsertionException"])):?>
    <div class="invalidinput-box"><?=$_SESSION["typeInsertionException"];?></div>
    <?php endif; ?>
    
    <?php if(!empty($_SESSION["typeFormErr"])):?>
        <?php foreach($_SESSION["typeFormErr"] as $err):?>
            <div class="invalidinput-box"><?=$err;?></div>
        <?php endforeach;?>    
    <?php endif;?>
    <form class="newTypeForm" action="<?= base_url;?>home/?homeController=user&homeAction=insertType" method="POST">
        <div class="registration__window-background hidThis" id="backWindow">
            <div class="registration__info-window" id="infoWindow">
                <div class="info-window__text-box"><h3>¿Estas seguro de registrar este tipo de equipo?</h3></div>
                <div class="info-window__selectbuttons-box">
                    <input class="selectbuttons-box__button" id="yes" type="submit" value="Si"/>
                    <button class="selectbuttons-box__button" id="no" type="button">No</button>
                </div>
            </div>
        </div>
        
        <fieldset class="newTypeForm__fieldset">
            <legend class="newTypeForm__legend">Tipo de equipo</legend>
            <table class="type-form-table" cellspacing="0">
                <tbody>
                <tr>
                    <td class="type-form-table__label-column">TIPO:</td>
                    <td class="type-form-table__input-column">
                        <input class="inputs-box__input unset-height" type="text" name="tipo" id="TypeFormField"/>
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