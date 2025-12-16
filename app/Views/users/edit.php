<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>

<style>
  body {
    font-family: "Segoe UI", Arial, sans-serif;
    background: #ffeef5;
  }

  main {
    background: #ffeef5;
    padding: 20px;
  }

  .box {
    background: #fff5f8;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
    border: 1px solid #ffd6e8;
  }

  h2, h3 {
    margin-top: 0;
    color: #333;
    font-weight: 600;
  }

  h2 {
    font-size: 24px;
    margin-bottom: 10px;
  }

  h3 {
    font-size: 18px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ffd6e8;
  }

  .form-group {
    margin-bottom: 16px;
  }

  .form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #444;
    font-size: 14px;
  }

  .form-group input, .form-group select, .form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ffd6e8;
    border-radius: 6px;
    font-size: 14px;
    background: #fff;
    box-sizing: border-box;
    transition: border-color 0.3s, box-shadow 0.3s;
  }

  .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    outline: none;
    border-color: #ff69b4;
    box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
  }

  .btn-add {
    background: linear-gradient(135deg, #ff69b4 0%, #ff1493 100%);
    color: #fff;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(255, 105, 180, 0.3);
    margin-bottom: 20px;
  }

  .btn-add:hover {
    background: linear-gradient(135deg, #ff1493 0%, #dc143c 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(255, 105, 180, 0.4);
  }

  .required { color: red; }

  .alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 14px;
  }

  .alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
  }
</style>

<main>
    <div class="box">
        <h2>👤 Edit User</h2>
        <p style="color: #666; line-height: 1.6; margin: 0;">
            Modify user details, role, and permissions.
        </p>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="box">
        <h3>📝 User Information</h3>
        <form action="<?= base_url('users/update/' . $user['id']) ?>" method="post">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div class="form-group">
                    <label for="username">
                        Username <span class="required">*</span>
                    </label>
                    <input type="text" id="username" name="username"
                           value="<?= old('username', esc($user['username'])) ?>" required>
                    <small style="color: #666;">Unique username for login</small>
                </div>
                <div class="form-group">
                    <label for="email">
                        Email <span class="required">*</span>
                    </label>
                    <input type="email" id="email" name="email"
                           value="<?= old('email', esc($user['email'])) ?>" required>
                    <small style="color: #666;">Valid email address</small>
                </div>
                <div class="form-group">
                    <label for="password">
                        New Password
                    </label>
                    <input type="password" id="password" name="password" minlength="6">
                    <small style="color: #666;">Leave blank to keep current password. Minimum 6 characters if changing.</small>
                </div>
                <div class="form-group">
                    <label for="role">
                        Role <span class="required">*</span>
                    </label>
                    <select id="role" name="role" required onchange="toggleFields()">
                        <option value="">Select Role</option>
                        <option value="System Administrator" <?= old('role', $user['role']) === 'System Administrator' ? 'selected' : '' ?>>System Administrator</option>
                        <option value="Central Office Admin" <?= old('role', $user['role']) === 'Central Office Admin' ? 'selected' : '' ?>>Central Office Admin</option>
                        <option value="Branch Manager" <?= old('role', $user['role']) === 'Branch Manager' ? 'selected' : '' ?>>Branch Manager</option>
                        <option value="Inventory Staff" <?= old('role', $user['role']) === 'Inventory Staff' ? 'selected' : '' ?>>Inventory Staff</option>
                        <option value="Supplier" <?= old('role', $user['role']) === 'Supplier' ? 'selected' : '' ?>>Supplier</option>
                        <option value="Logistics Coordinator" <?= old('role', $user['role']) === 'Logistics Coordinator' ? 'selected' : '' ?>>Logistics Coordinator</option>
                        <option value="Franchise Manager" <?= old('role', $user['role']) === 'Franchise Manager' ? 'selected' : '' ?>>Franchise Manager</option>
                    </select>
                </div>
                <div class="form-group" id="branchField" style="display: none;">
                    <label for="branch_id">Branch</label>
                    <select id="branch_id" name="branch_id">
                        <option value="">Select Branch</option>
                        <?php foreach ($branches as $branch): ?>
                            <option value="<?= $branch['id'] ?>" <?= old('branch_id', $user['branch_id']) == $branch['id'] ? 'selected' : '' ?>>
                                <?= esc($branch['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: #666;">Required for Branch Manager and Inventory Staff roles</small>
                </div>
                <div class="form-group" id="supplierField" style="display: none;">
                    <label for="supplier_id">Supplier</label>
                    <select id="supplier_id" name="supplier_id">
                        <option value="">Select Supplier</option>
                        <?php foreach ($suppliers as $supplier): ?>
                            <option value="<?= $supplier['id'] ?>" <?= old('supplier_id', $user['supplier_id']) == $supplier['id'] ? 'selected' : '' ?>>
                                <?= esc($supplier['supplier_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: #666;">Required for Supplier role</small>
                </div>
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ffd6e8; display: flex; gap: 10px; justify-content: flex-end;">
                <a href="<?= base_url('users') ?>" style="background: #6c757d; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none;">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn-add">
                    <i class="fas fa-save"></i> Update User
                </button>
            </div>
        </form>
    </div>
</main>

<script>
function toggleFields() {
    const role = document.getElementById('role').value;
    const branchField = document.getElementById('branchField');
    const supplierField = document.getElementById('supplierField');
    const branchSelect = document.getElementById('branch_id');
    const supplierSelect = document.getElementById('supplier_id');

    // Hide all fields first
    branchField.style.display = 'none';
    supplierField.style.display = 'none';
    branchSelect.required = false;
    supplierSelect.required = false;

    // Show relevant fields based on role
    if (role === 'Branch Manager' || role === 'Inventory Staff') {
        branchField.style.display = 'block';
        branchSelect.required = true;
    } else if (role === 'Supplier') {
        supplierField.style.display = 'block';
        supplierSelect.required = true;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleFields();
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
