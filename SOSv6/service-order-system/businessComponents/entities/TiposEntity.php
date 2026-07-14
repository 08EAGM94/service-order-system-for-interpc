<?php

class TiposEntity{

    private $id, $tipo;

    public function __construct($id = null, $tipo = null){
        $this->setId($id);
        $this->setTipo($tipo);
    }
    public function setId($id){
        if($id == null){
            $this->id = $id;
        }else{

            if(!preg_match('/[0-9]+/', $id) || intval($id) <= 0)
                throw new EntityException("Id del tipo no es un valor númerico ". 
                "o su valor es menor o igual a cero.");
                 
            $this->id = $id;
        }
    }
    public function setTipo($tipo){
        if($tipo == null){
            $this->tipo = $tipo;
        }else{

            if(trim($tipo) === "" || strlen($tipo) >= 255)
                throw new EntityException("El tipo no debe de estar vacío ".
                    "ni superar los 255 caracteres");
                 
            $this->tipo = $tipo;
        }
    }
    public function getId(){
        return $this->id;
    }
    public function getTipo(){
        return $this->tipo;
    }
}