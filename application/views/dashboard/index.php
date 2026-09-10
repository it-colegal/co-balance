<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard</h1>
    <p>Welcome, <?php echo html_escape($user['username']); ?>.</p>
    <p><a href="<?php echo site_url('logout'); ?>">Logout</a></p>
</body>
</html>
