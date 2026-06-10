<?php
    /*la clase DataBaseMssql tiene un método estiatico (getConnection) que los modelos (clases 
     * que representan a las entidades que hay en la base de datos de esta aplicación web)
     * Utilizan para conectarse a la base de datos*/
    class DataBaseMssql{
        public static function getConnection(){
            /*para la conexión a la base de datos se necesita instanciar un objeto de la clase 
             * PDO el cual requiere 3 argumentos, el primero se coloca el nombre del servidor 
             * de la base de datos (que en este caso es una instancia de SQL server) y el 
             * nombre de la base de datos que se va a conectar (en este caso la base de datos
             * de esta aplicación web llamada "sosDB"), el segundo argumento es el nombre del 
             * usuario de la base de datos (en este caso, un usuario generico de la instancia
             * de sql server llamado "sa") y el ultimo argumento es la contraseña del usuario*/
            $db = new PDO("sqlsrv:server=".getenv("SQL_SERVER").";"
                    ."Database=".getenv("SQL_DATABASE").";Encrypt=yes;TrustServerCertificate=true", getenv("SQL_USER"), getenv("SQL_PASSWORD"));
            /*Aqui se configura la conexión a la base de datos para que en cada petición, PHP 
             * obtenga los registros en forma de arrays asociativos*/
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            /*Finalmente, se retorna la instancia de la clase PDO ya configurada, por lo general
             * esta instancia se guarda en el atributo $db de los modelos*/
            return $db;
        }
    }