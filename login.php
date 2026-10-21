<?php
$root = ''; require 'includes/db.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = one("SELECT * FROM users WHERE email=?", 's', [strtolower(trim($_POST['email'] ?? ''))]);
    if ($u && password_verify($_POST['password'] ?? '', $u['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => $u['id'], 'name' => $u['name'], 'role' => $u['role']];
        header('Location: ' . ($u['role'] === 'admin' ? 'admin/index.php' : 'index.php')); exit;
    }
    $err = 'Email ya password galat hai.';
}
$title = 'Login'; include 'includes/header.php'; ?>

<div class="auth-box">
  <h2>Welcome back</h2>
  <?php if ($err): ?><div class="alert alert-danger py-2"><?= e($err) ?></div><?php endif; ?>
  <form method="post">
    <label class="form-label">Email</label>
    <input type="email" class="form-control" name="email" required autofocus>
    <label class="form-label">Password</label>
    <input type="password" class="form-control" name="password" required>
    <button type="submit" class="btn btn-full">Login</button>
  </form>
  <p>New here? <a href="register.php">Create an account</a></p>
</div>

<?php include 'includes/footer.php'; ?>
