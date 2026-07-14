<?php

class ContactosEntity{

    private $id, $empresa_id, $nombre_completo;

    public function __construct($id = null, 
                                $empresa_id = null, 
                                $nombre_completo = null){
        $this->setId($id);
        $this->setEmpresaId($empresa_id);
        $this->setNombreCompleto($nombre_completo);
    }
    public function setId($id){
        if($id == null){
            $this->id = $id;
        }else{

            if(!preg_match('/[0-9]+/', $id) || intval($id) <= 0)
                throw new EntityException("Id del contacto no es un valor númerico ". 
                "o su valor es menor o igual a cero.");
                 
            $this->id = $id;
        }
    }
    public function setEmpresaId($empresa_id){
        if($empresa_id == null){
            $this->empresa_id = $empresa_id;
        }else{

            if(!preg_match('/[0-9]+/', $empresa_id) || intval($empresa_id) <= 0)
                throw new EntityException("Id de la empresa en el contacto no es un valor númerico ". 
                "o su valor es menor o igual a cero.");
                 
            $this->empresa_id = $empresa_id;
        }
    }
    public function setNombreCompleto($nombre_completo){
        if($nombre_completo == null){
            $this->nombre_completo = $nombre_completo;
        }else{

            if(trim($nombre_completo) === "" || 
            strlen($nombre_completo) >= 255)
                throw new EntityException("El nombre del contacto no debe de estar vacío ".
                    "ni superar los 255 caracteres");
                 
            $this->nombre_completo = $nombre_completo;
        }
    }
    public function getId(){
        return $this->id;
    }
    public function getEmpresaId(){
        return $this->empresa_id;
    }
    public function getNombreCompleto(){
        return $this->nombre_completo;
    }
}