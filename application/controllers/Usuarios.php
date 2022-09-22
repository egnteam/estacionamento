<?php

defined('BASEPATH') or exit('No direct script access allowed');


class Usuarios extends CI_Controller
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
			'titulo' => 'Usuarios cadastrados',
			'sub_titulo' => 'Chegou a Hora de listar usuarios cadastrados no banco de dados',
			'titulo_tabela' => 'Listandor os usuários',

			'styles' => array(
				'plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css',

			),
			'scripts' => array(
				'plugins/datatables.net/js/jquery.dataTables.min.js',
				'plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
				'plugins/datatables.net/js/estacionamento.js',
				'plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js',


			),


			'usuarios' => $this->ion_auth->users()->result() // get all users
		);

//		echo '<pre>';
//		print_r($data['usuarios']);
//		exit();


		$this->load->view('layout/header', $data);
		$this->load->view('usuarios/index');
		$this->load->view('layout/footer');
	}

	public function core($usuario_id = Null)
	{

		if (!$usuario_id) {

			//cadastrar usuario

			$this->form_validation->set_rules('first_name', 'Nome', 'trim|required|min_length[5]|max_length[20]');
			$this->form_validation->set_rules('last_name', 'Sobrenome', 'trim|required|min_length[3]|max_length[20]');
			$this->form_validation->set_rules('username', 'Usuario', 'trim|required|min_length[3]|max_length[20]|is_unique[users.username]');
			$this->form_validation->set_rules('email', 'E-mail', 'trim|valid_email|required|min_length[5]|max_length[50]|is_unique[users.email]');
			$this->form_validation->set_rules('password', 'Senha', 'trim|min_length[8]|required');
			$this->form_validation->set_rules('confirmacao', 'Confirmacao', 'trim|matches[password]|required');


			if ($this->form_validation->run()) {

				/*
				[first_name] => lucio
				[last_name] => souza
				[username] => lucio_souza
				[email] => lucio@gmail.com
				[password] => 12345678
				[confirmacao] => 12345678
				[perfil] => 2
				[active] => 0
							*/

				$username = html_escape($this->input->post('username'));
				$password = html_escape($this->input->post('password'));
				$email = $this->input->post('email');
				$additional_data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'active' => $this->input->post('active'),
				);
				$group = array($this->input->post('perfil'));

				$adicional_data = html_escape($additional_data);

				if ($this->ion_auth->register($username, $password, $email, $additional_data, $group)) {

					$this->session->set_flashdata('sucesso', 'Dados Salvos com sucesso');

				} else {

					$this->session->set_flashdata('error', 'erro ao cadastrar usuario');
				}
				redirect($this->router->fetch_class());

			} else {


				$data = array(
					'titulo' => 'Cadastrar Usuário',
					'sub_titulo' => 'Chegou a Hora de Cadastro novo  Usuario',
					'icone_view' => 'ik ik-user',

				);

//				echo '<pre>';
//				print_r($data['perfil_usuario']);
//				exit();


				$this->load->view('layout/header', $data);
				$this->load->view('usuarios/core');
				$this->load->view('layout/footer');


			}

		} else {

			//editar usuario
			if (!$this->ion_auth->user($usuario_id)->row()) {

				exit('usuario não existe');

			} else {
				//editar usuario

				$perfil_atual = $this->ion_auth->get_users_groups($usuario_id)->row();

				$this->form_validation->set_rules('first_name', 'Nome', 'trim|required|min_length[5]|max_length[20]');
				$this->form_validation->set_rules('last_name', 'Sobrenome', 'trim|required|min_length[5]|max_length[20]');
				$this->form_validation->set_rules('username', 'Usuário', 'trim|required|min_length[5]|max_length[20]|callback_username_check');
				$this->form_validation->set_rules('email', 'E-mail', 'trim|valid_email|required|min_length[5]|max_length[50]|callback_email_check');
				$this->form_validation->set_rules('password', 'Senha', 'trim|min_length[8]');
				$this->form_validation->set_rules('confirmacao', 'Confirmacao', 'trim|matches[password]');


				if ($this->form_validation->run()) {


					$data = elements(
						array(
							'first_name',
							'last_name',
							'username',
							'email',
							'password',
							'active',

						), $this->input->post()
					);

					$password = $this->input->post('password');

					if (!$password) {
						unset($data['password']);
					}

					$data = html_escape($data);

					if ($this->ion_auth->update($usuario_id, $data)) {

						$perfil_post = $this->input->post('perfil');

						//se for diferente atual a grupo
						if ($perfil_atual->id != $perfil_post) {

							$this->ion_auth->remove_from_group($perfil_atual->id, $usuario_id);

							$this->ion_auth->add_to_group($perfil_post, $usuario_id);
						}

						$this->session->set_flashdata('sucesso', 'Dados Atualizados com sucesso');

					} else {

						$this->session->set_flashdata('error', 'Não foi posssivel Atualizar os dados');

					}
					redirect($this->router->fetch_class());

				} else {
					//erro de validaçao

//					echo '<pre>';
//					print_r($perfil_atual);
//					exit();

					$data = array(
						'titulo' => 'Editar Usuário',
						'sub_titulo' => 'Chegou a Hora de Editar o Usuario',
						'icone_view' => 'ik ik-user',
						'usuario' => $this->ion_auth->user($usuario_id)->row(), // get all users
						'perfil_usuario' => $this->ion_auth->get_users_groups($usuario_id)->row()
					);

//				echo '<pre>';
//				print_r($data['perfil_usuario']);
//				exit();


					$this->load->view('layout/header', $data);
					$this->load->view('usuarios/core');
					$this->load->view('layout/footer');
				}


			}
		}
	}

	public function username_check($username)
	{
		$usuario_id = $this->input->post('usuario_id');

		if ($this->core_model->get_by_id('users', array('username' => $username, 'id !=' => $usuario_id))) {
			$this->form_validation->set_message('username_check', 'Esse usuario ja existe');
			return false;

		} else {
			return true;
		}

	}

	public function email_check($email)
	{
		$usuario_id = $this->input->post('usuario_id');

		if ($this->core_model->get_by_id('users', array('email' => $email, 'id !=' => $usuario_id))) {
			$this->form_validation->set_message('username_check', 'Esse Email ja existe');
			return false;

		} else {
			return true;
		}

	}

	public function del($usuario_id = NULL)
	{
		if (!$usuario_id || !$this->core_model->get_by_id('users', array('id' => $usuario_id))) {

			$this->session->set_flashdata('error', 'usuario nao encontrado');
			redirect($this->router->fetch_class());
		} else {

			//deleta
			if ($this->ion_auth->is_admin($usuario_id)) {
				$this->session->set_flashdata('error', 'Administrador nao pode ser excluido');
				redirect($this->router->fetch_class());
			}

			if ($this->ion_auth->delete_user($usuario_id)) {
				$this->session->set_flashdata('sucesso', 'usuario excluido com sucesso');

			} else {

				$this->session->set_flashdata('error', 'não foi possivel excluir o usuario');
			}

			redirect($this->router->fetch_class());

		}

	}

}
