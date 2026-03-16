<?php if($_GET["homeAction"] === "generateBinnacleReport"):?>
<?php ob_start();?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<div style="width: 100%; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;">
    <table style="width: 100%;
    border-collapse: collapse;" cellspacing="0">
        <tr>
            <td align="center" style="width: 15%; vertical-align: middle; border: 0;"><img src="<?=$logo_base64;?>" style="width: 100px;"/></td>
            <td style="border: 0;
                text-align: center;
                vertical-align: middle;">
                <strong>Bitacora de Visita a Clientes</strong><br/>
                <span style="font-weight: bolder;
                             font-style: italic;">INTERPC</span><br/>
                AAAA1234567BB<br/>
                Calle Rio Lerma #111 Fracc. Las Estrellas<br/>
                Irapuato, Gto.<br/>
                1234567890<br/>
                abcdefg@interpc.com<br/>
            </td>
            <td style="border: 0;
                text-align: center;
                vertical-align: middle;">
                <fieldset style="width: 90%;
                                 border: 2px solid black;">
                    <legend style="background: black;
                                    color: white;">Folio</legend>
                        <?= $binn_info["Id"];?>
                </fieldset>
                
                <fieldset style="width: 90%;
                                 border: 2px solid black;">
                    <legend style="background: black;
                                    color: white;">Fecha Inicio</legend>
                        <?= $binn_info["Inicio"];?>
                </fieldset>
                
                <fieldset style="width: 90%;
                                 border: 2px solid black;">
                    <legend style="background: black;
                                    color: white;">Fecha Fin</legend>
                        <?= (!empty($binn_info["Fin"])) ? $binn_info["Fin"] : '--/--/--';?>
                </fieldset>
            </td>
        </tr>
    </table>
    <?php if($binn_info["Estatus"] === "en proceso" || $binn_info["Estatus"] === "falta confirmar"):?>
    <table style="width: 100%;
    border-collapse: collapse;" cellspacing="0">
        <tr>
            <td style="border: 0;
                       width: 80%;
                       border-bottom: 1px solid black;"><?=($binn_info["Estatus"] === "en proceso") ? 'Responsable: '.$binn_info["Nombre"].' '.$binn_info["Apellidos"] : '';?></td>
            <td style="border: 0;
                      width: 20%;
                      border-bottom: 1px solid black;">Estatus: <?= $binn_info["Estatus"];?></td>
        </tr>
    </table>
    <?php endif;?>
    <fieldset style="width: 96%;
    border: 2px solid black; padding: 10px;
    border-radius: 5px;">
        <legend style="background: black;
    color: white;">CLIENTE</legend>
        <table style="width: 100%;
    border-collapse: collapse;" cellspacing="0">
            <tr>
                <td style="border: 0;
                            width: 15%;
                            vertical-align: bottom;">QUIÉN SOLICITA:</td>
                <td style="border: 0;
                            width: 35%;
                            border-bottom: 1px solid black;
                            vertical-align: bottom;"><?= $binn_info["Nombre_completo"];?></td>
                <td style="border: 0;
                            width: 10%;
                            vertical-align: bottom;">TELÉFONOS:</td>
                <td style="border: 0;
                            width: 40%;
                            border-bottom: 1px solid black;
                            vertical-align: bottom;"><?= $binn_info["Telefonos"];?></td>
            </tr>
            <tr>
                <td style="border: 0;
                            width: 15%;
                            vertical-align: bottom;">NOMBRE COMERCIAL:</td>
                <td style="border: 0;
                            width: 35%;
                            border-bottom: 1px solid black;
                            vertical-align: bottom;"><?= $binn_info["Nombre_comercial"];?></td>
                <td style="border: 0;
                            width: 10%;
                            vertical-align: bottom;">HORARIO:</td>
                <td style="border: 0;
                            width: 40%;
                            border-bottom: 1px solid black;
                            vertical-align: bottom;"><?= (!empty($binn_info["Horario"])) ? $binn_info["Horario"] : 'Sin asignar';?></td>
            </tr>
            <tr>
                <td style="border: 0;
                            width: 15%;
                            vertical-align: bottom;">RAZÓN SOCIAL:</td>
                <td style="border: 0;
                            width: 35%;
                            border-bottom: 1px solid black;
                            vertical-align: bottom;"><?= (!empty($binn_info["Razon_social"])) ? $binn_info["Razon_social"] : 'Sin asignar';?></td>
                <td style="border: 0;
                            width: 10%;
                            vertical-align: bottom;">ATENCIÓN:</td>
                <td style="border: 0;
                            width: 40%;
                            border-bottom: 1px solid black;
                            vertical-align: bottom;"><?= (!empty($binn_info["Atencion"])) ? $binn_info["Atencion"] : 'Sin asignar';?></td>
            </tr>
            <tr>
                <td style="border: 0;
                            width: 15%;
                            vertical-align: bottom;">CALLE Y NÚMERO:</td>
                <td style="border: 0;
                            width: 35%;
                            border-bottom: 1px solid black;
                            vertical-align: bottom;"><?= (!empty($binn_info["Calle_numero"])) ? $binn_info["Calle_numero"] : 'Sin asignar';?></td>
                <td style="border: 0;
                            width: 10%;
                            vertical-align: bottom;">COLONIA:</td>
                <td style="border: 0;
                            width: 40%;
                            border-bottom: 1px solid black;
                            vertical-align: bottom;"><?= (!empty($binn_info["Colonia"])) ? $binn_info["Colonia"] : 'Sin asignar';?></td>
            </tr>
            <tr>
                <td style="border: 0;
                            width: 15%;
                            vertical-align: bottom;">ENTRE CALLES:</td>
                <td style="border: 0;
                            width: 35%;
                            border-bottom: 1px solid black;
                            vertical-align: bottom;"><?= (!empty($binn_info["Entre_calles"])) ? $binn_info["Entre_calles"] : 'Sin asignar';?></td>
                <td style="border: 0;
                            width: 10%;
                            vertical-align: bottom;">LOCALIDAD:</td>
                <td style="border: 0;
                            width: 40%;
                            border-bottom: 1px solid black;
                            vertical-align: bottom;"><?= (!empty($binn_info["Localidad"])) ? $binn_info["Localidad"] : 'Sin asignar';?></td>
            </tr>
            <tr>
                <td style="border: 0;
                            width: 15%;
                            vertical-align: bottom;">DIRIGIRSE CON:</td>
                <td style="border: 0;
                            width: 35%;
                            border-bottom: 1px solid black;
                            vertical-align: bottom;"><?= (!empty($binn_info["Dirigirse_con"])) ? $binn_info["Dirigirse_con"] : 'Sin asignar';?></td>
                <td style="border: 0;
                            width: 10%;
                            vertical-align: bottom;">EMAIL:</td>
                <td style="border: 0;
                            width: 40%;
                            border-bottom: 1px solid black;
                            vertical-align: bottom;"><?= (!empty($binn_info["Email"])) ? $binn_info["Email"] : 'Sin asignar';?></td>
            </tr>
        </table>
    </fieldset>
    
    <?php if(!empty($binn_info["Servicio"])):?>    
    <fieldset style="width: 96%;
    border: 2px solid black; padding: 10px;
    border-radius: 5px; height: 90px">
        <legend style="background: black;
    color: white;">SERVICIO</legend>
            <?= $binn_info["Servicio"];?>
    </fieldset>
    <?php else:?>    
    <fieldset style="width: 96%;
    border: 2px solid black; padding: 10px;
    border-radius: 5px;">
        <legend style="background: black;
    color: white;">EQUIPO</legend>
        <table style="width: 100%;
    border-collapse: collapse;" cellspacing="0">
            <tr>
                <td style="border: 0;
                            width: 10%;
                            vertical-align: bottom;">TIPO:</td>
                <td style="border: 0;
                        width: 90%;
                        border-bottom: 1px solid black;
                        vertical-align: bottom;"><?= ucfirst($binn_info["Tipo"]);?></td>
            </tr>
            <tr>
                <td style="border: 0;
                            width: 10%;
                            vertical-align: bottom;">MARCA:</td>
                <td style="border: 0;
                        width: 90%;
                        border-bottom: 1px solid black;
                        vertical-align: bottom;"><?= $binn_info["Marca"];?></td>
            </tr>
            <tr>
                <td style="border: 0;
                            width: 10%;
                            vertical-align: bottom;">MODELO:</td>
                <td style="border: 0;
                        width: 90%;
                        border-bottom: 1px solid black;
                        vertical-align: bottom;"><?= $binn_info["Modelo"];?></td>
            </tr>
            <tr>
                <td style="border: 0;
                            width: 10%;
                            vertical-align: bottom;">N.S.:</td>
                <td style="border: 0;
                        width: 90%;
                        border-bottom: 1px solid black;
                        vertical-align: bottom;"><?= $binn_info["Numero_serie"];?></td>
            </tr>
            <tr>
                <td style="border: 0;
                            width: 10%;
                            vertical-align: bottom;">NO.INVENTARIO:</td>
                <td style="border: 0;
                        width: 90%;
                        border-bottom: 1px solid black;
                        vertical-align: bottom;"><?= (!empty($binn_info["Numero_inventario"])) ? $binn_info["Numero_inventario"] : 'N/A';?></td>
            </tr>
        </table>
    </fieldset>
    <?php endif;?>
    
    <?php if($binn_info["Estatus"] !== 'en proceso'):?>    
    <table style="width: 100%;
    border-collapse: collapse;" cellspacing="0">
        <tr>
            <td style="border: 0;
                width: 80%;
                vertical-align: top;">
                <fieldset style="width: 90%;
                            height: 250px;
                            border: 2px solid black; padding: 10px;
                            border-radius: 5px;">
                    <legend style="background: black;
                                color: white;"><?= ($binn_info["Estatus"] === 'cancelado') ? 'MOTIVO DE CANCELACIÓN' : 'ACTIVIDADES REALIZADAS';?></legend>
                        <?=($binn_info["Estatus"] === 'cancelado') ? $binn_info["Observaciones"]  : $binn_info["Actividades_realizadas"];?>
                </fieldset>
            </td>
            
            <td style="border: 0;
                    width: 20%;
                    vertical-align: bottom;">
                <fieldset style="width: 88%;
                            border: 2px solid black; padding: 10px;
                            border-radius: 5px;">
                    <legend style="background: black;
                                    color: white;">TÉCNICO</legend>
                    <img src="<?= (!empty($tech_base64)) ? $tech_base64 : $no_img_base64?>" style="width: 200px; height: 100px;"/>
                    <div style="text-align: center;
                                border-top: 1px solid black;">
                        <?= $binn_info["Nombre"].' '.$binn_info["Apellidos"];?>
                    </div>
                </fieldset>
            </td>
        </tr>
    </table>
    <?php if($binn_info["Estatus"] !== 'cancelado'):?>    
    <fieldset style="width: 96%;
                height: 120px;
                border: 2px solid black; padding: 10px;
                border-radius: 5px;">
        <legend style="background: black;
                        color: white;">OBSERVACIONES</legend>
            <?= (!empty($binn_info["Observaciones"])) ? $binn_info["Observaciones"] : 'Sin observaciones...';?>
    </fieldset>
    <?php endif;?>    
    <?php endif;?>
    
    <table style="width: 100%;
    border-collapse: collapse;" cellspacing="0">
        <tr>
            <td style="border: 0;
                        width: 30%;
                        vertical-align: top;">
                <?php if($binn_info["Estatus"] === 'finalizado'):?>
                <fieldset style="width: 90%;
                            border: 2px solid black; padding: 10px;
                            border-radius: 5px;">
                    <legend style="background: black;
                            color: white;">CONFORMIDAD</legend>
                    <img src="<?= (!empty($cli_base64)) ? $cli_base64 : $no_img_base64?>" style="width: 200px; height: 100px;"/>
                    <div style="text-align: center;
                                border-top: 1px solid black;">
                        <?= $binn_info["Nombre_completo"];?>
                    </div>
                </fieldset>
                <?php endif;?>
            </td>
            
            <td style="border: 0;
                width: 70%;
                vertical-align: middle;">
                <?php if(!empty($binn_info["Monto"])):?>
                <fieldset style="width: 95%;
                                border: 2px solid black; padding: 10px;
                                border-radius: 5px;">
                    <legend style="background: black;
                                    color: white;">MONTO</legend>
                    <table style="width: 100%;
                        border-collapse: collapse;" cellspacing="0">
                        <tr>
                            <td style="border: 0;
                                        width: 10%;
                                        vertical-align: bottom;">PRECIO BASE:</td>
                            <td style="border: 0;
                                    width: 90%;
                                    border-bottom: 1px solid black;
                                    vertical-align: bottom;">$ <?= $binn_info["Monto"];?></td>
                        </tr>
                        <tr>
                            <td style="border: 0;
                                        width: 10%;
                                        vertical-align: bottom;">PRECIO+IVA:</td>
                            <td style="border: 0;
                                    width: 90%;
                                    border-bottom: 1px solid black;
                                    vertical-align: bottom;">$ <?= $with_iva?></td>
                        </tr>
                    </table>                
                </fieldset>
                <?php endif;?>
            </td>
        </tr>
    </table>
</div>
<?php
$html = ob_get_clean();
$options = new Dompdf\Options();
/*es importante inicializar el indice "isRemoteEnabled" en true para poder incluir imagenes de nuestro proyecto con la etiqueta img y el src poniendo la respectiva 
 * ruta de la imagen*/
// Forzar el uso de Imagick (que ya instalamos en el Dockerfile)
$options->set('isPhpEnabled', true);
$domPdf_obj = new Dompdf\Dompdf($options);
$domPdf_obj->loadHtml($html);
$domPdf_obj->setPaper('A4', 'portrait');
$domPdf_obj->render();
/*se añade al indice "Attachment" el valor false para evitar la descarga automatica del pdf*/
$domPdf_obj->stream("reporte_bitacora_folio_".$binn_info["Id"].".pdf", ['Attachment' => false]);?>
<!-- este html tiene tres if, si la clave get "homeAction" es igual "generateBinnacleReport" entonces se activa la vista del lienzo de una 
bitácora para ser convertida en PDF (la dependencia DomPdf requiere que el html sea extremadamente simple, por eso no se usan clases y se 
ponen los estilos directamente en las etiquetas html); si la clave get "homeAction" es igual "showBinnacle" entonces se activa la vista del lienzo 
de una Bitacora para mostrar su información al usuario, si la clave get "homeAction" es igual "editBinnacle" entonces se activa la vista del 
formulario de edición de una bitácora; también se utiliza etiquetas PHP evaluando sesiones y variables inicializadas 
por los controladores en sus métodos de vistas, esto con el fin de determinar qué elementos html mostrar al usuario -->
<?php endif;?>
<?php if($_GET["homeAction"] === "showBinnacle"):?>
<main class="binnacleInfoCanvas-main">
    <div class="binnacleInfoCanvas__binnacle-wrapper">
    <table class="binnacleInfoCanvas__binn-title-table" cellspacing="0">
        <tr>
            <td align="center" style="width: 15%; vertical-align: middle; border: 0;"><img src="<?= base_url;?>assets/img/logo.png" style="width: 100px;"/></td>
            <td class="binn-title-table__info-column">
                <strong>Bitacora de Visita a Clientes</strong><br/>
                <span class="info-column__enterprise-name">INTERPC</span><br/>
                FAOS7612268MA<br/>
                Calle San Isidro #936 Fracc. San Roque<br/>
                Irapuato, Gto.<br/>
                4626249165<br/>
                sfajardo@interpc.mx<br/>
            </td>
            <td class="binn-title-table__fieldset-column">
                <fieldset class="binn-title-table__fieldset">
                    <legend class="binn-title-table__legend">Folio</legend>
                        <?= $binn_info["Id"];?>
                </fieldset>
                
                <fieldset class="binn-title-table__fieldset">
                    <legend class="binn-title-table__legend">Fecha Inicio</legend>
                        <?= $binn_info["Inicio"];?>
                </fieldset>
                
                <fieldset class="binn-title-table__fieldset">
                    <legend class="binn-title-table__legend">Fecha Fin</legend>
                        <?= (!empty($binn_info["Fin"])) ? $binn_info["Fin"] : '--/--/--';?>
                </fieldset>
            </td>
        </tr>
    </table>
    
    <?php if($binn_info["Estatus"] === "en proceso" || $binn_info["Estatus"] === "falta confirmar"):?>    
    <table class="binnacleInfoCanvas__status-table" cellspacing="0">
        <tr>
            <td class="status-table__responsable-column"><?=($binn_info["Estatus"] === "en proceso") ? 'Responsable: '.$binn_info["Nombre"].' '.$binn_info["Apellidos"] : '';?></td>
            <td class="status-table__status-column">Estatus: <?= $binn_info["Estatus"];?></td>
        </tr>
    </table>
    <?php endif;?>    
    
    <fieldset class="binnacleInfoCanvas__client-table-wrapper">
        <legend class="client-table-wrapper__legend">CLIENTE</legend>
        <table class="binnacleInfoCanvas__client-table" cellspacing="0">
            <tr>
                <td class="client-table__label-column">QUIÉN SOLICITA:</td>
                <td class="client-table__field-column"><?= $binn_info["Nombre_completo"];?></td>
                <td class="client-table__label-column">TELÉFONOS:</td>
                <td class="client-table__field-column"><?= $binn_info["Telefonos"];?></td>
            </tr>
            <tr>
                <td class="client-table__label-column">NOMBRE COMERCIAL:</td>
                <td class="client-table__field-column"><?= $binn_info["Nombre_comercial"];?></td>
                <td class="client-table__label-column">HORARIO:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Horario"])) ? $binn_info["Horario"] : 'Sin asignar';?></td>
            </tr>
            <tr>
                <td class="client-table__label-column">RAZÓN SOCIAL:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Razon_social"])) ? $binn_info["Razon_social"] : 'Sin asignar';?></td>
                <td class="client-table__label-column">ATENCIÓN:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Atencion"])) ? $binn_info["Atencion"] : 'Sin asignar';?></td>
            </tr>
            <tr>
                <td class="client-table__label-column">CALLE Y NÚMERO:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Calle_numero"])) ? $binn_info["Calle_numero"] : 'Sin asignar';?></td>
                <td class="client-table__label-column">COLONIA:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Colonia"])) ? $binn_info["Colonia"] : 'Sin asignar';?></td>
            </tr>
            <tr>
                <td class="client-table__label-column">ENTRE CALLES:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Entre_calles"])) ? $binn_info["Entre_calles"] : 'Sin asignar';?></td>
                <td class="client-table__label-column">LOCALIDAD:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Localidad"])) ? $binn_info["Localidad"] : 'Sin asignar';?></td>
            </tr>
            <tr>
                <td class="client-table__label-column">DIRIGIRSE CON:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Dirigirse_con"])) ? $binn_info["Dirigirse_con"] : 'Sin asignar';?></td>
                <td class="client-table__label-column">EMAIL:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Email"])) ? $binn_info["Email"] : 'Sin asignar';?></td>
            </tr>
        </table>
    </fieldset>
    
    <?php if(!empty($binn_info["Servicio"])):?>    
    <fieldset class="binnacleInfoCanvas__service-desc-wrapper">
        <legend class="binnacleInfoCanvas__service-legend">SERVICIO</legend>
            <?= $binn_info["Servicio"];?>
    </fieldset>
    <?php else:?>    
    <fieldset class="binnacleInfoCanvas__device-table-wrapper">
        <legend class="binnacleInfoCanvas__device-legend">EQUIPO</legend>
        <table class="binnacleInfoCanvas__device-table" cellspacing="0">
            <tr>
                <td class="device-table__label-column">TIPO:</td>
                <td class="device-table__field-column"><?= $binn_info["Tipo"];?></td>
            </tr>
            <tr>
                <td class="device-table__label-column">MARCA:</td>
                <td class="device-table__field-column"><?= $binn_info["Marca"];?></td>
            </tr>
            <tr>
                <td class="device-table__label-column">MODELO:</td>
                <td class="device-table__field-column"><?= $binn_info["Modelo"];?></td>
            </tr>
            <tr>
                <td class="device-table__label-column">N.S.:</td>
                <td class="device-table__field-column"><?= $binn_info["Numero_serie"];?></td>
            </tr>
            <tr>
                <td class="device-table__label-column">NO.INVENTARIO:</td>
                <td class="device-table__field-column"><?= (!empty($binn_info["Numero_inventario"])) ? $binn_info["Numero_inventario"] : 'N/A';?></td>
            </tr>
        </table>
    </fieldset>
    <?php endif;?>
        
    <?php if($binn_info["Estatus"] !== 'en proceso'):?>    
    <table class="binnacleInfoCanvas__binn-tecnician-table" cellspacing="0">
        <tr>
            <td class="binn-tecnician-table__activities-column">
                <fieldset class="activities-column__fieldset">
                    <legend class="activities-column__legend"><?= ($binn_info["Estatus"] === 'cancelado') ? 'MOTIVO DE CANCELACIÓN' : 'ACTIVIDADES REALIZADAS';?></legend>
                        <?=($binn_info["Estatus"] === 'cancelado') ? $binn_info["Observaciones"]  : $binn_info["Actividades_realizadas"];?>
                </fieldset>
            </td>
            
            <td class="binn-tecnician-table__sign-column">
                <fieldset class="sign-column__fieldset">
                    <legend class="sign-column__legend">TÉCNICO</legend>
                    <img src="<?= (!empty($binn_info["Tecnico_firma"])) ? base_url.'finishing/uploads/firmas/'.$binn_info["Tecnico_firma"]."?nocache=".time() : base_url.'assets/img/no-image-icon-23494.png';?>" style="width: 100%; height: 100px;"/>
                    <div class="sign-column__tech-sign">
                        <?= $binn_info["Nombre"].' '.$binn_info["Apellidos"];?>
                    </div>
                </fieldset>
            </td>
        </tr>
    </table>
    <?php if($binn_info["Estatus"] !== 'cancelado'):?>    
    <fieldset class="binnacleInfoCanvas__hints-desc-wrapper">
        <legend class="binnacleInfoCanvas__hints-legend">OBSERVACIONES</legend>
            <?= (!empty($binn_info["Observaciones"])) ? $binn_info["Observaciones"] : 'Sin observaciones...';?>
    </fieldset>
    <?php endif;?>    
    <?php endif;?>
        
    <table class="binnacleInfoCanvas__binn-final-table" cellspacing="0">
        <tr>
            <td class="binn-final-table__sign-column">
                <?php if($binn_info["Estatus"] === 'finalizado'):?>
                <fieldset class="sign-column__fieldset">
                    <legend class="sign-column__legend">CONFORMIDAD</legend>
                    <img src="<?= (!empty($binn_info["Firma_cliente"])) ? base_url.'finishing/uploads/firmas/'.$binn_info["Firma_cliente"] : base_url.'assets/img/no-image-icon-23494.png';?>" style="width: 100%; height: 100px;"/>
                    <div class="sign-column__client-sign">
                        <?= $binn_info["Nombre_completo"];?>
                    </div>
                </fieldset>
                <?php endif;?>
            </td>
            
            <td class="binn-final-table__price-column">
                <?php if(!empty($binn_info["Monto"])):?>
                <fieldset class="price-column__fieldset">
                    <legend class="price-column__legend">MONTO</legend>
                    <table class="binnacleInfoCanvas__device-table" cellspacing="0">
                        <tr>
                            <td class="device-table__label-column">PRECIO BASE:</td>
                            <td class="device-table__field-column">$ <?= $binn_info["Monto"];?></td>
                        </tr>
                        <tr>
                            <td class="device-table__label-column">PRECIO+IVA:</td>
                            <td class="device-table__field-column">$ <?= $with_iva?></td>
                        </tr>
                    </table>
                </fieldset>
                <?php endif;?>
            </td>
        </tr>
    </table>
    </div>    
    <div class="binnacleInfoCanvas__pdf-btn-box">
        <div class="pdf-btn-box__link-box">
        <a class="binnacle-data-table__binn-pdf-link" href="<?= base_url;?>home/?homeController=user&homeAction=generateBinnacleReport&homeId=<?= $binn_info["Id"];?>">PDF</a>
        </div>
    </div>
</main>
<?php endif; ?>
<?php if($_GET["homeAction"] === "editBinnacle"):?>
<main class="binnacleInfoCanvas-main">
    <div class="binnacleInfoCanvas__binnacle-wrapper">
    <!-- Los controladores inicializan sesiones con strings que necesitan ser mostradas al usuario, se utilizan etiquetas PHP para evaluar 
    si existen estas sesiones, si existen, entonces se utiliza los valores de estas sesiones con un elemento html el cual muestra al usuario 
    el mensaje en forma de flags -->    
    <?php if (!empty($_SESSION["binnacleUpdateSucceed"])): ?>
        <div class="succeed-box"><?= $_SESSION["binnacleUpdateSucceed"]; ?></div>
    <?php endif; ?>    
    <form action="<?= base_url;?>home/?homeController=user&homeAction=updateBinnacleInfo" method="POST">
    <div class="binnacle-edit__background hidThis" id="binnEditConfirmationBackground">
        <div class="binnacle-edit__info-window">
            <div class="info-window__text-box"><h3>¿Está seguro de editar esta bitácora?, confirme su contraseña antes de continuar</h3></div>
            <input type="hidden" name="bitacoraId" value="<?= $binn_info["Id"];?>"/>
            <input type="hidden" name="estatus" value="<?= $binn_info["Estatus"];?>"/>
            <input class="adminpwdfield" type="password" name="adminContrasena"/>
            <div class="info-window__selectbuttons-box">
                <input class="selectbuttons-box__button" type="submit" value="Guardar"/>
                <button class="selectbuttons-box__button" id="binnEditCancelBtn" type="button">Cancelar</button>
            </div>    
        </div>
    </div>    
    <table class="binnacleInfoCanvas__binn-title-table" cellspacing="0">
        <tr>
            <td align="center" style="width: 15%; vertical-align: middle; border: 0;"><img src="<?= base_url;?>assets/img/logo.png" style="width: 100px;"/></td>
            <td class="binn-title-table__info-column">
                <strong>Bitacora de Visita a Clientes</strong><br/>
                <span class="info-column__enterprise-name">INTERPC</span><br/>
                FAOS7612268MA<br/>
                Calle San Isidro #936 Fracc. San Roque<br/>
                Irapuato, Gto.<br/>
                4626249165<br/>
                sfajardo@interpc.mx<br/>
            </td>
            <td class="binn-title-table__fieldset-column">
                <fieldset class="binn-title-table__fieldset">
                    <legend class="binn-title-table__legend">Folio</legend>
                        <?= $binn_info["Id"];?>
                </fieldset>
                
                <fieldset class="binn-title-table__fieldset">
                    <legend class="binn-title-table__legend">Fecha Inicio</legend>
                    <input class="binn-title-table__date-input" type="date" name="fechaInicio" value="<?= $binn_info["Inicio"];?>"/>
                </fieldset>
                
                <fieldset class="binn-title-table__fieldset">
                    <legend class="binn-title-table__legend">Fecha Fin</legend>
                        <?= (!empty($binn_info["Fin"])) ? "<input class='binn-title-table__date-input' type='date' name='fechaFin' value='".$binn_info["Fin"]."'/>" : '--/--/--';?>
                </fieldset>
            </td>
        </tr>
    </table>
    
    <?php if($binn_info["Estatus"] === "en proceso" || $binn_info["Estatus"] === "falta confirmar"):?>    
    <table class="binnacleInfoCanvas__status-table" cellspacing="0">
        <tr>
            <td class="status-table__responsable-column"><strong>Responsable:</strong> 
                <select class="js-example-placeholder-single" name="usuario" id="editBinnUserSelect">
                    <option></option>
                    <?php if (sizeof($usuarios) > 0): ?>
                        <?php foreach ($usuarios as $usr): ?>
                            <option value="<?=$usr["Id"]?>" <?= ($binn_info["Usuario_id"] === $usr["Id"]) ? 'selected' : '' ?>><?= $usr["Nombre"].' '.$usr["Apellidos"]?> - <?= $usr["Alias"] ?></option>
                        <?php endforeach; ?>
                    <?php endif;?> 
                </select>
            </td>
            <td class="status-table__status-column"><strong><?= $binn_info["Estatus"];?></strong></td>
        </tr>
    </table>
    <?php endif;?>    
    
    <fieldset class="binnacleInfoCanvas__client-table-wrapper">
        <legend class="client-table-wrapper__legend">CLIENTE</legend>
        <table class="binnacleInfoCanvas__client-table" cellspacing="0">
            <tr>
                <td class="client-table__label-column">QUIÉN SOLICITA:</td>
                <td class="client-table__field-column"><?= $binn_info["Nombre_completo"];?></td>
                <td class="client-table__label-column">TELÉFONOS:</td>
                <td class="client-table__field-column"><?= $binn_info["Telefonos"];?></td>
            </tr>
            <tr>
                <td class="client-table__label-column">NOMBRE COMERCIAL:</td>
                <td class="client-table__field-column"><?= $binn_info["Nombre_comercial"];?></td>
                <td class="client-table__label-column">HORARIO:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Horario"])) ? $binn_info["Horario"] : 'Sin asignar';?></td>
            </tr>
            <tr>
                <td class="client-table__label-column">RAZÓN SOCIAL:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Razon_social"])) ? $binn_info["Razon_social"] : 'Sin asignar';?></td>
                <td class="client-table__label-column">ATENCIÓN:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Atencion"])) ? $binn_info["Atencion"] : 'Sin asignar';?></td>
            </tr>
            <tr>
                <td class="client-table__label-column">CALLE Y NÚMERO:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Calle_numero"])) ? $binn_info["Calle_numero"] : 'Sin asignar';?></td>
                <td class="client-table__label-column">COLONIA:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Colonia"])) ? $binn_info["Colonia"] : 'Sin asignar';?></td>
            </tr>
            <tr>
                <td class="client-table__label-column">ENTRE CALLES:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Entre_calles"])) ? $binn_info["Entre_calles"] : 'Sin asignar';?></td>
                <td class="client-table__label-column">LOCALIDAD:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Localidad"])) ? $binn_info["Localidad"] : 'Sin asignar';?></td>
            </tr>
            <tr>
                <td class="client-table__label-column">DIRIGIRSE CON:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Dirigirse_con"])) ? $binn_info["Dirigirse_con"] : 'Sin asignar';?></td>
                <td class="client-table__label-column">EMAIL:</td>
                <td class="client-table__field-column"><?= (!empty($binn_info["Email"])) ? $binn_info["Email"] : 'Sin asignar';?></td>
            </tr>
        </table>
    </fieldset>
    
    <?php if(!empty($binn_info["Servicio"])):?>    
    <fieldset class="binnacleInfoCanvas__service-desc-wrapper">
        <legend class="binnacleInfoCanvas__service-legend">SERVICIO</legend>
        <textarea class="binnacleInfoCanvas__service-input" name="servicio"><?= $binn_info["Servicio"];?></textarea> 
    </fieldset>
    <?php else:?>    
    <fieldset class="binnacleInfoCanvas__device-table-wrapper">
        <legend class="binnacleInfoCanvas__device-legend">EQUIPO</legend>
        <table class="binnacleInfoCanvas__device-table" cellspacing="0">
            <tr>
                <td class="device-table__label-column">TIPO:</td>
                <td class="device-table__field-column"><?= $binn_info["Tipo"];?></td>
            </tr>
            <tr>
                <td class="device-table__label-column">MARCA:</td>
                <td class="device-table__field-column"><?= $binn_info["Marca"];?></td>
            </tr>
            <tr>
                <td class="device-table__label-column">MODELO:</td>
                <td class="device-table__field-column"><?= $binn_info["Modelo"];?></td>
            </tr>
            <tr>
                <td class="device-table__label-column">N.S.:</td>
                <td class="device-table__field-column"><?= $binn_info["Numero_serie"];?></td>
            </tr>
            <tr>
                <td class="device-table__label-column">NO.INVENTARIO:</td>
                <td class="device-table__field-column"><?= (!empty($binn_info["Numero_inventario"])) ? $binn_info["Numero_inventario"] : 'N/A';?></td>
            </tr>
        </table>
    </fieldset>
    <?php endif;?>
        
    <?php if($binn_info["Estatus"] !== 'en proceso'):?>    
    <table class="binnacleInfoCanvas__binn-tecnician-table" cellspacing="0">
        <tr>
            <td class="binn-tecnician-table__activities-column">
                <fieldset class="activities-column__fieldset">
                    <legend class="activities-column__legend"><?= ($binn_info["Estatus"] === 'cancelado') ? 'MOTIVO DE CANCELACIÓN' : 'ACTIVIDADES REALIZADAS';?></legend>
                    <textarea class="activities-column__input" name="<?= ($binn_info["Estatus"] === 'cancelado') ? 'motivoCancelacion' : 'seHizo';?>"><?=($binn_info["Estatus"] === 'cancelado') ? $binn_info["Observaciones"]  : $binn_info["Actividades_realizadas"];?></textarea> 
                </fieldset>
            </td>
            
            <td class="binn-tecnician-table__sign-column">
                <fieldset class="sign-column__fieldset">
                    <legend class="sign-column__legend">TÉCNICO</legend>
                    <img src="<?= (!empty($binn_info["Tecnico_firma"])) ? base_url.'finishing/uploads/firmas/'.$binn_info["Tecnico_firma"]."?nocache=".time() : base_url.'assets/img/no-image-icon-23494.png';?>" style="width: 100%; height: 100px;"/>
                    <div class="sign-column__tech-sign">
                        <?= $binn_info["Nombre"].' '.$binn_info["Apellidos"];?>
                    </div>
                </fieldset>
            </td>
        </tr>
    </table>
    <?php if($binn_info["Estatus"] !== 'cancelado'):?>    
    <fieldset class="binnacleInfoCanvas__hints-desc-wrapper">
        <legend class="binnacleInfoCanvas__hints-legend">OBSERVACIONES</legend>
        <textarea class="binnacleInfoCanvas__hints-input" name="observaciones"><?= (!empty($binn_info["Observaciones"])) ? $binn_info["Observaciones"] : 'Sin observaciones...';?></textarea>    
    </fieldset>
    <?php endif;?>    
    <?php endif;?>
        
    <table class="binnacleInfoCanvas__binn-final-table" cellspacing="0">
        <tr>
            <td class="binn-final-table__sign-column">
                <?php if($binn_info["Estatus"] === 'finalizado'):?>
                <fieldset class="sign-column__fieldset">
                    <legend class="sign-column__legend">CONFORMIDAD</legend>
                    <img src="<?= (!empty($binn_info["Firma_cliente"])) ? base_url.'finishing/uploads/firmas/'.$binn_info["Firma_cliente"] : base_url.'assets/img/no-image-icon-23494.png';?>" style="width: 100%; height: 100px;"/>
                    <div class="sign-column__client-sign">
                        <?= $binn_info["Nombre_completo"];?>
                    </div>
                </fieldset>
                <?php endif;?>
            </td>
            
            <td class="binn-final-table__price-column">
                <?php if($binn_info["Estatus"] !== 'cancelado'):?>
                <fieldset class="price-column__fieldset">
                    <legend class="price-column__legend">MONTO</legend>
                    <table class="binnacleInfoCanvas__device-table" cellspacing="0">
                        <tr>
                            <td class="device-table__label-column">PRECIO BASE: $</td>
                            <td class="device-table__field-column"><input class="price-column__input" type="text" name="precio" value="<?= (!empty($binn_info["Monto"])) ? $binn_info["Monto"] : '';?>"/></td>
                        </tr>
                    </table>
                </fieldset>
                <?php endif;?>
            </td>
        </tr>
    </table>
    </form>    
    </div>    
    <div class="binnacleInfoCanvas__pdf-btn-box">
        <div class="pdf-btn-box__link-box">
        <button class="binnacle-data-table__binn-edit-link" type="button" id="editBinnacleBtn">GUARDAR</button>
        </div>
    </div>
    <!-- generalmente, las vistas que muestran mensajes flags tienen una etiqueta PHP donde se utiliza el método estático unsetFlagsSessions el cual elimina las 
sesiones de mensajes de errores, excepciones y de exito en un proceso -->
    <?php Utils::unsetFlagsSessions();?>
</main>
<?php endif;?>