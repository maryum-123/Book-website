<?php
$root = '../'; require '../includes/db.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['name'])) { run("INSERT INTO categories(name) VALUES(?)", 's', [trim($_POST['name'])]); flash('Category add ho gayi'); }
    if (!empty($_POST['delete'])) {
        if (one("SELECT id FROM products WHERE category_id=? LIMIT 1", 'i', [(int)$_POST['delete']])) flash('Is category mein products hain', 'err');
        else { run("DELETE FROM categories WHERE id=?", 'i', [(int)$_POST['delete']]); flash('Delete ho gayi'); }
    }
    header('Location: categories.php'); exit;
}
$title = 'Categories'; include '../includes/header.php'; include '../includes/admin_nav.php'; ?>
<h1>Categories</h1>
<form method="post" style="display:flex;gap:8px;max-width:420px;margin-bottom:16px"><input name="name" placeholder="New category" required><button class="btn">Add</button></form>
<div class="table-wrap"><table><?php foreach (rows("SELECT * FROM categories ORDER BY name") as $c): ?>
<tr><td><?= e($c['name']) ?></td><td><form method="post" class="inline"><button class="btn sm red" name="delete" value="<?= $c['id'] ?>">Delete</button></form></td></tr>
<?php endforeach; ?></table></div>
<?php include '../includes/footer.php'; ?>
