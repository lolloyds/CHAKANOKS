<?php

/**
 * Database Setup Script
 * 
 * This script runs all migrations and seeders to set up your database.
 * 
 * IMPORTANT: Delete this file after setup for security reasons.
 */

// Bootstrap CodeIgniter
require_once __DIR__ . '/vendor/autoload.php';

// Path to CodeIgniter's index.php bootstrap
define('ROOTPATH', __DIR__);
define('ENVIRONMENT', getenv('CI_ENVIRONMENT') ?: 'development');

// Load CodeIgniter
$bootstrap = require_once ROOTPATH . '/vendor/codeigniter4/framework/system/bootstrap.php';
$app = \Config\Services::codeigniter();
$app->initialize();
$context = is_cli() ? 'php-cli' : 'web';
$app->setContext($context);

$db = \Config\Database::connect();
$output = [];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Setup</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .section { margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 4px; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .info { color: #17a2b8; }
        pre { background: #fff; padding: 10px; border: 1px solid #ddd; border-radius: 4px; overflow-x: auto; max-height: 400px; overflow-y: auto; }
        .warning { background: #fff3cd; border: 1px solid #ffc107; padding: 10px; border-radius: 4px; margin: 10px 0; }
        button { background: #007bff; color: white; border: none; padding: 12px 24px; border-radius: 4px; cursor: pointer; font-size: 16px; margin: 10px 5px; }
        button:hover { background: #0056b3; }
        .step { margin: 20px 0; padding: 15px; border-left: 4px solid #007bff; background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Setup & Migration</h1>
        
        <?php
        $run = $_GET['run'] ?? '';
        
        if ($run === 'migrate') {
            echo "<div class='section'>";
            echo "<h2>Running Migrations...</h2>";
            
            try {
                $migrate = \Config\Services::migrations();
                
                // Check if migrations table exists, if not, ensureTable will create it
                $migrate->ensureTable();
                
                // Run all migrations
                $result = $migrate->latest();
                
                if ($result) {
                    echo "<p class='success'>✅ All migrations completed successfully!</p>";
                    
                    // Get migration history
                    $history = $migrate->getHistory();
                    if (!empty($history)) {
                        echo "<p>Migrations run:</p><ul>";
                        foreach ($history as $migration) {
                            echo "<li>" . htmlspecialchars($migration->version . ' - ' . $migration->class) . "</li>";
                        }
                        echo "</ul>";
                    }
                } else {
                    echo "<p class='error'>❌ Migrations failed. Check the errors above.</p>";
                }
            } catch (\Exception $e) {
                echo "<p class='error'>❌ Error running migrations: " . htmlspecialchars($e->getMessage()) . "</p>";
                echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            }
            
            echo "</div>";
            echo "<div class='section'>";
            echo "<h3>Next Step:</h3>";
            echo "<p><a href='?run=seed'><button>Run Seeders Now</button></a></p>";
            echo "</div>";
            
        } elseif ($run === 'seed') {
            echo "<div class='section'>";
            echo "<h2>Running Seeders...</h2>";
            
            try {
                $seeder = \Config\Database::seeder();
                
                // Run DatabaseSeeder which runs all seeders
                $seeder->call('DatabaseSeeder');
                
                echo "<p class='success'>✅ All seeders completed successfully!</p>";
                
                // Verify users were created
                $users = $db->table('users')->countAllResults();
                if ($users > 0) {
                    echo "<p class='success'>✅ Created $users user(s) in the database</p>";
                    
                    // Show usernames
                    $userList = $db->table('users')->select('username, role')->get()->getResultArray();
                    echo "<p class='info'>Available accounts:</p><ul>";
                    foreach ($userList as $user) {
                        echo "<li><strong>" . htmlspecialchars($user['username']) . "</strong> (" . htmlspecialchars($user['role']) . ")</li>";
                    }
                    echo "</ul>";
                    echo "<p class='info'><strong>Default password for all accounts:</strong> <code>password</code></p>";
                }
                
            } catch (\Exception $e) {
                echo "<p class='error'>❌ Error running seeders: " . htmlspecialchars($e->getMessage()) . "</p>";
                echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            }
            
            echo "</div>";
            echo "<div class='section'>";
            echo "<h3>Setup Complete!</h3>";
            echo "<p>You can now <a href='" . base_url('login') . "'>login to the application</a></p>";
            echo "</div>";
            
        } elseif ($run === 'check') {
            echo "<div class='section'>";
            echo "<h2>Database Status Check</h2>";
            
            // Check tables
            $tables = ['branches', 'users', 'items', 'branch_stock', 'stock_movements', 'deliveries', 'delivery_items'];
            $allExist = true;
            
            foreach ($tables as $table) {
                if ($db->tableExists($table)) {
                    $count = $db->table($table)->countAllResults();
                    echo "<p class='success'>✅ Table '$table' exists ($count rows)</p>";
                } else {
                    echo "<p class='error'>❌ Table '$table' does not exist</p>";
                    $allExist = false;
                }
            }
            
            if ($allExist) {
                // Check users
                $userCount = $db->table('users')->countAllResults();
                if ($userCount > 0) {
                    echo "<p class='success'>✅ Database is fully set up with $userCount user(s)</p>";
                } else {
                    echo "<p class='warning'>⚠️ Tables exist but no users found. Run seeders.</p>";
                }
            } else {
                echo "<p class='warning'>⚠️ Some tables are missing. Run migrations first.</p>";
            }
            
            echo "</div>";
            
        } else {
            // Show initial setup interface
            ?>
            <div class="section">
                <h2>Setup Instructions</h2>
                <p>This script will help you set up your database by:</p>
                <ol>
                    <li>Running all migrations to create database tables</li>
                    <li>Running seeders to populate initial data (users, branches, items, etc.)</li>
                </ol>
            </div>

            <div class="section">
                <h2>Step 1: Check Current Status</h2>
                <p>First, let's check what's already set up:</p>
                <a href="?run=check"><button>Check Database Status</button></a>
            </div>

            <div class="section">
                <h2>Step 2: Run Migrations</h2>
                <p>This will create all necessary database tables:</p>
                <a href="?run=migrate"><button>Run All Migrations</button></a>
            </div>

            <div class="section">
                <h2>Step 3: Run Seeders</h2>
                <p>This will populate the database with initial data including user accounts:</p>
                <a href="?run=seed"><button>Run All Seeders</button></a>
            </div>

            <div class="warning">
                <strong>Alternative Method (Command Line):</strong><br>
                If you prefer using command line, open terminal in the project directory and run:
                <pre>php spark migrate
php spark db:seed DatabaseSeeder</pre>
            </div>
            <?php
        }
        ?>

        <div class="section" style="margin-top: 30px;">
            <h3>After Setup:</h3>
            <ul>
                <li>Login with username: <strong>sysadmin</strong> and password: <strong>password</strong></li>
                <li>Or use any other account from the list above</li>
                <li><strong>Delete this setup file (setup_database.php) after setup for security</strong></li>
            </ul>
        </div>
    </div>
</body>
</html>

