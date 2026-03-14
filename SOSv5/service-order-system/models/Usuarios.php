<?php

/*Esta clase representa a la tabla Usuarios de la base de datos sosDB, todas sus propiedades privadas representan los campos de esa entidad (a excepción de $db)*/
    class Usuarios{
        
        private $db, $id, $nombre, $apellidos, $alias, $contrasena, $privilegio, 
                $firma, $visibilidad;
        /*se define el constructor cuyos parametros son los campos de la tabla Usuarios, tienen el mismo orden que se tiene en la base de datos*/
        public function __construct($nombre = null, $apellidos = null, 
                $alias = null, $contrasena = null, $privilegio = null,
                $firma = null, $visibilidad = null) {
            /*Se inicializa la propiedad $db con el método estático getConnection, este método devuelve un objeto de la clase PDO para hacer la conexión con 
             * sql server para poder hacer peticiones CRUD*/
            $this->db = DataBaseMssql::getConnection();
            $this->nombre = $nombre;
            $this->apellidos = $apellidos;
            $this->alias = $alias;
            $this->contrasena = (!empty($contrasena)) ? password_hash($contrasena, 
                    PASSWORD_BCRYPT, ['cost' => 4]) : null;
            $this->privilegio = $privilegio;
            $this->firma = $firma;
            $this->visibilidad = $visibilidad;
        }
        
        /*los controladores (y también la clase Utils) requieren añadir a las instancias de esta clase ciertos valores a las propiedades privadas, por lo que 
         * se crearon métodos setters que estos controladores pueden usar*/
        public function setAlias($alias){
            $this->alias = $alias;
        }

        public function setContrasena($contrasena){
            $this->contrasena = $contrasena;
        }
        
        public function setAndEncryptContrasena($contrasena){
            $this->contrasena = password_hash($contrasena, 
                    PASSWORD_BCRYPT, ['cost' => 4]);
        }
        
        public function setFirma($firma){
            $this->firma = $firma;
        }
        
        public function setId($id){
            $this->id = $id;
        }
        
        public function setVisibilidad($visibilidad){
            $this->visibilidad = $visibilidad;
        }
        
        /*A partir de aqui se encuentran los métodos publicos los cuales utilizan los controladores (y tambien la clase Utils) cuando crean un objeto de esta clase, 
        * estos métodos contienen sentencias sql, es necesario utilizar la propiedad $db para acceder al metodo prepare de PDO, el objeto de esa conexión usualmente 
        * se guarda en la variable $stmt, el método prepare tiene la opción de añadir parametros personalizados en un string sql (el formato para crear estos parametros 
        * es el siguiente: ":nombreParametro"), parametros que posteriormente se utilizan como indices en un array asociativo que necesita el método execute para ejecutar 
        * la sentencia sql a sql server (a esos paramentros en este caso se tienen que escribir sin los dos puntos ":"), cada indice tendrá como valor las respectivas 
        * propiedades privadas de esta clase (dependiendo de lo que se requiera en la sentencia sql), haciendo esto estamos "escapando" los datos que se guardan en las 
        * propiedades privadas evitando inyecciones sql e incrustración de scripts; todos los métodos de esta clase usan parametros personalizados en los string sql; 
        * en peticiones de creación y actualización solo se utiliza execute, sin embargo, en peticiones de lectura antes del return se usa execute y en 
        * el return el metodo fetch en el caso de que se requiera los campos de un registro o fetchAll en el caso de que se requiera la información de más de un registro*/
        public function getUsers(){
            $sql = "SELECT Id, Nombre, Apellidos, Alias From "
                    ."Usuarios WHERE Visibilidad = 'ENABLED';";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        
        public function getUser(){
            $sql = "SELECT Id, Nombre, Apellidos, Alias, Privilegio, Firma From "
                    ."Usuarios WHERE Id = :id;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $this->id]);
            return $stmt->fetch();
        }
                
        public function insertSignature(){
            $sql = "UPDATE Usuarios SET Firma = :frm WHERE Id = :id;";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'frm' => $this->firma,
                'id'  => $this->id
            ]);
        }
        
        public function updateVisibilityById(){
            $sql = "UPDATE Usuarios SET Visibilidad = :vi WHERE Id = :id;";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'vi' => $this->visibilidad,
                'id' => $this->id
            ]);
        }
        
        public function updatePassword(){
            $sql = "UPDATE Usuarios SET Contrasena = :pwd WHERE Id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'pwd' => $this->contrasena,
                'id'  => $this->id
            ]);
        }
        
        public function insertUser(){
            
            $sql = "INSERT INTO Usuarios VALUES(:nre, :ape, :al, :pwd, :prv, "
                    . ":sgn, 'ENABLED');";
            $save = $this->db->prepare($sql);
            return $save->execute(array(
                        'nre' => $this->nombre,
                        'ape' => $this->apellidos,
                        'al' => $this->alias,
                        'pwd' => $this->contrasena,
                        'prv' => $this->privilegio,
                        'sgn' => $this->firma
            ));
            
        }
        
        
        public function login(){
            $result = false;
            $sql = "SELECT Contrasena FROM Usuarios WHERE Alias = :al AND Visibilidad = 'ENABLED';";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['al' => $this->alias]);
            $usuario = $stmt->fetch();
            
            if(!empty($usuario)){
                $verify = password_verify($this->contrasena, $usuario["Contrasena"]);
                //$verify = ($this->contrasena === $usuario["Contrasena"]) ? true : false;
                if($verify){
                    $sql = "SELECT Id, Alias, Nombre, Apellidos, Privilegio, Firma FROM Usuarios WHERE Alias = :al;";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(['al' => $this->alias]);
                    $usuario = $stmt->fetch();
                    $result = $usuario;
                }
            }
            return $result;
        }
        
        public function AdminPwdConfirmation(){
            $result = false;
            $slq = "SELECT Contrasena FROM Usuarios WHERE Alias = :al AND Visibilidad = 'ENABLED';";
            $stmt = $this->db->prepare($slq);
            $stmt->execute(['al' => $this->alias]);
            $usuario = $stmt->fetch();
            
            if(!empty($usuario)){
                $verify = password_verify($this->contrasena, $usuario["Contrasena"]);
                //$verify = ($this->contrasena === $usuario["Contrasena"]) ? true : false;
                if($verify){
                    $result = true;
                }
            }
            return $result;
        }
        
        
        public function getUserSign(){
            $sql = "SELECT Firma FROM Usuarios WHERE Id = :id;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $this->id]);
            return $stmt->fetchObject()->Firma;
        }
    }