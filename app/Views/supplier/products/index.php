<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>

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
    text-decoration: none;
    display: inline-block;
  }

  .btn-add:hover {
    background: linear-gradient(135deg, #ff1493 0%, #dc143c 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(255, 105, 180, 0.4);
  }

  .products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
    margin-top: 20px;
  }

  .product-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #ffd6e8;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
  }

  .product-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
  }

  .product-name {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0;
  }

  .product-category {
    background: #ff69b4;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
  }

  .product-price {
    font-size: 24px;
    font-weight: 700;
    color: #ff1493;
    margin: 10px 0;
  }

  .product-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin: 15px 0;
    font-size: 14px;
  }

  .detail-item {
    display: flex;
    flex-direction: column;
  }

  .detail-label {
    font-weight: 600;
    color: #666;
    font-size: 12px;
    text-transform: uppercase;
  }

  .detail-value {
    color: #333;
    margin-top: 2px;
  }

  .status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
  }

  .status-available {
    background: #d4edda;
    color: #155724;
  }

  .status-out_of_stock {
    background: #f8d7da;
    color: #721c24;
  }

  .status-discontinued {
    background: #d1ecf1;
    color: #0c5460;
  }

  .product-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #ffd6e8;
  }

  .btn-edit, .btn-delete {
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.2s ease;
  }

  .btn-edit {
    background: #28a745;
    color: white;
  }

  .btn-edit:hover {
    background: #218838;
  }

  .btn-delete {
    background: #dc3545;
    color: white;
  }

  .btn-delete:hover {
    background: #c82333;
  }

  .empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #666;
  }

  .empty-state i {
    font-size: 64px;
    color: #ffd6e8;
    margin-bottom: 20px;
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
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2>🛍️ My Products</h2>
                <p style="color: #666; line-height: 1.6; margin: 0;">
                    Manage your product catalog and pricing.
                </p>
            </div>
            <a href="<?= base_url('supplier/products/create') ?>" class="btn-add">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="box">
        <?php if (empty($products)): ?>
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>No Products Added Yet</h3>
                <p>Start building your product catalog by adding your first product.</p>
                <a href="<?= base_url('supplier/products/create') ?>" class="btn-add">
                    <i class="fas fa-plus"></i> Add Your First Product
                </a>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-header">
                            <h3 class="product-name"><?= esc($product['item_name']) ?></h3>
                            <span class="product-category"><?= esc($product['category']) ?></span>
                        </div>
                        
                        <div class="product-price">
                            ₱<?= number_format($product['price_per_unit'], 2) ?>
                            <span style="font-size: 14px; font-weight: normal; color: #666;">
                                per <?= esc($product['unit']) ?>
                            </span>
                        </div>



                        <div class="product-details">
                            <div class="detail-item">
                                <span class="detail-label">Min Order</span>
                                <span class="detail-value"><?= $product['minimum_order'] ?> <?= esc($product['unit']) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Lead Time</span>
                                <span class="detail-value"><?= $product['lead_time_days'] ?> day(s)</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Status</span>
                                <span class="status-badge status-<?= $product['availability_status'] ?>">
                                    <?= ucwords(str_replace('_', ' ', $product['availability_status'])) ?>
                                </span>
                            </div>
                        </div>

                        <?php if ($product['notes']): ?>
                            <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 6px; font-size: 14px;">
                                <strong>Notes:</strong> <?= esc($product['notes']) ?>
                            </div>
                        <?php endif; ?>

                        <div class="product-actions">
                            <a href="<?= base_url('supplier/products/edit/' . $product['id']) ?>" class="btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button onclick="confirmDelete(<?= $product['id'] ?>, '<?= esc($product['item_name']) ?>')" class="btn-delete">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
function confirmDelete(productId, productName) {
    if (confirm(`Are you sure you want to remove "${productName}" from your product catalog?`)) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= base_url('supplier/products/delete/') ?>${productId}`;
        
        // Add CSRF token if needed
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_method';
        csrfInput.value = 'DELETE';
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>