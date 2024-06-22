<?php

class Eventos extends CI_Model {

    public function getEvento($id = 0) {
        $this->db->select(['id', 'titulo', 'descripcion', 'fecha_inicio', 'fecha_fin', 'fecha_creacion']);
        $this->db->where(array(
            'id' => $id,
            'eliminado' => 0
        ));

        $query = $this->db->get('evento');

        return $query->row_array();
    }

    public function getEventos($limit = 0, $offset = 2) {
        $this->db->select(['id', 'titulo', 'descripcion', 'fecha_inicio', 'fecha_fin', 'fecha_creacion']);
        $this->db->where(array(
            'eliminado' => 0
        ));
        $this->db->limit($offset, $limit);

        $query = $this->db->get('evento');

        return $query->result_array();
    }

    public function actualizar($id = 0, $datos = []) {
        unset($datos['id']);

        $this->db->where(array(
            'id' => $id
        ));

        return $this->db->update('evento', $datos);
    }

    public function crear($datos = array()) {
        $datos['fecha_creacion'] = date('Y-m-d H:i:s');

        return $this->db->insert('evento', $datos);
    }

    public function borrar($id = 0) {
        $this->db->set(['eliminado' => 1, 'fecha_eliminacion' => date('Y-m-d H:i:s')]);

        $this->db->where(array(
            'id' => $id
        ));

        return $this->db->update('evento');
    }
    
    public function getTotalEventos() {
        return $this->db->count_all('evento');
    }
}
