<h2>Edit Item</h2>
<form method="post" action="/inventory/edit/<?= $item['id'] ?>">
    <label for="item_name">Item Name:</label>
    <input type="text" name="item_name" value="<?= $item['item_name'] ?>" required><br>
    <label for="category">Category:</label>
    <input type="text" name="category" value="<?= $item['category'] ?>" required><br>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" required><br>
    <label for="unit">Unit:</label>
    <input type="text" name="unit" value="<?= $item['unit'] ?>" required><br>
    <label for="expiry_date">Expiry Date:</label>
    <input type="date" name="expiry_date" value="<?= $item['expiry_date'] ?>"><br>
    <button type="submit">Update Item</button>
</form>
<a href="/inventory">Back to Inventory</a>
