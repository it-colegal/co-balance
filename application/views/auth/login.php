<!doctype html>
<html>
<head><title>Login</title></head>
<body>
  <h2>Login</h2>
  <?php if ($this->session->flashdata('error')): ?>
    <p style="color:red;"><?= html_escape($this->session->flashdata('error')); ?></p>
  <?php endif; ?>

  <form method="post" action="<?= site_url('auth/login'); ?>">
    <label>Username / Email</label><br>
    <input type="text" name="identity" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Masuk</button>
  </form>
</body>
</html>
