<?php
    
/*Esta clase representa a la tabla Empresas de la base de datos sosDB, todas sus propiedades privadas representan los campos de esa entidad (a excepción de $db)*/
    class Empresas{
        
        private $db, $id, $nombre_comercial, $razon_social, $calle_numero, $entre_calles,
                $dirigirse_con, $telefonos, $horario, $atencion, $colonia,
                $localidad, $email, $visibilidad;
        
        /*se define el constructor cuyos parametros son los campos de la tabla Empresas, tienen el mismo orden que se tiene en la base de datos*/
        public function __construct( $nombre_comercial = null, $razon_social = null,
                $calle_numero = null, $entre_calles = null, $dirigirse_con = null,
                $telefonos = null, $horario = null, $atencion = null, $colonia = null,
                $localidad = null, $email = null, $visibilidad = null) {
            /*Se inicializa la propiedad $db con el método estático getConnection, este método devuelve un objeto de la clase PDO para hacer la conexión con 
             * sql server para poder hacer peticiones CRUD*/
            $this->db = DataBaseMssql::getConnection();
            $this->nombre_comercial = $nombre_comercial;
            $this->razon_social = $razon_social;
            $this->calle_numero = $calle_numero;
            $this->entre_calles = $entre_calles;
            $this->dirigirse_con = $dirigirse_con;
            $this->telefonos = $telefonos;
            $this->horario = $horario;
            $this->atencion = $atencion;
            $this->colonia = $colonia;
            $this->localidad = $localidad;
            $this->email = $email;
            $this->visibilidad = $visibilidad;
        }
        
        /*los controladores (y también la clase Utils) requieren añadir a las instancias de esta clase ciertos valores a las propiedades privadas, por lo que 
         * se creó un método setter que estos controladores pueden usar*/
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
        * el return el metodo fetch en el caso de que se requiera los campos de un registro o fetchAll en el caso de que se requiera la información de más de un registro; 
        * el método insertEnterprise es el unico que utiliza el método lastInsertId el cual regresa el id del registro creado, esto es util cuando se hace registros en 
        * cascada (donde se involucra más de una entidad)*/
        public function insertEnterprise(){
            $sql = "INSERT INTO Empresas VALUES( :nc, "
                    .":rs, :cn, :ec, :dc, :tl, :ho, :at, :co, :lo, :em, 'ENABLED' );";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                "nc" => $this->nombre_comercial,
                "rs" => $this->razon_social, 
                "cn" => $this->calle_numero, 
                "ec" => $this->entre_calles, 
                "dc" => $this->dirigirse_con, 
                "tl" => $this->telefonos, 
                "ho" => $this->horario, 
                "at" => $this->atencion, 
                "co" => $this->colonia, 
                "lo" => $this->localidad, 
                "em" => $this->email
            ]);
            
            return $this->db->lastInsertId();
        }
        
        public function updateVisibilityById(){
            $sql = "UPDATE Empresas SET Visibilidad = :vi WHERE Id = :id;";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'vi' => $this->visibilidad,
                'id' => $this->id
            ]);
        }
        
        public function getEnterpriseById(){
            $sql = "SELECT * FROM Empresas WHERE Id = :id;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(["id" => $this->id]);
            return $stmt->fetch();
        }
        
        public function getEnterprisesForSelect(){
            $sql = "SELECT Id, Nombre_comercial, Razon_social FROM Empresas WHERE Visibilidad = 'ENABLED';";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        
        public function getEnterprisesForEditSelect(){
            $sql = "SELECT Id, Nombre_comercial, Razon_social FROM Empresas;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        
        public function updateEnterpriseInfo(){
            $sql = "UPDATE Empresas SET Nombre_comercial = :nco, "
                    ."Razon_social = :rso, Calle_numero = :cnu, "
                    ."Entre_calles = :eca, Dirigirse_con = :dco, "
                    ."Telefonos = :tel, Horario = :hro, Atencion = :aon, "
                    ."Colonia = :col, Localidad = :lca, Email = :eml "
                    ."WHERE Id = :eid;";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                "nco" => $this->nombre_comercial,
                "rso" => $this->razon_social,
                "cnu" => $this->calle_numero,
                "eca" => $this->entre_calles,
                "dco" => $this->dirigirse_con,
                "tel" => $this->telefonos,
                "hro" => $this->horario,
                "aon" => $this->atencion,
                "col" => $this->colonia,
                "lca" => $this->localidad,
                "eml" => $this->email,
                "eid" => $this->id
            ]);
        }
        
    }