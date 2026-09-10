<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('auth_service');
    }

    protected function require_login()
    {
        if (!$this->auth_service->check()) {
            redirect('login');
        }
    }

    protected function require_permission($permission_slug)
    {
        $this->require_login();

        if (!$this->auth_service->has_permission($permission_slug)) {
            show_error('Forbidden', 403);
        }
    }
}
