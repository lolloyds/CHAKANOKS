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
    h2, h3 {
      margin-top: 0;
      color: #333;
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
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
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
    .form-group input, .form-group select {
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 14px;
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
    .btn-submit { background: orange; color: #fff; width: 100%; margin-top: 10px; }
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
    .badge.rejected { background: #e57373; color: #fff; }
  </style>

  <div class="box">
    <h2>📑 Purchase Request</h2>
    <div class="desc">
      Create and track requests from branches. Approved requests can be converted to Purchase Orders.
    </div>
  </div>

  <div class="grid grid-2">
    <div class="box">
      <h3>📊 Quick Summary</h3>
      <div class="row">
        <div class="form-group">
          <label>Total Requests (This Week)</label>
          <input type="text" value="14" readonly>
        </div>
        <div class="form-group">
          <label>Pending Approvals</label>
          <input type="text" value="5" readonly>
        </div>
      </div>
    </div>

    <div class="box">
      <h3>📝 New Request</h3>
      <div class="row">
        <div class="form-group">
          <label for="branch">Branch</label>
          <select id="branch">
            <option>Davao - Bajada</option>
            <option>Davao - Matina</option>
            <option>Davao - Toril</option>
          </select>
        </div>
        <div class="form-group">
          <label for="needed">Date Needed</label>
          <input type="date" id="needed">
        </div>
      </div>

      <div class="row">
        <div class="form-group">
          <label for="item">Item</label>
          <input type="text" id="item" placeholder="e.g., Chicken, Rice, Spices">
        </div>
        <div class="form-group">
          <label for="qty">Quantity</label>
          <input type="number" id="qty" placeholder="e.g., 20">
        </div>
      </div>

      <div class="form-group">
        <label for="notes">Notes</label>
        <input type="text" id="notes" placeholder="Optional notes for approver">
      </div>

      <button type="button" class="btn-submit">Submit Request</button>
    </div>
  </div>

  <div class="box">
    <h3>⏳ Pending Requests</h3>
    <table class="table">
      <tr>
        <th>Request ID</th>
        <th>Branch</th>
        <th>Items</th>
        <th>Date Requested</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
      <tr>
        <td>PR-201</td>
        <td>Bajada</td>
        <td>20kg Chicken, 10kg Rice</td>
        <td>Aug 25, 2025</td>
        <td><span class="badge pending">Pending</span></td>
        <td>
          <button class="btn-approve">Approve</button>
          <button class="btn-reject">Reject</button>
        </td>
      </tr>
      <tr>
        <td>PR-202</td>
        <td>Toril</td>
        <td>5kg Spices, 500pcs Boxes</td>
        <td>Aug 24, 2025</td>
        <td><span class="badge approved">Approved</span></td>
        <td>
          <a href="purchase-order.php"><button class="btn-create">Create PO</button></a>
        </td>
      </tr>
      <tr>
        <td>PR-203</td>
        <td>Matina</td>
        <td>10kg Veggies</td>
        <td>Aug 24, 2025</td>
        <td><span class="badge rejected">Rejected</span></td>
        <td>
          <button class="btn-reject">View</button>
        </td>
      </tr>
    </table>
  </div>

  <div class="box">
    <h3>🕒 Recent Activity</h3>
    <table class="table">
      <tr>
        <th>Time</th>
        <th>Activity</th>
        <th>User</th>
      </tr>
      <tr>
        <td>09:40 AM</td>
        <td>✅ PR-202 approved and marked ready for PO</td>
        <td>Manager</td>
      </tr>
      <tr>
        <td>08:55 AM</td>
        <td>📝 New request PR-204 from Bajada</td>
        <td>Branch Staff</td>
      </tr>
      <tr>
        <td>Yesterday</td>
        <td>📦 PR-198 converted to PO-505</td>
        <td>Purchasing</td>
      </tr>
    </table>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
