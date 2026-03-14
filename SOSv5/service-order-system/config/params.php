<?php
    /*En este archivo se definen las "variables de entorno", estos son constantes que se 
     * usan en casi todos los archivos php de este proyecto, el primero "base_url" 
     * contiene la ruta raiz del proyecto, en dado caso de que se cambie el host hay
     * que cambiar la ip o el host asignado y despues anotar la raiz del proyecto*/
    define("base_url", getenv("SOSV5_URL"));
    /*Las siguientes constantes se utilizan en el método estático defaultUserPage de la 
     * clase Utils, método estitico que se invoca en el archivo index de la carpeta home*/
    define("default_userController", "UserController");
    define("default_action", "index");
    