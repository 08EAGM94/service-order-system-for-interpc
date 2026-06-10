<!-- este html es la vista del formulario de registro de un tipo de equipo; las vistas de contenido como este se utiliza la etiqueta <main>, si se quiere crear otras vistas es 
recomendable poner esta etiqueta como el padre de los elementos html, también se utiliza etiquetas PHP evaluando sesiones y variables inicializadas 
por los controladores en sus métodos de vistas, esto con el fin de determinar qué elementos html mostrar al usuario -->
<main class="newDeviceForm-main">
    
    <!-- Los controladores inicializan sesiones con strings que necesitan ser mostradas al usuario, se utilizan etiquetas PHP para evaluar 
    si existen estas sesiones, si existen, entonces se utiliza los valores de estas sesiones con un elemento html el cual muestra al usuario 
    el mensaje en forma de flags -->
    <?php if(!empty($_SESSION["insertDeviceSucceed"])):?>
    <div class="succeed-box"><?=$_SESSION["insertDeviceSucceed"]?></div>
    <?php endif; ?>
    
    <?php if(!empty($_SESSION["dataForSelectDceFormEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["dataForSelectDceFormEx"];?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["deviceInsertionException"])):?>
    <div class="invalidinput-box"><?=$_SESSION["deviceInsertionException"];?></div>
    <?php endif; ?>
    
    <?php if(!empty($_SESSION["deviceFormErr"])):?>
        <?php foreach($_SESSION["deviceFormErr"] as $err):?>
            <div class="invalidinput-box"><?=$err;?></div>
        <?php endforeach;?>    
    <?php endif;?>
    <form class="newDeviceForm" action="<?= base_url;?>home/?homeController=user&homeAction=insertDevice" method="POST">
        <div class="registration__window-background hidThis" id="backWindow">
            <div class="registration__info-window" id="infoWindow">
                <div class="pop-up-window-icon"><img class="pop-up-window-icon__img" src="<?= base_url;?>assets/img/caution-sign_75243.png"/></div>
                <div class="info-window__text-box"><h3>¿Estas seguro de registrar este equipo?, la empresa y el tipo de equipo no se podrán cambiar después</h3></div>
                <div class="info-window__selectbuttons-box">
                    <input class="selectbuttons-box__button" id="yes" type="submit" value="Si"/>
                    <button class="selectbuttons-box__button" id="no" type="button">No</button>
                </div>
            </div>
        </div>
        
        <fieldset class="newDeviceForm__fieldset">
            <legend class="newDeviceForm__legend">Equipo</legend>
            
            <table class="device-form-table" cellspacing="0">
                <tbody id="newDeviceFormTbody">
                    <tr>
                        <td class="device-form-table__label-column">EMPRESA:</td>
                        <td class="device-form-table__input-column">
                            <select class="js-example-placeholder-single" name="empresas" id="newDeviceFormEntSelect">
                                <option></option>
                                <?php if(sizeof($enters) > 0):?>
                                <?php foreach($enters as $ent): ?>
                                <option value="<?= $ent["Id"] ?>"><?= $ent["Nombre_comercial"] ?> - <?= $ent["Razon_social"] ?></option>
                                <?php endforeach;?>
                                <?php endif;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="device-form-table__label-column">TIPO:</td>
                        <td class="device-form-table__input-column">
                            <select class="js-example-placeholder-single" name="tipos" id="newDeviceFormTypSelect">
                                <option></option>
                                <?php if(sizeof($types) > 0):?>
                                <?php foreach($types as $typ): ?>
                                <option value="<?=$typ["Id"]?>"><?=$typ["Tipo"]?></option>
                                <?php endforeach;?>
                                <?php endif;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="device-form-table__label-column">MARCA:</td>
                        <td class="device-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="marca" id="marca"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="device-form-table__label-column">MODELO:</td>
                        <td class="device-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="modelo" id="modelo"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="device-form-table__label-column">N.S:</td>
                        <td class="device-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="ns" id="ns"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="device-form-table__label-column">NO.INVENTARIO:</td>
                        <td class="device-form-table__input-column">
                            <input class="inputs-box__input" type="text" name="numeroInventario" id="numeroInventario"/>
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