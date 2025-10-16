<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

  <main>
    <!-- Page Title -->
    <div class="page-header">
      <h1>CHAKANOKS</h1>
    </div>

    <h2>Deliveries</h2>

    <?php if (!$isBranchUser): ?>
    <div class="stats">
      <div class="stat">📦 Scheduled Today: <?= $scheduled ?? 0 ?></div>
      <div class="stat">🚚 In Transit: <?= $inTransit ?? 0 ?></div>
      <div class="stat">✅ Delivered: <?= $delivered ?? 0 ?></div>
    </div>
    <?php endif; ?>

    <div class="box">
      <h3><?php echo ($isBranchUser ? 'Your Branch Deliveries' : 'Upcoming Deliveries'); ?></h3>
      <table class="table">
        <tr>
          <th>Delivery ID</th>
          <th><?php echo ($isBranchUser ? 'Branch Location' : 'Destination Branch'); ?></th>
          <th>Items</th>
          <th>Driver</th>
          <th>Status</th>
          <?php if ($isBranchUser && $userRole === 'Inventory Staff'): ?>
            <th>Action</th>
          <?php endif; ?>
        </tr>
        <?php if (!empty($deliveries)): ?>
          <?php foreach ($deliveries as $delivery): ?>
            <tr>
              <td><?php echo htmlspecialchars($delivery['delivery_id'] ?? $delivery['id']); ?></td>
              <td><?php echo htmlspecialchars($delivery['branch_name']); ?></td>
              <td><?php echo htmlspecialchars($delivery['items']); ?></td>
              <td><?php echo htmlspecialchars($delivery['driver']); ?></td>
              <td><?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($delivery['status']))); ?> (Role: <?php echo $userRole; ?>)</td>
              <?php if ($isBranchUser && $userRole === 'Inventory Staff' && isset($delivery['can_approve']) && $delivery['can_approve'] && $delivery['status'] === 'delivered'): ?>
                <td>
                  <button onclick="receiveDelivery('<?php echo htmlspecialchars($delivery['delivery_id']); ?>', <?php echo $delivery['branch_id']; ?>)"
                          class="btn-action">Receive</button>
                </td>
              <?php elseif ($isBranchUser && $userRole === 'Inventory Staff'): ?>
                <td><?php echo $delivery['status'] === 'received' ? 'Already Received' : ($delivery['status'] === 'delivered' ? 'Ready to Receive' : 'Not Delivered'); ?></td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
            <tr>
              <td colspan="<?php echo ($isBranchUser ? '6' : '5'); ?>" class="text-center">
                No deliveries found at this time.
              </td>
            </tr>
        <?php endif; ?>
      </table>
    </div>

    <script>
      function receiveDelivery(deliveryId, branchId) {
        if (confirm('Confirm receipt of delivery? This will add all items to your branch inventory.')) {
          fetch('<?php echo base_url('deliveries/approve'); ?>', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
              delivery_id: deliveryId,
              branch_id: branchId
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Delivery received! Items added to inventory.');
              location.reload();
            } else {
              alert(data.message || 'Error receiving delivery');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
          });
        }
      }
    </script>
  </main>

<?php include __DIR__ . '/includes/footer.php'; ?>
