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
      margin-bottom: 20px;
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
    .badge.scheduled {
      background: #ff9800;
      color: #fff;
    }
    .badge.in-transit {
      background: #2196F3;
      color: #fff;
    }
    .badge.delivered {
      background: #4caf50;
      color: #fff;
    }
    .badge.received {
      background: #2e7d32;
      color: #fff;
    }
    .btn-action {
      background: #4caf50;
      color: #fff;
      padding: 8px 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      transition: background 0.3s;
    }
    .btn-action:hover {
      background: #45a049;
    }
    .text-center {
      text-align: center;
      color: #666;
    }
  </style>

  <div class="box">
    <h2>🚚 Deliveries</h2>
    <div style="font-size: 15px; color: #555; line-height: 1.6; margin-bottom: 20px;">
      Track and manage deliveries to branches. Monitor delivery status and receive items at branch locations.
    </div>
  </div>

  <?php if (!$isBranchUser): ?>
  <div class="stats">
    <div class="stat">
      <div style="font-size: 24px; color: #ff9800; margin-bottom: 5px;"><?= $scheduled ?? 0 ?></div>
      <div style="font-size: 13px; color: #666;">📦 Scheduled Today</div>
    </div>
    <div class="stat">
      <div style="font-size: 24px; color: #2196F3; margin-bottom: 5px;"><?= $inTransit ?? 0 ?></div>
      <div style="font-size: 13px; color: #666;">🚚 In Transit</div>
    </div>
    <div class="stat">
      <div style="font-size: 24px; color: #4caf50; margin-bottom: 5px;"><?= $delivered ?? 0 ?></div>
      <div style="font-size: 13px; color: #666;">✅ Delivered</div>
    </div>
  </div>
  <?php endif; ?>

  <div class="box">
    <h3><?php echo ($isBranchUser ? '📍 Your Branch Deliveries' : '📋 Upcoming Deliveries'); ?></h3>
    <table class="table">
      <thead>
        <tr>
          <th>Delivery ID</th>
          <th><?php echo ($isBranchUser ? 'Branch Location' : 'Destination Branch'); ?></th>
          <th>Items</th>
          <th>Driver</th>
          <th>Status</th>
          <?php if ($isBranchUser && $userRole === 'Inventory Staff'): ?>
            <th>Action</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($deliveries)): ?>
          <?php foreach ($deliveries as $delivery): ?>
            <tr>
              <td><strong><?php echo esc($delivery['delivery_id'] ?? $delivery['id']); ?></strong></td>
              <td><?php echo esc($delivery['branch_name']); ?></td>
              <td><?php echo esc($delivery['items']); ?></td>
              <td><?php echo esc($delivery['driver'] ?? 'N/A'); ?></td>
              <td>
                <?php
                  $status = strtolower(str_replace('_', '-', $delivery['status'] ?? 'scheduled'));
                  $statusClass = $status;
                  echo '<span class="badge ' . $statusClass . '">' . esc(ucwords(str_replace('_', ' ', $delivery['status'] ?? 'scheduled'))) . '</span>';
                ?>
              </td>
              <?php if ($isBranchUser && $userRole === 'Inventory Staff' && isset($delivery['can_approve']) && $delivery['can_approve'] && $delivery['status'] === 'delivered'): ?>
                <td>
                  <button onclick="receiveDelivery('<?php echo htmlspecialchars($delivery['delivery_id']); ?>', <?php echo $delivery['branch_id']; ?>)"
                          class="btn-action">Receive</button>
                </td>
              <?php elseif ($isBranchUser && $userRole === 'Inventory Staff'): ?>
                <td>
                  <?php if ($delivery['status'] === 'received'): ?>
                    <span style="color: #2e7d32; font-weight: 600;">✓ Already Received</span>
                  <?php elseif ($delivery['status'] === 'delivered'): ?>
                    <span style="color: #ff9800; font-weight: 600;">Ready to Receive</span>
                  <?php else: ?>
                    <span style="color: #666;">Not Delivered</span>
                  <?php endif; ?>
                </td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
            <tr>
              <td colspan="<?php echo ($isBranchUser && $userRole === 'Inventory Staff' ? '6' : '5'); ?>" class="text-center" style="padding: 30px;">
                No deliveries found at this time.
              </td>
            </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

    <script>
      function receiveDelivery(deliveryId, branchId) {
        if (confirm('Confirm receipt of delivery? This will add all items to your branch inventory.')) {
          fetch('<?php echo base_url('deliveries/approve'); ?>', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
              delivery_id: deliveryId,
              branch_id: branchId
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Delivery received! Items added to inventory.');
              location.reload();
            } else {
              alert(data.message || 'Error receiving delivery');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
          });
        }
      }
    </script>
  </main>

<?php include __DIR__ . '/includes/footer.php'; ?>
