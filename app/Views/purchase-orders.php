<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

  <main>
    <!-- Page Title -->
    <div class="page-title">
      <h1>CHAKANOKS</h1>
    </div>

    <h2>Purchase Orders</h2>
    <div class="desc">
      Review and manage official Purchase Orders (POs) generated from approved requests.
    </div>

    <div class="grid grid-2">
      <div class="box">
        <h3>Quick Summary</h3>
        <div class="row">
          <div class="form-group">
            <label>Total POs (This Month)</label>
            <input type="text" value="28" readonly>
          </div>
          <div class="form-group">
            <label>Pending Delivery</label>
            <input type="text" value="7" readonly>
          </div>
        </div>
      </div>

      <div class="box">
        <h3>Create New PO</h3>
        <div class="row">
          <div class="form-group">
            <label for="supplier">Supplier</label>
            <select id="supplier">
              <option>ABC Foods</option>
              <option>XYZ Poultry</option>
              <option>Fresh Market</option>
            </select>
          </div>
          <div class="form-group">
            <label for="date">Order Date</label>
            <input type="date" id="date">
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="items">Items</label>
            <input type="text" id="items" placeholder="e.g., 20kg Chicken, 10kg Rice">
          </div>
          <div class="form-group">
            <label for="total">Total Cost</label>
            <input type="number" id="total" placeholder="e.g., 15000">
          </div>
        </div>
        <button type="button">Generate PO</button>
      </div>
    </div>

    <div class="box" style="margin-top:20px;">
      <h3>Active Purchase Orders</h3>
      <table class="table">
        <tr>
          <th>PO ID</th>
          <th>Supplier</th>
          <th>Items</th>
          <th>Order Date</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
        <tr>
          <td>PO-505</td>
          <td>ABC Foods</td>
          <td>20kg Chicken, 10kg Rice</td>
          <td>Aug 24, 2025</td>
          <td><span class="badge pending">Pending Delivery</span></td>
          <td>
            <button>Mark Delivered</button>
          </td>
        </tr>
        <tr>
          <td>PO-506</td>
          <td>XYZ Poultry</td>
          <td>50kg Chicken</td>
          <td>Aug 23, 2025</td>
          <td><span class="badge approved">Approved</span></td>
          <td>
            <button>Cancel</button>
          </td>
        </tr>
        <tr>
          <td>PO-507</td>
          <td>Fresh Market</td>
          <td>10kg Veggies, 500pcs Boxes</td>
          <td>Aug 22, 2025</td>
          <td><span class="badge delivered">Delivered</span></td>
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
          <td>11:30 AM</td>
          <td>PO-505 marked as Pending Delivery</td>
          <td>Purchasing</td>
        </tr>
        <tr>
          <td>09:15 AM</td>
          <td>PO-504 received by Davao - Bajada</td>
          <td>Warehouse</td>
        </tr>
        <tr>
          <td>Yesterday</td>
          <td>PO-502 created from PR-198</td>
          <td>Manager</td>
        </tr>
      </table>
    </div>
  </main>

<?php include __DIR__ . '/includes/footer.php'; ?>
