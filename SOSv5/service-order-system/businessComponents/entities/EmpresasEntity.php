<?php

class EmpresasEntity{

    private $id, $nombre_comercial, $razon_social, $calle_numero,
        $entre_calles, $dirigirse_con, $telefonos, $horario, $atencion,
        $colonia, $localidad, $email;


    public function __construct($id = null, 
                                $nombre_comercial = null, 
                                $razon_social = null, 
                                $calle_numero = null,
                                $entre_calles = null, 
                                $dirigirse_con = null, 
                                $telefonos = null, 
                                $horario = null, 
                                $atencion = null,
                                $colonia = null, 
                                $localidad = null, 
                                $email = null){
        $this->setId($id);
        $this->setNombreComercial($nombre_comercial);
        $this->setRazonSocial($razon_social);
        $this->setCalleNumero($calle_numero);
        $this->setEntreCalles($entre_calles);
        $this->setDirigirseCon($dirigirse_con);
        $this->setTelefonos($telefonos);
        $this->setHorario($horario);
        $this->setAtencion($atencion);
        $this->setColonia($colonia);
        $this->setLocalidad($localidad);
        $this->setEmail($email);
    }
    public function setId($id)
    {
        if($id == null){
            $this->id = $id;
        }else{

            if(!preg_match('/[0-9]+/', $id) || intval($id) <= 0)
                throw new EntityException("Id de la empresa no es un valor númerico ". 
                "o su valor es menor o igual a cero.");
                 
            $this->id = $id;
        }
    }

    public function setNombreComercial($nombre_comercial)
    {
        
        if($nombre_comercial == null){
            $this->nombre_comercial = $nombre_comercial;
        }else{

            if(trim($nombre_comercial) === "" || 
            strlen($nombre_comercial) >= 255)
                throw new EntityException("El nombre comercial de la empresa no debe de estar vacío ".
                    "ni superar los 255 caracteres");
                 
            $this->nombre_comercial = $nombre_comercial;
        }
    }

    public function setRazonSocial($razon_social)
    {
        
        if($razon_social == null){
            $this->razon_social = $razon_social;
        }else{

            if(strlen($razon_social) >= 255)
                throw new EntityException("La razón social de la empresa no debe superar los 255 caracteres");
                 
            $this->razon_social = $razon_social;
        }
    }

    public function setCalleNumero($calle_numero)
    {
        
        if($calle_numero == null){
            $this->calle_numero = $calle_numero;
        }else{

            if(strlen($calle_numero) >= 255)
                throw new EntityException("La calle y número de la empresa no debe superar los 255 caracteres");
                 
            $this->calle_numero = $calle_numero;
        }
    }

    public function setEntreCalles($entre_calles)
    {
        if($entre_calles == null){
            $this->entre_calles = $entre_calles;
        }else{

            if(strlen($entre_calles) >= 255)
                throw new EntityException("Entre calles de la empresa no debe superar los 255 caracteres");
                 
            $this->entre_calles = $entre_calles;
        }
    }

    public function setDirigirseCon($dirigirse_con)
    {
        if($dirigirse_con == null){
            $this->dirigirse_con = $dirigirse_con;
        }else{

            if(strlen($dirigirse_con) >= 255)
                throw new EntityException('el campo "dirigirse con" de la empresa no debe superar los 255 caracteres');
                 
            $this->dirigirse_con = $dirigirse_con;
        }
    }

    public function setTelefonos($telefonos)
    {
        if($telefonos == null){
            $this->telefonos = $telefonos;
        }else{

            if(trim($telefonos) === "" || 
            strlen($telefonos) >= 255)
                throw new EntityException("Los teléfonos de la empresa no deben de estar vacíos ".
                    "ni superar los 255 caracteres");
                 
            $this->telefonos = $telefonos;
        }
    }

    public function setHorario($horario)
    {
        if($horario == null){
            $this->horario = $horario;
        }else{

            if(strlen($horario) >= 255)
                throw new EntityException('El horario de la empresa no debe superar los 255 caracteres');
                 
            $this->horario = $horario;
        }
    }

    public function setAtencion($atencion)
    {
        if($atencion == null){
            $this->atencion = $atencion;
        }else{

            if(strlen($atencion) >= 255)
                throw new EntityException('el campo "atención" de la empresa no debe superar los 255 caracteres');
                 
            $this->atencion = $atencion;
        }
    }

    public function setColonia($colonia)
    {
        if($colonia == null){
            $this->colonia = $colonia;
        }else{

            if(strlen($colonia) >= 255)
                throw new EntityException('La colonia de la empresa no debe superar los 255 caracteres');
                 
            $this->colonia = $colonia;
        }
    }

    public function setLocalidad($localidad)
    {
        
        if($localidad == null){

            $this->localidad = $localidad;        

        }else{

            if(strlen($localidad) >= 255)
                throw new EntityException('el campo "localidad" de la empresa no debe superar los 255 caracteres');
                 
            $this->localidad = $localidad;
        }
    }

    public function setEmail($email)
    {
        $this->email = $email;
        if($email == null){
            $this->email = $email;
        }else{

            if(strlen($email) >= 255)
                throw new EntityException('el campo "email" de la empresa no debe superar los 255 caracteres');
                 
            $this->email = $email;
        }
    }
    public function getId()
    {
        return $this->id;
    }

    public function getNombreComercial()
    {
        return $this->nombre_comercial;
    }

    public function getRazonSocial()
    {
        return $this->razon_social;
    }

    public function getCalleNumero()
    {
        return $this->calle_numero;
    }

    public function getEntreCalles()
    {
        return $this->entre_calles;
    }

    public function getDirigirseCon()
    {
        return $this->dirigirse_con;
    }

    public function getTelefonos()
    {
        return $this->telefonos;
    }

    public function getHorario()
    {
        return $this->horario;
    }

    public function getAtencion()
    {
        return $this->atencion;
    }

    public function getColonia()
    {
        return $this->colonia;
    }

    public function getLocalidad()
    {
        return $this->localidad;
    }

    public function getEmail()
    {
        return $this->email;
    }   
}