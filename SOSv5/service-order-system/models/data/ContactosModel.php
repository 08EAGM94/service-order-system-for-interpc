<?php

class ContactosModel{

    public $id, $empresa_id, $nombre_completo, $visibilidad;
    
    public function __construct($id = null, $empresa_id = null, $nombre_completo = null,
            $visibilidad = null) {
        $this->id = $id;
        $this->empresa_id = $empresa_id;
        $this->nombre_completo = $nombre_completo;
        $this->visibilidad = $visibilidad;        
    }
}