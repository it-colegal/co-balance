<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('auth_service', 'form_validation', 'session'));
	}

	public function index()
	{
		$this->login();
	}

	public function login()
	{
		if ($this->auth_service->check() && $this->auth_service->user())
		{
			redirect('dashboard');
			return;
		}

		$this->load->view('auth/login');
	}

	public function authenticate()
	{
		if ($this->input->method(TRUE) !== 'POST')
		{
			show_404();
		}

		$this->form_validation->set_rules('identity', 'Username or Email', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('auth/login');
			return;
		}

		$identity = trim((string) $this->input->post('identity', TRUE));
		$password = (string) $this->input->post('password', FALSE);

		if (! $this->auth_service->attempt($identity, $password))
		{
			$this->session->set_flashdata('error', 'Invalid username/email or password.');
			redirect('login');
			return;
		}

		redirect($this->auth_service->redirect_target('dashboard'));
	}

	public function logout()
	{
		if ($this->input->method(TRUE) !== 'POST')
		{
			show_404();
		}

		$this->auth_service->logout();
		redirect('login');
	}
}
