#!/bin/bash
git pull
php artisan cache:clear
php artisan config:clear
php artisan view:clear
