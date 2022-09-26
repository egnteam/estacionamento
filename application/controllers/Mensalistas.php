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
				$this->form_validation->set_rules('mensalista_sobrenome', 'Sobrenome', 'trim|required|min_length[4]|max_length[20]');
				$this->form_validation->set_rules('mensalista_data_nascimento', 'Data de Nascimento', 'required');
				$this->form_validation->set_rules('mensalista_cpf', 'CPF', 'trim|required|exact_length[14]|callback_valida_cpf');
				$this->form_validation->set_rules('mensalista_rg', 'Numero RG', 'trim|required|min_length[10]|max_length[14]|callback_check_rg');
				$this->form_validation->set_rules('mensalista_email', 'Email', 'trim|required|valid_email|min_length[10]|max_length[30]|callback_check_email');
				$this->form_validation->set_rules('mensalista_telefone_fixo', 'Telefone fixo', 'trim|min_length[12]|max_length[15]|callback_check_telefone_fixo');
				$this->form_validation->set_rules('mensalista_telefone_movel', 'Telefone Movel', 'trim|min_length[12]|max_length[15]|callback_check_telefone_movel');

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

	/**
	 * @param $cpf
	 * @return bool
	 */
	public function valida_cpf($cpf): bool
	{

		if ($this->input->post('mensalista_id')) {

			$mensalista_id = $this->input->post('mensalista_id');

			if ($this->core_model->get_by_id('mensalistas', array('mensalista_id !=' => $mensalista_id, 'mensalista_cpf' => $cpf))) {
				$this->form_validation->set_message('valida_cpf', 'O campo {field} já existe, ele deve ser único');
				return FALSE;
			}
		}

		$cpf = str_pad(preg_replace('/[^0-9]/', '', $cpf), 11, '0', STR_PAD_LEFT);
		// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
		if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {

			$this->form_validation->set_message('valida_cpf', 'Por favor digite um CPF válido');
			return FALSE;
		} else {
			// Calcula os números para verificar se o CPF é verdadeiro
			for ($t = 9; $t < 11; $t++) {
				for ($d = 0, $c = 0; $c < $t; $c++) {
					$d += $cpf[$c] * (($t + 1) - $c);
				}
				$d = ((10 * $d) % 11) % 10;
				if ($cpf[$c] != $d) {
					$this->form_validation->set_message('valida_cpf', 'Por favor digite um CPF válido');
					return FALSE;
				}
			}
			return TRUE;
		}
	}


	/**
	 * @param $mensalista_rg
	 * @return bool
	 */
	public function check_rg($mensalista_email): bool
	{
		$mensalista_id = $this->input->post('$mensalista_id');


		if ($this->core_model->get_by_id('mensalistas', array('mensalista_id !=' => $mensalista_id, 'mensalista_rg' => $mensalista_id))) {
			$this->form_validation->set_message('check_rg', 'O campo {field} já existe, ele deve ser único');

			return FALSE;

		} else {

			return TRUE;
		}


	}


	/**
	 * @param $mensalista_email
	 * @return bool
	 */
	public function check_email($mensalista_email): bool
	{
		$mensalista_id = $this->input->post('$mensalista_id');


		if ($this->core_model->get_by_id('mensalistas', array('mensalista_id !=' => $mensalista_email, 'mensalista_email' => $mensalista_id))) {
			$this->form_validation->set_message('check_email', 'O campo {field} já existe, ele deve ser único');

			return FALSE;

		} else {

			return TRUE;
		}


	}

	/**
	 * @param $mensalista_telefone_fixo
	 * @return bool
	 */
	public function check_telefone_fixo($mensalista_telefone_fixo): bool
	{
		$mensalista_id = $this->input->post('$mensalista_id');


		if ($this->core_model->get_by_id('mensalistas', array('mensalista_id !=' => $mensalista_id, 'mensalista_telefone_fixo' => $mensalista_telefone_fixo))) {
			$this->form_validation->set_message('check_telefone_fixo', 'O campo {field} já existe, ele deve ser único');

			return FALSE;

		} else {

			return TRUE;
		}


	}

	/**
	 * @param $mensalista_telefone_movel
	 * @return bool
	 */
	public function check_telefone_movel($mensalista_telefone_movel): bool
	{
		$mensalista_id = $this->input->post('$mensalista_id');


		if ($this->core_model->get_by_id('mensalistas', array('mensalista_id !=' => $mensalista_id, 'mensalista_telefone_movel' => $mensalista_telefone_movel))) {
			$this->form_validation->set_message('check_telefone_movel', 'O campo {field} já existe, ele deve ser único');

			return FALSE;

		} else {

			return TRUE;
		}


	}


}
