<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Precificacoes extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			redirect('login');
		}

	}


	public function index()
	{

		$data = array(
			'titulo' => 'Preficicações cadastradas',
			'sub_titulo' => 'Chegou a Hora de listar as precificaçoes cadastrados no banco de dados',
			'icone_view' => 'fa-sharp fa-solid fa-dollar-sign',

			'styles' => array(
				'plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css',

			),
			'scripts' => array(
				'plugins/datatables.net/js/jquery.dataTables.min.js',
				'plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
				'plugins/datatables.net/js/estacionamento.js',
				'plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js',


			),


			'precificacoes' => $this->core_model->get_all('precificacoes'),   // get all users
		);

//		echo '<pre>';
//		print_r($data['precificacoes']);
//		exit();


		$this->load->view('layout/header', $data);
		$this->load->view('precificacoes/index');
		$this->load->view('layout/footer');
	}

}
