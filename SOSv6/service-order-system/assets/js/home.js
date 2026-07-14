import {generateLinks, 
        generateTableRow, 
        dataManagmentProcedure, 
        resetDeviceFields,
        resetDeviceSelect,
        resetContactFields,
        enterOrContactSwitchWindow,
        typeSwitchWindow,
        deviceSwitchWindow,
        binnsSwitchWindow} from './UIFunctions/innerContents.js';

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

    
    //--------------------------GESTIÓN DE EVENTOS------------------------------
    
    //-------------------------EVENTOS USUARIO----------------------------------
    
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
    
    
    if(activityChoice1 != null){
        
        activityChoice1.addEventListener("click", ()=>{
            serviceBox.classList.remove("hidThis");
            deviceBox.classList.add("hidThis");
        });
        
        activityChoice2.addEventListener("click", ()=>{
            deviceBox.classList.remove("hidThis");
            serviceBox.classList.add("hidThis");
        });
        
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
    
    if(numkeySelect != null && linksArea != null){
        
        numkeySelect.addEventListener("change", () =>{
            
            const numSelected = numkeySelect.value;

            if(numSelected != null){
                
                fetch(BASE_URL + "home/?homeController=binnacle&homeAction=followuplist", {
                    "method": "POST",
                    "headers": {
                        "Content-Type": "application/json; charset=utf-8"
                    },
                    "body": JSON.stringify({"number": `${numSelected}`})
                })
                .then(res => res.json())
                .then(data => {
                    
                    linksArea.innerHTML = "";
                    data.binns.forEach(binn =>{
                        linksArea.append(generateLinks(binn));
                    });
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
            
            const enterpriseIdSelected = firstFormenterprisesSelect.value;
            
            if(enterpriseIdSelected !== ""){
                
                deviceFormTbody.innerHTML = resetDeviceFields();
                
                fetch(BASE_URL + "home/?homeController=binnacle&homeAction=index", {
                    "method": "POST",
                    "headers": {
                        "Content-Type": "application/json; charset=utf-8"
                    },
                    "body": JSON.stringify({"enterpriseId": `${enterpriseIdSelected}`})
                })
                .then(res => res.json())
                .then(data => {
                    dataManagmentProcedure(data);
                });            
            }
        });
        
        firstFormCancelSelectBtn.addEventListener("click", (e)=>{
            
            contactFormTbody.innerHTML = resetContactFields(e.target.id);     
            deviceSelectTbody.innerHTML = resetDeviceSelect();
            deviceFormTbody.innerHTML = resetDeviceFields();

            $(firstFormenterprisesSelect).val("").trigger("change");
                
            const contactsSelect = contactFormTbody.querySelector("#firstFormContactSelect");
            const devicesSelect = deviceSelectTbody.querySelector("#firstFormDeviceSelect");
            
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
        newContactFormCancelSelectBtn.addEventListener("click", (e)=>{
            contactFormTbody.innerHTML = resetContactFields(e.target.id);
            $(newContectFormEnterprisesSelect).val("").trigger("change");
        });
        
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

            const enterpriseIdSelected = newContectFormEnterprisesSelect.value;
            
            if(enterpriseIdSelected !== ""){  
                fetch(BASE_URL + "home/?homeController=contact&homeAction=index", {
                    "method": "POST",
                    "headers": {
                        "Content-Type": "application/json; charset=utf-8"
                    },
                    "body": JSON.stringify({"newContactEnterId": `${enterpriseIdSelected}`})
                })
                .then(res => res.json())
                .then(data => {
                    dataManagmentProcedure(data);
                });            
            }
        });
    }
    
    /*Este if evalúa si existe en el DOM un elemento html propio de la vista de formulario de registro de tipo de equipo*/
    if(TypeFormField != null){
        
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
            
           userCreationNiseSubmit.addEventListener("click", ()=>{
                userCreationbackWindow.classList.remove("hidThis");
                if(userCreationinfoWindow.className.includes("activate-pop-out")) userCreationinfoWindow.classList.remove("activate-pop-out");
                userCreationinfoWindow.classList.add("activate-pop-in");
           });
       }
       
       if(userCreationCancel != null){
           userCreationCancel.addEventListener("click", ()=>{
                userCreationinfoWindow.classList.remove("activate-pop-in");
                userCreationinfoWindow.classList.add("activate-pop-out");
                setTimeout(() => {userCreationbackWindow.classList.add("hidThis");}, 200);
           });
       }
       
       if(userDeleteNiseSubmit !== null){
           userDeleteNiseSubmit.addEventListener("click", ()=>{
               userDeletebackWindow.classList.remove("hidThis");
                if(userDeleteinfoWindow.className.includes("activate-pop-out")) userDeleteinfoWindow.classList.remove("activate-pop-out");
                userDeleteinfoWindow.classList.add("activate-pop-in");
           });
       }
       
       if(userDeleteCancel !== null){
           userDeleteCancel.addEventListener("click", ()=>{
               userDeleteinfoWindow.classList.remove("activate-pop-in");
               userDeleteinfoWindow.classList.add("activate-pop-out");
               setTimeout(() => {userDeletebackWindow.classList.add("hidThis");}, 200);
           });
       }
       
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
            
            let clientWidth = parseInt(document.body.clientWidth);
            if(clientWidth <= 1258){
                adminMenuWrapper.style.display = "none";
                mobileBtn.style.display = "block";
                mainsWrapper.style.width = "100%";
            }else{
                
                const visibleClass = mobileNavBar.classList[1];
                if(visibleClass === "mobile-navbar--show"){
                    mobileNavBar.classList.remove("mobile-navbar--show");
                }
                
                adminMenuWrapper.style.display = "block";
                mobileBtn.style.display = "none";
                mainsWrapper.style.width = "75%";
            }
            
            if(clientWidth <= 629 && alias !== null){
                alias.style.marginTop = "4.5rem";
            }else{
                if(alias !== null)
                    alias.style.marginTop = "1rem";
            }
            
        });
        
        if(window.innerWidth <= 1258){
            adminMenuWrapper.style.display = "none";
            mainsWrapper.style.width = "100%";
        }
        
        if(window.innerWidth <= 629){
            if(alias !== null)
                alias.style.marginTop = "4.5rem"; 
 
        }
        
        if(window.innerWidth >= 1258)
            mobileBtn.style.display = "none";
        
        
        /*adminMenuLis corresponde a los elementos li del menú del administrador de la barra lateral izquierda, este 
         * for each recorre todos los elementos li de ese menú*/
        adminMenuLis.forEach(menuLi =>{
            
            menuLi.addEventListener("click", ()=>{
                
                const subMenu = menuLi.lastElementChild;
                
                if(subMenu.className === "linkList__submenu-links"){
                    const iconBox = menuLi.querySelector(".row-link__icon-wrapper");
                    
                    if(subMenu.style.display === "block"){
                        subMenu.style.display = "none";
                        iconBox.innerHTML = '<i class="fa-solid fa-chevron-down">';
                    }else{
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
            const visibleClass = mobileNavBar.classList[1];
            if(visibleClass === "mobile-navbar--show"){
                mobileNavBar.classList.remove("mobile-navbar--show");
            }else{
                mobileNavBar.classList.add("mobile-navbar--show");
            }
            
        });
        
        mobileCancelBtn.addEventListener("click", ()=>{
            const visibleClass = mobileNavBar.classList[1];
            if(visibleClass === "mobile-navbar--show"){
                mobileNavBar.classList.remove("mobile-navbar--show");
            }
        });
        
        /*Se  evalua si existe
         * un elemento html propio de la vista de edicción de empresa (enterAndContactsForms.php)*/
        if(enterpriseFormsMain != null){
            
            if(enterDeleteButton != null){
                
                enterDeleteButton.addEventListener("click", (e)=>{
                    
                    enterOrClientDeletebackWindow.classList.remove("hidThis");
                    enterOrClientDeletebackWindow.innerHTML = enterOrContactSwitchWindow(e.target.classList[0], enterDeleteButton);
                    
                    const cancelDeleteBtn = enterOrClientDeletebackWindow.querySelector(".selectbuttons-box__cancel-delete-button");
                    const deleteFormWindow = enterOrClientDeletebackWindow.querySelector(".enter-or-client-delete__info-window");
                    deleteFormWindow.classList.add("activate-pop-in");

                    cancelDeleteBtn.addEventListener("click", ()=>{
                        deleteFormWindow.classList.remove("activate-pop-in");
                        deleteFormWindow.classList.add("activate-pop-out");

                        setTimeout(() => {
                            deleteFormWindow.remove();
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

                deleteContactBtns.forEach(deleteBtn =>{
                    
                    deleteBtn.addEventListener("click", (e)=>{
                       
                        enterOrClientDeletebackWindow.classList.remove("hidThis");
                        enterOrClientDeletebackWindow.innerHTML = enterOrContactSwitchWindow(e.target.classList[0], deleteBtn);
                        
                        const contactDeleteCancelBtn = enterOrClientDeletebackWindow.querySelector(".selectbuttons-box__cancel-delete-button");
                        const contactDeleteFormWindow = enterOrClientDeletebackWindow.querySelector(".enter-or-client-delete__info-window"); 
                        contactDeleteFormWindow.classList.add("activate-pop-in");
                        
                        contactDeleteCancelBtn.addEventListener("click", ()=>{
                            contactDeleteFormWindow.classList.remove("activate-pop-in");
                            contactDeleteFormWindow.classList.add("activate-pop-out");
                            setTimeout(() => {
                                contactDeleteFormWindow.remove();
                                enterOrClientDeletebackWindow.classList.add("hidThis");
                            }, 200);
                            
                        });
                   }); 
                });
            }
            
            if(contactEditBtns != null && contactEditCancelBtns != null){
                
                contactEditBtns.forEach(editBtn =>{
                    editBtn.addEventListener("click", ()=>{
                        const parentForm = editBtn.closest(".enterprise-forms__contact-form");
                        const editConfirmationWindow = parentForm.querySelector(".contact-edit__background");
                        const editConfirmationInfoWindow = editConfirmationWindow.querySelector(".contact-edit__info-window");
                        editConfirmationWindow.classList.remove("hidThis");
                            if(editConfirmationInfoWindow.className.includes("activate-pop-out")) editConfirmationInfoWindow.classList.remove("activate-pop-out");
                            editConfirmationInfoWindow.classList.add("activate-pop-in");
                    }); 
                });
                
                contactEditCancelBtns.forEach(cancelBtn =>{
                    
                    cancelBtn.addEventListener("click", ()=>{
                        const parentWindow = cancelBtn.closest(".contact-edit__background");
                        const parentInfoWindow = parentWindow.querySelector(".contact-edit__info-window");
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
        
            deleteContactBtns.forEach(deleteBtn =>{
                deleteBtn.addEventListener("click", (e)=>{

                    enableOrDisablebackWindow.classList.remove("hidThis");
                    enableOrDisablebackWindow.innerHTML = typeSwitchWindow(deleteBtn);
                    
                    const typeDeleteCancelBtn = enableOrDisablebackWindow.querySelector(".selectbuttons-box__cancel-delete-button");
                    const typeDeleteFormWindow = enableOrDisablebackWindow.querySelector(".enable-or-disable__info-window");
                    typeDeleteFormWindow.classList.add("activate-pop-in");
                    
                    typeDeleteCancelBtn.addEventListener("click", ()=>{
                        typeDeleteFormWindow.classList.remove("activate-pop-in");
                        typeDeleteFormWindow.classList.add("activate-pop-out");
                        setTimeout(() => {
                            typeDeleteFormWindow.remove();
                            enableOrDisablebackWindow.classList.add("hidThis");
                        }, 200);
                        
                    });
                }); 
            });
        }
        
        if(contactEditBtns != null && contactEditCancelBtns != null){
            contactEditBtns.forEach(editBtn =>{
                
                editBtn.addEventListener("click", ()=>{
                    const parentForm = editBtn.closest(".edit-forms__type-form");
                    const editConfirmationWindow = parentForm.querySelector(".contact-edit__background");
                    const editConfirmationInfoWindow = editConfirmationWindow.querySelector(".contact-edit__info-window");
                    editConfirmationWindow.classList.remove("hidThis");
                    if(editConfirmationInfoWindow.className.includes("activate-pop-out")) editConfirmationInfoWindow.classList.remove("activate-pop-out");
                    editConfirmationInfoWindow.classList.add("activate-pop-in");
                }); 
            });
            
            contactEditCancelBtns.forEach(cancelBtn =>{
                cancelBtn.addEventListener("click", ()=>{
                    const parentWindow = cancelBtn.closest(".contact-edit__background");
                    const parentInfoWindow = parentWindow.querySelector(".contact-edit__info-window");
                    parentInfoWindow.classList.remove("activate-pop-in");
                    parentInfoWindow.classList.add("activate-pop-out");
                    setTimeout(() => {parentWindow.classList.add("hidThis");}, 200);
                    
                });
            });
            
        }
        
        /*Este if evalua si hay un elemento html propio de la vista devicesEditForms.php, que es donde el usuario
         * puede editar dispositivos, se reutilizan los elementos html (los botones) de los contactos de la 
         * vista enterAndContactsEditForms, estos tendrán como dataset el id de equipos y ventanas de activación o 
         * desactivación personalizables para los registros de equipos*/
        if(devicesEditFormsMain != null){
            
            deleteContactBtns.forEach(deleteBtn =>{
                deleteBtn.addEventListener("click", ()=>{
                    enableOrDisablebackWindow.classList.remove("hidThis");
                    enableOrDisablebackWindow.innerHTML = deviceSwitchWindow(deleteBtn);
                    
                    const deviceDeleteCancelBtn = enableOrDisablebackWindow.querySelector(".selectbuttons-box__cancel-delete-button");
                    const deviceDeleteFormWindow = enableOrDisablebackWindow.querySelector(".enable-or-disable__info-window");
                    deviceDeleteFormWindow.classList.add("activate-pop-in");
                    
                    deviceDeleteCancelBtn.addEventListener("click", ()=>{
                        deviceDeleteFormWindow.classList.remove("activate-pop-in");
                        deviceDeleteFormWindow.classList.add("activate-pop-out");
                        setTimeout(() => {
                            deviceDeleteFormWindow.remove();
                            enableOrDisablebackWindow.classList.add("hidThis");
                        }, 200);
                        
                    });
                }); 
            });
            
            if(contactEditBtns != null && contactEditCancelBtns != null){
                contactEditBtns.forEach(editBtn =>{
                    
                    editBtn.addEventListener("click", ()=>{
                        const parentForm = editBtn.closest(".device-form__form");
                        const editConfirmationWindow = parentForm.querySelector(".contact-edit__background");
                        const editConfirmationInfoWindow = editConfirmationWindow.querySelector(".contact-edit__info-window");
                        editConfirmationWindow.classList.remove("hidThis");
                        if(editConfirmationInfoWindow.className.includes("activate-pop-out")) editConfirmationInfoWindow.classList.remove("activate-pop-out");
                        editConfirmationInfoWindow.classList.add("activate-pop-in");
                    }); 
                });
                contactEditCancelBtns.forEach(cancelBtn =>{
                    cancelBtn.addEventListener("click", ()=>{
                        const parentWindow = cancelBtn.closest(".contact-edit__background");
                        const parentInfoWindow = parentWindow.querySelector(".contact-edit__info-window");
                        parentInfoWindow.classList.remove("activate-pop-in");
                        parentInfoWindow.classList.add("activate-pop-out");
                        setTimeout(() => {parentWindow.classList.add("hidThis");}, 200);
                   });
                });
            }
        }
        
        /*Este if verifica si es que existen elementos html propios del control de filtrado de bitácoras en la vista
         * binnaclesFilter.php*/
        if(binnFiltersDeviceSelect != null && binnFiltersContactSelect != null && binnFiltersEnterSelect != null){
            
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
            
            if(servicioOEquipo.value === "Servicio"){
                $(binnFiltersDeviceSelect).prop("disabled", true);
            }
            
            servicioOEquipo.addEventListener("change", ()=>{
                if(servicioOEquipo.value === "Servicio"){
                    $(binnFiltersDeviceSelect).val("").trigger("change");
                    $(binnFiltersDeviceSelect).prop("disabled", true);
                }else{
                    $(binnFiltersDeviceSelect).prop("disabled", false);
                }
            });
            
            $(binnFiltersEnterSelect).on("change", ()=>{
                
                const enterIdFromFilterSelected = binnFiltersEnterSelect.value;
                if(enterIdFromFilterSelected !== ""){
                    
                    fetch(BASE_URL + "home/?homeController=binnacle&homeAction=binnaclesReport", {
                    "method": "POST",
                    "headers": {
                        "Content-Type": "application/json; charset=utf-8"
                    },
                    "body": JSON.stringify({"enterIdFromBinnFilter": `${enterIdFromFilterSelected}`})
                    })
                    .then(res => res.json())
                    .then(data => {
                        
                        let contactOptionsHtml = "<option value=''></option>";
                        let devicesOptionsHtml = "<option value=''></option>";
                        
                        if (data.enterContactsToBinnsFilter.length > 0) {
                            for(const contact of data.enterContactsToBinnsFilter){
                                contactOptionsHtml += `<option value="${contact.Id}">${contact.Nombre_completo}</option>`;
                            }        
                        }
                        
                        if (data.enterDcesToBinnsFilter.length > 0) {
                            for(const device of data.enterDcesToBinnsFilter){
                                devicesOptionsHtml += `<option value="${device.Id}">${device.Marca} - ${device.Numero_serie}</option>`;
                            }
                        }
                        
                        binnFiltersContactSelect.innerHTML = contactOptionsHtml;
                        binnFiltersDeviceSelect.innerHTML = devicesOptionsHtml;
                    });
                }
            });
        }
        
        /*Este if evalúa si existen elementos html propios de la vista binnaclesFilter.php cuando se realizó la
         * busqueda de bitácoras de acuerdo a las opciones de filtrado dadas*/
        if(binnsFilternumkeySelect != null && binnacleTbody != null){
            
            binnsFilternumkeySelect.addEventListener("change", () =>{
                
                const binnsFilterNumSelected = binnsFilternumkeySelect.value;
                
                if(binnsFilterNumSelected != null){
                    
                    fetch(BASE_URL + "home/?homeController=binnacle&homeAction=binnaclesReport", {
                        "method": "POST",
                        "headers": {
                            "Content-Type": "application/json; charset=utf-8"
                        },
                        "body": JSON.stringify({"binnsFilterNumber": `${binnsFilterNumSelected}`})
                    })
                    .then(res => res.json())
                    .then(data => {

                        binnacleTbody.innerHTML = "";
                        data.binns.forEach(binn =>{
                            binnacleTbody.append(generateTableRow(binn));
                        });
                        binnFilterPaginationBox.innerHTML = data.buttons;
                        
                        /* NOTA: EN ESTE CONTEXTO SE ESTÁ CAMBIANDO EL DOCUMENTO HTML, AUNQUE EN LA SECCION DE CONSTANTES ESTÉN 
                        * SELECCIONADOS ESTOS ELEMENTOS (LOS BOTONES DESACTIVAR/ACTIVAR) ESTOS YA NO SE CONTEMPLAN 
                        * POR MEDIO DE "document" SINO POR MEDIO DE SU ELEMENTO PADRE DONDE SE EFECTÚO EL CAMBIO 
                        * (binnacleTbody)*/
                        const binnEDBtns = binnacleTbody.querySelectorAll(".binnacle-data-table__binn-delete-btn");
                        
                        binnEDBtns.forEach(delBtn =>{
                        
                            delBtn.addEventListener("click", ()=>{
                                
                                binnDeletebackWindow.classList.remove("hidThis");
                                binnDeletebackWindow.innerHTML = binnsSwitchWindow(delBtn);
                                
                                const binnDelWindow = binnDeletebackWindow.querySelector(".binnacle-delete__info-window");
                                const binnDelCancelBtn = binnDeletebackWindow.querySelector(".selectbuttons-box__cancel-delete-button");
                                binnDelWindow.classList.add("activate-pop-in");
                                
                                binnDelCancelBtn.addEventListener("click", ()=>{
                                    binnDelWindow.classList.remove("activate-pop-in");
                                    binnDelWindow.classList.add("activate-pop-out");
                                    setTimeout(() => {
                                        binnDeletebackWindow.classList.add("hidThis");
                                        binnDelWindow.remove();
                                    }, 200);
                                    
                                });
                            });
                        });
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
                
                delBtn.addEventListener("click", ()=>{
                    
                    binnDeletebackWindow.classList.remove("hidThis");
                    binnDeletebackWindow.innerHTML = binnsSwitchWindow(delBtn);
                    
                    const binnDelWindow = binnDeletebackWindow.querySelector(".binnacle-delete__info-window");
                    const binnDelCancelBtn = binnDeletebackWindow.querySelector(".selectbuttons-box__cancel-delete-button");
                    binnDelWindow.classList.add("activate-pop-in");
                    
                    binnDelCancelBtn.addEventListener("click", ()=>{
                        binnDelWindow.classList.remove("activate-pop-in");
                        binnDelWindow.classList.add("activate-pop-out");
                        setTimeout(() => {
                            binnDeletebackWindow.classList.add("hidThis");
                            binnDelWindow.remove();
                        }, 200);
                        
                    });
                });
            });
        }
        
        if(editBinnacleBtn != null && binnEditConfirmationBackground != null){
            
            if(editBinnUserSelect != null){
                $(editBinnUserSelect).select2({
                    placeholder: "Selecciona un equipo",
                    allowClear: false,
                    width: "100%"
                });
            }

            const binnEditCancelBtn = binnEditConfirmationBackground.querySelector("#binnEditCancelBtn");
            
            editBinnacleBtn.addEventListener("click", ()=>{
                binnEditConfirmationBackground.classList.remove("hidThis");
                if(binnEditConfirmationInfoWindow.className.includes("activate-pop-out")) binnEditConfirmationInfoWindow.classList.remove("activate-pop-out");
                binnEditConfirmationInfoWindow.classList.add("activate-pop-in");
            });
            
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
            deviceReportPdfBtn.addEventListener("click", ()=>{
                deviceReportPdfBtn.classList.add("pdf-disabled");
            });
        }
        
        if(binnPdfBtns != null){
            binnPdfBtns.forEach(pdfBtn =>{
               pdfBtn.addEventListener("click", ()=>{
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