<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Core_model $core_model
 * @property ion_auth $ion_auth
 *
 */
class Formas extends CI_Controller
{
	/**
	 *9
	 * @use Core_model
	 *
	 * @param
	 * @return
	 */
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
			'titulo' => 'Formas de Pagamentos Cadastradas',
			'sub_titulo' => 'Chegou a Hora de listar as formas de pagamento cadastrados no banco de dados',
			'icone_view' => 'fa-regular fa-money-bill',


			'formas' => $this->core_model->get_all('formas_pagamentos'),
		);


		$this->load->view('layout/header', $data);
		$this->load->view('formas/index');
		$this->load->view('layout/footer');
	}


	/**
	 * @param $forma_pagamento_id
	 * @return void
	 */
	public function core($forma_pagamento_id = NULL): void
	{
		if (!$forma_pagamento_id) {

			//cadastrando usuario
			$this->form_validation->set_rules('forma_pagamento_nome', 'Nome Da Forma De Pagamento', 'trim|required|min_length[3]|max_length[30]|is_unique[formas_pagamentos.forma_pagamento_nome]');

			if ($this->form_validation->run()) {

				$data = elements(
					array(
						'forma_pagamento_nome',
						'forma_pagamento_ativa',
					), $this->input->post()
				);

				$data = html_escape($data);

				$this->core_model->insert('formas_pagamentos', $data,);
				redirect($this->router->fetch_class());


			} else {

				//erro de validaao

				$data = array(
					'titulo' => 'Formas De Pagamentos Cadastradas',
					'sub_titulo' => 'Chegou a Hora de Editar as formas de pagamento cadastrados',
					'icone_view' => 'fa-regular fa-money-bill',
				);

				$this->load->view('layout/header', $data);
				$this->load->view('formas/core');
				$this->load->view('layout/footer');
			}


		} else {

			if (!$this->core_model->get_by_id('formas_pagamentos', array('forma_pagamento_id' => $forma_pagamento_id))) {

				$this->session->set_flashdata('error', 'forma de pagamento nao encontrada');
				redirect($this->router->fetch_class());

			} else {

				//editando

				$this->form_validation->set_rules('forma_pagamento_nome', 'Nome', 'trim|required|min_length[3]|max_length[30]|callback_check_pagamento_nome');

				if ($this->form_validation->run()) {

					$data = elements(
						array(
							'forma_pagamento_nome',
							'forma_pagamento_ativa',
						), $this->input->post()
					);

					$data = html_escape($data);

					$this->core_model->update('formas_pagamentos', $data, array('forma_pagamento_id' => $forma_pagamento_id));
					redirect($this->router->fetch_class());


				} else {

					//erro de validaao

					$data = array(
						'titulo' => 'Formas De Pagamentos Cadastradas',
						'sub_titulo' => 'Chegou a Hora de Editar as formas de pagamento cadastrados',
						'icone_view' => 'fa-regular fa-money-bill',
						'forma' => $this->core_model->get_by_id('formas_pagamentos', array('forma_pagamento_id' => $forma_pagamento_id)),
					);

//					echo '<pre>';
//					print_r($data['forma']);
//					exit();

					$this->load->view('layout/header', $data);
					$this->load->view('formas/core');
					$this->load->view('layout/footer');
				}

			}


		}


	}

	public function check_pagamento_nome($forma_pagamento_nome)
	{
		$forma_pagamento_id = $this->input->post('forma_pagamento_id');

		if ($this->core_model->get_by_id('formas_pagamentos', array('forma_pagamento_nome' => $forma_pagamento_nome, 'forma_pagamento_id !=' => $forma_pagamento_id))) {
			$this->form_validation->set_message('check_pagamento_nome', 'forma de pagamento ja existe');

			return False;

		} else {

			return true;

		}

	}

	/**
	 * @param $forma_pagamento_id
	 * @return void
	 */
	public function del($forma_pagamento_id = NULL): void
	{

		if (!$this->core_model->get_by_id('formas_pagamentos', array('forma_pagamento_id' => $forma_pagamento_id))) {

			$this->session->set_flashdata('error', 'forma de pagamento nao encontrada');
			redirect($this->router->fetch_class());

			echo '<pre>';
			print_r($forma_pagamento_id);
			exit();


		} else {

			$this->core_model->delete('formas_pagamentos', array('forma_pagamento_id' => $forma_pagamento_id));
			redirect($this->router->fetch_class());


			echo '<pre>';
			print_r($forma_pagamento_id);
			exit();



		}

	}


}
