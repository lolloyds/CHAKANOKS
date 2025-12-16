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
    .badge.pending_delivery_schedule { background: #ff9800; color: #fff; }
    .badge.scheduled_for_delivery { background: #9c27b0; color: #fff; }
    .badge.ordered { background: #42a5f5; color: #fff; }
    .badge.in_transit { background: #ab47bc; color: #fff; }
    .badge.delayed { background: #f44336; color: #fff; }
    .badge.arrived { background: #4caf50; color: #fff; }
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
      min-width: 120px;
      padding: 8px 30px 8px 10px;
      border: 1px solid #ffd6e8;
      border-radius: 6px;
      background: #fff;
      font-size: 13px;
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 10px center;
    }
    .item-row .item-unit:focus {
      outline: none;
      border-color: #ff69b4;
      box-shadow: 0 0 0 2px rgba(255, 105, 180, 0.1);
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

  <!-- Change Supplier Modal -->
  <div id="changeSupplierModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">Change Supplier</div>
      <form id="changeSupplierForm">
        <input type="hidden" id="change_supplier_po_id" name="po_id">
        <div class="form-group">
          <label>Select New Supplier</label>
          <select id="change_supplier_id" name="supplier_id" class="form-select" required>
            <option value="">-- Select Supplier --</option>
            <?php if (!empty($suppliers)): ?>
              <?php foreach ($suppliers as $supplier): ?>
                <option value="<?= $supplier['id'] ?>" data-name="<?= esc($supplier['supplier_name']) ?>">
                  <?= esc($supplier['supplier_name']) ?>
                  <?php if (!empty($supplier['contact_person'])): ?>
                    - <?= esc($supplier['contact_person']) ?>
                  <?php endif; ?>
                </option>
              <?php endforeach; ?>
            <?php else: ?>
              <option value="" disabled>No suppliers available</option>
            <?php endif; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="supplier_change_notes">Change Reason (Optional)</label>
          <textarea id="supplier_change_notes" name="change_notes" rows="2" placeholder="Reason for supplier change..."></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-reject" onclick="closeChangeSupplierModal()">Cancel</button>
          <button type="button" class="btn-schedule" onclick="submitChangeSupplier()">Change Supplier</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Schedule Delivery Modal -->
  <div id="scheduleModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
      <div class="modal-header">Schedule Delivery</div>
      <div id="schedule-details">
        <!-- PO details will be loaded here -->
      </div>
      <form id="scheduleForm">
        <input type="hidden" id="schedule_po_id" name="po_id">
        <div class="form-group">
          <label for="schedule_delivery_date">Delivery Date *</label>
          <input type="date" id="schedule_delivery_date" name="delivery_date" required min="<?= date('Y-m-d') ?>">
        </div>
        <div class="form-group">
          <label for="schedule_notes">Schedule Notes (Optional)</label>
          <textarea id="schedule_notes" name="notes" rows="3" placeholder="Add any scheduling notes, route optimization, or delivery instructions..."></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-reject" onclick="closeScheduleModal()">Cancel</button>
          <button type="button" class="btn-schedule" onclick="createSchedule()">Create Schedule</button>
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

    <?php if (in_array($userRole ?? '', ['Central Office Admin', 'System Administrator'])): ?>
    <div class="box">
      <h3 style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e0e0e0;">📝 Create Custom Order</h3>
      <div style="background: #e8f5e8; border: 1px solid #4caf50; padding: 12px; border-radius: 6px; margin-bottom: 15px;">
        <strong>Central Office Only:</strong> Create custom purchase orders that are not based on existing purchase requests. This allows for strategic purchasing decisions.
      </div>
      <form id="poForm">
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
          <label for="branch_id">Delivery Branch *</label>
          <select id="branch_id" name="branch_id" class="form-select" required>
            <option value="">-- Select Branch --</option>
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
          <label for="notes">Order Notes</label>
          <textarea id="notes" name="notes" rows="2" placeholder="Strategic purchasing rationale or special instructions"></textarea>
        </div>
        <div id="items-container" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
          <h4 style="margin-bottom: 15px; color: #333;">Items to Order</h4>
          <div class="item-row">
            <input type="text" class="item-name" placeholder="Item name" required>
            <input type="number" class="item-quantity" placeholder="Qty" step="0.01" required onchange="calculateTotal()">
            <select class="item-unit" required>
              <option value="">Select Unit</option>
              <option value="kg">kg (Kilogram)</option>
              <option value="g">g (Gram)</option>
              <option value="pcs">pcs (Pieces)</option>
              <option value="box">box</option>
              <option value="pack">pack</option>
              <option value="bottle">bottle</option>
              <option value="can">can</option>
              <option value="liter">liter</option>
              <option value="ml">ml (Milliliter)</option>
              <option value="gallon">gallon</option>
              <option value="bag">bag</option>
              <option value="sack">sack</option>
              <option value="carton">carton</option>
              <option value="case">case</option>
              <option value="dozen">dozen</option>
              <option value="unit">unit</option>
            </select>
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
    <?php else: ?>
    <div class="box">
      <h3 style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e0e0e0;">📋 Purchase Orders</h3>
      <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 6px; text-align: center;">
        <strong>Central Office Feature</strong><br>
        Custom order creation is available to Central Office administrators only.
      </div>
    </div>
    <?php endif; ?>
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
            <td colspan="7" style="text-align: center; padding: 20px;">No purchase orders found</td>
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
                <?php if (($userRole ?? '') === 'Logistics Coordinator' && ($order['status'] ?? '') === 'pending_delivery_schedule'): ?>
                  <button class="btn-schedule" onclick="openScheduleModal(<?= $order['id'] ?>)">Schedule Delivery</button>
                <?php elseif (($userRole ?? '') === 'Logistics Coordinator' && in_array($order['status'] ?? '', ['scheduled_for_delivery', 'in_transit', 'delayed', 'arriving'])): ?>
                  <button class="btn-logistics" onclick="openLogisticsModal(<?= $order['id'] ?>, '<?= esc($order['status'] ?? '') ?>', '<?= esc($order['expected_delivery_date'] ?? '') ?>')">Manage Timeline</button>
                <?php elseif (($userRole ?? '') === 'Inventory Staff' && in_array($order['status'] ?? '', ['arriving', 'delivered'])): ?>
                  <button class="btn-receive" onclick="openReceiveModal(<?= $order['id'] ?>)">Receive Delivery</button>
                <?php elseif (($userRole ?? '') === 'Branch Manager' && ($order['status'] ?? '') === 'delivered_to_branch'): ?>
                  <button class="btn-confirm" onclick="openConfirmModal(<?= $order['id'] ?>)">Confirm Delivery</button>
                <?php elseif (in_array($userRole ?? '', ['Central Office Admin', 'System Administrator'])): ?>
                  <!-- Central Office actions: Change Supplier and Cancel Order -->
                  <button class="btn-schedule" onclick="changeSupplier(<?= $order['id'] ?>, '<?= esc($order['supplier_name'] ?? '') ?>')">Change Supplier</button>
                  <?php if (!in_array($order['status'] ?? '', ['delivered_to_branch', 'completed', 'cancelled'])): ?>
                  <button class="btn-reject" onclick="cancelOrder(<?= $order['id'] ?>)">Cancel Order</button>
                  <?php endif; ?>
                <?php else: ?>
                  <!-- No actions available for this user/role/status combination -->
                  <span style="color: #999; font-size: 12px;">No actions available</span>
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
// Global variables for modals
let currentLogisticsPoId = null;
let currentReceivePoId = null;
let currentConfirmPoId = null;
let currentChangePoId = null;
let currentSupplierName = null;

function addItemRow() {
  const container = document.getElementById('items-container');
  const newRow = document.createElement('div');
  newRow.className = 'item-row';
  newRow.innerHTML = `
    <input type="text" class="item-name" placeholder="Item name" required>
    <input type="number" class="item-quantity" placeholder="Qty" step="0.01" required onchange="calculateTotal()">
    <select class="item-unit" required>
      <option value="">Select Unit</option>
      <option value="kg">kg (Kilogram)</option>
      <option value="g">g (Gram)</option>
      <option value="pcs">pcs (Pieces)</option>
      <option value="box">box</option>
      <option value="pack">pack</option>
      <option value="bottle">bottle</option>
      <option value="can">can</option>
      <option value="liter">liter</option>
      <option value="ml">ml (Milliliter)</option>
      <option value="gallon">gallon</option>
      <option value="bag">bag</option>
      <option value="sack">sack</option>
      <option value="carton">carton</option>
      <option value="case">case</option>
      <option value="dozen">dozen</option>
      <option value="unit">unit</option>
    </select>
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

// Schedule modal functions
function openScheduleModal(poId) {
  document.getElementById('schedule_po_id').value = poId;

  // Fetch PO details
  fetch(`<?= base_url('purchase-orders/get/') ?>${poId}`)
    .then(r => r.json())
    .then(result => {
      if (result.success && result.data) {
        const po = result.data;
        const detailsContainer = document.getElementById('schedule-details');

        detailsContainer.innerHTML = `
          <div style="background: #fff0f5; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffd6e8;">
            <h4 style="margin: 0 0 10px 0; color: #333;">Purchase Order Details</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; font-size: 14px;">
              <div><strong>PO ID:</strong> ${po.po_id || 'N/A'}</div>
              <div><strong>Supplier:</strong> ${po.supplier_name || 'N/A'}</div>
              <div><strong>Branch:</strong> ${po.branch_name || 'N/A'}</div>
              <div><strong>Order Date:</strong> ${po.order_date ? new Date(po.order_date).toLocaleDateString() : 'N/A'}</div>
              <div><strong>Total Value:</strong> ₱${po.total_cost ? parseFloat(po.total_cost).toLocaleString() : '0.00'}</div>
              <div><strong>Status:</strong> <span class="badge ${po.status ? po.status.toLowerCase().replace(' ', '_') : 'pending'}">${po.status ? po.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'Pending'}</span></div>
            </div>

            <h5 style="margin: 15px 0 10px 0; color: #333;">Items Ordered:</h5>
            <div style="max-height: 150px; overflow-y: auto;">
              ${po.items && po.items.length > 0 ? po.items.map(item =>
                `<div style="padding: 8px; background: #fff; margin-bottom: 5px; border-radius: 4px; border: 1px solid #eee;">
                  ${item.quantity} ${item.unit || 'pcs'} × ${item.item_name} @ ₱${parseFloat(item.unit_price).toLocaleString()}
                </div>`
              ).join('') : '<div style="color: #999; font-style: italic;">No items found</div>'}
            </div>
          </div>
        `;

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('schedule_delivery_date').min = today;

        // Clear form
        document.getElementById('schedule_delivery_date').value = '';
        document.getElementById('schedule_notes').value = '';

        document.getElementById('scheduleModal').style.display = 'block';
      } else {
        showAlert('Error loading purchase order details', 'error');
      }
    })
    .catch(error => {
      showAlert('Error: ' + error.message, 'error');
    });
}

function closeScheduleModal() {
  document.getElementById('scheduleModal').style.display = 'none';
  document.getElementById('schedule-details').innerHTML = '';
  document.getElementById('schedule_po_id').value = '';
}

function createSchedule() {
  const poId = document.getElementById('schedule_po_id').value;
  const deliveryDate = document.getElementById('schedule_delivery_date').value;
  const notes = document.getElementById('schedule_notes').value;

  if (!poId) {
    showAlert('Error: PO ID not found', 'error');
    return;
  }

  if (!deliveryDate) {
    showAlert('Please select a delivery date', 'error');
    return;
  }

  if (!confirm('Create delivery schedule for this purchase order?')) {
    return;
  }

  fetch(`<?= base_url('purchase-orders/schedule-delivery/') ?>${poId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({
      expected_delivery_date: deliveryDate,
      notes: notes || null
    })
  })
  .then(r => r.json())
  .then(result => {
    if (result.success) {
      showAlert(result.message, 'success');
      closeScheduleModal();
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
  const changeSupplierModal = document.getElementById('changeSupplierModal');
  const scheduleModal = document.getElementById('scheduleModal');
  if (event.target == logisticsModal) {
    closeLogisticsModal();
  }
  if (event.target == receiveModal) {
    closeReceiveModal();
  }
  if (event.target == confirmModal) {
    closeConfirmModal();
  }
  if (event.target == changeSupplierModal) {
    closeChangeSupplierModal();
  }
  if (event.target == scheduleModal) {
    closeScheduleModal();
  }
}

function changeSupplier(poId, currentSupplier) {
  currentChangePoId = poId;
  currentSupplierName = currentSupplier;
  document.getElementById('change_supplier_po_id').value = poId;
  document.getElementById('supplier_change_notes').value = '';

  // Pre-select current supplier in dropdown
  const supplierSelect = document.getElementById('change_supplier_id');
  // Find the option with matching text (supplier name)
  for (let i = 0; i < supplierSelect.options.length; i++) {
    const option = supplierSelect.options[i];
    if (option.text.includes(currentSupplier)) {
      option.selected = true;
      break;
    }
  }

  document.getElementById('changeSupplierModal').style.display = 'block';
}

function closeChangeSupplierModal() {
  document.getElementById('changeSupplierModal').style.display = 'none';
  currentChangePoId = null;
  currentSupplierName = null;
}

function submitChangeSupplier() {
  if (!currentChangePoId) {
    showAlert('Error: PO ID not found', 'error');
    return;
  }

  const newSupplierId = document.getElementById('change_supplier_id').value;
  const changeNotes = document.getElementById('supplier_change_notes').value || null;

  if (!newSupplierId) {
    showAlert('Please select a new supplier', 'error');
    return;
  }

  if (!confirm('Are you sure you want to change the supplier for this purchase order?')) {
    return;
  }

  fetch(`<?= base_url('purchase-orders/change-supplier/') ?>${currentChangePoId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({
      supplier_id: newSupplierId,
      change_notes: changeNotes
    })
  })
  .then(r => r.json())
  .then(result => {
    if (result.success) {
      showAlert(result.message, 'success');
      closeChangeSupplierModal();
      setTimeout(() => location.reload(), 1500);
    } else {
      showAlert(result.message, 'error');
    }
  })
  .catch(error => {
    showAlert('Error: ' + error.message, 'error');
  });
}

function cancelOrder(poId) {
  const reason = prompt('Enter reason for cancellation (optional):');
  if (reason === null) return; // Cancelled

  if (!confirm('Are you sure you want to cancel this purchase order? This action cannot be undone.')) return;

  fetch(`<?= base_url('purchase-orders/cancel/') ?>${poId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({ cancellation_reason: reason || null })
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

function showAlert(message, type) {
  const container = document.getElementById('alert-container');
  container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
  setTimeout(() => container.innerHTML = '', 5000);
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
