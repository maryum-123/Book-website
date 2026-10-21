<?php
$root = ''; require 'includes/db.php'; require_login();
$orders = rows("SELECT * FROM orders WHERE user_id=? ORDER BY id DESC", 'i', [$_SESSION['user']['id']]);
$title = 'My Orders'; include 'includes/header.php'; ?>

<h1 class="mb-4" style="font-family:var(--font-serif);font-weight:700">My Orders</h1>

<?php if (!$orders): ?>
  <div class="empty-state">
    <div class="icon">📦</div>
    <p>You haven't placed any orders yet.</p>
    <a href="index.php" class="btn btn-accent">Browse books</a>
  </div>
<?php else: ?>
  <?php foreach ($orders as $o): $its = rows("SELECT oi.*, p.name FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE order_id=?", 'i', [$o['id']]); ?>
  <div class="order-card">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
      <div>
        <strong>Order #<?= $o['id'] ?></strong>
        <span class="text-muted small ms-2"><?= e($o['created_at']) ?></span>
        <span class="tag <?= e($o['status']) ?> ms-2"><?= e($o['status']) ?></span>
      </div>
      <strong style="color:var(--accent)"><?= money($o['total']) ?></strong>
    </div>
    <ul class="mb-2 ps-3">
      <?php foreach ($its as $i): ?>
        <li><?= e($i['name']) ?> × <?= $i['quantity'] ?> — <?= money($i['price'] * $i['quantity']) ?></li>
      <?php endforeach; ?>
    </ul>
    <small class="text-muted">📍 <?= e($o['address']) ?> · 📞 <?= e($o['phone']) ?></small>
  </div>
  <?php endforeach; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
