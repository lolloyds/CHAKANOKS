<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

  <main>
    <h2>Suppliers</h2>

    <div class="stats">
      <div class="stat">Total Suppliers: 12</div>
      <div class="stat">Active: 10</div>
      <div class="stat">Pending Contracts: 2</div>
      <div class="stat">Preferred Partners: 5</div>
    </div>

    <p>
      The Suppliers section contains the list of all vendors providing goods and services for Chakanok’s Roasted Chicken House.
      It includes suppliers for fresh chicken, spices, packaging, beverages, and cooking fuel.
      Managing supplier information ensures timely deliveries, better pricing, and high-quality raw materials for consistent product quality.
    </p>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>Supplier ID</th>
          <th>Supplier Name</th>
          <th>Contact Person</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Supply Type</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>SUP-001</td>
          <td>Fresh Farms Poultry</td>
          <td>Juan Dela Cruz</td>
          <td>+63 912 345 6789</td>
          <td>juan@freshfarms.com</td>
          <td>Whole Chickens</td>
          <td>Active</td>
        </tr>
        <tr>
          <td>SUP-002</td>
          <td>Spice Master Trading</td>
          <td>Ana Santos</td>
          <td>+63 917 555 8888</td>
          <td>ana@spicemaster.ph</td>
          <td>Seasonings & Marinades</td>
          <td>Active</td>
        </tr>
        <tr>
          <td>SUP-003</td>
          <td>Green Leaf Packaging</td>
          <td>Mark Lopez</td>
          <td>+63 915 444 7777</td>
          <td>mark@greenleafpack.ph</td>
          <td>Banana Leaves & Food Containers</td>
          <td>Active</td>
        </tr>
        <tr>
          <td>SUP-004</td>
          <td>Beverage Brothers Co.</td>
          <td>Maria Rivera</td>
          <td>+63 918 222 1111</td>
          <td>maria@bevbrothers.ph</td>
          <td>Soft Drinks & Bottled Water</td>
          <td>Active</td>
        </tr>
        <tr>
          <td>SUP-005</td>
          <td>Charcoal King Supply</td>
          <td>Carlos Reyes</td>
          <td>+63 926 333 6666</td>
          <td>carlos@charcoalking.ph</td>
          <td>Cooking Fuel</td>
          <td>Pending Contract</td>
        </tr>
      </tbody>
    </table>

  </main>

<?php include __DIR__ . '/includes/footer.php'; ?>

