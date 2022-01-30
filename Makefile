start:
	php artisan serve --host 0.0.0.0

setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi

deploy:
	git push heroku

lint:
	composer run-script phpcs -- --standard=phpcs.xml app config routes

test-coverage:
	composer run-script phpunit tests -- --coverage-clover build/logs/clover.xml

docker-up: docker-down
	docker-compose down
	docker-compose up -d --build

docker-down:
	docker-compose down

docker-restart: docker-up
