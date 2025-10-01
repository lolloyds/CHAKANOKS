    <aside>
      <?php $user = session()->get('user'); ?>
      <?php if ($user && in_array($user['role'], ['Inventory Staff', 'Branch Manager'])): ?>
      <!-- Branch User Navigation -->
      <nav>
        <div><a href="<?= base_url('dashboard'); ?>">Dashboard</a></div>
        <div><a href="<?= base_url('bpurchaserequest'); ?>">Purchase Requests</a></div>
        <div><a href="<?= base_url('bdeliveries'); ?>">Deliveries</a></div>
        <div><a href="<?= base_url('binventory'); ?>">Inventory</a></div>
        <div><a href="<?= base_url('btransfer'); ?>">Transfers</a></div>
        <div><a href="<?= base_url('bsettings'); ?>">Settings</a></div>
        <div><a href="<?= base_url('login'); ?>">Logout</a></div>
      </nav>
      <?php else: ?>
      <!-- Central Office Navigation -->
      <nav>
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
      </nav>
      <?php endif; ?>
    </aside>
