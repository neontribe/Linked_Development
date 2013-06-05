#!/bin/bash

PWD=`pwd`
CWD=/home/tobias/tmp/Linked_Development/sy2
COMPOSER=/home/tobias/tmp/composer.phar
SYUSER=nobody
SYGROUP=nogroup
WEBUSER=www-data
WEBGROUP=www-data

# ensure system status
apt-get install -y acl apache2 php5-curl php-pear php5-cli git git-flow curl acl php5-sqlite php-apc libapache2-mod-php5

# Set up apache
## Turn allow override on
sed -i -r -e \
        '/Directory \/var\/www\// { n ; n ; s/AllowOverride None/AllowOverride All/ }' \
            /etc/apache2/sites-available/default
## enable rewiring
a2enmod rewrite
ln -s $CWD/web /var/www/htdocs/api

# API Setup
cd $CWD
# run the vendoe install (twice, it occasionally fails half way through the first run)
php $COMPOSER update
php $COMPOSER update

# clear caches/logs the agressive way
rm -rf /vagrant/sy2/app/{cache,logs}/*

# reset permissions
chown -R $SYUSER:$SYGROUP $CWD
chown -R $WEBUSER:$WEBGROUP $CWD/app/{logs,cache}
chmod -R 1775 $CWD/app/{logs,cache}
