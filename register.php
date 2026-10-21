<?php
$root = ''; require 'includes/db.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? ''); $email = strtolower(trim($_POST['email'] ?? '')); $pass = $_POST['password'] ?? '';
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 6) $err = 'Sab fields sahi bharein (password kam az kam 6 characters).';
    elseif (one("SELECT id FROM users WHERE email=?", 's', [$email])) $err = 'Ye email pehle se registered hai.';
    else {
        $id = run("INSERT INTO users(name,email,password,role) VALUES(?,?,?,'customer')", 'sss', [$name, $email, password_hash($pass, PASSWORD_DEFAULT)]);
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => $id, 'name' => $name, 'role' => 'customer'];
        flash('Account ban gaya! Welcome 🎉'); header('Location: index.php'); exit;
    }
}
$title = 'Register'; include 'includes/header.php'; ?>

<div class="auth-box">
  <h2>Join BookNest</h2>
  <?php if ($err): ?><div class="alert alert-danger py-2"><?= e($err) ?></div><?php endif; ?>
  <form method="post">
    <label class="form-label">Name</label>
    <input class="form-control" name="name" required value="<?= e($_POST['name'] ?? '') ?>">
    <label class="form-label">Email</label>
    <input type="email" class="form-control" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
    <label class="form-label">Password</label>
    <input type="password" class="form-control" name="password" required minlength="6">
    <button type="submit" class="btn btn-full">Create Account</button>
  </form>
  <p>Already have an account? <a href="login.php">Login</a></p>
</div>

<?php include 'includes/footer.php'; ?>
