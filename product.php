<?php
$root = ''; require 'includes/db.php';
$p = one("SELECT p.*, c.name AS cat_name FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.id=?", 'i', [(int)($_GET['id'] ?? 0)]);
if (!$p) { header('Location: index.php'); exit; }
$related = rows("SELECT p.*, c.name AS cat_name FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.category_id=? AND p.id<>? LIMIT 4", 'ii', [$p['category_id'], $p['id']]);
$title = $p['name']; include 'includes/header.php'; ?>

<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb mb-0 small">
    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
    <?php if ($p['cat_name']): ?><li class="breadcrumb-item"><a href="index.php?cat=<?= (int)$p['category_id'] ?>"><?= e($p['cat_name']) ?></a></li><?php endif; ?>
    <li class="breadcrumb-item active" aria-current="page"><?= e($p['name']) ?></li>
  </ol>
</nav>

<div class="product-detail">
  <div class="product-cover cat-<?= (int)$p['category_id'] ?>">
    <?= pimg($p) ?>
  </div>
  <div class="product-meta">
    <span class="cat-label"><?= e($p['cat_name'] ?? 'Book') ?></span>
    <h1><?= e($p['name']) ?></h1>
    <div class="price"><?= money($p['price']) ?></div>
    <div class="desc"><?= nl2br(e($p['description'])) ?></div>
    <div class="stock-badge <?= $p['stock'] > 0 ? 'in' : 'out' ?>">
      <?= $p['stock'] > 0 ? '✓ In stock (' . (int)$p['stock'] . ' available)' : '✗ Currently out of stock' ?>
    </div>
    <?php if ($p['stock'] > 0): ?>
    <form method="post" action="cart.php" class="d-flex flex-wrap align-items-end gap-3">
      <input type="hidden" name="action" value="add">
      <input type="hidden" name="id" value="<?= $p['id'] ?>">
      <input type="hidden" name="back" value="cart.php">
      <div>
        <label class="form-label small fw-semibold text-muted mb-1">Quantity</label>
        <input class="qty-input form-control" type="number" name="qty" value="1" min="1" max="<?= (int)$p['stock'] ?>">
      </div>
      <button type="submit" class="btn btn-accent px-4 py-2">Add to Cart</button>
    </form>
    <?php endif; ?>
  </div>
</div>

<?php if ($related): ?>
<section class="mb-4">
  <h2 class="section-title">You may also like</h2>
  <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4">
    <?php foreach ($related as $r) echo card($r); ?>
  </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
