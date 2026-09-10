<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	public function index()
	{
		if ($this->auth_service->check() && $this->auth_service->user())
		{
			redirect('dashboard');
			return;
		}

		redirect('login');
	}
}
