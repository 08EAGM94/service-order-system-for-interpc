<?php

/*Esta clase representa a la tabla Clientes de la base de datos sosDB, todas sus propiedades privadas representan los campos de esa entidad (a excepción de $db)*/
class Contactos{
    
    private $db, $id, $empresa_id, $nombre_completo, $visibilidad;
    
    /*se define el constructor cuyos parametros son los campos de la tabla Clientes, tienen el mismo orden que se tiene en la base de datos*/
    public function __construct( $empresa_id = null, $nombre_completo = null,
            $visibilidad = null) {
        /*Se inicializa la propiedad $db con el método estático getConnection, este método devuelve un objeto de la clase PDO para hacer la conexión con 
         * sql server para poder hacer peticiones CRUD*/
        $this->db = DataBaseMssql::getConnection();
        $this->empresa_id = $empresa_id;
        $this->nombre_completo = $nombre_completo;
        $this->visibilidad = $visibilidad;        
    }
    
    /*los controladores (y también la clase Utils) requieren añadir a las instancias de esta clase ciertos valores a las propiedades privadas, por lo que 
     * se crearon métodos setters que estos controladores pueden usar*/
    public function setId($id){
        $this->id = $id;
    }
    
    public function setEmpresaId($empresaId){
        $this->empresa_id = $empresaId;
    }
    
    public function setNombre_completo($nombre_completo){
        $this->nombre_completo = $nombre_completo;
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
     * el return el metodo fetch en el caso de que se requiera los campos de un registro o fetchAll en el caso de que se requiera la información de más de un registro, 
     * algunas veces se usa el método fetchObject cuando solo se requiere obtener el valor de un campo en especifico*/
    public function insertContact(){
        $sql = "INSERT INTO Contactos VALUES( :ei, :nc, 'ENABLED' );";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            "ei" => $this->empresa_id, 
            "nc" => $this->nombre_completo
        ]);
    }
    
    public function getContactById(){
        $sql = "SELECT c.Id, c.Nombre_completo, e.Nombre_comercial, e.Razon_social, " 
        ."e.Calle_numero, e.Entre_calles, e.Dirigirse_con, e.Telefonos, e.Horario, " 
        ."e.Atencion, e.Colonia, e.Localidad, e.Email FROM Contactos c INNER JOIN "
        ."Empresas e ON c.Empresa_id = e.Id WHERE c.Id = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["id" => $this->id]);
        return $stmt->fetch();
    }
    
    
    public function getContactsForSelect(){
        $sql = "SELECT Id, Nombre_completo FROM Contactos WHERE Visible = 'ENABLED';";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function updateVisibilityById(){
        $sql = "UPDATE Contactos SET Visibilidad = :vi WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'vi' => $this->visibilidad,
            'id' => $this->id
        ]);
    }
    
    public function getContactCountByEnterprise(){
        $sql = "SELECT COUNT(Id) AS 'total' FROM Contactos WHERE Empresa_id = "
                .":eid;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['eid' => $this->empresa_id]);
        return $stmt->fetchObject()->total;
    }
    
    public function getContactsByEnterForSelect(){
        $sql = "SELECT * FROM Contactos WHERE Empresa_id = :eid AND Visibilidad = 'ENABLED';";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['eid' => $this->empresa_id]);
        return $stmt->fetchAll();
    }
    
    public function getContactsByEnterprise(){
        $sql = "SELECT * FROM Contactos WHERE Empresa_id = :eid;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['eid' => $this->empresa_id]);
        return $stmt->fetchAll();
    }
    
    public function updateContactNameById(){
        $sql = "UPDATE Contactos SET Nombre_completo = :nc WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nc' => $this->nombre_completo,
            'id' => $this->id
        ]);
    } 
    
}