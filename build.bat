@echo off
echo.
echo Building React components...
docker run --rm -v "%cd%":/app -w /app node:18-alpine sh -c "npm install && npm run build"
echo.
echo Build complete!
pause