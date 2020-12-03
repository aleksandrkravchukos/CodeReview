## Build the docker container, install the dependencies
build:
	docker-compose build

## Install the composer dependencies
vendors-install:
	docker-compose run --rm --no-deps php-cli composer install

## Update the composer dependencies
vendors-update:
	docker-compose run --rm --no-deps php-cli composer update


## Copy dist files to actual path (if not present yet)
copy-dist-configs:
	docker-compose run --rm --no-deps php-cli cp -n phpunit.xml.dist phpunit.xml
	docker-compose run --rm --no-deps php-cli cp -n phpstan.neon.dist phpstan.neon
	docker-compose run --rm --no-deps php-cli cp -n .php_cs.dist .php_cs

## Update composer autoload
dump-autoload:
	docker-compose run --rm --no-deps php-cli composer dump-autoload

## Run console application
run:
	docker-compose run --rm --no-deps php-cli php src/index.php

## Run unit tests
unit-tests:
	docker-compose run --rm --no-deps php-cli ./vendor/bin/phpunit --no-coverage --stop-on-error --stop-on-failure --testsuite Unit

## Run unit tests
functional-tests:
	docker-compose run --rm --no-deps php-cli ./vendor/bin/phpunit --no-coverage --stop-on-error --stop-on-failure --testsuite Functional

## Run unit tests
static-analysis:
	docker-compose run --rm --no-deps php-cli ./vendor/bin/phpstan analyze

## Run cs-fixer
cs-fix:
	docker-compose run --rm --no-deps php-cli ./vendor/bin/php-cs-fixer fix