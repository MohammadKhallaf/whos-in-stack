<?php
class Database {
    private $db;
    
    public function __construct() {
        $dbPath = __DIR__ . '/../data/queue.db';
        $dbDir = dirname($dbPath);
        
        if (!file_exists($dbDir)) {
            mkdir($dbDir, 0777, true);
        }
        
        $this->db = new PDO('sqlite:' . $dbPath);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->initializeDatabase();
    }
    
    private function initializeDatabase() {
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS queue (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                number INTEGER UNIQUE NOT NULL,
                name TEXT NOT NULL,
                status TEXT DEFAULT "waiting",
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                called_at DATETIME,
                completed_at DATETIME
            )
        ');
    }
    
    public function addToQueue($name) {
        $stmt = $this->db->prepare('SELECT MAX(number) FROM queue WHERE DATE(created_at) = DATE("now")');
        $stmt->execute();
        $lastNumber = $stmt->fetchColumn() ?: 0;
        
        $newNumber = $lastNumber + 1;
        
        $stmt = $this->db->prepare('INSERT INTO queue (number, name, status) VALUES (?, ?, "waiting")');
        $stmt->execute([$newNumber, $name]);
        
        return $newNumber;
    }
    
    public function getRoomStatus() {
        // Check if room is occupied
        $stmt = $this->db->prepare('
            SELECT name FROM queue 
            WHERE status = "occupied" 
            AND DATE(created_at) = DATE("now")
            LIMIT 1
        ');
        $stmt->execute();
        $occupiedBy = $stmt->fetchColumn();
        
        // Count waiting
        $stmt = $this->db->prepare('
            SELECT COUNT(*) FROM queue 
            WHERE status = "waiting" 
            AND DATE(created_at) = DATE("now")
        ');
        $stmt->execute();
        $waitingCount = $stmt->fetchColumn();
        
        return [
            'occupied' => !empty($occupiedBy),
            'current_user' => $occupiedBy,
            'waiting_count' => $waitingCount
        ];
    }
    
    public function getWaitingList() {
        $stmt = $this->db->prepare('
            SELECT number, name FROM queue 
            WHERE status = "waiting" 
            AND DATE(created_at) = DATE("now")
            ORDER BY number ASC
        ');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUserStatus($number) {
        $stmt = $this->db->prepare('
            SELECT status FROM queue 
            WHERE number = ? 
            AND DATE(created_at) = DATE("now")
        ');
        $stmt->execute([$number]);
        $status = $stmt->fetchColumn();
        
        if (!$status) return null;
        
        // Get position in queue if waiting
        $position = 0;
        if ($status === 'waiting') {
            $stmt = $this->db->prepare('
                SELECT COUNT(*) FROM queue 
                WHERE status = "waiting" 
                AND number < ?
                AND DATE(created_at) = DATE("now")
            ');
            $stmt->execute([$number]);
            $position = $stmt->fetchColumn() + 1;
        }
        
        return [
            'status' => $status,
            'position' => $position
        ];
    }
    
    public function updateStatus($number, $status) {
        $stmt = $this->db->prepare('
            UPDATE queue 
            SET status = ?, 
                called_at = CASE WHEN ? = "occupied" THEN CURRENT_TIMESTAMP ELSE called_at END,
                completed_at = CASE WHEN ? IN ("completed", "cancelled") THEN CURRENT_TIMESTAMP ELSE completed_at END
            WHERE number = ? 
            AND DATE(created_at) = DATE("now")
        ');
        $stmt->execute([$status, $status, $status, $number]);
    }
    
    public function getQueueList() {
        $stmt = $this->db->prepare('
            SELECT * FROM queue 
            WHERE DATE(created_at) = DATE("now")
            ORDER BY 
                CASE status 
                    WHEN "occupied" THEN 1
                    WHEN "waiting" THEN 2
                    WHEN "completed" THEN 3
                    WHEN "cancelled" THEN 4
                END,
                number ASC
        ');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function clearTodayQueue() {
        $stmt = $this->db->prepare('DELETE FROM queue WHERE DATE(created_at) = DATE("now")');
        $stmt->execute();
    }
}
