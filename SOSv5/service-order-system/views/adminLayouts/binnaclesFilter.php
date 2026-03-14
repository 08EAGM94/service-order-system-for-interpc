<!-- este html es la vista de reporte de bitácoras; las vistas de contenido como este se utiliza la etiqueta <main>, si se quiere crear otras vistas es 
recomendable poner esta etiqueta como el padre de los elementos html, también se utiliza etiquetas PHP evaluando sesiones y variables inicializadas 
por los controladores en sus métodos de vistas, esto con el fin de determinar qué elementos html mostrar al usuario -->
<main class="binnacles-filter-main">
    <form class="binnacles-filter__filter-options-form" action="<?= base_url;?>home/?homeController=user&homeAction=binnaclesReport" method="POST">
        
        <div class="filter-options-form__left-piece">
            <div class="left-piece__up-options">
                
                <div class="up-options__wrapper">
                    <div class="filter-options-form__label-box">
                        <label class="filter-options-form__label" for="binnFiltersEnterSelect">Empresa:</label>
                    </div>

                    <div class="up-options__enterpriseSelect-wrapper">
                        <select class="js-example-placeholder-single" name="empresaId" id="binnFiltersEnterSelect">
                            <option></option>
                            <?php if (sizeof($empresas) > 0): ?>
                                <?php foreach ($empresas as $enter): ?>
                                    <?php if(!empty($_SESSION["Empresa_id"])):?>
                                    <option value="<?= $enter["Id"]; ?>" <?= ($_SESSION["Empresa_id"] === $enter["Id"]) ? 'selected' : '';?>><?= $enter["Nombre_comercial"]; ?> - <?= (!empty($enter["Razon_social"])) ? $enter["Razon_social"] : ''; ?></option>
                                    <?php else:?>
                                    <option value="<?= $enter["Id"]; ?>"><?= $enter["Nombre_comercial"]; ?> - <?= (!empty($enter["Razon_social"])) ? $enter["Razon_social"] : ''; ?></option>
                                    <?php endif;?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                
                <div class="up-options__wrapper">
                    <div class="filter-options-form__label-box">
                        <label class="filter-options-form__label" for="binnFiltersClientSelect">Contacto:</label>
                    </div>

                    <div class="up-options__clientSelect-wrapper">
                        <select class="js-example-placeholder-single" name="contactoId" id="binnFiltersContactSelect">
                            <option></option>
                            <?php if (!empty($_SESSION["enterpriseRelatedContacts"])): ?>
                                <?php foreach ($_SESSION["enterpriseRelatedContacts"] as $contact): ?>
                                    <?php if(!empty($_SESSION["Contacto_id"])):?>
                                    <option value="<?= $contact["Id"]; ?>" <?= ($_SESSION["Contacto_id"] === $contact["Id"]) ? 'selected' : '';?>><?= $contact["Nombre_completo"]; ?></option>
                                    <?php else:?>
                                    <option value="<?= $contact["Id"]; ?>"><?= $contact["Nombre_completo"]; ?></option>
                                    <?php endif;?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                
                <div class="up-options__wrapper">
                    <div class="filter-options-form__label-box">
                        <label class="filter-options-form__label" for="binnFiltersDeviceSelect">Equipo:</label>
                    </div>

                    <div class="up-options__deviceSelect-wrapper">
                        <select class="js-example-placeholder-single" name="equipoId" id="binnFiltersDeviceSelect">
                            <option></option>
                            
                            <?php if (!empty($_SESSION["enterpriseRelatedDevices"])): ?>
                                <?php foreach ($_SESSION["enterpriseRelatedDevices"] as $equipo): ?>
                                    <?php if(!empty($_SESSION["Equipo_id"])):?>
                                    <option value="<?= $equipo["Id"]; ?>" <?=($_SESSION["Equipo_id"] === $equipo["Id"]) ? 'selected' : '';?>><?= $equipo["Marca"];?> - <?= $equipo["Numero_serie"];?></option>
                                    <?php else:?>
                                    <option value="<?= $equipo["Id"]; ?>"><?= $equipo["Marca"];?> - <?= $equipo["Numero_serie"];?></option>
                                    <?php endif;?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            
                        </select>
                    </div>
                </div>
                
                
            </div>
            
            <div class="left-piece__middle-options">
                
                <div class="middle-options__wrapper">
                    <div class="filter-options-form__label-box">
                        <label class="filter-options-form__label" for="servicioOEquipo">Trabajo:</label>
                    </div>

                    <select class="up-options__serviceOrDevice-select" name="servicioOEquipo" id="servicioOEquipo">
                        <?php if(!empty($_SESSION["isServiceOrDevice"])):?>
                            <option value="Servicio" <?= ($_SESSION["isServiceOrDevice"] === "Servicio") ? "selected" : "";?>>Servicio</option>
                            <option value="Equipo_id" <?= ($_SESSION["isServiceOrDevice"] === "Equipo_id") ? "selected" : "";?>>Equipo</option>
                        <?php else:?>
                            <option value="Servicio">Servicio</option>
                            <option value="Equipo_id">Equipo</option>
                        <?php endif;?>
                    </select>
                </div>
                
                <div class="middle-options__wrapper">
                    <div class="filter-options-form__label-box">
                        <label class="filter-options-form__label" for="estatus">Estatus:</label>
                    </div>

                    <select class="up-options__estatus-select" name="estatus" id="estatus">
                        <?php if(!empty($_SESSION["Estatus"])):?>
                            <option value="en proceso" <?= ($_SESSION["Estatus"] === "en proceso") ? "selected" : "";?>>En proceso</option>
                            <option value="falta confirmar" <?= ($_SESSION["Estatus"] === "falta confirmar") ? "selected" : "";?>>Falta confirmar</option>
                            <option value="cancelado" <?= ($_SESSION["Estatus"] === "cancelado") ? "selected" : "";?>>Cancelado</option>
                            <option value="finalizado" <?= ($_SESSION["Estatus"] === "finalizado") ? "selected" : "";?>>Finalizado</option>
                        <?php else:?>
                            <option value="en proceso">En proceso</option>
                            <option value="falta confirmar">Falta confirmar</option>
                            <option value="cancelado">Cancelado</option>
                            <option value="finalizado">Finalizado</option>
                        <?php endif;?>
                    </select>
                </div>
                
                <div class="middle-options__wrapper">
                    <div class="filter-options-form__label-box">
                        <label class="filter-options-form__label" for="visible">Visible:</label>
                    </div>

                    <select class="up-options__estatus-select" name="visible" id="visible">
                        <?php if(!empty($_SESSION["Visible"])):?>
                            <option value="ENABLED" <?= ($_SESSION["Visible"] === "ENABLED") ? "selected" : "";?>>Activado</option>
                            <option value="DISABLED" <?= ($_SESSION["Visible"] === "DISABLED") ? "selected" : "";?>>Desactivado</option>
                        <?php else:?>
                            <option value="ENABLED">Activado</option>
                            <option value="DISABLED">Desactivado</option>
                        <?php endif;?>
                    </select>
                </div>
            </div>
            
            <div class="left-piece__down-options">
                
                <select class="down-options__startedOrEnded-select" name="startedOrEnded">
                    <?php if(!empty($_SESSION["StartedOrEnded"])):?>
                    <option value="Inicio" <?= ($_SESSION["StartedOrEnded"] === "Inicio") ? "selected" : "";?>>Iniciado entre</option>
                    <option value="Fin" <?= ($_SESSION["StartedOrEnded"] === "Fin") ? "selected" : "";?>>Finalizado entre</option>
                    <?php else:?>
                    <option value="Inicio">Iniciado entre</option>
                    <option value="Fin">Finalizado entre</option>
                    <?php endif;?>
                </select>
                
                <div class="down-options__date-inputs-box">
                    <?php if(!empty($_SESSION["LeftDay"]) && !empty($_SESSION["RightDay"])):?>
                    <strong>El</strong>
                    <input class="down-options__date-input" type="date" name="leftDay" value="<?= $_SESSION["LeftDay"];?>"/>
                    <strong>Hasta el</strong>
                    <input class="down-options__date-input" type="date" name="rightDay" value="<?= $_SESSION["RightDay"];?>"/>
                    <?php else:?>
                    <strong>El</strong>
                    <input class="down-options__date-input" type="date" name="leftDay"/>
                    <strong>Hasta el</strong>
                    <input class="down-options__date-input" type="date" name="rightDay"/>
                    <?php endif;?>
                </div>
                
            </div>
        </div>
        
        <div class="filter-options-form__right-piece">
            <input class="right-piece__submit" type="submit" value="Filtrar"/>
        </div>
    </form>
    
    <!-- Los controladores inicializan sesiones con strings que necesitan ser mostradas al usuario, se utilizan etiquetas PHP para evaluar 
    si existen estas sesiones, si existen, entonces se utiliza los valores de estas sesiones con un elemento html el cual muestra al usuario 
    el mensaje en forma de flags -->
    <?php if (!empty($_SESSION["disableBinnSuccess"])): ?>
        <div class="succeed-box"><?= $_SESSION["disableBinnSuccess"]; ?></div>
    <?php endif; ?> 
           
    <?php if (!empty($_SESSION["gettingUsersException"])): ?>
        <div class="invalidinput-box"><?= $_SESSION["gettingUsersException"]; ?></div>
    <?php endif; ?> 
    <?php if(!empty($_SESSION["dces_arrException"])):?>
    <div class="invalidinput-box"><?=$_SESSION["dces_arrException"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["num_rowsEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["num_rowsEx"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["binnsRowsPaginationEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["binnsRowsPaginationEx"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["binnReportgetClientsForSelectEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["binnReportgetClientsForSelectEx"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["getBinnInfoEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["getBinnInfoEx"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["binnacleUpdateEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["binnacleUpdateEx"]?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION["disableBinnEx"])):?>
    <div class="invalidinput-box"><?=$_SESSION["disableBinnEx"]?></div>
    <?php endif; ?>
    
    
    <?php if(!empty($_SESSION["binnFilterErr"])):?>
        <?php foreach($_SESSION["binnFilterErr"] as $err):?>
            <div class="invalidinput-box"><?=$err?></div>
        <?php endforeach;?>
    <?php endif;?>
    
    <?php if(!empty($_SESSION["updateBinnacleInfoErr"])):?>
        <?php foreach($_SESSION["updateBinnacleInfoErr"] as $err):?>
            <div class="invalidinput-box"><?=$err?></div>
        <?php endforeach;?>
    <?php endif;?>
       
    <?php if(!empty($_SESSION["disableBinnErr"])):?>
        <?php foreach($_SESSION["disableBinnErr"] as $err):?>
            <div class="invalidinput-box"><?=$err?></div>
        <?php endforeach;?>
    <?php endif;?>         
    
    <?php if(!empty($_SESSION["filtValidated"])):?>
    <?php if($num_rows > 0):?>
    <div class="binnacle-delete__window-background hidThis" id="binnDeletebackWindow"></div>       
    <div class="binnacles-filter__binnacle-data-table-wrapper">
        <div class="binnacle-data-table-wrapper__numkey-box">
            Número de elementos en pantalla:
            <select class="binnacle-data-table-wrapper__numkey-select" name="pagElem" id="binnsFilternumkeySelect">
                <option value="5" <?=($page_elem === 5) ? "selected" : ""?>>5</option>
                <option value="50" <?=($page_elem === 50) ? "selected" : ""?>>50</option>
                <option value="100" <?=($page_elem === 100) ? "selected" : ""?>>100</option>
            </select>
        </div> 
        <table class="binnacles-filter__binnacle-data-table">
            <thead>
                <tr>
                    <th scope="col"><div class="binnacle-data-table__theader-left-radius-box">Folio</div></th>
                    <th scope="col"><div class="binnacle-data-table__regular-th">Responsable</div></th>
                    <th scope="col"><div class="binnacle-data-table__regular-th">cliente</div></th>
                    <th scope="col"><div class="binnacle-data-table__regular-th">Imprimir</div></th>
                    <th scope="col"><div class="binnacle-data-table__regular-th">Editar</div></th>
                    <th scope="col"><div class="binnacle-data-table__theader-right-radius-box">Switch</div></th>
                </tr>
            </thead>

            <tbody id="binnacleTbody">
                <?php while($binn = $stmt_binns->fetch()):?>
                <tr>
                    <td class="binnacle-data-table__regular-td"><a class="binnacle-data-table__binn-link" href="<?= base_url;?>home/?homeController=user&homeAction=showBinnacle&homeId=<?=$binn["Id"];?>"><?=$binn["Id"];?></a></td>
                    <td class="binnacle-data-table__regular-td"><?=$binn["Nombre"]." ".$binn["Apellidos"];?></td>
                    <td>
                        <div class="binnacle-data-table__client-name-box"><?=$binn["Nombre_completo"];?></div>
                        <div class="binnacle-data-table__enterprise-name-box"><?=$binn["Nombre_comercial"];?></div>
                    </td>
                    <td class="binnacle-data-table__regular-td"><a class="binnacle-data-table__binn-pdf-link" href="<?= base_url;?>home/?homeController=user&homeAction=generateBinnacleReport&homeId=<?=$binn["Id"];?>">PDF</a></td>
                    <td class="binnacle-data-table__regular-td"><a class="binnacle-data-table__binn-edit-link" href="<?= base_url;?>home/?homeController=user&homeAction=editBinnacle&homeId=<?=$binn["Id"];?>">Editar</a></td>
                    <td class="binnacle-data-table__regular-td"><button class="binnacle-data-table__binn-delete-btn <?=($binn["Visibilidad"] === "ENABLED") ? "" : 
                            "activation-background"?>" type="button" data-id="<?=$binn["Id"];?>" 
                            data-visibility="<?=$binn["Visibilidad"];?>"><?=($binn["Visibilidad"] === "ENABLED") ? "Desactivar" : "Activar"?></button></td>
                </tr>
                <?php endwhile;?>
            </tbody>
        </table>
    </div> 
    
    <div class="binnacles-filter__pagination-control-box" id="binnFilterPaginationBox">
        <?php $pagination->render();?>
    </div>
    <?php else:?>
    <div class="binnacles-filter__without-binns-row">
        <h1>No hay bitácoras relacionadas con las opciones de filtrado enviadas...</h1>
    </div>
    <?php endif;?>        
    <?php endif;?>        
</main>
<!-- generalmente, las vistas que muestran mensajes flags tienen una etiqueta PHP donde se utiliza el método estático unsetFlagsSessions el cual elimina las 
sesiones de mensajes de errores, excepciones y de exito en un proceso -->
<?php Utils::unsetFlagsSessions();?>