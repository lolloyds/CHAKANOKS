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
    .badge {
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: bold;
      text-transform: uppercase;
    }
    .badge.pending { background: #ffb74d; color: #fff; }
    .badge.pending_delivery_schedule { background: #2196f3; color: #fff; }
    .badge.scheduled_for_delivery { background: #4caf50; color: #fff; }
    .badge.in_transit { background: #ff9800; color: #fff; }
    .badge.arriving { background: #795548; color: #fff; }
    .badge.delivered { background: #607d8b; color: #fff; }
    .badge.completed { background: #4caf50; color: #fff; }
    .table {
      width: 100%;
      border-collapse: collapse;
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
    .table tr:hover {
      background: #fafafa;
    }
    button {
      padding: 6px 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 12px;
      font-weight: 600;
      margin: 2px;
    }
    .btn-schedule { background: #4CAF50; color: #fff; }
    .btn-update { background: #2196F3; color: #fff; }
    .btn-submit { background: #FF9800; color: #fff; }
  </style>

  <div class="box">
    <h2>📦 Purchase Orders - <?= esc($supplier['supplier_name'] ?? ' supplier') ?></h2>
    <div class="desc">
      View and manage orders assigned to your supplier. Update delivery status and submit invoices.
    </div>
  </div>

  <div class="box">
    <h3>📊 Quick Summary</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 15px;">
      <div style="text-align: center; padding: 12px; background: #fff0f5; border-radius: 8px;">
        <div style="font-size: 20px; font-weight: bold; color: #1976d2;"><?= $stats['pending_delivery_schedule'] ?? 0 ?></div>
        <div style="font-size: 12px; color: #666;">Pending Schedule</div>
      </div>
      <div style="text-align: center; padding: 12px; background: #fff0f5; border-radius: 8px;">
        <div style="font-size: 20px; font-weight: bold; color: #4caf50;"><?= $stats['scheduled_for_delivery'] ?? 0 ?></div>
        <div style="font-size: 12px; color: #666;">Scheduled</div>
      </div>
      <div style="text-align: center; padding: 12px; background: #fff0f5; border-radius: 8px;">
        <div style="font-size: 20px; font-weight: bold; color: #ff9800;"><?= $stats['in_transit'] ?? 0 ?></div>
        <div style="font-size: 12px; color: #666;">In Transit</div>
      </div>
      <div style="text-align: center; padding: 12px; background: #fff0f5; border-radius: 8px;">
        <div style="font-size: 20px; font-weight: bold; color: #795548;"><?= $stats['arriving'] ?? 0 ?></div>
        <div style="font-size: 12px; color: #666;">Arriving</div>
      </div>
      <div style="text-align: center; padding: 12px; background: #fff0f5; border-radius: 8px;">
        <div style="font-size: 20px; font-weight: bold; color: #607d8b;"><?= $stats['delivered'] ?? 0 ?></div>
        <div style="font-size: 12px; color: #666;">Delivered</div>
      </div>
    </div>
  </div>

  <div class="box">
    <h3>📋 Orders List</h3>
    <table class="table">
      <thead>
        <tr>
          <th>PO ID</th>
          <th>Branch</th>
          <th>Order Date</th>
          <th>Expected Delivery</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($orders)): ?>
          <tr>
            <td colspan="6" style="text-align: center; padding: 20px;">No orders assigned to your supplier</td>
          </tr>
        <?php else: ?>
          <?php foreach ($orders as $order): ?>
            <tr>
              <td><?= esc($order['po_id'] ?? 'N/A') ?></td>
              <td><?= esc($order['branch_name'] ?? 'N/A') ?></td>
              <td><?= $order['order_date'] ? date('M d, Y', strtotime($order['order_date'])) : 'N/A' ?></td>
              <td><?= $order['expected_delivery_date'] ? date('M d, Y', strtotime($order['expected_delivery_date'])) : 'N/A' ?></td>
              <td>
                <span class="badge <?= strtolower(str_replace('_', '-', $order['status'] ?? 'pending')) ?>">
                  <?= ucwords(str_replace('_', ' ', $order['status'] ?? 'pending')) ?>
                </span>
              </td>
              <td>
                <?php if (($order['status'] ?? '') === 'scheduled_for_delivery'): ?>
                  <button class="btn-update" onclick="startTransit(<?= $order['id'] ?>)">Start Delivery</button>
                <?php elseif (in_array($order['status'] ?? '', ['in_transit', 'delayed'])): ?>
                  <button class="btn-update" onclick="updateStatus(<?= $order['id'] ?>, '<?= $order['status'] ?>')">Update Status</button>
                <?php elseif (($order['status'] ?? '') === 'delivered'): ?>
                  <button class="btn-submit" onclick="submitInvoice(<?= $order['id'] ?>)">Submit Invoice</button>
                <?php else: ?>
                  <span style="color: #999; font-size: 12px;">Waiting for schedule</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<script>
// Start delivery transit
function startTransit(orderId) {
  if (!confirm('Confirm that you have started delivery for this order?')) return;

  fetch(`<?= base_url('purchase-orders/update-delivery-timeline/') ?>${orderId}`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ status: 'in_transit' })
  })
  .then(r => r.json())
  .then(result => {
    if (result.success) {
      alert('Delivery started successfully!');
      location.reload();
    } else {
      alert('Error: ' + result.message);
    }
  });
}

// Update delivery status
function updateStatus(orderId, currentStatus) {
  const newStatus = prompt('Update delivery status to:', currentStatus);
  if (!newStatus) return;

  fetch(`<?= base_url('purchase-orders/update-delivery-timeline/') ?>${orderId}`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ status: newStatus })
  })
  .then(r => r.json())
  .then(result => {
    if (result.success) {
      alert('Status updated successfully!');
      location.reload();
    } else {
      alert('Error: ' + result.message);
    }
  });
}

// Submit invoice (placeholder - would need actual implementation)
function submitInvoice(orderId) {
  alert('Invoice submission functionality to be implemented. Order ID: ' + orderId);
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
