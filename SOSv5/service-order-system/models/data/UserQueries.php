<?php

class UserQueries{

    private $db, $model, $db_class;

    public function __construct($msDatabase){
        $this->db_class = $msDatabase;
    }
    public function setConnection(){
        $this->db = $this->db_class->getConnection();
    }
    public function closeConnection(){
        $this->db = null;
    }
    public function setModel($model){
        if($model == null || get_class($model) !== "UsuariosModel")
            throw new WrongObjectException("La clase UserQueries necesita un objeto tipo UsuariosModel en la propiedad 'model', otro objeto diferente no es permitido");
        $this->model = $model;
    }

    public function getUsers(){
        $sql = "SELECT Id, Nombre, Apellidos, Alias From "
                ."Usuarios WHERE Visibilidad = 'ENABLED';";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getUser(){
        $sql = "SELECT Id, Nombre, Apellidos, Alias, Privilegio, Firma From "
                ."Usuarios WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $this->model->id]);
        return $stmt->fetch();
    }
            
    public function insertSignature(){
        $sql = "UPDATE Usuarios SET Firma = :frm WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'frm' => (isset($this->model->firma)) ? trim($this->model->firma) : $this->model->firma,
            'id'  => $this->model->id
        ]);
    }
    
    public function updateVisibilityById(){
        $sql = "UPDATE Usuarios SET Visibilidad = :vi WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'vi' => $this->model->visibilidad,
            'id' => $this->model->id
        ]);
    }
    
    public function updatePassword(){
        $sql = "UPDATE Usuarios SET Contrasena = :pwd WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'pwd' => password_hash($this->model->contrasena, 
                    PASSWORD_BCRYPT, ['cost' => 4]),
            'id'  => $this->model->id
        ]);
    }
    
    public function insertUser(){
        
        $sql = "INSERT INTO Usuarios VALUES(:nre, :ape, :al, :pwd, :prv, "
                . ":sgn, 'ENABLED');";
        $save = $this->db->prepare($sql);
        return $save->execute(array(
                    'nre' => ucwords(strtolower($this->model->nombre)),
                    'ape' => ucwords(strtolower($this->model->apellidos)),
                    'al' => $this->model->alias,
                    'pwd' => password_hash($this->model->contrasena, 
                    PASSWORD_BCRYPT, ['cost' => 4]),
                    'prv' => $this->model->privilegio,
                    'sgn' => $this->model->firma
        ));
        
    }
    
    
    public function login(){
        $result = ["loginFailed" => "La contraseña es incorrecta para este usuario"];
        $sql = "SELECT Contrasena FROM Usuarios WHERE Alias = :al AND Visibilidad = 'ENABLED';";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['al' => $this->model->alias]);
        $usuario = $stmt->fetch();

        if(empty($usuario))
            throw new UnknownInDataBaseException("El usuario no existe en la base de datos");

        $verify = password_verify($this->model->contrasena, $usuario["Contrasena"]);
        //$verify = ($this->contrasena === $usuario["Contrasena"]) ? true : false;
        if($verify){
            $sql = "SELECT Id, Alias, Nombre, Apellidos, Privilegio, Firma FROM Usuarios WHERE Alias = :al;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['al' => $this->model->alias]);
            $usuario = $stmt->fetch();
            $result = $usuario;
        }
        return $result;
    }
    
    public function adminPwdConfirmation(){
        $result = false;
        $slq = "SELECT Contrasena FROM Usuarios WHERE Alias = :al AND Visibilidad = 'ENABLED';";
        $stmt = $this->db->prepare($slq);
        $stmt->execute(['al' => $this->model->alias]);
        $usuario = $stmt->fetch();

        if(empty($usuario))
            throw new UnknownInDataBaseException("El usuario no existe en la base de datos");

        if(!empty($usuario)){
            $verify = password_verify($this->model->contrasena, $usuario["Contrasena"]);
            //$verify = ($this->contrasena === $usuario["Contrasena"]) ? true : false;
            if($verify){
                $result = true;
            }
        }
        return $result;
    }
    
    
    public function getUserSign(){
        $sql = "SELECT Firma FROM Usuarios WHERE Id = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $this->model->id]);
        return $stmt->fetchObject()->Firma;
    }
}