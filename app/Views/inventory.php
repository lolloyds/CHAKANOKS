<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

  <main>
    <!-- Page Title -->
    <div class="page-header">
      <h1>CHAKANOKS</h1>
    </div>

    <h2>Inventory</h2>

    <div class="stats">
      <div class="stat">Total Items: 120</div>
      <div class="stat">Low Stock: 8</div>
      <div class="stat">Out of Stock: 2</div>
      <div class="stat">Near Expiry: 5</div>
    </div>

    <p>
      The Inventory section provides a complete list of all items available in Chakanok’s Roasted Chicken House.
      It tracks raw ingredients, cooking supplies, packaging materials, and beverages across all branches.
      This helps ensure that fresh chicken and essential supplies are always available for daily operations.
    </p>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
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
          <td>INV-001</td>
          <td>Whole Chicken</td>
          <td>Meat</td>
          <td>45</td>
          <td>pcs</td>
          <td>2025-08-20</td>
          <td>In Stock</td>
        </tr>
        <tr>
          <td>INV-002</td>
          <td>Chicken Marinade Mix</td>
          <td>Seasoning</td>
          <td>10</td>
          <td>kg</td>
          <td>2025-09-10</td>
          <td>Low Stock</td>
        </tr>
        <tr>
          <td>INV-003</td>
          <td>Charcoal Bags</td>
          <td>Fuel</td>
          <td>50</td>
          <td>bags</td>
          <td>N/A</td>
          <td>In Stock</td>
        </tr>
        <tr>
          <td>INV-004</td>
          <td>Plastic Food Containers</td>
          <td>Packaging</td>
          <td>200</td>
          <td>pcs</td>
          <td>N/A</td>
          <td>In Stock</td>
        </tr>
        <tr>
          <td>INV-005</td>
          <td>Soft Drinks</td>
          <td>Beverage</td>
          <td>30</td>
          <td>cases</td>
          <td>2025-12-15</td>
          <td>In Stock</td>
        </tr>
        <tr>
          <td>INV-006</td>
          <td>Banana Leaves</td>
          <td>Wrapping</td>
          <td>80</td>
          <td>pcs</td>
          <td>2025-08-18</td>
          <td>Near Expiry</td>
        </tr>
      </tbody>
    </table>

  </main>

<?php include __DIR__ . '/includes/footer.php'; ?>
