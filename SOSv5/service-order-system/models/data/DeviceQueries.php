<?php

class DeviceQueries{

    private $db, $model;

    public function setConnection(){
        $this->db = DataBaseMssql::getConnection();
    }
    public function closeConnection(){
        $this->db = null;
    }
    public function setModel($model){
        if($model == null || get_class($model) !== "EquiposModel")
            throw new WrongObjectException("La clase DeviceQueries necesita un objeto tipo EquiposModel en la propiedad 'model', otro objeto diferente no es permitido");
        $this->model = $model;
    }

    public function insertDevice(){
        $sql = "INSERT INTO Equipos VALUES( :ei, :ti, :ma, :mo, :ns, :ni, 'ENABLED' );";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            "ei" => $this->model->empresa_id, 
            "ti" => $this->model->tipo_id, 
            "ma" => ucwords(strtoupper($this->model->marca)), 
            "mo" => ucwords(strtoupper($this->model->modelo)), 
            "ns" => strtoupper(trim($this->model->numero_serie)), 
            "ni" => $this->model->numero_inventario
        ]);
    }
    
    public function updateVisibiliyById(){
        $sql = "UPDATE Equipos SET Visibilidad = :vi WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'vi' => $this->model->visibilidad,
            'id' => $this->model->id
        ]);
    }
    
    public function getDevice(){
        $sql = "SELECT COUNT(b.Id) AS 'total', e.Id, t.Tipo, e.Marca, e.Modelo, "
                ."e.Numero_serie, e.Numero_inventario FROM Equipos e LEFT JOIN "
                ."Bitacoras b ON b.Equipo_id = e.Id INNER JOIN Tipos t ON "
                ."e.Tipo_id = t.Id WHERE e.Id = :id GROUP BY e.Id, t.Tipo, e.Marca, "
                ."e.Modelo, e.Numero_serie, e.Numero_inventario;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $this->model->id]);
        return $stmt->fetch();
    }
    
    public function getDevicesByEnterprise(){
        $sql = "SELECT e.Id, e.Tipo_id, t.Tipo, e.Marca, e.Modelo, e.Numero_serie, "
                ."e.Numero_inventario, e.Visibilidad FROM Equipos e INNER JOIN Tipos t ON "
                ."e.Tipo_id = t.Id WHERE e.Empresa_id = :eid;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['eid' => $this->model->empresa_id]);
        return $stmt->fetchAll();
    }
    
    public function getDevicesForSelectByEnterprise(){
        $sql = "SELECT Id, Marca, Numero_serie FROM Equipos WHERE Empresa_id = :ei AND Visibilidad = 'ENABLED';";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['ei' => $this->model->empresa_id]);
        return $stmt->fetchAll();
    }
    
    
    public function updateDevice(){
        $sql = "UPDATE Equipos SET Marca = :mca, Modelo = :mlo, "
                ."Numero_serie = :nus, Numero_inventario = :nui, Tipo_id = :ti WHERE Id = :eid;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            "ti" => $this->model->tipo_id,
            'mca' => ucwords(strtoupper($this->model->marca)),
            'mlo' => ucwords(strtoupper($this->model->modelo)),
            'nus' => strtoupper(trim($this->model->numero_serie)),
            'nui' => $this->model->numero_inventario,
            'eid' => $this->model->id
        ]);
    }
}