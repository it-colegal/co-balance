<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model', 'auth');
        $this->load->library('session');
        $this->load->helper(['url', 'security']);
    }

    public function login()
    {
        if ($this->session->userdata('is_logged_in')) {
            return redirect('dashboard');
        }

        if ($this->input->method() === 'post') {
            $identity = trim($this->input->post('identity', true));
            $password = (string)$this->input->post('password', false);

            $ip = $this->input->ip_address();
            $ua = substr((string)$this->input->user_agent(), 0, 500);

            $user = $this->auth->find_user($identity);

            if (!$user || !password_verify($password, $user['password_hash'])) {
                $this->auth->write_login_history($user['id'] ?? null, false, 'invalid_credentials', $ip, $ua);
                $this->session->set_flashdata('error', 'Username/email atau password salah.');
                return redirect('auth/login');
            }

            $roles = $this->auth->get_user_roles($user['id']);
            $perms = $this->auth->get_user_permissions($user['id']);

            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', time() + 7200);

            $this->auth->create_user_session($user['id'], hash('sha256', $token), $expiresAt, $ip, $ua);
            $this->auth->update_last_login($user['id']);
            $this->auth->write_login_history($user['id'], true, null, $ip, $ua);

            $this->session->set_userdata([
                'is_logged_in' => true,
                'user_id' => (int)$user['id'],
                'username' => $user['username'],
                'full_name' => $user['full_name'],
                'email' => $user['email'],
                'roles' => $roles,
                'permissions' => $perms,
                'api_session_token' => $token
            ]);

            return redirect('dashboard');
        }

        $this->load->view('auth/login');
    }

    public function logout()
    {
        $token = $this->session->userdata('api_session_token');
        if ($token) {
            $this->auth->revoke_user_session(hash('sha256', $token));
        }

        $this->session->sess_destroy();
        return redirect('auth/login');
    }
}
