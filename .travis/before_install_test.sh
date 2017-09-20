#!/usr/bin/env sh
set -ev

if [ "${TRAVIS_PHP_VERSION}" != "hhvm" ]; then
    PHP_INI_DIR="$HOME/.phpenv/versions/$(phpenv version-name)/etc/conf.d/"
    TRAVIS_INI_FILE="$PHP_INI_DIR/travis.ini"
    echo "memory_limit=3072M" >> "$TRAVIS_INI_FILE"

        if [ "$TRAVIS_PHP_VERSION" '<' '5.4' ]; then
        XDEBUG_INI_FILE="$PHP_INI_DIR/xdebug.ini"
        if [ -f  "$XDEBUG_INI_FILE" ]; then
            mv "$XDEBUG_INI_FILE" /tmp
        fi
    fi
    
    fi

sed --in-place "s/\"dev-master\":/\"dev-${TRAVIS_COMMIT}\":/" composer.json

