<?php
$ports = [3307, 3306];
$mysqli = null;
foreach ($ports as $p) {
    try {
        $m = @new mysqli('localhost','root','admin','chakanoks_db',$p);
        if ($m && !$m->connect_errno) { $mysqli = $m; break; }
    } catch (Exception $e) {}
}
if (!$mysqli) {
    echo "CONNECT_ERR: Could not connect to database on ports " . implode(',', $ports) . PHP_EOL;
    exit(1);
}
$reqId = $argv[1] ?? 'PR-005';
$stmt = $mysqli->prepare('SELECT * FROM purchase_requests WHERE request_id = ? LIMIT 1');
$stmt->bind_param('s', $reqId);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
echo "PR_ROW:" . json_encode($row) . PHP_EOL;
if ($row) {
    $id = (int)$row['id'];
    $itStmt = $mysqli->prepare('SELECT * FROM purchase_request_items WHERE purchase_request_id = ?');
    $itStmt->bind_param('i', $id);
    $itStmt->execute();
    $itRes = $itStmt->get_result();
    while ($it = $itRes->fetch_assoc()) {
        echo "ITEM_ROW:" . json_encode($it) . PHP_EOL;
    }
}
$mysqli->close();

echo "Done." . PHP_EOL;
