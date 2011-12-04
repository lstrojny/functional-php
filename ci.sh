#!/bin/sh
if [ "$FUNCTIONAL" = "native" ]; then
    php -n -d extension=.libs/functional.so `which phpunit`
else
    php -n `which phpunit`
fi
