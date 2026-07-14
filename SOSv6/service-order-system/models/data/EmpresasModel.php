<?php

class EmpresasModel{

    public  $id, $nombre_comercial, $razon_social, $calle_numero, $entre_calles,
            $dirigirse_con, $telefonos, $horario, $atencion, $colonia,
            $localidad, $email, $visibilidad;
    

    public function __construct($id = null, $nombre_comercial = null, $razon_social = null,
            $calle_numero = null, $entre_calles = null, $dirigirse_con = null,
            $telefonos = null, $horario = null, $atencion = null, $colonia = null,
            $localidad = null, $email = null, $visibilidad = null) {
        $this->id = $id;
        $this->nombre_comercial = $nombre_comercial;
        $this->razon_social = $razon_social;
        $this->calle_numero = $calle_numero;
        $this->entre_calles = $entre_calles;
        $this->dirigirse_con = $dirigirse_con;
        $this->telefonos = $telefonos;
        $this->horario = $horario;
        $this->atencion = $atencion;
        $this->colonia = $colonia;
        $this->localidad = $localidad;
        $this->email = $email;
        $this->visibilidad = $visibilidad;
    }
}