<?php

/**
 * Database Connection Test Script
 * 
 * This script tests the database connection configuration.
 * Place this file in the root directory and access it via browser.
 * 
 * IMPORTANT: Delete this file after testing for security reasons.
 */

// Load CodeIgniter's database configuration
require_once __DIR__ . '/vendor/autoload.php';

// Get database config
$configPath = __DIR__ . '/app/Config/Database.php';
if (!file_exists($configPath)) {
    die("Error: Database.php config file not found!");
}

// Read and parse config
$configContent = file_get_contents($configPath);
preg_match("/'username'\s*=>\s*'([^']+)'/", $configContent, $usernameMatch);
preg_match("/'password'\s*=>\s*'([^']*)'/", $configContent, $passwordMatch);
preg_match("/'database'\s*=>\s*'([^']+)'/", $configContent, $databaseMatch);
preg_match("/'hostname'\s*=>\s*'([^']+)'/", $configContent, $hostnameMatch);

$hostname = $hostnameMatch[1] ?? 'localhost';
$username = $usernameMatch[1] ?? 'root';
$password = $passwordMatch[1] ?? '';
$database = $databaseMatch[1] ?? 'chakanoks_db';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Connection Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .section { margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 4px; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .info { color: #17a2b8; }
        pre { background: #fff; padding: 10px; border: 1px solid #ddd; border-radius: 4px; overflow-x: auto; }
        .warning { background: #fff3cd; border: 1px solid #ffc107; padding: 10px; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Connection Test</h1>
        
        <div class="section">
            <h2>Configuration Detected:</h2>
            <pre>
Hostname: <?= htmlspecialchars($hostname) ?>
Username: <?= htmlspecialchars($username) ?>
Password: <?= str_repeat('*', strlen($password)) ?> (<?= strlen($password) ?> characters)
Database: <?= htmlspecialchars($database) ?>
            </pre>
        </div>

        <div class="section">
            <h2>Test Results:</h2>
            <?php
            // Test 1: Connect to MySQL server
            echo "<h3>1. Testing MySQL Server Connection...</h3>";
            $mysqli = @new mysqli($hostname, $username, $password);
            
            if ($mysqli->connect_error) {
                echo "<p class='error'>❌ Failed to connect to MySQL server!</p>";
                echo "<p>Error: " . htmlspecialchars($mysqli->connect_error) . "</p>";
                echo "<div class='warning'>";
                echo "<strong>Possible Solutions:</strong><br>";
                echo "• Check if MySQL/XAMPP is running<br>";
                echo "• Verify the username and password in app/Config/Database.php<br>";
                echo "• Ensure MySQL allows connections from localhost<br>";
                echo "</div>";
            } else {
                echo "<p class='success'>✅ Successfully connected to MySQL server!</p>";
                echo "<p>MySQL Version: " . htmlspecialchars($mysqli->server_info) . "</p>";
                
                // Test 2: Check if database exists
                echo "<h3>2. Testing Database Existence...</h3>";
                $dbExists = $mysqli->select_db($database);
                
                if (!$dbExists) {
                    echo "<p class='error'>❌ Database '" . htmlspecialchars($database) . "' does not exist!</p>";
                    echo "<div class='warning'>";
                    echo "<strong>Solution:</strong> You need to create the database first.<br>";
                    echo "Run this SQL command in phpMyAdmin or MySQL command line:<br>";
                    echo "<pre>CREATE DATABASE " . htmlspecialchars($database) . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;</pre>";
                    echo "Then run the migrations: <code>php spark migrate</code><br>";
                    echo "And seed the database: <code>php spark db:seed DatabaseSeeder</code>";
                    echo "</div>";
                } else {
                    echo "<p class='success'>✅ Database '" . htmlspecialchars($database) . "' exists!</p>";
                    
                    // Test 3: Check for users table
                    echo "<h3>3. Testing Tables...</h3>";
                    $tables = ['users', 'branches', 'items', 'branch_stock', 'stock_movements', 'deliveries'];
                    $allTablesExist = true;
                    
                    foreach ($tables as $table) {
                        $result = $mysqli->query("SHOW TABLES LIKE '$table'");
                        if ($result && $result->num_rows > 0) {
                            $countResult = $mysqli->query("SELECT COUNT(*) as count FROM `$table`");
                            $count = $countResult ? $countResult->fetch_assoc()['count'] : 0;
                            echo "<p class='success'>✅ Table '$table' exists ($count rows)</p>";
                        } else {
                            echo "<p class='error'>❌ Table '$table' does not exist!</p>";
                            $allTablesExist = false;
                        }
                    }
                    
                    if ($allTablesExist) {
                        echo "<h3>4. Testing User Data...</h3>";
                        $result = $mysqli->query("SELECT COUNT(*) as count FROM `users`");
                        if ($result) {
                            $row = $result->fetch_assoc();
                            $userCount = $row['count'];
                            if ($userCount > 0) {
                                echo "<p class='success'>✅ Found $userCount user(s) in the database</p>";
                                
                                // Show sample usernames
                                $userResult = $mysqli->query("SELECT username, role FROM `users` LIMIT 5");
                                if ($userResult && $userResult->num_rows > 0) {
                                    echo "<p class='info'>Sample users:</p><ul>";
                                    while ($userRow = $userResult->fetch_assoc()) {
                                        echo "<li>" . htmlspecialchars($userRow['username']) . " (" . htmlspecialchars($userRow['role']) . ")</li>";
                                    }
                                    echo "</ul>";
                                    echo "<p class='info'><strong>Default password:</strong> 'password'</p>";
                                }
                            } else {
                                echo "<p class='error'>❌ No users found in the database!</p>";
                                echo "<div class='warning'>";
                                echo "<strong>Solution:</strong> Run the seeder to populate users:<br>";
                                echo "<code>php spark db:seed DatabaseSeeder</code>";
                                echo "</div>";
                            }
                        }
                    } else {
                        echo "<div class='warning'>";
                        echo "<strong>Solution:</strong> Run the migrations to create tables:<br>";
                        echo "<code>php spark migrate</code>";
                        echo "</div>";
                    }
                }
                
                $mysqli->close();
            }
            ?>
        </div>

        <div class="section">
            <h2>Next Steps:</h2>
            <ol>
                <li>If the connection failed, check your MySQL/XAMPP configuration</li>
                <li>If the database doesn't exist, create it using the SQL command above</li>
                <li>If tables are missing, run: <code>php spark migrate</code></li>
                <li>If users are missing, run: <code>php spark db:seed DatabaseSeeder</code></li>
                <li>Once everything is working, try logging in with one of the usernames shown above</li>
                <li><strong>Delete this test file</strong> (test_db_connection.php) after testing for security</li>
            </ol>
        </div>
    </div>
</body>
</html>

