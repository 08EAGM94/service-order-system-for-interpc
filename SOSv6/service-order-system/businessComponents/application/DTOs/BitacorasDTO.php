<?php

class BitacorasDTO{

    public $binnacle_id, $usuario_id, $contacto_id, $actividad, $servicio, $equipo_id, 
        $monto, $Actividades_realizadas, $observaciones, $inicio, $fin, $estatus, 
        $firma_cliente ,$visibilidad, $dates_type, $left_day, $right_day, $empresa_id, 
        $cancel_desc;
    public function __construct($binnacle_id = null, $usuario_id = null, 
        $contacto_id = null, $actividad = null, $servicio = null, $equipo_id = null, 
        $monto = null, $Actividades_realizadas = null, $observaciones = null, 
        $inicio = null, $fin = null, $estatus = null, $firma_cliente = null, 
        $visibilidad = null, $dates_type = null, $left_day = null, $right_day = null, 
        $empresa_id = null, $cancel_desc = null){

        $this->binnacle_id = $binnacle_id; 
        $this->usuario_id = $usuario_id; 
        $this->contacto_id = $contacto_id; 
        $this->actividad = $actividad; 
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
        $this->dates_type = $dates_type; 
        $this->left_day = $left_day; 
        $this->right_day = $right_day; 
        $this->empresa_id = $empresa_id; 
        $this->cancel_desc = $cancel_desc;
    }
}