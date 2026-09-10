<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('auth_user')) {
    function auth_user()
    {
        $ci =& get_instance();
        $ci->load->library('auth_service');
        return $ci->auth_service->user();
    }
}

if (!function_exists('has_permission')) {
    function has_permission($permission_slug)
    {
        $ci =& get_instance();
        $ci->load->library('auth_service');
        return $ci->auth_service->has_permission($permission_slug);
    }
}
