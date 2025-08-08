<?php
session_start();
require_once __DIR__ . '/../src/Database.php';

$db = new Database();

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch($_POST['action']) {
            case 'enter_queue':
                $name = $_POST['name'] ?? 'Anonymous';
                $number = $db->addToQueue($name);
                $_SESSION['user_number'] = $number;
                $_SESSION['user_name'] = $name;
                header('Location: index.php');
                exit;
                break;
                
            case 'enter_room':
                if (isset($_SESSION['user_number'])) {
                    $db->updateStatus($_SESSION['user_number'], 'occupied');
                }
                break;
                
            case 'leave_room':
                if (isset($_SESSION['user_number'])) {
                    $db->updateStatus($_SESSION['user_number'], 'completed');
                    unset($_SESSION['user_number']);
                    unset($_SESSION['user_name']);
                }
                break;
                
            case 'leave_queue':
                if (isset($_SESSION['user_number'])) {
                    $db->updateStatus($_SESSION['user_number'], 'cancelled');
                    unset($_SESSION['user_number']);
                    unset($_SESSION['user_name']);
                }
                break;
        }
        header('Location: index.php');
        exit;
    }
}

$roomStatus = $db->getRoomStatus();
$waitingList = $db->getWaitingList();
$myStatus = null;

if (isset($_SESSION['user_number'])) {
    $myStatus = $db->getUserStatus($_SESSION['user_number']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Queue System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 400px;
            margin: 0 auto;
        }
        
        .card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .room-status {
            text-align: center;
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
            font-size: 20px;
            font-weight: bold;
        }
        
        .status-available {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }
        
        .status-occupied {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }
        
        .queue-info {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 10px;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: #48bb78;
            color: white;
        }
        
        .btn-danger {
            background: #f56565;
            color: white;
        }
        
        .btn-warning {
            background: #ed8936;
            color: white;
        }
        
        .waiting-list {
            margin-top: 20px;
        }
        
        .waiting-person {
            background: #f7fafc;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .position-badge {
            background: #667eea;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
        
        .my-status {
            background: #e6fffa;
            border: 2px solid #81e6d9;
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
            text-align: center;
        }
        
        .status-tag {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .tag-waiting {
            background: #fef5e7;
            color: #f39c12;
        }
        
        .tag-occupied {
            background: #e8f8f5;
            color: #27ae60;
        }
        
        .emoji {
            font-size: 40px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>🚪 Room Queue</h1>
            
            <!-- Room Status -->
            <div class="room-status <?php echo $roomStatus['occupied'] ? 'status-occupied' : 'status-available'; ?>">
                <div class="emoji"><?php echo $roomStatus['occupied'] ? '🔴' : '🟢'; ?></div>
                Room is <?php echo $roomStatus['occupied'] ? 'OCCUPIED' : 'AVAILABLE'; ?>
                <?php if ($roomStatus['occupied'] && $roomStatus['current_user']): ?>
                    <br><small>Current: <?php echo htmlspecialchars($roomStatus['current_user']); ?></small>
                <?php endif; ?>
            </div>
            
            <?php if ($roomStatus['waiting_count'] > 0): ?>
            <div class="queue-info">
                👥 <?php echo $roomStatus['waiting_count']; ?> person(s) waiting
            </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['user_number']) && $myStatus): ?>
                <!-- User has a number -->
                <div class="my-status">
                    <h3>Your Status</h3>
                    <p><strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></p>
                    <p>Queue #<?php echo $_SESSION['user_number']; ?></p>
                    
                    <?php if ($myStatus['status'] === 'waiting'): ?>
                        <span class="status-tag tag-waiting">⏳ Waiting - Position <?php echo $myStatus['position']; ?></span>
                        
                        <?php if ($myStatus['position'] === 1 && !$roomStatus['occupied']): ?>
                            <form method="POST" style="margin-top: 15px;">
                                <button type="submit" name="action" value="enter_room" class="btn btn-success">
                                    ✅ Enter Room
                                </button>
                            </form>
                        <?php endif; ?>
                        
                        <form method="POST" style="margin-top: 10px;">
                            <button type="submit" name="action" value="leave_queue" class="btn btn-warning">
                                ❌ Leave Queue
                            </button>
                        </form>
                        
                    <?php elseif ($myStatus['status'] === 'occupied'): ?>
                        <span class="status-tag tag-occupied">🔓 In Room</span>
                        <form method="POST" style="margin-top: 15px;">
                            <button type="submit" name="action" value="leave_room" class="btn btn-danger">
                                🚪 Leave Room
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
                
            <?php else: ?>
                <!-- No number yet -->
                <form method="POST">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Enter your name" required>
                    </div>
                    <button type="submit" name="action" value="enter_queue" class="btn btn-primary">
                        ➕ Join Queue
                    </button>
                </form>
            <?php endif; ?>
            
            <!-- Waiting List -->
            <?php if (count($waitingList) > 0): ?>
            <div class="waiting-list">
                <h3 style="margin-bottom: 15px;">📋 Queue List</h3>
                <?php foreach ($waitingList as $index => $person): ?>
                <div class="waiting-person">
                    <span><?php echo htmlspecialchars($person['name']); ?></span>
                    <span class="position-badge">#<?php echo $index + 1; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Auto-refresh every minute
        setTimeout(() => {
            location.reload();
        }, 1*1000*60);
    </script>
</body>
</html>
