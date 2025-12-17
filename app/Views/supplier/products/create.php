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

  .form-group {
    margin-bottom: 16px;
  }

  .form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #444;
    font-size: 14px;
  }

  .form-group input, .form-group select, .form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ffd6e8;
    border-radius: 6px;
    font-size: 14px;
    background: #fff;
    box-sizing: border-box;
    transition: border-color 0.3s, box-shadow 0.3s;
  }

  .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    outline: none;
    border-color: #ff69b4;
    box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
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
  }

  .btn-add:hover {
    background: linear-gradient(135deg, #ff1493 0%, #dc143c 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(255, 105, 180, 0.4);
  }

  .required { color: red; }

  .alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 14px;
  }

  .alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
  }

  .form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
  }

  .item-preview {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    margin-top: 10px;
  }

  .item-preview h4 {
    margin: 0 0 10px 0;
    color: #333;
  }

  .item-preview p {
    margin: 5px 0;
    color: #666;
    font-size: 14px;
  }
</style>

<main>
    <div class="box">
        <h2>➕ Add Product</h2>
        <p style="color: #666; line-height: 1.6; margin: 0;">
            Add a new product to your catalog with pricing and availability details.
        </p>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="box">
        <?php if (empty($availableItems)): ?>
            <div style="text-align: center; padding: 40px; color: #666;">
                <i class="fas fa-info-circle" style="font-size: 48px; color: #ffd6e8; margin-bottom: 20px;"></i>
                <h3>All Available Items Added</h3>
                <p>You have already added all available items to your product catalog.</p>
                <a href="<?= base_url('supplier/products') ?>" style="background: #6c757d; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> Back to Products
                </a>
            </div>
        <?php else: ?>
            <h3>📦 Product Information</h3>
            <form action="<?= base_url('supplier/products/store') ?>" method="post">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="item_id">
                            Select Item <span class="required">*</span>
                        </label>
                        <select id="item_id" name="item_id" required onchange="showItemPreview()">
                            <option value="">Choose an item...</option>
                            <?php 
                            $currentCategory = '';
                            foreach ($availableItems as $item): 
                                if ($currentCategory !== $item['category']):
                                    if ($currentCategory !== '') echo '</optgroup>';
                                    echo '<optgroup label="' . esc($item['category']) . '">';
                                    $currentCategory = $item['category'];
                                endif;
                            ?>
                                <option value="<?= $item['id'] ?>" 
                                        data-name="<?= esc($item['name']) ?>"
                                        data-unit="<?= esc($item['unit']) ?>"
                                        data-category="<?= esc($item['category']) ?>"
                                        <?= old('item_id') == $item['id'] ? 'selected' : '' ?>>
                                    <?= esc($item['name']) ?>
                                </option>
                            <?php endforeach; ?>
                            <?php if ($currentCategory !== '') echo '</optgroup>'; ?>
                        </select>
                        <div id="itemPreview" class="item-preview" style="display: none;">
                            <h4 id="previewName"></h4>
                            <p><strong>Category:</strong> <span id="previewCategory"></span></p>
                            <p><strong>Unit:</strong> <span id="previewUnit"></span></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="price_per_unit">
                            Price per Unit <span class="required">*</span>
                        </label>
                        <input type="number" id="price_per_unit" name="price_per_unit" 
                               step="0.01" min="0.01" 
                               value="<?= old('price_per_unit') ?>" required>
                        <small style="color: #666;">Price in Philippine Pesos (₱)</small>
                    </div>

                    <div class="form-group">
                        <label for="minimum_order">
                            Minimum Order Quantity <span class="required">*</span>
                        </label>
                        <input type="number" id="minimum_order" name="minimum_order" 
                               min="1" 
                               value="<?= old('minimum_order', 1) ?>" required>
                        <small style="color: #666;">Minimum quantity customers must order</small>
                    </div>

                    <div class="form-group">
                        <label for="availability_status">
                            Availability Status <span class="required">*</span>
                        </label>
                        <select id="availability_status" name="availability_status" required>
                            <option value="available" <?= old('availability_status') === 'available' ? 'selected' : '' ?>>Available</option>
                            <option value="out_of_stock" <?= old('availability_status') === 'out_of_stock' ? 'selected' : '' ?>>Out of Stock</option>
                            <option value="discontinued" <?= old('availability_status') === 'discontinued' ? 'selected' : '' ?>>Discontinued</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="lead_time_days">
                            Lead Time (Days) <span class="required">*</span>
                        </label>
                        <input type="number" id="lead_time_days" name="lead_time_days" 
                               min="1" 
                               value="<?= old('lead_time_days', 1) ?>" required>
                        <small style="color: #666;">How many days to fulfill orders</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">
                        Additional Notes
                    </label>
                    <textarea id="notes" name="notes" rows="3" 
                              placeholder="Any additional information about this product..."><?= old('notes') ?></textarea>
                    <small style="color: #666;">Optional notes about quality, packaging, etc.</small>
                </div>

                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ffd6e8; display: flex; gap: 10px; justify-content: flex-end;">
                    <a href="<?= base_url('supplier/products') ?>" style="background: #6c757d; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none;">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn-add">
                        <i class="fas fa-plus"></i> Add Product
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>

<script>
function showItemPreview() {
    const select = document.getElementById('item_id');
    const preview = document.getElementById('itemPreview');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        document.getElementById('previewName').textContent = selectedOption.dataset.name;
        document.getElementById('previewCategory').textContent = selectedOption.dataset.category;
        document.getElementById('previewUnit').textContent = selectedOption.dataset.unit;
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
}

// Initialize on page load if there's a selected value
document.addEventListener('DOMContentLoaded', function() {
    showItemPreview();
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>