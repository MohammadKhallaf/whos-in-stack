<?php
class QueueManager {
    private $db;
    
    public function __construct() {
        $this->initDatabase();
    }
    
    private function initDatabase() {
        $dbFile = __DIR__ . '/../data/queue.db';
        $firstRun = !file_exists($dbFile);
        
        $this->db = new PDO('sqlite:' . $dbFile);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        if ($firstRun) {
            $schema = file_get_contents(__DIR__ . '/../data/schema.sql');
            $this->db->exec($schema);
        }
    }
    
    public function joinQueue($name, $email = '', $phone = '', $reason = '') {
        $stmt = $this->db->prepare("
            INSERT INTO queue_members (name, email, phone, reason, queue_number)
            VALUES (?, ?, ?, ?, (SELECT COALESCE(MAX(queue_number), 0) + 1 FROM queue_members WHERE DATE(joined_at) = DATE('now')))
        ");
        $stmt->execute([$name, $email, $phone, $reason]);
        
        $id = $this->db->lastInsertId();
        $this->checkAndNotifyNext();
        
        return $this->getMember($id);
    }
    
    public function enterRoom($memberId) {
        // Update member status
        $stmt = $this->db->prepare("
            UPDATE queue_members 
            SET status = 'in_room', entered_room_at = CURRENT_TIMESTAMP 
            WHERE id = ? AND status = 'waiting'
        ");
        $stmt->execute([$memberId]);
        
        // Update room current serving
        $stmt = $this->db->prepare("UPDATE room_config SET current_serving = ? WHERE id = 1");
        $stmt->execute([$memberId]);
        
        return $this->getMember($memberId);
    }
    
    public function finishService($memberId) {
        // Mark as finished
        $stmt = $this->db->prepare("
            UPDATE queue_members 
            SET status = 'finished', finished_at = CURRENT_TIMESTAMP 
            WHERE id = ? AND status = 'in_room'
        ");
        $stmt->execute([$memberId]);
        
        // Clear room
        $stmt = $this->db->prepare("UPDATE room_config SET current_serving = NULL WHERE id = 1");
        $stmt->execute();
        
        // Notify next person
        $this->checkAndNotifyNext();
        
        return ['success' => true, 'next' => $this->getNextInLine()];
    }
    
    public function getQueue($status = null) {
        $sql = "SELECT * FROM queue_members WHERE DATE(joined_at) = DATE('now')";
        if ($status) {
            $sql .= " AND status = :status";
        }
        $sql .= " ORDER BY queue_number ASC";
        
        $stmt = $this->db->prepare($sql);
        if ($status) {
            $stmt->bindParam(':status', $status);
        }
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCurrentInRoom() {
        $stmt = $this->db->prepare("
            SELECT * FROM queue_members 
            WHERE status = 'in_room' 
            ORDER BY entered_room_at DESC 
            LIMIT 1
        ");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getNextInLine() {
        $stmt = $this->db->prepare("
            SELECT * FROM queue_members 
            WHERE status = 'waiting' AND DATE(joined_at) = DATE('now')
            ORDER BY queue_number ASC 
            LIMIT 1
        ");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getMember($id) {
        $stmt = $this->db->prepare("SELECT * FROM queue_members WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getWaitingCount() {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM queue_members 
            WHERE status = 'waiting' AND DATE(joined_at) = DATE('now')
        ");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    public function getEstimatedWaitTime($position) {
        $avgTime = 15; // minutes
        return $position * $avgTime;
    }
    
    public function getRoomStatus() {
        $stmt = $this->db->prepare("SELECT * FROM room_config WHERE id = 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    private function checkAndNotifyNext() {
        $current = $this->getCurrentInRoom();
        if (!$current) {
            $next = $this->getNextInLine();
            if ($next) {
                // In real app, send notification (email, SMS, push)
                $this->createNotification($next['id'], 'Your turn is coming up!');
            }
        }
    }
    
    private function createNotification($memberId, $message) {
        // Store notification in session for demo
        if (!isset($_SESSION['notifications'])) {
            $_SESSION['notifications'] = [];
        }
        $_SESSION['notifications'][] = [
            'member_id' => $memberId,
            'message' => $message,
            'time' => date('Y-m-d H:i:s')
        ];
    }
    
    public function cancelQueueEntry($memberId) {
        $stmt = $this->db->prepare("
            UPDATE queue_members 
            SET status = 'cancelled', finished_at = CURRENT_TIMESTAMP 
            WHERE id = ? AND status = 'waiting'
        ");
        $stmt->execute([$memberId]);
        return ['success' => true];
    }
}
