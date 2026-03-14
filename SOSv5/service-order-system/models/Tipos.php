<?php

/*Esta clase representa a la tabla Tipos de la base de datos sosDB, todas sus propiedades privadas representan los campos de esa entidad (a excepción de $db)*/
class Tipos{
    
    private $db, $id, $tipo, $visibilidad;
    /*se define el constructor cuyos parametros son los campos de la tabla Tipos, tienen el mismo orden que se tiene en la base de datos*/
    public function __construct($tipo = null, $visibilidad = null) {
        /*Se inicializa la propiedad $db con el método estático getConnection, este método devuelve un objeto de la clase PDO para hacer la conexión con 
         * sql server para poder hacer peticiones CRUD*/
        $this->db = DataBaseMssql::getConnection();
        $this->tipo = $tipo;
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
        * el return el metodo fetch en el caso de que se requiera los campos de un registro o fetchAll en el caso de que se requiera la información de más de un registro;*/
    public function insertType(){
        $sql = "INSERT INTO Tipos VALUES( :tp, 'ENABLED' );";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(["tp" => $this->tipo]);
    }
    
    public function updateVisibilityById(){
        $sql = "UPDATE Tipos SET Visibilidad = :vi WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'vi' => $this->visibilidad,
            'id' => $this->id
        ]);
    }
    
    public function updateTypeById(){
        $sql = "UPDATE Tipos SET Tipo = :typ WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'typ' => $this->tipo,
            'id'  => $this->id
        ]);
    }
    
    public function getTypeById(){
        $sql = "SELECT * FROM Tipos WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["id" => $this->id]);
        return $stmt->fetch();
    }
    
    public function getTypes(){
        $sql = "SELECT * FROM Tipos;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getTypeForSelect(){
        $sql = "SELECT * FROM Tipos WHERE Visibilidad = 'ENABLED';";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}