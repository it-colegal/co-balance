<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <?php if ($this->session->flashdata('error')): ?>
        <p style="color: #b00020;"><?php echo html_escape($this->session->flashdata('error')); ?></p>
    <?php endif; ?>

    <?php echo form_open('login'); ?>
        <p>
            <label for="identity">Username or Email</label><br>
            <input type="text" name="identity" id="identity" value="<?php echo set_value('identity'); ?>" required autofocus>
        </p>
        <p>
            <label for="password">Password</label><br>
            <input type="password" name="password" id="password" required>
        </p>
        <p>
            <button type="submit">Login</button>
        </p>
    <?php echo form_close(); ?>
</body>
</html>
