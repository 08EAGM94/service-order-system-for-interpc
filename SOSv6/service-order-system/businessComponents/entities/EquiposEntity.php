<?php

class EquiposEntity{

    private $id, $empresa_id, $tipo_id, 
        $marca, $modelo, $numero_serie,
        $numero_inventario;

    public function __construct($id = null, 
                                $empresa_id = null, 
                                $tipo_id = null, 
                                $marca = null, 
                                $modelo = null, 
                                $numero_serie = null,
                                $numero_inventario = null){
        $this->setId($id);
        $this->setEmpresaId($empresa_id);
        $this->setTipoId($tipo_id);
        $this->setMarca($marca);
        $this->setModelo($modelo);
        $this->setNumeroSerie($numero_serie);
        $this->setNumeroInventario($numero_inventario);
    }
    
    public function setId($id)
    {
        if($id == null){
            $this->id = $id;
        }else{

            if(!preg_match('/[0-9]+/', $id) || intval($id) <= 0)
                throw new EntityException("Id del equipo no es un valor númerico ". 
                "o su valor es menor o igual a cero.");
                 
            $this->id = $id;
        }
    }

    public function setEmpresaId($empresa_id)
    {

        if($empresa_id == null){
            $this->empresa_id = $empresa_id;
        }else{

            if(!preg_match('/[0-9]+/', $empresa_id) || intval($empresa_id) <= 0)
                throw new EntityException("Id de la empresa del equipo no es un valor númerico ". 
                "o su valor es menor o igual a cero.");
                 
            $this->empresa_id = $empresa_id;
        }
    }

    public function setTipoId($tipo_id)
    {
        
        if($tipo_id == null){
            $this->tipo_id = $tipo_id;
        }else{

            if(!preg_match('/[0-9]+/', $tipo_id) || intval($tipo_id) <= 0)
                throw new EntityException("Id del tipo del equipo no es un valor númerico ". 
                "o su valor es menor o igual a cero.");
                 
            $this->tipo_id = $tipo_id;
        }
    }

    public function setMarca($marca)
    {
        
        if($marca == null){
            $this->marca = $marca;
        }else{

            if(trim($marca) === "" || strlen($marca) >= 255)
                throw new EntityException("La marca del equipo no debe de estar vacío ".
                    "ni superar los 255 caracteres");
                 
            $this->marca = $marca;
        }
    }

    public function setModelo($modelo)
    {
        
        if($modelo == null){
            $this->modelo = $modelo;
        }else{

            if(trim($modelo) === "" || strlen($modelo) >= 255)
                throw new EntityException("El modelo del equipo no debe de estar vacío ".
                    "ni superar los 255 caracteres");
                 
            $this->modelo = $modelo;
        }
    }

    public function setNumeroSerie($numero_serie)
    {
        
        if($numero_serie == null){
            $this->numero_serie = $numero_serie;
        }else{

            if(trim($numero_serie) === "" || strlen($numero_serie) >= 255)
                throw new EntityException("El número de serie del equipo no debe de estar vacío ".
                    "ni superar los 255 caracteres");
                 
            $this->numero_serie = $numero_serie;
        }
    }

    public function setNumeroInventario($numero_inventario)
    {
        
        if($numero_inventario == null){
            $this->numero_inventario = $numero_inventario;
        }else{

            if(!preg_match('/[0-9]+/', $numero_inventario) || intval($numero_inventario) <= 0)
                throw new EntityException("El número de inventario del equipo no es un valor númerico ". 
                "o su valor es menor o igual a cero.");
                 
            $this->numero_inventario = $numero_inventario;
        }
    }
    public function getId()
    {
        return $this->id;
    }

    public function getEmpresaId()
    {
        return $this->empresa_id;
    }

    public function getTipoId()
    {
        return $this->tipo_id;
    }

    public function getMarca()
    {
        return $this->marca;
    }

    public function getModelo()
    {
        return $this->modelo;
    }

    public function getNumeroSerie()
    {
        return $this->numero_serie;
    }

    public function getNumeroInventario()
    {
        return $this->numero_inventario;
    }

}