<?php

require_once __DIR__ . '/vendor/autoload.php';

define('ROOTPATH', __DIR__);
$pathsConfig = ROOTPATH . '/app/Config/Paths.php';
require realpath($pathsConfig) ?: $pathsConfig;
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';
$app = \Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();
$users = $db->table('users')->get()->getResultArray();

echo "Total users in database: " . count($users) . PHP_EOL . PHP_EOL;

if (count($users) > 0) {
    echo "Users found:" . PHP_EOL;
    foreach ($users as $user) {
        echo "- Username: {$user['username']} | Role: {$user['role']}" . PHP_EOL;
        // Test password hash
        if (password_verify('password', $user['password_hash'])) {
            echo "  ✅ Password 'password' works for this user" . PHP_EOL;
        } else {
            echo "  ❌ Password 'password' does NOT work for this user" . PHP_EOL;
        }
    }
} else {
    echo "No users found in database!" . PHP_EOL;
    echo "Run: php spark db:seed DatabaseSeeder" . PHP_EOL;
}

