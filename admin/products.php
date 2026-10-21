<?php
$root = '../'; require '../includes/db.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = (int)$_POST['delete']; $p = one("SELECT image FROM products WHERE id=?", 'i', [$id]);
    if (one("SELECT id FROM order_items WHERE product_id=? LIMIT 1", 'i', [$id])) flash('Is product ke orders hain, delete nahi ho sakta. Stock 0 kar dein.', 'err');
    else { run("DELETE FROM products WHERE id=?", 'i', [$id]); if ($p && $p['image']) @unlink('../uploads/' . basename($p['image'])); flash('Product delete ho gaya'); }
    header('Location: products.php'); exit;
}
$list = rows("SELECT p.*, c.name AS cat_name FROM products p LEFT JOIN categories c ON c.id=p.category_id ORDER BY p.id DESC");
$title = 'Products'; include '../includes/header.php'; include '../includes/admin_nav.php'; ?>
<h1>Products <a class="btn sm" href="product_form.php">+ Add Product</a></h1>
<div class="table-wrap"><table><tr><th></th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th></th></tr>
<?php foreach ($list as $p): ?><tr>
<td><span class="mini"><?= pimg($p, '../') ?></span></td><td><?= e($p['name']) ?></td><td><?= e($p['cat_name']) ?></td><td><?= money($p['price']) ?></td><td><?= (int)$p['stock'] ?></td>
<td><a class="btn sm gray" href="product_form.php?id=<?= $p['id'] ?>">Edit</a>
<form method="post" class="inline" onsubmit="return confirm('Delete karna hai?')"><button class="btn sm red" name="delete" value="<?= $p['id'] ?>">Delete</button></form></td>
</tr><?php endforeach; ?></table></div>
<?php include '../includes/footer.php'; ?>
