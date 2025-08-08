<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['name'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Name is required']);
    exit;
}

$qm = new QueueManager();
$member = $qm->joinQueue(
    $data['name'],
    $data['email'] ?? '',
    $data['phone'] ?? '',
    $data['reason'] ?? ''
);

echo json_encode($member);
