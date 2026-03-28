#!/bin/sh
# Check that PHP-FPM is listening on TCP 9000
php -r "exit(@fsockopen('127.0.0.1', 9000) ? 0 : 1);"
