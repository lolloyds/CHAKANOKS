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

  <div class="box">
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
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
