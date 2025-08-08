#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}❌ Docker is not running!${NC}"
    echo "Please start Docker Desktop and try again."
    exit 1
fi

echo -e "${GREEN}🎫 Starting PHP Queue System on port 8000...${NC}"
echo "📍 Customer:  http://localhost:8000"
echo "📍 Admin:     http://localhost:8000/admin.php"
echo "📍 Display:   http://localhost:8000/display.php"
echo ""
echo "Press Ctrl+C to stop"

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