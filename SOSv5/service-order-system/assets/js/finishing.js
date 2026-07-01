import {generateSignPad} from './UIFunctions/signPad.js';
document.addEventListener("DOMContentLoaded", () =>{

    //-----------------------------declaración de constantes---------------------------------------------------------
    
    const backWindow = document.querySelector("#backWindow");
    const infoWindow = document.querySelector("#infoWindow");
    const consentNo = document.querySelector("#consentNo");
    const consentCancelBtn = document.querySelector("#consentCancelBtn");
    
    const formBackWindow = document.querySelector("#formBackWindow");
    const remindedBackWindow = document.querySelector("#remindedBackWindow");
    const remindedInfoWindow = document.querySelector(".binnacleremindedfields__info-window");
    const remindedYes = document.querySelector("#remindedYes");
    const remindedNo = document.querySelector("#remindedNo");
    const remindedCancelBtn = document.querySelector("#remindedCancelBtn");
    
    const technicianCanvas = document.querySelector("#technicianCanvas");
    const clientCanvas = document.querySelector("#clientCanvas");
    const buttonsBox = document.querySelector("#buttonsBox");
    //----------------------------------------------------------------------------------------------------------------

    //objeto de la clase FormData necesario para enviar datos al servidor (php) en la petición http ascincrona con fetch
    //más adelante se describe su función en la linea donde se utiliza.
    const formData = new FormData();
    
    /*estas constantes guardan datos del servidor (php) en dado caso de que existan, si no, tendrán un valor null, estos datos son un objeto JSON
    el cual fue originalmente una sesión de php con los datos obtenidos durante el seguimiento de la bitácora (o edición de firma), esto
    con el fin de usarlos para crear el nombre de las imagenes de las firmas que se van a generar, la forma del nombre
    de los archivos omite los espacios y se le sustituye por un guión bajo, por ejemplo, si el nombre del cliente "cliName"
    tiene espacios: Alma Morales, termina convirtiendose en: Alma_Morales gracias crear un arreglo del string separados por los espacios
    gracias a split() y volver a juntar el arreglo añadiendo un guión bajo para convertirlo en string gracias a join().*/
    const serverData = (window.serverData != null) ? window.serverData : null;
    //----------------------------------------------------------------------------------------------------------------
    
    //-----------------------------declaración de constantes---------------------------------------------------------

    //---------------------------------------------manejo de eventos---------------------------------------------------------

    if(backWindow != null && consentNo != null && consentCancelBtn != null){
        /*Este if evalua si están los elementos html necesarios para aplicarles una funcionalidad, en este caso, la ventana de cancelación en la vista
         de conformidad de actividades, aqui solo se gestiona eventos click, primero, si se le da click al botón de cancelar le quitará al elemento del fondo 
         de la ventana emergente la clase "hidThis" el cual tiene el estilo "display: none;", si en la ventana emegente se le da click al botón "No"
         se le agregará al fondo de la ventana emergente la clase "hidThis"*/
        consentCancelBtn.addEventListener("click", ()=>{
            backWindow.classList.remove("hidThis");
            if(infoWindow.className.includes("activate-pop-out")) infoWindow.classList.remove("activate-pop-out");
            infoWindow.classList.add("activate-pop-in");
            consentNo.addEventListener("click", () =>{
                infoWindow.classList.remove("activate-pop-in");
                infoWindow.classList.add("activate-pop-out");
                setTimeout(() => {backWindow.classList.add("hidThis");}, 200);
                    
            });
        });
    }
    
    
    if(formBackWindow != null     &&
       remindedBackWindow != null &&
       remindedYes != null        &&
       remindedNo != null         &&
       remindedCancelBtn != null){
   
        /*Este if evalua si es que existen elementos html propios de la vista de remindedfields.php, 
         elementos los cuales son botones y ventanas emergentes de cancelación*/
        
        remindedCancelBtn.addEventListener("click", ()=>{
            remindedBackWindow.classList.remove("hidThis");
            if(remindedInfoWindow.className.includes("activate-pop-out")) remindedInfoWindow.classList.remove("activate-pop-out");
            remindedInfoWindow.classList.add("activate-pop-in");
        })
        
        remindedYes.addEventListener("click", ()=>{
            remindedBackWindow.classList.add("hidThis");
            formBackWindow.classList.remove("hidThis");
        });
        
        remindedNo.addEventListener("click", ()=>{
            remindedInfoWindow.classList.remove("activate-pop-in");
            remindedInfoWindow.classList.add("activate-pop-out");
            setTimeout(() => {remindedBackWindow.classList.add("hidThis");}, 200);
        });
        
    }
    
    if ((technicianCanvas != null || clientCanvas != null) && (serverData != null))
        generateSignPad(technicianCanvas, clientCanvas, formData, serverData);

   //---------------------------------------------manejo de eventos--------------------------------------------------------- 
});