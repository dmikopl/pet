## Start

``docker compose up -d --build``

``docker exec -it nginx /bin/sh``

``chmod -R 777 /var/www/app/storage``

``cd var/www/app``

``cp .env.example .env``

Generate app APP_KEY if empty
 
``php artisan key:generate``

http://localhost:7000/
