<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar.php'; ?>

<style>
  body {
    background: #ffeef5;
    font-family: "Segoe UI", Arial, sans-serif;
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

  h2 {
    font-size: 24px;
    margin-top: 0;
    margin-bottom: 10px;
    color: #333;
    font-weight: 600;
  }

  h3 {
    font-size: 18px;
    margin-top: 0;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ffd6e8;
    color: #333;
    font-weight: 600;
  }

  .desc {
    color: #666;
    line-height: 1.6;
    margin: 0;
  }

  .grid {
    display: grid;
    gap: 20px;
  }

  .grid-2 {
    grid-template-columns: repeat(2, 1fr);
  }

  @media (max-width: 768px) {
    .grid-2 {
      grid-template-columns: 1fr;
    }
  }

  .form-group {
    margin-bottom: 18px;
  }

  .form-group label {
    display: block;
    margin-bottom: 8px;
    color: #555;
    font-weight: 500;
    font-size: 14px;
  }

  .form-group input,
  .form-group select {
    width: 100%;
    padding: 12px 14px;
    border: 2px solid #ffd6e8;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    box-sizing: border-box;
    background: #fff;
  }

  .form-group input:focus,
  .form-group select:focus {
    outline: none;
    border-color: #ff69b4;
    box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
  }

  .btn-primary {
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

  .btn-primary:hover {
    background: linear-gradient(135deg, #ff1493 0%, #dc143c 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(255, 105, 180, 0.4);
  }

  .btn-primary:active {
    transform: translateY(0);
  }

  .btn-ghost {
    background: #fff;
    color: #ff69b4;
    padding: 12px 24px;
    border: 2px solid #ffd6e8;
    border-radius: 8px;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .btn-ghost:hover {
    background: #fff0f5;
    border-color: #ff69b4;
    transform: translateY(-2px);
  }

  .btn-ghost:active {
    transform: translateY(0);
  }

  #message {
    padding: 14px 18px;
    border-radius: 8px;
    font-weight: 500;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    display: none;
  }

  #message.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
  }

  #message.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
  }
</style>

<main>
  <div class="box">
    <h2>⚙️ Settings</h2>
    <div class="desc">Manage account preferences, security, and notifications for your CHAKANOKS account.</div>
  </div>

  <div id="message"></div>

  <div class="grid grid-2">
    <div class="box">
      <h3>👤 Profile</h3>
      <form id="profileForm">
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" id="name" name="name" placeholder="Enter your full name" value="<?= esc(session()->get('user')['username'] ?? '') ?>">
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="you@company.com">
        </div>

        <div class="form-group">
          <label for="phone">Phone (optional)</label>
          <input type="text" id="phone" name="phone" placeholder="09xx-xxx-xxxx">
        </div>

        <div style="display:flex;gap:10px;margin-top:10px;">
          <button type="submit" class="btn-primary">💾 Save Profile</button>
          <button type="button" class="btn-ghost" onclick="enableEdit('profileForm')">✏️ Edit</button>
        </div>
      </form>
    </div>

    <div class="box">
      <h3>🔒 Security</h3>
      <form id="securityForm">
        <div class="form-group">
          <label for="current">Current Password</label>
          <input type="password" id="current" name="current_password" placeholder="••••••••" required>
        </div>
        <div class="form-group">
          <label for="new">New Password</label>
          <input type="password" id="new" name="new_password" placeholder="Choose a strong password" required>
        </div>
        <div class="form-group">
          <label for="confirm">Confirm Password</label>
          <input type="password" id="confirm" name="confirm_password" placeholder="Repeat new password" required>
        </div>

        <div style="display:flex;gap:10px;margin-top:10px;">
          <button type="submit" class="btn-primary">🔐 Update Password</button>
          <button type="button" class="btn-ghost" onclick="clearSecurityForm()">❌ Cancel</button>
        </div>
      </form>
    </div>

    <div class="box">
      <h3>🔔 Notifications</h3>
      <form id="notificationsForm">
        <div class="form-group">
          <label for="notif">Email Notifications</label>
          <select id="notif" name="email_notifications">
            <option value="enabled">Enabled</option>
            <option value="disabled">Disabled</option>
          </select>
        </div>

        <div class="form-group">
          <label for="sms">SMS Notifications</label>
          <select id="sms" name="sms_notifications">
            <option value="enabled">Enabled</option>
            <option value="disabled">Disabled</option>
          </select>
        </div>

        <div class="form-group">
          <label for="theme">Theme</label>
          <select id="theme" name="theme">
            <option value="light">Light</option>
            <option value="dark">Dark</option>
          </select>
        </div>

        <div style="display:flex;gap:10px;margin-top:10px;">
          <button type="submit" class="btn-primary">💾 Save Preferences</button>
          <button type="button" class="btn-ghost" onclick="previewTheme()">🖥️ Preview</button>
        </div>
      </form>
    </div>

    <div class="box">
      <h3>🧾 Account</h3>
      <p class="desc">Manage account-level settings such as role, branch association, and logout.</p>

      <div style="display:flex;gap:10px;margin-top:8px;align-items:center;">
        <form method="post" action="<?= base_url('logout') ?>" onsubmit="return confirm('Are you sure you want to logout?');" style="margin:0;">
          <button type="submit" class="btn-ghost">🚪 Logout</button>
        </form>

        <button type="button" class="btn-ghost" onclick="showAccountInfo()">📝 Edit Account</button>
      </div>
    </div>
  </div>
</main>

<script>
  function showMessage(message, type = 'success') {
    const messageDiv = document.getElementById('message');
    messageDiv.textContent = message;
    messageDiv.className = type;
    messageDiv.style.display = 'block';
    setTimeout(() => {
      messageDiv.style.display = 'none';
    }, 5000);
  }

  function enableEdit(formId) {
    const form = document.getElementById(formId);
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
      input.disabled = false;
      input.style.opacity = '1';
    });
  }

  function clearSecurityForm() {
    document.getElementById('securityForm').reset();
  }

  function previewTheme() {
    const theme = document.getElementById('theme').value;
    alert('Theme preview: ' + theme + ' mode. This feature will be implemented soon.');
  }

  function showAccountInfo() {
    const user = <?= json_encode(session()->get('user') ?? []) ?>;
    alert('Account Information:\n\nUsername: ' + (user.username || 'N/A') + '\nRole: ' + (user.role || 'N/A') + '\nBranch ID: ' + (user.branch_id || 'N/A'));
  }

  // Profile Form Handler
  document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('<?= base_url('settings/update-profile') ?>', {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showMessage(data.message || 'Profile updated successfully!', 'success');
      } else {
        showMessage(data.message || 'Error updating profile', 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showMessage('Network error. Please try again.', 'error');
    });
  });

  // Security Form Handler
  document.getElementById('securityForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const newPassword = formData.get('new_password');
    const confirmPassword = formData.get('confirm_password');

    if (newPassword !== confirmPassword) {
      showMessage('New password and confirm password do not match!', 'error');
      return;
    }

    if (newPassword.length < 6) {
      showMessage('Password must be at least 6 characters long!', 'error');
      return;
    }

    fetch('<?= base_url('settings/update-password') ?>', {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showMessage(data.message || 'Password updated successfully!', 'success');
        this.reset();
      } else {
        showMessage(data.message || 'Error updating password', 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showMessage('Network error. Please try again.', 'error');
    });
  });

  // Notifications Form Handler
  document.getElementById('notificationsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('<?= base_url('settings/update-notifications') ?>', {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showMessage(data.message || 'Preferences saved successfully!', 'success');
      } else {
        showMessage(data.message || 'Error saving preferences', 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showMessage('Network error. Please try again.', 'error');
    });
  });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

