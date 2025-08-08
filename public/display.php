<?php require_once '../config/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Display</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px;
        }
        .container {
            max-width: 1600px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 40px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        .big-text {
            font-size: 48px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="big-text" style="color: #1f2937;">🎫 Queue Status</h1>
        </div>
        
        <div class="grid">
            <div>
                <?php echo renderReactComponent('QueueDisplay'); ?>
            </div>
            
            <div>
                <?php echo renderReactComponent('RoomStatus'); ?>
            </div>
        </div>
    </div>
    
    <script src="/js/bundle.js"></script>
    <script>
        // Auto refresh page every 30 seconds
        setTimeout(() => window.location.reload(), 30000);
    </script>
</body>
</html>
