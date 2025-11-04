<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

<style>
  .box {
    background: #ffffff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04), 0 8px 24px rgba(0, 0, 0, 0.06);
    margin-bottom: 16px;
  }
  main {
    padding: 16px;
  }
  .page-header {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 16px;
    color: black;
  }
  .stats {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
  }
  .stat {
    background: #f8fafc;
    border: 1px solid #eef2f7;
    border-radius: 8px;
    padding: 12px 16px;
  }
  .table {
    width: 100%;
    border-collapse: collapse;
  }
  .table th,
  .table td {
    padding: 12px;
    border-bottom: 1px solid #eeeeee;
  }
  .table thead th {
    background: #f9fafb;
    text-align: left;
  }
</style>

<main>
  <div class="box">
    <div class="page-header">CHAKANOKS - Transfers</div>
  </div>

  <div class="box">
    <p>Manage stock movement between Chakanoks branches to ensure all locations have enough roasted chicken, side dishes, and supplies. Transfers help avoid shortages and reduce waste.</p>
  </div>

  <div class="box">
    <div class="stats">
      <div class="stat">📦 Pending Transfers: 5</div>
      <div class="stat">🚚 In Transit: 3</div>
      <div class="stat">✅ Completed Today: 7</div>
    </div>
  </div>

  <div class="box">
    <h3>Transfer Records</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Transfer ID</th>
          <th>From Branch</th>
          <th>To Branch</th>
          <th>Item</th>
          <th>Quantity</th>
          <th>Status</th>
          <th>Expected Arrival</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>T001</td>
          <td>Davao Downtown</td>
          <td>Davao Buhangin</td>
          <td>Roasted Chicken</td>
          <td>25 pcs</td>
          <td>In Transit</td>
          <td>Aug 13, 2025</td>
        </tr>
        <tr>
          <td>T002</td>
          <td>Davao Matina</td>
          <td>Davao Toril</td>
          <td>Lechon Sauce Packets</td>
          <td>100 packs</td>
          <td>Pending</td>
          <td>Aug 14, 2025</td>
        </tr>
        <tr>
          <td>T003</td>
          <td>Davao Buhangin</td>
          <td>Davao Matina</td>
          <td>Charcoal Bags</td>
          <td>10 sacks</td>
          <td>Completed</td>
          <td>Aug 12, 2025</td>
        </tr>
        <tr>
          <td>T004</td>
          <td>Davao Downtown</td>
          <td>Davao Toril</td>
          <td>Chicken Marinade</td>
          <td>30 liters</td>
          <td>In Transit</td>
          <td>Aug 13, 2025</td>
        </tr>
        <tr>
          <td>T005</td>
          <td>Davao Matina</td>
          <td>Davao Downtown</td>
          <td>Disposable Plates</td>
          <td>500 pcs</td>
          <td>Completed</td>
          <td>Aug 12, 2025</td>
        </tr>
      </tbody>
    </table>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
