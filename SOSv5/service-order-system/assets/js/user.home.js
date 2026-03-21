/*Este documento contempla tanto la funcionalidad de elementos html de la vista de los usuarios como de los administradores.
Este archivo JS se incluye en el html <head> del archivo: views/userLayouts/menuSides/head.php
gracias a aplicarle al archivo html "document" el evento "DOMContentLoaded", el codigo javascript
(el cual esta dentro de la función anonima del evento) empieza a funcionar ya despues de haber 
cargado todos los elementos html. hay que mencionar que en el servidor, en este caso PHP, divide
el documento html en cuatro principales partes gracias a "require_once" (el cual permite no repetir 
html y dar dinamismo a las vistas), head.php, welcomeBanner.php (el banner de bienvenida dependiendo si el 
privilegio del usuario es "user" o "Admin"), (cualquier vista que utilice la etiqueta <main>, ejemplo: 
userInsertForm.php) y footer.php; en este caso este archivo js se utiliza en: views/userLayouts/menuSides/head.php,
el cual es ahi donde se empieza la semantica html, ahí se incluye el <head> (el lugar donde se carga los estilos
y codigo JS) y se abre la etiqueta <body>, en este caso, las vistas de usuario y administrador cuentan con un banner
de bienvenida por lo que se carga tambien la etiqueta <header> en el documento, el html dinamico será el contenido principal 
del documento, contenido que puede cambiar gracias a los controladores UserController.php y ErrorController
y cuyos elementos que requieren funcionalidad se seleccionarán y se guardarán en sus respectivas constantes de este archivo JS, 
finalmente, views/userLayouts/menuSides/footer.php a primera vista contiene una estructura de control con etiquetas PHP, si 
la sesión isAdmin no esta vacía entonces se cargará el html del menú para la vista responsiva y se cerrará la semantica 
html con </body> y </html>, en dado caso de que no se cumpla con la condición solamente se cerrará la semantica del html, 
esto es cerrar el body con </body> y el html con </html>*/
document.addEventListener("DOMContentLoaded", ()=>{
    
    //--------------------------------------------------------------------------
    /*constantes que contienen contenido html de las vistas de usuarios (técnicos de la empresa)*/
    const binnacleform = document.querySelector(".binnacle-form");
    const newContactForm = document.querySelector(".newContactForm");
    const newTypeForm = document.querySelector(".newTypeForm");
    const newDeviceForm = document.querySelector(".newDeviceForm");
    
    const activityChoice1 = document.querySelector("#activityChoice1");
    const activityChoice2 = document.querySelector("#activityChoice2");    
    const serviceBox = document.querySelector("#serviceBox");    
    const deviceBox = document.querySelector("#deviceBox");
    
    const firstFormCancelSelectBtn = document.querySelector("#firstFormCancelSelectBtn");
    const firstFormCancelDeviceSelectBtn = document.querySelector("#firstFormCancelDeviceSelectBtn");
    const contactFormTbody = document.querySelector("#contactFormTbody");
    const deviceSelectTbody = document.querySelector("#deviceSelectTbody");
    const deviceFormTbody = document.querySelector("#deviceFormTbody");
    const firstFormContactSelect = document.querySelector("#firstFormContactSelect");
    const firstFormenterprisesSelect = document.querySelector("#firstFormenterprisesSelect");
    const firstFormDeviceSelect = document.querySelector("#firstFormDeviceSelect");
    
    const newContactFormCancelSelectBtn = document.querySelector("#newContactFormCancelSelectBtn");
    const newContectFormEnterprisesSelect = document.querySelector("#newContectFormEnterprisesSelect");
    
    const TypeFormField = document.querySelector("#TypeFormField");
    
    const newDeviceFormTbody = document.querySelector("#newDeviceFormTbody");
    const newDeviceFormEntSelect = document.querySelector("#newDeviceFormEntSelect");
    const newDeviceFormTypSelect = document.querySelector("#newDeviceFormTypSelect");
    
    
    const backWindow = document.querySelector("#backWindow");
    const infoWindow = document.querySelector("#infoWindow");
    const niseSubmit = document.querySelector("#niseSubmit");
    const no = document.querySelector("#no");
    const alias = document.querySelector("#alias");
    const numkeySelect = document.querySelector("#numkeySelect");
    const linksArea = document.querySelector("#linksArea");
    const paginationBox = document.querySelector("#paginationBox");
    //--------------------------------------------------------------------------
    
    //--------------------------------------------------------------------------
    /*constantes que contienen elementos html de las vistas del administrador*/
    const userForm = document.querySelector(".userform__form");
    const enterForm = document.querySelector(".enterprise-forms__form");
    const contactsForms = document.querySelectorAll(".enterprise-forms__contact-form");
    const typesForms = document.querySelectorAll(".edit-forms__type-form"); 
    const devicesForms = document.querySelectorAll(".device-form__form"); 
    
    const adminMenuWrapper = document.querySelector("#adminMenuWrapper");
    const adminMenuLis = document.querySelectorAll(".linkList__row");
    const adminMobileMenuLis = document.querySelectorAll(".mobile-navbar__row");
    const mainsWrapper = document.querySelector("#mainsWrapper");
    const mobileBtn = document.querySelector("#mobileBtn");
    const mobileNavBar = document.querySelector("#mobileNavBar");
    const mobileCancelBtn = document.querySelector("#mobileCancelBtn");
    const numkeyBox = document.querySelector("#numkeyBox");
    const userCreationbackWindow = document.querySelector("#userCreationbackWindow");
    const userCreationinfoWindow = document.querySelector(".userCreationFileds__info-window");
    const userCreationNiseSubmit = document.querySelector("#userCreationNiseSubmit");
    const userDeleteinfoWindow = document.querySelector("#userDeleteinfoWindow");
    const userCreationCancel = document.querySelector("#userCreationCancel");
    const usersSelect = document.querySelector("#usersSelect");
    const userDeleteNiseSubmit = document.querySelector("#userDeleteNiseSubmit");
    const userDeletebackWindow = document.querySelector("#userDeletebackWindow");
    const userDeleteCancel = document.querySelector("#userDeleteCancel");
    const editEnterSelect = document.querySelector("#editEnterSelect");
    const entersSelect = document.querySelector("#entersSelect");
    const enterpriseFormsMain = document.querySelector("#enterpriseFormsMain");
    const enterEditInfoWindow = document.querySelector(".enter-edit__info-window");
    const enterOrClientDeletebackWindow = document.querySelector("#enterOrClientDeletebackWindow");
    const enterDeleteButton = document.querySelector(".enterprise-forms__delete-button");
    const deleteContactBtns = document.querySelectorAll(".contact-form__delete-button");
    const enterpriseEditConfirmationBackground = document.querySelector("#enterpriseEditConfirmationBackground");
    const enterpriseEditBtn = document.querySelector(".enterprise-forms__edit-button");
    const enterpriseEditeCancelBtn = document.querySelector("#enterpriseEditeCancelBtn");
    const contactEditBtns = document.querySelectorAll(".contact-form__edit-button");
    const contactEditCancelBtns = document.querySelectorAll(".selectbuttons-box__cancelContact-edit-button");
    const enableOrDisablebackWindow = document.querySelector("#enableOrDisablebackWindow");
    const typesEditFormsMain = document.querySelector(".typesEditForms-main");
    const typeSelect = document.querySelectorAll(".typeSelect");
    const devicesEditFormsMain = document.querySelector(".devicesEditForms-main");
    const editDevicesEnterSelect = document.querySelector(".editDevicesEnterSelect");
    const binnFiltersEnterSelect = document.querySelector("#binnFiltersEnterSelect");
    const binnFiltersContactSelect = document.querySelector("#binnFiltersContactSelect");
    const binnFiltersDeviceSelect = document.querySelector("#binnFiltersDeviceSelect");
    const servicioOEquipo = document.querySelector("#servicioOEquipo");
    const binnsFilternumkeySelect = document.querySelector("#binnsFilternumkeySelect");
    const binnacleTbody = document.querySelector("#binnacleTbody");
    const binnFilterPaginationBox = document.querySelector("#binnFilterPaginationBox");
    const binnDeletebackWindow = document.querySelector("#binnDeletebackWindow");
    const binnDelBtns = document.querySelectorAll(".binnacle-data-table__binn-delete-btn");
    const editBinnacleBtn = document.querySelector("#editBinnacleBtn");
    const editBinnUserSelect = document.querySelector("#editBinnUserSelect");
    const binnEditConfirmationBackground =  document.querySelector("#binnEditConfirmationBackground");
    const binnEditConfirmationInfoWindow = document.querySelector(".binnacle-edit__info-window");
    const deviceReportPdfBtn = document.querySelector(".devicesReport__pdf-button");
    const binnPdfBtns = document.querySelectorAll(".binnacle-data-table__binn-pdf-link");
    //--------------------------------------------------------------------------

    //--------------------------FUNCIONES CALLBACK------------------------------
    
    /*Esta constante contiene una función que posteriormente se utiliza en el evento change del elemento html el cual
     * está contenido en la constante numkeySelect*/
    const generateLinks = link =>{
        /*el parametro link contendrá datos que php transformó a JSON gracias a json_encode, lo primero que se hace es
         * crear una constante el cual contendrá un elemento html "div"*/
        const linkDiv = document.createElement("div");
        /*dentro de ese elemento div se le añade un elemento link gracias a innerHTML, ese link se contruye gracias al 
         * acceso de datos del JSON pasado como argumento en la función, también se usa la constante BASE_URL para completar
         * las rutas url correspondientes, hay que aclarar que el controlador FormController en su metodo index exige un
         * ID, por lo que se esta accediendo a la propiedad id del JSON para completar el href*/
        linkDiv.innerHTML = `
            <a class="binn-row" href="${BASE_URL}finishing/?controller=form&action=index&id=${link.Id}">
                <div class="binn-row__id">Id - ${link.Id}</div>
                <div class="binn-row__comm-name">${link.Nombre_comercial}</div>
                <div class="binn-row__status">
                    <div class="status-img"><img src="${BASE_URL}assets/img/alert_16750344.png"/></div>
                    ${(link.Estatus === "falta confirmar") ? link.Estatus : "Sin terminar"}
                </div>
            </a>
        `;
        /*finalmente, esta función retorna el elemento html contenido en la constante linkDiv, este elemento html se 
         * concatenará dentro del elemento html contenido en la constante linksArea (del evento change de numkeySelect) con
         * append()*/
        return linkDiv;
    };
    
    /*Esta constante contiene una función que posteriormente se utiliza en el evento change del elemento html el cual
     * está contenido en la constante binnsFilternumkeySelect*/
    const generateTableRow = row =>{
        /*el parametro row contendrá datos que php transformó a JSON gracias a json_encode, lo primero que se hace es
         * crear una constante el cual contendrá un elemento html de una fila de tabla "tr"*/
        const tableRow = document.createElement("tr");
        tableRow.classList.add("binnacle-data-table__row");
        /*dentro de ese elemento tr se le añade elementos td gracias a innerHTML, esos td se contruyen gracias al 
         * acceso de datos del JSON pasado como argumento en la función, también se usa la constante BASE_URL para completar
         * las rutas url correspondientes, hay que aclarar que el controlador UserController en su metodo showBinnacle exige un
         * ID, por lo que se esta accediendo a la propiedad id del JSON para completar el href, tambien hay que aclarar que el 
         * botón de "Eliminar" como propiedad se crea un "dataset" en este caso "data-id", esto para que cada botón de eliminación
         * tenga algo con que identificarlos y que cuando el documento html se cargue y la constante binnDelBtns no sea nula, se
         * puedan gestionar de la mejor forma eventos click para esos botones (eventos que se gestionan más adelante en este 
         * documento)*/
        tableRow.innerHTML = `
            <td class="binnacle-data-table__regular-td"><a class="binnacle-data-table__binn-link" href="${BASE_URL}home/?homeController=user&homeAction=showBinnacle&homeId=${row.Id}">${row.Id}</a></td>
            <td class="binnacle-data-table__regular-td">${row.Nombre} ${row.Apellidos}</td>
            <td>
                <div class="binnacle-data-table__client-name-box">${row.Nombre_completo}</div>
                <div class="binnacle-data-table__enterprise-name-box">${row.Nombre_comercial}</div>
            </td>
            <td class="binnacle-data-table__regular-td"><a class="binnacle-data-table__binn-pdf-link" href="${BASE_URL}home/?homeController=user&homeAction=generateBinnacleReport&homeId=${row.Id}">PDF</a></td>
            <td class="binnacle-data-table__regular-td"><a class="binnacle-data-table__binn-edit-link" href="${BASE_URL}home/?homeController=user&homeAction=editBinnacle&homeId=${row.Id}">Editar</a></td>
            <td class="binnacle-data-table__regular-td"><button class="binnacle-data-table__binn-delete-btn ${(row.Visibilidad === "ENABLED") ? "" : 
                            "activation-background"}" type="button" data-id="${row.Id}" 
                            data-visibility="${row.Visibilidad}">${(row.Visibilidad === "ENABLED") ? "Desactivar" : "Activar"}</button></td>
        `;
        /*finalmente, esta función retorna el elemento html contenido en la constante tableRow, este elemento html se 
         * concatenará dentro del elemento html contenido en la constante binnacleTbody (del evento change de 
         * binnsFilternumkeySelect) con append()*/
        return tableRow;
    };
    
    /*Esta constante contiene una función la cual es posteriormente utilizada dentro del evento "change" del 
     * elemento html contenido por la constante firstFormenterprisesSelect, se añade la
     * palabra clave async debido a que dentro de esta función se crea y utiliza una promesa dentro del proceso
     * de creación de elementos html*/
    const dataManagmentProcedure =  async(jsonRes) =>{
        /*el parametro jsonRes contendrá datos que php transformó a JSON gracias a json_encode*/
        
        if(jsonRes.entInfoForContactForm != null){
            /*Este bloque if corresponde a la recepción de datos del evento change de la caja de selección de empresas en el formulario de "Crear contacto" newContactForm.php*/
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
            
            /*Este bloque if corresponde a la recepción de datos del evento change de la caja de selección de empresas en el formulario de "Nueva bitácora" firstForm.php*/
            
            /*lo que toca hacer es recibir los datos de las propiedades 
             * enterpriseDevices y enterpriseContacts, entonces lo primero que se hace es declarar variables de string*/
            let deviceOptionsHtml = "";
            let contactOptionsHtml = "";
            /*después de declarar las variables string ahora se evalua si la propiedad enterpriseDevices tiene contenido ya que el servidor 
             * puede (dependiendo de lo que haya en la base de datos) devolver un array vacío*/
            if (jsonRes.enterpriseDevices.length > 0) {
                /*Si se entra en la condición, entonces se recorre cada indice de la propiedad con for of, en cada bucle se concatenará un 
                 * texto html de etiqueta option con su respectiva información del indice (la constante device) a nuestra variable optionsHtml*/
                for (const device of jsonRes.enterpriseDevices) {
                    deviceOptionsHtml += `<option value="${device.Id}">${device.Marca} - ${device.Numero_serie}</option>`;
                }
            }
            /*Se hace lo mismo que lo anterior pero ahora con la propiedad enterpriseContacts*/
            if (jsonRes.enterpriseContacts.length > 0) {
                for (const contact of jsonRes.enterpriseContacts) {
                    contactOptionsHtml += `<option value="${contact.Id}">${contact.Nombre_completo}</option>`;
                }
            }
            /*Después de tener listas nuestras variables optionsHtml, entonces se utiliza la constante contactFormTbody (la tabla de campos de contacto) 
             * para configurar su contenido html con la información que nos envío PHP, se agrega con ayuda de la interpolación nuestras variables deviceOptionsHtml 
             * y contactOptionsHtml ya que ahí se está creando los SELECT*/
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


            /*De aqui el por qué se usó async en la función, se utiliza esta tecnica (crear una promesa 
             * que se resuelve después de un intervalo, en este caso 0 segundos), a pesar de que sea 0 
             * segundos, la aplicación web se detiene, es como una especie de "tick" o "hipo", de esta
             * forma se asegura que toda linea de código antes de esta promesa se ejecute correctamente*/
            await new Promise(resolve => setTimeout(resolve, 0));

            /*después de este "hipo", entonces podemos seleccionar con seguridad los elementos html 
             * configurados antes de la promesa, en este caso se selecciona el select de contactos y el 
             * select de los dispositivos y se guardan en sus respectivas constantes, a parte de seleccionar 
             * el botón "Cancelar seleccion" del dispositivo.
             * NOTA: EN ESTE CONTEXTO SE ESTÁ CAMBIANDO EL DOCUMENTO HTML, AUNQUE EN LA SECCION DE CONSTANTES ESTÉN 
             * SELECCIONADOS ESTOS ELEMENTOS (LOS SELECT Y EL BOTÓN CANCELAR DEL DISPOSITIVO) ESTOS YA NO SE CONTEMPLAN 
             * POR MEDIO DE "document" SINO POR MEDIO DE SUS ELEMENTOS PADRE DONDE SE EFECTÚO EL CAMBIO 
             * (contactFormTbody y deviceSelectTbody)*/
            const contactsSelect = contactFormTbody.querySelector("#firstFormContactSelect");
            const devicesSelect = deviceSelectTbody.querySelector("#firstFormDeviceSelect");
            const cancelDevice = deviceSelectTbody.querySelector("#firstFormCancelDeviceSelectBtn");

            /*SELECT2 funciona con JQuery, por lo que se utiliza el selector de esta librería y le ponemos
             * nuestraS constanteS donde contiene el select de los dispositivos Y los contactos respectivamente, finalmente se le aplica el
             * método select2(), como argumento le tenemos que poner un JSON, en este caso para colocarle
             * un placeholder e indicar que el select va a ocupar el 100% de ancho del elemento html tipo bloque
             * que lo contiene*/
            $(contactsSelect).select2({
                placeholder: "Selecciona un contacto",
                width: "100%"
            });

            $(devicesSelect).select2({
                placeholder: "Selecciona un equipo",
                width: "100%"
            });
            /*Tenemos que usar la librería JQuery para gestionar el evento change de nuestro select de
             * dispositivos*/
            $(devicesSelect).on("change", () => {
                /*Si en el select se selecciona un dispositivo, entonces su valor (el cual es el Id del 
                 * dispositivo) se guardará en la constante deviceIdSelected*/
                const deviceIdSelected = devicesSelect.value;
                if (deviceIdSelected !== "") {

                    /*después, se hace una comunicación asincrona con PHP, esta vez, javascript le va a enviar
                     * un JSON con el Id del dispositivo (contenido en nuestra constante deviceIdSelected) como 
                     * valor de la propiedad "deviceId"*/
                    fetch(BASE_URL + "home/?homeController=controlluser&homeAction=newbinnacle", {
                        "method": "POST",
                        "headers": {
                            "Content-Type": "application/json; charset=utf-8"
                        },
                        "body": JSON.stringify({"deviceId": `${deviceIdSelected}`})
                    })
                            .then(res => res.json())
                            .then(data => {
                                /*PHP va a responder con un array asociativo (con la información del dispositivo) 
                                 * convertido a JSON con json_encode, entonces se utiliza la constante deviceFormTbody 
                                 * para modificar su contenido html con la ayuda del acceso de propiedades
                                 * del JSON con el que PHP respondió*/
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
            /*Una vez gestionado el evento de nuestro select de dispositivo, aplicamos un evento "click" al elemento html contenido en
             * cancelDevice*/
            cancelDevice.addEventListener("click", () => {
                /*si se da click al botón de "Cancelar selección" del dispositivo, entonces se "resetea" los campos del "formulario" de dispositivo*/
                deviceFormTbody.innerHTML = `
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

                /*se quita cualquier valor que haya tenido
                 * el select de dispositivo activando su placeholder*/

                $(devicesSelect).val("").trigger("change");
            });
        }
        
    }
    //--------------------------FUNCIONES CALLBACK------------------------------
    
    //--------------------------GESTIÓN DE EVENTOS------------------------------
    
    //-------------------------EVENTOS USUARIO----------------------------------
    
    /*Estos eventos es para evitar que el usuario envíe datos del formulario con solo apretar el teclado "ENTER"*/
    if(binnacleform != null)
    binnacleform.addEventListener("keypress", e =>{
           if(e.key === "Enter"){
               e.preventDefault();
           } 
    });
    
    if(newContactForm != null)
    newContactForm.addEventListener("keypress", e =>{
           if(e.key === "Enter"){
               e.preventDefault();
           } 
    });
    
    if(newTypeForm != null)
    newTypeForm.addEventListener("keypress", e =>{
           if(e.key === "Enter"){
               e.preventDefault();
           } 
    });
    
    if(newDeviceForm != null)
    newDeviceForm.addEventListener("keypress", e =>{
           if(e.key === "Enter"){
               e.preventDefault();
           } 
    });
    
    
    /*Este if contiene funcionalidad de la vista de registro de bitácoras (firstForm.php),
     * este if evalua si estan disponibles elementos html propios de esta vista*/
    if(activityChoice1 != null &&
       activityChoice2 != null &&    
       serviceBox != null      && 
       deviceBox != null       &&
       backWindow != null      &&
       infoWindow != null      &&
       niseSubmit != null      &&
       no != null){
        /*Si todos los elementos clave de la vista de registro de bitácoras no estan nulas
         * entonces podemos gestionar sus eventos click, activityChoice1 contiene el elemento
         * html de radio correspondiente al servicio, entonces, al dar click se remueve
         * la clase de estilo "hidThis" al elemento html del fieldset donde contiene la
         * caja de texto del servicio y agrega la clase de estilo "hidThis" al fieldset
         * que contiene el formulario de registro de equipo*/
        activityChoice1.addEventListener("click", ()=>{
            serviceBox.classList.remove("hidThis");
            deviceBox.classList.add("hidThis");
        });
        /*activityChoice2 contiene el elemento html radio correspondiente al "equipo", al dar 
         * click se remueve la clase "hidThis" al fieldset que contiene el formulario de 
         * registro de equipo y añade la clase de estilo "hidThis" al elemento html del 
         * fieldset donde contiene la caja de texto del servicio*/
        activityChoice2.addEventListener("click", ()=>{
            deviceBox.classList.remove("hidThis");
            serviceBox.classList.add("hidThis");
        });
        /*niseSubmit contiene el elemento html del botón "Registrar", al dar click a este
         * elemento se quita la clase "hidThis" al fondo de la ventana emergente de confirmación
         * de registro de bitácora, el botón "si" es el input tipo submit del formulario y
         * el botón "No" al darle click se le agrega al fondo de la ventana emergente la clase
         * de estilo "hidThis" para ocultar esta ventana emergente al usuario*/
        niseSubmit.addEventListener("click", ()=>{
            backWindow.classList.remove("hidThis");
            if(infoWindow.className.includes("activate-pop-out")) infoWindow.classList.remove("activate-pop-out");
            infoWindow.classList.add("activate-pop-in");
            no.addEventListener("click", () =>{
               infoWindow.classList.remove("activate-pop-in");
               infoWindow.classList.add("activate-pop-out");
               setTimeout(() =>{backWindow.classList.add("hidThis");}, 200); 
               
            });
        });
    }
    
    /*Este if contiene funcionalidad de la vista de seguimiento de bitácoras (followup.php) y
     * evalua si los elementos html propios de esta vista están presentes en el DOM*/
    if(numkeySelect != null && linksArea != null){
        /*a la constante que contiene el select de numero de elementos en pantalla (numkeySelect),
         * se le aplica un evento tipo "change", cuando un usuario seleccióna alguna de las opciones
         * del select en cuestión*/
        numkeySelect.addEventListener("change", () =>{
            /*si el usuario seleccionó un numero, entonces ese valor se guarda en la constante
             * numSelected*/
            const numSelected = numkeySelect.value;
            if(numSelected != null){
                /*Verificamos si esta constante no está vacia, si no lo está, entonces se va a hacer
                 * una comunicación asincrona con PHP, JS va a enviar un JSON con el valor contenido en
                 * la constante numSelected como valor de la propiedad "number"*/
                fetch(BASE_URL + "home/?homeController=user&homeAction=followuplist", {
                    "method": "POST",
                    "headers": {
                        "Content-Type": "application/json; charset=utf-8"
                    },
                    "body": JSON.stringify({"number": `${numSelected}`})
                })
                .then(res => res.json())
                .then(data => {
                    /*PHP va a responder con un JSON una vez haya calculado los elementos de la 
                     * paginación gracias al numero enviado, ese JSON contiene los registros
                     * de la base de datos frutos del calculo y el control de la paginación,
                     * lo primero que se hace es vaciar el elemento html donde va a contener
                     * lo que nuestra función generateLinks devolverá*/
                    linksArea.innerHTML = "";
                    /*binns es una propiedad del JSON que envió php, en esa propiedad hay un array
                     * de JSON (información de cada uno de los registros sacados de la base de datos)
                     * por lo tanto se aplica un forEach para recorrer cada uno de los indices de ese
                     * arreglo, cada indice se guarda en la variable "binn" en la función anonima del
                     * forEach*/
                    data.binns.forEach(binn =>{
                        /*cada indice del arreglo pasará como argumento de nuestra función generateLinks,
                         * nuestra función devuelve un elemento html de link, cada elemento link se
                         * concatenará en el elemento html tipo bloque que contiene la constante
                         * linkArea gracias a append()*/
                        linksArea.append(generateLinks(binn));
                    });
                    /*una vez generado todos lo elementos link, ahora toca guardar el control de paginación
                     * (botones numerados que permite la navegación de cada pagina que la calse zebraPagination
                     * generó), el control de paginación se estaría guardando en el elemento html tipo bloque que
                     * contiene la constante paginationBox*/
                    paginationBox.innerHTML = data.buttons;
                });
            }
            
        });
    }
    
    /*Este if contiene el funcionamiento de los select presentes en la vista
     * de registro de bitácoras (firstForm.php)
     * ATECNIÓN: ESTE BLOQUE IF PUEDE CAMBIAR CONSIDERABLEMENTE SI SE DECIDE
     * CAMBIAR LA FUNCIONALIDAD DEL REGISTRO DE BITÁCORAS*/
    if(firstFormCancelSelectBtn != null && firstFormCancelDeviceSelectBtn != null){
        
        /*SELECT2 funciona con JQuery, por lo que se utiliza el selector de esta librería y le ponemos
         * nuestras constantes donde contienen sus respectivos select (de contactos, empresas y tipos 
         * de equipo), finalmente se le aplica el método select2(), como argumento le tenemos que poner 
         * un JSON, en este caso para colocarle un placeholder e indicar que el select va a ocupar el 
         * 100% de ancho del elemento html tipo bloque que lo contiene*/
        
        $(firstFormenterprisesSelect).select2({
            placeholder: "Selecciona una empresa",
            width: "100%"
        });
        
        $(firstFormContactSelect).select2({
            placeholder: "Selecciona un contacto",
            width: "100%"
        });
        
        $(firstFormDeviceSelect).select2({
            placeholder: "Selecciona un equipo",
            width: "100%"
        });
        
        
        $(firstFormenterprisesSelect).on("change", ()=>{
            /*si el usuario selecciona una empresa, entonces su Id se va a guardar en la constante
             * enterpriseIdSelected*/
            const enterpriseIdSelected = firstFormenterprisesSelect.value;
            //console.log(enterpriseIdSelected);
            if(enterpriseIdSelected !== ""){
                
                /*tambien se "resetea" los campos del "formulario" de equipo*/
                deviceFormTbody.innerHTML = `
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
                /*ya realizado las posteriores configuraciones en el DOM ahora toca hacer una comunicación asincrona
                 * con PHP, JS va a enviar el Id de la empresa como valor de la propiedad "enterpriseId"*/    
                fetch(BASE_URL + "home/?homeController=user&homeAction=newbinnacle", {
                    "method": "POST",
                    "headers": {
                        "Content-Type": "application/json; charset=utf-8"
                    },
                    "body": JSON.stringify({"enterpriseId": `${enterpriseIdSelected}`})
                })
                .then(res => res.json())
                .then(data => {
                    /*PHP envia un JSON una vez usado el Id de la empresa para conseguir su respectiva
                     * información en la base de datos, data contiene las propiedades entInfo y enterpriseDevices,
                     * cada uno posee como valor un array de JSON con sus respectivos registros sacados de la base
                     * de datos, entInfo tiene la información de la empresa y enterpriseDevices tiene todos
                     * los registros de equipos vinculados a la empresa que se seleccionó,
                     * nuestra función dataManagmentProcedure es una función tipo procedimiento, quiere decir que no
                     * devuelve nada, su unica función es modificar el documento html (ve a la parte de declaración de
                     * esta función para conocer sus funciones)*/
                    dataManagmentProcedure(data);
                });            
            }
        });
        
        
        /*firstFormCancelSelectBtn contiene el elemento html del botón de "cancelar selección" de la vista del
         * registro de bitácoras*/
        firstFormCancelSelectBtn.addEventListener("click", ()=>{
            
            
            /*se "resetea" los inputs del "formulario" del cliente (contacto), inputs contenidos en el elemento
             * html que contiene la constante contactFormTbody*/
            
            contactFormTbody.innerHTML = `
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
            /*de igual forma, se "resetea" los campos de equipos*/
            deviceSelectTbody.innerHTML = `
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
            
            deviceFormTbody.innerHTML = `
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
            /*Finalmente se "limpia" el select de empresas, esto es
             * procurar que no tenga ningun valor seleccionado*/
            $(firstFormenterprisesSelect).val("").trigger("change");
            /* NOTA: EN ESTE CONTEXTO SE ESTÁ CAMBIANDO EL DOCUMENTO HTML, AUNQUE EN LA SECCION DE CONSTANTES ESTÉN 
             * SELECCIONADOS ESTOS ELEMENTOS (LOS SELECT DE CONTACTOS Y EQUIPOS) ESTOS YA NO SE CONTEMPLAN 
             * POR MEDIO DE "document" SINO POR MEDIO DE SUS ELEMENTOS PADRE DONDE SE EFECTÚO EL CAMBIO 
             * (contactFormTbody y deviceSelectTbody)*/
                const contactsSelect = contactFormTbody.querySelector("#firstFormContactSelect");
                const devicesSelect = deviceSelectTbody.querySelector("#firstFormDeviceSelect");

                /*SELECT2 funciona con JQuery, por lo que se utiliza el selector de esta librería y le ponemos
                 * nuestraS constanteS donde contiene el select de los dispositivos Y los contactos respectivamente, finalmente se le aplica el
                 * método select2(), como argumento le tenemos que poner un JSON, en este caso para colocarle
                 * un placeholder e indicar que el select va a ocupar el 100% de ancho del elemento html tipo bloque
                 * que lo contiene*/
                $(contactsSelect).select2({
                    placeholder: "Selecciona un contacto",
                    width: "100%"
                });

                $(devicesSelect).select2({
                    placeholder: "Selecciona un equipo",
                    width: "100%"
                });
            
        });
        
    }
    
    /*Este if evalúa si existe en el DOM elementos html propios de la vista de formulario de registro de contacto*/
    if(newContactFormCancelSelectBtn != null && newContectFormEnterprisesSelect != null){
        
        $(newContectFormEnterprisesSelect).select2({
            placeholder: "crear contacto con empresa existente",
            width: "100%"
        });
        
        /*Se gestiona el evento "click" del botón "cancelar selección" del formulario*/
        newContactFormCancelSelectBtn.addEventListener("click", ()=>{
            contactFormTbody.innerHTML = `
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
            /*Se quita cualquir valor que haya tenido la caja de selección de empresas en ese momento*/
            $(newContectFormEnterprisesSelect).val("").trigger("change");
        });
        
        /*niseSubmit contiene el elemento html del botón "Registrar", al dar click a este
         * elemento se quita la clase "hidThis" al fondo de la ventana emergente de confirmación
         * de registro de un contacto, el botón "si" es el input tipo submit del formulario y
         * el botón "No" al darle click se le agrega al fondo de la ventana emergente la clase
         * de estilo "hidThis" para ocultar esta ventana emergente al usuario*/
        niseSubmit.addEventListener("click", ()=>{
            backWindow.classList.remove("hidThis");
            if(infoWindow.className.includes("activate-pop-out")) infoWindow.classList.remove("activate-pop-out");
            infoWindow.classList.add("activate-pop-in");
            no.addEventListener("click", () =>{
               infoWindow.classList.remove("activate-pop-in");
               infoWindow.classList.add("activate-pop-out");
               setTimeout(() =>{backWindow.classList.add("hidThis");}, 200); 
               
            });
        });
        
        $(newContectFormEnterprisesSelect).on("change", ()=>{
            /*si el usuario selecciona una empresa, entonces su Id se va a guardar en la constante
             * enterpriseIdSelected*/
            const enterpriseIdSelected = newContectFormEnterprisesSelect.value;
            console.log(enterpriseIdSelected);
            if(enterpriseIdSelected !== ""){
                /*toca hacer una comunicación asincrona
                 * con PHP, JS va a enviar el Id de la empresa como valor de la propiedad "enterpriseId"*/    
                fetch(BASE_URL + "home/?homeController=user&homeAction=newbinnacle", {
                    "method": "POST",
                    "headers": {
                        "Content-Type": "application/json; charset=utf-8"
                    },
                    "body": JSON.stringify({"newContactEnterId": `${enterpriseIdSelected}`})
                })
                .then(res => res.json())
                .then(data => {
                    /*PHP envia un JSON una vez usado el Id de la empresa para conseguir su respectiva
                     * información en la base de datos, data contiene la propiedad entInfoForContactForm,
                     * posee como valor un array de JSON que contiene como propiedades las columnas sacadas de la base
                     * de datos, entInfoForContactForm tiene la información de la empresa,
                     * nuestra función dataManagmentProcedure es una función tipo procedimiento, quiere decir que no
                     * devuelve nada, su unica función es modificar el documento html (ve a la parte de declaración de
                     * esta función para conocer sus funciones)*/
                    dataManagmentProcedure(data);
                });            
            }
        });
    }
    
    /*Este if evalúa si existe en el DOM un elemento html propio de la vista de formulario de registro de tipo de equipo*/
    if(TypeFormField != null){
        /*niseSubmit contiene el elemento html del botón "Registrar", al dar click a este
         * elemento se quita la clase "hidThis" al fondo de la ventana emergente de confirmación
         * de registro de un tipo, el botón "si" es el input tipo submit del formulario y
         * el botón "No" al darle click se le agrega al fondo de la ventana emergente la clase
         * de estilo "hidThis" para ocultar esta ventana emergente al usuario*/
        niseSubmit.addEventListener("click", ()=>{
            backWindow.classList.remove("hidThis");
            if(infoWindow.className.includes("activate-pop-out")) infoWindow.classList.remove("activate-pop-out");
            infoWindow.classList.add("activate-pop-in");
            no.addEventListener("click", () =>{
                infoWindow.classList.remove("activate-pop-in");
                infoWindow.classList.add("activate-pop-out"); 
                setTimeout(() => {backWindow.classList.add("hidThis");}, 200);
            });
        });
    }
    
    /*Este if evalúa si existe en el DOM elementos html propios de la vista de formulario de registro de un equipo*/
    if(newDeviceFormTbody != null && newDeviceFormTypSelect != null){
        /*SELECT2 funciona con JQuery, por lo que se utiliza el selector de esta librería y le ponemos
         * nuestras constantes donde contiene el select de los tipos de equipo Y las empresas respectivamente, finalmente se le aplica el
         * método select2(), como argumento le tenemos que poner un JSON, en este caso para colocarle
         * un placeholder e indicar que el select va a ocupar el 100% de ancho del elemento html tipo bloque
         * que lo contiene*/
         $(newDeviceFormEntSelect).select2({
            placeholder: "Empresa dueña del equipo",
            allowClear: true,
            width: "100%"
         });
        
         $(newDeviceFormTypSelect).select2({
            placeholder: "Selecciona un tipo",
            allowClear: true,
            width: "100%"
         });
         
         /*niseSubmit contiene el elemento html del botón "Registrar", al dar click a este
         * elemento se quita la clase "hidThis" al fondo de la ventana emergente de confirmación
         * de registro de un equipo, el botón "si" es el input tipo submit del formulario y
         * el botón "No" al darle click se le agrega al fondo de la ventana emergente la clase
         * de estilo "hidThis" para ocultar esta ventana emergente al usuario*/
         niseSubmit.addEventListener("click", ()=>{
            backWindow.classList.remove("hidThis");
            if(infoWindow.className.includes("activate-pop-out")) infoWindow.classList.remove("activate-pop-out");
            infoWindow.classList.add("activate-pop-in");
            no.addEventListener("click", () =>{
                infoWindow.classList.remove("activate-pop-in");
                infoWindow.classList.add("activate-pop-out");
                setTimeout(() => {backWindow.classList.add("hidThis");}, 200); 
            });
        });
    }
    //-------------------------EVENTOS USUARIO----------------------------------
    
    //-------------------------EVENTOS ADMINISTRADOR----------------------------
    
    /*Estos eventos es para evitar que el usuario envíe datos del formulario con solo apretar el teclado "ENTER"*/
    if(userForm != null)
    userForm.addEventListener("keypress", e =>{
           if(e.key === "Enter"){
               e.preventDefault();
           } 
    });
    
    if(enterForm != null)
    enterForm.addEventListener("keypress", e =>{
           if(e.key === "Enter"){
               e.preventDefault();
           } 
    });
    
    if(contactsForms != null)
    contactsForms.forEach(contactForm =>{
        contactForm.addEventListener("keypress", e =>{
           if(e.key === "Enter"){
               e.preventDefault();
           } 
        });
    });    
    
    
    if(typesForms != null)
    typesForms.forEach(typeForm =>{
        typeForm.addEventListener("keypress", e =>{
           if(e.key === "Enter"){
               e.preventDefault();
           } 
        });
    });

    if(devicesForms != null)
    devicesForms.forEach(deviceForm =>{
        deviceForm.addEventListener("keypress", e =>{
           if(e.key === "Enter"){
               e.preventDefault();
           } 
        });
    });
    
    
    /*Este bloque if evalua si existen elementos html propios de la vista del administrador*/
    if(adminMenuWrapper != null &&
       mainsWrapper != null && mobileBtn !== null){
       
        /*este if modifica el estilo del elemento html propio de la vista de seguimiento de
        * bitácoras para ajustarlo a la vista del administrador*/
       if(numkeyBox != null){
           numkeyBox.style.width = "50%";
       }
       
       
       if(userCreationNiseSubmit != null){
            
            /*userCreationNiseSubmit el cual contiene un elemento html propio de la vista 
            * userInsertForm.php, si su valor no es nulo, entonces se aplica un evento
            * click para este elemento, al dar click, al fondo de la ventana emergente de esta
            * vista se le quitará la clase de estilo "hidThis" para mostrar esta ventana emergente
            * al usuario*/
           userCreationNiseSubmit.addEventListener("click", ()=>{
                userCreationbackWindow.classList.remove("hidThis");
                if(userCreationinfoWindow.className.includes("activate-pop-out")) userCreationinfoWindow.classList.remove("activate-pop-out");
                userCreationinfoWindow.classList.add("activate-pop-in");
           });
       }
       
       if(userCreationCancel != null){
           /*userCreationCancel el cual contiene un elemento html propio de la vista 
            * userInsertForm.php, si su valor no es nulo, entonces se aplica un evento
            * click para este elemento, al dar click, al fondo de la ventana emergente de esta
            * vista se le agregará la clase de estilo "hidThis" para ocultar esta ventana
            * emergente al usuario*/
           userCreationCancel.addEventListener("click", ()=>{
                userCreationinfoWindow.classList.remove("activate-pop-in");
                userCreationinfoWindow.classList.add("activate-pop-out");
                setTimeout(() => {userCreationbackWindow.classList.add("hidThis");}, 200);
           });
       }
       
       if(userDeleteNiseSubmit !== null){
           /*userDeleteNiseSubmit el cual contiene un elemento html propio de la vista 
            * userNewPwd.php, si su valor no es nulo, entonces se aplica un evento
            * click para este elemento, al dar click, al fondo de la ventana emergente de esta
            * vista se le quitará la clase de estilo "hidThis" para mostrar esta ventana
            * emergente al usuario*/
           userDeleteNiseSubmit.addEventListener("click", ()=>{
               userDeletebackWindow.classList.remove("hidThis");
                if(userDeleteinfoWindow.className.includes("activate-pop-out")) userDeleteinfoWindow.classList.remove("activate-pop-out");
                userDeleteinfoWindow.classList.add("activate-pop-in");
           });
       }
       
       if(userDeleteCancel !== null){
           /*userDeleteCancel el cual contiene un elemento html propio de la vista 
            * userInsertForm.php, si su valor no es nulo, entonces se aplica un evento
            * click para este elemento, al dar click, al fondo de la ventana emergente de esta
            * vista se le agregará la clase de estilo "hidThis" para ocultar esta ventana
            * emergente al usuario*/
           userDeleteCancel.addEventListener("click", ()=>{
               userDeleteinfoWindow.classList.remove("activate-pop-in");
               userDeleteinfoWindow.classList.add("activate-pop-out");
               setTimeout(() => {userDeletebackWindow.classList.add("hidThis");}, 200);
           });
       }
       
       /*SELECT2 funciona con JQuery, por lo que se utiliza el selector de esta librería y le ponemos
         * nuestras constantes donde contienen sus respectivos select (de usuarios (empleados de la empresa), empresas y tipos 
         * de equipo), finalmente se le aplica el método select2(), como argumento le tenemos que poner 
         * un JSON, en este caso para colocarle un placeholder e indicar que el select va a ocupar el 
         * 100% de ancho del elemento html tipo bloque que lo contiene, estos select PHP se encarga de gestionar su información*/
       $(usersSelect).select2({
            placeholder: "Selecciona un usuario",
            width: "100%"
        });
        
        $(editEnterSelect).select2({
            placeholder: "Selecciona una empresa",
            width: "100%"
        });
        
        $(editDevicesEnterSelect).select2({
            placeholder: "Selecciona una empresa",
            width: "100%",
            allowClear: true
        });
        
        
        $(entersSelect).select2({
            placeholder: "Selecciona una empresa",
            width: "100%",
            allowClear: true
        });
        
        $(typeSelect).select2({
            placeholder: "Selecciona un tipo",
            width: "100%"
        })
        
        /*a la ventana del navegador se le aplica un evento cuando el usuario modifique el tamaño 
         * de la ventana del navegador*/
        window.addEventListener("resize", ()=>{
            /*si el usuario cambia el tamaño de la ventana, entonces el valor numerico del tamaño de la
             * ventana estará contenido en la variable clientWidth*/
            let clientWidth = parseInt(document.body.clientWidth);
            if(clientWidth <= 1258){
                /*si clientWidth es menor o igual a 1258 entonces el menú lateral izquierdo se ocultará
                 * cambiando su display a "none"*/
                adminMenuWrapper.style.display = "none";
                /*después, el botón de menú para moviles se mostrará modificando su display a "block"*/
                mobileBtn.style.display = "block";
                /*finalmente, el div que puede alojar todas las vistas de administrador cambia su ancho
                 * al 100% del body que lo contiene*/
                mainsWrapper.style.width = "100%";
            }else{
                /*Si clientWidth no es menor o igual a 1258, entonces lo primero que se hace es acceder 
                 * a la segunda clase de estilo del elemento html de la barra de menú para moviles contenido 
                 * en la constante mobileNavBar y esa clase va a alojarse en la constante visibleClass*/
                const visibleClass = mobileNavBar.classList[1];
                if(visibleClass === "mobile-navbar--show"){
                    /*en dado caso que se cumpla la condición, entonces al elemento html de la barra de menú 
                     * para moviles se le removera esa clase de estilo, esto hara que desaparesca de la vista
                     * del usuario*/
                    mobileNavBar.classList.remove("mobile-navbar--show");
                }
                /*despues se mostrará el menú lateral izquierdo modificando su display a "block"*/
                adminMenuWrapper.style.display = "block";
                /*Luego se oculta el botón que despliega el menú para moviles modificando su display a "none"*/
                mobileBtn.style.display = "none";
                /*Finalmente, se modifica el ancho del contenedor de etiquetas <main> (vistas del administrador) al 75%*/
                mainsWrapper.style.width = "75%";
            }
            /*este if es para modificar el input correspondiente a "Alias" de la vista del formulario para agregar un
             * usuario*/
            if(clientWidth <= 629 && alias !== null){
                /*Si el clientWidth es menor o igual a 629 y si el elemento html contenido en la constante alias existe en
                 * el DOM, entonces se modifica su margen superior a un valor más alto*/
                alias.style.marginTop = "4.5rem";
            }else{
                if(alias !== null){
                    /*Si el clientWidth no es menor o igual a 629 y si el elemento html contenido en la constante alias existe,
                     * enotnces su margen superior se modificará a un valor menor*/
                alias.style.marginTop = "1rem";
                }
            }
            
        });
        /*Estos if se crearon debido a que puede que el usuario refresque la pagina pero con el tamaño de la ventana menor a 
         * lo abitual*/
        if(window.innerWidth <= 1258){
            /*si el usuario refresca la pagina teniendo un ancho de la ventana de navegador menor o igual a 1258
             * entonces se oculta el menú lateral y se alarga el contenedor de <main> (vistas del administrador)
             * al 100%*/
            adminMenuWrapper.style.display = "none";
            mainsWrapper.style.width = "100%";
        }
        
        if(window.innerWidth <= 629){
            if(alias !== null){
                /*si el usuario refresca la pagina teniendo un ancho de la ventana de navegador menor o igual a 629 y si 
                 * el elemento html contenido en la constante alias existe, entonces se modifica el margen superior de 
                 * alias a un valor mayor*/
               alias.style.marginTop = "4.5rem"; 
           }   
        }
        
        if(window.innerWidth >= 1258){
            /*si el usuario refresca la pagina teniendo un ancho de la ventana de navegador mayor o igual a 1258,
             * entonces oculta el botón de despliegue de menu para moviles modificando su display a "none"*/
            mobileBtn.style.display = "none";
        }
        
        /*adminMenuLis corresponde a los elementos li del menú del administrador de la barra lateral izquierda, este 
         * for each recorre todos los elementos li de ese menú*/
        adminMenuLis.forEach(menuLi =>{
            /*cada uno de los elementos se le aplica un evento click*/
            menuLi.addEventListener("click", ()=>{
                /*hay algunos elementos li que tienen un "submenú", la constante submenu obtiene el ultimo elemento 
                 * html que contiene el elemento li en cuestión (el ultimo elemento es un ul)*/
                const subMenu = menuLi.lastElementChild;
                if(subMenu.className === "linkList__submenu-links"){
                    /*si un elemento li tiene un submenu (ul) entonces se entra a este if, los li con submenus tienen un div 
                     * que contiene un elemento tipo icono, la constante iconBox contiene ese div*/
                    const iconBox = menuLi.querySelector(".row-link__icon-wrapper");
                    /*en este if se accede al estilo css del submenu*/
                    if(subMenu.style.display === "block"){
                        /*si se da click a un elemento li del menú del administrador y este tiene un submenu con un estilo display block, 
                         * entonces se cambia el estilo a none y se utiliza la 
                         * constante iconBox para modificar el icono chevron (flecha hacia abajo)*/
                        subMenu.style.display = "none";
                        iconBox.innerHTML = '<i class="fa-solid fa-chevron-down">';
                    }else{
                        /*si se da click a un elemento li del menú del administrador y este tiene un submenu con un estilo display none, 
                         * entonces se cambia el estilo a block y se utiliza la 
                         * constante iconBox para modificar el icono chevron (flecha hacia arriba)*/
                        subMenu.style.display = "block";
                        iconBox.innerHTML = '<i class="fa-solid fa-chevron-up">';
                    }
                }
            });
        });
        
        /*adminMobileMenuLis tiene todos los elementos li de la barra de menú para móviles, se crean eventos click para todos esos elementos 
         * y la logica es similar al del menú para administrador de la barra lateral izquierda*/
        adminMobileMenuLis.forEach(mobileMenuLi =>{
            mobileMenuLi.addEventListener("click", ()=>{
                const subMenu = mobileMenuLi.lastElementChild;
                if(subMenu.className === "mobile-submenu__linkList"){
                    const iconBox = mobileMenuLi.querySelector(".mobile-navbar__icon-wrapper");
                    if(subMenu.style.display === "block"){
                        subMenu.style.display = "none";
                        iconBox.innerHTML = '<i class="fa-solid fa-chevron-down" style="top: 1.5rem;"></i>';
                    }else{
                        subMenu.style.display = "block";
                        iconBox.innerHTML = '<i class="fa-solid fa-chevron-up" style="top: 1.5rem;"></i>';
                    }
                }
            });
        });
        
        /*Se le aplica un evento al botón para despliegue de menu para moviles*/
        mobileBtn.addEventListener("click", ()=>{
            /*si el usuario da click a ese botón, entonces lo primero que se hace es acceder 
                 * a la segunda clase de estilo del elemento html de la barra de menú para moviles contenido 
                 * en la constante mobileNavBar y esa clase va a alojarse en la constante visibleClass*/
            const visibleClass = mobileNavBar.classList[1];
            if(visibleClass === "mobile-navbar--show"){
                /*si la barra de menú para moviles tiene una clase que hace que sea visible para el usuario,
                 * entonces se elimina esa clase para ocultar el menú*/
                mobileNavBar.classList.remove("mobile-navbar--show");
            }else{
                /*si la barra de menú para moviles no tiene una clase que hace que sea visible para el usuario,
                 * entonces se agrega esa clase para que el usuario pueda visualizar ese menú*/
                mobileNavBar.classList.add("mobile-navbar--show");
            }
            
        });
        /*mobileCancelBtn es el botón "X" de la barra de menú para moviles*/
        mobileCancelBtn.addEventListener("click", ()=>{
            /*si el usuario da click a ese botón, entonces lo primero que se hace es acceder 
                 * a la segunda clase de estilo del elemento html de la barra de menú para moviles contenido 
                 * en la constante mobileNavBar y esa clase va a alojarse en la constante visibleClass*/
            const visibleClass = mobileNavBar.classList[1];
            if(visibleClass === "mobile-navbar--show"){
                /*si la barra de menú para moviles tiene una clase que hace que sea visible para el usuario,
                 * entonces se elimina esa clase para ocultar el menú*/
                mobileNavBar.classList.remove("mobile-navbar--show");
            }
        });
        
        /*Se  evalua si existe
         * un elemento html propio de la vista de edicción de empresa (enterAndContactsForms.php)*/
        if(enterpriseFormsMain != null){
            
            if(enterDeleteButton != null){
                /*Si se cumple la condición, quiere decir que estamos en la vista de enterAndContactsForms.php, entonces lo primero
                 * que se hace es aplicar un evento al botón "Eliminar" de la empresa*/
                enterDeleteButton.addEventListener("click", ()=>{
                    /*Al dar click al botón eliminar se quita la clase de estilo "hidThis" al fondo de la ventana emergente de
                     * confirmación de eliminación de empresa*/
                    enterOrClientDeletebackWindow.classList.remove("hidThis");
                    
                    /*la ventana emergente en este caso es un formulario, los datos del formulario se van a procesar dentro del metodo enableOrDisableEnterprise
                     * del controlador UserController*/
                    enterOrClientDeletebackWindow.innerHTML = `
                        <form class="enter-or-client-delete__info-window" action="${BASE_URL}home/?homeController=user&homeAction=enableOrDisableEnterprise" method="POST">
                            <div class="pop-up-window-icon"><img class="pop-up-window-icon__img" src="${BASE_URL}assets/img/caution-sign_75243.png"/></div>
                            <div class="info-window__text-box"><h3>¿Está seguro de ${(enterDeleteButton.dataset.visibility === "ENABLED") ? "desactivar" : "activar"} esta empresa?, 
                                            este registro ${(enterDeleteButton.dataset.visibility === "ENABLED") ? 'no será visible' : 'será visible'} 
                                            en la caja de selección de los formularios de "Nueva bitácora", "Crear contacto", "Crear un equipo" y la caja de selección 
                                            de empresas en los filtros de "Reportes de bitácoras"</h3></div>
                            <input type="hidden" value="${enterDeleteButton.dataset.id}" name="empresaId"/>
                            <input type="hidden" value="${(enterDeleteButton.dataset.visibility === "ENABLED") ? 'DISABLED' : 'ENABLED'}" name="visibilidad"/>
                            <div class="info-window__selectbuttons-box">
                               <input class="selectbuttons-box__button" type="submit" value="${(enterDeleteButton.dataset.visibility === "ENABLED") ? 'Desactivar' : 'Activar'}"/>
                               <button class="selectbuttons-box__cancel-delete-button" type="button">Cancelar</button>
                            </div>
                        </form>
                    `;
                    
                    /*Se selecciona los elementos html generados en este contexto, el botón "Cancelar" en la constante cancelDeleteBtn
                     * y la ventana emergente que en este caso es un formulario a la constante deleteFormWindow*/
                    const cancelDeleteBtn = enterOrClientDeletebackWindow.querySelector(".selectbuttons-box__cancel-delete-button");
                    const deleteFormWindow = enterOrClientDeletebackWindow.querySelector(".enter-or-client-delete__info-window");
                    deleteFormWindow.classList.add("activate-pop-in");
                    cancelDeleteBtn.addEventListener("click", ()=>{
                        deleteFormWindow.classList.remove("activate-pop-in");
                        deleteFormWindow.classList.add("activate-pop-out");
                        setTimeout(() => {
                            /*Si se da click al botón cancelar, entonces se utiliza la constante deleteFormWindow para eliminar su contenido
                            * html gracias a remove() esto para evitar duplicaciones innecesarias de elementos html en el DOM*/
                            deleteFormWindow.remove();
                            /*finalmente se le agrega al fondo de la ventana emergente la clase de estilo "hidThis" para ocultar esta ventana
                            * al usuario*/
                            enterOrClientDeletebackWindow.classList.add("hidThis");
                        }, 200);
                        
                    });
                });
                /*enterpriseEditBtn contiene el elemento html botón "Guardar" dentro de la edición de la empresa*/
                enterpriseEditBtn.addEventListener("click", ()=>{
                    /*Si se da click al botón "Guardar", entonces se le quita la clase de estilo "hidThis" al fondo de la
                     * ventana emergente de confirmación de edición de empresa para que el usuario pueda ver esta ventana*/
                    enterpriseEditConfirmationBackground.classList.remove("hidThis");
                    if(enterEditInfoWindow.className.includes("activate-pop-out")) enterEditInfoWindow.classList.remove("activate-pop-out");
                    enterEditInfoWindow.classList.add("activate-pop-in");
                });

                enterpriseEditeCancelBtn.addEventListener("click", ()=>{
                    enterEditInfoWindow.classList.remove("activate-pop-in");
                    enterEditInfoWindow.classList.add("activate-pop-out");
                    /*Si el usuario da click al botón "Cancelar", entonces se le agrega la clase de estilo "hidThis" al fondo de la
                     * ventana emergente para ocultarselo al usuario*/
                    setTimeout(() => {enterpriseEditConfirmationBackground.classList.add("hidThis");}, 200);
                    
                });
            }
            if(deleteContactBtns != null){
                /*Si en la edición de empresa hay contactos, entonces deleteContactBtns no esta vacía (son los botones 
                 * "Desactivar contacto"), lo primero que se hace es recorrer con forEach todos los elementos html contenidos en la constante
                 * deleteContactBtns ya que puede haber más de un contacto*/
                deleteContactBtns.forEach(deleteBtn =>{
                    /*Cada botón se le gestionará el evento click*/
                   deleteBtn.addEventListener("click", ()=>{
                       /*Si se da click a un botón "Desactivar contacto" lo primero que se hace es quitar la clase "hidThis" al fondo de la
                        * ventana emergente de confirmación de eliminación de cliente (contacto), esto para que el usuario pueda ver esta
                        * ventana*/
                       enterOrClientDeletebackWindow.classList.remove("hidThis");
                       /*despues se modifica la ventana emergente, en este caso, su contenido html es un formulario, como cada botón
                        * tiene su propio dataset el cual contiene el Id del contacto correspondiente (y tambien su visibilidad) (esto del dataset se hace en la vista 
                        * de edición de empresas enterAndContactsForms.php, osease que PHP se encarga de llenar los dataset), entonces se accede al dataset para
                        * incluir en el formulario el id del cliente a desactivar/activar, el id del contacto y la visibilidad se alojan en sus respectivos inputs 
                        * tipo "hidden", estos datos los procesará el metodo enableOrDisableContact del controlador UserController*/
                       enterOrClientDeletebackWindow.innerHTML = `
                            <form class="enter-or-client-delete__info-window" action="${BASE_URL}home/?homeController=user&homeAction=enableOrDisableContact" method="POST">
                                <div class="pop-up-window-icon"><img class="pop-up-window-icon__img" src="${BASE_URL}assets/img/caution-sign_75243.png"/></div>
                                <div class="info-window__text-box"><h3>¿Está seguro de ${(deleteBtn.dataset.visibility === "ENABLED") ? "desactivar" : "activar"} el contacto 
                                        con Id ${deleteBtn.dataset.id}?, este registro ${(deleteBtn.dataset.visibility === "ENABLED") ? 'no será visible' : 'será visible'} 
                                        en la caja de selección del formulario de "Nueva bitácora" y la caja de selección 
                                            de contactos en los filtros de "Reportes de bitácoras"</h3></div>
                                <input type="hidden" value="${deleteBtn.dataset.id}" name="contactoId"/>
                                <input type="hidden" value="${(deleteBtn.dataset.visibility === "ENABLED") ? 'DISABLED' : 'ENABLED'}" name="visibilidad"/>
                                <div class="info-window__selectbuttons-box">
                                   <input class="selectbuttons-box__button" type="submit" value="${(deleteBtn.dataset.visibility === "ENABLED") ? 'Desactivar' : 'Activar'}"/>
                                   <button class="selectbuttons-box__cancel-delete-button" type="button">Cancelar</button>
                                </div>
                            </form>
                        `;
                        /*Se selecciona los elementos html generados en este contexto, el botón "Cancelar" en la constante contactDeleteCancelBtn
                         * y la ventana emergente que en este caso es un formulario a la constante contactDeleteFormWindow*/    
                        const contactDeleteCancelBtn = enterOrClientDeletebackWindow.querySelector(".selectbuttons-box__cancel-delete-button");
                        const contactDeleteFormWindow = enterOrClientDeletebackWindow.querySelector(".enter-or-client-delete__info-window");
                        contactDeleteFormWindow.classList.add("activate-pop-in");
                        contactDeleteCancelBtn.addEventListener("click", ()=>{
                            contactDeleteFormWindow.classList.remove("activate-pop-in");
                            contactDeleteFormWindow.classList.add("activate-pop-out");
                            setTimeout(() => {
                                /*Si se da click al botón cancelar, entonces se utiliza la constante contactDeleteFormWindow para eliminar su contenido
                                * html gracias a remove() esto para evitar duplicaciones innecesarias de elementos html en el DOM*/
                                contactDeleteFormWindow.remove();
                                /*finalmente se le agrega al fondo de la ventana emergente la clase de estilo "hidThis" para ocultar esta ventana
                                * al usuario*/
                                enterOrClientDeletebackWindow.classList.add("hidThis");
                            }, 200);
                            
                        });
                   }); 
                });
            }
            /*Este if evalua si es que existe los botones "Guardar" y "Cancelar" (de ventana emergente) de cada uno de los contactos
             * presentes en la vista enterAndContactsForms.php, si es que existen, entonces se van a gestionar eventos click a estos botones*/
            if(contactEditBtns != null && contactEditCancelBtns != null){
                /*Se recorre todos los botones "Guardar" ya que puede haber más de un contacto, se utiliza forEach, en su función anonima
                 * se utiliza como argumento (editBtn) los indices del arreglo de botones contenido en la constante contactEditBtns*/
                contactEditBtns.forEach(editBtn =>{
                    /*cada botón "Guardar" tendrá su respectivo evento click*/
                   editBtn.addEventListener("click", ()=>{
                      /*si se da click al botón "Guardar" entonces lo primero que se hace es seleccionar un elemento html cercano al botón en cuestión,
                       * cabe aclarar que cada contacto en la edición de empresa tiene sus propias ventanas emergentes de confirmación
                       * de edición de contacto contacto tal como se ve en el foreach del archivo enterAndContactsForms.php, entonces lo primero que se selecciona es
                       * el formulario del cliente (contacto) cuyo botón "Guardar" esta contenido en ese formulario (el respectivo formulario es el 
                       * elemento más cercano del botón que se está manejando en el forEach), ese formulario va a estar contenido en la constante
                       * parentForm*/ 
                        const parentForm = editBtn.closest(".enterprise-forms__contact-form");
                        /*una vez delcarado parentForm entonces lo utilizamos para acceder al elemento html de la ventana emergente de confirmación de
                        * edición de cliente (contacto), ese elemento estará contenido en la constante editConfirmationWindow*/
                        const editConfirmationWindow = parentForm.querySelector(".contact-edit__background");
                        const editConfirmationInfoWindow = editConfirmationWindow.querySelector(".contact-edit__info-window");
                        /*Después, al fondo de la ventana emergente se le quita la clase de estilo "hidThis" para mostrarle al usuario la ventana
                        * emergente de confirmación de edición de cliente (contacto) en cuestión*/
                        editConfirmationWindow.classList.remove("hidThis");
                        if(editConfirmationInfoWindow.className.includes("activate-pop-out")) editConfirmationInfoWindow.classList.remove("activate-pop-out");
                        editConfirmationInfoWindow.classList.add("activate-pop-in");
                   }); 
                });
                /*Se recorre todos los botones "Cancelar" de las ventanas emergentes de confirmación de edición de contacto generadas en 
                 * la vista enterpriseForms.php, se utiliza forEach en su función anonima se utiliza como argumento (cancelBtn) los indices del 
                 * arreglo de botones contenido en la constante contactEditCancelBtns*/
                contactEditCancelBtns.forEach(cancelBtn =>{
                   /*cada botón "Guardar" tendrá su respectivo evento click*/ 
                   cancelBtn.addEventListener("click", ()=>{
                       /*si se da click al botón "Cancelar" entonces lo primero que se hace es seleccionar un elemento html cercano al botón en cuestión,
                       * cabe aclarar que cada contacto en la edición de empresa tiene sus propias ventanas emergentes de confirmación
                       * de edición de contacto tal como se ve en el foreach del archivo enterAndContactsForms.php, entonces lo primero que se selecciona es
                       * el fondo de la ventana emergente de confirmación de edición de contacto cuyo botón "Cancelar" esta contenido en esa estructura de ventana 
                       * (la respectiva estructura de ventana emergente de confirmación de edición de contacto es el elemento más cercano del botón que 
                       * se está manejando en el forEach), esa estructura de ventana emergente va a estar contenida en la constante parentWindow*/
                       const parentWindow = cancelBtn.closest(".contact-edit__background");
                       const parentInfoWindow = parentWindow.querySelector(".contact-edit__info-window");
                       /*Después, al fondo de la ventana emergente se le agrega la clase de estilo "hidThis" para ocultarle al usuario la ventana
                       * emergente de confirmación de edición de cliente (contacto) en cuestión*/
                        parentInfoWindow.classList.remove("activate-pop-in");
                        parentInfoWindow.classList.add("activate-pop-out");
                        setTimeout(() => {parentWindow.classList.add("hidThis");}, 200);
                        
                   });
                });
            }
            
        }
        
        /*Este bloque if verifica un elemento html propio de la vista typesEditForms.php, se reutilizan los elementos html (los botones) de los contactos de la 
         * vista enterAndContactsEditForms, estos tendrán como dataset el id de tipos y ventanas de activación o desactivación personalizables para los registros 
         * de tipos*/
        if(typesEditFormsMain != null){
            
            if(deleteContactBtns != null){
                /*lo primero que se hace es recorrer con forEach todos los elementos html contenidos en la constante
                 * deleteContactBtns ya que puede haber más de un formulario (tipos en este caso)*/
                deleteContactBtns.forEach(deleteBtn =>{
                    /*Cada botón se le gestionará el evento click*/
                   deleteBtn.addEventListener("click", ()=>{
                       /*Si se da click a un botón "Desactivar tipo" lo primero que se hace es quitar la clase "hidThis" al fondo de la
                        * ventana emergente de confirmación cambio de visibilidad, esto para que el usuario pueda ver esta
                        * ventana*/
                       enableOrDisablebackWindow.classList.remove("hidThis");
                       /*despues se modifica la ventana emergente, en este caso, su contenido html es un formulario, como cada botón
                        * tiene su propio dataset el cual contiene el Id (y tambien la visibilidad) del tipo correspondiente (esto del dataset se hace en la vista 
                        * de edición de tipos typesEditForms.php, osease que PHP se encarga de llenar los dataset), entonces se accede al dataset para
                        * incluir en el formulario el id del tipo a desactivar/activar y su visibilidad, el id del tipo y la visibilidad se alojan en sus respectivos 
                        * inputs tipo "hidden", estos datos los procesará el metodo enableOrDisableType del controlador UserController*/
                       enableOrDisablebackWindow.innerHTML = `
                            <form class="enable-or-disable__info-window" action="${BASE_URL}home/?homeController=user&homeAction=enableOrDisableType" method="POST">
                                <div class="pop-up-window-icon"><img class="pop-up-window-icon__img" src="${BASE_URL}assets/img/caution-sign_75243.png"/></div>
                                <div class="info-window__text-box"><h3>¿Está seguro de ${(deleteBtn.dataset.visibility === "ENABLED") ? "desactivar" : "activar"} el tipo 
                                        con Id ${deleteBtn.dataset.id}?, este registro ${(deleteBtn.dataset.visibility === "ENABLED") ? 'no será visible' : 'será visible'} 
                                        en la caja de selección del formulario de "Crear un equipo"</h3></div>
                                <input type="hidden" value="${deleteBtn.dataset.id}" name="tipoId"/>
                                <input type="hidden" value="${(deleteBtn.dataset.visibility === "ENABLED") ? 'DISABLED' : 'ENABLED'}" name="visibilidad"/>
                                <div class="info-window__selectbuttons-box">
                                   <input class="selectbuttons-box__button" type="submit" value="${(deleteBtn.dataset.visibility === "ENABLED") ? 'Desactivar' : 'Activar'}"/>
                                   <button class="selectbuttons-box__cancel-delete-button" type="button">Cancelar</button>
                                </div>
                            </form>
                        `;
                        /*Se selecciona los elementos html generados en este contexto, el botón "Cancelar" en la constante typeDeleteCancelBtn
                         * y la ventana emergente que en este caso es un formulario a la constante typeDeleteFormWindow*/    
                        const typeDeleteCancelBtn = enableOrDisablebackWindow.querySelector(".selectbuttons-box__cancel-delete-button");
                        const typeDeleteFormWindow = enableOrDisablebackWindow.querySelector(".enable-or-disable__info-window");
                        typeDeleteFormWindow.classList.add("activate-pop-in");
                        typeDeleteCancelBtn.addEventListener("click", ()=>{
                            typeDeleteFormWindow.classList.remove("activate-pop-in");
                            typeDeleteFormWindow.classList.add("activate-pop-out");
                            setTimeout(() => {
                                /*Si se da click al botón cancelar, entonces se utiliza la constante clientDeleteFormWindow para eliminar su contenido
                                * html gracias a remove() esto para evitar duplicaciones innecesarias de elementos html en el DOM*/
                                typeDeleteFormWindow.remove();
                                /*finalmente se le agrega al fondo de la ventana emergente la clase de estilo "hidThis" para ocultar esta ventana
                                * al usuario*/
                                enableOrDisablebackWindow.classList.add("hidThis");
                            }, 200);
                            
                        });
                   }); 
                });
            }
            /*Este if evalua si es que existe los botones "Guardar" y "Cancelar" (de ventana emergente) de cada uno de los tipos
             * presentes en la vista typesEditForms.php, si es que existen, entonces se van a gestionar eventos click a estos botones*/
            if(contactEditBtns != null && contactEditCancelBtns != null){
                /*Se recorre todos los botones "Guardar" ya que puede haber más de un tipo, se utiliza forEach, en su función anonima
                 * se utiliza como argumento (editBtn) los indices del arreglo de botones contenido en la constante contactEditBtns*/
                contactEditBtns.forEach(editBtn =>{
                    /*cada botón "Guardar" tendrá su respectivo evento click*/
                   editBtn.addEventListener("click", ()=>{
                      /*si se da click al botón "Guardar" entonces lo primero que se hace es seleccionar un elemento html cercano al botón en cuestión,
                       * cabe aclarar que cada tipo en la vista de edición de tipos tienen sus propias ventanas emergentes de confirmación
                       * de edición del tipo tal como se ve en el foreach del archivo typesEditForms.php, entonces lo primero que se selecciona es
                       * el formulario del tipo cuyo botón "Guardar" esta contenido en ese formulario (el respectivo formulario es el 
                       * elemento más cercano del botón que se está manejando en el forEach), ese formulario va a estar contenido en la constante
                       * parentForm*/ 
                      const parentForm = editBtn.closest(".edit-forms__type-form");
                      /*una vez delcarado parentForm entonces lo utilizamos para acceder al elemento html de la ventana emergente de confirmación de
                       * edición, ese elemento estará contenido en la constante editConfirmationWindow*/
                      const editConfirmationWindow = parentForm.querySelector(".contact-edit__background");
                      const editConfirmationInfoWindow = editConfirmationWindow.querySelector(".contact-edit__info-window");
                      /*Después, al fondo de la ventana emergente se le quita la clase de estilo "hidThis" para mostrarle al usuario la ventana
                       * emergente de confirmación de edición en cuestión*/
                      editConfirmationWindow.classList.remove("hidThis");
                      if(editConfirmationInfoWindow.className.includes("activate-pop-out")) editConfirmationInfoWindow.classList.remove("activate-pop-out");
                      editConfirmationInfoWindow.classList.add("activate-pop-in");
                   }); 
                });
                /*Se recorre todos los botones "Cancelar" de las ventanas emergentes de confirmación de edición de tipos generadas en 
                 * la vista typesEditForms.php, se utiliza forEach, en su función anonima se utiliza como argumento (cancelBtn) los indices del 
                 * arreglo de botones contenido en la constante contactEditCancelBtns*/
                contactEditCancelBtns.forEach(cancelBtn =>{
                   /*cada botón "cancelar" tendrá su respectivo evento click*/ 
                   cancelBtn.addEventListener("click", ()=>{
                       /*si se da click al botón "Cancelar" entonces lo primero que se hace es seleccionar un elemento html cercano al botón en cuestión,
                       * cabe aclarar que cada tipo en la vista de edición de tipos tienen sus propias ventanas emergentes de confirmación
                       * de edición tal como se ve en el foreach del archivo typesEditForms.php, entonces lo primero que se selecciona es
                       * el fondo de la ventana emergente de confirmación de edición cuyo botón "Cancelar" esta contenido en esa estructura de ventana 
                       * (la respectiva estructura de ventana emergente de confirmación de edición de tipo es el elemento más cercano del botón que 
                       * se está manejando en el forEach), esa estructura de ventana emergente va a estar contenida en la constante parentWindow*/
                       const parentWindow = cancelBtn.closest(".contact-edit__background");
                       const parentInfoWindow = parentWindow.querySelector(".contact-edit__info-window");
                       parentInfoWindow.classList.remove("activate-pop-in");
                       parentInfoWindow.classList.add("activate-pop-out");
                       /*Después, al fondo de la ventana emergente se le agrega la clase de estilo "hidThis" para ocultarle al usuario la ventana
                       * emergente de confirmación de edición en cuestión*/
                       setTimeout(() => {parentWindow.classList.add("hidThis");}, 200);
                       
                   });
                });
            }
        }
        
        /*Este if evalua si hay un elemento html propio de la vista devicesEditForms.php, que es donde el usuario
         * puede editar dispositivos, se reutilizan los elementos html (los botones) de los contactos de la 
         * vista enterAndContactsEditForms, estos tendrán como dataset el id de equipos y ventanas de activación o 
         * desactivación personalizables para los registros de equipos*/
        if(devicesEditFormsMain != null){
            
            if(deleteContactBtns != null){
                /*lo primero que se hace es recorrer con forEach todos los elementos html contenidos en la constante
                 * deleteContactBtns ya que puede haber más de un formulario (tipos en este caso)*/
                deleteContactBtns.forEach(deleteBtn =>{
                    /*Cada botón se le gestionará el evento click*/
                   deleteBtn.addEventListener("click", ()=>{
                       /*Si se da click a un botón "Desactivar/Activar tipo" lo primero que se hace es quitar la clase "hidThis" al fondo de la
                        * ventana emergente de confirmación cambio de visibilidad, esto para que el usuario pueda ver esta
                        * ventana*/
                       enableOrDisablebackWindow.classList.remove("hidThis");
                       /*despues se modifica la ventana emergente, en este caso, su contenido html es un formulario, como cada botón
                        * tiene su propio dataset el cual contiene el Id (y tambien la visibilidad) del equipo correspondiente (esto del dataset se hace en la vista 
                        * de edición de equipos devicesEditForms.php, osease que PHP se encarga de llenar los dataset), entonces se accede al dataset para
                        * incluir en el formulario el id del equipo a desactivar/activar y su visibilidad, el id del equipo y la visibilidad se alojan en sus respectivos
                        * input tipo "hidden", estos datos los procesará el metodo enableOrDisableType del controlador UserController*/
                       enableOrDisablebackWindow.innerHTML = `
                            <form class="enable-or-disable__info-window" action="${BASE_URL}home/?homeController=user&homeAction=enableOrDisableDevice" method="POST">
                                <div class="pop-up-window-icon"><img class="pop-up-window-icon__img" src="${BASE_URL}assets/img/caution-sign_75243.png"/></div>
                                <div class="info-window__text-box"><h3>¿Está seguro de ${(deleteBtn.dataset.visibility === "ENABLED") ? "desactivar" : "activar"} el equipo 
                                        con Id ${deleteBtn.dataset.id}?, este registro ${(deleteBtn.dataset.visibility === "ENABLED") ? 'no será visible' : 'será visible'} 
                                        en la caja de selección de equipo del formulario de "Nueva bitácora" y en los filtros de "Reportes de bitácoras"</h3></div>
                                <input type="hidden" value="${deleteBtn.dataset.id}" name="equipoId"/>
                                <input type="hidden" value="${(deleteBtn.dataset.visibility === "ENABLED") ? 'DISABLED' : 'ENABLED'}" name="visibilidad"/>
                                <div class="info-window__selectbuttons-box">
                                   <input class="selectbuttons-box__button" type="submit" value="${(deleteBtn.dataset.visibility === "ENABLED") ? 'Desactivar' : 'Activar'}"/>
                                   <button class="selectbuttons-box__cancel-delete-button" type="button">Cancelar</button>
                                </div>
                            </form>
                        `;
                        /*Se selecciona los elementos html generados en este contexto, el botón "Cancelar" en la constante deviceDeleteCancelBtn
                         * y la ventana emergente que en este caso es un formulario a la constante deviceDeleteFormWindow*/    
                        const deviceDeleteCancelBtn = enableOrDisablebackWindow.querySelector(".selectbuttons-box__cancel-delete-button");
                        const deviceDeleteFormWindow = enableOrDisablebackWindow.querySelector(".enable-or-disable__info-window");
                        deviceDeleteFormWindow.classList.add("activate-pop-in");
                        deviceDeleteCancelBtn.addEventListener("click", ()=>{
                            deviceDeleteFormWindow.classList.remove("activate-pop-in");
                            deviceDeleteFormWindow.classList.add("activate-pop-out");
                            setTimeout(() => {
                                /*Si se da click al botón cancelar, entonces se utiliza la constante clientDeleteFormWindow para eliminar su contenido
                                * html gracias a remove() esto para evitar duplicaciones innecesarias de elementos html en el DOM*/
                                deviceDeleteFormWindow.remove();
                                /*finalmente se le agrega al fondo de la ventana emergente la clase de estilo "hidThis" para ocultar esta ventana
                                * al usuario*/
                                enableOrDisablebackWindow.classList.add("hidThis");
                            }, 200);
                            
                        });
                   }); 
                });
            }
            /*Este if evalua si es que existe los botones "Guardar" y "Cancelar" (de ventana emergente) de cada uno de los equipos
             * presentes en la vista devicesEditForms.php, si es que existen, entonces se van a gestionar eventos click a estos botones*/
            if(contactEditBtns != null && contactEditCancelBtns != null){
                /*Se recorre todos los botones "Guardar" ya que puede haber más de un equipo, se utiliza forEach, en su función anonima
                 * se utiliza como argumento (editBtn) los indices del arreglo de botones contenido en la constante contactEditBtns*/
                contactEditBtns.forEach(editBtn =>{
                    /*cada botón "Guardar" tendrá su respectivo evento click*/
                   editBtn.addEventListener("click", ()=>{
                      /*si se da click al botón "Guardar" entonces lo primero que se hace es seleccionar un elemento html cercano al botón en cuestión,
                       * cabe aclarar que cada equipo en la vista de edición de equipos tienen sus propias ventanas emergentes de confirmación
                       * de edición tal como se ve en el foreach del archivo devicesEditForms.php, entonces lo primero que se selecciona es
                       * el formulario del equipo cuyo botón "Guardar" esta contenido en ese formulario (el respectivo formulario es el 
                       * elemento más cercano del botón que se está manejando en el forEach), ese formulario va a estar contenido en la constante
                       * parentForm*/ 
                      const parentForm = editBtn.closest(".device-form__form");
                      /*una vez delcarado parentForm entonces lo utilizamos para acceder al elemento html de la ventana emergente de confirmación de
                       * edición, ese elemento estará contenido en la constante editConfirmationWindow*/
                      const editConfirmationWindow = parentForm.querySelector(".contact-edit__background");
                      const editConfirmationInfoWindow = editConfirmationWindow.querySelector(".contact-edit__info-window");
                      /*Después, al fondo de la ventana emergente se le quita la clase de estilo "hidThis" para mostrarle al usuario la ventana
                       * emergente de confirmación de edición en cuestión*/
                      editConfirmationWindow.classList.remove("hidThis");
                      if(editConfirmationInfoWindow.className.includes("activate-pop-out")) editConfirmationInfoWindow.classList.remove("activate-pop-out");
                      editConfirmationInfoWindow.classList.add("activate-pop-in");
                   }); 
                });
                /*Se recorre todos los botones "Cancelar" de las ventanas emergentes de confirmación de edición de equipos generadas en 
                 * la vista devicesEditForms.php, se utiliza forEach, en su función anonima se utiliza como argumento (cancelBtn) los indices del 
                 * arreglo de botones contenido en la constante contactEditCancelBtns*/
                contactEditCancelBtns.forEach(cancelBtn =>{
                   /*cada botón "cancelar" tendrá su respectivo evento click*/ 
                   cancelBtn.addEventListener("click", ()=>{
                       /*si se da click al botón "Cancelar" entonces lo primero que se hace es seleccionar un elemento html cercano al botón en cuestión,
                       * cabe aclarar que cada equipo en la vista de edición de equipos tienen sus propias ventanas emergentes de confirmación
                       * de edición tal como se ve en el foreach del archivo devicesEditForms.php, entonces lo primero que se selecciona es
                       * el fondo de la ventana emergente de confirmación de edición cuyo botón "Cancelar" esta contenido en esa estructura de ventana 
                       * (la respectiva estructura de ventana emergente de confirmación de edición de equipo es el elemento más cercano del botón que 
                       * se está manejando en el forEach), esa estructura de ventana emergente va a estar contenida en la constante parentWindow*/
                        const parentWindow = cancelBtn.closest(".contact-edit__background");
                        const parentInfoWindow = parentWindow.querySelector(".contact-edit__info-window");
                        parentInfoWindow.classList.remove("activate-pop-in");
                        parentInfoWindow.classList.add("activate-pop-out");
                       /*Después, al fondo de la ventana emergente se le agrega la clase de estilo "hidThis" para ocultarle al usuario la ventana
                       * emergente de confirmación de edición en cuestión*/
                        setTimeout(() => {parentWindow.classList.add("hidThis");}, 200);
                   });
                });
            }
        }
        
        /*Este if verifica si es que existen elementos html propios del control de filtrado de bitácoras en la vista
         * binnaclesFilter.php*/
        if(binnFiltersDeviceSelect != null && binnFiltersContactSelect != null && binnFiltersEnterSelect != null){
            /*Si existe el select de clientes (contactos) y el select de dispositivos de la vista binnaclesFilter.php
             * entonces lo primero que se hace es seleccionar estos dos select*/
            /*SELECT2 funciona con JQuery, por lo que se utiliza el selector de esta librería y le ponemos
             * nuestras constantes que contienen los select de clientes (contactos) y dispositivos respectivamente, 
             * finalmente se le aplica el método select2(), como argumento le tenemos que poner un JSON, en este caso 
             * para colocarle un placeholder e indicar que el select va a ocupar el 100% de ancho del elemento html tipo bloque
             * que lo contiene*/
            
            $(binnFiltersEnterSelect).select2({
                placeholder: "Selecciona una Empresa",
                allowClear: true,
                width: "100%"
            });
            
            $(binnFiltersContactSelect).select2({
                placeholder: "Selecciona un cliente",
                allowClear: true,
                width: "100%"
            });
            
            $(binnFiltersDeviceSelect).select2({
                placeholder: "Selecciona un equipo",
                allowClear: true,
                width: "100%"
            });
            /*este if evalua si el select contenido en la constante servicioOEquipo tiene el valor "Servicio", si es que lo
             * tiene, entonces se desactiva el select de dispositivos*/
            if(servicioOEquipo.value === "Servicio"){
                $(binnFiltersDeviceSelect).prop("disabled", true);
            }
            
            /*al select contenido en la constante servicioOEquipo se le aplica un evento tipo "change", esto es cuando el usuario
             * elige una opción del select*/
            servicioOEquipo.addEventListener("change", ()=>{
                if(servicioOEquipo.value === "Servicio"){
                    /*si el valor del select es "Servicio" entonces lo primero que se hace es "limpiar" el select de dispositivos,
                     * esto es quitar cualquier valor a ese select*/
                    $(binnFiltersDeviceSelect).val("").trigger("change");
                    /*finalmente se desactiva el select de dispositivos*/
                    $(binnFiltersDeviceSelect).prop("disabled", true);
                }else{
                    /*Si el usuario elige "Equipo" entonces entra a este bloque false del if, lo que se hace aqui es volver a habilitar
                     * el select de dispositivos*/
                    $(binnFiltersDeviceSelect).prop("disabled", false);
                }
            });
            /*Como SELECT2 funciona con JQuery, entonces se utiliza esta librería para aplicarle al select de clientes (contactos) un evento
             * change*/
            $(binnFiltersEnterSelect).on("change", ()=>{
                /*Si el usuario selecciona cun cliente (contacto), entonces el Id del cliente (contacto) se guardará en la constante
                 * clientIdFromFilterSelected*/
                const enterIdFromFilterSelected = binnFiltersEnterSelect.value;
                if(enterIdFromFilterSelected !== ""){
                    /*Verificamos si la constante clientIdFromFilterSelected no está vacío, si no lo está, entonces se efectúa una comunicación
                     * asincrona con PHP, JS va a enviar el id del cliente (contacto) contenido en la constante clientIdFromFilterSelected como
                     * valor de la propiedad clientIdFromBinnFilter del JSON a enviar*/
                    fetch(BASE_URL + "home/?homeController=user&homeAction=binnaclesReport", {
                    "method": "POST",
                    "headers": {
                        "Content-Type": "application/json; charset=utf-8"
                    },
                    "body": JSON.stringify({"enterIdFromBinnFilter": `${enterIdFromFilterSelected}`})
                    })
                    .then(res => res.json())
                    .then(data => {
                        /*PHP va enviar un JSON una vez que se hizo la petición a la base de datos para conseguir los registros de contactos y dispositivos
                         * vinculados a la empresa seleccionada, por lo tanto, el JSON que envia PHP tiene las propiedades
                         * llamadas enterContactsToBinnsFilter y enterDcesToBinnsFilter, los cuales tienen un array de JSON, cada indice corresponde a los 
                         * registros de dispositivos que PHP logró sacar de la base de datos*/
                         /*Lo primero que se hace es declarar variables donde se concatenarán un string html de los options que tendrá el
                          * select de dispositivos y de contactos respectivamente, se necesita colocar un option vacío para que estos select tengan un placeholder
                          * gracias a SELECT2*/
                        let contactOptionsHtml = "<option value=''></option>";
                        let devicesOptionsHtml = "<option value=''></option>";
                        
                        if (data.enterContactsToBinnsFilter.length > 0) {
                            /*Este if verifica si el array que contiene enterContactsToBinnsFilter es mayor a cero, ya que PHP puede devolver un 0 si
                             * no hay registros en la tabla "Equipos"*/
                            for(const contact of data.enterContactsToBinnsFilter){
                                /*Se recorren los indices del arreglo de enterContactsToBinnsFilter, se utiliza la constante contact para evaluar cada indice
                                 * con for of, aqui lo que se hace es concatenar string html de option a la variable contactOptionsHtml de acuerdo a la cantidad
                                 * de indices del arreglo de enterContactsToBinnsFilter*/
                                contactOptionsHtml += `<option value="${contact.Id}">${contact.Nombre_completo}</option>`;
                            }
                        }
                        
                        if (data.enterDcesToBinnsFilter.length > 0) {
                            /*Este if verifica si el array que contiene enterDcesToBinnsFilter es mayor a cero, ya que PHP puede devolver un 0 si
                             * no hay registros en la tabla "Equipos"*/
                            for(const device of data.enterDcesToBinnsFilter){
                                /*Se recorren los indices del arreglo de enterDcesToBinnsFilter, se utiliza la constante device para evaluar cada indice
                                 * con for of, aqui lo que se hace es concatenar string html de option a la variable optionsHtml de acuerdo a la cantidad
                                 * de indices del arreglo de enterDcesToBinnsFilter*/
                                devicesOptionsHtml += `<option value="${device.Id}">${device.Marca} - ${device.Numero_serie}</option>`;
                            }
                        }
                        /*Al tener lista nuestra variable contactOptionsHtml vamos a agregar todos esos elementos html al select de contactos, select que está
                         * contenido en la constante binnFiltersContactSelect*/
                        binnFiltersContactSelect.innerHTML = contactOptionsHtml;
                        /*Al tener lista nuestra variable devicesOptionsHtml vamos a agregar todos esos elementos html al select de dispositivos, select que está
                         * contenido en la constante binnFiltersDeviceSelect*/
                        binnFiltersDeviceSelect.innerHTML = devicesOptionsHtml;
                    });
                }
            });
        }
        
        /*Este if evalúa si existen elementos html propios de la vista binnaclesFilter.php cuando se realizó la
         * busqueda de bitácoras de acuerdo a las opciones de filtrado dadas*/
        if(binnsFilternumkeySelect != null && binnacleTbody != null){
            /*Si la busqueda da resultados, entonces quiere decir que existe el elemento html select para seleccionar
             * el numero de elementos en pantalla y el elemento tbody el cual es la tabla donde se despliga los resultados
             * de la busqueda*/
            binnsFilternumkeySelect.addEventListener("change", () =>{
                /*Si el usuario selecciona algún numero, entonces ese valor se guardará en la constante binnsFilterNumSelected*/
                const binnsFilterNumSelected = binnsFilternumkeySelect.value;
                if(binnsFilterNumSelected != null){
                    /*Verificamos si la constante binnsFilterNumSelected no está vacía, si no lo está, entonces se efectúa
                     * una comunicación asincrona con PHP, JS va a enviar el numero que el usuario seleccionó contenido en la
                     * constante binnsFilterNumSelected como valor de la propiedad binnsFilterNumber del JSON a enviar*/
                    fetch(BASE_URL + "home/?homeController=user&homeAction=binnaclesReport", {
                        "method": "POST",
                        "headers": {
                            "Content-Type": "application/json; charset=utf-8"
                        },
                        "body": JSON.stringify({"binnsFilterNumber": `${binnsFilterNumSelected}`})
                    })
                    .then(res => res.json())
                    .then(data => {
                        /*PHP envía un JSON una vez hecho los calculos de paginación de acuerdo al numero de elementos en pantalla
                         * que envió JS, lo primero que se hace es vaciar nuestra tabla de contenido*/
                        binnacleTbody.innerHTML = "";
                        /*binns es una propiedad del JSON que PHP envió, este tiene un arreglo de JSON, cada JSON es un registro
                         * de bitácora producto del calculo de paginación, por lo tanto, se le aplica binns un forEach para recorrer
                         * todos los indices de binns, cada indice entra como argumento en el parametro binn de la función anonima
                         * de forEach*/
                        data.binns.forEach(binn =>{
                            /*Utilizamos nuestra función generateTableRow para usar cada indice de binns como argumento, nuestra función
                             * devuelve un elemento html, en este caso una fila de tabla, cada elemento html se concatenará en el elemento
                             * html de tabla contenido en la constante binnacleTbody gracias a append()*/
                            binnacleTbody.append(generateTableRow(binn));
                        });
                        /*Finalmente toca manejar el control de paginación, este control está contenido en la propiedad buttons del JSON
                         * que envió PHP, este control de paginación (botones con numeros que representan la cantidad de paginas) fue
                         * fruto del calculo de paginación que efectúo PHP y es necesario colocarlo en una parte de nuestra vista,
                         * en este caso en un elemento tipo bloque como lo es un div en la vista de binnaclesFilter.php, ese elemento
                         * está contenido en la constante binnFilterPaginationBox, gracias a innerHTML podemos añadirle el contenido
                         * html del control de paginación*/
                        binnFilterPaginationBox.innerHTML = data.buttons;
                        
                        /* NOTA: EN ESTE CONTEXTO SE ESTÁ CAMBIANDO EL DOCUMENTO HTML, AUNQUE EN LA SECCION DE CONSTANTES ESTÉN 
                        * SELECCIONADOS ESTOS ELEMENTOS (LOS BOTONES DESACTIVAR/ACTIVAR) ESTOS YA NO SE CONTEMPLAN 
                        * POR MEDIO DE "document" SINO POR MEDIO DE SU ELEMENTO PADRE DONDE SE EFECTÚO EL CAMBIO 
                        * (binnacleTbody)*/
                        const binnEDBtns = binnacleTbody.querySelectorAll(".binnacle-data-table__binn-delete-btn");
                        
                        if(binnEDBtns != null){
                            binnEDBtns.forEach(delBtn =>{
                            /*cada elemento de binnEDBtns se le aplica un evento click*/
                                delBtn.addEventListener("click", ()=>{
                                    /*Si el usuario da click a un botón "Desactivar/Activar" entonces se le quita al fondo de la ventana de confirmación
                                    * de desactivación/activación de bitácora la clase de estilo "hidThis", esto para mostrar la ventana emergente al usuario*/
                                    binnDeletebackWindow.classList.remove("hidThis");
                                    /*luego se modifica el contenido de binnDeletebackWindow, en este caso, la ventana es practicamente un formulario,
                                     * se accede al dataset id y al dataset visibility del botón correspondiente los cuales tienen respectivamente el id 
                                     * y la visibilidad de la bitácora en cuestión,
                                     * valores que estarán alojados en un input tipo hidden, luego esta tambien un campo para que el administrador pueda
                                     * escribirlo antes de confirmar, tanto los input hidden como el password del administrador serán procesados por
                                     * el metodo enableOrDisableBinn del controlador UserController*/
                                    binnDeletebackWindow.innerHTML = `
                                        <form class="binnacle-delete__info-window" action="${BASE_URL}home/?homeController=user&homeAction=enableOrDisableBinn" method="POST">
                                            <div class="pop-up-window-icon"><img class="pop-up-window-icon__img" src="${BASE_URL}assets/img/caution-sign_75243.png"/></div>
                                            <div class="info-window__text-box"><h3>¿Está seguro de ${(delBtn.dataset.visibility === "ENABLED") ? "desactivar" : "activar"} la bitácora 
                                                    con Id ${delBtn.dataset.id}?, este registro ${(delBtn.dataset.visibility === "ENABLED") ? 'no será visible' : 'será visible'} 
                                                    en el apartado de "Reportes de bitácoras" al menos que se seleccione 
                                                    ${(delBtn.dataset.visibility === "ENABLED") ? 'Desactivado' : 'Activado'} en la caja de selección de "Visible" en la busqueda</h3></div>
                                            <input type="hidden" value="${delBtn.dataset.id}" name="bitacoraId"/>
                                            <input type="hidden" value="${(delBtn.dataset.visibility === "ENABLED") ? 'DISABLED' : 'ENABLED'}" name="visibilidad"/>
                                            <div class="info-window__selectbuttons-box">
                                                <input class="selectbuttons-box__button" type="submit" value="${(delBtn.dataset.visibility === "ENABLED") ? 'Desactivar' : 'Activar'}"/>
                                                <button class="selectbuttons-box__cancel-delete-button" type="button">Cancelar</button>
                                            </div>
                                        </form>
                                    `;
                                    /*Después de cambiar el contenido html de binnDeletebackWindow, entonces toca seleccionar el contenido creado en 
                                     * este contexto, esto es seleccionar la ventana emergente y agregarla en la constante binnDelWindow y el botón
                                     * "Cancelar" el cual se agrega en la constante binnDelCancelBtn*/
                                    const binnDelWindow = binnDeletebackWindow.querySelector(".binnacle-delete__info-window");
                                    const binnDelCancelBtn = binnDeletebackWindow.querySelector(".selectbuttons-box__cancel-delete-button");
                                    binnDelWindow.classList.add("activate-pop-in");
                                    binnDelCancelBtn.addEventListener("click", ()=>{
                                        binnDelWindow.classList.remove("activate-pop-in");
                                        binnDelWindow.classList.add("activate-pop-out");
                                        setTimeout(() => {
                                            /*Al dar click al botón "Cancelar" lo primero que se hace es agregarle al fondo de la ventana emergente
                                            * la clase de estilo "hidThis" esto para ocultar la ventana al usuario*/
                                            binnDeletebackWindow.classList.add("hidThis");
                                            /*Finalmente es elimina el contenido de la ventana con remove(), esto para evitar duplicaciones innecesarias de elementos
                                            * html en el DOM*/
                                        binnDelWindow.remove();
                                        }, 200);
                                        
                                    });
                                });
                            });
                        }
                       
                    });
                }    
            });
                        
            /*Cada elemento mostrado en la tabla de contenido tiene su propio botón de "Desactivar/Activar", por lo que la constante
             * binnDelBtns contiene todos estos botones de las filas de la tabla gracias a querySelectorAll, entonces
             * se utiliza forEach para recorrer cada elemento de la constante binnDelBtns, cabe aclarar que cada botón "Desactivar/Activar"
             * tiene dos datasets, estos son propiedades html personalizadas, que en este caso, se usa para agregar respectivamente el id y la visibilidad de la
             * bitácora en cuestión, el llenado de datasets lo realiza PHP en la vista binnaclesFilter.php y tambien JavaScript
             * con nuestra función generateTableRow, los datasets son la propiedad "data-id=" y "data-visibility" presente en esos procesos*/
            binnDelBtns.forEach(delBtn =>{
                /*cada elemento de binnDelBtns se le aplica un evento click*/
                delBtn.addEventListener("click", ()=>{
                    /*Si el usuario da click a un botón "Desactivar/Activar" entonces se le quita al fondo de la ventana de confirmación
                     * de desactivación/activación de bitácora la clase de estilo "hidThis", esto para mostrar la ventana emergente al usuario*/
                    binnDeletebackWindow.classList.remove("hidThis");
                    /*luego se modifica el contenido de binnDeletebackWindow, en este caso, la ventana es practicamente un formulario,
                                     * se accede al dataset id y al dataset visibility del botón correspondiente los cuales tienen respectivamente el id 
                                     * y la visibilidad de la bitácora en cuestión,
                                     * valores que estarán alojados en un input tipo hidden, luego esta tambien un campo para que el administrador pueda
                                     * escribirlo antes de confirmar, tanto los input hidden como el password del administrador serán procesados por
                                     * el metodo enableOrDisableBinn del controlador UserController*/
                    binnDeletebackWindow.innerHTML = `
                        <form class="binnacle-delete__info-window" action="${BASE_URL}home/?homeController=user&homeAction=enableOrDisableBinn" method="POST">
                            <div class="pop-up-window-icon"><img class="pop-up-window-icon__img" src="${BASE_URL}assets/img/caution-sign_75243.png"/></div>
                            <div class="info-window__text-box"><h3>¿Está seguro de ${(delBtn.dataset.visibility === "ENABLED") ? "desactivar" : "activar"} la bitácora 
                                    con Id ${delBtn.dataset.id}?, este registro ${(delBtn.dataset.visibility === "ENABLED") ? 'no será visible' : 'será visible'} 
                                    en el apartado de "Reportes de bitácoras" a menos que se seleccione 
                                    ${(delBtn.dataset.visibility === "ENABLED") ? '"Desactivado"' : '"Activado"'} en la caja de selección de "Visible:" en el filtrado</h3></div>
                            <input type="hidden" value="${delBtn.dataset.id}" name="bitacoraId"/>
                            <input type="hidden" value="${(delBtn.dataset.visibility === "ENABLED") ? 'DISABLED' : 'ENABLED'}" name="visibilidad"/>
                            <div class="info-window__selectbuttons-box">
                                <input class="selectbuttons-box__button" type="submit" value="${(delBtn.dataset.visibility === "ENABLED") ? 'Desactivar' : 'Activar'}"/>
                                <button class="selectbuttons-box__cancel-delete-button" type="button">Cancelar</button>
                            </div>
                        </form>
                    `;
                    /*Después de cambiar el contenido html de binnDeletebackWindow, entonces toca seleccionar el contenido creado en 
                     * este contexto, esto es seleccionar la ventana emergente y agregarla en la constante binnDelWindow y el botón
                     * "Cancelar" el cual se agrega en la constante binnDelCancelBtn*/
                    const binnDelWindow = binnDeletebackWindow.querySelector(".binnacle-delete__info-window");
                    const binnDelCancelBtn = binnDeletebackWindow.querySelector(".selectbuttons-box__cancel-delete-button");
                    binnDelWindow.classList.add("activate-pop-in");
                    binnDelCancelBtn.addEventListener("click", ()=>{
                        binnDelWindow.classList.remove("activate-pop-in");
                        binnDelWindow.classList.add("activate-pop-out");
                        setTimeout(() => {
                            /*Al dar click al botón "Cancelar" lo primero que se hace es agregarle al fondo de la ventana emergente
                            * la clase de estilo "hidThis" esto para ocultar la ventana al usuario*/
                            binnDeletebackWindow.classList.add("hidThis");
                            /*Finalmente es elimina el contenido de la ventana con remove(), esto para evitar duplicaciones innecesarias de elementos
                            * html en el DOM*/
                        binnDelWindow.remove();
                        }, 200);
                        
                    });
                });
            });
        }
        
        /*Este if verifica si es que existen elementos html propios de la vista binnacleInfoCanvas.php (dentro del bloque 
         * <?php if($_GET["homeAction"] === "editBinnacle"):?>)*/
        if(editBinnacleBtn != null && binnEditConfirmationBackground != null){
            /*Si existe el botón "GUARDAR" de edición de bitácora y el fondo de la ventana emergente de confirmación de 
             * edición de bitácora, entonces lo primero que se hace es verificar si existe el elemento html del select
             * de usuarios (técnicos)*/
            if(editBinnUserSelect != null){
                /*Si es que existe el elemento html select de usuarios (técnicos) en el DOM, entonces se
                 * selecciona este select*/
                /*SELECT2 funciona con JQuery, por lo que se utiliza el selector de esta librería y le ponemos
                 * nuestra constante que contiene el select de usuarios (técnicos), 
                 * finalmente se le aplica el método select2(), como argumento le tenemos que poner un JSON, en este caso 
                 * para colocarle un placeholder, indicar que no se va añadir la funcionalidad de "limipar" manualmente el 
                 * select (quitarle manualmente el valor precionando "x" en la seleccion) e indicar que el select va a 
                 * ocupar el 100% de ancho del elemento html tipo bloque que lo contiene*/
                $(editBinnUserSelect).select2({
                    placeholder: "Selecciona un equipo",
                    allowClear: false,
                    width: "100%"
                });
            }
            /*luego se selecciona el elemento html del botón "Cancelar" de la ventana emergente de confirmación de edición
             * de bitácora y se guarda en la constante binnEditCancelBtn*/
            const binnEditCancelBtn = binnEditConfirmationBackground.querySelector("#binnEditCancelBtn");
            
            /*Si se da click al botón "GUARDAR" entonces se le quita al fondo de la ventana emergente de confirmación de edición
             * de bitácora la clase de estilo "hidThis", esto para mostrarle al usuario la ventana*/
            editBinnacleBtn.addEventListener("click", ()=>{
                binnEditConfirmationBackground.classList.remove("hidThis");
                if(binnEditConfirmationInfoWindow.className.includes("activate-pop-out")) binnEditConfirmationInfoWindow.classList.remove("activate-pop-out");
                binnEditConfirmationInfoWindow.classList.add("activate-pop-in");
            });
            
            /*Si se da click al botón "Cancelar" entonces se le agrega al fondo de la ventana emergente de confirmación de edición
             * de bitácora la clase de estilo "hidThis", esto para ocultarle al usuario la ventana*/
            binnEditCancelBtn.addEventListener("click", ()=>{
                binnEditConfirmationInfoWindow.classList.remove("activate-pop-in");
                binnEditConfirmationInfoWindow.classList.add("activate-pop-out");
                setTimeout(() => {binnEditConfirmationBackground.classList.add("hidThis");}, 200);
            });
        }
        
        /*Hay que aclarar que cada vez que el usuario presiona algún botón "PDF", en el servidor se activa las vistas gestionadas
         * por la estructura de control del metodo estatico reportPdfGenerator de la clase Utils, dentro de esas vistas se
         * crea una instancia de la clase Dompdf (una de nuestras dependencias), si se genera muchos clicks, el html que Dompdf tiene que procesar
         * se acumula, esto quiere decir que se va tardar mas en generar el PDF en proporcion a la cantidad de clicks que se haga en el botón pdf,
         * causando un efecto contrario al que se quiere llegar (el usuario suele pensar que haciendo esto la aplicación web se "apura" 
         * para generar el pdf), entonces para evitar esto, por eso existen estos dos if, el primero evalúa si existe el botón PDF de la vista
         * de reportes de dispositivos y el segundo if evalua si existe botones "PDF" en la vista de reportes de bitácoras*/
        if(deviceReportPdfBtn != null){
            /*Si existe el botón PDF de la vista de reportes de dispositivos (devicesReport.php) en el DOM, entonces se le va a aplicar
             * un evento click*/
            deviceReportPdfBtn.addEventListener("click", ()=>{
                /*Si se da click al botón PDF entonces al propio botón se le agrega la clase de estilo "pdf-disabled" esa clase tiene el estilo
                 * pointer-events: none esto desactiva la posibilidad de hacer click a este botón PDF, esto quiere decir que el usuario solamente
                 * podrá hacer un solo click al botón PDF de la vista de reportes de dispositivos, al generar el PDF y regresar a la vista de
                 * reportes de dispositivos, los elementos html de esa vista se volverán a "resetear" por lo que se puede volver hacer click al
                 * botón PDF*/
                deviceReportPdfBtn.classList.add("pdf-disabled");
            });
        }
        
        if(binnPdfBtns != null){
            /*Si existen botones PDF de la vista de reportes de bitácoras (binnaclesFilter.php y también en binnacleInfoCanvas,php en el bloque 
             * <?php if($_GET["homeAction"] === "showBinnacle"):?>) en el DOM, entonces se le va a aplicar un evento click*/
            /*puede haber más de un botón en las vistas de reportes de bitácoras, esos botones estan contenidos en la constante binnPdfBtns gracias
             * al selector querySelectorAll, por lo que, se utiliza un forEach para recorrer cada uno de los botones PDF contenidos en esa constante*/
            binnPdfBtns.forEach(pdfBtn =>{
               /*cada botón PDF en el DOM se le va a aplicar un evento click*/ 
               pdfBtn.addEventListener("click", ()=>{
                   /*Si se da click a un botón PDF entonces a todos los botones PDF incluidos en la constante binnPdfBtns se le agregan la clase de 
                    * estilo "pdf-disabled" esa clase tiene el estilo pointer-events: none esto desactiva la posibilidad de hacer click a estos botones 
                    * PDF, esto quiere decir que el usuario solamente podrá hacer un solo click a un solo botón dentro del listado de botones PDF de 
                    * la vista de reportes de Bitácoras, al generar el PDF y regresar a la vista de reportes de bitácoras, los elementos html de esa 
                    * vista se volverán a "resetear" por lo que se puede volver hacer click a cualquier botón PDF*/
                   binnPdfBtns.forEach(pdfBtnClass =>{
                       pdfBtnClass.classList.add("pdf-disabled");
                   });
               }); 
            });
        }
    }
    //-------------------------EVENTOS ADMINISTRADOR----------------------------
    
    //--------------------------GESTIÓN DE EVENTOS------------------------------
});