#!/bin/sh
cd docker/
# load .env variables
echo "\nLoad '.env'."
if [ -f .env ]
then
  export $(cat .env | xargs)
fi

echo "\nDocker Containers build start."
docker-compose build --no-cache \
&& docker-compose up -d \

echo "\n\033[43m\033[1;30mWaiting for mysql connection...\033[0m"
echo "Note: this operation is to make sure that mysql is ready to can run the database migrations."
while ! docker exec carefer-mysql8-dev mysql --user=root --password=root -e "SELECT 1" >/dev/null 2>&1; do
    sleep 1
done
echo "\e[32mMySQL is ready.\033[0m"

echo "Docker Containers build completed.\n"

echo "\nComposer intstall start."
docker exec -it carefer-php-dev composer install
echo "Composer intstall completed.\n"

echo "\nMigration start."
docker exec -it carefer-php-dev php artisan migrate
echo "Migration completed.\n"

echo "\nDB Seed."
docker exec -it carefer-php-dev php artisan db:seed
echo "DB Seed Done.\n"

echo "\nRun Unit tests."
docker exec -it carefer-php-dev php artisan test
echo "Unit tests Done.\n"

echo "\nClear Laravel cache."
docker exec -it carefer-php-dev php artisan config:cache
docker exec -it carefer-php-dev php artisan cache:clear
echo "Clear Laravel cacheDone.\n"

echo "\nChange storage permission."
docker exec -it carefer-php-dev chgrp -R www-data storage bootstrap/cache
docker exec -it carefer-php-dev chmod -R ug+rwx storage bootstrap/cache
echo "Change storage permission Done.\n"

echo "\n\n"
echo "\t\t\t\033[43m\033[1;30mApp Domain: \033[0m \033[1;33m http://localhost:$CAREFER_HOST_PORT \033[0m"
echo "\t\t\t\033[43m\033[1;30mPHPMyAdmin: \033[0m \033[1;33m http://localhost:$CAREFER_PMA_HOST_PORT \033[0m"
echo "\n\n"
