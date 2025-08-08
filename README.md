# Who's In The Stack - Queue Management System

A PHP-based queue management system with React components.

## Requirements
- Docker Desktop (that's it!)

## Installation

### 🪟 Windows
1. **Install Docker Desktop for Windows**
   - Download from: https://www.docker.com/products/docker-desktop/
   - Enable WSL 2 during installation
   - Restart your computer after installation

### 🍎 macOS
1. **Install Docker Desktop for Mac**
   - Intel Mac: https://desktop.docker.com/mac/main/amd64/Docker.dmg
   - Apple Silicon (M1/M2): https://desktop.docker.com/mac/main/arm64/Docker.dmg
   - Open Docker Desktop after installation

### 🐧 Linux
1. **Install Docker**
   ```bash
   # Ubuntu/Debian
   curl -fsSL https://get.docker.com -o get-docker.sh
   sudo sh get-docker.sh
   
   # Add your user to docker group (to run without sudo)
   sudo usermod -aG docker $USER
   newgrp docker
   ```

## Quick Start

### Clone the repository
```bash
git clone https://github.com/YOUR_USERNAME/whos-in-stack.git
cd whos-in-stack
```

### Run the application

#### 🪟 Windows (Command Prompt or PowerShell)
```cmd
run.bat
```

#### 🍎 macOS / 🐧 Linux
```bash
./run.sh
```

That's it! The application will:
- Build React components automatically (first run only)
- Start PHP server on port 8000
- Install all dependencies inside Docker

## Access Points
- **Customer View:** http://localhost:8000
- **Admin Panel:** http://localhost:8000/admin.php
- **Display Screen:** http://localhost:8000/display.php

## Stopping the Application
- **Windows:** Press `Ctrl+C` in Command Prompt
- **Mac/Linux:** Press `Ctrl+C` in Terminal

## Project Structure
```
├── public/          # PHP pages (entry points)
├── includes/        # PHP classes
├── src/            # React components
├── data/           # SQLite database (auto-created)
├── run.sh          # Start script (Mac/Linux)
└── run.bat         # Start script (Windows)
```

## For Developers

### Rebuild React components

#### 🪟 Windows
```cmd
build.bat
```

#### 🍎 macOS / 🐧 Linux
```bash
./build.sh
```

### Start without rebuilding

#### 🪟 Windows
```cmd
start.bat
```

#### 🍎 macOS / 🐧 Linux
```bash
./start.sh
```

## Platform-Specific Notes

### Windows Users
- Make sure Docker Desktop is running (check system tray)
- Use Command Prompt, PowerShell, or Git Bash
- If using Git Bash, you can use `./run.sh` instead of `run.bat`

### Mac Users
- Make sure Docker Desktop is running (check menu bar)
- First time running scripts: `chmod +x run.sh build.sh start.sh`
- For Apple Silicon Macs (M1/M2), Docker runs in emulation mode but works fine

### Linux Users
- No Docker Desktop needed, just Docker Engine
- First time running scripts: `chmod +x run.sh build.sh start.sh`
- If permission denied: `sudo ./run.sh` or add user to docker group

## Troubleshooting

### "Docker not found" error
- **Windows/Mac:** Make sure Docker Desktop is installed and running
- **Linux:** Install Docker with the commands above

### "Permission denied" error (Mac/Linux)
```bash
chmod +x run.sh build.sh start.sh
```

### "Port 8000 already in use" error

#### Windows
```cmd
# Find what's using port 8000
netstat -ano | findstr :8000

# Kill the process (replace PID with the number from above)
taskkill /PID <PID> /F
```

#### Mac/Linux
```bash
# Find what's using port 8000
lsof -i :8000

# Kill the process (replace PID with the number from above)
kill -9 <PID>
```

### Docker Desktop won't start (Windows)
1. Enable virtualization in BIOS
2. Enable WSL 2: `wsl --install`
3. Restart computer

### Docker Desktop won't start (Mac)
1. Check macOS version (needs 10.15 or higher)
2. For M1/M2 Macs: Enable Rosetta in Docker settings

## No Installation Required (except Docker)!
Everything runs inside Docker containers. No need to install:
- ❌ PHP
- ❌ Node.js
- ❌ npm
- ❌ SQLite
- ❌ Composer
- ✅ Just Docker Desktop!

## Team Development

### First time setup
```bash
git clone https://github.com/YOUR_USERNAME/whos-in-stack.git
cd whos-in-stack

# Windows
run.bat

# Mac/Linux
./run.sh
```

### Pull latest changes
```bash
git pull

# Windows
build.bat
run.bat

# Mac/Linux
./build.sh
./run.sh
```

## License
MIT