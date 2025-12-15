<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

<style>
  body {
    font-family: "Segoe UI", Arial, sans-serif;
    background: #ffeef5;
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

  h2, h3 {
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

  .stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
  }

  .stat {
    background: linear-gradient(135deg, #fff 0%, #fff0f5 100%);
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    font-weight: 600;
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
  .stat:nth-child(2) { border-left: 4px solid #4caf50; }
  .stat:nth-child(3) { border-left: 4px solid #ff9800; }
  .stat:nth-child(4) { border-left: 4px solid #9c27b0; }
  .stat:nth-child(5) { border-left: 4px solid #f44336; }

  .stat b {
    font-size: 24px;
    display: block;
    margin-top: 5px;
    color: #333;
  }

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

  .table tbody tr.deleted-row {
    background: #ffe0e0;
    opacity: 0.7;
  }

  .table tbody tr:last-child td {
    border-bottom: none;
  }

  .badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
  }

  .badge.active {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
  }
  .badge.pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
  }
  .badge.inactive {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
  }
  .badge.deleted {
    background: #e0e0e0;
    color: #666;
    border: 1px solid #ccc;
  }

  .action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }

  .btn-edit, .btn-delete, .btn-restore {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-block;
  }

  .btn-edit {
    background: linear-gradient(135deg, #42a5f5 0%, #2196F3 100%);
    color: #fff;
    box-shadow: 0 2px 6px rgba(66, 165, 245, 0.3);
  }

  .btn-edit:hover {
    background: linear-gradient(135deg, #2196F3 0%, #1976d2 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(66, 165, 245, 0.4);
  }

  .btn-delete {
    background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
    color: #fff;
    box-shadow: 0 2px 6px rgba(229, 57, 53, 0.3);
  }

  .btn-delete:hover {
    background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(229, 57, 53, 0.4);
  }

  .btn-restore {
    background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%);
    color: #fff;
    box-shadow: 0 2px 6px rgba(102, 187, 106, 0.3);
  }

  .btn-restore:hover {
    background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(102, 187, 106, 0.4);
  }

  .btn-add {
    background: linear-gradient(135deg, #ff69b4 0%, #ff1493 100%);
    color: #fff;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(255, 105, 180, 0.3);
    margin-bottom: 20px;
  }

  .btn-add:hover {
    background: linear-gradient(135deg, #ff1493 0%, #dc143c 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(255, 105, 180, 0.4);
  }

  /* Modal Styles */
  .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
  }

  .modal-content {
    background: #fff5f8;
    margin: 5% auto;
    padding: 30px;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    border: 1px solid #ffd6e8;
  }

  .modal-header {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
    padding-bottom: 10px;
    border-bottom: 2px solid #ffd6e8;
  }

  .form-group {
    margin-bottom: 16px;
  }

  .form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #444;
    font-size: 14px;
  }

  .form-group input, .form-group select, .form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ffd6e8;
    border-radius: 6px;
    font-size: 14px;
    background: #fff;
    box-sizing: border-box;
    transition: border-color 0.3s, box-shadow 0.3s;
  }

  .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    outline: none;
    border-color: #ff69b4;
    box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
  }

  .modal-footer {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #ffd6e8;
  }

  .alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 14px;
  }

  .alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
  }

  .alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
  }
</style>

<main>
  <?php if (isset($isSupplierView) && $isSupplierView): ?>
  <!-- SUPPLIER PROFILE VIEW -->
  <div class="box">
    <h2>🏢 <?= esc($supplier['supplier_name'] ?? 'Supplier Profile') ?></h2>
    <p style="color: #666; line-height: 1.6; margin: 0;">
      Your company profile and order statistics. Update your contact information and track your performance.
    </p>
  </div>

  <div class="stats">
    <div class="stat">
      <div style="font-size: 13px; color: #666; margin-bottom: 5px;">Total Orders</div>
      <b style="color: #2196F3;"><?= $stats['total_orders'] ?? 0 ?></b>
    </div>
    <div class="stat">
      <div style="font-size: 13px; color: #666; margin-bottom: 5px;">Pending Orders</div>
      <b style="color: #ff9800;"><?= $stats['pending_orders'] ?? 0 ?></b>
    </div>
    <div class="stat">
      <div style="font-size: 13px; color: #666; margin-bottom: 5px;">Active Deliveries</div>
      <b style="color: #4caf50;"><?= $stats['active_orders'] ?? 0 ?></b>
    </div>
    <div class="stat">
      <div style="font-size: 13px; color: #666; margin-bottom: 5px;">Completed Orders</div>
      <b style="color: #9c27b0;"><?= $stats['completed_orders'] ?? 0 ?></b>
    </div>
    <div class="stat">
      <div style="font-size: 13px; color: #666; margin-bottom: 5px;">Company Status</div>
      <span class="badge <?= strtolower($supplier['status'] ?? 'active') ?>">
        <?= esc($supplier['status'] ?? 'Active') ?>
      </span>
    </div>
  </div>

  <div class="box">
    <h3>📋 Company Information</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
      <div>
        <strong>Supplier Name:</strong><br>
        <span style="color: #333;"><?= esc($supplier['supplier_name'] ?? 'N/A') ?></span>
      </div>
      <div>
        <strong>Contact Person:</strong><br>
        <span style="color: #333;"><?= esc($supplier['contact_person'] ?? 'N/A') ?></span>
      </div>
      <div>
        <strong>Phone:</strong><br>
        <span style="color: #333;"><?= esc($supplier['phone'] ?? 'N/A') ?></span>
      </div>
      <div>
        <strong>Email:</strong><br>
        <span style="color: #333;"><?= esc($supplier['email'] ?? 'N/A') ?></span>
      </div>
      <div>
        <strong>Supply Type:</strong><br>
        <span style="color: #333;"><?= esc($supplier['supply_type'] ?? 'N/A') ?></span>
      </div>
      <div>
        <strong>Address:</strong><br>
        <span style="color: #333;"><?= esc($supplier['address'] ?? 'N/A') ?></span>
      </div>
    </div>
  </div>

  <div class="box">
    <h3>📈 Recent Orders</h3>
    <table class="table">
      <thead>
        <tr>
          <th>PO ID</th>
          <th>Branch</th>
          <th>Order Date</th>
          <th>Expected Delivery</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($recentOrders)): ?>
          <tr>
            <td colspan="5" style="text-align: center; padding: 20px; color: #666;">No recent orders</td>
          </tr>
        <?php else: ?>
          <?php foreach ($recentOrders as $order): ?>
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
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php else: ?>
  <!-- ADMIN MANAGEMENT VIEW -->
  <div class="box">
    <h2>📦 Suppliers</h2>
    <p style="color: #666; line-height: 1.6; margin: 0;">
      The Suppliers section contains the list of all vendors providing goods and services for Chakanok's Roasted Chicken House.
      It includes suppliers for fresh chicken, spices, packaging, beverages, and cooking fuel.
      Managing supplier information ensures timely deliveries, better pricing, and high-quality raw materials for consistent product quality.
    </p>
  </div>

  <div class="stats">
    <div class="stat">
      <div style="font-size: 13px; color: #666; margin-bottom: 5px;">Total Suppliers</div>
      <b style="color: #2196F3;"><?= $stats['total'] ?? 0 ?></b>
    </div>
    <div class="stat">
      <div style="font-size: 13px; color: #666; margin-bottom: 5px;">Active</div>
      <b style="color: #4caf50;"><?= $stats['active'] ?? 0 ?></b>
    </div>
    <div class="stat">
      <div style="font-size: 13px; color: #666; margin-bottom: 5px;">Pending</div>
      <b style="color: #ff9800;"><?= $stats['pending'] ?? 0 ?></b>
    </div>
    <div class="stat">
      <div style="font-size: 13px; color: #666; margin-bottom: 5px;">Inactive</div>
      <b style="color: #9c27b0;"><?= $stats['inactive'] ?? 0 ?></b>
    </div>
    <div class="stat">
      <div style="font-size: 13px; color: #666; margin-bottom: 5px;">Deleted</div>
      <b style="color: #f44336;"><?= $stats['deleted'] ?? 0 ?></b>
    </div>
  </div>

  <div class="box">
    <h3>📋 Supplier List</h3>
    <div id="alert-container"></div>
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Supplier Name</th>
          <th>Contact Person</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Supply Type</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($suppliers)): ?>
          <tr>
            <td colspan="8" style="text-align: center; padding: 20px; color: #666;">
              No suppliers found
              <br><small>Debug: <?php echo 'isSupplierView: ' . (isset($isSupplierView) ? ($isSupplierView ? 'true' : 'false') : 'not set'); ?>, Supplier count: <?php echo count($suppliers ?? []); ?>, Controller data: <?php print_r($suppliers); ?></small>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($suppliers as $supplier): ?>
            <?php $isDeleted = !empty($supplier['deleted_at']); ?>
            <tr class="<?= $isDeleted ? 'deleted-row' : '' ?>">
              <td>SUP-<?= str_pad($supplier['id'], 3, '0', STR_PAD_LEFT) ?></td>
              <td><?= esc($supplier['supplier_name'] ?? 'N/A') ?></td>
              <td><?= esc($supplier['contact_person'] ?? 'N/A') ?></td>
              <td><?= esc($supplier['phone'] ?? 'N/A') ?></td>
              <td><?= esc($supplier['email'] ?? 'N/A') ?></td>
              <td><?= esc($supplier['supply_type'] ?? 'N/A') ?></td>
              <td>
                <?php if ($isDeleted): ?>
                  <span class="badge deleted">Deleted</span>
                <?php else: ?>
                  <span class="badge <?= strtolower($supplier['status'] ?? 'active') ?>">
                    <?= esc($supplier['status'] ?? 'Active') ?>
                  </span>
                <?php endif; ?>
              </td>
              <td>
                <div class="action-buttons">
                  <?php if ($isDeleted): ?>
                    <button class="btn-restore" onclick="restoreSupplier(<?= $supplier['id'] ?>)">Restore</button>
                  <?php else: ?>
                    <button class="btn-edit" onclick="openEditModal(<?= htmlspecialchars(json_encode($supplier), ENT_QUOTES, 'UTF-8') ?>)">Edit</button>
                    <button class="btn-delete" onclick="deleteSupplier(<?= $supplier['id'] ?>)">Delete</button>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php endif; ?>
</main>

<!-- Add/Edit Modal -->
<div id="supplierModal" class="modal">
  <div class="modal-content">
    <div class="modal-header" id="modalTitle">Add New Supplier</div>
    <form id="supplierForm">
      <input type="hidden" id="supplier_id" name="id">
      <div class="form-group">
        <label for="supplier_name">Supplier Name *</label>
        <input type="text" id="supplier_name" name="supplier_name" required>
      </div>
      <div class="form-group">
        <label for="contact_person">Contact Person</label>
        <input type="text" id="contact_person" name="contact_person">
      </div>
      <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone">
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email">
      </div>
      <div class="form-group">
        <label for="address">Address</label>
        <textarea id="address" name="address" rows="2"></textarea>
      </div>
      <div class="form-group">
        <label for="supply_type">Supply Type</label>
        <input type="text" id="supply_type" name="supply_type" placeholder="e.g., Whole Chickens, Spices, Packaging">
      </div>
      <div class="form-group">
        <label for="status">Status *</label>
        <select id="status" name="status" required>
          <option value="Active">Active</option>
          <option value="Pending">Pending</option>
          <option value="Inactive">Inactive</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-delete" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn-edit">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
let currentSupplierId = null;

function showAlert(message, type) {
  const container = document.getElementById('alert-container');
  container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
  setTimeout(() => {
    container.innerHTML = '';
  }, 5000);
}

function openAddModal() {
  currentSupplierId = null;
  document.getElementById('modalTitle').textContent = 'Add New Supplier';
  document.getElementById('supplierForm').reset();
  document.getElementById('supplier_id').value = '';
  document.getElementById('supplierModal').style.display = 'block';
}

function openEditModal(supplier) {
  currentSupplierId = supplier.id;
  document.getElementById('modalTitle').textContent = 'Edit Supplier';
  document.getElementById('supplier_id').value = supplier.id;
  document.getElementById('supplier_name').value = supplier.supplier_name || '';
  document.getElementById('contact_person').value = supplier.contact_person || '';
  document.getElementById('phone').value = supplier.phone || '';
  document.getElementById('email').value = supplier.email || '';
  document.getElementById('address').value = supplier.address || '';
  document.getElementById('supply_type').value = supplier.supply_type || '';
  document.getElementById('status').value = supplier.status || 'Active';
  document.getElementById('supplierModal').style.display = 'block';
}

function closeModal() {
  document.getElementById('supplierModal').style.display = 'none';
  currentSupplierId = null;
}

document.getElementById('supplierForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  const formData = new FormData(this);
  const data = Object.fromEntries(formData.entries());

  const url = currentSupplierId
    ? `<?= base_url('suppliers/update/') ?>${currentSupplierId}`
    : `<?= base_url('suppliers/create') ?>`;

  try {
    const response = await fetch(url, {
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
      closeModal();
      setTimeout(() => location.reload(), 1000);
    } else {
      showAlert(result.message || 'Operation failed', 'error');
    }
  } catch (error) {
    showAlert('Error: ' + error.message, 'error');
  }
});

function deleteSupplier(id) {
  if (!confirm('Are you sure you want to delete this supplier? This will soft delete the supplier.')) return;

  fetch(`<?= base_url('suppliers/delete/') ?>${id}`, {
    method: 'POST',
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(r => r.json())
  .then(result => {
    if (result.success) {
      showAlert(result.message, 'success');
      setTimeout(() => location.reload(), 1000);
    } else {
      showAlert(result.message || 'Failed to delete supplier', 'error');
    }
  })
  .catch(error => {
    showAlert('Error: ' + error.message, 'error');
  });
}

function restoreSupplier(id) {
  if (!confirm('Are you sure you want to restore this supplier?')) return;

  fetch(`<?= base_url('suppliers/restore/') ?>${id}`, {
    method: 'POST',
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(r => r.json())
  .then(result => {
    if (result.success) {
      showAlert(result.message, 'success');
      setTimeout(() => location.reload(), 1000);
    } else {
      showAlert(result.message || 'Failed to restore supplier', 'error');
    }
  })
  .catch(error => {
    showAlert('Error: ' + error.message, 'error');
  });
}

// Close modal when clicking outside
window.onclick = function(event) {
  const modal = document.getElementById('supplierModal');
  if (event.target == modal) {
    closeModal();
  }
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
