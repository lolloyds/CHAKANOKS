<?php include __DIR__ . '/../branch includes/header.php'; ?>
<?php include __DIR__ . '/../branch includes/sidebar.php'; ?>

<main>
  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: #f4f6f9;
    }
    .box { 
      background: #ffffff; 
      padding: 20px; 
      border-radius: 12px; 
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); 
      margin-bottom: 20px; 
    }
    h2, h3, h4 { 
      margin-top: 0; 
      color: #333; 
    }
    .desc { 
      font-size: 15px; 
      color: #555; 
      line-height: 1.6; 
    }
    .stats { 
      display: grid; 
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
      gap: 16px; 
    }
    .stat { 
      background: linear-gradient(135deg, #f9fbff, #eef2f7); 
      border-radius: 10px; 
      padding: 16px; 
      font-weight: 600; 
      text-align: center; 
      font-size: 16px;
      color: #222;
      box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .highlights ul { 
      list-style: none; 
      padding: 0; 
      margin: 0; 
    }
    .highlights li { 
      background: #f9fbff; 
      margin: 8px 0; 
      padding: 10px 14px; 
      border-left: 4px solid orange; 
      border-radius: 6px; 
      font-size: 14px; 
      color: #444;
    }
    .table { 
      width: 100%; 
      border-collapse: collapse; 
      margin-top: 10px; 
      font-size: 14px;
    }
    .table th, .table td { 
      text-align: left; 
      padding: 12px 14px; 
      border-bottom: 1px solid #eef2f7; 
    }
    .table th { 
      background: #f7f9fc; 
      font-weight: bold; 
      color: #555; 
    }
    .activity { 
      font-size: 14px; 
      color: #333; 
    }
    .activity tr:hover { 
      background: #fafafa; 
    }
  </style>

  <div class="box">
    <h2>📊 Dashboard</h2>
  </div>

  <div class="page-header">
    <h1>CHAKANOKS</h1>
    <h3>Welcome to <?= esc($branch->name) ?></h3>
  </div>

  <div class="box">
    <div class="stats">
      <div class="stat">🏢 Total Items: <?= esc($totalItems) ?></div>
      <div class="stat">📉 Low Stock Items: <?= esc($lowStock) ?></div>
      <div class="stat">📦 Out of Stock: <?= esc($outOfStock) ?></div>
      <div class="stat">📅 Near Expiry: <?= esc($nearExpiry) ?></div>
    </div>
  </div>

  <div class="box">
    <a href="<?= base_url('inventory/binventory') ?>">
        📦 View Inventory
      </a>
  </div>
</main>

<?php include __DIR__ . '/../branch includes/footer.php'; ?>
