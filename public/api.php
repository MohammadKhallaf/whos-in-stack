<?php
require_once __DIR__ . '/../src/Database.php';

header('Content-Type: application/json');

$db = new Database();
$action = $_GET['action'] ?? '';

switch($action) {
    case 'display_status':
        $room = $db->getRoomStatus();
        $waitingList = $db->getWaitingList();
        $nextPerson = count($waitingList) > 0 ? $waitingList[0] : null;
        
        echo json_encode([
            'room' => $room,
            'next_person' => $nextPerson,
            'waiting_list' => $waitingList
        ]);
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action']);
}
