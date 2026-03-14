<?php

/*Esta clase representa a la tabla Equipos de la base de datos sosDB, todas sus propiedades privadas representan los campos de esa entidad (a excepción de $db)*/
class Equipos{
    
    private $db, $id, $empresa_id, $tipo_id, $marca, $modelo, $numero_serie,
            $numero_inventario, $visibilidad;
    /*se define el constructor cuyos parametros son los campos de la tabla Equipos, tienen el mismo orden que se tiene en la base de datos*/
    public function __construct($empresa_id = null, $tipo_id = null, $marca = null,
            $modelo = null, $numero_serie = null, $numero_inventario = null, $visibilidad = null) {
        /*Se inicializa la propiedad $db con el método estático getConnection, este método devuelve un objeto de la clase PDO para hacer la conexión con 
         * sql server para poder hacer peticiones CRUD*/
        $this->db = DataBaseMssql::getConnection();
        $this->empresa_id = $empresa_id;
        $this->tipo_id = $tipo_id;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->numero_serie = $numero_serie;
        $this->numero_inventario = $numero_inventario;
        $this->visibilidad = $visibilidad;
    }
    
    /*los controladores (y también la clase Utils) requieren añadir a las instancias de esta clase ciertos valores a las propiedades privadas, por lo que 
     * se crearon métodos setters que estos controladores pueden usar (getId es el unico getter)*/
    public function setId($id){
        $this->id = $id;
    }
    
    public function setEmpresa_id($empresa_id){
        $this->empresa_id = $empresa_id;
    }
    
    public function setVisibilidad($visibilidad){
        $this->visibilidad = $visibilidad;
    }
    
    public function getId() {
        return $this->id;
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
     * algunas veces se usa el método fetchObject cuando solo se requiere obtener el valor de un campo en especifico; el método insertDevice es el unico que utiliza 
     * el método lastInsertId el cual regresa el id del registro creado, esto es util cuando se hace registros en cascada (donde se involucra más de una entidad)*/
    public function insertDevice(){
        $sql = "INSERT INTO Equipos VALUES( :ei, :ti, :ma, :mo, :ns, :ni, 'ENABLED' );";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            "ei" => $this->empresa_id, 
            "ti" => $this->tipo_id, 
            "ma" => $this->marca, 
            "mo" => $this->modelo, 
            "ns" => $this->numero_serie, 
            "ni" => $this->numero_inventario
        ]);
    }
    
    public function updateVisibiliyById(){
        $sql = "UPDATE Equipos SET Visibilidad = :vi WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'vi' => $this->visibilidad,
            'id' => $this->id
        ]);
    }
    
    public function getDeviceById(){
        $sql = "SELECT COUNT(b.Id) AS 'total', e.Id, t.Tipo, e.Marca, e.Modelo, "
                ."e.Numero_serie, e.Numero_inventario FROM Equipos e LEFT JOIN "
                ."Bitacoras b ON b.Equipo_id = e.Id INNER JOIN Tipos t ON "
                ."e.Tipo_id = t.Id WHERE e.Id = :id GROUP BY e.Id, t.Tipo, e.Marca, "
                ."e.Modelo, e.Numero_serie, e.Numero_inventario;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $this->id]);
        return $stmt->fetch();
    }
    
    public function getDevicesByEnterprise(){
        $sql = "SELECT e.Id, e.Tipo_id, t.Tipo, e.Marca, e.Modelo, e.Numero_serie, "
                ."e.Numero_inventario, e.Visibilidad FROM Equipos e INNER JOIN Tipos t ON "
                ."e.Tipo_id = t.Id WHERE e.Empresa_id = :eid;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['eid' => $this->empresa_id]);
        return $stmt->fetchAll();
    }
    
    public function getDevicesForSelectByEnterpriseId(){
        $sql = "SELECT Id, Marca, Numero_serie FROM Equipos WHERE Empresa_id = :ei AND Visibilidad = 'ENABLED';";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['ei' => $this->empresa_id]);
        return $stmt->fetchAll();
    }
    
    public function getDeviceCountByEnterprise(){
        $sql = "SELECT COUNT(Id) AS 'total' FROM Equipos WHERE Empresa_id = "
                .":eid;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['eid' => $this->empresa_id]);
        return $stmt->fetchObject()->total;
    }
    
    public function updateDeviceInfoById(){
        $sql = "UPDATE Equipos SET Marca = :mca, Modelo = :mlo, "
                ."Numero_serie = :nus, Numero_inventario = :nui WHERE Id = :eid;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'mca' => $this->marca,
            'mlo' => $this->modelo,
            'nus' => $this->numero_serie,
            'nui' => $this->numero_inventario,
            'eid' => $this->id
        ]);
    }
    
    public function deleteDevicesByEnterprise(){
        $sql = "DELETE FROM Equipos WHERE Empresa_id = :eid;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['eid' => $this->empresa_id]);
    }
    
    public function deleteDeviceById(){
        $sql = "DELETE FROM Equipos WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $this->id]);
    }
}