<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<style>
		body { font-family: Arial, sans-serif; background: #f5f7fb; margin: 0; padding: 2rem; color: #1f2937; }
		.auth-card { max-width: 420px; margin: 3rem auto; background: #fff; border-radius: 8px; padding: 2rem; box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08); }
		h1 { margin-top: 0; font-size: 1.5rem; }
		label { display: block; margin-bottom: 0.5rem; font-weight: 700; }
		input { width: 100%; box-sizing: border-box; padding: 0.75rem; margin-bottom: 1rem; border: 1px solid #cbd5e1; border-radius: 6px; }
		button { width: 100%; padding: 0.85rem; border: 0; border-radius: 6px; background: #1d4ed8; color: #fff; font-weight: 700; cursor: pointer; }
		.alert { padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
		.alert-error { background: #fee2e2; color: #991b1b; }
		.help-text { color: #475569; font-size: 0.95rem; margin-bottom: 1.5rem; }
	</style>
</head>
<body>
	<div class="auth-card">
		<h1>ERP Login</h1>
		<p class="help-text">Sign in with your username or email and password.</p>

		<?php if ($this->session->flashdata('error')): ?>
			<div class="alert alert-error"><?php echo html_escape($this->session->flashdata('error')); ?></div>
		<?php endif; ?>

		<?php if (validation_errors()): ?>
			<div class="alert alert-error"><?php echo validation_errors(); ?></div>
		<?php endif; ?>

		<?php echo form_open('login/submit'); ?>
			<label for="identity">Username or Email</label>
			<input id="identity" type="text" name="identity" value="<?php echo html_escape(set_value('identity')); ?>" autocomplete="username" required>

			<label for="password">Password</label>
			<input id="password" type="password" name="password" autocomplete="current-password" required>

			<button type="submit">Login</button>
		<?php echo form_close(); ?>
	</div>
</body>
</html>
