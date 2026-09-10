<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_service {

    protected $CI;
    protected $current_user;
    protected $current_permissions;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('User_model');
    }

    public function attempt($identity, $password)
    {
        $identity = trim((string) $identity);
        $password = (string) $password;

        if ($identity === '' || $password === '')
        {
            return FALSE;
        }

        $user = $this->CI->User_model->find_by_identity($identity);

        if (empty($user) || empty($user->is_active) || ! password_verify($password, $user->password_hash))
        {
            return FALSE;
        }

        $this->login($user);

        return TRUE;
    }

    public function login($user)
    {
        $this->CI->session->sess_regenerate(TRUE);
        $this->CI->session->set_userdata(array(
            'auth_user_id' => (int) $user->id,
            'auth_logged_in' => TRUE,
        ));

        $this->current_user = NULL;
        $this->current_permissions = NULL;

        $this->CI->User_model->update_login_metadata((int) $user->id, $this->CI->input->ip_address());
    }

    public function logout()
    {
        $this->CI->session->unset_userdata(array('auth_user_id', 'auth_logged_in', 'auth_intended_url'));
        $this->CI->session->sess_regenerate(TRUE);
        $this->current_user = NULL;
        $this->current_permissions = NULL;
    }

    public function is_logged_in()
    {
        return $this->CI->session->userdata('auth_logged_in') === TRUE
            && (int) $this->CI->session->userdata('auth_user_id') > 0;
    }

    public function user()
    {
        if (! $this->is_logged_in())
        {
            return NULL;
        }

        if ($this->current_user !== NULL)
        {
            return $this->current_user;
        }

        $user = $this->CI->User_model->find((int) $this->CI->session->userdata('auth_user_id'));

        if (empty($user) || empty($user->is_active))
        {
            $this->logout();

            return NULL;
        }

        $user->roles = $this->CI->User_model->get_roles((int) $user->id);

        $this->current_user = $user;

        return $this->current_user;
    }

    public function has_permission($permission)
    {
        $permission = trim((string) $permission);

        if ($permission === '' || ! $this->is_logged_in())
        {
            return FALSE;
        }

        if ($this->user() === NULL)
        {
            return FALSE;
        }

        if ($this->current_permissions === NULL)
        {
            $this->current_permissions = $this->CI->User_model->get_permissions(
                (int) $this->CI->session->userdata('auth_user_id')
            );
        }

        return in_array($permission, $this->current_permissions, TRUE);
    }

    public function consume_intended_url()
    {
        $intended_url = (string) $this->CI->session->userdata('auth_intended_url');
        $this->CI->session->unset_userdata('auth_intended_url');

        if ($intended_url === '' || strpos($intended_url, '://') !== FALSE)
        {
            return '';
        }

        return $intended_url;
    }
}
