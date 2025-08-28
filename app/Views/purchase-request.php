<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

  <main>
    <h2>Purchase Request</h2>
    <div class="desc">
      Create and track requests from branches. Approved requests can be converted to Purchase Orders.
    </div>

    <div class="grid grid-2">
      <div class="box">
        <h3>Quick Summary</h3>
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
        <h3>New Request</h3>
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

        <button type="button">Submit Request</button>
      </div>
    </div>

    <div class="box" style="margin-top:20px;">
      <h3>Pending Requests</h3>
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
            <button>Approve</button>
            <button>Reject</button>
          </td>
        </tr>
        <tr>
          <td>PR-202</td>
          <td>Toril</td>
          <td>5kg Spices, 500pcs Boxes</td>
          <td>Aug 24, 2025</td>
          <td><span class="badge approved">Approved</span></td>
          <td>
            <a href="purchase-order.php"><button>Create PO</button></a>
          </td>
        </tr>
        <tr>
          <td>PR-203</td>
          <td>Matina</td>
          <td>10kg Veggies</td>
          <td>Aug 24, 2025</td>
          <td><span class="badge rejected">Rejected</span></td>
          <td>
            <button>View</button>
          </td>
        </tr>
      </table>
    </div>

    <div class="box" style="margin-top:20px;">
      <h3>Recent Activity</h3>
      <table class="table">
        <tr>
          <th>Time</th>
          <th>Activity</th>
          <th>User</th>
        </tr>
        <tr>
          <td>09:40 AM</td>
          <td>PR-202 approved and marked ready for PO</td>
          <td>Manager</td>
        </tr>
        <tr>
          <td>08:55 AM</td>
          <td>New request PR-204 from Bajada</td>
          <td>Branch Staff</td>
        </tr>
        <tr>
          <td>Yesterday</td>
          <td>PR-198 converted to PO-505</td>
          <td>Purchasing</td>
        </tr>
      </table>
    </div>
  </main>

<?php include __DIR__ . '/includes/footer.php'; ?>

