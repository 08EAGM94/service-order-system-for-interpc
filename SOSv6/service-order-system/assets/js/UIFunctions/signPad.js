
const generateCanvas = (canvas, innerWidth, innerHeight) =>{
    
    const ctx = canvas.getContext("2d");
    const canvasOffsetX = canvas.offsetLeft;
    const canvasOffsetY = canvas.offsetTop;
    //-----------------------------------------------------------------------------------------------------------
    let isPainting = false;
    let lineWidth = 5;
    //---------------------------------------------------------------------------------------------------------
    canvas.width = innerWidth - canvasOffsetX;
    canvas.height = innerHeight - canvasOffsetY;
    //---------------------------------------------------------------------------------------------------------

    const draw = (e, isMouse) =>{

        if(!isPainting)
            return;

        if(isMouse){
            ctx.lineWidth = lineWidth;
            ctx.lineCap = "round";
            ctx.lineTo(e.clientX - canvasOffsetX, e.clientY);
            ctx.stroke();
        }else{
            e.preventDefault();

            ctx.lineWidth = lineWidth;
            ctx.lineCap = "round";
            
            ctx.lineTo(e.touches[0].clientX - canvasOffsetX, e.touches[0].clientY);
            ctx.stroke();
        }
    };

    canvas.addEventListener("mousedown", () =>{
        isPainting = true;
    });
    
    canvas.addEventListener("mouseup", () =>{
        isPainting = false;
        ctx.stroke();
        ctx.beginPath();
    });

    canvas.addEventListener("mousemove", (e) => draw(e, true));
    
    canvas.addEventListener("touchstart", e =>{
        isPainting = true;
        e.preventDefault();
    });
    
    canvas.addEventListener("touchend", () =>{
        isPainting = false;
        ctx.stroke();
        ctx.beginPath();
    });

    canvas.addEventListener("touchmove", (e) => draw(e, false));
};
/*
la función dataURLtoBlob representaría la segunda logica del pad de firmas, su principal función es convertir el string base64 del canvas en cuestion
en una imagen Blob (binary large object) el cual es un conjunto de datos binarios crudos de un archivo de imagen, se necesita hacer esto para enviar los datos por 
medio del objeto de formulario de javascript que se inicializó al principio de este archivo JS para que PHP lo interprete como un archivo enviado por un formulario 
($_FILE), la función necesita de un argumento el cual es el string base64 del canvas para funcionar.
*/
const dataURLtoBlob = (dataURL) => {
    
    const parts = dataURL.split(';base64,');
    const contentType = parts[0].split(':')[1];
    const raw = window.atob(parts[1]);
    const rawLength = raw.length;
    const uInt8Array = new Uint8Array(rawLength);

    for (let i = 0; i < rawLength; ++i) {
        uInt8Array[i] = raw.charCodeAt(i);
    }

    return new Blob([uInt8Array], { type: contentType });
};
/*Este metodo es otra logica del pad de firmas, aqui se determina si el canvas esta en "blanco"*/
const isCanvasBlank = (canvas) => {
    
    const ctx = canvas.getContext('2d');
    const pixelBuffer = new Uint32Array(
            ctx.getImageData(0, 0, canvas.width, canvas.height).data.buffer
            );
    return !pixelBuffer.some(color => color !== 0);
};

function generateSignPad(technicianCanvas, clientCanvas, formData, serverData){

    if(technicianCanvas != null){
        generateCanvas(technicianCanvas, window.innerWidth, window.innerHeight);
        window.addEventListener("resize", () => {
            generateCanvas(technicianCanvas, window.innerWidth, window.innerHeight);
        });
    }else if(clientCanvas != null){
        generateCanvas(clientCanvas, window.innerWidth, window.innerHeight);
        window.addEventListener("resize", () => {
            generateCanvas(clientCanvas, window.innerWidth, window.innerHeight);
        });
    }
    //----------------------------------------------------------------------
    
    //----------------------------------------------------------------------
    buttonsBox.addEventListener("click", e => {

        if (e.target.id === "cleanButton") {
            
            (technicianCanvas != null) ? 
            generateCanvas(technicianCanvas, window.innerWidth, 
            window.innerHeight) :
            generateCanvas(clientCanvas, window.innerWidth, 
            window.innerHeight);
            
        } else if (e.target.id === "nextButton") {
            
            backWindow.classList.remove("hidThis");
            if(infoWindow.className.includes("activate-pop-out")) infoWindow.classList.remove("activate-pop-out");
            infoWindow.classList.add("activate-pop-in");
            
            const iconBox = document.createElement("div");
            const textBox = document.createElement("div");
            const buttonsBox = document.createElement("div");
            
            iconBox.classList.add("pop-up-window-icon");
            textBox.classList.add("info-window__text-box");
            buttonsBox.classList.add("info-window__selectbuttons-box");
            
            iconBox.innerHTML = `<img class="pop-up-window-icon__img" src="${BASE_URL}assets/img/caution-sign_75243.png"/>`;
            textBox.innerHTML = "<h3>¿Estas seguro de guardar la firma?</h3>";
            buttonsBox.innerHTML = '<button class="selectbuttons-box__button" id="yes">Si</button>' +
                    '<button class="selectbuttons-box__button" id="no">No</button>';
            
            infoWindow.append(iconBox);
            infoWindow.append(textBox);
            infoWindow.append(buttonsBox);
            
            const buttonsArea = infoWindow.querySelector(".info-window__selectbuttons-box");
            
            buttonsArea.addEventListener("click", e => {
                if (e.target.id === "yes") {
                    
                    if(technicianCanvas != null){
                        if(isCanvasBlank(technicianCanvas)){
                            
                            buttonsBox.classList.add("okCenter");
                            
                            iconBox.innerHTML = `<img class="pop-up-window-icon__img" src="${BASE_URL}assets/img/delete-button_8637533.png"/>`;
                            textBox.innerHTML = `<h3>Debes de pintar tu firma antes de seguir</h3>`;
                            buttonsBox.innerHTML = '<button class="selectbuttons-box__button" id="ok">OK</button>';
                            
                            const okArea = infoWindow.querySelector(".info-window__selectbuttons-box");
                            
                            okArea.addEventListener("click", e =>{
                                if(e.target.id === "ok"){
                                    infoWindow.classList.remove("activate-pop-in");
                                    infoWindow.classList.add("activate-pop-out");
                                    setTimeout(() => {
                                        backWindow.classList.add("hidThis");
                                        iconBox.remove();
                                        textBox.remove();
                                        buttonsBox.remove();
                                    }, 200);
                                    
                                }
                            });
                        }    
                    }
                    
                    if(clientCanvas != null){
                        if(isCanvasBlank(clientCanvas)){
                            buttonsBox.classList.add("okCenter");
                            iconBox.innerHTML = `<img class="pop-up-window-icon__img" src="${BASE_URL}assets/img/delete-button_8637533.png"/>`;  
                            textBox.innerHTML = `<h3>Debes de pintar la firma del cliente antes de seguir</h3>`;
                            buttonsBox.innerHTML = '<button class="selectbuttons-box__button" id="ok">OK</button>';
                            const okArea = infoWindow.querySelector(".info-window__selectbuttons-box");
                            okArea.addEventListener("click", e =>{
                                if(e.target.id === "ok"){
                                    infoWindow.classList.remove("activate-pop-in");
                                    infoWindow.classList.add("activate-pop-out");
                                    setTimeout(() => {
                                        backWindow.classList.add("hidThis");
                                        iconBox.remove();
                                        textBox.remove();
                                        buttonsBox.remove();
                                    }, 200);
                                }
                            });
                        }
                    }    
                    //------------------------------------------------------
                    
                    //------------------------------------------------------
                    if(technicianCanvas != null){
                        if(!isCanvasBlank(technicianCanvas)){
                            const dataURL = technicianCanvas.toDataURL('image/png');
                            const imageBlob = dataURLtoBlob(dataURL);
                            formData.append(`${(serverData.binnId != null) ? "techSign" : "newTechSign"}`, imageBlob, `userid_${serverData.userId}_${serverData.userName.split(" ").join("_")}_${serverData.userSurname.split(" ").join("_")}_Sign.png`);
                        }
                    }else if(clientCanvas != null){
                        if(!isCanvasBlank(clientCanvas)){   
                            const dataURL = clientCanvas.toDataURL('image/png');
                            const imageBlob = dataURLtoBlob(dataURL);
                            formData.append("cliSign", imageBlob, `bitacoraid_${serverData.binnId}_${serverData.clientName.split(" ").join("_")}_${serverData.altClientName.split(" ").join("_")}_Sign.png`);
                        }
                    } 
                    //------------------------------------------------------
                    
                    if(formData.has("techSign") || formData.has("cliSign") || formData.has("newTechSign")){
                        
                        fetch(BASE_URL + "finishing/", {
                            method: "POST",
                            headers: {
                                "X-Requested-With": "XMLHttpRequest"
                            },
                            body: formData
                        })
                        .then(res => res.text())
                        .then(txt => {
                            buttonsBox.classList.add("okCenter");
                            iconBox.innerHTML = `<img class="pop-up-window-icon__img" src="${BASE_URL}assets/img/check_13526142.png"/>`;
                            textBox.innerHTML = `<h3>${txt}</h3>`;
                            
                            let determinateMethod = (serverData.binnId != null) ? '<a class="selectbuttons-box__button ok" id="ok"' +
                                ' href="'+BASE_URL+'finishing/?controller=followupform&action=clientSign">OK</a>' : '<a class="selectbuttons-box__button ok" id="ok"' +
                                ' href="'+BASE_URL+'home/?homeController=user&homeAction=editSign">OK</a>';
                            buttonsBox.innerHTML = (technicianCanvas != null) ? determinateMethod :
                            '<a class="selectbuttons-box__button ok" id="ok"' +
                                ' href="'+BASE_URL+'finishing/?controller=followupform&action=finishbinnacle">OK</a>';
                            
                            const okArea = infoWindow.querySelector(".info-window__selectbuttons-box");
                            okArea.addEventListener("click", e =>{
                                
                                if(e.target.id === "ok"){
                                    
                                    (technicianCanvas != null) ? formData.delete(`${(serverData.binnId != null) ? "techSign" : "newTechSign"}`) :
                                            formData.delete("cliSign");
                                    infoWindow.classList.remove("activate-pop-in");
                                    infoWindow.classList.add("activate-pop-out");
                                    setTimeout(() => {
                                        backWindow.classList.add("hidThis");
                                        iconBox.remove();
                                        textBox.remove();
                                        buttonsBox.remove();
                                    }, 200);        
                                    
                                }
                            });
                        })
                        .catch(error => {
                            
                            buttonsBox.classList.add("okCenter");
                            iconBox.innerHTML = `<img class="pop-up-window-icon__img" src="${BASE_URL}assets/img/delete-button_8637533.png"/>`;
                            textBox.innerHTML = `<h3>${error}</h3>`;
                            buttonsBox.innerHTML = '<button class="selectbuttons-box__button" id="ok">OK</button>';
                            const okArea = infoWindow.querySelector(".info-window__selectbuttons-box");
                            
                            okArea.addEventListener("click", e =>{
                                if(e.target.id === "ok"){
                                    
                                    (technicianCanvas != null) ? formData.delete(`${(serverData.binnId != null) ? "techSign" : "newTechSign"}`) :
                                            formData.delete("cliSign");
                                    infoWindow.classList.remove("activate-pop-in");
                                    infoWindow.classList.add("activate-pop-out");
                                    setTimeout(() => {
                                        backWindow.classList.add("hidThis");
                                        iconBox.remove();
                                        textBox.remove();
                                        buttonsBox.remove();
                                    }, 200); 
                                }
                            });
                        });
                    }    

                }else if(e.target.id === "no"){
                    infoWindow.classList.remove("activate-pop-in");
                    infoWindow.classList.add("activate-pop-out");
                    setTimeout(() => {
                        backWindow.classList.add("hidThis");
                        iconBox.remove();
                        textBox.remove();
                        buttonsBox.remove();
                    }, 200);
                }
            });
        }else if(e.target.id === "cancelButton"){
            
            backWindow.classList.remove("hidThis");
            if(infoWindow.className.includes("activate-pop-out")) infoWindow.classList.remove("activate-pop-out");
            infoWindow.classList.add("activate-pop-in");
            
            const iconBox = document.createElement("div");
            const textBox = document.createElement("div");
            const buttonsBox = document.createElement("div");
            
            iconBox.classList.add("pop-up-window-icon");
            textBox.classList.add("info-window__text-box");
            buttonsBox.classList.add("info-window__selectbuttons-box");
            
            iconBox.innerHTML = `<img class="pop-up-window-icon__img" src="${BASE_URL}assets/img/caution-sign_75243.png"/>`;
            textBox.innerHTML = (serverData.binnId != null) ? "<h3>¿quieres regresar a la parte de conformidad de actividades?</h3>" : 
                    '<h3>¿quieres regresar al apartado de "Editar firmas"?</h3>';
            buttonsBox.innerHTML = (serverData.binnId != null) ? '<a class="selectbuttons-box__button ok" id="ok"' +
                            ' href="'+BASE_URL+'finishing/?controller=followupform&action=index&id='+ serverData.binnId +'">Si</a>' +
                    '<button class="selectbuttons-box__button" id="no">No</button>' :
                    '<a class="selectbuttons-box__button ok" id="ok"' +
                            ' href="'+BASE_URL+'home/?homeController=user&homeAction=editSign">Si</a>' +
                    '<button class="selectbuttons-box__button" id="no">No</button>';
            
            infoWindow.append(iconBox);
            infoWindow.append(textBox);
            infoWindow.append(buttonsBox);
            
            const buttonsArea = document.querySelector(".info-window__selectbuttons-box");
            
            buttonsArea.addEventListener("click", e =>{
                if(e.target.id === "ok"){
                    
                    backWindow.classList.add("hidThis");

                    iconBox.remove();
                    textBox.remove();
                    buttonsBox.remove();
                }else if(e.target.id === "no"){

                    infoWindow.classList.remove("activate-pop-in");
                    infoWindow.classList.add("activate-pop-out");
                    setTimeout(() => {
                        backWindow.classList.add("hidThis");
                        iconBox.remove();
                        textBox.remove();
                        buttonsBox.remove();
                    }, 200);
                } 
            });
        }
        
    });
    //--------------------------------------------------------------------
    
}
export {generateSignPad, generateCanvas, dataURLtoBlob, isCanvasBlank};
//module.exports = { generateCanvas, dataURLtoBlob, isCanvasBlank };