#!/bin/bash
#./node_modules/pre-commit/hook

RESULT=$?
[ $RESULT -ne 0 ] && exit 1

hasPHP=`git status --porcelain | grep -E -i '\.php' | grep -v -i '\.blade\.php'`

# Lint PHP

if [ "$hasPHP" != "" ]; then
        echo "Starting PHPCS…"
        vendor/bin/phpcs -n --standard=PSR2 ./app/ ./bootstrap/ ./config/ ./resources/ ./tests/ --extensions=php --ignore=cache || exit 1
fi

exit 0

