<?php

class ContactosDTO{

    public $contact_id, $empresa_id, $nombre_completo, $visibilidad;
    public function __construct($contact_id = null, $empresa_id = null, 
        $nombre_completo = null, $visibilidad = null){

        $this->contact_id = $contact_id; 
        $this->empresa_id = $empresa_id; 
        $this->nombre_completo = $nombre_completo; 
        $this->visibilidad = $visibilidad;
    }
}