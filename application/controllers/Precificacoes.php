<?php


defined('BASEPATH') or exit('No direct script access allowed');


/**
 * @property Core_model $core_model
 * @property ion_auth $ion_auth
 */
class Precificacoes extends CI_Controller
{
	/**
	 *
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
			'titulo' => 'Preficicações cadastradas',
			'sub_titulo' => 'Chegou a Hora de listar as precificaçoes cadastrados no banco de dados',
			'icone_view' => 'fa-solid fa-dollar-sign',
			//'icone_view' => 'ik ik-settings',

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


	/**
	 * @param $precificacao_id
	 * @return void
	 */
	public function core($precificacao_id = NULL): void
	{

		if (!$precificacao_id) {
			//cadastrando

		} else {

			//atualizando


			if (!$this->core_model->get_by_id('precificacoes', array('precificacao_id' => $precificacao_id))) {

				$this->session->set_flashdata('error', 'precificação nao encontrada');
				redirect($this->router->fetch_class());

			} else {

				//$this->form_validation->set_rules('precificacao_categoria', 'Categoria', 'trim|required|min_length[3]|max_length[30]|callback_check_categoria');
				$this->form_validation->set_rules('precificacao_categoria', 'Categoria', 'trim|required|min_length[3]|max_length[30]');
				$this->form_validation->set_rules('precificacao_valor_hora', 'Valor Hora', 'trim|required');
				$this->form_validation->set_rules('precificacao_valor_mensalidade', 'Valor Mensalidade', 'trim|required');
				$this->form_validation->set_rules('precificacao_numero_vagas', 'Numero Vagas', 'trim|required|max_length[50]|integer|greater_than[0]');

				if ($this->form_validation->run()) {
					$precificacao_ativa = $this->input->post('precificacao_ativa');

					if ($precificacao_ativa == 0) {

						if ($this->db->table_exists('estacionar')) {

							if ($this->core_model->get_by_id('estacionar', array('estacionar_precificacao_id' => $precificacao_id, 'estacionar_status' => 0))) {

								$this->session->set_flashdata('error', 'Esta categoria esta sendo ultilizada em estacionar');
								redirect($this->router->fetch_class());

							}
						}
					}

					$data = elements(
						array(
							'precificacao_categoria',
							'precificacao_valor_hora',
							'precificacao_valor_mensalidade',
							'precificacao_numero_vagas',
							'precificacao_ativa',
						), $this->input->post()
					);

					$data = html_escape($data);

					$this->core_model->update('precificacoes', $data, array('precificacao_id' => $precificacao_id));
					redirect($this->router->fetch_class());

				} else {

					// erro de validação
					$data = array(
						'titulo' => 'Editar precificação',
						'sub_titulo' => 'Chegou a Hora de editar a preficação',
						'icone_view' => 'fa-solid fa-dollar-sign',

						'styles' => array(
							'plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css',

						),
						'scripts' => array(
							'plugins/datatables.net/js/jquery.dataTables.min.js',
							'plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
							'plugins/datatables.net/js/estacionamento.js',
							'plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js',
							'plugins/mask/jquery.mask.min.js',
							'plugins/mask/custom.js',

						),

						'precificacao' => $this->core_model->get_by_id('precificacoes', array('precificacao_id' => $precificacao_id)), // get all users
					);

//				echo '<pre>';
//				print_r($data['precificacao']);
//				exit();


					$this->load->view('layout/header', $data);
					$this->load->view('precificacoes/core');
					$this->load->view('layout/footer');

				}


			}

		}


	}

	/**
	 * @param $precificacao_categoria
	 * @return bool
	 */
	public function check_categoria($precificacao_categoria): bool{



		$precificacao_id = $this->input->post('precificacao_id');

		if ($this->core_model->get_by_id('precificacoes', array('precificacao_categoria' => $precificacao_categoria, 'precificacao_id !=' => $precificacao_id))) {

			$this->form_validation->set_message('check_categoria', 'Essa categoria ja existe');


			return FALSE;

		} else {

			return TRUE;
		}


	}

	/**
	 * @param $precificacao_id
	 * @return void
	 */
	public function del($precificacao_id = NULL): void
	{
		if (!$this->core_model->get_by_id('precificacoes', array('precificacao_id' => $precificacao_id))) {

			$this->session->set_flashdata('error', 'precificação nao encontrada');
			redirect($this->router->fetch_class());

		}

		if ($this->core_model->get_by_id('precificacoes', array('precificacao_id' => $precificacao_id, 'precificacao_ativa' => 1))) {

			$this->session->set_flashdata('error', 'precificação Ativa nao pode ser excluida');
			redirect($this->router->fetch_class());

		}

//		echo '<pre>';
//		print_r($precificacao_id);
//		exit();

		$this->core_model->delete('precificacoes', array('precificacao_id' => $precificacao_id));
		redirect($this->router->fetch_class());


	}

}
