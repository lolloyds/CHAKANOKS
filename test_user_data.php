<?php

// Simple test to check user data structure
require_once 'vendor/autoload.php';

// Define constants
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', __DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR);
define('APPPATH', __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);
define('WRITEPATH', __DIR__ . DIRECTORY_SEPARATOR . 'writable' . DIRECTORY_SEPARATOR);
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Load CodeIgniter
$pathsConfig = APPPATH . 'Config/Paths.php';
require realpath($pathsConfig) ?: $pathsConfig;

$paths = new Config\Paths();
require SYSTEMPATH . 'bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();

// Test database connection and user data
$db = \Config\Database::connect();

echo "Testing user data structure...\n\n";

// Get a sample user
$user = $db->table('users')->where('id', 1)->get()->getRowArray();

if ($user) {
    echo "User found with ID 1:\n";
    echo "Available fields:\n";
    foreach ($user as $key => $value) {
        echo "- $key: " . (is_string($value) ? substr($value, 0, 50) : $value) . "\n";
    }
} else {
    echo "No user found with ID 1\n";
    echo "Available users:\n";
    $users = $db->table('users')->select('id, username, role')->get()->getResultArray();
    foreach ($users as $u) {
        echo "- ID: {$u['id']}, Username: {$u['username']}, Role: {$u['role']}\n";
    }
}