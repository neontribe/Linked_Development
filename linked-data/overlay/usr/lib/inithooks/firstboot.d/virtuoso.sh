#!/bin/bash
#turn virtuoso on to be able to change passwords
service  virtuoso-opensource-6.1 restart &&
python /usr/lib/inithooks/bin/set_virtuoso_password.py 
function pause(){
   read -p "$*"
}
pause 'Press [Enter] key to continue...'
exit