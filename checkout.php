<?php
$root = ''; require 'includes/db.php'; require_login();
[$items, $total] = cart_items();
$items = array_values(array_filter($items, fn($i) => $i['qty'] > 0));
if (!$items) { flash('Cart khali hai', 'err'); header('Location: cart.php'); exit; }
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? ''); $phone = trim($_POST['phone'] ?? ''); $addr = trim($_POST['address'] ?? '');
    if ($name === '' || $phone === '' || $addr === '') $err = 'Sab fields bharna zaroori hai.';
    else {
        $db->begin_transaction(); $ok = true;
        $oid = run("INSERT INTO orders(user_id,name,phone,address,total,status) VALUES(?,?,?,?,?,'pending')", 'isssd', [$_SESSION['user']['id'], $name, $phone, $addr, $total]);
        foreach ($items as $i) {
            run("UPDATE products SET stock=stock-? WHERE id=? AND stock>=?", 'iii', [$i['qty'], $i['id'], $i['qty']]);
            if ($db->affected_rows < 1) { $ok = false; break; }
            run("INSERT INTO order_items(order_id,product_id,quantity,price) VALUES(?,?,?,?)", 'iiid', [$oid, $i['id'], $i['qty'], $i['price']]);
        }
        if ($ok) { $db->commit(); unset($_SESSION['cart']); flash("Order #$oid place ho gaya! Cash on Delivery 🎉"); header('Location: orders.php'); exit; }
        $db->rollback(); $err = 'Kisi product ka stock khatam ho gaya, cart check karein.';
    }
}
$title = 'Checkout'; include 'includes/header.php'; ?>

<h1 class="mb-4" style="font-family:var(--font-serif);font-weight:700">Checkout</h1>
<?php if ($err): ?><div class="alert alert-danger"><?= e($err) ?></div><?php endif; ?>

<div class="checkout-grid">
  <div>
    <div class="auth-box m-0" style="max-width:none">
      <h2 class="text-start mb-3" style="font-size:1.25rem">Delivery details</h2>
      <form method="post">
        <label class="form-label">Full name</label>
        <input class="form-control" name="name" required value="<?= e($_POST['name'] ?? $_SESSION['user']['name']) ?>">
        <label class="form-label">Phone</label>
        <input class="form-control" name="phone" required value="<?= e($_POST['phone'] ?? '') ?>">
        <label class="form-label">Address</label>
        <textarea class="form-control" name="address" rows="3" required><?= e($_POST['address'] ?? '') ?></textarea>
        <label class="form-label">Payment</label>
        <select class="form-select mb-3" disabled><option>Cash on Delivery</option></select>
        <button type="submit" class="btn btn-accent w-100 py-2">Place Order</button>
      </form>
    </div>
  </div>
  <div>
    <div class="summary-box">
      <h3 class="mb-3" style="font-family:var(--font-serif);font-size:1.2rem">Order summary</h3>
      <?php foreach ($items as $i): ?>
        <div class="d-flex justify-content-between mb-2">
          <span><?= e($i['name']) ?> × <?= $i['qty'] ?></span>
          <strong><?= money($i['sub']) ?></strong>
        </div>
      <?php endforeach; ?>
      <hr>
      <div class="d-flex justify-content-between">
        <strong>Total</strong>
        <strong style="color:var(--accent);font-size:1.2rem"><?= money($total) ?></strong>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
