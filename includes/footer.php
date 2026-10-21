</div><!-- /.container -->
</main>

<footer class="site-footer">
  <div class="container">
    <div class="row g-4 py-5">
      <div class="col-lg-4 col-md-6">
        <div class="footer-brand mb-3">
          <span class="logo-icon"><?= STORE_ICON ?></span>
          <span class="logo-text"><?= e(STORE_NAME) ?></span>
        </div>
        <p class="footer-tagline mb-3"><?= e(STORE_TAGLINE) ?></p>
        <p class="small text-muted mb-0">Curated books & stationery delivered to your door. Read more, discover more.</p>
      </div>
      <div class="col-6 col-lg-2 col-md-3">
        <h6 class="footer-heading">Explore</h6>
        <ul class="footer-links">
          <li><a href="<?= $root ?>index.php">All Books</a></li>
          <li><a href="<?= $root ?>index.php?cat=1">Novels</a></li>
          <li><a href="<?= $root ?>index.php?cat=2">Islamic</a></li>
          <li><a href="<?= $root ?>index.php?cat=3">Education</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2 col-md-3">
        <h6 class="footer-heading">Account</h6>
        <ul class="footer-links">
          <li><a href="<?= $root ?>cart.php">Cart</a></li>
          <li><a href="<?= $root ?>orders.php">My Orders</a></li>
          <li><a href="<?= $root ?>login.php">Login</a></li>
          <li><a href="<?= $root ?>register.php">Register</a></li>
        </ul>
      </div>
      <div class="col-lg-4 col-md-12">
        <h6 class="footer-heading">Stay in the loop</h6>
        <p class="small text-muted mb-3">New arrivals, reading lists & exclusive offers.</p>
        <form class="newsletter-form" onsubmit="return false;">
          <div class="input-group">
            <input type="email" class="form-control" placeholder="Your email address" aria-label="Email">
            <button class="btn btn-accent" type="button">Subscribe</button>
          </div>
        </form>
      </div>
    </div>
    <div class="footer-bottom py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
      <span class="small">&copy; <?= date('Y') ?> <?= e(STORE_NAME) ?>. All rights reserved.</span>
      <span class="small text-muted">Crafted for readers · Cash on Delivery</span>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
