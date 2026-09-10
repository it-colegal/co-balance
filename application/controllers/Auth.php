<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'form_validation', 'auth_service'));
        $this->load->helper(array('url', 'form'));
    }

    public function login()
    {
        if ($this->auth_service->check()) {
            redirect('dashboard');
        }

        if ($this->input->method(TRUE) === 'POST') {
            $this->form_validation->set_rules('identity', 'Username or Email', 'trim|required|max_length[190]');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() && $this->auth_service->login(
                $this->input->post('identity', TRUE),
                $this->input->post('password', FALSE)
            )) {
                redirect('dashboard');
            }

            $this->session->set_flashdata('error', 'Invalid login credentials.');
            redirect('login');
        }

        $this->load->view('auth/login');
    }

    public function logout()
    {
        $this->auth_service->logout();
        redirect('login');
    }
}
