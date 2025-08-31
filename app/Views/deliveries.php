<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

  <main>
    <!-- Page Title -->
    <div class="page-header">
      <h1>CHAKANOKS</h1>
    </div>

    <h2>Deliveries</h2>
    <div class="desc">
      This section tracks all product deliveries from the central kitchen and suppliers 
      to different Chakanoks branches. It helps monitor schedules, routes, and statuses 
      to ensure fresh roasted chicken and supplies arrive on time.
    </div>

    <div class="stats">
      <div class="stat">📦 Scheduled Today: 8</div>
      <div class="stat">🚚 In Transit: 3</div>
      <div class="stat">✅ Delivered: 15</div>
    </div>

    <div class="box">
      <h3>Upcoming Deliveries</h3>
      <table class="table">
        <tr>
          <th>Delivery ID</th>
          <th>Destination Branch</th>
          <th>Items</th>
          <th>Driver</th>
          <th>Status</th>
        </tr>
        <tr>
          <td>DLV-101</td>
          <td>Chakanoks Davao - Bajada</td>
          <td>50 Roasted Chickens, 30kg Rice</td>
          <td>Juan Dela Cruz</td>
          <td>Scheduled</td>
        </tr>
        <tr>
          <td>DLV-102</td>
          <td>Chakanoks Davao - Matina</td>
          <td>25 Roasted Chickens, 10kg Veggies</td>
          <td>Pedro Santos</td>
          <td>In Transit</td>
        </tr>
        <tr>
          <td>DLV-103</td>
          <td>Chakanoks Davao - Toril</td>
          <td>40 Roasted Chickens, 20kg Rice</td>
          <td>Carlos Reyes</td>
          <td>Delivered</td>
        </tr>
      </table>
    </div>
  </main>

<?php include __DIR__ . '/includes/footer.php'; ?>
