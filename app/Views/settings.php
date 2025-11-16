<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

  <main>
    <div class="box">
      <h2>⚙️ Settings</h2>
      <div class="desc">Manage account preferences, security, and notifications for your CHAKANOKS account.</div>
    </div>

    <div class="grid grid-2">
      <div class="box">
        <h3>👤 Profile</h3>
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" id="name" placeholder="Enter your full name">
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" placeholder="you@company.com">
        </div>

        <div class="form-group">
          <label for="phone">Phone (optional)</label>
          <input type="text" id="phone" placeholder="09xx-xxx-xxxx">
        </div>

        <div style="display:flex;gap:10px;margin-top:10px;">
          <button class="btn-primary">💾 Save Profile</button>
          <button class="btn-ghost">✏️ Edit</button>
        </div>
      </div>

      <div class="box">
        <h3>🔒 Security</h3>
        <div class="form-group">
          <label for="current">Current Password</label>
          <input type="password" id="current" placeholder="••••••••">
        </div>
        <div class="form-group">
          <label for="new">New Password</label>
          <input type="password" id="new" placeholder="Choose a strong password">
        </div>
        <div class="form-group">
          <label for="confirm">Confirm Password</label>
          <input type="password" id="confirm" placeholder="Repeat new password">
        </div>

        <div style="display:flex;gap:10px;margin-top:10px;">
          <button class="btn-primary">🔐 Update Password</button>
          <button class="btn-ghost">❌ Cancel</button>
        </div>
      </div>

      <div class="box">
        <h3>🔔 Notifications</h3>
        <div class="form-group">
          <label for="notif">Email Notifications</label>
          <select id="notif">
            <option>Enabled</option>
            <option>Disabled</option>
          </select>
        </div>

        <div class="form-group">
          <label for="sms">SMS Notifications</label>
          <select id="sms">
            <option>Enabled</option>
            <option>Disabled</option>
          </select>
        </div>

        <div class="form-group">
          <label for="theme">Theme</label>
          <select id="theme">
            <option>Light</option>
            <option>Dark</option>
          </select>
        </div>

        <div style="display:flex;gap:10px;margin-top:10px;">
          <button class="btn-primary">💾 Save Preferences</button>
          <button class="btn-ghost">🖥️ Preview</button>
        </div>
      </div>

      <div class="box">
        <h3>🧾 Account</h3>
        <p class="desc">Manage account-level settings such as role, branch association, and logout.</p>

        <div style="display:flex;gap:10px;margin-top:8px;align-items:center;">
          <form method="post" action="<?= base_url('logout') ?>" onsubmit="return confirm('Are you sure you want to logout?');">
            <button type="submit" class="btn-ghost">🚪 Logout</button>
          </form>

          <a href="<?= base_url('settings') ?>"><button class="btn-ghost">📝 Edit Account</button></a>
        </div>
      </div>
    </div>
  </main>

<?php include __DIR__ . '/includes/footer.php'; ?>

