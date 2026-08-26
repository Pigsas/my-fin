#!/usr/bin/env bash

if [ -z "$1" ]; then
    echo "No Remote IP provided. xDebug not configured"
    echo ""
    echo "Usage: $0 IP_OF_HOST_WITH_PHP_STORM"
    exit 1
fi

echo "
zend_extension=xdebug
xdebug.client_host=host.docker.internal
xdebug.start_with_request=yes
xdebug.mode=debug,coverage,gcstats,trace
xdebug.output_dir = /code/.docker/xdebug-data
" > /usr/local/etc/php/conf.d/custom-xdebug.ini


echo "xDebug enabled"

kill -USR2 1
