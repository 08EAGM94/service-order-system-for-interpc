<!-- este html es la vista de reportes de dispositivos; las vistas de contenido como este se utiliza la etiqueta <main>, si se quiere crear otras vistas es 
recomendable poner esta etiqueta como el padre de los elementos html, también se utiliza etiquetas PHP evaluando sesiones y variables inicializadas 
por los controladores en sus métodos de vistas, esto con el fin de determinar qué elementos html mostrar al usuario -->
<main class="devicesReport-main">
    
    <div class="searchForm-wrapper">
        <form class="searchForm" action="<?= base_url;?>home/?homeController=user&homeAction=devicesReport" method="POST">
            <div class="searchEntersForm__elterSelect-wrapper">
                <select class="js-example-placeholder-single" name="empresas" id="entersSelect">
                    <option></option>
                    <?php if (sizeof($enters) > 0): ?>
                        <?php foreach ($enters as $enter): ?>
                            <?php if(!empty($_SESSION["devicesReport_enterId"])):?>
                            <option value="<?= $enter["Id"]; ?>" <?= ($_SESSION["devicesReport_enterId"] === $enter["Id"]) ? 'selected' : '';?>><?= $enter["Nombre_comercial"]; ?> - <?= (!empty($enter["Razon_social"])) ? $enter["Razon_social"] : ''; ?></option>
                            <?php else:?>
                            <option value="<?= $enter["Id"]; ?>"><?= $enter["Nombre_comercial"]; ?> - <?= (!empty($enter["Razon_social"])) ? $enter["Razon_social"] : ''; ?></option>
                            <?php endif;?>
                        <?php endforeach; ?>
                    <?php endif; ?>       
                </select>
            </div>

            <input class="devicesReport__submit" type="submit" value="Buscar"/>
            <?php if(!empty($_SESSION["devicesReport_enterId"])):?>
                <?php if(sizeof($enter_devices) > 0):?> 
            <a class="devicesReport__pdf-button" href="<?=base_url;?>home/?homeController=user&homeAction=generateDevicesReport">PDF</a>
                <?php endif;?>
            <?php endif;?>
        </form>
    </div>
    
    <!-- Los controladores inicializan sesiones con strings que necesitan ser mostradas al usuario, se utilizan etiquetas PHP para evaluar 
    si existen estas sesiones, si existen, entonces se utiliza los valores de estas sesiones con un elemento html el cual muestra al usuario 
    el mensaje en forma de flags -->   
    <?php if (!empty($_SESSION["num_of_devicesEx"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["num_of_devicesEx"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["gettingEntersException"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["gettingEntersException"]; ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION["deviceReportException"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["deviceReportException"]; ?></div>
    <?php endif; ?>     
    <?php if (!empty($_SESSION["enterpriseInfoException"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["enterpriseInfoException"]; ?></div>
    <?php endif; ?>
   
        
    <?php if(!empty($_SESSION["devicesReport_enterId"])):?>
        <?php if(sizeof($enter_devices) > 0):?>    
    <div class="devicesReport__info-box" id="devicesReportInfoBox">
        
        <div class="devicesReport__head-wrapper">
            
            <div class="devicesReport__logo">
                <img class="interpc-logo" src="<?= base_url; ?>assets/img/logo.png"/>
            </div>
            
            <div class="devicesReport__reportTitle">
                <h1>Reporte de dispositivos</h1>
            </div>

        </div>
        
        <fieldset class="devicesReport__enterprise-wrapper">
            <legend class="devicesReport__legend">Empresa ID - <?=$enter_info["Id"]?></legend>

            <div class="devicesReport__first-half">
                <div class="devicesReport__labels-box">
                    <label class="devicesReport__label" for="nombreComercial">NOMBRE COMERCIAL:</label>
                    <label class="devicesReport__label" for="razonSocial">RAZÓN SOCIAL:</label>
                    <label class="devicesReport__label" for="calleYNumero">CALLE Y NÚMERO:</label>
                    <label class="devicesReport__label" for="entreCalles">ENTRE CALLES:</label>
                    <label class="devicesReport__label" for="dirigirseCon">DIRIGIRSE CON:</label>  
                </div>

                <div class="devicesReport__inputs-box">
                    <input class="devicesReport__input" type="text" id="nombreComercial" value="<?=$enter_info["Nombre_comercial"];?>" disabled=""/>
                    <input class="devicesReport__input" type="text" id="razonSocial" value="<?=(!empty($enter_info["Razon_social"])) ? $enter_info["Razon_social"] : "Sin asignar";?>" disabled=""/>
                    <input class="devicesReport__input" type="text" id="calleYNumero" value="<?=(!empty($enter_info["Calle_numero"])) ? $enter_info["Calle_numero"] : "Sin asignar";?>" disabled=""/>
                    <input class="devicesReport__input" type="text" id="entreCalles" value="<?=(!empty($enter_info["Entre_calles"])) ? $enter_info["Entre_calles"] : "Sin asignar";?>" disabled=""/>
                    <input class="devicesReport__input" type="text" id="dirigirseCon" value="<?=(!empty($enter_info["Dirigirse_con"])) ? $enter_info["Dirigirse_con"] : "Sin asignar";?>" disabled=""/>
                </div>
            </div>    

            <div class="devicesReport__second-half">
                <div class="devicesReport__labels-box">
                    <label class="devicesReport__label" for="telefonos">TELÉFONO(S):</label>
                    <label class="devicesReport__label" for="horario">HORARIO:</label>
                    <label class="devicesReport__label" for="atencion">ATENCIÓN:</label>
                    <label class="devicesReport__label" for="colonia" id="dceCol">COLONIA:</label>
                    <label class="devicesReport__label" for="localidad" id="dceLoc">LOCALIDAD:</label>
                    <label class="devicesReport__label" for="email" id="dceEml">E-MAIL:</label>
                </div>

                <div class="devicesReport__inputs-box">
                    <input class="devicesReport__input" type="text" id="telefonos" value="<?=$enter_info["Telefonos"];?>" disabled=""/>
                    <input class="devicesReport__input" type="text" id="horario" value="<?=(!empty($enter_info["Horario"])) ? $enter_info["Horario"] : "Sin asignar";?>" disabled=""/>
                    <input class="devicesReport__input" type="text" id="atencion" value="<?=(!empty($enter_info["Atencion"])) ? $enter_info["Atencion"] : "Sin asignar";?>" disabled=""/>
                    <input class="devicesReport__input" type="text" id="colonia" value="<?=(!empty($enter_info["Colonia"])) ? $enter_info["Colonia"] : "Sin asignar";?>" disabled=""/>
                    <input class="devicesReport__input" type="text" id="localidad" value="<?=(!empty($enter_info["Localidad"])) ? $enter_info["Localidad"] : "Sin asignar";?>" disabled=""/>
                    <input class="devicesReport__input" type="email" id="email" value="<?=(!empty($enter_info["Email"])) ? $enter_info["Email"] : "Sin asignar";?>" disabled=""/>
                </div>
            </div>

        </fieldset>

        <?php foreach($enter_devices as $device):?>
        <fieldset class="devicesReport__device-wrapper">
            <legend class="devicesReport__legend">Equipo ID - <?=$device["Id"]?></legend>
            <div class="devicesReport__labels-box">
                <label class="devicesReport__label" for="tipoEquipo">TIPO:</label>
                <label class="devicesReport__label" for="marca">MARCA:</label>
                <label class="devicesReport__label" for="modelo">MODELO:</label>
                <label class="devicesReport__label" for="ns" id="dceNs">No.SERIE:</label>
                <label class="devicesReport__label" for="numeroInventario" id="dceNumInv">No.INVENTARIO:</label>
            </div>
            <div class="devicesReport__inputs-box">
                <input class="devicesReport__input" type="text" id="tipoEquipo" value="<?=ucfirst($device['Tipo'])?>" disabled=""/>
                <input class="devicesReport__input" type="text" id="marca" value="<?=$device['Marca']?>" disabled=""/>
                <input class="devicesReport__input" type="text" id="modelo" value="<?=$device['Modelo']?>" disabled=""/>
                <input class="devicesReport__input" type="text" id="ns" value="<?=$device['Numero_serie']?>" disabled=""/>
                <input class="devicesReport__input" type="text" id="numeroInventario" value="<?=($device['Numero_inventario'] !== '0') ? $device['Numero_inventario'] : 'N/A'?>" disabled=""/>
            </div>
        </fieldset>
        <?php endforeach;?>
    </div>
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