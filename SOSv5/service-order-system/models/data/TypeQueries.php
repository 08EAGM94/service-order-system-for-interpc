<?php

class TypeQueries{

    private $db, $model;

    public function setConnection(){
        $this->db = DataBaseMssql::getConnection();
    }
    public function closeConnection(){
        $this->db = null;
    }
    public function setModel($model){
        if($model == null || get_class($model) !== "TiposModel")
            throw new WrongObjectException("La clase TypeQueries necesita un objeto tipo TiposModel en la propiedad 'model', otro objeto diferente no es permitido");
        $this->model = $model;
    }

    public function insertType(){
        $sql = "INSERT INTO Tipos VALUES( :tp, 'ENABLED' );";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(["tp" => ucfirst(strtolower(trim($this->model->tipo)))]);
    }
    
    public function updateVisibilityById(){
        $sql = "UPDATE Tipos SET Visibilidad = :vi WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'vi' => $this->model->visibilidad,
            'id' => $this->model->id
        ]);
    }
    
    public function updateType(){
        $sql = "UPDATE Tipos SET Tipo = :typ WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'typ' => ucfirst(strtolower(trim($this->model->tipo))),
            'id'  => $this->model->id
        ]);
    }
    
    public function getType(){
        $sql = "SELECT * FROM Tipos WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["id" => $this->model->id]);
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