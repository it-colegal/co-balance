<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Dashboard</title>
	<style>
		body { font-family: Arial, sans-serif; background: #f8fafc; margin: 0; padding: 2rem; color: #1e293b; }
		.card { max-width: 720px; margin: 2rem auto; background: #fff; border-radius: 8px; padding: 2rem; box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08); }
		h1 { margin-top: 0; }
		ul { padding-left: 1.25rem; }
		li { margin-bottom: 0.35rem; }
		button { padding: 0.75rem 1rem; border: 0; border-radius: 6px; background: #0f172a; color: #fff; cursor: pointer; }
	</style>
</head>
<body>
	<div class="card">
		<h1>Dashboard</h1>
		<p>Welcome, <strong><?php echo html_escape($user['full_name']); ?></strong>.</p>
		<p>Authenticated as <code><?php echo html_escape($user['username']); ?></code>.</p>

		<h2>Roles</h2>
		<ul>
			<?php foreach ($user['roles'] as $role): ?>
				<li><?php echo html_escape($role['role_name']); ?> (<?php echo html_escape($role['role_code']); ?>)</li>
			<?php endforeach; ?>
		</ul>

		<?php echo form_open('logout'); ?>
			<button type="submit">Logout</button>
		<?php echo form_close(); ?>
	</div>
</body>
</html>
