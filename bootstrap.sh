#!/usr/bin/env bash

echo "nameserver 8.8.8.8" | sudo tee /etc/resolv.conf
echo "nameserver 8.8.8.8" | sudo tee /etc/resolvconf/resolv.conf.d/base

apt-get update
apt-get install -y acl apache2 php5-curl php-pear php5-cli git git-flow curl acl php5-sqlite php-codesniffer php5-xdebug php-apc php-pear libapache2-mod-php5
sed -i 's/errors=remount-ro/errors=remount-ro,acl/g' /etc/fstab
mount -o remount /

pear config-set auto_discover 1
pear install --alldeps pear.phpunit.de/PHPUnit
pear install --alldeps phpdocs
pear install --alldeps XML_Serializer-0.20.2

rm -rf /var/www
ln -fs /vagrant/sy2/web /var/www

a2enmod rewrite
sed -i -e '/export APACHE_RUN_USER=vagrant/export APACHE_RUN_USER=vagrant/' /etc/apache2/envvars
sed -i -e '/export APACHE_RUN_GROUP=vagrant/export APACHE_RUN_GROUP=vagrant/' /etc/apache2/envvars
sed -i -r -e '/Directory \/var\/www\// { n ; n ; s/AllowOverride None/AllowOverride All/ }' /etc/apache2/sites-available/default
/etc/init.d/apache2 restart

# get composer
cd /vagrant/sy2
curl -s http://getcomposer.org/installer | php

# run the vendoe install (twice, it occasionally fails half way through the first run)
php composer.phar update
php composer.phar update

cd app/config
ln -sf parameters.default.yml parameters.yml
cd -

# ln -s /var/logs/apache2/error.log /vagrant/sy2/app/logs/apache2_error.log
