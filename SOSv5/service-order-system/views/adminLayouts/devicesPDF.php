<?php ob_start();?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<div style="width: 100%; font-family: Verdana, Arial, Helvetica, sans-serif;"> 
        <table cellspacing="0" style="width: 100%; margin-bottom: 20px;">
            <tr>
            
            <td style="width: 40%;">
                <img src="<?=$logo_base64;?>" style="width: 90px;"/>
            </td>
            
            <td style="width: 60%;">
                <h1 style="font-size: 25px;">Reporte de dispositivos</h1>
            </td>

            </tr>
            
        </table>
        <fieldset style="width: 95%; border: 2px solid black; border-radius: 5px;">
            <legend style="background: black; color: white; font-size: 15px;">Empresa ID - <?=$enter_info["Id"];?></legend>
        <table cellspacing="0" style="width: 100%; font-size: 13px;">
                <tr>
                    <td style="width: 20%;">NOMBRE COMERCIAL:</td>
                    <td style="width: 80%; border-bottom: 1px solid black;"><?= $enter_info["Nombre_comercial"];?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">RAZÓN SOCIAL:</td>
                    <td style="width: 80%; border-bottom: 1px solid black;"><?=(!empty($enter_info["Razon_social"])) ? $enter_info["Razon_social"] : "Sin asignar";?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">CALLE Y NÚMERO:</td>
                    <td style="width: 80%; border-bottom: 1px solid black;"><?=(!empty($enter_info["Calle_numero"])) ? $enter_info["Calle_numero"] : "Sin asignar";?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">ENTRE CALLES:</td>
                    <td style="width: 80%; border-bottom: 1px solid black;"><?=(!empty($enter_info["Entre_calles"])) ? $enter_info["Entre_calles"] : "Sin asignar";?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">DIRIGIRSE CON:</td>
                    <td style="width: 80%; border-bottom: 1px solid black;"><?=(!empty($enter_info["Dirigirse_con"])) ? $enter_info["Dirigirse_con"] : "Sin asignar";?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">TELÉFONO(S):</td>
                    <td style="width: 80%; border-bottom: 1px solid black;"><?=$enter_info["Telefonos"];?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">HORARIO:</td>
                    <td style="width: 80%; border-bottom: 1px solid black;"><?=(!empty($enter_info["Horario"])) ? $enter_info["Horario"] : "Sin asignar";?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">ATENCIÓN:</td>
                    <td style="width: 80%; border-bottom: 1px solid black;"><?=(!empty($enter_info["Atencion"])) ? $enter_info["Atencion"] : "Sin asignar";?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">COLONIA:</td>
                    <td style="width: 80%; border-bottom: 1px solid black;"><?=(!empty($enter_info["Colonia"])) ? $enter_info["Colonia"] : "Sin asignar";?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">LOCALIDAD:</td>
                    <td style="width: 80%; border-bottom: 1px solid black;"><?=(!empty($enter_info["Localidad"])) ? $enter_info["Localidad"] : "Sin asignar";?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">E-MAIL:</td>
                    <td style="width: 80%; border-bottom: 1px solid black;"><?=(!empty($enter_info["Email"])) ? $enter_info["Email"] : "Sin asignar";?></td>
                </tr>
        </table>
        </fieldset>    
        <?php foreach($enter_devices as $device):?>
        <fieldset style="width: 95%; border: 2px solid black; border-radius: 5px;">
            <legend style="background: black; color: white; font-size: 15px;">Equipo ID - <?=$device["Id"]?></legend>
        <table cellspacing="0" style="width: 100%; font-size: 13px;">
                <tr>
                    <td style="width: 20%;">TIPO:</td>
                    <td style="width: 80%; border-bottom: 1px solid black;"><?=ucfirst($device['Tipo']);?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">MARCA:</td>
                    <td style="width: 80%; border-bottom: 1px solid black;"><?=$device['Marca'];?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">MODELO:</td>
                    <td style="width: 80%; border-bottom: 1px solid black;"><?=$device['Modelo'];?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">No.SERIE:</td>
                    <td style="width: 80%; border-bottom: 1px solid black;"><?=$device['Numero_serie'];?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">No.INVENTARIO:</td>
                    <td style="width: 80%; border-bottom: 1px solid black;"><?=($device['Numero_inventario'] !== '0') ? $device['Numero_inventario'] : 'N/A';?></td>
                </tr>
        </table>
        </fieldset>    
        <?php endforeach;?>
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
$domPdf_obj->stream("reporte_dispositivos_Empresa_id_".$enter_info["Id"].".pdf", ['Attachment' => false]);?>
<!-- este html es la vista del lienzo de un reporte de dispositivos para convertirlo en PDF (la dependencia DomPdf requiere 
que el html sea extremadamente simple, por eso no se usan clases y se ponen los estilos directamente en las etiquetas html); 
se utiliza etiquetas PHP evaluando sesiones y variables inicializadas por los controladores en sus métodos de vistas, esto 
con el fin de determinar qué elementos html mostrar al usuario  -->