#!/usr/bin/env sh
set -ev

if [ "${TRAVIS_PHP_VERSION}" != "hhvm" ]; then
    mv "$HOME/.phpenv/versions/$(phpenv version-name)/etc/conf.d/xdebug.ini" /tmp
    echo "memory_limit=3072M" >> ~/.phpenv/versions/$(phpenv version-name)/etc/conf.d/travis.ini

    if [ ${TRAVIS_PHP_VERSION} '<' '7.0' ]; then
        echo "extension=mongo.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/conf.d/travis.ini
    fi
fi

# To be removed when following PR will be merged: https://github.com/travis-ci/travis-build/pull/718
composer self-update --stable
sed --in-place "s/\"dev-master\":/\"dev-${TRAVIS_COMMIT}\":/" composer.json

