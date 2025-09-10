<?php include __DIR__ . '/../branch includes/header.php'; ?>
<?php include __DIR__ . '/../branch includes/sidebar.php'; ?>

<main>
  <!-- Page Title -->
  <div class="page-header">
    <h1>CHAKANOKS</h1>
    <h2>Branch Dashboard</h2>
  </div>

  <!-- Welcome Message -->
  <div class="welcome-section" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
    <h3>Welcome to <?= esc($branch->name) ?></h3>
    <p><strong>Location:</strong> <?= esc($branch->address) ?></p>
    <p>Manage your branch inventory, track stock levels, and handle daily operations efficiently.</p>
  </div>

  <!-- Quick Stats -->
  <div class="stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="stat-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
      <h4>Total Items</h4>
      <div style="font-size: 2em; font-weight: bold; color: #007bff;"><?= esc($totalItems) ?></div>
    </div>
    <div class="stat-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
      <h4>Low Stock Items</h4>
      <div style="font-size: 2em; font-weight: bold; color: #ffc107;"><?= esc($lowStock) ?></div>
    </div>
    <div class="stat-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
      <h4>Out of Stock</h4>
      <div style="font-size: 2em; font-weight: bold; color: #dc3545;"><?= esc($outOfStock) ?></div>
    </div>
    <div class="stat-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
      <h4>Near Expiry</h4>
      <div style="font-size: 2em; font-weight: bold; color: #fd7e14;"><?= esc($nearExpiry) ?></div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="quick-actions" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 30px;">
    <h4>Quick Actions</h4>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-top: 15px;">
      <a href="<?= base_url('inventory/binventory') ?>" style="display: block; padding: 15px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; text-align: center;">
        📦 View Inventory
      </a>
      <button onclick="openModal('addItemModal')" style="padding: 15px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
        ➕ Add Item
      </button>
      <button onclick="openModal('useItemModal')" style="padding: 15px; background: #ffc107; color: black; border: none; border-radius: 5px; cursor: pointer;">
        📤 Use Item
      </button>
      <button onclick="openModal('reportModal')" style="padding: 15px; background: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer;">
        🚨 Report Damage
      </button>
    </div>
  </div>

  <!-- Recent Activity -->
  <div class="recent-activity" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <h4>Recent Activity</h4>
    <div style="margin-top: 15px;">
      <p style="color: #6c757d; font-style: italic;">No recent activity to display.</p>
      <small>Activity will appear here as you perform inventory operations.</small>
    </div>
  </div>

  <!-- Quick Add Item Modal -->
  <div id="addItemModal" class="modal" style="display:none;">
    <form method="post" action="<?= base_url('inventory/addItem') ?>" style="background: white; padding: 20px; border-radius: 8px; width: 400px; max-width: 90vw;">
      <h3>Add Item to Inventory</h3>
      <label>Item Name:</label>
      <input type="text" name="item_name" placeholder="Type item name..." required autocomplete="off" style="width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
      <br>
      <label>Quantity:</label>
      <input type="number" name="quantity" required style="width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
      <br>
      <div style="display: flex; gap: 10px; margin-top: 15px;">
        <button type="submit" style="flex: 1; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Save</button>
        <button type="button" onclick="closeModal('addItemModal')" style="flex: 1; padding: 10px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Cancel</button>
      </div>
    </form>
  </div>

  <!-- Quick Use Item Modal -->
  <div id="useItemModal" class="modal" style="display:none;">
    <form method="post" action="<?= base_url('inventory/useItem') ?>" style="background: white; padding: 20px; border-radius: 8px; width: 400px; max-width: 90vw;">
      <h3>Use Item from Inventory</h3>
      <label>Item Name:</label>
      <input type="text" name="item_name" placeholder="Type item name..." required autocomplete="off" style="width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
      <br>
      <label>Quantity:</label>
      <input type="number" name="quantity" required style="width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
      <br>
      <div style="display: flex; gap: 10px; margin-top: 15px;">
        <button type="submit" style="flex: 1; padding: 10px; background: #ffc107; color: black; border: none; border-radius: 4px; cursor: pointer;">Use Item</button>
        <button type="button" onclick="closeModal('useItemModal')" style="flex: 1; padding: 10px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Cancel</button>
      </div>
    </form>
  </div>

  <!-- Quick Report Modal -->
  <div id="reportModal" class="modal" style="display:none;">
    <form method="post" action="<?= base_url('inventory/reportDamage') ?>" style="background: white; padding: 20px; border-radius: 8px; width: 400px; max-width: 90vw;">
      <h3>Report Damage / Expiry</h3>
      <label>Item Name:</label>
      <input type="text" name="item_name" placeholder="Type item name..." required autocomplete="off" style="width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
      <br>
      <label>Quantity:</label>
      <input type="number" name="quantity" required style="width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
      <br>
      <label>Reason:</label>
      <select name="reason" style="width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
        <option value="damaged">Damaged</option>
        <option value="expired">Expired</option>
      </select>
      <br>
      <div style="display: flex; gap: 10px; margin-top: 15px;">
        <button type="submit" style="flex: 1; padding: 10px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Report</button>
        <button type="button" onclick="closeModal('reportModal')" style="flex: 1; padding: 10px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Cancel</button>
      </div>
    </form>
  </div>

  <!-- Messages -->
  <div id="message" style="display: none; padding: 10px; margin-bottom: 20px; border-radius: 5px; position: fixed; top: 20px; right: 20px; z-index: 1000;"></div>

  <script>
    function openModal(id) {
      document.getElementById(id).style.display = 'block';
    }
    function closeModal(id) {
      document.getElementById(id).style.display = 'none';
    }

    // Handle AJAX form submissions
    document.addEventListener('DOMContentLoaded', function() {
      // Add hidden branch_id to forms
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

              // Hide message after 3 seconds
              setTimeout(() => {
                messageDiv.style.display = 'none';
              }, 3000);
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

<?php include __DIR__ . '/../branch includes/footer.php'; ?>
