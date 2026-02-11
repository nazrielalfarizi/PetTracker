web: vendor/bin/heroku-php-apache2 public/

web: php artisan migrate --force && php artisan db:seed --force && vendor/bin/heroku-php-apache2 public/
