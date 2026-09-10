<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->library('auth_service');
    }

    public function login()
    {
        if ($this->auth_service->is_logged_in())
        {
            redirect('dashboard');
        }

        if ($this->input->method(TRUE) === 'POST')
        {
            $this->form_validation->set_rules('identity', 'Username or email', 'trim|required|max_length[191]');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() && $this->auth_service->attempt(
                $this->input->post('identity', TRUE),
                $this->input->post('password', FALSE)
            ))
            {
                $redirect_to = $this->auth_service->consume_intended_url();

                redirect($redirect_to !== '' ? $redirect_to : 'dashboard');
            }

            $this->session->set_flashdata('auth_error', 'Invalid credentials or inactive account.');
        }

        $this->load->view('auth/login');
    }

    public function logout()
    {
        if ($this->auth_service->is_logged_in())
        {
            $this->auth_service->logout();
        }

        $this->session->set_flashdata('auth_message', 'You have been logged out.');

        redirect('login');
    }
}
