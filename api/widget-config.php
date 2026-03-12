<?php
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

$siteKey = trim($_GET['site_key'] ?? '');
if ($siteKey === '') {
    echo json_encode(['button_text' => 'Feedback', 'button_color' => '#0b1730', 'position' => 'right']);
    exit;
}

$stmt = $pdo->prepare("SELECT ws.button_text, ws.button_color, ws.position
    FROM projects p
    LEFT JOIN widget_settings ws ON ws.project_id = p.id
    WHERE p.site_key = ?");
$stmt->execute([$siteKey]);
$row = $stmt->fetch();

echo json_encode([
    'button_text' => $row['button_text'] ?? 'Feedback',
    'button_color' => $row['button_color'] ?? '#0b1730',
    'position' => $row['position'] ?? 'right'
]);
?>
