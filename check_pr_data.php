<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=chakanoks_db', 'root', '');
    
    echo "=== Recent Purchase Requests ===\n";
    $stmt = $db->query('SELECT * FROM purchase_requests ORDER BY id DESC LIMIT 5');
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($results)) {
        echo "No purchase requests found.\n";
    } else {
        foreach($results as $row) {
            echo "ID: {$row['id']}, Request ID: {$row['request_id']}, Status: {$row['status']}, Branch: {$row['branch_id']}\n";
        }
    }
    
    echo "\n=== Recent Purchase Request Items ===\n";
    $stmt = $db->query('SELECT * FROM purchase_request_items ORDER BY id DESC LIMIT 10');
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($results)) {
        echo "No purchase request items found.\n";
    } else {
        foreach($results as $row) {
            echo "ID: {$row['id']}, PR ID: {$row['purchase_request_id']}, Item: {$row['item_name']}, Qty: {$row['quantity']}\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>