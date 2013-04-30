#!/usr/bin/env bash

apt-get update
apt-get install -y apache2 php5-curl php-pear php5-cli git git-flow curl acl php5-sqlite php-codesniffer php5-xdebug php-apc php-pear git
pear install --alldeps pear.phpunit.de/PHPUnit
pear install --alldeps phpdocs
pear install --alldeps XML_Serializer-0.20.2

# /usr/bin/git clone git@github.com:neontribe/Linked_Development.git /vagrant/sy2

rm -rf /var/www
ln -fs /vagrant/sy2/web /var/www
