#!/bin/bash
cd /workspaces/laravel-sdui
composer dump-autoload -o
./vendor/bin/phpunit --display-incomplete --display-skipped
