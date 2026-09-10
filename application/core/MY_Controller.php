<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->library('auth_service');
    }

    protected function require_login()
    {
        if ($this->auth_service->is_logged_in())
        {
            return;
        }

        $intended_url = uri_string();

        if ($intended_url !== '' && $intended_url !== 'login' && $intended_url !== 'logout')
        {
            $this->session->set_userdata('auth_intended_url', $intended_url);
        }

        redirect('login');
    }

    protected function require_permission($permission)
    {
        $this->require_login();

        if ($this->auth_service->has_permission($permission))
        {
            return;
        }

        show_error('You do not have permission to access this page.', 403, 'Access denied');
    }
}
