<?php

defined('BASEPATH' or exit('ação nao permitida'));

/**
 * @property Core_model $core_model
 * @property ion_auth $ion_auth
 *
 */

class Sistema extends CI_Controller
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

		$this->form_validation->set_rules('sistema_razao_social', 'Razão Social', 'trim|required|min_length[5]|max_length[50]');
		$this->form_validation->set_rules('sistema_nome_fantasia', 'Nome fantasia', 'trim|required|min_length[5]|max_length[20]');
		$this->form_validation->set_rules('sistema_cnpj', 'CNPJ', 'trim|required|exact_length[18]');
		$this->form_validation->set_rules('sistema_ie', 'inscricao estadual', 'trim|required|min_length[5]|max_length[20]');
		$this->form_validation->set_rules('sistema_telefone_fixo', 'Telefone Fixo', 'trim|required|min_length[14]|max_length[15]');
		$this->form_validation->set_rules('sistema_telefone_movel', 'Telefone Movel', 'trim|required|min_length[14]|max_length[15]');
		$this->form_validation->set_rules('sistema_cep', 'cep', 'trim|required|exact_length[9]');
		$this->form_validation->set_rules('sistema_endereco', 'Endereço', 'trim|required|min_length[5]|max_length[30]');
		$this->form_validation->set_rules('sistema_numero', 'Numero', 'trim|required|min_length[1]|max_length[10]');
		$this->form_validation->set_rules('sistema_cidade', 'Cidade', 'trim|required|min_length[4]|max_length[20]');
		$this->form_validation->set_rules('sistema_estado', 'Estado', 'trim|required|exact_length[2]');
		$this->form_validation->set_rules('sistema_site_url', 'Url Site', 'trim|required|valid_url|max_length[50]');
		$this->form_validation->set_rules('sistema_email', 'Email de contato', 'trim|required|valid_email|max_length[50]');
		$this->form_validation->set_rules('sistema_texto_ticket', 'texto do ticket estacionamentos', 'trim|max_length[500]');

		if ($this->form_validation->run()) {


			// validaão bem sucedida

			$data = elements(
				array(
					'sistema_razao_social',
					'sistema_nome_fantasia',
					'sistema_cnpj',
					'sistema_ie',
					'sistema_telefone_fixo',
					'sistema_telefone_movel',
					'sistema_cep',
					'sistema_endereco',
					'sistema_numero',
					'sistema_cidade',
					'sistema_estado',
					'sistema_site_url',
					'sistema_email',
					'sistema_texto_ticket',

				), $this->input->post()
			);

			$data = html_escape($data);

			$this->core_model->update('sistema',$data, array('sistema_id'=> 1));

			redirect($this->router->fetch_class());


		} else {

			//erro devalidacão

			$data = array(
				'titulo' => 'Editar informaçoes do sistema',
				'sub_titulo' => 'Chegou a Hora de Editar informaçoes do sistema',
				'icone_view' => 'ik ik-settings',

				'scripts' => array(

					'plugins/mask/jquery.mask.min.js',
					'plugins/mask/custom.js',

				),

				'sistema' => $this->core_model->get_by_id('sistema', array('sistema_id' => 1)),


			);


			$this->load->view('layout/header', $data);
			$this->load->view('sistema/index');
			$this->load->view('layout/footer');


		}


	}


}
