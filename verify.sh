#!/bin/bash

set -e

composer install -q
echo "###################"
echo "#      TESTS      #"
echo "###################"
vendor/bin/phpunit || true
echo "###################"
echo "#   CODE SNIFFER  #"
echo "###################"
vendor/bin/phpcs --standard=PSR2 -p src tests || true
