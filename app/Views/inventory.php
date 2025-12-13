<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

<style>
  /* Inventory Page Enhanced Styles */
  body {
    background: #ffeef5;
    font-family: "Segoe UI", Arial, sans-serif;
  }

  main {
    background: #ffeef5;
  }

  .inventory-container {
    background: #fff5f8;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    margin-bottom: 24px;
    border: 1px solid #ffd6e8;
  }

  .page-header h2 {
    color: #333;
    margin-bottom: 8px;
    font-size: 24px;
    font-weight: 600;
  }

  .page-header p {
    color: #666;
    margin: 8px 0 20px 0;
  }

  /* Enhanced Stats Cards */
  .stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin: 20px 0 24px 0;
  }

  .stat {
    background: linear-gradient(135deg, #fff 0%, #fff0f5 100%);
    border: 1px solid #ffd6e8;
    border-left: 4px solid #ff69b4;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    font-weight: 600;
    font-size: 16px;
    color: #333;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .stat:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .stat:nth-child(1) { border-left-color: #ff9800; }
  .stat:nth-child(2) { border-left-color: #ffc107; }
  .stat:nth-child(3) { border-left-color: #f44336; }
  .stat:nth-child(4) { border-left-color: #ff9800; }

  /* Enhanced Filters */
  .filters {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
    margin: 20px 0;
    padding: 16px;
    background: #fff5f8;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    border: 1px solid #ffd6e8;
  }

  .filters input[type="text"] {
    flex: 1;
    min-width: 250px;
    padding: 12px 16px;
    border: 2px solid #ffd6e8;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    background: #fff;
  }

  .filters input[type="text"]:focus {
    outline: none;
    border-color: #ff69b4;
    box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
  }

  .filters select {
    padding: 12px 16px;
    border: 2px solid #ffd6e8;
    border-radius: 8px;
    font-size: 14px;
    background: #fff;
    cursor: pointer;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    min-width: 150px;
  }

  .filters select:focus {
    outline: none;
    border-color: #ff69b4;
    box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
  }

  /* Enhanced Action Buttons */
  .actions {
    display: flex;
    gap: 12px;
    margin: 20px 0;
    flex-wrap: wrap;
  }

  .actions button {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    background: linear-gradient(135deg, #ff69b4 0%, #ff1493 100%);
    color: #fff;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(255, 105, 180, 0.3);
  }

  .actions button:hover {
    background: linear-gradient(135deg, #ff1493 0%, #dc143c 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(255, 105, 180, 0.4);
  }

  .actions button:active {
    transform: translateY(0);
  }

  /* Enhanced Table */
  .inventory-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    margin: 20px 0;
    border: 1px solid #ffd6e8;
  }

  .inventory-table thead {
    background: linear-gradient(135deg, #fff0f5 0%, #ffeef5 100%);
  }

  .inventory-table th {
    padding: 16px 18px;
    text-align: left;
    font-weight: 600;
    color: #333;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #ffd6e8;
  }

  .inventory-table td {
    padding: 14px 18px;
    border-bottom: 1px solid #ffd6e8;
    color: #333;
    font-size: 14px;
  }

  .inventory-table tbody tr {
    transition: background-color 0.2s ease;
    background: #fff;
  }

  .inventory-table tbody tr:hover {
    background-color: #fff0f5;
  }

  .inventory-table tbody tr:last-child td {
    border-bottom: none;
  }

  /* Status Badges */
  .status-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .status-badge.in-stock {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
  }

  .status-badge.low-stock {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
  }

  .status-badge.out-of-stock {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
  }

  .status-badge.near-expiry {
    background: #ffeaa7;
    color: #d63031;
    border: 1px solid #fdcb6e;
  }

  /* Action Buttons in Table */
  .inventory-table .action-buttons {
    display: flex;
    gap: 8px;
  }

  .inventory-table .action-buttons button {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .inventory-table .action-buttons button.edit-btn {
    background: #ff69b4;
    color: #fff;
  }

  .inventory-table .action-buttons button.edit-btn:hover {
    background: #ff1493;
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(255, 105, 180, 0.3);
  }

  .inventory-table .action-buttons button.remove-btn {
    background: #e74c3c;
    color: #fff;
  }

  .inventory-table .action-buttons button.remove-btn:hover {
    background: #c0392b;
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(231, 76, 60, 0.3);
  }

  /* Enhanced Modal */
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

  .modal form {
    background-color: #fff;
    margin: 10% auto;
    padding: 30px;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    animation: modalSlideIn 0.3s ease;
  }

  @keyframes modalSlideIn {
    from {
      opacity: 0;
      transform: translateY(-50px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .modal h3 {
    margin-top: 0;
    color: #333;
    font-size: 22px;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #ffd6e8;
  }

  .modal label {
    display: block;
    margin: 16px 0 8px 0;
    color: #555;
    font-weight: 500;
    font-size: 14px;
  }

  .modal input,
  .modal select {
    width: 100%;
    padding: 12px 14px;
    border: 2px solid #ffd6e8;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s ease;
    box-sizing: border-box;
  }

  .modal input:focus,
  .modal select:focus {
    outline: none;
    border-color: #ff69b4;
    box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
  }

  .modal button[type="submit"] {
    margin-top: 20px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #ff69b4 0%, #ff1493 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-right: 10px;
  }

  .modal button[type="submit"]:hover {
    background: linear-gradient(135deg, #ff1493 0%, #dc143c 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 105, 180, 0.3);
  }

  .modal button[type="button"] {
    margin-top: 20px;
    padding: 12px 24px;
    background: #6c757d;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .modal button[type="button"]:hover {
    background: #5a6268;
    transform: translateY(-2px);
  }

  /* Message Styles */
  #message {
    padding: 14px 18px;
    border-radius: 8px;
    font-weight: 500;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  /* Responsive */
  @media (max-width: 768px) {
    .filters {
      flex-direction: column;
    }

    .filters input[type="text"],
    .filters select {
      width: 100%;
      min-width: auto;
    }

    .actions {
      flex-direction: column;
    }

    .actions button {
      width: 100%;
    }

    .inventory-table {
      font-size: 12px;
    }

    .inventory-table th,
    .inventory-table td {
      padding: 10px 12px;
    }

    .inventory-table .action-buttons {
      flex-direction: column;
    }
  }
</style>

<main>
  <?php if (!isset($isBranchUser) || !$isBranchUser): ?>
  <!-- Central Office Inventory View -->
  <div class="box" style="margin-bottom: 20px;">
    <h2 style="margin-top: 0; padding-bottom: 10px; border-bottom: 2px solid #ffd6e8;">📦 Inventory</h2>
    <p style="color: #666; line-height: 1.6; margin: 0;">
      The Inventory section provides a complete list of all items available in Chakanok's Roasted Chicken House.
      It tracks raw ingredients, cooking supplies, packaging materials, and beverages across all branches.
      This helps ensure that fresh chicken and essential supplies are always available for daily operations.
    </p>
  </div>

  <div class="stats">
    <div class="stat">
      <div style="font-size: 24px; color: #ff9800; margin-bottom: 5px;">120</div>
      <div style="font-size: 13px; color: #666;">Total Items</div>
    </div>
    <div class="stat">
      <div style="font-size: 24px; color: #ffc107; margin-bottom: 5px;">8</div>
      <div style="font-size: 13px; color: #666;">Low Stock</div>
    </div>
    <div class="stat">
      <div style="font-size: 24px; color: #f44336; margin-bottom: 5px;">2</div>
      <div style="font-size: 13px; color: #666;">Out of Stock</div>
    </div>
    <div class="stat">
      <div style="font-size: 24px; color: #ff9800; margin-bottom: 5px;">5</div>
      <div style="font-size: 13px; color: #666;">Near Expiry</div>
    </div>
  </div>

  <div class="box">
    <h3 style="margin-top: 0; padding-bottom: 10px; border-bottom: 2px solid #ffd6e8;">📋 Inventory Items</h3>
    <table class="inventory-table">
      <thead>
        <tr>
          <th>Item ID</th>
          <th>Item Name</th>
          <th>Category</th>
          <th>Quantity</th>
          <th>Unit</th>
          <th>Expiry Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><strong>INV-001</strong></td>
          <td>Whole Chicken</td>
          <td>Meat</td>
          <td>45</td>
          <td>pcs</td>
          <td>2025-08-20</td>
          <td><span class="status-badge in-stock">In Stock</span></td>
        </tr>
        <tr>
          <td><strong>INV-002</strong></td>
          <td>Chicken Marinade Mix</td>
          <td>Seasoning</td>
          <td>10</td>
          <td>kg</td>
          <td>2025-09-10</td>
          <td><span class="status-badge low-stock">Low Stock</span></td>
        </tr>
        <tr>
          <td><strong>INV-003</strong></td>
          <td>Charcoal Bags</td>
          <td>Fuel</td>
          <td>50</td>
          <td>bags</td>
          <td>N/A</td>
          <td><span class="status-badge in-stock">In Stock</span></td>
        </tr>
        <tr>
          <td><strong>INV-004</strong></td>
          <td>Plastic Food Containers</td>
          <td>Packaging</td>
          <td>200</td>
          <td>pcs</td>
          <td>N/A</td>
          <td><span class="status-badge in-stock">In Stock</span></td>
        </tr>
        <tr>
          <td><strong>INV-005</strong></td>
          <td>Soft Drinks</td>
          <td>Beverage</td>
          <td>30</td>
          <td>cases</td>
          <td>2025-12-15</td>
          <td><span class="status-badge in-stock">In Stock</span></td>
        </tr>
        <tr>
          <td><strong>INV-006</strong></td>
          <td>Banana Leaves</td>
          <td>Wrapping</td>
          <td>80</td>
          <td>pcs</td>
          <td>2025-08-18</td>
          <td><span class="status-badge near-expiry">Near Expiry</span></td>
        </tr>
      </tbody>
    </table>
  </div>
  <?php else: ?>
  <!-- Branch Inventory View -->
  <div class="page-header">
    <h1>CHAKANOKS</h1>
    <h2>Branch Inventory - <?= esc($branch->name ?? 'Branch') ?></h2>
    <p><strong>Location:</strong> <?= esc($branch->address ?? 'N/A') ?></p>
  </div>

  <!-- Quick Stats -->
  <div class="stats">
    <div class="stat">Total Items: <?= esc($totalItems ?? 0) ?></div>
    <div class="stat">Low Stock Items: <?= esc($lowStock ?? 0) ?></div>
    <div class="stat">Out of Stock: <?= esc($outOfStock ?? 0) ?></div>
    <div class="stat">Near Expiry: <?= esc($nearExpiry ?? 0) ?></div>
  </div>

  <!-- Alerts Section -->
  <div class="inventory-container">
    <h3>🚨 Active Alerts</h3>
    <div id="alerts-container">
      <div id="alerts-loading">Loading alerts...</div>
    </div>
    <div style="margin-top: 10px;">
      <button type="button" onclick="checkAlerts()" style="padding: 8px 16px; background: #ff69b4; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; transition: background 0.3s;">🔄 Check for New Alerts</button>
    </div>
  </div>

  <!-- Search & Filters -->
  <div class="filters">
    <div style="position: relative; flex: 1;">
      <input type="text" id="inventorySearch" placeholder="Search items..." style="width: 100%; padding: 12px 40px 12px 16px;" />
      <i class="fas fa-search" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #666; font-size: 16px;"></i>
    </div>
    <select id="categoryFilter">
      <option value="">Category</option>
      <option value="Meat">Meat</option>
      <option value="Seasoning">Seasoning</option>
      <option value="Fuel">Fuel</option>
      <option value="Packaging">Packaging</option>
      <option value="Beverage">Beverage</option>
    </select>
    <select id="statusFilter">
      <option value="">Status</option>
      <option value="In Stock">In Stock</option>
      <option value="Low Stock">Low Stock</option>
      <option value="Out of Stock">Out of Stock</option>
      <option value="Near Expiry">Near Expiry</option>
    </select>
  </div>

  <!-- Messages -->
  <div id="message" style="display: none;"></div>

  <!-- Action Buttons -->
  <div class="actions">
    <button type="button" onclick="openModal('useItemModal')">Use Item</button>
    <button type="button" onclick="openModal('reportModal')">Report Damage/Expiry</button>
  </div>

  <!-- Items Table -->
  <table class="inventory-table">
    <thead>
      <tr>
        <th>Item ID</th>
        <th>Item Name</th>
        <th>Category</th>
        <th>Quantity</th>
        <th>Unit</th>
        <th>Expiry Date</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($inventory ?? [] as $item): ?>
        <?php
          $statusClass = '';
          if (($item['quantity'] ?? 0) == 0) {
              $status = "Out of Stock";
              $statusClass = "out-of-stock";
          } elseif (($item['quantity'] ?? 0) <= ($item['reorder_level'] ?? 0)) {
              $status = "Low Stock";
              $statusClass = "low-stock";
          } elseif (isset($item['expiry_date']) && $item['expiry_date'] && $item['expiry_date'] <= date('Y-m-d', strtotime('+7 days'))) {
              $status = "Near Expiry";
              $statusClass = "near-expiry";
          } else {
              $status = "In Stock";
              $statusClass = "in-stock";
          }
        ?>
        <tr>
          <td>INV-<?= esc($item['item_id'] ?? '') ?></td>
          <td><?= esc($item['item_name'] ?? '') ?></td>
          <td><?= esc($item['category'] ?? 'General') ?></td>
          <td><?= esc($item['quantity'] ?? 0) ?></td>
          <td><?= esc($item['unit'] ?? '') ?></td>
          <td><?= $item['expiry_date'] ?? 'N/A' ?></td>
          <td><span class="status-badge <?= $statusClass ?>"><?= $status ?></span></td>
          <td>
            <div class="action-buttons">
              <a href="/inventory/edit/<?= esc($item['item_id']) ?>" class="edit-btn" style="text-decoration:none;">Edit</a>
              <a href="/inventory/remove/<?= esc($item['item_id']) ?>" class="remove-btn" style="text-decoration:none;" onclick="return confirm('Are you sure you want to remove this item?');">Remove</a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>



  <!-- Use Item Modal -->
  <div id="useItemModal" class="modal">
    <form method="post" action="<?= base_url('inventory/useItem') ?>">
      <h3>Use Item</h3>
      <label>Item Name:</label>
      <input type="text" name="item_name" placeholder="Type item name..." required autocomplete="off">
      <label>Quantity:</label>
      <input type="number" name="quantity" required>
      <div style="display: flex; gap: 10px; margin-top: 10px;">
        <button type="submit">Use</button>
        <button type="button" onclick="closeModal('useItemModal')">Cancel</button>
      </div>
    </form>
  </div>

  <!-- Report Damage/Expiry Modal -->
  <div id="reportModal" class="modal">
    <form method="post" action="<?= base_url('inventory/reportDamage') ?>">
      <h3>Report Damage / Expiry</h3>
      <label>Item Name:</label>
      <input type="text" name="item_name" placeholder="Type item name..." required autocomplete="off">
      <label>Quantity:</label>
      <input type="number" name="quantity" required>
      <label>Reason:</label>
      <select name="reason">
        <option value="damaged">Damaged</option>
        <option value="expired">Expired</option>
      </select>
      <div style="display: flex; gap: 10px; margin-top: 10px;">
        <button type="submit">Save</button>
        <button type="button" onclick="closeModal('reportModal')">Cancel</button>
      </div>
    </form>
  </div>

  <script>
    function openModal(id) {
      const modal = document.getElementById(id);
      modal.style.display = 'block';
      document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }
    function closeModal(id) {
      const modal = document.getElementById(id);
      modal.style.display = 'none';
      document.body.style.overflow = ''; // Restore scrolling
    }

    // Alert management functions
    function loadAlerts() {
      fetch('<?= base_url('inventory/alerts') ?>')
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            displayAlerts(data.alerts, data.counts);
          } else {
            document.getElementById('alerts-container').innerHTML = '<p style="color: red;">Failed to load alerts</p>';
          }
        })
        .catch(error => {
          console.error('Error loading alerts:', error);
          document.getElementById('alerts-container').innerHTML = '<p style="color: red;">Error loading alerts</p>';
        });
    }

    function displayAlerts(alerts, counts) {
      const container = document.getElementById('alerts-container');

      if (!alerts || alerts.length === 0) {
        container.innerHTML = '<p style="color: #666; font-style: italic;">No active alerts</p>';
        return;
      }

      let html = '<div style="margin-bottom: 15px;">';
      html += `<span style="font-weight: bold; color: #333;">${alerts.length} active alert(s)</span>`;
      html += '<div style="display: flex; gap: 10px; margin-top: 5px; font-size: 12px;">';
      if (counts.critical > 0) html += `<span style="color: #dc3545;">🔴 ${counts.critical} Critical</span>`;
      if (counts.high > 0) html += `<span style="color: #fd7e14;">🟠 ${counts.high} High</span>`;
      if (counts.medium > 0) html += `<span style="color: #ffc107;">🟡 ${counts.medium} Medium</span>`;
      if (counts.low > 0) html += `<span style="color: #6c757d;">🔵 ${counts.low} Low</span>`;
      html += '</div></div>';

      alerts.forEach(alert => {
        const severityColor = {
          'low': '#6c757d',
          'medium': '#ffc107',
          'high': '#fd7e14',
          'critical': '#dc3545'
        }[alert.severity] || '#6c757d';

        html += `
          <div style="border-left: 4px solid ${severityColor}; padding: 12px 15px; margin-bottom: 10px; background: #f8f9fa; border-radius: 4px;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
              <div style="flex: 1;">
                <div style="font-weight: bold; color: #333; margin-bottom: 5px;">${alert.title}</div>
                <div style="color: #666; font-size: 14px;">${alert.message}</div>
                <div style="color: #999; font-size: 12px; margin-top: 5px;">
                  ${new Date(alert.created_at).toLocaleString()}
                </div>
              </div>
              <button onclick="acknowledgeAlert(${alert.id})" style="background: #28a745; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; font-size: 12px;">✓ Read</button>
            </div>
          </div>
        `;
      });

      container.innerHTML = html;
    }

    function acknowledgeAlert(alertId) {
      fetch('<?= base_url('inventory/alerts/acknowledge') ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'alert_id=' + alertId
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          loadAlerts(); // Refresh alerts
        } else {
          alert('Failed to acknowledge alert: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error acknowledging alert');
      });
    }

    function checkAlerts() {
      fetch('<?= base_url('inventory/alerts/check') ?>', {
        method: 'POST'
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          loadAlerts(); // Refresh alerts
        } else {
          alert('Failed to check alerts: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error checking alerts');
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      // Load alerts on page load (only for branch users)
      <?php if (isset($isBranchUser) && $isBranchUser): ?>
      loadAlerts();
      <?php endif; ?>

      const modals = document.querySelectorAll('.modal');
      modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
          if (e.target === modal) {
            closeModal(modal.id);
          }
        });
      });

      // Close modal with Escape key
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          modals.forEach(modal => {
            if (modal.style.display === 'block') {
              closeModal(modal.id);
            }
          });
        }
      });

      // Handle AJAX form submissions
      // Add hidden branch_id to forms
      const forms = document.querySelectorAll('.modal form');
      forms.forEach(form => {
        const branchInput = document.createElement('input');
        branchInput.type = 'hidden';
        branchInput.name = 'branch_id';
        branchInput.value = '<?= $branch->id ?? session()->get('user')['branch_id'] ?? 1 ?>';
        form.appendChild(branchInput);

        form.addEventListener('submit', function(e) {
          e.preventDefault();

          const formData = new FormData(this);
          const action = this.getAttribute('action');

          fetch(action, {
            method: 'POST',
            body: formData,
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
          .then(response => response.json())
          .then(data => {
            const messageDiv = document.getElementById('message');
            if (data.success) {
              messageDiv.style.display = 'block';
              messageDiv.style.backgroundColor = '#d4edda';
              messageDiv.style.color = '#155724';
              messageDiv.style.border = '1px solid #c3e6cb';
              messageDiv.textContent = data.message;

              // Close modal
              const modal = this.closest('.modal');
              closeModal(modal.id);

              // Refresh the page to update inventory data
              setTimeout(() => {
                window.location.reload();
              }, 1500);
            } else {
              messageDiv.style.display = 'block';
              messageDiv.style.backgroundColor = '#f8d7da';
              messageDiv.style.color = '#721c24';
              messageDiv.style.border = '1px solid #f5c6cb';
              messageDiv.textContent = data.message;
            }
          })
          .catch(error => {
            console.error('Error:', error);
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
            messageDiv.style.backgroundColor = '#f8d7da';
            messageDiv.style.color = '#721c24';
            messageDiv.style.border = '1px solid #f5c6cb';
            messageDiv.textContent = 'An error occurred. Please try again.';
          });
        });
      });

      // AJAX Remove
      document.querySelectorAll('.remove-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          if (!confirm('Are you sure you want to remove this item?')) return;
          fetch(this.href, { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(data => {
              // Show success message
              const messageDiv = document.getElementById('message');
              messageDiv.style.display = 'block';
              messageDiv.style.backgroundColor = '#d4edda';
              messageDiv.style.color = '#155724';
              messageDiv.style.border = '1px solid #c3e6cb';
              messageDiv.textContent = 'Item removed successfully.';
              // Remove row from table
              btn.closest('tr').remove();
            })
            .catch(() => {
              const messageDiv = document.getElementById('message');
              messageDiv.style.display = 'block';
              messageDiv.style.backgroundColor = '#f8d7da';
              messageDiv.style.color = '#721c24';
              messageDiv.style.border = '1px solid #f5c6cb';
              messageDiv.textContent = 'Error removing item.';
            });
        });
      });

      // Search and Filter Functionality
      const searchInput = document.getElementById('inventorySearch');
      const categoryFilter = document.getElementById('categoryFilter');
      const statusFilter = document.getElementById('statusFilter');
      const table = document.querySelector('.inventory-table');
      const rows = table ? table.getElementsByTagName('tbody')[0].getElementsByTagName('tr') : [];

      function filterTable() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const categoryValue = categoryFilter ? categoryFilter.value.toLowerCase() : '';
        const statusValue = statusFilter ? statusFilter.value.toLowerCase() : '';

        for (let i = 0; i < rows.length; i++) {
          const row = rows[i];
          const cells = row.getElementsByTagName('td');
          let showRow = true;

          if (cells.length > 0) {
            // Search across all columns
            const textContent = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
            if (searchTerm && !textContent.includes(searchTerm)) {
              showRow = false;
            }

            // Category filter
            if (categoryValue) {
              const categoryCell = cells[2]; // Category column
              if (categoryCell) {
                const categoryText = categoryCell.textContent.toLowerCase().trim();
                if (!categoryText.includes(categoryValue)) {
                  showRow = false;
                }
              }
            }

            // Status filter
            if (statusValue) {
              const statusCell = cells[6]; // Status column
              if (statusCell) {
                const statusText = statusCell.textContent.toLowerCase().trim();
                if (!statusText.includes(statusValue)) {
                  showRow = false;
                }
              }
            }
          }

          row.style.display = showRow ? '' : 'none';
        }
      }

      // Add event listeners if elements exist
      if (searchInput) searchInput.addEventListener('input', filterTable);
      if (categoryFilter) categoryFilter.addEventListener('change', filterTable);
      if (statusFilter) statusFilter.addEventListener('change', filterTable);
    });
  </script>
  <?php endif; ?>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
