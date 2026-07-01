<?php
class UsuariosEntity{
    
    private $id, $nombre, $apellidos, $alias, $contrasena, $privilegio,
        $firma;
    
    public function __construct($id = null, 
                                $nombre = null,
                                $apellidos = null,
                                $alias = null,
                                $contrasena = null,
                                $privilegio = null,
                                $firma = null){

        $this->setId($id);
        $this->setNombre($nombre);
        $this->setApellidos($apellidos);
        $this->setAlias($alias);
        $this->setContrasena($contrasena);
        $this->setPrivilegio($privilegio);
        $this->setFirma($firma);
    }
    
    public function setId($id){
        if($id == null){
            $this->id = $id;
        }else{

            if(!preg_match('/[0-9]+/', $id) || intval($id) <= 0)
                throw new EntityException("Id del usuario no es un valor númerico ". 
                "o su valor es menor o igual a cero.");
                 
            $this->id = $id;
        }
        
    }
    public function setNombre($nombre){
        
        if($nombre == null){
            $this->nombre = $nombre;
        }else{

            if(trim($nombre) === "" || strlen($nombre) >= 100)
                throw new EntityException("El nombre del usuario no debe de estar vacío ".
                    "ni superar los 100 caracteres");
                 
            $this->nombre = $nombre;
        }
    }
    public function setApellidos($apellidos){

        if($apellidos == null){
            $this->apellidos = $apellidos;
        }else{

            if(trim($apellidos) === "" || strlen($apellidos) >= 100)
                throw new EntityException("Los apellidos del usuario no deben de estar vacíos ".
                    "ni superar los 100 caracteres");
                 
            $this->apellidos = $apellidos;
        }
    }
    public function setAlias($alias){

        if($alias == null){
            $this->alias = $alias;
        }else{

            if(trim($alias) === "" || strlen($alias) >= 100)
                throw new EntityException("El alias del usuario no debe de estar vacío ".
                    "ni superar los 100 caracteres");
                 
            $this->alias = $alias;
        }
    }
    public function setContrasena($contrasena){
        
        if($contrasena == null){
            $this->contrasena = $contrasena;
        }else{

            if(trim($contrasena) === "" || strlen($contrasena) >= 255)
                throw new EntityException("La contraseña del usuario no debe de estar vacío ".
                    "ni superar los 255 caracteres");
                 
            $this->contrasena = $contrasena;
        }
    }
    public function setPrivilegio($privilegio){
        
        if($privilegio == null){
            $this->privilegio = $privilegio;
        }else{

            if(trim($privilegio) === "" || strlen($privilegio) >= 20)
                throw new EntityException("El privilegio del usuario no debe de estar vacío ".
                    "ni superar los 20 caracteres");
                 
            $this->privilegio = $privilegio;
        }
    }
    public function setFirma($firma){
        $this->firma = $firma;
        if($firma == null){
            $this->firma = $firma;
        }else{

            if(strlen($firma) >= 255)
                throw new EntityException("La firma del usuario no debe superar los 255 caracteres");
                 
            $this->firma = $firma;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellidos() {
        return $this->apellidos;
    }

    public function getAlias() {
        return $this->alias;
    }

    public function getContrasena() {
        return $this->contrasena;
    }

    public function getPrivilegio() {
        return $this->privilegio;
    }

    public function getFirma() {
        return $this->firma;
    }
}