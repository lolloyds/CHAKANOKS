<h2>Schedule a Delivery</h2>
<form method="post" action="/deliveries/create">
    <label for="supplier_id">Supplier:</label>
    <select name="supplier_id" required>
        <option value="">Select Supplier</option>
        <?php foreach ($suppliers as $supplier): ?>
            <option value="<?= $supplier['id'] ?>"><?= $supplier['name'] ?></option>
        <?php endforeach; ?>
    </select><br>
    <label for="item_name">Item Name:</label>
    <input type="text" name="item_name" required><br>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" required><br>
    <label for="delivery_date">Delivery Date:</label>
    <input type="date" name="delivery_date" required><br>
    <button type="submit">Schedule Delivery</button>
</form>
