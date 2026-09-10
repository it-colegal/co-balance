<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		if (! is_cli())
		{
			show_404();
		}

		$this->load->library('migration');
	}

	public function index()
	{
		if ($this->migration->latest() === FALSE)
		{
			show_error($this->migration->error_string(), 500);
		}

		echo "Migrations completed.\n";
	}
}
