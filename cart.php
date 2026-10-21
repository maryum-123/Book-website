<?php
$root = ''; require 'includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $a = $_POST['action'] ?? ''; $id = (int)($_POST['id'] ?? 0);
    if ($a === 'add') {
        $qty = max(1, (int)($_POST['qty'] ?? 1));
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
        flash('Product cart mein add ho gaya ✅');
        $back = $_POST['back'] ?? 'cart.php';
        if (!preg_match('/^[a-z_]+\.php(\?[\w=&%+.-]*)?$/i', $back)) $back = 'cart.php';
        header('Location: ' . $back); exit;
    }
    if ($a === 'update') { $q = (int)($_POST['qty'] ?? 0); if ($q > 0) $_SESSION['cart'][$id] = $q; else unset($_SESSION['cart'][$id]); }
    if ($a === 'remove') unset($_SESSION['cart'][$id]);
    header('Location: cart.php'); exit;
}
[$items, $total] = cart_items();
$title = 'Cart'; include 'includes/header.php'; ?>

<h1 class="mb-4" style="font-family:var(--font-serif);font-weight:700">Shopping Cart</h1>

<?php if (!$items): ?>
  <div class="empty-state">
    <div class="icon">🛒</div>
    <p>Your cart is empty.</p>
    <a href="index.php" class="btn btn-accent">Start shopping</a>
  </div>
<?php else: ?>
<div class="table-wrap">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th style="width:70px"></th>
          <th>Book</th>
          <th>Price</th>
          <th>Qty</th>
          <th>Subtotal</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $i): ?>
        <tr>
          <td><div class="mini-cover"><?= pimg($i) ?></div></td>
          <td><a href="product.php?id=<?= $i['id'] ?>" class="fw-semibold"><?= e($i['name']) ?></a></td>
          <td><?= money($i['price']) ?></td>
          <td>
            <form method="post" class="d-inline-flex align-items-center gap-2">
              <input type="hidden" name="action" value="update">
              <input type="hidden" name="id" value="<?= $i['id'] ?>">
              <input class="qty-input form-control form-control-sm" type="number" name="qty" value="<?= $i['qty'] ?>" min="0" max="<?= (int)$i['stock'] ?>" style="width:70px">
              <button type="submit" class="btn btn-sm btn-outline-secondary">Update</button>
            </form>
          </td>
          <td class="fw-semibold"><?= money($i['sub']) ?></td>
          <td>
            <form method="post" class="d-inline">
              <input type="hidden" name="action" value="remove">
              <input type="hidden" name="id" value="<?= $i['id'] ?>">
              <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<div class="cart-total">Total: <?= money($total) ?></div>
<div class="text-end mt-3">
  <a class="btn btn-accent btn-lg px-4" href="checkout.php">Proceed to Checkout →</a>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
