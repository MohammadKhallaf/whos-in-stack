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

# Expose port
EXPOSE 8000

# Start PHP server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]