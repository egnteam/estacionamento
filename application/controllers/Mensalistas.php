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


		$this->load->view('layout/header', $data);
		$this->load->view('mensalistas/index');
		$this->load->view('layout/footer');
	}

	/**
	 * @param $mensalista_id
	 * @return void
	 */
	public function core($mensalista_id = NULL): void
	{

		if (!$mensalista_id) {

			//cadastrando

		} else {

			if (!$this->core_model->get_by_id('mensalistas', array('mensalista_id' => $mensalista_id))) {
				$this->session->set_flashdata('error', 'Mensalista não encontrando');
				redirect($this->router->fetch_class());


			} else {


				$this->form_validation->set_rules('mensalista_nome', 'Nome', 'trim|required|min_length[4]|max_length[20]');
				$this->form_validation->set_rules('mensalista_nome', 'Nome', 'trim|required|min_length[5]|max_length[20]');


				/**
				 * mensalista_id] => 1
				 * [mensalista_data_cadastro] => 2020-03-13 19:00:02
				 * [mensalista_nome] => Lucio
				 * [mensalista_sobrenome] => Souza
				 * [mensalista_data_nascimento] => 2020-03-13
				 * [mensalista_cpf] => 359.731.420-19
				 * [mensalista_rg] => 334.44644-12
				 * [mensalista_email] => lucio@gmail.com
				 * [mensalista_telefone_fixo] =>
				 * [mensalista_telefone_movel] => (41) 9999-9999
				 * [mensalista_cep] => 80530-000
				 * [mensalista_endereco] => Rua de Curitiba
				 * [mensalista_numero_endereco] => 45
				 * [mensalista_bairro] => Centro
				 * [mensalista_cidade] => Curitiba
				 * [mensalista_estado] => PR
				 * [mensalista_complemento] =>
				 * [mensalista_ativo] => 1
				 * [mensalista_dia_vencimento] => 31
				 * [mensalista_obs] => *
				 */

				if ($this->form_validation->run()) {

					echo '<pre>';
					print_r($this->input->post());
					exit();


				} else {
					//erro de validação

					$data = array(
						'titulo' => 'Editar cadastrados',
						'sub_titulo' => 'Chegou a Hora de editar o mensalistas cadastrados no banco de dados',
						'titulo_tabela' => 'Listandor os Mensalistas',
						'icone_view' => 'fa-solid fa-users',

						'scripts' => array(

							'plugins/mask/jquery.mask.min.js',
							'plugins/mask/custom.js',
						),


						'mensalista' => $this->core_model->get_by_id('mensalistas', array('mensalista_id' => $mensalista_id))
					);

//		echo '<pre>';
//		print_r($data['mensalista']);
//		exit();


					$this->load->view('layout/header', $data);
					$this->load->view('mensalistas/core');
					$this->load->view('layout/footer');

				}
			}
		}
	}
}
