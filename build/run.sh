cd build
docker-compose build
docker-compose up -d
docker-compose run --rm php74 bash -c "composer install"
