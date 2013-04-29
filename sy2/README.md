Linked Data API
===============

Set up an ubuntu dev host
-------------------------

I'm assuming you have a bash prompt on an ubuntu box (12.04 LTS or higher).  First install the required librires (I think this list is complete:

    apt-get install apache2 php5-curl php-pear php5-cli git git-flow curl acl php5-sqlite php-codesniffer php5-xdebug php-apc

When that's done install the PEAR:

    pear install --alldeps pear.phpunit.de/PHPUnit
    pear install --alldeps phpdocs
    pear install --alldeps XML_Serializer-0.20.2

Checkout the codebase to a suitable location:

    git clone git@github.com:neontribe/Linked_Development.git

Get composer, do this in the root of ther install:

    curl -s http://getcomposer.org/installer | php

Fetch the vendors files:

    php composer.phar update

Create your own parameters file.  Currently we are not using the DB so DB settings are not required:

    cd app/config
    ln -s parameters.default.yml parameters.yml
    cd -

Fix permisions, run the reset permission with help flag and follow instructions:

    sudo app/reset-permissions -h

That should be it.


Auto generate docs/coverage
---------------------------

    phpdoc -d src -t web/docs/ --parseprivate
    phpunit -c app --coverage-html=web/coverage
