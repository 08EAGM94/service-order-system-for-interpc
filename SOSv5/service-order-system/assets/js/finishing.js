/* 
Este archivo JS se incluye en el html <head> del archivo: views/finishingLayouts/htmlSides/head.php
gracias a aplicarle al archivo html "document" el evento "DOMContentLoaded", el codigo javascript
(el cual esta dentro de la función anonima del evento) empieza a funcionar ya despues de haber 
cargado todos los elementos html. hay que mencionar que en el servidor, en este caso PHP, divide
el documento html en cuatro principales partes gracias a "require_once" (el cual permite no repetir 
html y dar dinamismo a las vistas), head.php, welcomeBanner.php (el banner de bienvenida dependiendo si el 
privilegio del usuario es "user" o "Admin"), (cualquier vista que utilice la etiqueta <main>, ejemplo: 
consentInfo.php) y footer.php; en este caso este archivo js se utiliza en: views/finishingLayouts/htmlSides/head.php,
el cual es ahi donde se empieza la sintaxis de html, ahí se incluye las cabeceras (el lugar donde se carga los estilos
y codigo JS) y se abre la etiqueta <body>, en este caso, la vista de seguimiento de bitácoras no cuenta con un banner
de bienvenida por lo que en esta ocasión no se carga ninguna etiqueta <header> en el documento, lo que si se podrá incluir
es el contenido principal del documento, contenido que puede cambiar dinamicamente gracias al controlador FormController.php
y cuyos elementos que requieren funcionalidad se seleccionarán en las constantes, finalmente, views/finishingLayouts/htmlSides/footer.php es muy simple,
su unica función es concluir la semantica del html esto es cerrar el body con </body> y el html con </html> 
*/
document.addEventListener("DOMContentLoaded", () =>{

    //-----------------------------declaración de constantes---------------------------------------------------------
    
    //elementos html identificados por la propiedad "id" en el html de las posibles vistas generadas en el documento
    const backWindow = document.querySelector("#backWindow");
    const infoWindow = document.querySelector("#infoWindow");
    const consentNo = document.querySelector("#consentNo");
    const consentCancelBtn = document.querySelector("#consentCancelBtn");
    
    const formBackWindow = document.querySelector("#formBackWindow");
    const remindedBackWindow = document.querySelector("#remindedBackWindow");
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
    const binnId = (window.serverData != null) ? window.serverData.binnId : null;
    const cliName = (window.serverData != null && window.serverData.clientName != null) ? window.serverData.clientName.split(" ").join("_") : null;
    const commName = (window.serverData != null && window.serverData.altClientName != null) ? window.serverData.altClientName.split(" ").join("_") : null;
    const userId = (window.serverData != null) ? window.serverData.userId : null;
    const userName = (window.serverData != null) ? window.serverData.userName.split(" ").join("_") : null;
    const userSurname = (window.serverData != null) ? window.serverData.userSurname.split(" ").join("_") : null;
    const oldTechSign = (window.serverData != null) ? window.serverData.oldTechSign : null;
    //----------------------------------------------------------------------------------------------------------------
    
    //-----------------------------declaración de constantes---------------------------------------------------------

    //-----------------------------declaración de funciones callback---------------------------------------------------------

    /* 
    este documento, aparte de gestionar eventos "click", tambien alberga la logica del "pad" de las firmas, la logica se divide en 4 partes,
    la primer parte consiste en esta función tipo "callback", esto es que se utiliza o llama en una parte en concreto de este archivo JS, generateCanvas
    necesita de 3 argumentos, el elemento html canvas del documento, el ancho de la ventana del navegador y la altura de la ventana del navegador, esta
    función alberga la lógica necesaria para pintar en el canvas html al presionar (tanto en ratón como el dedo en una pantalla touch) y moverse dentro 
    de este elemento html.
    */
    const generateCanvas = (canvas, innerWidth, innerHeight) =>{
        //la constante ctx tiene contenido el contexto del elemento html canvas pasado por argumento en la función, como se pintan de forma 
        //bidimensional las firmas, entonces el contexto es en "2d"
        const ctx = canvas.getContext("2d");
        //esto es obtener la distancia (tanto horizontal como vertical) que el canvas tiene con respecto al viewport.
        const canvasOffsetX = canvas.offsetLeft;
        const canvasOffsetY = canvas.offsetTop;
        //-----------------------------------------------------------------------------------------------------------

        //son variables con valores por defecto, isPainting se puede modificar a true por ejemplo en los eventos de 
        //pulsación de mause o touch y se utiliza en la estructura de control de la función interna "draw",
        //linewidth hace referencia al grosor de la linea que se está pintando
        let isPainting = false;
        let lineWidth = 5;
        //---------------------------------------------------------------------------------------------------------

        //esto es especificar el ancho y altura exactos del canvas ya que en el viewport puede haber más elementos.
        canvas.width = innerWidth - canvasOffsetX;
        canvas.height = innerHeight - canvasOffsetY;
        //---------------------------------------------------------------------------------------------------------

        /*
        funcion interna que utilizan los eventos de movimiento tanto del ratón "mousemove" como del touch "touchmove",
        evidentemiente esto tiene que estar al unisono de los eventos de presionar, "mousedown" y "touchstart" los
        cuales le dan a la variable isPainting el valor true, en dado caso que sea false la función no retornará nada
        esto es que no se generará ninguna linea cuando el usuario mueva el ratón sin presionar nada. esta función
        requiere dos argumentos, el evento de movimiento y un valor booleano que indica si el movimiento se está
        haciendo con el ratón o no
        */
        const draw = (e, isMouse) =>{
            if(!isPainting){
                return;
            }
            if(isMouse){
                /*
                 Bloque en el caso de que isMouse sea true, codigo necesario para pintar el canvas con un mouse
                */
                //se le añade al contexto del canvas el grosor de la linea 
                ctx.lineWidth = lineWidth;
                //se indica la forma de trazado de la linea, en este caso circular
                ctx.lineCap = "round";
                //el mentodo lineTo procesa las coordenadas de movimiento del evento para pintar una linea, se requiere restar la coordenada x
                //menos el tamaño horizontal exacto del canvas para que la flecha del mouse coincida con la linea que se va a pintar
                ctx.lineTo(e.clientX - canvasOffsetX, e.clientY);
                //el metodo stroke() se refiere al pintado de la linea dentro del contexto del canvas. si el evento es mousemove o 
                //touchmove el pintado de la linea será progresivo al movimiento de estos dos eventos.
                ctx.stroke();
            }else{
                /*
                 Bloque en el caso de que isMouse sea false, codigo necesario para pintar el canvas en una pantalla touch
                */
               //la acción por defecto de presionar el dedo en un navegador es mover la posicion del navegador dependiendo del movimiento del dedo
               //preventDefault() evita esta acción ya que necesitamos que el pad de la firma este estatica para poder pintar una firma.
                e.preventDefault();

                ctx.lineWidth = lineWidth;
                ctx.lineCap = "round";
                /*
                la propiedad del evento "touches" accede a un indice de un array porque, a diferencia del ratón que es un unico puntero, las pantallas 
                táctiles permiten el multi-toque. Una pantalla tactil puede identificar varios dedos al mismo tiempo. Por ello, el navegador devuelve una 
                lista llamada "TouchList", que es un objeto "similar a un array" donde cada elemento representa un dedo o punto de contacto diferente en 
                la superficie. Al usar e.touches[0] se está indicando que solo interesa las coordenadas del "primer dedo" que tocó la pantalla. En un 
                evento de ratón, el objeto del evento tiene las propiedades clientX y clientY directamente en la raíz por que solo hay un cursor. En 
                eventos táctiles, estas coordenadas viven dentro de cada objeto de toque individual contenido en la lista.
                */  
                ctx.lineTo(e.touches[0].clientX - canvasOffsetX, e.touches[0].clientY);
                ctx.stroke();
            }
        };

        //evento de ratón al momento de hacer click en uno de sus botones, cambia el valor de isPainting a true.
        canvas.addEventListener("mousedown", () =>{
            isPainting = true;
        });
        
        //evento de ratón al momento de dejar de hacer click en uno de sus botones, cambia el valor de isPainting a false.
        canvas.addEventListener("mouseup", () =>{
            isPainting = false;
            ctx.stroke();
            //el método beginPath permite interrumpir el trazo de una linea y empezar otra en una coordenada distinta.
            ctx.beginPath();
        });

        //evento de movimiento del ratón, aqui se llama a la función draw y se le pasa como argumentos el evento y el valor true
        //para activar la lógica de pintado de linea del ratón
        canvas.addEventListener("mousemove", (e) => draw(e, true));
        
        //evento touch al momento de presionar un dedo en el navegador, cambia el valor de isPainting a true.
        canvas.addEventListener("touchstart", e =>{
            isPainting = true;
            e.preventDefault();
        });
        
        //evento touch al momento de soltar un dedo en el navegador, cambia el valor de isPainting a false.
        canvas.addEventListener("touchend", () =>{
            isPainting = false;
            ctx.stroke();
            ctx.beginPath();
        });

        //evento de movimiento touch, aqui se llama a la función draw y se le pasa como argumentos el evento y el valor false
        //para activar la lógica de pintado para pantallas touch.
        canvas.addEventListener("touchmove", (e) => draw(e, false));
    };
    
    /*
    la función dataURLtoBlob representaría la segunda logica del pad de firmas, su principal función es convertir el string base64 del canvas en cuestion
    en una imagen Blob (binary large object) el cual es un conjunto de datos binarios crudos de un archivo de imagen, se necesita hacer esto para enviar los datos por 
    medio del objeto de formulario de javascript que se inicializó al principio de este archivo JS para que PHP lo interprete como un archivo enviado por un formulario 
    ($_FILE), la función necesita de un argumento el cual es el string base64 del canvas para funcionar.
    */
    const dataURLtoBlob = (dataURL) => {
        /* 
        En parts se genera un array de dos indices producto de haber separado el string Base64, cortando la parte exacta de ';base64,', esto es debido a que 
        despues de ';base64,', estan todos los caracteres necesarios (datos de la imagen) para obtener un string binario.
        */
        const parts = dataURL.split(';base64,');
        /*
        con la constante contentType se está obteniendo el string "image/png" el cual contiene el indice 0 de parts, al separar el indice 0 por algo que coincida 
        con ':' el indice 0 tiene dos indices el 0 corresponde a "data" y 1 corresponde a "image/png" por esa razon parts[0].split(':')[1] despues de la separación, 
        se accede al indice 1 de parts[0]. 
        */
        const contentType = parts[0].split(':')[1];
        //raw contiene el string binario de los datos de la imagen separados del string base64
        const raw = window.atob(parts[1]);
        //rawLength contiene el numero contado de caracteres del string binario
        const rawLength = raw.length;
        //Se crea un objeto tipo array de la clase Uint8Array(), debido a que la clase Blob necesita de un array object Uint8Array() para crear un objeto de datos 
        //binarios crudos, gracias a (rawLength), se establece el tamaño de este objeto.
        const uInt8Array = new Uint8Array(rawLength);

        for (let i = 0; i < rawLength; ++i) {
            uInt8Array[i] = raw.charCodeAt(i);
        }

        //Despues de haber guardado cada caracter del string binario al objeto de Uint8Array, se retorna un objeto de la clase Blob el cual necesita un array object 
        //de la clase Uint8Array(), y un json que determine el tipo de archivo, en este caso, "image/png"
        return new Blob([uInt8Array], { type: contentType });
    };
    
    /*Este metodo es otra logica del pad de firmas, aqui se determina si el canvas esta en "blanco"*/
    const isCanvasBlank = (canvas) => {
        //la constante ctx contiene el contexto del canvas pasado como argumento en la función.
        const ctx = canvas.getContext('2d');
        //la costante pixelBuffer es un objeto de la clase Uint32Array, al pasar al constructor el buffer (Uint8ClampedArray) de los datos del canvas se puede
        //tratar cada píxel como un único entero de 32 bits en lugar de manipular cuatro valores de 8 bits por separado (R, G, B, A), esto permite leer o escribir 
        //un píxel completo con una sola operación de asignación como en el caso del return
        const pixelBuffer = new Uint32Array(
                ctx.getImageData(0, 0, canvas.width, canvas.height).data.buffer
                );
        //some recorre el arreglo pixelBuffer, some admite una función callback y puede tener 3 parametros (valor actual, indice y un array), en este caso solo tiene
        //un parametro "color" el cual javascript lo interpreta como el valor del arreglo que se está recorriendo en ese momento, este callback evalua lo siguiente:
        //si cualquier canal (Rojo, Verde, Azul o Alpha) tiene un valor distinto de cero, el callback (y some) se detiene y devuelve true, sin embargo, en el return se usa el
        //operador de negación, por lo que el true que devuelva some pasará a false dando a entender que el canvas no está en blanco, si some devuelve false,
        //quiere decir que no hubo ningún pixel de color en el canvas, por lo tanto, en ese caso, esta función (isCanvasBlank) devuelve true.
        return !pixelBuffer.some(color => color !== 0);
    };

    //-----------------------------declaración de funciones callback---------------------------------------------------------

    //---------------------------------------------manejo de eventos---------------------------------------------------------

    if(backWindow != null && consentNo != null && consentCancelBtn != null){
        /*Este if evalua si están los elementos html necesarios para aplicarles una funcionalidad, en este caso, la ventana de cancelación en la vista
         de conformidad de actividades, aqui solo se gestiona eventos click, primero, si se le da click al botón de cancelar le quitará al elemento del fondo 
         de la ventana emergente la clase "hidThis" el cual tiene el estilo "display: none;", si en la ventana emegente se le da click al botón "No"
         se le agregará al fondo de la ventana emergente la clase "hidThis"*/
        consentCancelBtn.addEventListener("click", ()=>{
            backWindow.classList.remove("hidThis");
            consentNo.addEventListener("click", () =>{
                    backWindow.classList.add("hidThis");
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
        
        /*Esta gestión de evento, remueve la clase de estilo "hidThis" al fondo de la ventana emergente de
        confirmación de cancelación si se le da click al botón cancelar de la vista remindedfields.php*/
        remindedCancelBtn.addEventListener("click", ()=>{
            remindedBackWindow.classList.remove("hidThis");
        })
        
        /*Esta gestión de evento, añade la clase de estilo "hidThis" al fondo de la ventana emergente de confirmación
         * de cancelación al momento de dar click al botón "Si" y remueve la clase de estilo "hidThis" al fondo de la
         * ventana de formulario donde el usuario justifica su cancelación*/
        remindedYes.addEventListener("click", ()=>{
            remindedBackWindow.classList.add("hidThis");
            formBackWindow.classList.remove("hidThis");
        });
        
        /*Esta gestión de evento, añade la clase de estilo "hidThis" al fondo de la ventana de confirmación de 
         * cancelación al momento de dar click al botón "No"*/
        remindedNo.addEventListener("click", ()=>{
                remindedBackWindow.classList.add("hidThis");
        });
        
    }
    
    
    if ((technicianCanvas != null || clientCanvas != null) && (binnId != null || oldTechSign != null)) {
        
        /*Este if es una estructura de control donde se encuentra la logica "principal" del pad de firmas, evalúa si
         * existe el pad de firma del técnico o el pad de firma del cliente, y tambien evalúa si binnId no es null 
         * o si oldTechSign no es null, esas constantes tienen sus respectivos valores pasados por la sesión "dataSelectionForSigns" 
         * del servidor, en el caso de que binnId no sea null entonces se pasó datos del formulario del consentimiento 
         * de actividades en "dataSelectionForSigns", si oldTechSign no es null se pasaron datos de formulario de "Editar firma" 
         * en la sesión "dataSelectionForSigns"*/
        
        //----------------------------------------------------------------------
        /*Esta estructura de control evalua si el canvas pertenece al pad de firmas de tecnicos o si es el pad de
         * firmas del cliente*/
        if(technicianCanvas != null){
            /*se utiliza nuestra función callback "generateCanvas" donde necesita 3 argumentos, el canvas en cuestión,
             * el ancho de la ventana del navegador y la altura de la ventana del navegador para generar la funcionalidad
             * para pintar el canvas*/
            generateCanvas(technicianCanvas, window.innerWidth, window.innerHeight);
            /*Este gestor de eventos "resetea" el canvas si el usuario cambia el tamaño de la ventana del navegador volviendo
             * a llamar nuestra función generateCanvas con sus argumentos correspondientes*/
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
        /*Este gestor de evento click utiliza el argumento del evento para acceder al botón que se dio click
         * en la caja de botones (div) de la vista absoluteElems.php*/
        buttonsBox.addEventListener("click", e => {

            if (e.target.id === "cleanButton") {
                
                /*Si se dio click al botón "Limpiar" se activa un operador ternario donde evalua si en este 
                 * momento se encuentra en el pad de firmas del tecnico, en dado caso de que no, entonces
                 * sería el pad de firmas del cliente, en ambos casos se llama a nuestra función callback
                 * generateCanvas para "resetear" o "dejar en blanco" el canvas en cuestión*/
                (technicianCanvas != null) ? 
                generateCanvas(technicianCanvas, window.innerWidth, 
                window.innerHeight) :
                generateCanvas(clientCanvas, window.innerWidth, 
                window.innerHeight);
                
            } else if (e.target.id === "nextButton") {
                /*Si se da click al botón "Seguir" lo primero que se hace es quitarle la clase de estilo 
                 * "hidThis" al fondo de la ventana emergente del pad de firmas de confirmación de guardado 
                 * de firma*/
                backWindow.classList.remove("hidThis");
                /*Después, se declaran constantes que contienen la creación de elementos html, en este caso "cajas"
                 * (div), textBox para añadir un h3 con el mensaje que va a leer el usuario y buttonsBox para darle al
                 * usuario opciones para dar click*/
                const textBox = document.createElement("div");
                const buttonsBox = document.createElement("div");
                /*A los elementos div creados se les añade clases de estilo existentes en el archivo finishingStyles.css*/
                textBox.classList.add("info-window__text-box");
                buttonsBox.classList.add("info-window__selectbuttons-box");
                /*A los elementos div creados se les añade contenido html gracias a la propiedad "innerHTML"*/
                textBox.innerHTML = "<h3>¿Estas seguro de guardar la firma?</h3>";
                buttonsBox.innerHTML = '<button class="selectbuttons-box__button" id="yes">Si</button>' +
                        '<button class="selectbuttons-box__button" id="no">No</button>';
                /*Aqui se le añade en la ventana emergente de confirmación de guardado de firmas los elementos creados 
                 * y configurados*/
                infoWindow.append(textBox);
                infoWindow.append(buttonsBox);
                
                /*Se declara una constante donde se guarda la referencia del elemento creado de la caja de botones de la 
                 * ventana emergente de confirmación de guardado de firmas*/
                const buttonsArea = infoWindow.querySelector(".info-window__selectbuttons-box");
                /*Este gestor de evento click utiliza el argumento del evento para acceder al botón que se dio click
                 * en la caja de botones (div) de la ventana emergente de confirmación de guardado de firmas*/
                buttonsArea.addEventListener("click", e => {
                    if (e.target.id === "yes") {
                        
                        //------------------------------------------------------
                        /*Si se da click al botón "Si" lo primero que se hace es dependiendo si es el pad de firma del 
                         * tecnico o del cliente se verifica si sus respectivos canvas están vacios*/
                        if(technicianCanvas != null){
                            if(isCanvasBlank(technicianCanvas)){
                                /*si nuestra función isCanvasBlank da true, entonces se modifica la ventana de 
                                 * confirmación de guardado de firma, primero se le añade una clase de estilo 
                                 * "okCenter" al elemento de caja de botones*/
                                buttonsBox.classList.add("okCenter");
                                /*se sobrescribe el contenido html de los elementos creados anteriormente 
                                 * (textBox y buttonBox)*/
                                textBox.innerHTML = `<h3>Debes de pintar tu firma antes de seguir</h3>`;
                                buttonsBox.innerHTML = '<button class="selectbuttons-box__button" id="ok">OK</button>';
                                /*se declara una constante donde se añade la referencia del elemento de la caja de botones*/
                                const okArea = infoWindow.querySelector(".info-window__selectbuttons-box");
                                /*Esta gestión de evento utiliza el argumento del evento para identificar si el usuario hizo 
                                 * click en un elemento de la caja de botones*/
                                okArea.addEventListener("click", e =>{
                                    if(e.target.id === "ok"){
                                        /*si se le da click al botón "OK" entonces el fondo de la ventana emergente se le aplica
                                         * la clase de estilo "hidThis", posteriormente, se elimina el contenido html de las constantes
                                         * textBox y buttonsBox, esto para evitar duplicado de elementos html en la pagina*/
                                        backWindow.classList.add("hidThis");
                                        textBox.remove();
                                        buttonsBox.remove();
                                    }
                                });
                            }    
                        }
                        
                        if(clientCanvas != null){
                            if(isCanvasBlank(clientCanvas)){
                                buttonsBox.classList.add("okCenter");  
                                textBox.innerHTML = `<h3>Debes de pintar la firma del cliente antes de seguir</h3>`;
                                buttonsBox.innerHTML = '<button class="selectbuttons-box__button" id="ok">OK</button>';
                                const okArea = infoWindow.querySelector(".info-window__selectbuttons-box");
                                okArea.addEventListener("click", e =>{
                                    if(e.target.id === "ok"){
                                        backWindow.classList.add("hidThis");
                                        textBox.remove();
                                        buttonsBox.remove();
                                    }
                                });
                            }
                        }    
                        //------------------------------------------------------
                        
                        //------------------------------------------------------
                        /*Si el canvas contiene algun pixel de color (si la función isCanvasBlank retorna false), 
                         * entonces se entra en esta estructura de control*/
                        if(technicianCanvas != null){
                            if(!isCanvasBlank(technicianCanvas)){
                                /*se hace otra verificación para validar si isCanvasBlank es falso, entonces se crea una 
                                 * constante donde se guarda lo que devuelve la función propia de javascript toDataURL, 
                                 * esa función devuelve un data URI el cual contiene una representación de la imagen en 
                                 * el formato especificado por el parámetro, en este caso "image/png"*/
                                const dataURL = technicianCanvas.toDataURL('image/png');
                                /*se declara la constante imageBlob el cual contiene lo que devuelve nuestra función 
                                 * callback dataURLtoBlob y le pasamos como argumento la constante dataURL*/
                                const imageBlob = dataURLtoBlob(dataURL);
                                /*aqui es donde se utiliza nuestro objeto de la clase FormData, el append de esta clase 
                                 * puede tener 3 argumentos (el nombre del campo, el archivo y el nombre del archivo), en este caso
                                 * el nombre del campo es "techSign", el archivo a enviar es la imagen blob del canvas (lo que 
                                 * devuelve nuestra función dataURLtoBlob) y el nombre del archivo*/
                                formData.append(`${(binnId != null) ? "techSign" : "newTechSign"}`, imageBlob, `userid_${userId}_${userName}_${userSurname}_Sign.png`);
                                console.log(formData);
                           }
                        }else if(clientCanvas != null){
                            if(!isCanvasBlank(clientCanvas)){   
                                const dataURL = clientCanvas.toDataURL('image/png');
                                const imageBlob = dataURLtoBlob(dataURL);
                                formData.append("cliSign", imageBlob, `bitacoraid_${binnId}_${cliName}_${commName}_Sign.png`);
                            }
                        } 
                        //------------------------------------------------------
                        
                        if(formData.has("techSign") || formData.has("cliSign") || formData.has("newTechSign")){
                            /*Este if verifica si el objeto de FormData tiene el campo "techSign" o "cliSign", en dado caso 
                             * que si tenga alguno de los dos, entonces se hará una comunicación ascincronica entre JavaScript y PHP con
                             * fetch, en fetch se coloca dos argumentos, la url de alguna pagina donde proporcione un servicio de envio de datos,
                             * ya sea una api restful, o en este caso, la url donde PHP recepciona los datos que envia el cliente, el segundo
                             * argumento de fetch es un JSON donde se especifica los metodos y cabeceras HTTP, tambien en ese JSON se coloca los datos
                             * que se van a enviar, en este caso, el objeto de FormData, PHP lo va interpretar como un array superglobal llamado $_FILES*/
                            fetch(BASE_URL + "finishing/", {
                                method: "POST",
                                headers: {
                                    "X-Requested-With": "XMLHttpRequest"
                                },
                                body: formData
                            })
                            /*aqui se hace una promesa donde se espera una respuesta con respecto a lo que se envio en el JSON, php enviará un texto*/
                            .then(res => res.text())
                            .then(txt => {
                                /*Si PHP envió un texto, entonces la recepción de datos de la imagen y su posterior guardado en la carpeta de esta 
                                 * aplicación web fue un éxito, lo que se hace aqui es modificar la ventana emergente de confirmación de guardado de firma
                                 * y se le agrega un mensaje al usuario para notificar que la firma se guardó en la aplicación, primero se le coloca al elemento
                                 * de caja de botones la clase de estilo "okCenter"*/                                 
                                buttonsBox.classList.add("okCenter");
                                /*en la constante textBox se le modifica su contenido html con el texto que envió PHP*/
                                textBox.innerHTML = `<h3>${txt}</h3>`;
                                /*en la contante buttonsBox se le modifica su contenido html dependiendo de la evaluación del operador ternario, si el pad de
                                 * firma es del tecnico, entonces el elemento html de link enviará al usuario al pad de firma del cliente, en dado caso de que sea
                                 * un pad distinto al del tecnico quiere decir entonces que es el pad del cliente, por lo que, la parte falsa del ternario
                                 * tomará en cuenta el elemento html de link el cual enviará al usuario al controlador "finishbinnacle" donde pondrá fin al seguimiento
                                 * de la bitácora*/
                                /*Se genera una variable evaluando si se trata de firmas para una bitácora, o una actualización de firma del técnico, esa variable 
                                 * se concatenará con el string del resultado verdadero del ternario del innerHTML de buttonsBox*/
                                let determinateMethod = (binnId != null) ? '<a class="selectbuttons-box__button ok" id="ok"' +
                                    ' href="'+BASE_URL+'finishing/?controller=form&action=clientSign">OK</a>' : '<a class="selectbuttons-box__button ok" id="ok"' +
                                    ' href="'+BASE_URL+'home/?homeController=user&homeAction=editSign">OK</a>';
                                buttonsBox.innerHTML = (technicianCanvas != null) ? determinateMethod :
                                '<a class="selectbuttons-box__button ok" id="ok"' +
                                    ' href="'+BASE_URL+'finishing/?controller=form&action=finishbinnacle">OK</a>';
                                /*se crea una constante donde contiene los elementos html de la caja de botones y el botón "OK"*/    
                                const okArea = infoWindow.querySelector(".info-window__selectbuttons-box");
                                /*esta gestion de evento utiliza el argumento del evento para acceder al elemento html que el usuario dio click en la caja de botones*/
                                okArea.addEventListener("click", e =>{
                                    
                                    if(e.target.id === "ok"){
                                        /*al dar click al botón "OK" se activa primero este operador ternario, si estamos en el pad de firmas del tecnico, entonces se
                                         * borra el campo "techSign" del objeto de la clase FormData, en dado caso de que no estemos en el pad de firmas del tecnico, 
                                         * entonces quiere decir que estamos en el pad del cliente, entonces se elimina el campo "cliSign" del objeto de la clase
                                         * FormData*/
                                        (technicianCanvas != null) ? formData.delete(`${(binnId != null) ? "techSign" : "newTechSign"}`) :
                                                formData.delete("cliSign");
                                        /*Después, se le agrega al elemento del fondo de la ventana emergente la clase de estilo "hidThis" para ocultar la ventana
                                         * emergente en la vista del usuario*/
                                        backWindow.classList.add("hidThis");
                                        /*Finalmente, se eliminan los elementos html que contienen las constantes textBox y buttonBox para evitar duplicados
                                         * innecesarios en el documento html*/
                                        textBox.remove();
                                        buttonsBox.remove();
                                    }
                                });
                            })
                            .catch(error => {
                                /*Se entra a este catch en dado caso de que la promesa no se pudo cumplir, primero, se modifica la caja de botones con la clase
                                 * de estilo "okCenter" ya que solo va a haber un botón en el contenedor*/
                                buttonsBox.classList.add("okCenter");
                                /*se modifican los elementos html que contienen las constantes textBox y buttonsBox, el primero con el mensaje del error y el
                                 * segundo se añade un elemento html button*/
                                textBox.innerHTML = `<h3>${error}</h3>`;
                                buttonsBox.innerHTML = '<button class="selectbuttons-box__button" id="ok">OK</button>';
                                /*Se declara una constante donde va a contener la caja de botones del contexto en el que estamos*/
                                const okArea = infoWindow.querySelector(".info-window__selectbuttons-box");
                                /*esta gestión de evento utiliza el argumento del evento para identificar el elemento html que el usuario dio click*/
                                okArea.addEventListener("click", e =>{
                                    if(e.target.id === "ok"){
                                        /*al dar click al botón "OK" lo primero que se activa es este operador ternario, si estamos en el pad de firmas del tecnico, entonces se
                                         * borra el campo "techSign" del objeto de la clase FormData, en dado caso de que no estemos en el pad de firmas del tecnico, 
                                         * entonces quiere decir que estamos en el pad del cliente, entonces se elimina el campo "cliSign" del objeto de la clase
                                         * FormData*/
                                        (technicianCanvas != null) ? formData.delete(`${(binnId != null) ? "techSign" : "newTechSign"}`) :
                                                formData.delete("cliSign");
                                        /*Después, se le agrega al elemento del fondo de la ventana emergente la clase de estilo "hidThis" para ocultar la ventana
                                         * emergente en la vista del usuario*/
                                        backWindow.classList.add("hidThis");
                                        /*Finalmente, se eliminan los elementos html que contienen las constantes textBox y buttonBox para evitar duplicados
                                         * innecesarios en el documento html*/
                                        textBox.remove();
                                        buttonsBox.remove();
                                    }
                                });
                            });
                        }    

                    }else if(e.target.id === "no"){
                        /*Si se dio click al botón "No" en la ventana emergente de confirmación de guardado de firma, entonces lo primero que se hace es ocultar
                         * la ventana emergente de la vista del cliente con la clase de estilo "hidThis"*/
                        backWindow.classList.add("hidThis");
                        /*Finalmente, se eliminan los elementos html que contienen las constantes textBox y buttonBox para evitar duplicados
                         * innecesarios en el documento html*/
                        textBox.remove();
                        buttonsBox.remove();
                    }
                });
            }else if(e.target.id === "cancelButton"){
                /*si se da click al botón de cancelar en cualquier pad de firma, entonces lo primero que se hace es mostrar la ventana emergente 
                 * al usuario, en este caso le quitamos la clase "hidThis" al fondo de la ventana emergente*/
                backWindow.classList.remove("hidThis");
                /*Se crea elementos div los cuales estarán contenidas en las constantes textBox y buttonsBox*/
                const textBox = document.createElement("div");
                const buttonsBox = document.createElement("div");
                /*a cada elemento div se le coloca respectivamente sus clases de estilo cuyo bloques estan contenidos en el archivo finishingStyles.css*/
                textBox.classList.add("info-window__text-box");
                buttonsBox.classList.add("info-window__selectbuttons-box");
                /*después se le añade a cada div elementos html gracias a innerHTML, textBox se le añade un texto que podrá ser leído por el usuario
                 * y buttonsBox el cual tendrá un link que enviará al usuario a la vista de conformidad de actividades o a la vista de editar firma y 
                 * un botón de negativa "No"*/
                textBox.innerHTML = (binnId != null) ? "<h3>¿quieres regresar a la parte de conformidad de actividades?</h3>" : 
                        '<h3>¿quieres regresar al apartado de "Editar firmas"?</h3>';
                buttonsBox.innerHTML = (binnId != null) ? '<a class="selectbuttons-box__button ok" id="ok"' +
                                ' href="'+BASE_URL+'finishing/?controller=form&action=index&id='+ binnId +'">Si</a>' +
                        '<button class="selectbuttons-box__button" id="no">No</button>' :
                        '<a class="selectbuttons-box__button ok" id="ok"' +
                                ' href="'+BASE_URL+'home/?homeController=user&homeAction=editSign">Si</a>' +
                        '<button class="selectbuttons-box__button" id="no">No</button>';
                /*todo el html que generamos y que están contenidos en sus respectivas constantes se añadirán al elemento de la ventana emergente el cual
                 * está contenida en la constante infoWindow*/
                infoWindow.append(textBox);
                infoWindow.append(buttonsBox);
                /*se declara una constante el cual contendrá la caja de botones que creamos en este contexto con la clase de estilo del elemento que contiene
                 * la constante buttonsBox*/
                const buttonsArea = document.querySelector(".info-window__selectbuttons-box");
                /*este gestor de evento utiliza el argumento del evento para identificar que elementos html hizo click el usuario*/
                buttonsArea.addEventListener("click", e =>{
                   if(e.target.id === "ok"){
                       /*si se dio click al botón "OK" entonces se oculta la ventana emergente aplicando al fondo de esta la clase de estilo "hidThis"*/
                       backWindow.classList.add("hidThis");
                       /*Finalmente, se eliminan los elementos html que contienen las constantes textBox y buttonBox para evitar duplicados
                         * innecesarios en el documento html*/
                       textBox.remove();
                       buttonsBox.remove();
                   }else if(e.target.id === "no"){
                       /*si se dio click al botón "No" entonces se oculta la ventana emergente aplicando al fondo de esta la clase de estilo "hidThis"*/
                       backWindow.classList.add("hidThis");
                       /*Finalmente, se eliminan los elementos html que contienen las constantes textBox y buttonBox para evitar duplicados
                         * innecesarios en el documento html*/
                       textBox.remove();
                       buttonsBox.remove();
                   } 
                });
            }
            
        });
        //----------------------------------------------------------------------
    }       
    
});
//---------------------------------------------manejo de eventos---------------------------------------------------------