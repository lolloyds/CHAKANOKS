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
    h2 {
      font-size: 24px;
      margin-top: 0;
      margin-bottom: 8px;
      color: #333;
      font-weight: 600;
    }
    .desc {
      font-size: 15px;
      color: #555;
      line-height: 1.6;
      margin-bottom: 20px;
    }
    .stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      margin-bottom: 25px;
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
    .stat:nth-child(1) { border-left: 4px solid #2196F3; }
    .stat:nth-child(2) { border-left: 4px solid #ff9800; }
    .stat:nth-child(3) { border-left: 4px solid #4caf50; }
    .stat:nth-child(4) { border-left: 4px solid #9c27b0; }
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
    .badge.active {
      background: #4caf50;
      color: #fff;
    }
    .badge.pending {
      background: #ff9800;
      color: #fff;
    }
    .badge.in-progress {
      background: #2196F3;
      color: #fff;
    }
  </style>

  <div class="box">
    <h2>🏢 Franchise Management</h2>
    <div class="desc">
      Manage franchise partnerships nationwide. Track applications, approved branches, royalty payments, and supply allocations to ensure consistent product quality and strong business relationships.
    </div>
  </div>

  <div class="stats">
    <div class="stat">
      <div style="font-size: 24px; color: #2196F3; margin-bottom: 5px;"><?= $stats['total'] ?? 0 ?></div>
      <div style="font-size: 13px; color: #666;">Total Franchise Partners</div>
    </div>
    <div class="stat">
      <div style="font-size: 24px; color: #ff9800; margin-bottom: 5px;"><?= ($stats['pending_approval'] ?? 0) + ($stats['in_progress'] ?? 0) ?></div>
      <div style="font-size: 13px; color: #666;">Applications in Progress</div>
    </div>
    <div class="stat">
      <div style="font-size: 24px; color: #4caf50; margin-bottom: 5px;"><?= $stats['active'] ?? 0 ?></div>
      <div style="font-size: 13px; color: #666;">Active Branches</div>
    </div>
    <div class="stat">
      <div style="font-size: 24px; color: #9c27b0; margin-bottom: 5px;">₱<?= number_format($stats['total_monthly_royalty'] ?? 0, 2) ?></div>
      <div style="font-size: 13px; color: #666;">Monthly Royalty Collected</div>
    </div>
  </div>

  <div class="box">
    <h3 style="margin-top: 0; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #ffd6e8; font-size: 18px;">📋 Franchise Details</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Franchise ID</th>
          <th>Branch Name</th>
          <th>Owner</th>
          <th>Location</th>
          <th>Contact Number</th>
          <th>Status</th>
          <th>Monthly Sales</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($franchises)): ?>
          <tr>
            <td colspan="7" style="text-align: center; padding: 30px; color: #666;">
              No franchise records found. Franchises will appear here once added to the system.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($franchises as $franchise): ?>
            <tr>
              <td><strong><?= esc($franchise['franchise_id'] ?? 'N/A') ?></strong></td>
              <td><?= esc($franchise['branch_name'] ?? 'N/A') ?></td>
              <td><?= esc($franchise['owner_name'] ?? 'N/A') ?></td>
              <td><?= esc($franchise['location'] ?? 'N/A') ?></td>
              <td><?= esc($franchise['contact_number'] ?? 'N/A') ?></td>
              <td>
                <?php
                  $status = $franchise['status'] ?? 'Application In Progress';
                  $statusClass = strtolower(str_replace(' ', '-', $status));
                  if ($statusClass === 'active') {
                    echo '<span class="badge active">Active</span>';
                  } elseif ($statusClass === 'pending-approval') {
                    echo '<span class="badge pending">Pending Approval</span>';
                  } elseif ($statusClass === 'application-in-progress') {
                    echo '<span class="badge in-progress">Application In Progress</span>';
                  } else {
                    echo '<span class="badge pending">' . esc($status) . '</span>';
                  }
                ?>
              </td>
              <td>
                <?php if (!empty($franchise['monthly_sales']) && $franchise['monthly_sales'] > 0): ?>
                  <strong>₱<?= number_format($franchise['monthly_sales'], 2) ?></strong>
                <?php else: ?>
                  —
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

