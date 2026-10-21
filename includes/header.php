<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'Home') ?> | <?= e(STORE_NAME) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= $root ?>style.css">
</head>
<body>
<header class="site-header sticky-top">
  <div class="top-bar d-none d-md-block">
    <div class="container d-flex justify-content-between align-items-center py-1">
      <span class="small text-muted">Free delivery on orders over Rs. 3,000</span>
      <span class="small text-muted">Cash on Delivery available nationwide</span>
    </div>
  </div>
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand logo" href="<?= $root ?>index.php">
        <span class="logo-icon"><?= STORE_ICON ?></span>
        <span class="logo-text"><?= e(STORE_NAME) ?></span>
      </a>

      <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        <form class="search-form mx-lg-4 my-3 my-lg-0 flex-grow-1" action="<?= $root ?>index.php" method="get">
          <div class="input-group">
            <input type="search" name="q" class="form-control" placeholder="Search books, authors, topics..." value="<?= e($_GET['q'] ?? '') ?>" autocomplete="off">
            <button class="btn btn-search" type="submit" aria-label="Search">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </button>
          </div>
        </form>

        <ul class="navbar-nav align-items-lg-center gap-lg-1 ms-lg-auto">
          <li class="nav-item">
            <a class="nav-link cart-link" href="<?= $root ?>cart.php">
              <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
              <span class="d-none d-lg-inline">Cart</span>
              <?php if (cart_count() > 0): ?><span class="cart-badge"><?= cart_count() ?></span><?php endif; ?>
            </a>
          </li>
          <?php if (logged()): ?>
            <li class="nav-item"><a class="nav-link" href="<?= $root ?>orders.php">My Orders</a></li>
            <?php if (is_admin()): ?><li class="nav-item"><a class="nav-link" href="<?= $root ?>admin/index.php">Admin</a></li><?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="<?= $root ?>logout.php">Logout <span class="text-muted small">(<?= e($_SESSION['user']['name']) ?>)</span></a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="<?= $root ?>login.php">Login</a></li>
            <li class="nav-item"><a class="btn btn-accent btn-sm ms-lg-2" href="<?= $root ?>register.php">Join Free</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
</header>

<main class="main-content">
  <div class="container py-4">
    <?php if ($f = flash()): ?>
      <div class="alert alert-<?= $f[1] === 'ok' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
        <?= e($f[0]) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
