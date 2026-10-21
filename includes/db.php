<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config.php';
mysqli_report(MYSQLI_REPORT_OFF);
$db = @new mysqli(DB_HOST, DB_USER, DB_PASS);
if ($db->connect_error) die('MySQL se connect nahi hua. XAMPP Control Panel mein MySQL START karein. (' . htmlspecialchars($db->connect_error) . ')');
$db->set_charset('utf8mb4');
$db->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$db->select_db(DB_NAME);
// Auto-setup: agar tables nahi hain to database.sql khud run ho jati hai
$have = []; if ($res = $db->query("SHOW TABLES")) { foreach ($res->fetch_all() as $r) $have[] = $r[0]; }
if (array_diff(['users', 'categories', 'products', 'orders', 'order_items'], $have)) {
    $file = __DIR__ . '/../' . SQL_FILE;
    if (!is_file($file)) die('SQL file nahi mili: ' . htmlspecialchars($file));
    if ($db->multi_query(file_get_contents($file))) {
        do { if ($r = $db->store_result()) $r->free(); } while ($db->more_results() && $db->next_result());
    }
    if ($db->errno) die('Auto-setup error: ' . htmlspecialchars($db->error));
    $db->select_db(DB_NAME);
}
if (!isset($root)) $root = '';

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money($n){ return CURRENCY . ' ' . number_format((float)$n); }
function rows($sql, $types = '', $params = []){
    global $db; $st = $db->prepare($sql);
    if (!$st) die('SQL error: ' . e($db->error));
    if ($types) $st->bind_param($types, ...$params);
    $st->execute(); $r = $st->get_result();
    return $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
}
function one($sql, $types = '', $params = []){ $r = rows($sql, $types, $params); return $r[0] ?? null; }
function run($sql, $types = '', $params = []){
    global $db; $st = $db->prepare($sql);
    if (!$st) die('SQL error: ' . e($db->error));
    if ($types) $st->bind_param($types, ...$params);
    $st->execute(); return $db->insert_id;
}
function flash($m = null, $type = 'ok'){
    if ($m !== null) { $_SESSION['flash'] = [$m, $type]; return; }
    if (!empty($_SESSION['flash'])) { $f = $_SESSION['flash']; unset($_SESSION['flash']); return $f; }
    return null;
}
function logged(){ return !empty($_SESSION['user']); }
function is_admin(){ return logged() && $_SESSION['user']['role'] === 'admin'; }
function require_login(){ global $root; if (!logged()) { flash('Pehle login karein', 'err'); header('Location: ' . $root . 'login.php'); exit; } }
function require_admin(){ if (!is_admin()) { flash('Sirf admin ke liye', 'err'); header('Location: ../login.php'); exit; } }
function cart_count(){ return array_sum($_SESSION['cart'] ?? []); }
function cart_items(){
    $cart = $_SESSION['cart'] ?? [];
    if (!$cart) return [[], 0];
    $in = implode(',', array_map('intval', array_keys($cart)));
    $ps = rows("SELECT * FROM products WHERE id IN ($in)");
    $total = 0;
    foreach ($ps as &$p) {
        $p['qty'] = min((int)$cart[$p['id']], max(0, (int)$p['stock']));
        $p['sub'] = $p['qty'] * $p['price'];
        $total += $p['sub'];
    }
    return [$ps, $total];
}
function pimg($p, $r = ''){
    if (!empty($p['image'])) return '<img src="' . $r . 'uploads/' . e($p['image']) . '" alt="' . e($p['name']) . '" class="img-fluid">';
    return '<span class="book-emoji">' . e($p['emoji'] ?: '📖') . '</span>';
}
function card($p){
    global $root;
    $back = basename($_SERVER['PHP_SELF']) . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '');
    $catClass = 'cat-' . (int)($p['category_id'] ?? 0);
    ob_start(); ?>
    <div class="col">
      <article class="book-card h-100 <?= $catClass ?>">
        <a class="book-cover" href="<?= $root ?>product.php?id=<?= $p['id'] ?>">
          <?= pimg($p, $root) ?>
          <span class="cover-shine"></span>
        </a>
        <div class="book-info">
          <span class="book-cat"><?= e($p['cat_name'] ?? 'Book') ?></span>
          <h3 class="book-title"><a href="<?= $root ?>product.php?id=<?= $p['id'] ?>"><?= e($p['name']) ?></a></h3>
          <div class="book-price"><?= money($p['price']) ?></div>
          <?php if ($p['stock'] > 0): ?>
          <form method="post" action="<?= $root ?>cart.php" class="mt-auto">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="id" value="<?= $p['id'] ?>">
            <input type="hidden" name="back" value="<?= e($back) ?>">
            <button type="submit" class="btn btn-add w-100">Add to Cart</button>
          </form>
          <?php else: ?>
            <button class="btn btn-outline-secondary w-100" disabled>Out of stock</button>
          <?php endif; ?>
        </div>
      </article>
    </div>
    <?php return ob_get_clean();
}
