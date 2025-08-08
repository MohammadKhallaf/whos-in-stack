<?php
session_start();
require_once __DIR__ . '/../src/Database.php';

$db = new Database();

// Handle admin actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch($_POST['action']) {
            case 'clear_all':
                $db->clearTodayQueue();
                break;
        }
        header('Location: admin.php');
        exit;
    }
}

$queueList = $db->getQueueList();
$roomStatus = $db->getRoomStatus();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        
        .dashboard {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
        }
        
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
        
        table {
            width: 100%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        th {
            background: #667eea;
            color: white;
            padding: 15px;
            text-align: left;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-waiting { background: #fef5e7; color: #f39c12; }
        .status-occupied { background: #e8f8f5; color: #27ae60; }
        .status-completed { background: #ebf5fb; color: #3498db; }
        .status-cancelled { background: #fdedec; color: #e74c3c; }
        
        .btn-clear {
            background: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h1>🎛️ Admin Dashboard</h1>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-value"><?php echo $roomStatus['occupied'] ? '🔴' : '🟢'; ?></div>
                <div class="stat-label">Room <?php echo $roomStatus['occupied'] ? 'Occupied' : 'Available'; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $roomStatus['waiting_count']; ?></div>
                <div class="stat-label">People Waiting</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo count($queueList); ?></div>
                <div class="stat-label">Total Today</div>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Queue #</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Join Time</th>
                    <th>Enter Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($queueList as $item): ?>
                <tr>
                    <td><?php echo $item['number']; ?></td>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>
                        <span class="status-badge status-<?php echo $item['status']; ?>">
                            <?php echo ucfirst($item['status']); ?>
                        </span>
                    </td>
                    <td><?php echo date('H:i', strtotime($item['created_at'])); ?></td>
                    <td><?php echo $item['called_at'] ? date('H:i', strtotime($item['called_at'])) : '-'; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <form method="POST" onsubmit="return confirm('Clear all queue data for today?')">
            <button type="submit" name="action" value="clear_all" class="btn-clear">
                Clear Today's Queue
            </button>
        </form>
    </div>
</body>
</html>
