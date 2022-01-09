start:
	php artisan serve --host 0.0.0.0

setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi

deploy:
	git push heroku

lint:
	composer run-script phpcs -- --standard=phpcs.xml app config
