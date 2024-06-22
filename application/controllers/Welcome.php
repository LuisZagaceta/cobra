<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('eventos');
    }

    public function getEventos() {
        $pagina_actual = (int) $this->input->get('page', TRUE);
        $pagina_actual = ($pagina_actual > 0) ? $pagina_actual : 1;
        $pagina_items = 10; //items por pagina
        $limit = ($pagina_actual - 1) * $pagina_items;
        $eventos = $this->eventos->getEventos($limit, $pagina_items);
        $total = $this->eventos->getTotalEventos();

        $this->output->set_content_type('application/json');
        return $this->output->set_output(json_encode(['eventos' => $eventos, 'total' => $total, 'pag_actual' => $pagina_actual, 'pag_items' => $pagina_items]));
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function editarCrear($id = 0) {
        $datos = [];

        if (intval($id) > 0) {
            $datos = $this->eventos->getEvento(intval($id));
        }

        $this->load->view('crear-editar-modal', ['datos' => $datos]);
    }

    public function guardar() {
        $datos = $this->input->post('evento', TRUE);

        $id = intval($datos['id']);

        if ($id > 0) {
            $this->eventos->actualizar($id, $datos);
        } else {
            unset($datos['id']);
            $this->eventos->crear($datos);
        }

        $this->output->set_content_type('application/json');
        return $this->output->set_output(json_encode(['success' => TRUE]));
    }

    public function borrar($id = 0) {
        $id = (int) $this->input->post('id', TRUE);

        $this->eventos->borrar($id);

        $this->output->set_content_type('application/json');
        return $this->output->set_output(json_encode(['success' => TRUE]));
    }
}
