<?php
/**
 * Database Reset Script
 * This will drop all existing tables and run fresh migrations
 */

require_once 'vendor/autoload.php';

// Load CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();

echo "🔄 Resetting Database...\n\n";

// Drop all tables in reverse dependency order
$tables = [
    'alerts',
    'delivery_items', 
    'deliveries',
    'purchase_order_items',
    'purchase_orders', 
    'purchase_request_items',
    'purchase_requests',
    'stock_movements',
    'branch_stock',
    'users',
    'suppliers',
    'items',
    'branches'
];

echo "📋 Tables to drop: " . implode(', ', $tables) . "\n\n";

foreach ($tables as $table) {
    try {
        $db->query("SET FOREIGN_KEY_CHECKS = 0");
        $db->query("DROP TABLE IF EXISTS `{$table}`");
        echo "✅ Dropped table: {$table}\n";
    } catch (Exception $e) {
        echo "⚠️  Could not drop {$table}: " . $e->getMessage() . "\n";
    }
}

$db->query("SET FOREIGN_KEY_CHECKS = 1");

echo "\n🚀 Running fresh migrations...\n";

// Run migrations
exec('php spark migrate', $output, $return_code);

if ($return_code === 0) {
    echo "✅ Migrations completed successfully!\n";
    foreach ($output as $line) {
        echo $line . "\n";
    }
    
    echo "\n🌱 Running seeders...\n";
    
    // Run seeders
    exec('php spark db:seed DatabaseSeeder', $seedOutput, $seedReturnCode);
    
    if ($seedReturnCode === 0) {
        echo "✅ Seeders completed successfully!\n";
        foreach ($seedOutput as $line) {
            echo $line . "\n";
        }
    } else {
        echo "❌ Seeding failed!\n";
        foreach ($seedOutput as $line) {
            echo $line . "\n";
        }
    }
    
} else {
    echo "❌ Migration failed!\n";
    foreach ($output as $line) {
        echo $line . "\n";
    }
}

echo "\n✨ Database reset complete!\n";