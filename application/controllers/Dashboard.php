<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function index()
    {
        $this->require_login();

        $this->load->view('dashboard/index', array(
            'user' => $this->auth_service->user(),
        ));
    }
}
