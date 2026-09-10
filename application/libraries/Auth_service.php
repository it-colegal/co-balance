<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_service {

	const REDIRECT_KEY = 'auth_redirect_to';

	protected $CI;
	protected $user_cache;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library('session');
		$this->CI->load->model('User_model');
	}

	public function attempt($identity, $password)
	{
		$user = $this->CI->User_model->find_by_identity($identity);

		if (! $user || empty($user['is_active']))
		{
			return FALSE;
		}

		$hash = isset($user['password_hash']) ? $user['password_hash'] : '';

		if (! is_string($hash) || $hash === '' || ! password_verify($password, $hash))
		{
			return FALSE;
		}

		if (password_needs_rehash($hash, PASSWORD_BCRYPT))
		{
			$this->CI->User_model->update_password_hash($user['id'], password_hash($password, PASSWORD_BCRYPT));
		}

		$this->establish_session((int) $user['id']);
		$this->CI->User_model->touch_last_login((int) $user['id']);

		return TRUE;
	}

	public function check()
	{
		return $this->user() !== NULL;
	}

	public function user()
	{
		if ($this->user_cache !== NULL)
		{
			return $this->user_cache;
		}

		$user_id = (int) $this->CI->session->userdata('auth_user_id');

		if ($user_id < 1)
		{
			return NULL;
		}

		$user = $this->CI->User_model->find_by_id($user_id);

		if (! $user || empty($user['is_active']))
		{
			$this->logout();
			return NULL;
		}

		$user['roles'] = $this->CI->User_model->get_roles($user_id);
		$user['role_codes'] = $this->session_role_codes();
		$user['permission_codes'] = $this->session_permission_codes();

		$this->user_cache = $user;

		return $this->user_cache;
	}

	public function has_role($role_code)
	{
		return in_array($role_code, $this->session_role_codes(), TRUE);
	}

	public function has_permission($permission_code)
	{
		if ($this->has_role('super_admin'))
		{
			return TRUE;
		}

		return in_array($permission_code, $this->session_permission_codes(), TRUE);
	}

	public function logout()
	{
		$this->user_cache = NULL;
		$this->CI->session->unset_userdata(array(
			'auth_user_id',
			'auth_roles',
			'auth_permissions',
			self::REDIRECT_KEY,
		));
		$this->CI->session->sess_destroy();
	}

	public function remember_requested_url($url)
	{
		if (is_string($url) && $url !== '')
		{
			$this->CI->session->set_userdata(self::REDIRECT_KEY, $url);
		}
	}

	public function redirect_target($default = 'dashboard')
	{
		$target = $this->CI->session->userdata(self::REDIRECT_KEY);
		$this->CI->session->unset_userdata(self::REDIRECT_KEY);

		if (is_string($target) && $target !== '')
		{
			return $target;
		}

		return site_url($default);
	}

	protected function establish_session($user_id)
	{
		$this->CI->session->sess_regenerate(TRUE);
		$this->CI->session->set_userdata(array(
			'auth_user_id' => $user_id,
			'auth_roles' => $this->CI->User_model->get_role_codes($user_id),
			'auth_permissions' => $this->CI->User_model->get_permission_codes($user_id),
		));
		$this->user_cache = NULL;
	}

	protected function session_role_codes()
	{
		$roles = $this->CI->session->userdata('auth_roles');
		return is_array($roles) ? $roles : array();
	}

	protected function session_permission_codes()
	{
		$permissions = $this->CI->session->userdata('auth_permissions');
		return is_array($permissions) ? array_values(array_unique($permissions)) : array();
	}
}
