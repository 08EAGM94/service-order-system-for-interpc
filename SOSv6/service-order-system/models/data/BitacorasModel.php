<?php

class BitacorasModel{

    public $id, $usuario_id, $contacto_id, $servicio, $equipo_id, $monto, 
        $Actividades_realizadas, $observaciones, $inicio, $fin, $estatus, 
        $firma_cliente ,$visibilidad;

    public function __construct($id = null, $usuario_id = null, $contacto_id = null,
            $servicio = null, $equipo_id = null, $monto = null,
            $Actividades_realizadas = null, $observaciones = null, $firma_cliente = null, 
            $estatus = null, $inicio = null, $fin = null,  $visibilidad = null) {
        $this->id = $id;
        $this->usuario_id = $usuario_id;
        $this->contacto_id = $contacto_id;
        $this->servicio = $servicio;
        $this->equipo_id = $equipo_id;
        $this->monto = $monto;
        $this->Actividades_realizadas = $Actividades_realizadas;
        $this->observaciones = $observaciones;
        $this->inicio = $inicio;
        $this->fin = $fin;
        $this->estatus = $estatus;
        $this->firma_cliente = $firma_cliente;
        $this->visibilidad = $visibilidad;
    }
}