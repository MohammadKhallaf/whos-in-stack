<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['member_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Member ID is required']);
    exit;
}

$qm = new QueueManager();
$result = $qm->finishService($data['member_id']);

echo json_encode($result);
