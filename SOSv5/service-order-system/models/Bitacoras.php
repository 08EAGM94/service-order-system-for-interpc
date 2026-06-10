<?php

/*Esta clase representa a la tabla Bitacoras de la base de datos sosDB, todas sus propiedades privadas representan los campos de esa entidad (a excepción de $db 
 * y $external_query)*/
class Bitacoras{
    
    private $db, $id, $external_query, $empresa_id, $usuario_id, $cliente_id, $servicio, $equipo_id, $monto,
            $Actividades_realizadas, $observaciones, $inicio, $fin, $estatus, $firma_cliente ,$visibilidad;
    
    /*se define el constructor cuyos parametros son los campos de la tabla Bitacoras, tienen el mismo orden que se tiene en la base de datos*/
    public function __construct($usuario_id = null, $cliente_id = null,
            $servicio = null, $equipo_id = null, $monto = null,
            $Actividades_realizadas = null, $observaciones = null, $inicio = null,
            $fin = null, $estatus = null, $firma_cliente = null, $visibilidad = null) {
        /*Se inicializa la propiedad $db con el método estático getConnection, este método devuelve un objeto de la clase PDO para hacer la conexión con 
         * sql server para poder hacer peticiones CRUD*/
        $this->db = DataBaseMssql::getConnection();
        $this->usuario_id = $usuario_id;
        $this->cliente_id = $cliente_id;
        $this->servicio = $servicio;
        $this->equipo_id = $equipo_id;
        $this->monto = $monto;
        $this->Actividades_realizadas = $Actividades_realizadas;
        $this->observaciones = $observaciones;
        $this->inicio = $inicio;
        $this->fin = $fin;
        $this->estatus = $estatus;
        $this->firma_cliente = $firma_cliente;
        $this->visibilidad = $visibilidad;
    }
    
    /*los controladores (y también la clase Utils) requieren añadir a las instancias de esta clase ciertos valores a las propiedades privadas, por lo que 
     * se crearon métodos setters que estos controladores pueden usar (getId es el unico getter)*/
    public function setEmpresa_id($empresa_id){
        $this->empresa_id = $empresa_id;
    }
    
    public function setId($id){
        $this->id = $id;
    }
    
    public function setExternal_query($external_query){
        $this->external_query = $external_query;
    }
        
    public function setUsuario_id($usuario_id){
        $this->usuario_id = $usuario_id;
    }
    
    public function setCliente_id($cliente_id){
        $this->cliente_id = $cliente_id;
    }
    
    public function setEquipo_id($equipo_id){
        $this->equipo_id = $equipo_id;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function setActividades_realizadas($Actividades_realizadas){
        $this->Actividades_realizadas = $Actividades_realizadas;
    }

    public function setObservaciones($observaciones){
        $this->observaciones = $observaciones;
    }

    public function setInicio($inicio){
        $this->inicio = $inicio;
    }

    public function setEstatus($estatus){
        $this->estatus = $estatus;
    }
    
    public function setFirma_cliente($firma_cliente){
        $this->firma_cliente = $firma_cliente;
    }
    
    public function setVisibility($visibilidad){
        $this->visibilidad = $visibilidad;
    }

    /*A partir de aqui se encuentran los métodos publicos los cuales utilizan los controladores (y tambien la clase Utils) cuando crean un objeto de esta clase, 
     * estos métodos contienen sentencias sql, es necesario utilizar la propiedad $db para acceder al metodo prepare de PDO, el objeto de esa conexión usualmente 
     * se guarda en la variable $stmt, el método prepare tiene la opción de añadir parametros personalizados en un string sql (el formato para crear estos parametros 
     * es el siguiente: ":nombreParametro"), parametros que posteriormente se utilizan como indices en un array asociativo que necesita el método execute para ejecutar 
     * la sentencia sql a sql server (a esos paramentros en este caso se tienen que escribir sin los dos puntos ":"), cada indice tendrá como valor las respectivas 
     * propiedades privadas de esta clase (dependiendo de lo que se requiera en la sentencia sql), haciendo esto estamos "escapando" los datos que se guardan en las 
     * propiedades privadas evitando inyecciones sql e incrustración de scripts; todos los métodos de esta clase usan parametros personalizados en los string sql a 
     * excepción del método updateBinnacleInfo donde se usan directamente las propiedades privadas en los string sql sin embargo, al usar prepare, estos valores siempre 
     * se van a "limpiar"; en peticiones de creación y actualización solo se utiliza execute, sin embargo, en peticiones de lectura antes del return se usa execute y en 
     * el return el metodo fetch en el caso de que se requiera los campos de un registro o fetchAll en el caso de que se requiera la información de más de un registro, 
     * algunas veces se usa el método fetchObject cuando solo se requiere obtener el valor de un campo en especifico*/
    public function insertBinnacle(){
        $sql = "INSERT INTO Bitacoras VALUES( :ui, :ci, :se, :ei, :mo, :ar, "
                .":ob, FORMAT(GETDATE(), 'yyyy-MM-dd'), :fi, 'en proceso', :fc, 'ENABLED' );";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            "ui" => $this->usuario_id, 
            "ci" => $this->cliente_id, 
            "se" => $this->servicio, 
            "ei" => $this->equipo_id, 
            "mo" => $this->monto, 
            "ar" => $this->Actividades_realizadas, 
            "ob" => $this->observaciones, 
            "fi" => $this->fin,
            "fc" => $this->firma_cliente
        ]);
    }
    
    public function updateVisibilityById(){
        $sql = "UPDATE Bitacoras SET Visibilidad = :vi WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'vi' => $this->visibilidad,
            'id' => $this->id
        ]);
    }
    
    public function getBinnCountByUserAndStatus(){
        $sql = "SELECT COUNT(Id) AS 'total' FROM Bitacoras WHERE Usuario_id = "
                .":usid AND (Estatus = 'en proceso' OR Estatus = 'falta confirmar');";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['usid' => $this->usuario_id]);
        return $stmt->fetchObject()->total;
    }
    
    public function getBinnCountByFilterSessions(){
        $stmt = $this->db->prepare($this->external_query);
        $stmt->execute();
        return $stmt->fetchObject()->total;
    }
    
    
    public function getServicioFieldById(){
        if(!empty($this->usuario_id)){
            $sql = "SELECT Servicio FROM Bitacoras WHERE Id = :id AND Usuario_id = :ui;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                "id" => $this->id,
                "ui" => $this->usuario_id
            ]);
        }else{
            $sql = "SELECT Servicio FROM Bitacoras WHERE Id = :id;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                "id" => $this->id
            ]);
        }
        return $stmt->fetch();
    }
    
    public function getInfoIfServicioIsNotNull(){
        /*Si un objeto de esta clase se le añade un valor a su propiedad privada $usuario_id se usará la sentencia sql de una bitácora vinculada a un usuario en especifico*/
        if(!empty($this->usuario_id)){
            $sql = "SELECT b.Id, b.Usuario_id, u.Nombre, u.Apellidos, u.firma as 'Tecnico_firma', c.Nombre_completo, e.Nombre_comercial, e.Razon_social, " 
                   ."e.Calle_numero, e.Entre_calles, e.Dirigirse_con, e.Telefonos, e.Horario, e.Atencion, "
                   ."e.Colonia, e.Localidad, e.Email, b.Servicio, b.Actividades_realizadas, b.Observaciones, "
                   ."b.Inicio, b.Estatus, b.Firma_cliente FROM Bitacoras b INNER JOIN Usuarios u ON b.Usuario_id = u.Id INNER JOIN Contactos c ON "
                   ."b.Contacto_id = c.Id INNER JOIN Empresas e ON c.Empresa_id = e.Id "
                   ."WHERE b.Id = :id AND b.Usuario_id = :ui;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                "id" => $this->id,
                "ui" => $this->usuario_id
            ]);
        }else{
        /*Si un objeto de esta clase no se le añade un valor a su propiedad privada $usuario_id se usará la sentencia sql de una bitácora en especifico*/    
            $sql = "SELECT b.Id, b.Usuario_id, u.Nombre, u.Apellidos, u.firma as 'Tecnico_firma', c.Nombre_completo, e.Nombre_comercial, e.Razon_social, " 
                   ."e.Calle_numero, e.Entre_calles, e.Dirigirse_con, e.Telefonos, e.Horario, e.Atencion, "
                   ."e.Colonia, e.Localidad, e.Email, b.Servicio, b.Monto, b.Actividades_realizadas, b.Observaciones, "
                   ."b.Inicio, b.Fin, b.Estatus, b.Firma_cliente FROM Bitacoras b INNER JOIN Usuarios u ON b.Usuario_id = u.Id INNER JOIN Contactos c ON "
                   ."b.Contacto_id = c.Id INNER JOIN Empresas e ON c.Empresa_id = e.Id "
                   ."WHERE b.Id = :id;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                "id" => $this->id
            ]);
        }
        return $stmt->fetch();
    }
    
    public function getInfoIfServicioIsNull(){
        /*Si un objeto de esta clase se le añade un valor a su propiedad privada $usuario_id se usará la sentencia sql de una bitácora vinculada a un usuario en especifico*/
        if(!empty($this->usuario_id)){
            $sql = "SELECT b.Id, b.Usuario_id, u.Nombre, u.Apellidos, u.firma as 'Tecnico_firma', c.Nombre_completo, e.Nombre_comercial, e.Razon_social, " 
                    ."e.Calle_numero, e.Entre_calles, e.Dirigirse_con, e.Telefonos, e.Horario, e.Atencion, "
                    ."e.Colonia, e.Localidad, e.Email, t.Tipo, eq.Marca, eq.Modelo, eq.Numero_serie, eq.Numero_inventario, " 
                    ."b.Actividades_realizadas, b.Observaciones, b.Inicio, b.Estatus, b.Firma_cliente FROM Bitacoras b INNER JOIN Usuarios u ON b.Usuario_id = u.Id "
                    ."INNER JOIN Contactos c ON b.Contacto_id = c.Id "
                    ."INNER JOIN Empresas e ON c.Empresa_id = e.Id " 
                    ."INNER JOIN Equipos eq ON b.Equipo_id = eq.Id " 
                    ."INNER JOIN Tipos t ON eq.Tipo_id = t.Id WHERE b.Id = :id AND b.Usuario_id = :ui;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                "id" => $this->id,
                "ui" => $this->usuario_id
            ]);
        }else{
        /*Si un objeto de esta clase no se le añade un valor a su propiedad privada $usuario_id se usará la sentencia sql de una bitácora en especifico*/    
            $sql = "SELECT b.Id, b.Usuario_id, u.Nombre, u.Apellidos, u.firma as 'Tecnico_firma', c.Nombre_completo, e.Nombre_comercial, e.Razon_social, " 
                    ."e.Calle_numero, e.Entre_calles, e.Dirigirse_con, e.Telefonos, e.Horario, e.Atencion, "
                    ."e.Colonia, e.Localidad, e.Email, t.Tipo, eq.Marca, eq.Modelo, eq.Numero_serie, eq.Numero_inventario, b.Monto, " 
                    ."b.Actividades_realizadas, b.Observaciones, b.Inicio, b.Fin, b.Estatus, b.Firma_cliente FROM Bitacoras b INNER JOIN Usuarios u ON b.Usuario_id = u.Id "
                    ."INNER JOIN Contactos c ON b.Contacto_id = c.Id "
                    ."INNER JOIN Empresas e ON c.Empresa_id = e.Id " 
                    ."INNER JOIN Equipos eq ON b.Equipo_id = eq.Id " 
                    ."INNER JOIN Tipos t ON eq.Tipo_id = t.Id WHERE b.Id = :id;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                "id" => $this->id
            ]);
        }
        return $stmt->fetch();
    }
    
    public function insertFollowupPartial(){
        $sql = "UPDATE Bitacoras SET Actividades_realizadas = :actreal, "
            ."Observaciones = :obs, Inicio = :ini, Estatus = :sts "
            ."WHERE Id = :id AND Usuario_id = :usrid;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
                    'actreal' => $this->Actividades_realizadas,
                    'obs' => $this->observaciones,
                    'ini' => $this->inicio,
                    'sts' => $this->estatus,
                    'id' => $this->id,
                    'usrid' => $this->usuario_id
        ]);
    }

    public function resetActivitiesDesc(){
        $sql = "UPDATE Bitacoras SET Observaciones = :obs, "
            ."Actividades_realizadas = :ar, Estatus = 'en proceso' "
            ."WHERE Id = :id AND Usuario_id = :usrid;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
                    'obs' => $this->observaciones,
                    'ar' => $this->actividades_realizadas,
                    'id' => $this->id,
                    'usrid' => $this->usuario_id
        ]);
    }
    
    public function cancelBinnacle(){
        $sql = "UPDATE Bitacoras SET Observaciones = :obs, Estatus = :sts "
                ."WHERE Id = :id AND Usuario_id = :usrid;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'obs'   => $this->observaciones,
            'sts'   => $this->estatus,
            'id'    => $this->id,
            'usrid' => $this->usuario_id
        ]);
    }

    public function finishBinnacle(){
        $sql = "UPDATE Bitacoras SET Estatus = 'finalizado', "
                ."Fin = FORMAT(GETDATE(), 'yyyy-MM-dd'), Firma_cliente = :fm WHERE Id = :id AND "
                ."Usuario_id = :usrid;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'fm'    => $this->firma_cliente,
            'id'    => $this->id,
            'usrid' => $this->usuario_id
        ]);
    }
    
    public function updateBinnacleInfo(){
        /*Los campos del formulario de edición de una bitácora pueden variar dependiendo del estatus, updateBinnacleInfo contiene bloques if evaluando si la 
         * propiedad privada estatus es igual a uno de los 4 estatus que puede tener una bitácora, dentro de los if se crea un string sql utilizando propiedades 
         * privadas para establecer los valores de los campos del registro de la tabla Bitacoras a cambiar (tanto la propiedad privada estatus y las otras posibles 
         * propiedades a utilizar se inicializan en el constructor de la clase cuando se crea una instancia de esta)*/
        if($this->estatus === "en proceso"){
            /*puede que una bitacora sea de servicio o equipo, si la propiedad privada $servicio tiene un valor, se inicializa la variable $sql_add_service con una porción de 
             * oración sql utilizando el valor de esa propiedad, si esa propiedad está vacía, entonces se agrega un string de espacio a $sql_add_service*/
            $sql_add_service = (!empty($this->servicio)) ? " Servicio = '".$this->servicio."', " : " ";
            /*Una bitacora puede tener o no un monto, si la propiedad privada $monto tiene un valor se inicializa la variable $sql_price con una porción de 
             * oración sql utilizando el valor de esa propiedad, si esa propiedad está vacía, entonces se agrega una porción de oración sql indicando que el 
             * campo Monto es igual a NULL a $sql_price*/
            $sql_price = (!empty($this->monto)) ? "Monto = ".$this->monto.", " : "Monto = NULL, ";
            /*las variables $sql_add_service y $sql_price se utilizan para dar forma a la sentencia sql*/
            $sql = "UPDATE Bitacoras SET Usuario_id = ".$this->usuario_id
                .",".$sql_add_service.$sql_price."Inicio = '".$this->inicio."' WHERE Id = "
                .$this->id.";";    
        }
        
        if($this->estatus === "falta confirmar"){
            $sql_add_service = (!empty($this->servicio)) ? " Servicio = '".$this->servicio."', " : " ";
            $sql_price = (!empty($this->monto)) ? "Monto = ".$this->monto.", " : "Monto = NULL, ";
            $sql = "UPDATE Bitacoras SET Usuario_id = ".$this->usuario_id
                .",".$sql_add_service.$sql_price."Actividades_realizadas = '"
                .$this->Actividades_realizadas."',"." Observaciones = '".$this->observaciones."',"
                ." Inicio = '".$this->inicio."' WHERE Id = ".$this->id.";";    
        }
        
        if($this->estatus === "cancelado"){
            $sql_add_service = (!empty($this->servicio)) ? " Servicio = '".$this->servicio."', " : " ";
            $sql = "UPDATE Bitacoras SET".$sql_add_service."Observaciones = '".$this->observaciones."',"
                   ." Inicio = '".$this->inicio."' WHERE Id = ".$this->id.";"; 
        }
        
        if($this->estatus === "finalizado"){
            $sql_add_service = (!empty($this->servicio)) ? " Servicio = '".$this->servicio."', " : " ";
            $sql_price = (!empty($this->monto)) ? "Monto = ".$this->monto.", " : "Monto = NULL, ";
            $sql = "UPDATE Bitacoras SET".$sql_add_service.$sql_price."Actividades_realizadas = '"
                    .$this->Actividades_realizadas."', Observaciones = '".$this->observaciones
                    ."', Inicio = '".$this->inicio."', Fin = '".$this->fin."' WHERE Id = ".$this->id.";";    
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }
    
}