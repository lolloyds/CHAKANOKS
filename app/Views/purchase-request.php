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
    .grid {
      display: grid;
      gap: 20px;
    }
    .grid-2 {
      grid-template-columns: 1fr 1.2fr;
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
      margin-bottom: 12px;
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
      border: 1px solid #ffd6e8;
      font-size: 14px;
      background: #fff;
      transition: border-color 0.3s, box-shadow 0.3s;
      width: 100%;
      box-sizing: border-box;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
      outline: none;
      border-color: #ff69b4;
      box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
    }
    .form-group input[readonly] {
      background: #fff0f5;
      cursor: not-allowed;
      font-weight: 600;
    }
    .form-group select {
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 12px center;
      padding-right: 35px;
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
    .btn-approve { background: #4CAF50; color: #fff; }
    .btn-reject { background: #e53935; color: #fff; }
    .btn-create { background: #1976D2; color: #fff; }
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
      background: #ff69b4; 
      color: #fff; 
      margin-top: 10px;
      padding: 10px 16px;
      transition: background 0.3s;
      border-radius: 6px;
    }
    .btn-add-item:hover {
      background: #ff1493;
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
    .badge.pending-central-office-review { background: #ff9800; color: #fff; }
    .badge.approved { background: #66bb6a; color: #fff; }
    .badge.rejected { background: #e57373; color: #fff; }
    .badge.converted { background: #42a5f5; color: #fff; }
    .item-row {
      display: grid;
      grid-template-columns: 2fr 0.9fr 1fr 1.2fr auto;
      gap: 8px;
      margin-bottom: 12px;
      align-items: end;
      padding: 12px;
      background: #fff0f5;
      border-radius: 8px;
      border: 1px solid #ffd6e8;
      transition: box-shadow 0.3s;
    }
    .item-row:hover {
      box-shadow: 0 2px 8px rgba(255, 105, 180, 0.15);
    }
    .item-row input {
      padding: 8px 10px;
      border: 1px solid #ffd6e8;
      border-radius: 6px;
      font-size: 13px;
      width: 100%;
      box-sizing: border-box;
      min-width: 0;
      background: #fff;
      transition: border-color 0.3s, box-shadow 0.3s;
    }
    .item-row input:focus {
      outline: none;
      border-color: #ff69b4;
      box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
    }
    .item-row .item-name {
      min-width: 120px;
    }
    .item-row .item-quantity {
      min-width: 70px;
    }
    .item-row .item-unit {
      min-width: 80px;
    }
    .item-row .item-notes {
      min-width: 100px;
    }
    .item-row .btn-remove-item {
      white-space: nowrap;
      min-width: 75px;
      padding: 8px 12px;
      font-size: 12px;
      border-radius: 6px;
    }
    #items-container {
      margin-top: 20px;
      padding-top: 20px;
      border-top: 2px solid #ffd6e8;
    }
    #items-container h4 {
      margin-top: 0;
      margin-bottom: 15px;
      color: #333;
      font-size: 16px;
      font-weight: 600;
    }
    .alert {
      padding: 12px;
      border-radius: 6px;
      margin-bottom: 15px;
    }
    .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
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
      margin: 15% auto;
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
  </style>

  <div class="box">
    <h2>📑 Purchase Request</h2>
    <div class="desc">
      Create and track requests from branches. Approved requests can be converted to Purchase Orders.
    </div>
  </div>

  <div id="alert-container"></div>

  <!-- Approval Modal -->
  <div id="approvalModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">Approve Purchase Request</div>
      <div class="form-group">
        <label for="approval_supplier_id">Select Supplier (Required)</label>
        <select id="approval_supplier_id" class="form-group" required>
          <option value="">-- Select Supplier --</option>
          <?php foreach ($suppliers ?? [] as $supplier): ?>
            <option value="<?= $supplier['id'] ?>"><?= esc($supplier['supplier_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-reject" onclick="closeApprovalModal()">Cancel</button>
        <button type="button" class="btn-approve" onclick="confirmApprove()">Approve & Create PO</button>
      </div>
    </div>
  </div>

  <div class="grid grid-2">
    <div class="box" style="height: fit-content;">
      <h3>📊 Quick Summary</h3>
      <div class="row">
        <div class="form-group">
          <label>Total Requests</label>
          <input type="text" value="<?= $stats['total'] ?? 0 ?>" readonly style="color: #1976d2; font-weight: 600;">
        </div>
        <div class="form-group">
          <label>Pending Approvals</label>
          <input type="text" value="<?= $stats['pending'] ?? 0 ?>" readonly style="color: #ff9800; font-weight: 600;">
        </div>
        <div class="form-group">
          <label>Pending Central Office Review</label>
          <input type="text" value="<?= $stats['pending_central_office_review'] ?? 0 ?>" readonly style="color: #ff9800; font-weight: 600;">
        </div>
        <div class="form-group">
          <label>Approved</label>
          <input type="text" value="<?= $stats['approved'] ?? 0 ?>" readonly style="color: #4caf50; font-weight: 600;">
        </div>
        <div class="form-group">
          <label>Rejected</label>
          <input type="text" value="<?= $stats['rejected'] ?? 0 ?>" readonly style="color: #e57373; font-weight: 600;">
        </div>
      </div>
    </div>

    <div class="box">
      <h3>📝 New Request</h3>
      <form id="prForm">
        <div class="form-group">
          <label for="branch_id">Branch</label>
          <select id="branch_id" name="branch_id" <?= isset($branchId) ? 'disabled' : '' ?> required>
            <option value="">Select Branch</option>
            <?php foreach ($branches ?? [] as $branch): ?>
              <option value="<?= $branch['id'] ?>" <?= (isset($branchId) && $branchId == $branch['id']) ? 'selected' : '' ?>>
                <?= esc($branch['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if (isset($branchId)): ?>
            <input type="hidden" name="branch_id" value="<?= $branchId ?>">
          <?php endif; ?>
        </div>
        <div class="form-group">
          <label for="date_needed">Date Needed</label>
          <input type="date" id="date_needed" name="date_needed" required>
        </div>
        <div class="form-group">
          <label for="notes">Notes</label>
          <textarea id="notes" name="notes" rows="2" placeholder="Optional notes for approver"></textarea>
        </div>
        <div id="items-container">
          <h4>📦 Items</h4>
          <div class="item-row">
            <input type="text" class="item-name" placeholder="Item name" required>
            <input type="number" class="item-quantity" placeholder="Qty" step="0.01" required>
            <input type="text" class="item-unit" placeholder="Unit (kg, pcs, etc)">
            <input type="text" class="item-notes" placeholder="Notes (optional)">
            <button type="button" class="btn-remove-item" style="background: #e53935; color: #fff; border-radius: 6px;" onclick="this.parentElement.remove()">Remove</button>
          </div>
        </div>
        <button type="button" class="btn-add-item" onclick="addItemRow()">+ Add Item</button>
        <button type="submit" class="btn-submit">Submit Request</button>
      </form>
    </div>
  </div>

  <div class="box">
    <h3>⏳ Purchase Requests</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Request ID</th>
          <th>Branch</th>
          <th>Items</th>
          <th>Date Needed</th>
          <th>Date Requested</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($requests)): ?>
          <tr>
            <td colspan="7" style="text-align: center; padding: 20px;">No purchase requests found</td>
          </tr>
        <?php else: ?>
          <?php foreach ($requests as $request): ?>
            <tr>
              <td><?= esc($request['request_id'] ?? 'N/A') ?></td>
              <td><?= esc($request['branch_name'] ?? 'N/A') ?></td>
              <td>
                <?php
                  $itemsList = [];
                  foreach ($request['items'] ?? [] as $item) {
                    $itemsList[] = $item['quantity'] . ' ' . $item['item_name'] . ($item['unit'] ? ' (' . $item['unit'] . ')' : '');
                  }
                  echo esc(implode(', ', $itemsList) ?: 'No items');
                ?>
              </td>
              <td><?= $request['date_needed'] ? date('M d, Y', strtotime($request['date_needed'])) : 'N/A' ?></td>
              <td><?= $request['created_at'] ? date('M d, Y', strtotime($request['created_at'])) : 'N/A' ?></td>
              <td>
                <span class="badge <?= strtolower(str_replace(' ', '-', $request['status'] ?? 'pending')) ?>">
                  <?= esc(ucwords($request['status'] ?? 'pending')) ?>
                </span>
              </td>
              <td>
                <?php if (in_array($request['status'] ?? '', ['pending', 'pending central office review']) && in_array($userRole ?? '', ['Central Office Admin', 'System Administrator'])): ?>
                  <button class="btn-approve" onclick="approveRequest(<?= $request['id'] ?>)">Approve</button>
                  <button class="btn-reject" onclick="rejectRequest(<?= $request['id'] ?>)">Reject</button>
                <?php elseif (($request['status'] ?? '') === 'approved'): ?>
                  <a href="<?= base_url('purchase-orders?pr_id=' . $request['id']) ?>">
                    <button class="btn-create">Create PO</button>
                  </a>
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
    <input type="number" class="item-quantity" placeholder="Qty" step="0.01" required>
    <input type="text" class="item-unit" placeholder="Unit (kg, pcs, etc)">
    <input type="text" class="item-notes" placeholder="Notes (optional)">
    <button type="button" class="btn-remove-item" style="background: #e53935; color: #fff; border-radius: 6px;" onclick="this.parentElement.remove()">Remove</button>
  `;
  container.appendChild(newRow);
}

document.getElementById('prForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  
  const formData = new FormData(this);
  const items = [];
  
  document.querySelectorAll('.item-row').forEach(row => {
    const name = row.querySelector('.item-name').value;
    const quantity = row.querySelector('.item-quantity').value;
    const unit = row.querySelector('.item-unit').value;
    const notes = row.querySelector('.item-notes').value;
    
    if (name && quantity) {
      items.push({ item_name: name, quantity: quantity, unit: unit, notes: notes });
    }
  });
  
  if (items.length === 0) {
    showAlert('Please add at least one item', 'error');
    return;
  }
  
  const data = {
    branch_id: formData.get('branch_id'),
    date_needed: formData.get('date_needed'),
    notes: formData.get('notes'),
    items: items
  };
  
  try {
    const response = await fetch('<?= base_url('purchase-request/create') ?>', {
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
    showAlert('Error creating request: ' + error.message, 'error');
  }
});

let currentApproveId = null;

function approveRequest(id) {
  currentApproveId = id;
  document.getElementById('approvalModal').style.display = 'block';
  document.getElementById('approval_supplier_id').value = '';
}

function closeApprovalModal() {
  document.getElementById('approvalModal').style.display = 'none';
  currentApproveId = null;
}

function confirmApprove() {
  const supplierId = document.getElementById('approval_supplier_id').value;
  if (!supplierId) {
    showAlert('Please select a supplier', 'error');
    return;
  }
  
  if (!currentApproveId) {
    showAlert('Error: Request ID not found', 'error');
    return;
  }
  
  fetch(`<?= base_url('purchase-request/approve/') ?>${currentApproveId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({ supplier_id: supplierId })
  })
  .then(r => r.json())
  .then(result => {
    if (result.success) {
      showAlert(result.message, 'success');
      closeApprovalModal();
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
  const modal = document.getElementById('approvalModal');
  if (event.target == modal) {
    closeApprovalModal();
  }
}

function rejectRequest(id) {
  const reason = prompt('Enter rejection reason:');
  if (!reason) return;
  
  fetch(`<?= base_url('purchase-request/reject/') ?>${id}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({ rejection_reason: reason })
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

function showAlert(message, type) {
  const container = document.getElementById('alert-container');
  container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
  setTimeout(() => container.innerHTML = '', 5000);
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
