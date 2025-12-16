    <aside>
      <nav>
        <?php
        $user = session()->get('user');
        if ($user) {
            if ($user['role'] === 'Supplier') {
                // Limited menu for Supplier role
                ?>
                <div><a href="<?= base_url('dashboard'); ?>">Dashboard</a></div>
                <div><a href="<?= base_url('supplier-orders'); ?>">Orders</a></div>
                <div><a href="<?= base_url('settings'); ?>">Settings</a></div>
                <div><a href="<?= base_url('logout'); ?>" onclick="return confirm('Are you sure you want to logout?')">Logout</a></div>
                <?php
            } elseif (in_array($user['role'], ['Branch Manager', 'Inventory Staff'])) {
                // Limited menu for Branch Manager and Inventory Staff
                ?>
                <div><a href="<?= base_url('dashboard'); ?>">Dashboard</a></div>
                <div><a href="<?= base_url('purchase-request'); ?>">Purchase Requests</a></div>
                <div><a href="<?= base_url('inventory'); ?>">Inventory</a></div>
                <div><a href="<?= base_url('deliveries'); ?>">Deliveries</a></div>
                <div><a href="<?= base_url('transfer'); ?>">Transfers</a></div>
                <div><a href="<?= base_url('settings'); ?>">Settings</a></div>
                <div><a href="<?= base_url('logout'); ?>" onclick="return confirm('Are you sure you want to logout?')">Logout</a></div>
                <?php
            } else {
                // Full menu for other roles (Central Office Admin, System Admin, etc.)
                ?>
                <div><a href="<?= base_url('dashboard'); ?>">Dashboard</a></div>
                <div><a href="<?= base_url('purchase-request'); ?>">Purchase Requests</a></div>
                <div><a href="<?= base_url('purchase-orders'); ?>">Purchase Orders</a></div>
                <div><a href="<?= base_url('deliveries'); ?>">Deliveries</a></div>
                <div><a href="<?= base_url('inventory'); ?>">Inventory</a></div>
                <div><a href="<?= base_url('suppliers'); ?>">Suppliers</a></div>
                <div><a href="<?= base_url('transfer'); ?>">Transfers</a></div>
                <div><a href="<?= base_url('franchise'); ?>">Franchise</a></div>
                <?php if ($user['role'] === 'System Administrator'): ?>
                    <div><a href="<?= base_url('users'); ?>">User Management</a></div>
                <?php endif; ?>
                <div><a href="<?= base_url('settings'); ?>">Settings</a></div>
                <div><a href="<?= base_url('logout'); ?>" onclick="return confirm('Are you sure you want to logout?')">Logout</a></div>
                <?php
            }
        }
        ?>
      </nav>
    </aside>
