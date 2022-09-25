<?php

defined('BASEPATH' or exit('ação nao permitida'));
/**
 * @property Core_model $core_model
 * @property ion_auth $ion_auth
 *
 */

class Login extends CI_Controller
{

	/**
	 * @return void
	 */
	public function index(): void
	{
		$data = array(


			'titulo' => 'Login',
		);

		$this->load->view('layout/header', $data);
		$this->load->view('login/index');
		$this->load->view('layout/footer');

	}

	/**
	 * @return void
	 */
	public function auth(): void
	{
		$identity = html_escape($this->input->post('email'));
		$password = html_escape($this->input->post('password'));
		$remember = FALSE; // remember the user
		if ($this->ion_auth->login($identity, $password, $remember)) {

			$usuario = $this->core_model->get_by_id('users',array('email'=> $identity));

			$this->session->set_flashdata('sucesso','Seja Muito bem vinda(o) '. $usuario->first_name);
			redirect('/');

		}else{
			$this->session->set_flashdata('error','verifique seu email ou senha');
			redirect($this->router->fetch_class());
		}

	}

	/**
	 * @return void
	 */
	public function logout(): void
	{

		$this->ion_auth->logout();
		redirect($this->router->fetch_class());


	}


}
