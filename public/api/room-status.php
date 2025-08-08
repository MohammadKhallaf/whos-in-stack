<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

$qm = new QueueManager();

$response = [
    'room' => $qm->getRoomStatus(),
    'current' => $qm->getCurrentInRoom(),
    'next' => $qm->getNextInLine(),
    'waiting_count' => $qm->getWaitingCount()
];

echo json_encode($response);
