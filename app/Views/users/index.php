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

  .table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  }

  .table th, .table td {
    text-align: left;
    padding: 14px 16px;
    border-bottom: 1px solid #ffd6e8;
  }

  .table th {
    background: linear-gradient(135deg, #fff0f5 0%, #ffeef5 100%);
    font-weight: 600;
    color: #333;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
  }

  .table tbody tr {
    background: #fff;
    transition: background 0.2s;
  }

  .table tbody tr:hover {
    background: #fff0f5;
  }

  .badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
  }

  .badge.active {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
  }
  .badge.inactive {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
  }

  .table tbody tr.current-user {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-left: 4px solid #ffc107;
  }

  .table tbody tr.inactive-row {
    opacity: 0.6;
    background: #f8f9fa;
  }

  .action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }

  .btn-edit, .btn-delete, .btn-activate {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-block;
  }

  .btn-disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
  }

  .btn-edit {
    background: linear-gradient(135deg, #42a5f5 0%, #2196F3 100%);
    color: #fff;
    box-shadow: 0 2px 6px rgba(66, 165, 245, 0.3);
  }

  .btn-edit:hover {
    background: linear-gradient(135deg, #2196F3 0%, #1976d2 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(66, 165, 245, 0.4);
  }

  .btn-delete {
    background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
    color: #fff;
    box-shadow: 0 2px 6px rgba(229, 57, 53, 0.3);
  }

  .btn-delete:hover {
    background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(229, 57, 53, 0.4);
  }

  .btn-activate {
    background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%);
    color: #fff;
    box-shadow: 0 2px 6px rgba(102, 187, 106, 0.3);
  }

  .btn-activate:hover {
    background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(102, 187, 106, 0.4);
  }

  .btn-reset {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    color: #fff;
    box-shadow: 0 2px 6px rgba(255, 152, 0, 0.3);
  }

  .btn-reset:hover {
    background: linear-gradient(135deg, #f57c00 0%, #ef6c00 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(255, 152, 0, 0.4);
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

  /* Modal Styles */
  .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
  }

  .modal-content {
    background: #fff5f8;
    margin: 5% auto;
    padding: 30px;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    border: 1px solid #ffd6e8;
  }

  .modal-header {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
    padding-bottom: 10px;
    border-bottom: 2px solid #ffd6e8;
  }

  .modal-footer {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #ffd6e8;
  }

  .alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 14px;
  }

  .alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
  }

  .alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
  }
</style>

<main>
    <div class="box">
        <h2>👥 User Management</h2>
        <p style="color: #666; line-height: 1.6; margin: 0;">
            Manage system users, their roles, and access permissions. Only System Administrators can access this section.
        </p>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <a href="<?= base_url('users/create') ?>" class="btn-add">
        <i class="fas fa-plus"></i> Add New User
    </a>

    <div class="box">
        <h3>📋 User List</h3>
        <div id="alert-container"></div>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Branch</th>
                    <th>Supplier</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <?php
                    $isCurrentUser = ($user['id'] == $currentUserId);
                    $isInactive = ($user['status'] === 'inactive');
                    $rowClasses = [];
                    if ($isCurrentUser) $rowClasses[] = 'current-user';
                    if ($isInactive) $rowClasses[] = 'inactive-row';
                    $rowClass = implode(' ', $rowClasses);
                    ?>
                    <tr class="<?= $rowClass ?>">
                        <td><?= $user['id'] ?></td>
                        <td>
                            <strong><?= esc($user['username']) ?>
                                <?php if ($isCurrentUser): ?>
                                    <small style="color: #ff9800; font-weight: bold;">(You)</small>
                                <?php endif; ?>
                            </strong>
                        </td>
                        <td><?= esc($user['email']) ?></td>
                        <td>
                            <span class="badge bg-secondary"><?= esc($user['role']) ?></span>
                        </td>
                        <td>
                            <?= $user['branch_name'] ? esc($user['branch_name']) : '-' ?>
                        </td>
                        <td>
                            <?= $user['supplier_name'] ? esc($user['supplier_name']) : '-' ?>
                        </td>
                        <td>
                            <span class="badge <?= $user['status'] === 'active' ? 'active' : 'inactive' ?>">
                                <?= ucfirst($user['status']) ?>
                            </span>
                        </td>
                        <td>
                            <?= date('M d, Y', strtotime($user['created_at'])) ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?= base_url('users/edit/' . $user['id']) ?>" class="btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <?php if ($user['status'] === 'active'): ?>
                                    <button type="button" class="btn-delete
                                        <?= $isCurrentUser ? ' btn-disabled' : '' ?>"
                                        <?= $isCurrentUser ? 'disabled' : 'onclick="deactivateUser(' . $user['id'] . ', \'' . esc($user['username']) . '\')"' ?>
                                        title="<?= $isCurrentUser ? 'Cannot deactivate yourself' : 'Deactivate' ?>">
                                        <i class="fas fa-ban"></i> Deactivate
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn-activate"
                                            onclick="activateUser(<?= $user['id'] ?>, '<?= esc($user['username']) ?>')"
                                            title="Activate">
                                        <i class="fas fa-check"></i> Activate
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (empty($users)): ?>
            <div style="text-align: center; padding: 40px; color: #666;">
                <i class="fas fa-users fa-4x mb-3" style="color: #ddd;"></i>
                <h5>No users found</h5>
                <a href="<?= base_url('users/create') ?>" class="btn-add">
                    <i class="fas fa-plus"></i> Create First User
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Modals -->
<!-- Deactivate User Modal -->
<div class="modal" id="deactivateModal">
    <div class="modal-content">
        <div class="modal-header">Deactivate User</div>
        <div class="modal-body">
            Are you sure you want to deactivate user "<strong id="deactivateUsername"></strong>"?
            <br><small>The user will not be able to log in until reactivated.</small>
        </div>
        <div class="modal-footer">
            <button onclick="closeModal()" style="background: #6c757d; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Cancel</button>
            <form id="deactivateForm" method="post" style="display: inline;">
                <button type="submit" style="background: #ffc107; color: #000; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Deactivate</button>
            </form>
        </div>
    </div>
</div>

<!-- Activate User Modal -->
<div class="modal" id="activateModal">
    <div class="modal-content">
        <div class="modal-header">Activate User</div>
        <div class="modal-body">
            Are you sure you want to activate user "<strong id="activateUsername"></strong>"?
            <br><small>The user will be able to log in immediately.</small>
        </div>
        <div class="modal-footer">
            <button onclick="closeModal()" style="background: #6c757d; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Cancel</button>
            <form id="activateForm" method="post" style="display: inline;">
                <button type="submit" style="background: #28a745; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Activate</button>
            </form>
        </div>
    </div>
</div>



<script>
function showAlert(message, type) {
    const container = document.getElementById('alert-container');
    container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
    setTimeout(() => {
        container.innerHTML = '';
    }, 5000);
}

function deactivateUser(userId, username) {
    document.getElementById('deactivateUsername').textContent = username;
    document.getElementById('deactivateForm').action = `<?= base_url('users/deactivate/') ?>${userId}`;
    document.getElementById('deactivateModal').style.display = 'block';
}

function activateUser(userId, username) {
    document.getElementById('activateUsername').textContent = username;
    document.getElementById('activateForm').action = `<?= base_url('users/activate/') ?>${userId}`;
    document.getElementById('activateModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('deactivateModal').style.display = 'none';
    document.getElementById('activateModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modals = ['deactivateModal', 'activateModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
