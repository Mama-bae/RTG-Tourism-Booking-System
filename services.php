<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db.php';

$stmt = $pdo->query('SELECT service_id, service_name, category, location, description, image_path FROM services WHERE active = 1 ORDER BY service_name');
echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
