#!/bin/sh

cp config.php.sample config.php
chmod -R 777 ./application/cache
chmod -R 777 ./public_data
