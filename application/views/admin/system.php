<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>System Administration</title>
</head>
<body>
    <h1>System Administration</h1>
    <p>Access granted to <?php echo html_escape($user->full_name ?: $user->username); ?>.</p>
    <ul>
        <li><a href="<?php echo site_url('dashboard'); ?>">Dashboard</a></li>
        <li><a href="<?php echo site_url('logout'); ?>">Logout</a></li>
    </ul>
</body>
</html>
