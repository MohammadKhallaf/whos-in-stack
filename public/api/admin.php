<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

$qm = new QueueManager();

$action = $_GET['action'] ?? 'stats';

switch($action) {
    case 'stats':
        $waiting = $qm->getQueue('waiting');
        $finished = $qm->getQueue('finished');
        
        echo json_encode([
            'todayTotal' => count($qm->getQueue()),
            'currentWaiting' => count($waiting),
            'servedToday' => count($finished),
            'averageWaitTime' => 15 // Would calculate from actual data
        ]);
        break;
        
    case 'clear':
        // Clear queue logic here
        echo json_encode(['success' => true]);
        break;
        
    default:
        echo json_encode(['error' => 'Unknown action']);
}
