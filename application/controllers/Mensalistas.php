<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Core_model $core_model
 * @property ion_auth $ion_auth
 *
 */
class Mensalistas extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			redirect('login');
		}

	}


	/**
	 * @return void
	 */
	public function index(): void
	{

		$data = array(
			'titulo' => 'Mensalistas cadastrados',
			'sub_titulo' => 'Chegou a Hora de listar mensalistas cadastrados no banco de dados',
			'titulo_tabela' => 'Listandor os Mensalistas',
			'icone_view' => 'fa-solid fa-users',

			'styles' => array(
				'plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css',

			),
			'scripts' => array(
				'plugins/datatables.net/js/jquery.dataTables.min.js',
				'plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
				'plugins/datatables.net/js/estacionamento.js',
				'plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js',


			),


			'mensalistas' => $this->core_model->get_all('mensalistas')
		);

//		echo '<pre>';
//		print_r($data['mensalistas']);
//		exit();


		$this->load->view('layout/header', $data);
		$this->load->view('mensalistas/index');
		$this->load->view('layout/footer');
	}

}
