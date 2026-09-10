<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

    public function system()
    {
        $this->require_permission('system.admin.access');

        $this->load->view('admin/system', array(
            'user' => $this->auth_service->user(),
        ));
    }
}
