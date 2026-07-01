<?php

class BinnacleQueries{

    private $db, $model, $pagination;

    public function __construct($pagination = null){
        
        $this->pagination = $pagination;
    }
    public function setConnection(){
        $this->db = DataBaseMssql::getConnection();
    }
    public function closeConnection(){
        $this->db = null;
    }
    public function setModel($model){
        if($model == null || get_class($model) !== "BitacorasModel")
            throw new WrongObjectException("La clase BinnacleQueries necesita un objeto tipo BitacorasModel en la propiedad 'model', otro objeto diferente no es permitido");
        $this->model = $model;
    }

    public function insertBinnacle(){
        $sql = "INSERT INTO Bitacoras VALUES( :ui, :ci, :se, :ei, :mo, :ar, "
                .":ob, FORMAT(GETDATE(), 'yyyy-MM-dd'), :fi, 'en proceso', :fc, 'ENABLED' );";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            "ui" => $this->model->usuario_id, 
            "ci" => $this->model->contacto_id, 
            "se" => (isset($this->model->servicio)) ? trim($this->model->servicio) : $this->model->servicio, 
            "ei" => $this->model->equipo_id, 
            "mo" => $this->model->monto, 
            "ar" => $this->model->Actividades_realizadas, 
            "ob" => $this->model->observaciones, 
            "fi" => $this->model->fin,
            "fc" => $this->model->firma_cliente
        ]);
    }
    
    public function updateVisibilityById(){
        $sql = "UPDATE Bitacoras SET Visibilidad = :vi WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'vi' => $this->model->visibilidad,
            'id' => $this->model->id
        ]);
    }

    public function getBinnaclesFollowUpPagination($elemsKey){
        $sql = "SELECT COUNT(Id) AS 'total' FROM Bitacoras WHERE Usuario_id = "
                .":usid AND (Estatus = 'en proceso' OR Estatus = 'falta confirmar');";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['usid' => $this->model->usuario_id]);
        $num_rows = $stmt->fetchObject()->total;
        
        if($num_rows > 0){ 
            
            $this->pagination->records($num_rows);
            $this->pagination->records_per_page($elemsKey);
            $page = $this->pagination->get_page();
            $empieza_aqui = (($page - 1) * $elemsKey);

            $stmt_binns = $this->db->prepare("SELECT "
                        ."b.Id, e.Nombre_comercial, b.Estatus FROM Bitacoras b "
                        ."INNER JOIN Contactos c ON b.Contacto_id = c.Id "
                        ."INNER JOIN Empresas e ON c.Empresa_id = e.Id "
                        ."WHERE b.Usuario_id = :uid"
                        ." AND (b.Estatus = 'en proceso' OR b.Estatus = 'falta confirmar') "
                        ."ORDER BY b.Id OFFSET $empieza_aqui ROWS "
                        ."FETCH NEXT $elemsKey ROWS ONLY;");
            $stmt_binns->execute([
                'uid' => $this->model->usuario_id
            ]);
            ob_start();
            $this->pagination->render();
            $pagination_html = ob_get_clean();            
            return [
                "binns" => $stmt_binns->fetchAll(),
                "buttons"  => $pagination_html
            ];
        }

        return [];
    }
    
    public function getBinnaclesReportPagination($elemsKey, $binnsFiltArr){

        $activity = $binnsFiltArr['IsServiceOrDevice'];
        $startedOrEnded = $binnsFiltArr['StartedOrEnded'];

        $sql = "SELECT COUNT(Id) AS 'total' FROM Bitacoras WHERE ".
        "(:cid_check IS NULL OR Contacto_id = :cid_value) AND ".
        "$activity IS NOT NULL AND ".
        "(:eid_check IS NULL OR Equipo_id = :eid_value) AND ".
        "Estatus = :sts AND ".
        "(:ldy_check IS NULL OR $startedOrEnded BETWEEN :ldy_value AND :rdy) AND ".
        "Visibilidad = :vbl;";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'cid_check' => $binnsFiltArr["Contacto_id"],
            'cid_value' => $binnsFiltArr["Contacto_id"],
            'eid_check' => $binnsFiltArr["Equipo_id"],
            'eid_value' => $binnsFiltArr["Equipo_id"],
            'sts' => $binnsFiltArr["Estatus"],
            'ldy_check' => $binnsFiltArr["LeftDay"],
            'ldy_value' => $binnsFiltArr["LeftDay"],
            'rdy' => $binnsFiltArr["RightDay"],
            'vbl' => $binnsFiltArr["Visible"]
        ]);

        $num_rows = $stmt->fetchObject()->total;

        if($num_rows > 0){
            
            $this->pagination->records($num_rows);
            $this->pagination->records_per_page($elemsKey);
            $page = $this->pagination->get_page();
            $empieza_aqui = (($page - 1) * $elemsKey);
            
            $sql2 = "SELECT b.Id, u.Nombre, u.Apellidos, c.Nombre_completo, ".
                "e.Nombre_comercial, b.Visibilidad FROM Bitacoras b ".
                "INNER JOIN Usuarios u ON b.Usuario_id = u.Id ".
                "INNER JOIN Contactos c ON b.Contacto_id = c.Id ".
                "INNER JOIN Empresas e ON c.Empresa_id = e.Id WHERE ".
                "(:cid_check IS NULL OR b.Contacto_id = :cid_value) AND ".
                "$activity IS NOT NULL AND ".
                "(:eid_check IS NULL OR b.Equipo_id = :eid_value) AND ".
                "b.Estatus = :sts AND ".
                "(:ldy_check IS NULL OR b.$startedOrEnded BETWEEN :ldy_value AND :rdy) AND ".
                "b.Visibilidad = :vbl ".
                "ORDER BY b.Id OFFSET ".
                "$empieza_aqui ".
                "ROWS ".
                "FETCH NEXT ".
                "$elemsKey ROWS ONLY;";

            $stmt_binns = $this->db->prepare($sql2);
            $stmt_binns->execute([
                'cid_check' => $binnsFiltArr["Contacto_id"],
                'cid_value' => $binnsFiltArr["Contacto_id"],
                'eid_check' => $binnsFiltArr["Equipo_id"],
                'eid_value' => $binnsFiltArr["Equipo_id"],
                'sts' => $binnsFiltArr["Estatus"],
                'ldy_check' => $binnsFiltArr["LeftDay"],
                'ldy_value' => $binnsFiltArr["LeftDay"],
                'rdy' => $binnsFiltArr["RightDay"],
                'vbl' => $binnsFiltArr["Visible"]
            ]);

            ob_start();
            $this->pagination->render();
            $pagination_html = ob_get_clean();            
            return [
                "binns" => $stmt_binns->fetchAll(),
                "buttons"  => $pagination_html
            ];
        }

        return [];
    }
    
    public function getBinnacle(){

        
        if(!empty($this->model->usuario_id)){
            $sql = "SELECT Servicio FROM Bitacoras WHERE Id = :id AND Usuario_id = :ui;";
            $servico_field_stmt = $this->db->prepare($sql);
            $servico_field_stmt->execute([
                "id" => $this->model->id,
                "ui" => $this->model->usuario_id
            ]);
        }else{
            $sql = "SELECT Servicio FROM Bitacoras WHERE Id = :id;";
            $servico_field_stmt = $this->db->prepare($sql);
            $servico_field_stmt->execute([
                "id" => $this->model->id
            ]);
        }

        $service = $servico_field_stmt->fetch();
        
        if(!empty($service["Servicio"])){

            if(!empty($this->model->usuario_id)){
            /*Si un objeto de esta clase se le añade un valor a su propiedad privada $usuario_id se usará la sentencia sql de una bitácora vinculada a un usuario en especifico*/    
                $sql = "SELECT b.Id, b.Usuario_id, u.Nombre, u.Apellidos, u.firma as 'Tecnico_firma', c.Nombre_completo, e.Nombre_comercial, e.Razon_social, " 
                    ."e.Calle_numero, e.Entre_calles, e.Dirigirse_con, e.Telefonos, e.Horario, e.Atencion, "
                    ."e.Colonia, e.Localidad, e.Email, b.Servicio, b.Actividades_realizadas, b.Observaciones, "
                    ."b.Inicio, b.Estatus, b.Firma_cliente FROM Bitacoras b INNER JOIN Usuarios u ON b.Usuario_id = u.Id INNER JOIN Contactos c ON "
                    ."b.Contacto_id = c.Id INNER JOIN Empresas e ON c.Empresa_id = e.Id "
                    ."WHERE b.Id = :id AND b.Usuario_id = :ui;";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    "id" => $this->model->id,
                    "ui" => $this->model->usuario_id
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
                    "id" => $this->model->id
                ]);
            }

        }else{

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
                    "id" => $this->model->id,
                    "ui" => $this->model->usuario_id
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
                    "id" => $this->model->id
                ]);
            }

        }
        $result = $stmt->fetch();

        if(sizeof($result) === 0)
            throw new UnknownInDataBaseException("El id insertado de la bitácora no existe en la base de datos");

        return $result;
    }
    
    public function insertFollowupPartial(){
        $sql = "UPDATE Bitacoras SET Actividades_realizadas = :actreal, "
            ."Observaciones = :obs, Inicio = :ini, Estatus = :sts "
            ."WHERE Id = :id AND Usuario_id = :usrid;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
                    'actreal' => trim($this->model->Actividades_realizadas),
                    'obs' => (isset($this->model->observaciones)) ? trim($this->model->observaciones) : $this->model->observaciones,
                    'ini' => $this->model->inicio,
                    'sts' => $this->model->estatus,
                    'id' => $this->model->id,
                    'usrid' => $this->model->usuario_id
        ]);
    }

    public function resetActivitiesDesc(){
        $sql = "SELECT Estatus FROM Bitacoras WHERE Id = :id AND Usuario_id = :usrid;";
        $estatus_stmt = $this->db->prepare($sql);
        $estatus_stmt->execute([
                    'id' => $this->model->id,
                    'usrid' => $this->model->usuario_id
        ]);
        $estatus = $estatus_stmt->fetch();
        if($estatus["Estatus"] === "cancelado" || $estatus["Estatus"] === "finalizado")
            throw new UnauthorizedDataException("Id de bitácora prohibida");
        
        $sql = "UPDATE Bitacoras SET Observaciones = :obs, "
            ."Actividades_realizadas = :ar, Estatus = 'en proceso' "
            ."WHERE Id = :id AND Usuario_id = :usrid;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
                    'obs' => $this->model->observaciones,
                    'ar' => $this->model->Actividades_realizadas,
                    'id' => $this->model->id,
                    'usrid' => $this->model->usuario_id
        ]);
    }
    
    public function cancelBinnacle(){
        $sql = "UPDATE Bitacoras SET Observaciones = :obs, Estatus = :sts "
                ."WHERE Id = :id AND Usuario_id = :usrid;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'obs'   => trim($this->model->observaciones),
            'sts'   => $this->model->estatus,
            'id'    => $this->model->id,
            'usrid' => $this->model->usuario_id
        ]);
    }

    public function finishBinnacle(){
        $sql = "UPDATE Bitacoras SET Estatus = 'finalizado', "
                ."Fin = FORMAT(GETDATE(), 'yyyy-MM-dd'), Firma_cliente = :fm WHERE Id = :id AND "
                ."Usuario_id = :usrid;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'fm'    => trim($this->model->firma_cliente),
            'id'    => $this->model->id,
            'usrid' => $this->model->usuario_id
        ]);
    }
    
    public function updateBinnacle(){
        /*Los campos del formulario de edición de una bitácora pueden variar dependiendo del estatus, updateBinnacleInfo contiene bloques if evaluando si la 
         * propiedad privada estatus es igual a uno de los 4 estatus que puede tener una bitácora, dentro de los if se crea un string sql utilizando propiedades 
         * privadas para establecer los valores de los campos del registro de la tabla Bitacoras a cambiar (tanto la propiedad privada estatus y las otras posibles 
         * propiedades a utilizar se inicializan en el constructor de la clase cuando se crea una instancia de esta)*/
        $current_params = [];
        
        if($this->model->estatus === "en proceso"){
            /*puede que una bitacora sea de servicio o equipo, si la propiedad privada $servicio tiene un valor, se inicializa la variable $sql_add_service con una porción de 
             * oración sql utilizando el valor de esa propiedad, si esa propiedad está vacía, entonces se agrega un string de espacio a $sql_add_service*/
            $sql_add_service = (!empty($this->model->servicio)) ? " Servicio = :ser, " : " ";
            /*Una bitacora puede tener o no un monto, si la propiedad privada $monto tiene un valor se inicializa la variable $sql_price con una porción de 
             * oración sql utilizando el valor de esa propiedad, si esa propiedad está vacía, entonces se agrega una porción de oración sql indicando que el 
             * campo Monto es igual a NULL a $sql_price*/
            $sql_price = (!empty($this->model->monto)) ? "Monto = :mon, " : " ";
            /*las variables $sql_add_service y $sql_price se utilizan para dar forma a la sentencia sql*/
            $sql = "UPDATE Bitacoras SET Usuario_id = :uid,".$sql_add_service.$sql_price
                ."Inicio = :ini WHERE Id = :id;";

            if(!empty($this->model->servicio) && !empty($this->model->monto))
                $current_params = [
                    'uid' => $this->model->usuario_id,
                    'ser' => trim($this->model->servicio),
                    'mon' => (isset($this->model->monto)) ? trim($this->model->monto) : $this->model->monto,
                    'ini' => $this->model->inicio,
                    'id'  => $this->model->id  
                ];

            if(empty($this->model->servicio) && !empty($this->model->monto))
                $current_params = [
                    'uid' => $this->model->usuario_id,
                    'mon' => (isset($this->model->monto)) ? trim($this->model->monto) : $this->model->monto,
                    'ini' => $this->model->inicio,
                    'id'  => $this->model->id  
                ];
                
            if(!empty($this->model->servicio) && empty($this->model->monto))
                $current_params = [
                    'uid' => $this->model->usuario_id,
                    'ser' => trim($this->model->servicio),
                    'ini' => $this->model->inicio,
                    'id'  => $this->model->id  
                ];
                
            if(empty($this->model->servicio) && empty($this->model->monto))
                $current_params = [
                    'uid' => $this->model->usuario_id,
                    'ini' => $this->model->inicio,
                    'id'  => $this->model->id  
                ];    
        }
        
        if($this->model->estatus === "falta confirmar"){
            $sql_add_service = (!empty($this->model->servicio)) ? " Servicio = :ser, " : " ";
            $sql_price = (!empty($this->model->monto)) ? "Monto = :mon, " : " ";
            $sql = "UPDATE Bitacoras SET Usuario_id = :uid,".$sql_add_service.$sql_price
                ."Actividades_realizadas = :act, Observaciones = :obs,"
                ." Inicio = :ini WHERE Id = :id;";
            
            if(!empty($this->model->servicio) && !empty($this->model->monto))
                $current_params = [
                    'uid' => $this->model->usuario_id,
                    'ser' => trim($this->model->servicio),
                    'mon' => (isset($this->model->monto)) ? trim($this->model->monto) : $this->model->monto,
                    'act' => trim($this->model->Actividades_realizadas),
                    'obs' => (isset($this->model->observaciones)) ? trim($this->model->observaciones) : $this->model->observaciones,
                    'ini' => $this->model->inicio,
                    'id'  => $this->model->id  
                ];

            if(empty($this->model->servicio) && !empty($this->model->monto))
                $current_params = [
                    'uid' => $this->model->usuario_id,
                    'mon' => (isset($this->model->monto)) ? trim($this->model->monto) : $this->model->monto,
                    'act' => trim($this->model->Actividades_realizadas),
                    'obs' => (isset($this->model->observaciones)) ? trim($this->model->observaciones) : $this->model->observaciones,
                    'ini' => $this->model->inicio,
                    'id'  => $this->model->id  
                ];
                
            if(!empty($this->model->servicio) && empty($this->model->monto))
                $current_params = [
                    'uid' => $this->model->usuario_id,
                    'ser' => trim($this->model->servicio),
                    'act' => trim($this->model->Actividades_realizadas),
                    'obs' => (isset($this->model->observaciones)) ? trim($this->model->observaciones) : $this->model->observaciones,
                    'ini' => $this->model->inicio,
                    'id'  => $this->model->id  
                ];
                
            if(empty($this->model->servicio) && empty($this->model->monto))
                $current_params = [
                    'uid' => $this->model->usuario_id,
                    'act' => trim($this->model->Actividades_realizadas),
                    'obs' => (isset($this->model->observaciones)) ? trim($this->model->observaciones) : $this->model->observaciones,
                    'ini' => $this->model->inicio,
                    'id'  => $this->model->id   
                ];    
        }
        
        if($this->model->estatus === "cancelado"){
            $sql_add_service = (!empty($this->model->servicio)) ? " Servicio = :ser, " : " ";
            $sql = "UPDATE Bitacoras SET".$sql_add_service."Observaciones = :obs,"
                   ." Inicio = :ini WHERE Id = :id;";
            
            if(!empty($this->model->servicio))
                $current_params = [
                    'ser' => trim($this->model->servicio),
                    'obs' => trim($this->model->observaciones),
                    'ini' => $this->model->inicio,
                    'id'  => $this->model->id  
                ];

            if(empty($this->model->servicio))
                $current_params = [
                    'obs' => trim($this->model->observaciones),
                    'ini' => $this->model->inicio,
                    'id'  => $this->model->id  
                ];
                      
        }
        
        if($this->model->estatus === "finalizado"){
            $sql_add_service = (!empty($this->model->servicio)) ? " Servicio = :ser, " : " ";
            $sql_price = (!empty($this->model->monto)) ? "Monto = :mon, " : " ";
            $sql = "UPDATE Bitacoras SET".$sql_add_service.$sql_price
                    ."Actividades_realizadas = :act, Observaciones = :obs"
                    .", Inicio = :ini, Fin = :fin WHERE Id = :id;";
                
            if(!empty($this->model->servicio) && !empty($this->model->monto))
                $current_params = [
                    'ser' => trim($this->model->servicio),
                    'mon' => $this->model->monto,
                    'act' => trim($this->model->Actividades_realizadas),
                    'obs' => (isset($this->model->observaciones)) ? trim($this->model->observaciones) : $this->model->observaciones,
                    'ini' => $this->model->inicio,
                    'fin' => $this->model->fin,
                    'id'  => $this->model->id  
                ];

            if(empty($this->model->servicio) && !empty($this->model->monto))
                $current_params = [
                    'mon' => $this->model->monto,
                    'act' => trim($this->model->Actividades_realizadas),
                    'obs' => (isset($this->model->observaciones)) ? trim($this->model->observaciones) : $this->model->observaciones,
                    'ini' => $this->model->inicio,
                    'fin' => $this->model->fin,
                    'id'  => $this->model->id  
                ];
                
            if(!empty($this->model->servicio) && empty($this->model->monto))
                $current_params = [
                    'ser' => trim($this->model->servicio),
                    'act' => trim($this->model->Actividades_realizadas),
                    'obs' => (isset($this->model->observaciones)) ? trim($this->model->observaciones) : $this->model->observaciones,
                    'ini' => $this->model->inicio,
                    'fin' => $this->model->fin,
                    'id'  => $this->model->id  
                ];
                
            if(empty($this->model->servicio) && empty($this->model->monto))
                $current_params = [
                    'act' => trim($this->model->Actividades_realizadas),
                    'obs' => (isset($this->model->observaciones)) ? trim($this->model->observaciones) : $this->model->observaciones,
                    'ini' => $this->model->inicio,
                    'fin' => $this->model->fin,
                    'id'  => $this->model->id  
                ];    
        }
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($current_params);
    }
}