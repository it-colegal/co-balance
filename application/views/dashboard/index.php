<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard</h1>
    <p>Welcome, <?php echo html_escape($user->full_name ?: $user->username); ?>.</p>
    <p>You are logged in as: <?php echo html_escape(implode(', ', $user->roles)); ?></p>
    <ul>
        <li><a href="<?php echo site_url('admin/system'); ?>">System Administration</a></li>
        <li><a href="<?php echo site_url('logout'); ?>">Logout</a></li>
    </ul>
</body>
</html>
