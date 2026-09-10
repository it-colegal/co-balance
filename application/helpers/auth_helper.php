<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function require_login()
{
    $CI =& get_instance();
    if (!$CI->session->userdata('is_logged_in')) {
        redirect('auth/login');
        exit;
    }
}

function require_permission($permission_code)
{
    $CI =& get_instance();
    $permissions = (array)$CI->session->userdata('permissions');

    if (!in_array($permission_code, $permissions, true)) {
        show_error('Forbidden (403): Anda tidak memiliki akses.', 403);
        exit;
    }
}
