<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login</title>
</head>
<body>
    <h1>ERP Login</h1>

    <?php if ($this->session->flashdata('auth_message')): ?>
        <p><?php echo html_escape($this->session->flashdata('auth_message')); ?></p>
    <?php endif; ?>

    <?php if ($this->session->flashdata('auth_error')): ?>
        <p><?php echo html_escape($this->session->flashdata('auth_error')); ?></p>
    <?php endif; ?>

    <?php echo validation_errors('<p>', '</p>'); ?>

    <?php echo form_open('login', array('autocomplete' => 'off')); ?>
        <p>
            <label for="identity">Username or email</label><br>
            <input type="text" name="identity" id="identity" value="<?php echo html_escape(set_value('identity')); ?>" maxlength="191" required autofocus>
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
