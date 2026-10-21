<?php
$root = '../'; require '../includes/db.php'; require_admin();
$statuses = ['pending', 'shipped', 'delivered', 'cancelled'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($_POST['status'] ?? '', $statuses)) {
    run("UPDATE orders SET status=? WHERE id=?", 'si', [$_POST['status'], (int)$_POST['id']]);
    flash('Status update ho gaya'); header('Location: orders.php'); exit;
}
$orders = rows("SELECT o.*, u.email FROM orders o LEFT JOIN users u ON u.id=o.user_id ORDER BY o.id DESC");
$title = 'Orders'; include '../includes/header.php'; include '../includes/admin_nav.php'; ?>
<h1>Orders</h1>
<?php foreach ($orders as $o): $its = rows("SELECT oi.*, p.name FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE order_id=?", 'i', [$o['id']]); ?>
<div class="box wide" style="margin-bottom:12px">
  <b>#<?= $o['id'] ?></b> &middot; <?= e($o['name']) ?> (<?= e($o['email']) ?>) &middot; <?= e($o['created_at']) ?> <b style="float:right"><?= money($o['total']) ?></b>
  <ul><?php foreach ($its as $i): ?><li><?= e($i['name']) ?> × <?= $i['quantity'] ?></li><?php endforeach; ?></ul>
  <small>📍 <?= e($o['address']) ?> &middot; 📞 <?= e($o['phone']) ?></small>
  <form method="post" style="display:flex;gap:8px;max-width:300px;margin-top:8px"><input type="hidden" name="id" value="<?= $o['id'] ?>">
    <select name="status"><?php foreach ($statuses as $s): ?><option <?= $o['status'] === $s ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?></select><button class="btn sm">Update</button></form>
</div>
<?php endforeach; include '../includes/footer.php'; ?>
