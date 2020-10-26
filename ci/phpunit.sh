#!/bin/bash

php bin/console cache:clear --env=test
php bin/console doctrine:database:drop --force --env=test
php bin/console doctrine:database:create --env=test
php bin/console doctrine:schema:update --force --env=test --no-interaction
vendor/bin/phpunit --teamcity