<?php include __DIR__ . '/includes/header.php'; ?>

<main>
	<style>
		.low-stock { background: #fff8e1; }
		.critical-stock { background: #ffebee; }
		.table-scroll { max-height: 60vh; overflow:auto; }
		.barcode { display:inline-block; }
		.muted { color:#666; font-size: 13px; }
	</style>

	<div style="text-align:right; margin-bottom: 10px;">
		<a href="<?= base_url('logout'); ?>" style="color:#d35400; text-decoration:none; font-weight:600;">Logout</a>
	</div>

	<div class="box">
		<h2>🏬 Branch Manager</h2>
		<div class="desc">Monitor branch inventory in real-time, adjust quantities, and review alerts.</div>
	</div>

	<div class="box">
		<div class="stats">
			<div class="stat">Total SKUs<br><b id="stat-total-skus">-</b></div>
			<div class="stat">Total Units<br><b id="stat-total-units">-</b></div>
			<div class="stat">Low Stock<br><b id="stat-low">-</b></div>
			<div class="stat">Critical<br><b id="stat-critical">-</b></div>
		</div>
	</div>

	<div class="box">
		<h3>🔔 Alerts</h3>
		<div id="alerts"></div>
	</div>

	<div class="box">
		<h3>📋 Inventory</h3>
		<input type="search" id="search" placeholder="Search by name or SKU" />
		<div class="table-scroll">
			<table class="table">
				<thead>
					<tr>
						<th>SKU</th>
						<th>Name</th>
						<th>Qty</th>
						<th>Min</th>
						<th>Barcode</th>
						<th style="width:320px;">Adjust</th>
					</tr>
				</thead>
				<tbody id="itemsBody"></tbody>
			</table>
		</div>
	</div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
	const itemsBody = document.getElementById('itemsBody');
	const searchInput = document.getElementById('search');

	async function fetchItems() {
		const res = await fetch('<?= base_url('api/inventory') ?>');
		const data = await res.json();
		return data.items || [];
	}

	function rowClass(item) {
		if (item.quantity <= 0 || item.quantity < item.min_quantity / 2) return 'critical-stock';
		if (item.quantity <= item.min_quantity) return 'low-stock';
		return '';
	}

	function computeStats(items) {
		let totalSkus = items.length;
		let totalUnits = 0;
		let low = 0;
		let critical = 0;
		for (const it of items) {
			totalUnits += Number(it.quantity) || 0;
			if (it.quantity <= 0 || it.quantity < it.min_quantity / 2) critical++;
			else if (it.quantity <= it.min_quantity) low++;
		}
		return { totalSkus, totalUnits, low, critical };
	}

	function renderStats(stats) {
		document.getElementById('stat-total-skus').textContent = stats.totalSkus;
		document.getElementById('stat-total-units').textContent = stats.totalUnits;
		document.getElementById('stat-low').textContent = stats.low;
		document.getElementById('stat-critical').textContent = stats.critical;
	}

	function renderAlerts(items) {
		const alerts = document.getElementById('alerts');
		const atRisk = items.filter(it => it.quantity <= it.min_quantity).sort((a,b)=>a.quantity-b.quantity);
		if (atRisk.length === 0) {
			alerts.innerHTML = '<p>No low stock alerts.</p>';
			return;
		}
		const rows = atRisk.map(it => `<div class="row ${rowClass(it)}" style="padding:6px 0;">
			<div class="col-sm-4"><strong>${it.sku}</strong></div>
			<div class="col-sm-6">${it.name}</div>
			<div class="col-sm-2">${it.quantity} / ${it.min_quantity}</div>
		</div>`).join('');
		alerts.innerHTML = rows;
	}

	function render(items) {
		const q = (searchInput.value || '').toLowerCase();
		const filtered = items.filter(it => it.name.toLowerCase().includes(q) || it.sku.toLowerCase().includes(q));
		itemsBody.innerHTML = '';
		for (const it of filtered) {
			const tr = document.createElement('tr');
			tr.className = rowClass(it);
			tr.innerHTML = `
				<td><strong>${it.sku}</strong></td>
				<td>${it.name}</td>
				<td><span id="qty-${it.id}">${it.quantity}</span></td>
				<td>${it.min_quantity}</td>
				<td><svg id="barcode-${it.id}" class="barcode"></svg></td>
				<td>
					<div class="row">
						<div><input type="number" id="chg-${it.id}" placeholder="e.g. 5 or -2" /></div>
						<div><input type="text" id="rsn-${it.id}" placeholder="Reason (optional)" /></div>
						<div><button data-id="${it.id}">Apply</button></div>
					</div>
				</td>
			`;
			itemsBody.appendChild(tr);
			JsBarcode(`#barcode-${it.id}`, it.sku, {format: 'CODE128', width: 2, height: 40, displayValue: false});
		}
	}

	async function applyChange(id) {
		const changeInput = document.getElementById(`chg-${id}`);
		const reasonInput = document.getElementById(`rsn-${id}`);
		const change = parseInt(changeInput.value || '0', 10);
		const reason = reasonInput.value || '';
		if (!change) return;
		const formData = new FormData();
		formData.append('change', change);
		formData.append('reason', reason);
		const res = await fetch('<?= base_url('api/inventory/update/') ?>' + id, { method: 'POST', body: formData });
		const data = await res.json();
		if (data && data.success) {
			document.getElementById(`qty-${id}`).textContent = data.quantity;
			changeInput.value = '';
			reasonInput.value = '';
		}
	}

	itemsBody.addEventListener('click', (e) => {
		if (e.target.tagName === 'BUTTON') {
			applyChange(e.target.getAttribute('data-id'));
		}
	});

	async function refresh() {
		const items = await fetchItems();
		render(items);
		renderStats(computeStats(items));
		renderAlerts(items);
	}

	setInterval(refresh, 5000);
	searchInput.addEventListener('input', refresh);
	refresh();
</script>


