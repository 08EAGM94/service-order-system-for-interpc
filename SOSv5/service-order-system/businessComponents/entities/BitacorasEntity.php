<?php

class BitacorasEntity{

    private $id, $usuario_id, $contacto_id, $servicio, $equipo_id,
        $monto, $actividades_realizadas, $observaciones,
        $firma_cliente, $estatus, $inicio, $fin;

    public function __construct($id = null, 
                                $usuario_id = null, 
                                $contacto_id = null, 
                                $servicio = null, 
                                $equipo_id = null,
                                $monto = null, 
                                $actividades_realizadas = null, 
                                $observaciones = null,
                                $firma_cliente = null, 
                                $estatus = null, 
                                $inicio = null, 
                                $fin = null){
        $this->setId($id);
        $this->setUsuarioId($usuario_id);
        $this->setContactoId($contacto_id);
        $this->setServicio($servicio);
        $this->setEquipoId($equipo_id);
        $this->setMonto($monto);
        $this->setActividadesRealizadas($actividades_realizadas);
        $this->setObservaciones($observaciones);
        $this->setFirmaCliente($firma_cliente);
        $this->setEstatus($estatus);
        $this->setInicio($inicio);
        $this->setFin($fin);
    }
    public function setId($id) {
        if($id == null){
            $this->id = $id;
        }else{

            if(!preg_match('/[0-9]+/', $id) || intval($id) <= 0)
                throw new EntityException("Id de la bitácora no es un dato númerico ". 
                "o su valor es menor o igual a cero.");
                 
            $this->id = $id;
        }
    }

    public function setUsuarioId($usuario_id) {
        
        if($usuario_id == null){
            $this->usuario_id = $usuario_id;
        }else{

            if(!preg_match('/[0-9]+/', $usuario_id) || intval($usuario_id) <= 0)
                throw new EntityException("Id del usuario en la bitácora no es un dato númerico ". 
                "o su valor es menor o igual a cero.");
                 
            $this->usuario_id = $usuario_id;
        }
    }

    public function setContactoId($contacto_id) {
        
        if($contacto_id == null){
            $this->contacto_id = $contacto_id;
        }else{

            if(!preg_match('/[0-9]+/', $contacto_id) || intval($contacto_id) <= 0)
                throw new EntityException("Id del contacto en la bitácora no es un dato númerico ". 
                "o su valor es menor o igual a cero.");
                 
            $this->contacto_id = $contacto_id;
        }
    }

    public function setServicio($servicio) {
        
        if($servicio == null){
            $this->servicio = $servicio;
        }else{

            if(strlen($servicio) <= 10)
                throw new EntityException("El servicio de la bitácora debe tener una ".
                    "descripción minima de 11 caracteres");
                 
            $this->servicio = $servicio;
        }
    }

    public function setEquipoId($equipo_id) {
        
        if($equipo_id == null){
            $this->equipo_id = $equipo_id;
        }else{

            if(!preg_match('/[0-9]+/', $equipo_id) || intval($equipo_id) <= 0)
                throw new EntityException("Id del equipo en la bitácora no es un dato númerico ". 
                "o su valor es menor o igual a cero.");
                 
            $this->equipo_id = $equipo_id;
        }
    }

    public function setMonto($monto) {
        
        if($monto == null){
            $this->monto = $monto;
        }else{

            if(!filter_var($monto, FILTER_VALIDATE_FLOAT) || floatval($monto) <= 0)
                throw new EntityException("El monto de la bitácora no es un dato flotante ". 
                "o su valor es menor o igual a cero.");
                 
            $this->monto = $monto;
        }
    }

    public function setActividadesRealizadas($actividades_realizadas) {
        
        if($actividades_realizadas == null){
            $this->actividades_realizadas = $actividades_realizadas;
        }else{

            if(strlen($actividades_realizadas) <= 10)
                throw new EntityException("Las actividades realizadas de la bitácora ".
                    "debe tener una ".
                    "descripción minima de 11 caracteres");
                 
            $this->actividades_realizadas = $actividades_realizadas;
        }
    }

    public function setObservaciones($observaciones) {
        
        if($observaciones == null){
            $this->observaciones = $observaciones;
        }else{

            if(strlen($observaciones) <= 10)
                throw new EntityException("Las observaciones de la bitácora ".
                    "debe tener una ".
                    "descripción minima de 11 caracteres");
                 
            $this->observaciones = $observaciones;
        }
    }

    public function setFirmaCliente($firma_cliente) {
        
        if($firma_cliente == null){
            $this->firma_cliente = $firma_cliente;
        }else{

            if(strlen($firma_cliente) >= 255)
                throw new EntityException("La firma del cliente en la bitácora ".
                    "no debe superar los 255 caracteres ");
                 
            $this->firma_cliente = $firma_cliente;
        }
    }

    public function setEstatus($estatus) {
        
        if($estatus == null){
            $this->estatus = $estatus;
        }else{

            if(trim($estatus) === "" || strlen($estatus) >= 100)
                throw new EntityException("El estatus de la bitacora no debe estar vacio ". 
                "ni superar 100 caracteres.");
                 
            $this->estatus = $estatus;
        }
    }

    public function setInicio($inicio) {
        
        if($inicio == null){
            $this->inicio = $inicio;
        }else{

            if(trim($inicio) === "")
                throw new EntityException("La fecha no debe ser una cadena de texto vacía");
                 
            $this->inicio = $inicio;
        }
    }

    public function setFin($fin) {
        if($fin == null){
            $this->fin = $fin;
        }else{

            if(trim($fin) === "")
                throw new EntityException("La fecha no debe ser una cadena de texto vacía");
                 
            $this->fin = $fin;
        }
    }
    public function getId() {
        return $this->id;
    }

    public function getUsuarioId() {
        return $this->usuario_id;
    }

    public function getContactoId() {
        return $this->contacto_id;
    }

    public function getServicio() {
        return $this->servicio;
    }

    public function getEquipoId() {
        return $this->equipo_id;
    }

    public function getMonto() {
        return $this->monto;
    }

    public function getActividadesRealizadas() {
        return $this->actividades_realizadas;
    }

    public function getObservaciones() {
        return $this->observaciones;
    }

    public function getFirmaCliente() {
        return $this->firma_cliente;
    }

    public function getEstatus() {
        return $this->estatus;
    }

    public function getInicio() {
        return $this->inicio;
    }

    public function getFin() {
        return $this->fin;
    }
    
}