<?php

class ContactQueries{

    private $db, $model;
    public function setConnection(){
        $this->db = DataBaseMssql::getConnection();
    }
    public function closeConnection(){
        $this->db = null;
    }
    public function setModel($model){
        if($model == null || get_class($model) !== "ContactosModel")
            throw new WrongObjectException("La clase ContactQueries necesita un objeto tipo ContactosModel en la propiedad 'model', otro objeto diferente no es permitido");
        $this->model = $model;
    }

    public function insertContact(){
        $sql = "INSERT INTO Contactos VALUES( :ei, :nc, 'ENABLED' );";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            "ei" => $this->model->empresa_id, 
            "nc" => ucwords(strtolower(trim($this->model->nombre_completo)))
        ]);
    }
    
    public function getContact(){
        $sql = "SELECT c.Id, c.Nombre_completo, e.Nombre_comercial, e.Razon_social, " 
        ."e.Calle_numero, e.Entre_calles, e.Dirigirse_con, e.Telefonos, e.Horario, " 
        ."e.Atencion, e.Colonia, e.Localidad, e.Email FROM Contactos c INNER JOIN "
        ."Empresas e ON c.Empresa_id = e.Id WHERE c.Id = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["id" => $this->model->id]);
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
            'vi' => $this->model->visibilidad,
            'id' => $this->model->id
        ]);
    }
    
    public function getContactsByEnterForSelect(){
        $sql = "SELECT * FROM Contactos WHERE Empresa_id = :eid AND Visibilidad = 'ENABLED';";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['eid' => $this->model->empresa_id]);
        return $stmt->fetchAll();
    }
    
    public function getContactsByEnterprise(){
        $sql = "SELECT * FROM Contactos WHERE Empresa_id = :eid;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['eid' => $this->model->empresa_id]);
        return $stmt->fetchAll();
    }
    
    public function updateContact(){
        $sql = "UPDATE Contactos SET Nombre_completo = :nc WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nc' => ucwords(strtolower(trim($this->model->nombre_completo))),
            'id' => $this->model->id
        ]);
    }
}