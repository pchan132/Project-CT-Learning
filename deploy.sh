#!/bin/bash
# deploy.sh

echo "🚀 Starting CT Learning Deployment..."

# Pull latest code
git pull origin main

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recreate caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Set permissions
chmod -R 775 storage bootstrap/cache

# Restart services (Adjust service names as needed)
# sudo systemctl reload nginx
# sudo systemctl reload php8.2-fpm

echo "✅ Deployment completed successfully!"
