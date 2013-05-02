#!/usr/bin/env bash

echo "nameserver 8.8.8.8" | sudo tee /etc/resolv.conf
echo "nameserver 8.8.8.8" | sudo tee /etc/resolvconf/resolv.conf.d/base

apt-get update
apt-get install -y acl apache2 php5-curl php-pear php5-cli git git-flow curl acl php5-sqlite php-codesniffer php5-xdebug php-apc php-pear libapache2-mod-php5 ccze
sed -i 's/errors=remount-ro/errors=remount-ro,acl/g' /etc/fstab
mount -o remount /

pear config-set auto_discover 1
pear install --alldeps pear.phpunit.de/PHPUnit
pear install --alldeps phpdocs
pear install --alldeps XML_Serializer-0.20.2

##########
# APACHE #
##########

# Set apache root to be symfony
/etc/init.d/apache2 stop
rm /var/log/apache/*
rm -rf /var/www
ln -fs /vagrant/sy2/web /var/www
# Set up apache to run as vagrant:vagrant
sed -i -e 's/www-data/vagrant/g' /etc/apache2/envvars
# Turn allow override on
sed -i -r -e \
    '/Directory \/var\/www\// { n ; n ; s/AllowOverride None/AllowOverride All/ }' \
    /etc/apache2/sites-available/default
# enable rewiring
a2enmod rewrite
chown vagrant:vagrant /var/lock/apache2
# This is a security risk.  It should only ever be used on dev boxes
echo 'umask 002' >> /etc/apache2/envvars
/etc/init.d/apache2 start

###########
# Symfony #
###########

# get composer
cd /vagrant/sy2
curl -s http://getcomposer.org/installer | php

# run the vendoe install (twice, it occasionally fails half way through the first run)
php composer.phar update
php composer.phar update

rm -rf /vagrant/sy2/app/cache/*
