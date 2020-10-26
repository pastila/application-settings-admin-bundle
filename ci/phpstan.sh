#!/usr/bin/env bash

php -d memory_limit=256M vendor/bin/phpstan analyse src tests --level=1