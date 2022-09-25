<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Core_model $core_model
 * @property ion_auth $ion_auth
 *
 */

class Home extends CI_controller
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
			'titulo' => 'Home'
		);

		$this->load->view('layout/header', $data);
		$this->load->view('home/index');
		$this->load->view('layout/footer');
	}

}
