<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['login'] = 'auth/login';
$route['login/submit'] = 'auth/authenticate';
$route['logout'] = 'auth/logout';
$route['dashboard'] = 'dashboard/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
