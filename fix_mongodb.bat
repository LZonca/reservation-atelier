@echo off
echo ========================================
echo Nettoyage des caches Laravel
echo ========================================
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear

echo.
echo ========================================
echo Recompilation de l'autoloader Composer
echo ========================================
composer dump-autoload -o

echo.
echo ========================================
echo Verification de l'extension MongoDB
echo ========================================
php -m | findstr mongodb

echo.
echo ========================================
echo Test de la connexion MongoDB
echo ========================================
php artisan tinker --execute="echo 'DB Name: ' . DB::connection()->getDatabaseName();"

echo.
echo ========================================
echo TERMINE - Redemarrez votre serveur web
echo ========================================
echo Si vous utilisez 'php artisan serve', arretez-le (Ctrl+C) et relancez-le
pause

