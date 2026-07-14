function generateLinks (link){
    const linkDiv = document.createElement("div");
    linkDiv.innerHTML = `
        <a class="binn-row" href="${BASE_URL}finishing/?controller=followupform&action=index&id=${link.Id}">
            <div class="binn-row__id">Id - ${link.Id}</div>
            <div class="binn-row__comm-name">${link.Nombre_comercial}</div>
            <div class="binn-row__status">
                <div class="status-img"><img src="${BASE_URL}assets/img/alert_16750344.png"/></div>
                ${(link.Estatus === "falta confirmar") ? link.Estatus : "Sin terminar"}
            </div>
        </a>
    `;
    return linkDiv;
};

function generateTableRow (row){
    const tableRow = document.createElement("tr");
    tableRow.classList.add("binnacle-data-table__row");
    tableRow.innerHTML = `
        <td class="binnacle-data-table__regular-td"><a class="binnacle-data-table__binn-link generated-link" href="${BASE_URL}home/?homeController=binnacle&homeAction=showBinnacle&homeId=${row.Id}">${row.Id}</a></td>
        <td class="binnacle-data-table__regular-td">${row.Nombre} ${row.Apellidos}</td>
        <td>
            <div class="binnacle-data-table__client-name-box">${row.Nombre_completo}</div>
            <div class="binnacle-data-table__enterprise-name-box">${row.Nombre_comercial}</div>
        </td>
        <td class="binnacle-data-table__regular-td"><a class="binnacle-data-table__binn-pdf-link generated-link" href="${BASE_URL}home/?homeController=binnacle&homeAction=generateBinnacleReport&homeId=${row.Id}">PDF</a></td>
        <td class="binnacle-data-table__regular-td"><a class="binnacle-data-table__binn-edit-link generated-link" href="${BASE_URL}home/?homeController=binnacle&homeAction=editBinnacle&homeId=${row.Id}">Editar</a></td>
        <td class="binnacle-data-table__regular-td"><button class="binnacle-data-table__binn-delete-btn ${(row.Visibilidad === "ENABLED") ? "" : 
                        "activation-background"}" type="button" data-id="${row.Id}" 
                        data-visibility="${row.Visibilidad}">${(row.Visibilidad === "ENABLED") ? "Desactivar" : "Activar"}</button></td>
    `;
    return tableRow;
};

function resetDeviceFields(){

    return `
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
        `;
}

function resetContactFields(target){

    let result = "";

    if(target === 'newContactFormCancelSelectBtn'){
        result = `
            <tr>
                <td class="contact-form-table__label-column">QUIÉN SOLICITA:</td>
                <td class="contact-form-table__input-column">
                    <input class="inputs-box__input" type="text" name="contacto" id="contacto"/>
                </td>
                <td class="contact-form-table__label-column">TELÉFONOS:</td>
                <td class="contact-form-table__input-column">
                    <input class="inputs-box__input" type="text" name="telefonos" id="telefonos"/>
                </td>
            </tr>

            <tr>
                <td class="contact-form-table__label-column">NOMBRE COMERCIAL:</td>
                <td class="contact-form-table__input-column">
                    <input class="inputs-box__input" type="text" name="nombreComercial" id="nombreComercial"/>
                </td>
                <td class="contact-form-table__label-column">HORARIO:</td>
                <td class="contact-form-table__input-column">
                    <input class="inputs-box__input" type="text" name="horario" id="horario"/>
                </td>
            </tr>

            <tr>
                <td class="contact-form-table__label-column">RAZÓN SOCIAL:</td>
                <td class="contact-form-table__input-column">
                    <input class="inputs-box__input" type="text" name="razonSocial" id="razonSocial"/>
                </td>
                <td class="contact-form-table__label-column">ATENCIÓN:</td>
                <td class="contact-form-table__input-column">
                    <input class="inputs-box__input" type="text" name="atencion" id="atencion"/>
                </td>
            </tr>

            <tr>
                <td class="contact-form-table__label-column">CALLE Y NÚMERO:</td>
                <td class="contact-form-table__input-column">
                    <input class="inputs-box__input" type="text" name="calleYNumero" id="calleYNumero"/>
                </td>
                <td class="contact-form-table__label-column">COLONIA:</td>
                <td class="contact-form-table__input-column">
                    <input class="inputs-box__input" type="text" name="colonia" id="colonia"/>
                </td>
            </tr>

            <tr>
                <td class="contact-form-table__label-column">ENTRE CALLES:</td>
                <td class="contact-form-table__input-column">
                    <input class="inputs-box__input" type="text" name="entreCalles" id="entreCalles"/>
                </td>
                <td class="contact-form-table__label-column">LOCALIDAD:</td>
                <td class="contact-form-table__input-column">
                    <input class="inputs-box__input" type="text" name="localidad" id="localidad"/>
                </td>
            </tr>

            <tr>
                <td class="contact-form-table__label-column">DIRIGIRSE CON:</td>
                <td class="contact-form-table__input-column">
                    <input class="inputs-box__input" type="text" name="dirigirseCon" id="dirigirseCon"/>
                </td>
                <td class="contact-form-table__label-column">EMAIL:</td>
                <td class="contact-form-table__input-column">
                    <input class="inputs-box__input" type="email" name="email" id="email"/>
                </td>
            </tr>
        `;
    }else{
        result = `
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
        `;
    }

    return result;
}

function resetDeviceSelect(){
    return `
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
            `;
}

async function dataManagmentProcedure (jsonRes){

    if(jsonRes.entInfoForContactForm != null){
        
        contactFormTbody.innerHTML = `
                <tr>
                    <td class="contact-form-table__label-column">QUIÉN SOLICITA:</td>
                    <td class="contact-form-table__input-column">
                        <input type="hidden" name="hiddenEntId" value="${jsonRes.entInfoForContactForm.Id}"/>
                        <input class="inputs-box__input" type="text" name="contacto" id="contacto"/>
                    </td>
                    <td class="contact-form-table__label-column">TELÉFONOS:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" type="text" name="telefonos" id="telefonos" value="${jsonRes.entInfoForContactForm.Telefonos}" readonly/>
                    </td>
                </tr>

                <tr>
                    <td class="contact-form-table__label-column">NOMBRE COMERCIAL:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" type="text" name="nombreComercial" id="nombreComercial" value="${jsonRes.entInfoForContactForm.Nombre_comercial}" readonly/>
                    </td>
                    <td class="contact-form-table__label-column">HORARIO:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" type="text" name="horario" id="horario" value="${(jsonRes.entInfoForContactForm.Horario !== "") ? jsonRes.entInfoForContactForm.Horario : "Sin asignar"}" readonly/>
                    </td>
                </tr>

                <tr>
                    <td class="contact-form-table__label-column">RAZÓN SOCIAL:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" type="text" name="razonSocial" id="razonSocial" value="${(jsonRes.entInfoForContactForm.Razon_social !== "") ? jsonRes.entInfoForContactForm.Razon_social : "Sin asignar"}" readonly/>
                    </td>
                    <td class="contact-form-table__label-column">ATENCIÓN:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" type="text" name="atencion" id="atencion" value="${(jsonRes.entInfoForContactForm.Atencion !== "") ? jsonRes.entInfoForContactForm.Atencion : "Sin asignar"}" readonly/>
                    </td>
                </tr>

                <tr>
                    <td class="contact-form-table__label-column">CALLE Y NÚMERO:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" type="text" name="calleYNumero" id="calleYNumero" value="${(jsonRes.entInfoForContactForm.Calle_numero !== "") ? jsonRes.entInfoForContactForm.Calle_numero : "Sin asignar"}" readonly/>
                    </td>
                    <td class="contact-form-table__label-column">COLONIA:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" type="text" name="colonia" id="colonia" value="${(jsonRes.entInfoForContactForm.Colonia !== "") ? jsonRes.entInfoForContactForm.Colonia : "Sin asignar"}" readonly/>
                    </td>
                </tr>

                <tr>
                    <td class="contact-form-table__label-column">ENTRE CALLES:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" type="text" name="entreCalles" id="entreCalles" value="${(jsonRes.entInfoForContactForm.Entre_calles !== "") ? jsonRes.entInfoForContactForm.Entre_calles : "Sin asignar"}" readonly/>
                    </td>
                    <td class="contact-form-table__label-column">LOCALIDAD:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" type="text" name="localidad" id="localidad" value="${(jsonRes.entInfoForContactForm.Localidad !== "") ? jsonRes.entInfoForContactForm.Localidad : "Sin asignar"}" readonly/>
                    </td>
                </tr>

                <tr>
                    <td class="contact-form-table__label-column">DIRIGIRSE CON:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" type="text" name="dirigirseCon" id="dirigirseCon" value="${(jsonRes.entInfoForContactForm.Dirigirse_con !== "") ? jsonRes.entInfoForContactForm.Dirigirse_con : "Sin asignar"}" readonly/>
                    </td>
                    <td class="contact-form-table__label-column">EMAIL:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" type="email" name="email" id="email" value="${(jsonRes.entInfoForContactForm.Email !== "") ? jsonRes.entInfoForContactForm.Email : "Sin asignar"}" disabled/>
                    </td>
                </tr>
        `;
    }else if(jsonRes.entInfo != null){
        
        let deviceOptionsHtml = "";
        let contactOptionsHtml = "";

        if (jsonRes.enterpriseDevices.length > 0) {
            for (const device of jsonRes.enterpriseDevices) {
                deviceOptionsHtml += `<option value="${device.Id}">${device.Marca} - ${device.Numero_serie}</option>`;
            }
        }

        if (jsonRes.enterpriseContacts.length > 0) {
            for (const contact of jsonRes.enterpriseContacts) {
                contactOptionsHtml += `<option value="${contact.Id}">${contact.Nombre_completo}</option>`;
            }
        }

        contactFormTbody.innerHTML = `
                <tr>
                    <td class="contact-form-table__label-column">QUIÉN SOLICITA:</td>
                    <td class="contact-form-table__input-column">
                        <select class="js-example-placeholder-single" name="contactos" id="firstFormContactSelect">
                                <option></option>
                                ${contactOptionsHtml}
                        </select>
                    </td>
                    <td class="contact-form-table__label-column">TELÉFONOS:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" value="${jsonRes.entInfo.Telefonos}" disabled=""/> 
                    </td>
                </tr>
                <tr>
                    <td class="contact-form-table__label-column">NOMBRE COMERCIAL:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" value="${jsonRes.entInfo.Nombre_comercial}" disabled=""/>
                    </td>
                    <td class="contact-form-table__label-column">HORARIO:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" value="${(jsonRes.entInfo.Horario !== "") ? jsonRes.entInfo.Horario : "Sin asignar"}" disabled=""/>
                    </td>
                </tr>

                <tr>
                    <td class="contact-form-table__label-column">RAZÓN SOCIAL:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" value="${(jsonRes.entInfo.Razon_social !== "") ? jsonRes.entInfo.Razon_social : "Sin asignar"}" disabled=""/>
                    </td>
                    <td class="contact-form-table__label-column">ATENCIÓN:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" value="${(jsonRes.entInfo.Atencion !== "") ? jsonRes.entInfo.Atencion : "Sin asignar"}" disabled=""/>
                    </td>
                </tr>

                <tr>
                    <td class="contact-form-table__label-column">CALLE Y NÚMERO:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" value="${(jsonRes.entInfo.Calle_numero !== "") ? jsonRes.entInfo.Calle_numero : "Sin asignar"}" disabled=""/>
                    </td>
                    <td class="contact-form-table__label-column">COLONIA:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" value="${(jsonRes.entInfo.Colonia !== "") ? jsonRes.entInfo.Colonia : "Sin asignar"}" disabled=""/>
                    </td>
                </tr>

                <tr>
                    <td class="contact-form-table__label-column">ENTRE CALLES:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" value="${(jsonRes.entInfo.Entre_calles !== "") ? jsonRes.entInfo.Entre_calles : "Sin asignar"}" disabled=""/>
                    </td>
                    <td class="contact-form-table__label-column">LOCALIDAD:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" value="${(jsonRes.entInfo.Localidad !== "") ? jsonRes.entInfo.Localidad : "Sin asignar"}" disabled=""/>
                    </td>
                </tr>

                <tr>
                    <td class="contact-form-table__label-column">DIRIGIRSE CON:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" value="${(jsonRes.entInfo.Dirigirse_con !== "") ? jsonRes.entInfo.Dirigirse_con : "Sin asignar"}" disabled=""/>
                    </td>
                    <td class="contact-form-table__label-column">EMAIL:</td>
                    <td class="contact-form-table__input-column">
                        <input class="inputs-box__input" value="${(jsonRes.entInfo.Email !== "") ? jsonRes.entInfo.Email : "Sin asignar"}" disabled=""/>
                    </td>
                </tr>
        `;

        deviceSelectTbody.innerHTML = `
            <tr>
                <td class="device-select-table__label-column" valign="middle">EQUIPO:</td>
                <td class="device-select-table__select-column">
                    <select class="js-example-placeholder-single" name="equipos" id="firstFormDeviceSelect">
                        <option></option>
                        ${deviceOptionsHtml}
                    </select>
                </td>
                <td class="device-select-table__button-column">
                    <button class="binnacle-form__button" type="button" id="firstFormCancelDeviceSelectBtn">Cancelar selección</button>  
                </td>
            </tr>
        `;

        await new Promise(resolve => setTimeout(resolve, 0));

        const contactsSelect = contactFormTbody.querySelector("#firstFormContactSelect");
        const devicesSelect = deviceSelectTbody.querySelector("#firstFormDeviceSelect");
        const cancelDevice = deviceSelectTbody.querySelector("#firstFormCancelDeviceSelectBtn");

        $(contactsSelect).select2({
            placeholder: "Selecciona un contacto",
            width: "100%"
        });

        $(devicesSelect).select2({
            placeholder: "Selecciona un equipo",
            width: "100%"
        });

        $(devicesSelect).on("change", () => {
            
            const deviceIdSelected = devicesSelect.value;
            if (deviceIdSelected !== "") {

                fetch(BASE_URL + "home/?homeController=binnacle&homeAction=index", {
                    "method": "POST",
                    "headers": {
                        "Content-Type": "application/json; charset=utf-8"
                    },
                    "body": JSON.stringify({"deviceId": `${deviceIdSelected}`})
                })
                .then(res => res.json())
                .then(data => {

                    deviceFormTbody.innerHTML = `
                        <tr>
                            <td class="device-form-table__label-column">TIPO:</td>
                            <td class="device-form-table__input-column"><input class="inputs-box__input" value="${data.Tipo.charAt(0).toUpperCase() + data.Tipo.slice(1)}" disabled=""/></td>
                        </tr>
                        <tr>
                            <td class="device-form-table__label-column">MARCA:</td>
                            <td class="device-form-table__input-column"><input class="inputs-box__input" value="${data.Marca}" disabled=""/></td>
                        </tr>
                        <tr>
                            <td class="device-form-table__label-column">MODELO:</td>
                            <td class="device-form-table__input-column"><input class="inputs-box__input" value="${data.Modelo}" disabled=""/></td>
                        </tr>
                        <tr>
                            <td class="device-form-table__label-column">N.S:</td>
                            <td class="device-form-table__input-column"><input class="inputs-box__input" value="${data.Numero_serie}" disabled=""/></td>
                        </tr>
                        <tr>
                            <td class="device-form-table__label-column">NO.INVENTARIO:</td>
                            <td class="device-form-table__input-column"><input class="inputs-box__input" value="${(data.Numero_inventario !== "0") ? data.Numero_inventario : "N/A"}" disabled=""/></td>
                        </tr>
                    `; 
                });

            }
        });

        cancelDevice.addEventListener("click", () => {
            
            deviceFormTbody.innerHTML = resetDeviceFields();

            /*se quita cualquier valor que haya tenido
                * el select de dispositivo activando su placeholder*/

            $(devicesSelect).val("").trigger("change");
        });
    }
    
}


function enterOrContactSwitchWindow(target, button){

    let result = "";
    if(target === "enterprise-forms__delete-button"){
        result = `
            <form class="enter-or-client-delete__info-window" action="${BASE_URL}home/?homeController=enterprise&homeAction=enableOrDisableEnterprise" method="POST">
                <div class="pop-up-window-icon"><img class="pop-up-window-icon__img" src="${BASE_URL}assets/img/caution-sign_75243.png"/></div>
                <div class="info-window__text-box"><h3>¿Está seguro de ${(button.dataset.visibility === "ENABLED") ? "desactivar" : "activar"} esta empresa?, 
                                este registro ${(button.dataset.visibility === "ENABLED") ? 'no será visible' : 'será visible'} 
                                en la caja de selección de los formularios de "Nueva bitácora", "Crear contacto", "Crear un equipo" y la caja de selección 
                                de empresas en los filtros de "Reportes de bitácoras"</h3></div>
                <input type="hidden" value="${button.dataset.id}" class="empresa-id" name="empresaId"/>
                <input type="hidden" value="${(button.dataset.visibility === "ENABLED") ? 'DISABLED' : 'ENABLED'}" class="visibilidad" name="visibilidad"/>
                <div class="info-window__selectbuttons-box">
                    <input class="selectbuttons-box__button" type="submit" value="${(button.dataset.visibility === "ENABLED") ? 'Desactivar' : 'Activar'}"/>
                    <button class="selectbuttons-box__cancel-delete-button" type="button">Cancelar</button>
                </div>
            </form>
        `;
    }else{
        result = `
            <form class="enter-or-client-delete__info-window" action="${BASE_URL}home/?homeController=contact&homeAction=enableOrDisableContact" method="POST">
                <div class="pop-up-window-icon"><img class="pop-up-window-icon__img" src="${BASE_URL}assets/img/caution-sign_75243.png"/></div>
                <div class="info-window__text-box"><h3>¿Está seguro de ${(button.dataset.visibility === "ENABLED") ? "desactivar" : "activar"} el contacto 
                        con Id ${button.dataset.id}?, este registro ${(button.dataset.visibility === "ENABLED") ? 'no será visible' : 'será visible'} 
                        en la caja de selección del formulario de "Nueva bitácora" y la caja de selección 
                            de contactos en los filtros de "Reportes de bitácoras"</h3></div>
                <input type="hidden" value="${button.dataset.id}" class="contacto-id" name="contactoId"/>
                <input type="hidden" value="${(button.dataset.visibility === "ENABLED") ? 'DISABLED' : 'ENABLED'}" class="visibilidad" name="visibilidad"/>
                <div class="info-window__selectbuttons-box">
                    <input class="selectbuttons-box__button" type="submit" value="${(button.dataset.visibility === "ENABLED") ? 'Desactivar' : 'Activar'}"/>
                    <button class="selectbuttons-box__cancel-delete-button" type="button">Cancelar</button>
                </div>
            </form>
        `;
    }

    return result;
}

function typeSwitchWindow(button){
    return `
                <form class="enable-or-disable__info-window" action="${BASE_URL}home/?homeController=type&homeAction=enableOrDisableType" method="POST">
                    <div class="pop-up-window-icon"><img class="pop-up-window-icon__img" src="${BASE_URL}assets/img/caution-sign_75243.png"/></div>
                    <div class="info-window__text-box"><h3>¿Está seguro de ${(button.dataset.visibility === "ENABLED") ? "desactivar" : "activar"} el tipo 
                            con Id ${button.dataset.id}?, este registro ${(button.dataset.visibility === "ENABLED") ? 'no será visible' : 'será visible'} 
                            en la caja de selección del formulario de "Crear un equipo"</h3></div>
                    <input type="hidden" value="${button.dataset.id}" class="tipo-id" name="tipoId"/>
                    <input type="hidden" value="${(button.dataset.visibility === "ENABLED") ? 'DISABLED' : 'ENABLED'}" class="visibilidad" name="visibilidad"/>
                    <div class="info-window__selectbuttons-box">
                        <input class="selectbuttons-box__button" type="submit" value="${(button.dataset.visibility === "ENABLED") ? 'Desactivar' : 'Activar'}"/>
                        <button class="selectbuttons-box__cancel-delete-button" type="button">Cancelar</button>
                    </div>
                </form>
            `;
}

function deviceSwitchWindow(button){
    return `
                <form class="enable-or-disable__info-window" action="${BASE_URL}home/?homeController=device&homeAction=enableOrDisableDevice" method="POST">
                    <div class="pop-up-window-icon"><img class="pop-up-window-icon__img" src="${BASE_URL}assets/img/caution-sign_75243.png"/></div>
                    <div class="info-window__text-box"><h3>¿Está seguro de ${(button.dataset.visibility === "ENABLED") ? "desactivar" : "activar"} el equipo 
                            con Id ${button.dataset.id}?, este registro ${(button.dataset.visibility === "ENABLED") ? 'no será visible' : 'será visible'} 
                            en la caja de selección de equipo del formulario de "Nueva bitácora" y en los filtros de "Reportes de bitácoras"</h3></div>
                    <input type="hidden" value="${button.dataset.id}" class="equipo-id" name="equipoId"/>
                    <input type="hidden" value="${(button.dataset.visibility === "ENABLED") ? 'DISABLED' : 'ENABLED'}" class="visibilidad" name="visibilidad"/>
                    <div class="info-window__selectbuttons-box">
                        <input class="selectbuttons-box__button" type="submit" value="${(button.dataset.visibility === "ENABLED") ? 'Desactivar' : 'Activar'}"/>
                        <button class="selectbuttons-box__cancel-delete-button" type="button">Cancelar</button>
                    </div>
                </form>
            `;
}

function binnsSwitchWindow(button){
    return `
                <form class="binnacle-delete__info-window" action="${BASE_URL}home/?homeController=binnacle&homeAction=enableOrDisableBinn" method="POST">
                    <div class="pop-up-window-icon"><img class="pop-up-window-icon__img" src="${BASE_URL}assets/img/caution-sign_75243.png"/></div>
                    <div class="info-window__text-box"><h3>¿Está seguro de ${(button.dataset.visibility === "ENABLED") ? "desactivar" : "activar"} la bitácora 
                            con Id ${button.dataset.id}?, este registro ${(button.dataset.visibility === "ENABLED") ? 'no será visible' : 'será visible'} 
                            en el apartado de "Reportes de bitácoras" al menos que se seleccione 
                            ${(button.dataset.visibility === "ENABLED") ? 'Desactivado' : 'Activado'} en la caja de selección de "Visible" en la busqueda</h3></div>
                    <input type="hidden" value="${button.dataset.id}" class="bitacora-id" name="bitacoraId"/>
                    <input type="hidden" value="${(button.dataset.visibility === "ENABLED") ? 'DISABLED' : 'ENABLED'}" class="visibilidad" name="visibilidad"/>
                    <div class="info-window__selectbuttons-box">
                        <input class="selectbuttons-box__button" type="submit" value="${(button.dataset.visibility === "ENABLED") ? 'Desactivar' : 'Activar'}"/>
                        <button class="selectbuttons-box__cancel-delete-button" type="button">Cancelar</button>
                    </div>
                </form>
            `;
}

export {generateLinks, 
        generateTableRow, 
        dataManagmentProcedure, 
        resetDeviceFields,
        resetDeviceSelect,
        resetContactFields,
        enterOrContactSwitchWindow,
        typeSwitchWindow,
        deviceSwitchWindow,
        binnsSwitchWindow};

// module.exports = {generateLinks, 
//         generateTableRow, 
//         dataManagmentProcedure, 
//         resetDeviceFields,
//         resetDeviceSelect,
//         resetContactFields,
//         enterOrContactSwitchWindow,
//         typeSwitchWindow,
//         deviceSwitchWindow,
//         binnsSwitchWindow};