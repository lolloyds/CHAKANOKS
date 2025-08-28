<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

  <main>
    <style>
      .box { background: #ffffff; padding: 16px; border-radius: 10px; box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06); margin-bottom: 16px; }
      .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; }
      .stat { background: #f7f9fc; border: 1px solid #eef2f7; border-radius: 8px; padding: 12px; }
      .table { width: 100%; border-collapse: collapse; }
      .table th, .table td { text-align: left; padding: 10px 12px; border-bottom: 1px solid #eef2f7; }
      .table th { background: #f9fbff; }
    </style>

    <div class="box">
      <h2>Dashboard</h2>
    </div>

    <div class="box">
      <div class="desc">
        Welcome to the CHAKANOKS Management Dashboard.  
        Here you can monitor real-time operations including inventory, suppliers, purchase requests, deliveries, transfers, and franchise performance.
      </div>
    </div>

    <div class="box">
      <div class="stats">
        <div class="stat">Total Branches: 5</div>
        <div class="stat">Active Deliveries: 5</div>
        <div class="stat">Pending Orders: 7</div>
        <div class="stat">Suppliers:5</div>
      </div>
    </div>

    <div class="box">
      <h3>Quick Overview</h3>
    </div>

    <div class="box">
      <h4>Today’s Highlights</h4>
      <ul>
        <li>🔹 3 Deliveries scheduled for Davao branches</li>
        <li>🔹 2 Pending Purchase Requests awaiting approval</li>
        <li>🔹 Inventory running low on Chicken (20kg left)</li>
        <li>🔹 New Supplier partnership request received</li>
      </ul>
    </div>

    <div class="box">
      <h4>Recent Activity</h4>
      <table class="table">
        <tr>
          <th>Time</th>
          <th>Activity</th>
          <th>User</th>
        </tr>
        <tr>
          <td>08:15 AM</td>
          <td>Purchase Request approved for 50kg rice</td>
          <td>Manager</td>
        </tr>
        <tr>
          <td>09:30 AM</td>
          <td>Delivery dispatched to Chakanoks Bajada</td>
          <td>Juan Dela Cruz</td>
        </tr>
        <tr>
          <td>10:00 AM</td>
          <td>Inventory updated: +30 roasted chickens</td>
          <td>Kitchen Staff</td>
        </tr>
      </table>
    </div>
  </main>

<?php include __DIR__ . '/includes/footer.php'; ?>

