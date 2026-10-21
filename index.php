<?php
$root = ''; require 'includes/db.php';
$cat = (int)($_GET['cat'] ?? 0); $q = trim($_GET['q'] ?? '');
$cats = rows("SELECT * FROM categories ORDER BY name");
$sql = "SELECT p.*, c.name AS cat_name FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE 1";
$t = ''; $p = [];
if ($cat) { $sql .= " AND p.category_id=?"; $t .= 'i'; $p[] = $cat; }
if ($q !== '') { $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)"; $t .= 'ss'; $p[] = "%$q%"; $p[] = "%$q%"; }
$products = rows($sql . " ORDER BY p.id DESC", $t, $p);
$featured = (!$cat && $q === '') ? array_slice($products, 0, 4) : [];
$title = 'Home'; include 'includes/header.php'; ?>

<?php if (!$cat && $q === ''): ?>
<section class="hero-section">
  <h1>Discover your next great read</h1>
  <p><?= e(STORE_TAGLINE) ?>. Curated titles for every reader — novels, Islamic literature, education & more.</p>
  <a href="#catalog" class="btn btn-hero">Browse the collection</a>
</section>

<?php if ($featured): ?>
<section class="mb-5">
  <h2 class="section-title">Featured books</h2>
  <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4">
    <?php foreach ($featured as $pr) echo card($pr); ?>
  </div>
</section>
<?php endif; ?>

<div class="promo-strip">
  <div>
    <p class="promo-text">Free delivery on orders over Rs. 3,000</p>
    <p class="promo-sub">Cash on Delivery · Secure packaging · Fast dispatch</p>
  </div>
  <a href="#catalog" class="btn btn-accent">Shop now</a>
</div>
<?php endif; ?>

<section id="catalog">
  <?php
    $sectionLabel = 'All books';
    if ($q !== '') $sectionLabel = 'Search results';
    elseif ($cat) {
      foreach ($cats as $c) { if ((int)$c['id'] === $cat) { $sectionLabel = $c['name']; break; } }
    }
  ?>
  <h2 class="section-title"><?= e($sectionLabel) ?></h2>

  <div class="cat-chips">
    <a href="index.php" class="cat-chip <?= !$cat ? 'active' : '' ?>">All</a>
    <?php foreach ($cats as $c): ?>
      <a href="index.php?cat=<?= $c['id'] ?>" class="cat-chip <?= $cat == $c['id'] ? 'active' : '' ?>"><?= e($c['name']) ?></a>
    <?php endforeach; ?>
  </div>

  <?php if (!$products): ?>
    <div class="empty-state">
      <div class="icon">🔍</div>
      <p>No books found matching your search.</p>
      <a href="index.php" class="btn btn-accent">View all books</a>
    </div>
  <?php else: ?>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4">
      <?php foreach ($products as $pr) echo card($pr); ?>
    </div>
  <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>
