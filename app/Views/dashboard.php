<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

<style>
  body {
    background: #ffeef5;
    font-family: "Segoe UI", Arial, sans-serif;
  }

  main {
    background: #ffeef5;
    padding: 20px;
  }

  .box { 
    background: #fff5f8; 
    padding: 25px; 
    border-radius: 12px; 
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); 
    margin-bottom: 20px; 
    border: 1px solid #ffd6e8;
  }

  h2, h3, h4 { 
    margin-top: 0; 
    color: #333; 
    font-weight: 600;
  }

  h2 {
    font-size: 24px;
    margin-bottom: 10px;
  }

  h3 {
    font-size: 18px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ffd6e8;
  }

  h4 {
    font-size: 16px;
    margin-bottom: 15px;
  }

  .desc { 
    font-size: 15px; 
    color: #666; 
    line-height: 1.6; 
    margin: 0;
  }

  .stats { 
    display: grid; 
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
    gap: 16px; 
  }

  .stat { 
    background: linear-gradient(135deg, #fff 0%, #fff0f5 100%); 
    border-radius: 10px; 
    padding: 20px; 
    font-weight: 600; 
    text-align: center; 
    font-size: 16px;
    color: #333;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    border: 1px solid #ffd6e8;
    transition: transform 0.2s, box-shadow 0.2s;
  }

  .stat:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .stat:nth-child(1) { border-left: 4px solid #2196F3; }
  .stat:nth-child(2) { border-left: 4px solid #ff9800; }
  .stat:nth-child(3) { border-left: 4px solid #ffc107; }
  .stat:nth-child(4) { border-left: 4px solid #4caf50; }

  .stat b {
    font-size: 24px;
    display: block;
    margin-top: 5px;
    color: #333;
  }

  .highlights ul { 
    list-style: none; 
    padding: 0; 
    margin: 0; 
  }

  .highlights li { 
    background: #fff; 
    margin: 10px 0; 
    padding: 12px 16px; 
    border-left: 4px solid #ff69b4; 
    border-radius: 6px; 
    font-size: 14px; 
    color: #444;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s;
  }

  .highlights li:hover {
    transform: translateX(5px);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  }

  .table { 
    width: 100%; 
    border-collapse: collapse; 
    margin-top: 10px; 
    font-size: 14px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  }

  .table th, .table td { 
    text-align: left; 
    padding: 14px 16px; 
    border-bottom: 1px solid #ffd6e8; 
  }

  .table th { 
    background: linear-gradient(135deg, #fff0f5 0%, #ffeef5 100%);
    font-weight: 600; 
    color: #333; 
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
  }

  .table tbody tr {
    background: #fff;
    transition: background 0.2s;
  }

  .table tbody tr:hover { 
    background: #fff0f5; 
  }

  .table tbody tr:last-child td {
    border-bottom: none;
  }

  .activity { 
    font-size: 14px; 
    color: #333; 
  }

  .text-center {
    text-align: center;
    color: #666;
    padding: 30px;
  }
</style>

<main>
  <?php if (!isset($isBranchUser) || !$isBranchUser): ?>
  <!-- Central Office Dashboard -->
  <div class="box">
    <h2>📊 Dashboard</h2>
    <div class="desc">
      Welcome to the <b>CHAKANOKS Management Dashboard</b>.
      Monitor <b>real-time operations</b> including <u>inventory, suppliers, purchase requests, deliveries, transfers, and franchise performance</u>.
    </div>
  </div>

  <div class="box">
    <div class="stats">
      <div class="stat">
        <div style="font-size: 13px; color: #666; margin-bottom: 5px;">🏢 Total Branches</div>
        <b style="color: #2196F3;"><?= $totalBranches ?? 0 ?></b>
      </div>
      <div class="stat">
        <div style="font-size: 13px; color: #666; margin-bottom: 5px;">🚚 Active Deliveries</div>
        <b style="color: #ff9800;"><?= $activeDeliveries ?? 0 ?></b>
      </div>
      <div class="stat">
        <div style="font-size: 13px; color: #666; margin-bottom: 5px;">📦 Pending Orders</div>
        <b style="color: #ffc107;"><?= $pendingOrders ?? 0 ?></b>
      </div>
      <div class="stat">
        <div style="font-size: 13px; color: #666; margin-bottom: 5px;">🤝 Suppliers</div>
        <b style="color: #4caf50;"><?= $totalSuppliers ?? 0 ?></b>
      </div>
    </div>
  </div>

  <div class="box highlights">
    <h4>📌 Today's Highlights</h4>
    <ul>
      <?php if (($scheduledDeliveries ?? 0) > 0): ?>
        <li><?= $scheduledDeliveries ?> Delivery<?= $scheduledDeliveries > 1 ? 'ies' : '' ?> scheduled for today</li>
      <?php endif; ?>
      <?php if (($pendingPRs ?? 0) > 0): ?>
        <li><?= $pendingPRs ?> Pending Purchase Request<?= $pendingPRs > 1 ? 's' : '' ?> awaiting approval</li>
      <?php endif; ?>
      <?php if (($lowStockItems ?? 0) > 0): ?>
        <li>Inventory running low on <b><?= $lowStockItems ?> item<?= $lowStockItems > 1 ? 's' : '' ?></b></li>
      <?php endif; ?>
      <?php if (($inactiveSuppliers ?? 0) > 0): ?>
        <li><b><?= $inactiveSuppliers ?> inactive supplier<?= $inactiveSuppliers > 1 ? 's' : '' ?></b> (can be activated from Suppliers page)</li>
      <?php endif; ?>
      <?php if (($scheduledDeliveries ?? 0) == 0 && ($pendingPRs ?? 0) == 0 && ($lowStockItems ?? 0) == 0 && ($inactiveSuppliers ?? 0) == 0): ?>
        <li>All systems operating normally. No urgent actions required.</li>
      <?php endif; ?>
    </ul>
  </div>

  <div class="box">
    <h4>🕑 Recent Activity</h4>
    <table class="table activity">
      <thead>
        <tr>
          <th>Time</th>
          <th>Activity</th>
          <th>User</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($recentActivity)): ?>
          <?php foreach ($recentActivity as $activity): ?>
            <tr>
              <td><?= date('h:i A', strtotime($activity['created_at'])) ?></td>
              <td>
                <?php
                  $movementType = $activity['movement_type'] ?? '';
                  $itemName = $activity['item_name'] ?? 'Item';
                  $quantity = abs($activity['quantity'] ?? 0);
                  $branchName = $activity['branch_name'] ?? 'Branch';
                  
                  $icons = [
                    'receive' => '📦',
                    'use' => '🍗',
                    'transfer_in' => '⬇️',
                    'transfer_out' => '⬆️',
                    'adjustment' => '⚙️',
                    'spoilage' => '⚠️'
                  ];
                  
                  $icon = $icons[$movementType] ?? '📋';
                  $action = ucwords(str_replace('_', ' ', $movementType));
                  
                  echo $icon . ' ' . $action . ': ' . $quantity . ' ' . $itemName . ($movementType === 'transfer_out' ? ' to ' : ($movementType === 'transfer_in' ? ' from ' : ' at ')) . $branchName;
                ?>
              </td>
              <td><?= esc($activity['username'] ?? 'System') ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="text-center">No recent activity</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
  <!-- Branch Dashboard -->
  <div class="box">
    <h2>📊 Dashboard</h2>
    <h3 style="margin-top: 10px; border: none; padding: 0;">Welcome to <?= esc($branch->name ?? 'Branch') ?></h3>
  </div>

  <div class="box">
    <div class="stats">
      <div class="stat">
        <div style="font-size: 13px; color: #666; margin-bottom: 5px;">📦 Total Items</div>
        <b style="color: #2196F3;"><?= esc($totalItems ?? 0) ?></b>
      </div>
      <div class="stat">
        <div style="font-size: 13px; color: #666; margin-bottom: 5px;">📉 Low Stock Items</div>
        <b style="color: #ff9800;"><?= esc($lowStock ?? 0) ?></b>
      </div>
      <div class="stat">
        <div style="font-size: 13px; color: #666; margin-bottom: 5px;">❌ Out of Stock</div>
        <b style="color: #f44336;"><?= esc($outOfStock ?? 0) ?></b>
      </div>
      <div class="stat">
        <div style="font-size: 13px; color: #666; margin-bottom: 5px;">📅 Near Expiry</div>
        <b style="color: #ffc107;"><?= esc($nearExpiry ?? 0) ?></b>
      </div>
    </div>
  </div>

  <div class="box">
    <a href="<?= base_url('inventory') ?>" style="display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #ff69b4 0%, #ff1493 100%); color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.2s; box-shadow: 0 4px 12px rgba(255, 105, 180, 0.3);">
      📦 View Inventory
    </a>
  </div>
  <?php endif; ?>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
