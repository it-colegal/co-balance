<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    protected $table = 'users';

    public function find($user_id)
    {
        return $this->db
            ->select('id, username, email, full_name, password_hash, is_active, last_login_at, last_login_ip')
            ->from($this->table)
            ->where('id', (int) $user_id)
            ->limit(1)
            ->get()
            ->row();
    }

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
            ->row();
    }

    public function get_roles($user_id)
    {
        $rows = $this->db
            ->select('roles.name')
            ->from('roles')
            ->join('user_roles', 'user_roles.role_id = roles.id')
            ->where('user_roles.user_id', (int) $user_id)
            ->order_by('roles.name', 'ASC')
            ->get()
            ->result();

        return array_map(function ($row) {
            return $row->name;
        }, $rows);
    }

    public function get_permissions($user_id)
    {
        $rows = $this->db
            ->distinct()
            ->select('permissions.name')
            ->from('permissions')
            ->join('role_permissions', 'role_permissions.permission_id = permissions.id')
            ->join('user_roles', 'user_roles.role_id = role_permissions.role_id')
            ->where('user_roles.user_id', (int) $user_id)
            ->order_by('permissions.name', 'ASC')
            ->get()
            ->result();

        return array_map(function ($row) {
            return $row->name;
        }, $rows);
    }

    public function update_login_metadata($user_id, $ip_address)
    {
        $this->db
            ->where('id', (int) $user_id)
            ->update($this->table, array(
                'last_login_at' => date('Y-m-d H:i:s'),
                'last_login_ip' => (string) $ip_address,
            ));
    }
}
