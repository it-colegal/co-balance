<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    protected $table = 'users';

    public function find_active_by_identity($identity)
    {
        return $this->db
            ->from($this->table)
            ->group_start()
                ->where('username', $identity)
                ->or_where('email', $identity)
            ->group_end()
            ->where('is_active', 1)
            ->limit(1)
            ->get()
            ->row_array();
    }

    public function get_roles($user_id)
    {
        $rows = $this->db
            ->select('r.slug')
            ->from('user_roles ur')
            ->join('roles r', 'r.id = ur.role_id')
            ->where('ur.user_id', (int) $user_id)
            ->get()
            ->result_array();

        return array_values(array_unique(array_column($rows, 'slug')));
    }

    public function get_permissions($user_id)
    {
        $rows = $this->db
            ->select('p.slug')
            ->from('user_roles ur')
            ->join('role_permissions rp', 'rp.role_id = ur.role_id')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('ur.user_id', (int) $user_id)
            ->get()
            ->result_array();

        return array_values(array_unique(array_column($rows, 'slug')));
    }
}
