<?php

class EnterpriseQueries{

    private $db, $model, $contactModel;

    public function setConnection(){
        $this->db = DataBaseMssql::getConnection();
    }
    public function closeConnection(){
        $this->db = null;
    }
    public function setModel($model){
        if($model == null || get_class($model) !== "EmpresasModel")
            throw new WrongObjectException("La clase EnterpriseQueries necesita un objeto tipo EmpresasModel en la propiedad 'model', otro objeto diferente no es permitido");
        $this->model = $model;
    }
    public function setContactModel($contactModel){
        if($contactModel == null || get_class($contactModel) !== "ContactosModel")
            throw new WrongObjectException("La clase EnterpriseQueries necesita un objeto tipo ContactosModel en la propiedad 'model', otro objeto diferente no es permitido");
        $this->contactModel = $contactModel;
    }

    public function twoModelsTransaction(){
        $result = false;
        try{
            $this->db->beginTransaction();
            $sql1 = "INSERT INTO Empresas OUTPUT INSERTED.Id AS 'Empresa_id' ".
            "VALUES(:nco, :rso, :cnu, :eca, :dir, :tel, ".
            ":hor, :ate, :col, :loc, :eml, 'ENABLED');";

            $stmt = $this->db->prepare($sql1);
            $stmt->execute([
                'nco' => ucwords(strtolower(trim($this->model->nombre_comercial))),
                'rso' => (isset($this->model->razon_social)) ? strtoupper($this->model->razon_social) : $this->model->razon_social, 
                'cnu' => (isset($this->model->calle_numero)) ? ucwords(strtolower($this->model->calle_numero)) : $this->model->calle_numero, 
                'eca' => (isset($this->model->entre_calles)) ? ucwords(strtolower($this->model->entre_calles)) : $this->model->entre_calles, 
                'dir' => (isset($this->model->dirigirse_con)) ? ucwords(strtolower($this->model->dirigirse_con)) : $this->model->dirigirse_con, 
                'tel' => $this->model->telefonos, 
                'hor' => $this->model->horario, 
                'ate' => $this->model->atencion, 
                'col' => (isset($this->model->colonia)) ? ucwords(strtolower($this->model->colonia)) : $this->model->colonia, 
                'loc' => (isset($this->model->localidad)) ? ucwords(strtolower($this->model->localidad)) : $this->model->localidad, 
                'eml' => $this->model->email
            ]);
            //$stmt->nextRowset();
            $empresa_id = $stmt->fetch();
            if(empty($empresa_id["Empresa_id"])) 
                throw new Exception();

            $sql2 = "INSERT INTO Contactos VALUES(:eid, :con, 'ENABLED');";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute([
                'eid' => $empresa_id["Empresa_id"],
                'con' => ucwords(strtolower(trim($this->contactModel->nombre_completo)))
            ]);
            $this->db->commit();
            $result = true;
        }catch(Exception $ex){
            if($this->db->inTransaction())
                $this->db->rollBack();
            $message = $ex->getMessage();
        }finally{
            if(!$result)
                throw new Exception($message);
            return $result;
        }

    }
    
    public function updateVisibilityById(){
        $sql = "UPDATE Empresas SET Visibilidad = :vi WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'vi' => $this->model->visibilidad,
            'id' => $this->model->id
        ]);
    }
    
    public function getEnterprise(){
        $sql = "SELECT * FROM Empresas WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["id" => $this->model->id]);
        return $stmt->fetch();
    }
    
    public function getEnterprisesForSelect(){
        $sql = "SELECT Id, Nombre_comercial, Razon_social FROM Empresas WHERE Visibilidad = 'ENABLED';";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getEnterprises(){
        $sql = "SELECT Id, Nombre_comercial, Razon_social FROM Empresas;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function updateEnterprise(){
        $sql = "UPDATE Empresas SET Nombre_comercial = :nco, "
                ."Razon_social = :rso, Calle_numero = :cnu, "
                ."Entre_calles = :eca, Dirigirse_con = :dco, "
                ."Telefonos = :tel, Horario = :hro, Atencion = :aon, "
                ."Colonia = :col, Localidad = :lca, Email = :eml "
                ."WHERE Id = :eid;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            "nco" => ucwords(strtolower(trim($this->model->nombre_comercial))),
            "rso" => (isset($this->model->razon_social)) ? strtoupper($this->model->razon_social) : $this->model->razon_social,
            "cnu" => (isset($this->model->calle_numero)) ? ucwords(strtolower($this->model->calle_numero)) : $this->model->calle_numero,
            "eca" => (isset($this->model->entre_calles)) ? ucwords(strtolower($this->model->entre_calles)) : $this->model->entre_calles,
            "dco" => (isset($this->model->dirigirse_con)) ? ucwords(strtolower($this->model->dirigirse_con)) : $this->model->dirigirse_con,
            "tel" => $this->model->telefonos,
            "hro" => $this->model->horario,
            "aon" => $this->model->atencion,
            "col" => (isset($this->model->colonia)) ? ucwords(strtolower($this->model->colonia)) : $this->model->colonia,
            "lca" => (isset($this->model->localidad)) ? ucwords(strtolower($this->model->localidad)) : $this->model->localidad,
            "eml" => $this->model->email,
            "eid" => $this->model->id
        ]);
    }
}