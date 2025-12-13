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
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
    border: 1px solid #ffd6e8;
  }

  h2 {
    font-size: 24px;
    margin-top: 0;
    margin-bottom: 10px;
    color: #333;
    font-weight: 600;
  }

  h3 {
    font-size: 18px;
    margin-top: 0;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ffd6e8;
    color: #333;
    font-weight: 600;
  }

  .stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
  }

  .stat {
    background: linear-gradient(135deg, #fff 0%, #fff0f5 100%);
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #ffd6e8;
    text-align: center;
    font-weight: 600;
    color: #333;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s, box-shadow 0.2s;
  }

  .stat:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .stat:nth-child(1) { border-left: 4px solid #ff9800; }
  .stat:nth-child(2) { border-left: 4px solid #2196F3; }
  .stat:nth-child(3) { border-left: 4px solid #4caf50; }

  .table {
    width: 100%;
    border-collapse: collapse;
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

  .badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-block;
  }

  .badge.pending {
    background: #fff3cd;
    color: #856404;
  }

  .badge.in-transit {
    background: #cfe2ff;
    color: #084298;
  }

  .badge.completed {
    background: #d1e7dd;
    color: #0f5132;
  }

  .text-center {
    text-align: center;
    color: #666;
    padding: 30px;
  }
</style>

<main>
  <div class="box">
    <h2>🚚 CHAKANOKS - Transfers</h2>
    <p style="color: #666; line-height: 1.6; margin: 0;">
      Manage stock movement between Chakanoks branches to ensure all locations have enough roasted chicken, side dishes, and supplies. Transfers help avoid shortages and reduce waste.
    </p>
  </div>

  <div class="box">
    <div class="stats">
      <div class="stat">
        <div style="font-size: 24px; color: #ff9800; margin-bottom: 5px;"><?= $stats['pending'] ?? 0 ?></div>
        <div style="font-size: 13px; color: #666;">📦 Pending Transfers</div>
      </div>
      <div class="stat">
        <div style="font-size: 24px; color: #2196F3; margin-bottom: 5px;"><?= $stats['in_transit'] ?? 0 ?></div>
        <div style="font-size: 13px; color: #666;">🚚 In Transit</div>
      </div>
      <div class="stat">
        <div style="font-size: 24px; color: #4caf50; margin-bottom: 5px;"><?= $stats['completed_today'] ?? 0 ?></div>
        <div style="font-size: 13px; color: #666;">✅ Completed Today</div>
      </div>
    </div>
  </div>

  <div class="box">
    <h3>📋 Transfer Records</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Transfer ID</th>
          <th>From Branch</th>
          <th>To Branch</th>
          <th>Item</th>
          <th>Quantity</th>
          <th>Status</th>
          <th>Expected Arrival</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($transfers)): ?>
          <?php foreach ($transfers as $transfer): ?>
            <tr>
              <td><strong><?= esc($transfer['transfer_id'] ?? 'T' . str_pad($transfer['id'], 3, '0', STR_PAD_LEFT)) ?></strong></td>
              <td><?= esc($transfer['from_branch_name'] ?? 'Unknown') ?></td>
              <td><?= esc($transfer['to_branch_name'] ?? 'Pending Assignment') ?></td>
              <td><?= esc($transfer['item_name'] ?? 'N/A') ?></td>
              <td><?= abs($transfer['quantity'] ?? 0) . ' ' . esc($transfer['item_unit'] ?? '') ?></td>
              <td>
                <?php
                  $status = strtolower($transfer['status'] ?? 'pending');
                  $statusClass = str_replace(' ', '-', $status);
                  $statusLabel = ucwords(str_replace('_', ' ', $status));
                  echo '<span class="badge ' . $statusClass . '">' . esc($statusLabel) . '</span>';
                ?>
              </td>
              <td><?= esc($transfer['expected_arrival'] ?? 'N/A') ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center">
              No transfer records found at this time.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
