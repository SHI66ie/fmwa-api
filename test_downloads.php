<?php
require_once 'config.php';
try {
    $stmt = $pdo->query("SELECT * FROM downloads LIMIT 5");
    $results = $stmt->fetchAll();
    echo json_encode($results);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
