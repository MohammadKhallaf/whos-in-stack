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

# Create data directory with proper permissions
RUN mkdir -p /app/data && chmod 777 /app/data
RUN mkdir -p /app/sessions && chmod 777 /app/sessions

# Create a start script that uses PORT environment variable
RUN echo '#!/bin/sh\n\
PORT=${PORT:-8000}\n\
echo "Starting PHP server on port $PORT"\n\
exec php -S 0.0.0.0:$PORT -t public' > /app/start.sh && \
chmod +x /app/start.sh

# Railway will set the PORT environment variable
CMD ["/app/start.sh"]