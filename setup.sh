#!/bin/sh

cp config.php.sample config.php
chmod 777 ./application/cache
mkdir -p ./application/cache/templates_c
chmod 777 ./application/cache/templates_c
mkdir -p ./img/upload/image
chmod 777 ./img/upload/image
