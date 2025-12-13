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
    }
    .desc {
      font-size: 15px;
      color: #555;
      line-height: 1.6;
    }
    .grid {
      display: grid;
      gap: 20px;
    }
    .grid-2 {
      grid-template-columns: 1fr 1.5fr;
      align-items: start;
    }
    @media (max-width: 968px) {
      .grid-2 {
        grid-template-columns: 1fr;
      }
    }
    .form-group {
      display: flex;
      flex-direction: column;
      margin-bottom: 15px;
    }
    .form-group label {
      font-size: 14px;
      margin-bottom: 6px;
      color: #444;
      font-weight: 600;
    }
    .form-group input, .form-group select, .form-group textarea {
      padding: 10px 12px;
      border-radius: 6px;
      border: 1px solid #ddd;
      font-size: 14px;
      transition: border-color 0.3s, box-shadow 0.3s;
      background: #fff;
      width: 100%;
      box-sizing: border-box;
    }
    .form-group select {
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 12px center;
      padding-right: 35px;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
      outline: none;
      border-color: #ff69b4;
      box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
    }
    .form-group select option {
      padding: 10px;
      background: #fff;
    }
    .form-group select option:hover {
      background: #fff0f5;
    }
    .form-group input[readonly] {
      background: #f5f5f5;
      cursor: not-allowed;
    }
    .row {
      display: flex;
      flex-direction: column;
    }
    button {
      padding: 8px 14px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      margin: 2px;
      font-weight: 600;
    }
    button:hover { opacity: 0.9; }
    .btn-submit { 
      background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); 
      color: #fff; 
      width: 100%; 
      margin-top: 15px; 
      padding: 12px;
      font-size: 15px;
      font-weight: 600;
      box-shadow: 0 2px 4px rgba(255, 152, 0, 0.3);
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-submit:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(255, 152, 0, 0.4);
    }
    .btn-add-item { 
      background: #42a5f5; 
      color: #fff; 
      margin-top: 10px;
      padding: 10px 16px;
      transition: background 0.3s;
    }
    .btn-add-item:hover {
      background: #1976d2;
    }
    #total-cost {
      font-size: 18px;
      font-weight: bold;
      color: #2e7d32;
    }
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
    .badge {
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: bold;
      text-transform: uppercase;
    }
    .badge.pending { background: #ffb74d; color: #fff; }
    .badge.approved { background: #66bb6a; color: #fff; }
    .badge.po_issued_to_supplier { background: #ff9800; color: #fff; }
    .badge.scheduled_for_delivery { background: #9c27b0; color: #fff; }
    .badge.ordered { background: #42a5f5; color: #fff; }
    .badge.in_transit { background: #ab47bc; color: #fff; }
    .badge.delayed { background: #f44336; color: #fff; }
    .badge.arriving { background: #4caf50; color: #fff; }
    .badge.delivered { background: #26a69a; color: #fff; }
    .badge.delivered_to_branch { background: #00bcd4; color: #fff; }
    .badge.completed { background: #2e7d32; color: #fff; }
    .badge.cancelled { background: #e57373; color: #fff; }
    .btn-schedule { background: #9c27b0; color: #fff; }
    .btn-logistics { background: #2196F3; color: #fff; }
    .btn-receive { background: #4caf50; color: #fff; }
    .btn-confirm { background: #2e7d32; color: #fff; }
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
      background-color: #fff;
      margin: 10% auto;
      padding: 20px;
      border-radius: 8px;
      width: 90%;
      max-width: 500px;
    }
    .modal-header {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 15px;
    }
    .modal-footer {
      margin-top: 20px;
      text-align: right;
    }
    .item-row {
      display: grid;
      grid-template-columns: 2fr 0.8fr 0.8fr 1fr 1.2fr auto;
      gap: 8px;
      margin-bottom: 12px;
      align-items: end;
      padding: 12px;
      background: #fff0f5;
      border-radius: 6px;
      border: 1px solid #ffd6e8;
    }
    .item-row input {
      padding: 8px 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 13px;
      width: 100%;
      box-sizing: border-box;
      min-width: 0;
    }
    .item-row .item-name {
      min-width: 120px;
    }
    .item-row .item-quantity {
      min-width: 60px;
    }
    .item-row .item-unit {
      min-width: 60px;
    }
    .item-row .item-unit-price {
      min-width: 80px;
    }
    .item-row .item-notes {
      min-width: 100px;
    }
    .item-row .btn-remove-item {
      white-space: nowrap;
      min-width: 70px;
    }
    #items-container h4 {
      margin-top: 0;
      margin-bottom: 15px;
      color: #333;
      font-size: 16px;
    }
    .alert {
      padding: 12px;
      border-radius: 6px;
      margin-bottom: 15px;
    }
    .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
  </style>

  <div class="box">
    <h2>Purchase Orders</h2>
    <div class="desc">
      Review and manage official Purchase Orders (POs) generated from approved requests.
    </div>
  </div>

  <div id="alert-container"></div>

  <!-- Branch Manager Confirm Delivery Modal -->
  <div id="confirmModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">Confirm Delivery - Verify Stock</div>
      <form id="confirmForm">
        <input type="hidden" id="confirm_po_id" name="po_id">
        <div class="form-group">
          <label>Please verify that all delivered goods are correct and match the purchase order.</label>
          <p style="color: #666; font-size: 14px; margin-top: 5px;">
            Once confirmed, the stock will be officially recorded in branch inventory and the order will be marked as completed.
          </p>
        </div>
        <div class="form-group">
          <label for="confirm_notes">Confirmation Notes (Optional)</label>
          <textarea id="confirm_notes" name="confirmation_notes" rows="3" placeholder="Add any notes about the delivery confirmation..."></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-reject" onclick="closeConfirmModal()">Cancel</button>
          <button type="button" class="btn-confirm" onclick="submitConfirmDelivery()">Confirm & Complete</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Inventory Staff Receive Delivery Modal -->
  <div id="receiveModal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
      <div class="modal-header">Receive Delivery - Update Stock Levels</div>
      <form id="receiveForm">
        <input type="hidden" id="receive_po_id" name="po_id">
        <div id="receive-items-container" style="max-height: 400px; overflow-y: auto; margin-bottom: 15px;">
          <!-- Items will be loaded here -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-reject" onclick="closeReceiveModal()">Cancel</button>
          <button type="button" class="btn-receive" onclick="submitReceiveDelivery()">Receive Items & Update Stock</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Logistics Coordinator Delivery Timeline Modal -->
  <div id="logisticsModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">Manage Delivery Timeline</div>
      <form id="logisticsForm">
        <input type="hidden" id="logistics_po_id" name="po_id">
        <div class="form-group">
          <label for="logistics_status">Delivery Status</label>
          <select id="logistics_status" name="status" required>
            <option value="scheduled_for_delivery">Scheduled for Delivery</option>
            <option value="in_transit" selected>In Transit</option>
            <option value="delayed">Delayed</option>
            <option value="arriving">Arriving</option>
          </select>
        </div>
        <div class="form-group">
          <label for="logistics_expected_arrival">Expected Arrival Date</label>
          <input type="date" id="logistics_expected_arrival" name="expected_arrival_date">
        </div>
        <div class="form-group">
          <label for="logistics_notes">Delivery Notes (Optional)</label>
          <textarea id="logistics_notes" name="delivery_notes" rows="3" placeholder="Add any updates or notes about the delivery..."></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-reject" onclick="closeLogisticsModal()">Cancel</button>
          <button type="button" class="btn-logistics" onclick="updateDeliveryTimeline()">Update Timeline</button>
        </div>
      </form>
    </div>
  </div>

  <div class="grid grid-2">
    <div class="box" style="height: fit-content;">
      <h3 style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e0e0e0;">📊 Quick Summary</h3>
      <div class="row">
        <div class="form-group">
          <label>Total POs</label>
          <input type="text" value="<?= $stats['total'] ?? 0 ?>" readonly style="font-weight: 600; color: #1976d2;">
        </div>
        <div class="form-group">
          <label>Pending Delivery</label>
          <input type="text" value="<?= ($stats['ordered'] ?? 0) + ($stats['in_transit'] ?? 0) ?>" readonly style="font-weight: 600; color: #ff9800;">
        </div>
        <div class="form-group">
          <label>Delivered</label>
          <input type="text" value="<?= $stats['delivered'] ?? 0 ?>" readonly style="font-weight: 600; color: #4caf50;">
        </div>
        <div class="form-group">
          <label>Completed</label>
          <input type="text" value="<?= $stats['completed'] ?? 0 ?>" readonly style="font-weight: 600; color: #2e7d32;">
        </div>
      </div>
    </div>

    <div class="box">
      <h3 style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e0e0e0;">📝 Create New PO</h3>
      <form id="poForm">
        <div class="form-group">
          <label for="purchase_request_id">From Purchase Request (Optional)</label>
          <select id="purchase_request_id" name="purchase_request_id" class="form-select">
            <option value="">None - Create New</option>
            <?php if (!empty($approvedRequests)): ?>
              <?php foreach ($approvedRequests as $pr): ?>
                <option value="<?= $pr['id'] ?>" data-branch="<?= $pr['branch_id'] ?? '' ?>">
                  <?= esc($pr['request_id'] ?? 'N/A') ?> - <?= esc($pr['date_needed'] ? date('M d, Y', strtotime($pr['date_needed'])) : 'No date') ?>
                </option>
              <?php endforeach; ?>
            <?php else: ?>
              <option value="" disabled>No approved purchase requests available</option>
            <?php endif; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="supplier_id">Supplier *</label>
          <select id="supplier_id" name="supplier_id" required class="form-select">
            <option value="">-- Select Supplier --</option>
            <?php if (!empty($suppliers)): ?>
              <?php foreach ($suppliers as $supplier): ?>
                <option value="<?= $supplier['id'] ?>">
                  <?= esc($supplier['supplier_name']) ?>
                  <?php if (!empty($supplier['contact_person'])): ?>
                    - <?= esc($supplier['contact_person']) ?>
                  <?php endif; ?>
                </option>
              <?php endforeach; ?>
            <?php else: ?>
              <option value="" disabled>No suppliers available. Please add suppliers first.</option>
            <?php endif; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="branch_id">Branch (Optional)</label>
          <select id="branch_id" name="branch_id" class="form-select">
            <option value="">All Branches</option>
            <?php if (!empty($branches)): ?>
              <?php foreach ($branches as $branch): ?>
                <option value="<?= $branch['id'] ?>"><?= esc($branch['name']) ?></option>
              <?php endforeach; ?>
            <?php else: ?>
              <option value="" disabled>No branches available</option>
            <?php endif; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="order_date">Order Date</label>
          <input type="date" id="order_date" name="order_date" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-group">
          <label for="expected_delivery_date">Expected Delivery Date</label>
          <input type="date" id="expected_delivery_date" name="expected_delivery_date">
        </div>
        <div class="form-group">
          <label for="notes">Notes</label>
          <textarea id="notes" name="notes" rows="2"></textarea>
        </div>
        <div id="items-container" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
          <h4 style="margin-bottom: 15px; color: #333;">Items</h4>
          <div class="item-row">
            <input type="text" class="item-name" placeholder="Item name" required>
            <input type="number" class="item-quantity" placeholder="Qty" step="0.01" required onchange="calculateTotal()">
            <input type="text" class="item-unit" placeholder="Unit">
            <input type="number" class="item-unit-price" placeholder="Unit Price" step="0.01" required onchange="calculateTotal()">
            <input type="text" class="item-notes" placeholder="Notes">
            <button type="button" class="btn-remove-item" style="background: #e53935; color: #fff; padding: 8px 12px; font-size: 12px;" onclick="this.parentElement.remove(); calculateTotal()">Remove</button>
          </div>
        </div>
        <button type="button" class="btn-add-item" onclick="addItemRow()">+ Add Item</button>
        <div class="form-group" style="margin-top: 20px; padding-top: 15px; border-top: 2px solid #ffd6e8; background: #fff0f5; padding: 15px; border-radius: 6px;">
          <label style="font-size: 16px; margin-bottom: 8px;">Total Cost: <span id="total-cost">₱0.00</span></label>
        </div>
        <button type="submit" class="btn-submit">Generate PO</button>
      </form>
    </div>
  </div>

  <div class="box" style="margin-top: 20px;">
    <h3 style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e0e0e0;">📋 Active Purchase Orders</h3>
    <table class="table">
      <thead>
        <tr>
          <th>PO ID</th>
          <th>Supplier</th>
          <th>Branch</th>
          <th>Items</th>
          <th>Total Cost</th>
          <th>Order Date</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($orders)): ?>
          <tr>
            <td colspan="8" style="text-align: center; padding: 20px;">No purchase orders found</td>
          </tr>
        <?php else: ?>
          <?php foreach ($orders as $order): ?>
            <tr>
              <td><?= esc($order['po_id'] ?? 'N/A') ?></td>
              <td><?= esc($order['supplier_name'] ?? 'N/A') ?></td>
              <td><?= esc($order['branch_name'] ?? 'All Branches') ?></td>
              <td>
                <?php
                  $itemsList = [];
                  foreach ($order['items'] ?? [] as $item) {
                    $itemsList[] = $item['quantity'] . ' ' . $item['item_name'] . ' @ ₱' . number_format($item['unit_price'], 2);
                  }
                  echo esc(implode(', ', $itemsList) ?: 'No items');
                ?>
              </td>
              <td>₱<?= number_format($order['total_cost'] ?? 0, 2) ?></td>
              <td><?= $order['order_date'] ? date('M d, Y', strtotime($order['order_date'])) : 'N/A' ?></td>
              <td>
                <span class="badge <?= strtolower(str_replace(' ', '_', $order['status'] ?? 'pending')) ?>">
                  <?= esc(ucwords(str_replace('_', ' ', $order['status'] ?? 'pending'))) ?>
                </span>
              </td>
              <td>
                <?php if (($userRole ?? '') === 'Supplier' && ($order['status'] ?? '') === 'po_issued_to_supplier'): ?>
                  <button class="btn-schedule" onclick="scheduleDelivery(<?= $order['id'] ?>)">Schedule Delivery</button>
                <?php elseif (($userRole ?? '') === 'Logistics Coordinator' && in_array($order['status'] ?? '', ['scheduled_for_delivery', 'in_transit', 'delayed', 'arriving'])): ?>
                  <button class="btn-logistics" onclick="openLogisticsModal(<?= $order['id'] ?>, '<?= esc($order['status'] ?? '') ?>', '<?= esc($order['expected_delivery_date'] ?? '') ?>')">Manage Timeline</button>
                <?php elseif (($userRole ?? '') === 'Inventory Staff' && in_array($order['status'] ?? '', ['arriving', 'delivered'])): ?>
                  <button class="btn-receive" onclick="openReceiveModal(<?= $order['id'] ?>)">Receive Delivery</button>
                <?php elseif (($userRole ?? '') === 'Branch Manager' && ($order['status'] ?? '') === 'delivered_to_branch'): ?>
                  <button class="btn-confirm" onclick="openConfirmModal(<?= $order['id'] ?>)">Confirm Delivery</button>
                <?php else: ?>
                  <select onchange="updateStatus(<?= $order['id'] ?>, this.value)">
                    <option value="">Change Status</option>
                    <option value="pending" <?= ($order['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="approved" <?= ($order['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="po_issued_to_supplier" <?= ($order['status'] ?? '') === 'po_issued_to_supplier' ? 'selected' : '' ?>>PO Issued to Supplier</option>
                    <option value="scheduled_for_delivery" <?= ($order['status'] ?? '') === 'scheduled_for_delivery' ? 'selected' : '' ?>>Scheduled for Delivery</option>
                    <option value="ordered" <?= ($order['status'] ?? '') === 'ordered' ? 'selected' : '' ?>>Ordered</option>
                    <option value="in_transit" <?= ($order['status'] ?? '') === 'in_transit' ? 'selected' : '' ?>>In Transit</option>
                    <option value="delayed" <?= ($order['status'] ?? '') === 'delayed' ? 'selected' : '' ?>>Delayed</option>
                    <option value="arriving" <?= ($order['status'] ?? '') === 'arriving' ? 'selected' : '' ?>>Arriving</option>
                    <option value="delivered" <?= ($order['status'] ?? '') === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                    <option value="delivered_to_branch" <?= ($order['status'] ?? '') === 'delivered_to_branch' ? 'selected' : '' ?>>Delivered to Branch</option>
                    <option value="completed" <?= ($order['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="cancelled" <?= ($order['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                  </select>
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
function addItemRow() {
  const container = document.getElementById('items-container');
  const newRow = document.createElement('div');
  newRow.className = 'item-row';
  newRow.innerHTML = `
    <input type="text" class="item-name" placeholder="Item name" required>
    <input type="number" class="item-quantity" placeholder="Qty" step="0.01" required onchange="calculateTotal()">
    <input type="text" class="item-unit" placeholder="Unit">
    <input type="number" class="item-unit-price" placeholder="Unit Price" step="0.01" required onchange="calculateTotal()">
    <input type="text" class="item-notes" placeholder="Notes">
    <button type="button" class="btn-remove-item" style="background: #e53935; color: #fff;" onclick="this.parentElement.remove(); calculateTotal()">Remove</button>
  `;
  container.appendChild(newRow);
}

function calculateTotal() {
  let total = 0;
  document.querySelectorAll('.item-row').forEach(row => {
    const qty = parseFloat(row.querySelector('.item-quantity').value) || 0;
    const price = parseFloat(row.querySelector('.item-unit-price').value) || 0;
    total += qty * price;
  });
  document.getElementById('total-cost').textContent = '₱' + total.toFixed(2);
}

// Auto-fill branch when PR is selected
document.getElementById('purchase_request_id').addEventListener('change', function() {
  const option = this.options[this.selectedIndex];
  if (option.value && option.dataset.branch) {
    document.getElementById('branch_id').value = option.dataset.branch;
  }
});

document.getElementById('poForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  
  const formData = new FormData(this);
  const items = [];
  
  document.querySelectorAll('.item-row').forEach(row => {
    const name = row.querySelector('.item-name').value;
    const quantity = row.querySelector('.item-quantity').value;
    const unit = row.querySelector('.item-unit').value;
    const unitPrice = row.querySelector('.item-unit-price').value;
    const notes = row.querySelector('.item-notes').value;
    
    if (name && quantity && unitPrice) {
      items.push({
        item_name: name,
        quantity: quantity,
        unit: unit,
        unit_price: unitPrice,
        notes: notes
      });
    }
  });
  
  if (items.length === 0) {
    showAlert('Please add at least one item', 'error');
    return;
  }
  
  const data = {
    purchase_request_id: formData.get('purchase_request_id') || null,
    supplier_id: formData.get('supplier_id'),
    branch_id: formData.get('branch_id') || null,
    order_date: formData.get('order_date'),
    expected_delivery_date: formData.get('expected_delivery_date') || null,
    notes: formData.get('notes'),
    items: items
  };
  
  try {
    const response = await fetch('<?= base_url('purchase-orders/create') ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify(data)
    });
    
    const result = await response.json();
    
    if (result.success) {
      showAlert(result.message, 'success');
      setTimeout(() => location.reload(), 1500);
    } else {
      showAlert(result.message, 'error');
    }
  } catch (error) {
    showAlert('Error creating PO: ' + error.message, 'error');
  }
});

function updateStatus(id, status) {
  if (!status) return;
  
  if (!confirm(`Change status to ${status}?`)) {
    location.reload();
    return;
  }
  
  fetch(`<?= base_url('purchase-orders/update-status/') ?>${id}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({ status: status })
  })
  .then(r => r.json())
  .then(result => {
    if (result.success) {
      showAlert(result.message, 'success');
      setTimeout(() => location.reload(), 1500);
    } else {
      showAlert(result.message, 'error');
    }
  });
}

function scheduleDelivery(id) {
  if (!confirm('Mark this purchase order as scheduled for delivery?')) return;
  
  fetch(`<?= base_url('purchase-orders/schedule-delivery/') ?>${id}`, {
    method: 'POST',
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(r => r.json())
  .then(result => {
    if (result.success) {
      showAlert(result.message, 'success');
      setTimeout(() => location.reload(), 1500);
    } else {
      showAlert(result.message, 'error');
    }
  })
  .catch(error => {
    showAlert('Error: ' + error.message, 'error');
  });
}

let currentLogisticsPoId = null;

function openLogisticsModal(poId, currentStatus, expectedDate) {
  currentLogisticsPoId = poId;
  document.getElementById('logistics_po_id').value = poId;
  document.getElementById('logistics_status').value = currentStatus || 'in_transit';
  document.getElementById('logistics_expected_arrival').value = expectedDate || '';
  document.getElementById('logistics_notes').value = '';
  document.getElementById('logisticsModal').style.display = 'block';
}

function closeLogisticsModal() {
  document.getElementById('logisticsModal').style.display = 'none';
  currentLogisticsPoId = null;
}

function updateDeliveryTimeline() {
  if (!currentLogisticsPoId) {
    showAlert('Error: PO ID not found', 'error');
    return;
  }
  
  const status = document.getElementById('logistics_status').value;
  const expectedArrival = document.getElementById('logistics_expected_arrival').value;
  const notes = document.getElementById('logistics_notes').value;
  
  const data = {
    status: status,
    expected_arrival_date: expectedArrival || null,
    delivery_notes: notes || null
  };
  
  fetch(`<?= base_url('purchase-orders/update-delivery-timeline/') ?>${currentLogisticsPoId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify(data)
  })
  .then(r => r.json())
  .then(result => {
    if (result.success) {
      showAlert(result.message, 'success');
      closeLogisticsModal();
      setTimeout(() => location.reload(), 1500);
    } else {
      showAlert(result.message, 'error');
    }
  })
  .catch(error => {
    showAlert('Error: ' + error.message, 'error');
  });
}

let currentReceivePoId = null;

function openReceiveModal(poId) {
  currentReceivePoId = poId;
  document.getElementById('receive_po_id').value = poId;
  
  // Fetch PO details and items
  fetch(`<?= base_url('purchase-orders/get/') ?>${poId}`)
    .then(r => r.json())
    .then(result => {
      if (result.success && result.data) {
        const container = document.getElementById('receive-items-container');
        container.innerHTML = '';
        
        result.data.items.forEach(item => {
          const itemRow = document.createElement('div');
          itemRow.className = 'form-group';
          itemRow.style.border = '1px solid #ddd';
          itemRow.style.padding = '15px';
          itemRow.style.marginBottom = '10px';
          itemRow.style.borderRadius = '6px';
          itemRow.innerHTML = `
            <div style="font-weight: bold; margin-bottom: 10px;">${item.item_name} (Expected: ${item.quantity} ${item.unit || 'pcs'})</div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
              <div>
                <label>Received Quantity (Good)</label>
                <input type="number" class="received-qty" data-item-id="${item.item_id || ''}" data-item-name="${item.item_name}" step="0.01" min="0" max="${item.quantity}" value="${item.quantity}" required>
              </div>
              <div>
                <label>Expiry Date (if applicable)</label>
                <input type="date" class="expiry-date" data-item-id="${item.item_id || ''}">
              </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
              <div>
                <label>Damaged Quantity</label>
                <input type="number" class="damaged-qty" data-item-id="${item.item_id || ''}" step="0.01" min="0" max="${item.quantity}" value="0">
                <textarea class="damage-notes" placeholder="Damage notes (optional)" rows="2" style="margin-top: 5px; width: 100%;"></textarea>
              </div>
              <div>
                <label>Expired Quantity</label>
                <input type="number" class="expired-qty" data-item-id="${item.item_id || ''}" step="0.01" min="0" max="${item.quantity}" value="0">
                <textarea class="expiry-notes" placeholder="Expiry notes (optional)" rows="2" style="margin-top: 5px; width: 100%;"></textarea>
              </div>
            </div>
          `;
          container.appendChild(itemRow);
        });
        
        document.getElementById('receiveModal').style.display = 'block';
      } else {
        showAlert('Error loading order details', 'error');
      }
    })
    .catch(error => {
      showAlert('Error: ' + error.message, 'error');
    });
}

function closeReceiveModal() {
  document.getElementById('receiveModal').style.display = 'none';
  currentReceivePoId = null;
}

function submitReceiveDelivery() {
  if (!currentReceivePoId) {
    showAlert('Error: PO ID not found', 'error');
    return;
  }
  
  const items = [];
  document.querySelectorAll('#receive-items-container .form-group').forEach(row => {
    const itemId = row.querySelector('.received-qty').dataset.itemId;
    const itemName = row.querySelector('.received-qty').dataset.itemName;
    const receivedQty = parseFloat(row.querySelector('.received-qty').value) || 0;
    const damagedQty = parseFloat(row.querySelector('.damaged-qty').value) || 0;
    const expiredQty = parseFloat(row.querySelector('.expired-qty').value) || 0;
    const expiryDate = row.querySelector('.expiry-date').value || null;
    const damageNotes = row.querySelector('.damage-notes').value || null;
    const expiryNotes = row.querySelector('.expiry-notes').value || null;
    
    items.push({
      item_id: itemId || null,
      item_name: itemName,
      received_quantity: receivedQty,
      damaged_quantity: damagedQty,
      expired_quantity: expiredQty,
      expiry_date: expiryDate,
      damage_notes: damageNotes,
      expiry_notes: expiryNotes
    });
  });
  
  fetch(`<?= base_url('purchase-orders/receive-delivery/') ?>${currentReceivePoId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({ items: items })
  })
  .then(r => r.json())
  .then(result => {
    if (result.success) {
      showAlert(result.message, 'success');
      closeReceiveModal();
      setTimeout(() => location.reload(), 1500);
    } else {
      showAlert(result.message, 'error');
    }
  })
  .catch(error => {
    showAlert('Error: ' + error.message, 'error');
  });
}

let currentConfirmPoId = null;

function openConfirmModal(poId) {
  currentConfirmPoId = poId;
  document.getElementById('confirm_po_id').value = poId;
  document.getElementById('confirm_notes').value = '';
  document.getElementById('confirmModal').style.display = 'block';
}

function closeConfirmModal() {
  document.getElementById('confirmModal').style.display = 'none';
  currentConfirmPoId = null;
}

function submitConfirmDelivery() {
  if (!currentConfirmPoId) {
    showAlert('Error: PO ID not found', 'error');
    return;
  }
  
  if (!confirm('Confirm that all delivered goods are correct and stock is verified?')) {
    return;
  }
  
  const confirmationNotes = document.getElementById('confirm_notes').value || null;
  
  fetch(`<?= base_url('purchase-orders/confirm-delivery/') ?>${currentConfirmPoId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({ confirmation_notes: confirmationNotes })
  })
  .then(r => r.json())
  .then(result => {
    if (result.success) {
      showAlert(result.message, 'success');
      closeConfirmModal();
      setTimeout(() => location.reload(), 1500);
    } else {
      showAlert(result.message, 'error');
    }
  })
  .catch(error => {
    showAlert('Error: ' + error.message, 'error');
  });
}

// Close modal when clicking outside
window.onclick = function(event) {
  const logisticsModal = document.getElementById('logisticsModal');
  const receiveModal = document.getElementById('receiveModal');
  const confirmModal = document.getElementById('confirmModal');
  if (event.target == logisticsModal) {
    closeLogisticsModal();
  }
  if (event.target == receiveModal) {
    closeReceiveModal();
  }
  if (event.target == confirmModal) {
    closeConfirmModal();
  }
}

function showAlert(message, type) {
  const container = document.getElementById('alert-container');
  container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
  setTimeout(() => container.innerHTML = '', 5000);
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
