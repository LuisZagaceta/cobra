<?php

class Eventos extends CI_Model {

    public function crear($datos = array()) {
        return $this->db->insert('eventos', $datos);
    }
}
