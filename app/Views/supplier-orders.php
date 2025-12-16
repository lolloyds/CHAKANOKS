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
    .badge.arrived { background: #795548; color: #fff; }
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
    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
    }
    .modal-content {
      background-color: #fff5f8;
      margin: 10% auto;
      padding: 0;
      border-radius: 12px;
      width: 90%;
      max-width: 500px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.2);
      border: 1px solid #ffd6e8;
    }
    .modal-header {
      padding: 20px 25px;
      border-bottom: 2px solid #ffd6e8;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .modal-header h3 {
      margin: 0;
      color: #333;
      font-size: 18px;
    }
    .close {
      color: #999;
      font-size: 24px;
      font-weight: bold;
      cursor: pointer;
      transition: color 0.3s;
    }
    .close:hover {
      color: #333;
    }
    .modal-body {
      padding: 25px;
    }
    .status-options {
      margin: 20px 0;
    }
    .status-option {
      display: block;
      padding: 15px;
      margin: 10px 0;
      border: 2px solid #ffd6e8;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s;
      background: #fff;
    }
    .status-option:hover {
      border-color: #ff69b4;
      background: #fff0f5;
    }
    .status-option input[type="radio"] {
      margin-right: 10px;
    }
    .status-label {
      font-weight: 600;
      color: #333;
      display: block;
      margin-bottom: 5px;
    }
    .status-option small {
      color: #666;
      font-size: 12px;
    }

    .modal-footer {
      padding: 20px 25px;
      border-top: 2px solid #ffd6e8;
      text-align: right;
    }
    .btn-cancel {
      background: #999;
      color: white;
      margin-right: 10px;
    }
    .btn-save {
      background: linear-gradient(135deg, #4CAF50 0%, #388e3c 100%);
      color: white;
      box-shadow: 0 2px 6px rgba(76,175,80,0.3);
    }
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
        <div style="font-size: 20px; font-weight: bold; color: #795548;"><?= $stats['arrived'] ?? 0 ?></div>
        <div style="font-size: 12px; color: #666;">Arrived</div>
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
                  <button class="btn-update" onclick="openStatusModal(<?= $order['id'] ?>, '<?= $order['status'] ?>')">Update Status</button>
                <?php elseif (($order['status'] ?? '') === 'arrived'): ?>
                  <span style="color: #ff9800; font-weight: 600;">Waiting for Branch to Claim</span>
                <?php elseif (($order['status'] ?? '') === 'delivered'): ?>
                  <button class="btn-submit" onclick="submitInvoice(<?= $order['id'] ?>)">Send Invoice</button>
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

<!-- Status Update Modal -->
<div id="statusModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3>Update Delivery Status</h3>
      <span class="close" onclick="closeStatusModal()">&times;</span>
    </div>
    <div class="modal-body">
      <p>Select the new delivery status:</p>
      <div class="status-options">
        <label class="status-option">
          <input type="radio" name="newStatus" value="in_transit">
          <span class="status-label">🚚 In Transit</span>
          <small>Order is currently being delivered</small>
        </label>
        <label class="status-option">
          <input type="radio" name="newStatus" value="delayed">
          <span class="status-label">⏰ Delayed</span>
          <small>Delivery is experiencing delays</small>
        </label>

        <label class="status-option">
          <input type="radio" name="newStatus" value="arrived">
          <span class="status-label">📍 Arrived</span>
          <small>Order has arrived at destination</small>
        </label>
      </div>

    </div>
    <div class="modal-footer">
      <button class="btn-cancel" onclick="closeStatusModal()">Cancel</button>
      <button class="btn-save" onclick="saveStatusUpdate()">Save Status</button>
    </div>
  </div>
</div>

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

// Modal functionality
let currentOrderId = null;

function openStatusModal(orderId, currentStatus) {
  currentOrderId = orderId;
  
  // Clear previous selections
  document.querySelectorAll('input[name="newStatus"]').forEach(radio => {
    radio.checked = false;
  });
  
  // Select current status
  const currentRadio = document.querySelector(`input[name="newStatus"][value="${currentStatus}"]`);
  if (currentRadio) {
    currentRadio.checked = true;
  }
  

  
  // Show modal
  document.getElementById('statusModal').style.display = 'block';
}

function closeStatusModal() {
  document.getElementById('statusModal').style.display = 'none';
  currentOrderId = null;
}

function saveStatusUpdate() {
  const selectedStatus = document.querySelector('input[name="newStatus"]:checked');
  if (!selectedStatus) {
    alert('Please select a status');
    return;
  }
  
  const newStatus = selectedStatus.value;
  
  const requestData = { status: newStatus };
  
  fetch(`<?= base_url('purchase-orders/update-delivery-timeline/') ?>${currentOrderId}`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify(requestData)
  })
  .then(r => r.json())
  .then(result => {
    if (result.success) {
      alert('Status updated successfully!');
      closeStatusModal();
      location.reload();
    } else {
      alert('Error: ' + result.message);
    }
  })
  .catch(error => {
    alert('Error updating status: ' + error.message);
  });
}

// Close modal when clicking outside
window.onclick = function(event) {
  const modal = document.getElementById('statusModal');
  if (event.target == modal) {
    closeStatusModal();
  }
}

// Submit invoice (placeholder - would need actual implementation)
function submitInvoice(orderId) {
  alert('Invoice submission functionality to be implemented. Order ID: ' + orderId);
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
