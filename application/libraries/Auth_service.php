<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_service
{
    protected $ci;
    protected $session_key = 'auth_user';

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->model('User_model', 'user_model');
    }

    public function login($identity, $password)
    {
        $identity = trim((string) $identity);
        $password = (string) $password;

        if ($identity === '' || $password === '') {
            return FALSE;
        }

        $user = $this->ci->user_model->find_active_by_identity($identity);
        if (empty($user) || empty($user['password_hash']) || !password_verify($password, $user['password_hash'])) {
            return FALSE;
        }

        $this->ci->session->sess_regenerate(TRUE);

        $auth_user = array(
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'roles' => $this->ci->user_model->get_roles($user['id']),
            'permissions' => $this->ci->user_model->get_permissions($user['id']),
        );

        $this->ci->session->set_userdata($this->session_key, $auth_user);

        return TRUE;
    }

    public function logout()
    {
        $this->ci->session->unset_userdata($this->session_key);
        $this->ci->session->sess_regenerate(TRUE);
    }

    public function user()
    {
        $user = $this->ci->session->userdata($this->session_key);
        return is_array($user) ? $user : NULL;
    }

    public function check()
    {
        return $this->user() !== NULL;
    }

    public function has_role($role_slug)
    {
        $user = $this->user();
        return $user !== NULL && in_array($role_slug, $user['roles'], TRUE);
    }

    public function has_permission($permission_slug)
    {
        $user = $this->user();
        return $user !== NULL && in_array($permission_slug, $user['permissions'], TRUE);
    }
}
