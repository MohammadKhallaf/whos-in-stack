#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}❌ Docker is not running!${NC}"
    echo ""
    echo "Please start Docker Desktop and try again."
    echo ""
    if [[ "$OSTYPE" == "darwin"* ]]; then
        echo "On macOS: Open Docker Desktop from Applications"
    elif [[ "$OSTYPE" == "linux-gnu"* ]]; then
        echo "On Linux: Run 'sudo systemctl start docker'"
    fi
    exit 1
fi

# Build React if needed
if [ ! -f "public/js/bundle.js" ]; then
    echo -e "${YELLOW}📦 First run detected. Building React components...${NC}"
    docker run --rm -v "$(pwd)":/app -w /app node:18-alpine sh -c "npm install && npm run build"
    echo -e "${GREEN}✅ Build complete!${NC}"
fi

# Start PHP server
echo ""
echo -e "${GREEN}🎫 Starting Queue Management System${NC}"
echo "===================================="
echo "📍 Customer:  http://localhost:8000"
echo "📍 Admin:     http://localhost:8000/admin.php"
echo "📍 Display:   http://localhost:8000/display.php"
echo ""
echo "Press Ctrl+C to stop"
echo ""

docker run -it --rm \
    -p 8000:8000 \
    -v "$(pwd)":/app \
    -w /app \
    php:8.1-cli \
    bash -c "
        apt-get update -qq && 
        apt-get install -y libsqlite3-dev > /dev/null 2>&1 && 
        docker-php-ext-install pdo_sqlite > /dev/null 2>&1 &&
        php -S 0.0.0.0:8000 -t public
    "