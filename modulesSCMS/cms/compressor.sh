#!/bin/bash

# to run:
#
# ./compressor.sh
# rm -f compressor*
# rm -f find_php_files.sh



# list all PHP files in project
PHP_FILES=$(./find_php_files.sh)

for file in $PHP_FILES; do

    # compress FILE.php and rename to FILE.php.NEW
    php ./compressor.php $file > $file.NEW;

    # delete FILE.php and rename FILE.php.NEW to FILE.php
    rm -f $file;

    mv $file.NEW $file
done;





