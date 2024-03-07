#Запустить проект
init: down rebuild create-env  composer-install migrate-db insert-default-coupons insert-default-products insert-default-taxes

up:
	docker-compose -f docker-compose.yml up -d
down:
	docker-compose -f docker-compose.yml down
rebuild:
	docker-compose -f docker-compose.yml up -d --build
create-env:
	docker exec -t test-api-php cp .env.example .env
migrate-db:
	docker exec -t test-api-php php bin/console doctrine:migrations:migrate --no-interaction
composer-install:
	docker exec -t test-api-php composer install
insert-default-coupons:
	docker exec -t test-api-php php bin/console insert:default-coupons
insert-default-products:
	docker exec -t test-api-php php bin/console insert:default-products
insert-default-taxes:
	docker exec -t test-api-php  php bin/console insert:default-taxes

