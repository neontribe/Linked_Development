Linked-Development
==================

Neontribe are developing a Turnkey patch to install infrastructure and scripts to import data from various sources.  The system is intended to allow an organisation to expose information about research projects as Linked Data.

A Turnkey patch compromises the following structure:

patch-name/  
patch-name/debs/  
patch-name/overlay/  
patch-name/conf/functions  
patch-name/conf/post-debs  
patch-name/conf/post-overlay  
patch-name/conf/pre-debs  
patch-name/conf/pre-overlay  

Once these patches have been complete they can be applied to the "Turnkey Core" to produce installation media.

$ wget http://downloads.sourceforge.net/project/turnkeylinux/iso/turnkey-core-12.0-squeeze-x86.iso  
$ git clone https://github.com/neontribe/Linked-Data.git  
$ tklpatch turnkey-core-12.0-squeeze-x86.iso Linked-Data/linked-data/  

Should result in you having a turnkey-core-12.0-squeeze-x86-patched.iso.  

NB: the patch is currently being tested against turnkey-core-13.0rc-wheezy-i386 ready for the wheezy debian release.  

the linked-data server can be reconfigured as root running update_linked_data from the command line  
once running, the user 'linked-data' is created with ssh access. 

look in linked-data/conf/pre-overlay for a list of installed packages.  

## Laravel
==========

The laravel install sits in the Laravel folder.  To set it up cd into the laravel root folder and run composer:

    curl -sS https://getcomposer.org/installer | php
    php composer.phar install

Set the permissions:

    sudo chmod -R www-data:www-data app/storage

Create/link the correct enviroment:

    cd app/config
    ln -s sparql.`hostname`.php sparql.php

And that should be it.  More as I get more foo with laravel
