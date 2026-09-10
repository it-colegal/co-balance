<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->require_permission('auth.access_dashboard');
	}

	public function index()
	{
		$this->load->view('dashboard/index', array(
			'user' => $this->current_user,
		));
	}
}
