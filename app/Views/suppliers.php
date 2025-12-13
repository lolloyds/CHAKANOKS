<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

<main>
  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: #f4f6f9;
    }
    .box {
      background: #ffffff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      margin-bottom: 20px;
    }
    h2 {
      margin-top: 0;
      color: #333;
    }
    .stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      margin-bottom: 20px;
    }
    .stat {
      background: #ffffff;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      text-align: center;
      font-weight: bold;
      color: #333;
    }
    .table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
      background: #ffffff;
      border-radius: 8px;
      overflow: hidden;
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
    .badge.active { background: #66bb6a; color: #fff; }
    .badge.pending { background: #ffb74d; color: #fff; }
    .badge.inactive { background: #e57373; color: #fff; }
    .btn-edit, .btn-delete {
      padding: 6px 12px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 12px;
      margin: 2px;
    }
    .btn-edit { background: #42a5f5; color: #fff; }
    .btn-delete { background: #e53935; color: #fff; }
  </style>

  <div class="box">
    <h2>Suppliers</h2>
    <p>
      The Suppliers section contains the list of all vendors providing goods and services for Chakanok's Roasted Chicken House.
      It includes suppliers for fresh chicken, spices, packaging, beverages, and cooking fuel.
      Managing supplier information ensures timely deliveries, better pricing, and high-quality raw materials for consistent product quality.
    </p>
  </div>

  <div class="stats">
    <div class="stat">Total Suppliers: <?= $stats['total'] ?? 0 ?></div>
    <div class="stat">Active: <?= $stats['active'] ?? 0 ?></div>
    <div class="stat">Pending: <?= $stats['pending'] ?? 0 ?></div>
    <div class="stat">Inactive: <?= $stats['inactive'] ?? 0 ?></div>
  </div>

  <!-- Search & Filters -->
  <div class="box" style="margin-bottom: 15px; padding: 15px; background: #fff;">
    <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
      <div style="position: relative; flex: 1; min-width: 200px;">
        <input type="text" id="supplierSearch" placeholder="Search suppliers..." style="width: 100%; padding: 10px 40px 10px 15px; border: 2px solid #ddd; border-radius: 8px; font-size: 14px;">
        <i class="fas fa-search" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #666; font-size: 16px;"></i>
      </div>
      <select id="statusFilter" style="padding: 10px 15px; border: 2px solid #ddd; border-radius: 8px; font-size: 14px; min-width: 120px;">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="pending">Pending</option>
        <option value="inactive">Inactive</option>
      </select>
      <select id="supplyTypeFilter" style="padding: 10px 15px; border: 2px solid #ddd; border-radius: 8px; font-size: 14px; min-width: 140px;">
        <option value="">All Supply Types</option>
        <option value="Meat">Meat</option>
        <option value="Seasoning">Seasoning</option>
        <option value="Fuel">Fuel</option>
        <option value="Packaging">Packaging</option>
        <option value="Beverage">Beverage</option>
      </select>
    </div>
  </div>

  <div class="box">
    <table class="table" id="suppliersTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Supplier Name</th>
          <th>Contact Person</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Supply Type</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($suppliers)): ?>
          <tr>
            <td colspan="8" style="text-align: center; padding: 20px;">No suppliers found</td>
          </tr>
        <?php else: ?>
          <?php foreach ($suppliers as $supplier): ?>
            <tr>
              <td>SUP-<?= str_pad($supplier['id'], 3, '0', STR_PAD_LEFT) ?></td>
              <td><?= esc($supplier['supplier_name'] ?? 'N/A') ?></td>
              <td><?= esc($supplier['contact_person'] ?? 'N/A') ?></td>
              <td><?= esc($supplier['phone'] ?? 'N/A') ?></td>
              <td><?= esc($supplier['email'] ?? 'N/A') ?></td>
              <td><?= esc($supplier['supply_type'] ?? 'N/A') ?></td>
              <td>
                <span class="badge <?= strtolower($supplier['status'] ?? 'active') ?>">
                  <?= esc($supplier['status'] ?? 'Active') ?>
                </span>
              </td>
              <td>
                <a href="<?= base_url('suppliers/edit/' . $supplier['id']) ?>">
                  <button class="btn-edit">Edit</button>
                </a>
                <button class="btn-delete" onclick="deleteSupplier(<?= $supplier['id'] ?>)">Delete</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<script>
function deleteSupplier(id) {
  if (!confirm('Are you sure you want to delete this supplier?')) return;

  window.location.href = '<?= base_url('suppliers/delete/') ?>' + id;
}

// Search and Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('supplierSearch');
  const statusFilter = document.getElementById('statusFilter');
  const supplyTypeFilter = document.getElementById('supplyTypeFilter');
  const table = document.getElementById('suppliersTable');
  const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

  function filterTable() {
    const searchTerm = searchInput.value.toLowerCase();
    const statusValue = statusFilter.value.toLowerCase();
    const supplyTypeValue = supplyTypeFilter.value.toLowerCase();

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

        // Supply type filter
        if (supplyTypeValue) {
          const supplyTypeCell = cells[5]; // Supply Type column
          if (supplyTypeCell) {
            const supplyTypeText = supplyTypeCell.textContent.toLowerCase().trim();
            if (!supplyTypeText.includes(supplyTypeValue)) {
              showRow = false;
            }
          }
        }
      }

      row.style.display = showRow ? '' : 'none';
    }
  }

  // Add event listeners
  searchInput.addEventListener('input', filterTable);
  statusFilter.addEventListener('change', filterTable);
  supplyTypeFilter.addEventListener('change', filterTable);
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
