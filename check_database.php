<?php
/**
 * Simple Database Connection Check
 */

// Database configuration from .env
$hostname = 'localhost';
$username = 'root';
$password = 'admin';
$database = 'chakanoks_db';
$port = 3306;

echo "🔍 Checking Database Configuration...\n\n";

echo "Configuration:\n";
echo "- Host: $hostname:$port\n";
echo "- Username: $username\n";
echo "- Password: " . str_repeat('*', strlen($password)) . "\n";
echo "- Database: $database\n\n";

// Test MySQL connection
echo "📡 Testing MySQL connection...\n";
$mysqli = @new mysqli($hostname, $username, $password, '', $port);

if ($mysqli->connect_error) {
    echo "❌ Connection failed: " . $mysqli->connect_error . "\n";
    echo "\nPossible solutions:\n";
    echo "1. Check if MySQL/XAMPP is running\n";
    echo "2. Verify username/password in .env file\n";
    echo "3. Check if MySQL is running on port $port\n";
    exit(1);
}

echo "✅ MySQL connection successful!\n";
echo "MySQL version: " . $mysqli->server_info . "\n\n";

// Check if database exists
echo "🗄️  Checking database '$database'...\n";
$result = $mysqli->query("SHOW DATABASES LIKE '$database'");

if ($result->num_rows == 0) {
    echo "❌ Database '$database' does not exist!\n";
    echo "\nCreating database...\n";
    
    if ($mysqli->query("CREATE DATABASE `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
        echo "✅ Database '$database' created successfully!\n";
    } else {
        echo "❌ Failed to create database: " . $mysqli->error . "\n";
        exit(1);
    }
} else {
    echo "✅ Database '$database' exists!\n";
}

// Select the database
$mysqli->select_db($database);

// Check for existing tables
echo "\n📋 Checking tables...\n";
$result = $mysqli->query("SHOW TABLES");
$tables = [];
while ($row = $result->fetch_array()) {
    $tables[] = $row[0];
}

if (empty($tables)) {
    echo "ℹ️  No tables found. Database is empty.\n";
    echo "Run 'php spark migrate' to create tables.\n";
} else {
    echo "Found " . count($tables) . " tables:\n";
    foreach ($tables as $table) {
        $countResult = $mysqli->query("SELECT COUNT(*) as count FROM `$table`");
        $count = $countResult->fetch_assoc()['count'];
        echo "- $table ($count rows)\n";
    }
}

$mysqli->close();

echo "\n✨ Database check complete!\n";
echo "\nNext steps:\n";
echo "1. Run: php spark migrate\n";
echo "2. Run: php spark db:seed DatabaseSeeder\n";
echo "3. Test the application\n";
?>