<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

  <main>
    <h2>Settings</h2>
    <div class="desc">
      Manage your account preferences, security, and notifications.
    </div>

    <div class="settings-container">
      <div class="settings-box">
        <h3>Profile Settings</h3>
        <label for="name">Full Name</label>
        <input type="text" id="name" placeholder="Enter your name">
        
        <label for="email">Email Address</label>
        <input type="email" id="email" placeholder="Enter your email">

        <button>Save Profile</button>
      </div>

      <div class="settings-box">
        <h3>Password Settings</h3>
        <label for="current">Current Password</label>
        <input type="password" id="current" placeholder="Enter current password">

        <label for="new">New Password</label>
        <input type="password" id="new" placeholder="Enter new password">

        <button>Update Password</button>
      </div>

      <div class="settings-box">
        <h3>Notifications</h3>
        <label for="notif">Email Notifications</label>
        <select id="notif">
          <option>Enabled</option>
          <option>Disabled</option>
        </select>

        <label for="theme">Theme</label>
        <select id="theme">
          <option>Light</option>
          <option>Dark</option>
        </select>

        <button>Save Preferences</button>
      </div>
    </div>
  </main>

<?php include __DIR__ . '/includes/footer.php'; ?>

