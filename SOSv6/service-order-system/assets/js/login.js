/*Este documento contempla la funcionalidad de la vista del login*/
document.addEventListener("DOMContentLoaded", () => {
    //----------------------------------------------------
    /*elementos html propios de la vista del login*/
    const pwd = document.querySelector("#pwd");
    const icon = document.querySelector("#icon");
    const errorBox = document.querySelector(".error-box");
    //----------------------------------------------------
    
    if(errorBox != null){
        /*si el controlador ErrorController genera su vista, entonces el elemento 
         * html que contiene la constante errorBox no tiene valor nulo, entonces,
         * para que el texto del error se vea bien en la vista del login se le
         * añade a este elemento la clase de estilo "error-absolute" contenida en 
         * el archivo generalStyles.css*/
        errorBox.classList.add("error-absolute");
    }
    
    if(pwd != null && icon != null){
        /*si el elemento input pwd y el icono estan presentes en la vista del
         * login entonces se activará esta pequeña logica*/
        icon.addEventListener("click", () =>{
            /*al dar click al icono, lo primero que se hace es posicionar al
             * usuario dentro del elemento input de password gracias a focus()*/
            pwd.focus();
            if(pwd.type === "password"){
                /*Después, si al dar click al icono el elemento input tiene tipo password entonces se modifica el icono
                 * a fa-eye de la fuente fontawesome-free-6.7.2-web y se cambia el tipo del input a texto, para
                 * que el usuario pueda ver su contraseña*/
                icon.innerHTML = '<i class="fa-solid fa-eye"></i>';
                pwd.type = "text";
            }else{
                /*Después, si al dar click al icono el tipo del elemento input no es exactamente igual a "password" entonces
                 * se modifica el icono por fa-eye-slash de la fuente fontawesome-free-6.7.2-web y se cambia el tipo del input a
                 * password para ocultar la contraseña del usuario*/
                icon.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
                pwd.type = "password";
            }
        });
    }
});