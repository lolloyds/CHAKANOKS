<h2>All Deliveries</h2>
<a href="/deliveries/create" class="btn btn-primary" style="margin-bottom:20px;">Create New Delivery</a>
<table border="1" cellpadding="8" style="width:100%;border-collapse:collapse;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Supplier</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Delivery Date</th>
            <th>Status</th>
            <th>Branch</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($deliveries as $delivery): ?>
        <tr>
            <td><?= esc($delivery['id']) ?></td>
            <td><?= esc($delivery['supplier_id']) ?></td>
            <td><?= esc($delivery['item_name']) ?></td>
            <td><?= esc($delivery['quantity']) ?></td>
            <td><?= esc($delivery['delivery_date']) ?></td>
            <td><?= esc($delivery['status']) ?></td>
            <td><?= esc($delivery['branch_id'] ?? 'N/A') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
