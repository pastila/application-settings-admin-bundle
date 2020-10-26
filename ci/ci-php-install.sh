#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

apt-get update -yqq
apt-get install git zlib1g-dev unzip -yqq

docker-php-ext-install pdo pdo_mysql zip # add other extension needed