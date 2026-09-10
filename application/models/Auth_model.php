<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    public function find_user($identity)
    {
        return $this->db
            ->from('users')
            ->group_start()
                ->where('username', $identity)
                ->or_where('email', $identity)
            ->group_end()
            ->where('is_active', 1)
            ->where('deleted_at IS NULL', null, false)
            ->limit(1)
            ->get()
            ->row_array();
    }

    public function get_user_roles($user_id)
    {
        return $this->db->select('r.id, r.role_code, r.role_name')
            ->from('user_roles ur')
            ->join('roles r', 'r.id = ur.role_id', 'inner')
            ->where('ur.user_id', $user_id)
            ->where('r.is_active', 1)
            ->get()->result_array();
    }

    public function get_user_permissions($user_id)
    {
        $rolePerms = $this->db->select('p.permission_code')
            ->from('user_roles ur')
            ->join('role_permissions rp', 'rp.role_id = ur.role_id', 'inner')
            ->join('permissions p', 'p.id = rp.permission_id', 'inner')
            ->where('ur.user_id', $user_id)
            ->where('p.is_active', 1)
            ->get()->result_array();

        $permissions = array_column($rolePerms, 'permission_code');

        $overrides = $this->db->select('p.permission_code, urp.is_allowed')
            ->from('user_role_permissions urp')
            ->join('permissions p', 'p.id = urp.permission_id', 'inner')
            ->where('urp.user_id', $user_id)
            ->where('p.is_active', 1)
            ->get()->result_array();

        foreach ($overrides as $ov) {
            if ((int)$ov['is_allowed'] === 1) {
                $permissions[] = $ov['permission_code'];
            } else {
                $permissions = array_diff($permissions, [$ov['permission_code']]);
            }
        }

        return array_values(array_unique($permissions));
    }

    public function create_user_session($user_id, $token, $expires_at, $ip, $ua)
    {
        return $this->db->insert('user_sessions', [
            'user_id' => $user_id,
            'session_token' => $token,
            'ip_address' => $ip,
            'user_agent' => $ua,
            'expires_at' => $expires_at
        ]);
    }

    public function revoke_user_session($token)
    {
        return $this->db->where('session_token', $token)
            ->update('user_sessions', ['revoked_at' => date('Y-m-d H:i:s')]);
    }

    public function write_login_history($user_id, $success, $reason, $ip, $ua)
    {
        return $this->db->insert('login_history', [
            'user_id' => $user_id,
            'ip_address' => $ip,
            'user_agent' => $ua,
            'success' => $success ? 1 : 0,
            'failure_reason' => $reason
        ]);
    }

    public function update_last_login($user_id)
    {
        return $this->db->where('id', $user_id)
            ->update('users', ['last_login_at' => date('Y-m-d H:i:s')]);
    }
}
