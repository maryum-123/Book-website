<?php
$root = '../'; require '../includes/db.php'; require_admin();
$id = (int)($_GET['id'] ?? 0);
$p = $id ? one("SELECT * FROM products WHERE id=?", 'i', [$id]) : null;
if ($id && !$p) { header('Location: products.php'); exit; }
$cats = rows("SELECT * FROM categories ORDER BY name"); $err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']); $price = (float)$_POST['price']; $stock = (int)$_POST['stock'];
    $cid = (int)$_POST['category_id']; $desc = trim($_POST['description']); $emoji = trim($_POST['emoji']);
    $image = $p['image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']) || !getimagesize($_FILES['image']['tmp_name'])) $err = 'Sirf image file upload karein.';
        else { $new = bin2hex(random_bytes(8)) . '.' . $ext; move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $new); if ($image) @unlink('../uploads/' . basename($image)); $image = $new; }
    }
    if ($name === '' || $price <= 0) $err = $err ?: 'Name aur price zaroori hain.';
    if (!$err) {
        if ($id) run("UPDATE products SET name=?,price=?,stock=?,category_id=?,description=?,emoji=?,image=? WHERE id=?", 'sdiisssi', [$name, $price, $stock, $cid, $desc, $emoji, $image, $id]);
        else run("INSERT INTO products(name,price,stock,category_id,description,emoji,image) VALUES(?,?,?,?,?,?,?)", 'sdiisss', [$name, $price, $stock, $cid, $desc, $emoji, $image]);
        flash('Product save ho gaya ✅'); header('Location: products.php'); exit;
    }
}
$v = fn($k, $d = '') => e($_POST[$k] ?? ($p[$k] ?? $d));
$title = $id ? 'Edit Product' : 'Add Product'; include '../includes/header.php'; include '../includes/admin_nav.php'; ?>
<div class="box"><h2><?= $title ?></h2>
<?php if ($err): ?><div class="alert err"><?= e($err) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data">
  <label>Name</label><input name="name" required value="<?= $v('name') ?>">
  <label>Category</label><select name="category_id"><?php foreach ($cats as $c): ?><option value="<?= $c['id'] ?>" <?= ($p['category_id'] ?? 0) == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option><?php endforeach; ?></select>
  <label>Price</label><input type="number" step="0.01" name="price" required value="<?= $v('price') ?>">
  <label>Stock</label><input type="number" name="stock" required value="<?= $v('stock', 10) ?>">
  <label>Description</label><textarea name="description" rows="4"><?= $v('description') ?></textarea>
  <label>Emoji (agar image na ho)</label><input name="emoji" maxlength="4" value="<?= $v('emoji', '🛍️') ?>">
  <label>Image (optional)</label><input type="file" name="image" accept="image/*">
  <button class="btn full">Save</button>
</form></div>
<?php include '../includes/footer.php'; ?>
