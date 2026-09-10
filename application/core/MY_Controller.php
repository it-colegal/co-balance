<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	protected $current_user;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('auth_service');
		$this->current_user = $this->auth_service->user();
	}

	protected function require_auth()
	{
		if ($this->current_user)
		{
			return;
		}

		if ($this->input->method(TRUE) === 'GET')
		{
			$this->auth_service->remember_requested_url(current_url());
		}

		redirect('login');
		exit;
	}

	protected function require_permission($permission_code)
	{
		$this->require_auth();

		if ($this->auth_service->has_permission($permission_code))
		{
			return;
		}

		show_error('You do not have permission to access this page.', 403, 'Forbidden');
	}
}
