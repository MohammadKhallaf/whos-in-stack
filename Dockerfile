FROM node:18-alpine AS builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

FROM php:8.1-apache
WORKDIR /var/www/html

# Install SQLite extension
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copy application
COPY --from=builder /app .

# Create necessary directories with proper permissions
RUN mkdir -p data sessions \
    && chown -R www-data:www-data data sessions \
    && chmod -R 777 data sessions

# Configure Apache to use Railway's PORT
RUN echo '#!/bin/bash\n\
if [ -z "$PORT" ]; then\n\
  PORT=8080\n\
fi\n\
sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf\n\
echo "ServerName localhost" >> /etc/apache2/apache2.conf\n\
apache2-foreground' > /start-apache.sh && \
chmod +x /start-apache.sh

# Set document root to public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Railway will set PORT environment variable
CMD ["/start-apache.sh"]