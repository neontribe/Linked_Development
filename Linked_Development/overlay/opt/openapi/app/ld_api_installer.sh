#!/bin/bash

#PWD=`pwd`
#CWD=`dirname $0`/..
#COMPOSER=$CWD/app/composer.phar
#SYUSER=nobody
#SYGROUP=nogroup
#WEBUSER=www-data
#WEBGROUP=www-data

# ensure system status
# now integrated in Linked_Development/Linked_Development/conf/pre-overlay - AB
# apt-get install -y apache2 php5-curl php-pear php5-cli curl php5-sqlite php-apc libapache2-mod-php5

# Set up apache
## Turn allow override on
# dont turn it on - we integrate .htaccess into the webserver rules
#sed -i -r -e \
#        '/Directory \/var\/www\// { n ; n ; s/AllowOverride None/AllowOverride All/ }' \
#            /etc/apache2/sites-available/default

## enable rewiring
# rewrite already enabled
#a2enmod rewrite

#ln -s /opt/openapi/web /var/www/htdocs/openapi

# API Setup
cd $CWD
# run the vendor install (twice, it occasionally fails half way through the first run)
#php $COMPOSER update
#php $COMPOSER update

# clear caches/logs the agressive way
#rm -rf $CWD/app/{cache,logs}/*

# reset permissions
#chown -R $SYUSER:$SYGROUP $CWD
#chown -R $WEBUSER:$WEBGROUP $CWD/app/{logs,cache}
#chmod -R 1775 $CWD/app/{logs,cache}

#cd $PWD
