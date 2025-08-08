<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

$qm = new QueueManager();

// Get all queue members for today
$queue = $qm->getQueue();

echo json_encode($queue);
