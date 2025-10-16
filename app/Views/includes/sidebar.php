    <aside>
      <nav>
        <?php
        $user = session()->get('user');
        if ($user && in_array($user['role'], ['Branch Manager', 'Inventory Staff'])) {
            // Limited menu for Branch Manager and Inventory Staff
            ?>
            <div><a href="<?= base_url('dashboard'); ?>">Dashboard</a></div>
            <div><a href="<?= base_url('purchase-request'); ?>">Purchase Requests</a></div>
            <div><a href="<?= base_url('inventory'); ?>">Inventory</a></div>
            <div><a href="<?= base_url('deliveries'); ?>">Deliveries</a></div>
            <div><a href="<?= base_url('transfer'); ?>">Transfers</a></div>
            <div><a href="<?= base_url('settings'); ?>">Settings</a></div>
            <div><a href="<?= base_url('login'); ?>">Logout</a></div>
            <?php
        } else {
            // Full menu for other roles (Central Office Admin, Supplier, etc.)
            ?>
            <div><a href="<?= base_url('dashboard'); ?>">Dashboard</a></div>
            <div><a href="<?= base_url('purchase-request'); ?>">Purchase Requests</a></div>
            <div><a href="<?= base_url('purchase-orders'); ?>">Purchase Orders</a></div>
            <div><a href="<?= base_url('deliveries'); ?>">Deliveries</a></div>
            <div><a href="<?= base_url('inventory'); ?>">Inventory</a></div>
            <div><a href="<?= base_url('suppliers'); ?>">Suppliers</a></div>
            <div><a href="<?= base_url('transfer'); ?>">Transfers</a></div>
            <div><a href="<?= base_url('franchise'); ?>">Franchise</a></div>
            <div><a href="<?= base_url('settings'); ?>">Settings</a></div>
            <div><a href="<?= base_url('login'); ?>">Logout</a></div>
            <?php
        }
        ?>
      </nav>
    </aside>
