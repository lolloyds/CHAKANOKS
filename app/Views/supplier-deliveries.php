<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

<main>
  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: #ffeef5;
    }
    .box {
      background: #fff5f8;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      margin-bottom: 20px;
      border: 1px solid #ffd6e8;
    }
    h2, h3 {
      margin-top: 0;
      color: #333;
      font-weight: 600;
    }
    h2 {
      font-size: 24px;
      margin-bottom: 8px;
    }
    h3 {
      font-size: 18px;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid #ffd6e8;
    }
    .desc {
      font-size: 15px;
      color: #555;
      line-height: 1.6;
    }
  </style>

  <div class="box">
    <h2>🚚 Delivery Status - <?= esc($supplier['supplier_name'] ?? ' supplier') ?></h2>
    <div class="desc">
      Track delivery progress and update status for your orders.
    </div>
  </div>

  <div class="box">
    <div style="text-align: center; padding: 40px;">
      <h3>Coming Soon</h3>
      <p>This feature is under development. For now, use the Orders page to manage deliveries.</p>
      <a href="<?= base_url('supplier-orders'); ?>" style="display: inline-block; background: #ff69b4; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; margin-top: 20px;">Go to Orders</a>
    </div>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
