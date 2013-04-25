Linked_Development
==================

Linked_Development will deliver a prototype platform providing access to various international development related datasets as linked open data. By using common vocabularies, and making links between data from different providers, it can overcome organisational barriers and can provide seamless access to development knowledge from many different providers. 

The project has 3 stages:

(1) Server build

Creating a secure and fully functioning server with Virtuoso, Apache and other relevant tools, with any required documentation scripts to ensure that a repeat build can be easily deployed.

Neontribe are developing a Turnkey patch to install and configure this infrastructure.

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

=======
(2) Loading data

Neontribe are working with scripts provided by Tim Davies of Practical Participation to incorporate scripts to import data from various sources as part of the Turnkey patch.  The system is intended to allow an organisation to expose information about research projects as Linked Data.

(3) IDS API Clone

A number of applications are already built which work against the Institute for Development Studies API: http://api.ids.ac.uk/ 

This project aims to create a feature and output compatible version of this API, backed by its own triple store. The is that existing applications can be pointed to a wider range of research resources, making both IDS and ELDIS data accessible through a common API layer.

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
