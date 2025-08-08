<?php require_once '../config/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Join Queue</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 2fr 1fr;
            gap: 20px;
        }
        @media (max-width: 768px) {
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #1f2937; margin-bottom: 8px;">🎫 <?php echo APP_NAME; ?></h1>
            <p style="color: #6b7280;">Join the queue and wait for your turn</p>
        </div>
        
        <div class="grid">
            <div>
                <?php echo renderReactComponent('JoinQueueForm'); ?>
            </div>
            
            <div>
                <?php echo renderReactComponent('QueueDisplay'); ?>
            </div>
            
            <div>
                <?php echo renderReactComponent('WaitingIndicator'); ?>
            </div>
        </div>
    </div>
    
    <script src="/js/bundle.js"></script>
</body>
</html>
