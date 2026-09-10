<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	protected $table = 'users';

	public function find_by_identity($identity)
	{
		return $this->db
			->select('id, username, email, full_name, password_hash, is_active')
			->from($this->table)
			->group_start()
				->where('username', $identity)
				->or_where('email', $identity)
			->group_end()
			->limit(1)
			->get()
			->row_array();
	}

	public function find_by_id($user_id)
	{
		return $this->db
			->select('id, username, email, full_name, is_active, last_login_at')
			->from($this->table)
			->where('id', $user_id)
			->limit(1)
			->get()
			->row_array();
	}

	public function get_roles($user_id)
	{
		return $this->db
			->select('r.id, r.role_code, r.role_name')
			->from('user_roles ur')
			->join('roles r', 'r.id = ur.role_id', 'inner')
			->where('ur.user_id', $user_id)
			->where('r.is_active', 1)
			->order_by('r.role_name', 'ASC')
			->get()
			->result_array();
	}

	public function get_role_codes($user_id)
	{
		$rows = $this->db
			->select('r.role_code')
			->from('user_roles ur')
			->join('roles r', 'r.id = ur.role_id', 'inner')
			->where('ur.user_id', $user_id)
			->where('r.is_active', 1)
			->order_by('r.role_code', 'ASC')
			->get()
			->result_array();

		return array_column($rows, 'role_code');
	}

	public function get_permission_codes($user_id)
	{
		$rows = $this->db
			->distinct()
			->select('p.permission_code')
			->from('user_roles ur')
			->join('roles r', 'r.id = ur.role_id', 'inner')
			->join('role_permissions rp', 'rp.role_id = r.id', 'inner')
			->join('permissions p', 'p.id = rp.permission_id', 'inner')
			->where('ur.user_id', $user_id)
			->where('r.is_active', 1)
			->where('p.is_active', 1)
			->order_by('p.permission_code', 'ASC')
			->get()
			->result_array();

		return array_column($rows, 'permission_code');
	}

	public function update_password_hash($user_id, $password_hash)
	{
		return $this->db
			->where('id', $user_id)
			->update($this->table, array(
				'password_hash' => $password_hash,
				'updated_at' => date('Y-m-d H:i:s'),
			));
	}

	public function touch_last_login($user_id)
	{
		return $this->db
			->where('id', $user_id)
			->update($this->table, array(
				'last_login_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			));
	}
}
