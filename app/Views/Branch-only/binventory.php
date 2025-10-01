<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>



<main>
  <!-- Page Title -->
  <div class="page-header">
    <h1>CHAKANOKS</h1>
    <h2>Branch Inventory - <?= esc($branch->name) ?></h2>
    <p><strong>Location:</strong> <?= esc($branch->address) ?></p>
  </div>

  <!-- Quick Stats -->
  <div class="stats">
    <div class="stat">Total Items: <?= esc($totalItems) ?></div>
    <div class="stat">Low Stock: <?= esc($lowStock) ?></div>
    <div class="stat">Out of Stock: <?= esc($outOfStock) ?></div>
    <div class="stat">Near Expiry: <?= esc($nearExpiry) ?></div>
  </div>

  <!-- Search & Filters -->
  <div class="filters" style="margin: 15px 0;">
    <input type="text" placeholder="Search items..." />
    <select>
      <option value="">Category</option>
      <option value="Meat">Meat</option>
      <option value="Seasoning">Seasoning</option>
      <option value="Fuel">Fuel</option>
      <option value="Packaging">Packaging</option>
      <option value="Beverage">Beverage</option>
    </select>
    <select>
      <option value="">Status</option>
      <option value="In Stock">In Stock</option>
      <option value="Low Stock">Low Stock</option>
      <option value="Out of Stock">Out of Stock</option>
      <option value="Near Expiry">Near Expiry</option>
    </select>
  </div>

  <!-- Messages -->
  <div id="message" style="display: none; padding: 10px; margin-bottom: 20px; border-radius: 5px;"></div>

  <!-- Action Buttons -->
  <div class="actions" style="margin-bottom: 20px;">
    <button type="button" onclick="openModal('addItemModal')">+ Add Item</button>
    <button type="button" onclick="openModal('useItemModal')">Use Item</button>
    <button type="button" onclick="openModal('reportModal')">Report Damage/Expiry</button>
  </div>


  <!-- Items Table -->
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
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($inventory as $item): ?>
        <?php
          if ($item['quantity'] == 0) {
              $status = "Out of Stock";
          } elseif ($item['quantity'] <= $item['reorder_level']) {
              $status = "Low Stock";
          } elseif ($item['expiry_date'] && $item['expiry_date'] <= date('Y-m-d', strtotime('+7 days'))) {
              $status = "Near Expiry";
          } else {
              $status = "In Stock";
          }
        ?>
        <tr>
          <td>INV-<?= esc($item['item_id']) ?></td>
          <td><?= esc($item['item_name']) ?></td>
          <td><?= esc($item['category']) ?></td>
          <td><?= esc($item['quantity']) ?></td>
          <td><?= esc($item['unit']) ?></td>
          <td><?= $item['expiry_date'] ?? 'N/A' ?></td>
          <td><?= $status ?></td>
          <td>
            <button>Edit</button>
            <button>Remove</button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>



    <!-- Add Item Modal -->
  <div id="addItemModal" class="modal" style="display:none;">
    <form method="post" action="<?= base_url('inventory/addItem') ?>">
      <h3>Add Item</h3>
      <label>Item Name:</label>
      <input type="text" name="item_name" placeholder="Type item name..." required autocomplete="off">
      <br>
      <label>Quantity:</label>
      <input type="number" name="quantity" required>
      <br>
      <button type="submit">Save</button>
      <button type="button" onclick="closeModal('addItemModal')">Cancel</button>
    </form>
  </div>

  <!-- Use Item Modal -->
  <div id="useItemModal" class="modal" style="display:none;">
    <form method="post" action="<?= base_url('inventory/useItem') ?>">
      <h3>Use Item</h3>
      <label>Item Name:</label>
      <input type="text" name="item_name" placeholder="Type item name..." required autocomplete="off">
      <br>
      <label>Quantity:</label>
      <input type="number" name="quantity" required>
      <br>
      <button type="submit">Save</button>
      <button type="button" onclick="closeModal('useItemModal')">Cancel</button>
    </form>
  </div>

  <!-- Report Damage/Expiry Modal -->
  <div id="reportModal" class="modal" style="display:none;">
    <form method="post" action="<?= base_url('inventory/reportDamage') ?>">
      <h3>Report Damage / Expiry</h3>
      <label>Item Name:</label>
      <input type="text" name="item_name" placeholder="Type item name..." required autocomplete="off">
      <br>
      <label>Quantity:</label>
      <input type="number" name="quantity" required>
      <br>
      <label>Reason:</label>
      <select name="reason">
        <option value="damaged">Damaged</option>
        <option value="expired">Expired</option>
      </select>
      <br>
      <button type="submit">Save</button>
      <button type="button" onclick="closeModal('reportModal')">Cancel</button>
    </form>
  </div>

  <script>
    function openModal(id) {
      document.getElementById(id).style.display = 'block';
    }
    function closeModal(id) {
      document.getElementById(id).style.display = 'none';
    }

    // Handle AJAX form submissions
    document.addEventListener('DOMContentLoaded', function() {
      // Add hidden branch_id to forms (assuming branch_id is 1 for now, should be dynamic)
      const forms = document.querySelectorAll('.modal form');
      forms.forEach(form => {
        const branchInput = document.createElement('input');
        branchInput.type = 'hidden';
        branchInput.name = 'branch_id';
        branchInput.value = '<?= $branch->id ?>'; // Use actual branch_id from session
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
    });
  </script>

</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
