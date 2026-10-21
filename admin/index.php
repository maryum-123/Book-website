<?php
$root = '../'; require '../includes/db.php'; require_admin();
$s = [
 'Products' => one("SELECT COUNT(*) n FROM products")['n'],
 'Orders' => one("SELECT COUNT(*) n FROM orders")['n'],
 'Customers' => one("SELECT COUNT(*) n FROM users WHERE role='customer'")['n'],
 'Sales' => money(one("SELECT COALESCE(SUM(total),0) n FROM orders WHERE status<>'cancelled'")['n']),
];
$title = 'Admin'; include '../includes/header.php'; include '../includes/admin_nav.php'; ?>
<h1>Dashboard</h1>
<div class="stats"><?php foreach ($s as $k => $v): ?><div class="stat"><b><?= $v ?></b><?= $k ?></div><?php endforeach; ?></div>
<h3>Latest orders</h3>
<div class="table-wrap"><table><tr><th>#</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr>
<?php foreach (rows("SELECT * FROM orders ORDER BY id DESC LIMIT 5") as $o): ?>
<tr><td><?= $o['id'] ?></td><td><?= e($o['name']) ?></td><td><?= money($o['total']) ?></td><td><span class="tag <?= e($o['status']) ?>"><?= e($o['status']) ?></span></td><td><?= e($o['created_at']) ?></td></tr>
<?php endforeach; ?></table></div>
<?php include '../includes/footer.php'; ?>
