FROM php:8.1-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable SQLite
RUN apt-get install -y libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite

# Set working directory
WORKDIR /app

# Copy application files
COPY . /app

# Install Node dependencies and build React
RUN cd /app && \
    npm install && \
    npm run build

# Create data and sessions directories with proper permissions
RUN mkdir -p /app/data /app/sessions && \
    chmod 777 /app/data /app/sessions

# Create startup script
RUN printf '#!/bin/sh\n\
if [ -z "$PORT" ]; then\n\
  echo "PORT not set, using 8080"\n\
  PORT=8080\n\
fi\n\
echo "Starting PHP server on port $PORT"\n\
exec php -S 0.0.0.0:$PORT -t public\n' > /app/start.sh && \
chmod +x /app/start.sh

# Use the startup script
CMD ["/app/start.sh"]