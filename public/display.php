<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Status Display</title>
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .status-container {
            text-align: center;
            padding: 50px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .room-icon {
            font-size: 120px;
            margin-bottom: 20px;
        }
        
        .status-text {
            font-size: 60px;
            font-weight: bold;
            margin: 20px 0;
        }
        
        .available { color: #48bb78; }
        .occupied { color: #f56565; }
        
        .queue-count {
            font-size: 30px;
            margin-top: 30px;
            opacity: 0.9;
        }
        
        .waiting-next {
            margin-top: 40px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
        }
        
        .next-person {
            font-size: 24px;
        }
        
        .time {
            position: absolute;
            top: 30px;
            right: 30px;
            font-size: 24px;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="time" id="clock"></div>
    
    <div class="status-container">
        <div class="room-icon" id="icon">🚪</div>
        <div class="status-text" id="status">Loading...</div>
        <div id="current-user"></div>
        <div class="queue-count" id="queue-info"></div>
        <div class="waiting-next" id="next-person" style="display: none;"></div>
    </div>
    
    <script>
        function updateDisplay() {
            fetch('api.php?action=display_status')
                .then(response => response.json())
                .then(data => {
                    const statusEl = document.getElementById('status');
                    const iconEl = document.getElementById('icon');
                    const queueEl = document.getElementById('queue-info');
                    const currentEl = document.getElementById('current-user');
                    const nextEl = document.getElementById('next-person');
                    
                    if (data.room.occupied) {
                        iconEl.textContent = '🔴';
                        statusEl.textContent = 'OCCUPIED';
                        statusEl.className = 'status-text occupied';
                        if (data.room.current_user) {
                            currentEl.innerHTML = `<div style="font-size: 24px; margin-top: 10px;">Current: ${data.room.current_user}</div>`;
                        }
                    } else {
                        iconEl.textContent = '🟢';
                        statusEl.textContent = 'AVAILABLE';
                        statusEl.className = 'status-text available';
                        currentEl.innerHTML = '';
                    }
                    
                    if (data.room.waiting_count > 0) {
                        queueEl.textContent = `${data.room.waiting_count} person(s) waiting`;
                        
                        if (data.next_person) {
                            nextEl.style.display = 'block';
                            nextEl.innerHTML = `<div class="next-person">Next: ${data.next_person.name}</div>`;
                        }
                    } else {
                        queueEl.textContent = 'No one waiting';
                        nextEl.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error fetching status:', error);
                });
        }
        
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleTimeString();
        }
        
        updateDisplay();
        updateClock();
        setInterval(updateDisplay, 3000);
        setInterval(updateClock, 1000);
    </script>
</body>
</html>
