<?php
require_once 'includes/db.php';

$stmt = $pdo->query("SELECT * FROM feedback ORDER BY created_at DESC");
$rows = $stmt->fetchAll();

echo "<h2>Feedback Dashboard</h2>";

foreach($rows as $r){
    echo "<p><strong>Rating:</strong> {$r['rating']}<br>";
    echo "<strong>Message:</strong> {$r['message']}<br>";
    echo "<strong>Page:</strong> {$r['page_url']}<hr></p>";
}
?>