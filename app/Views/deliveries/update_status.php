<h2>Update Delivery Status</h2>
<?php if ($delivery): ?>
<form method="post" action="/deliveries/update-status/<?= $delivery['id'] ?>">
    <p>Delivery ID: <?= $delivery['id'] ?></p>
    <p>Current Status: <?= $delivery['status'] ?></p>
    <button type="submit">Set to In Transit</button>
</form>
<?php if ($delivery['status'] === 'in_transit'): ?>
    <a href="/deliveries/mark-delivered/<?= $delivery['id'] ?>" class="btn btn-success">Mark as Delivered</a>
<?php endif; ?>
<?php else: ?>
<p>Delivery not found.</p>
<?php endif; ?>
