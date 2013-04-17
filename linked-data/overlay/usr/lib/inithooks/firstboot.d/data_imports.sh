#!/bin/bash
#python /usr/lib/inithooks/bin/data_imports.py
/sbin/update_linked_data
function pause(){
   read -p "$*"
}
pause 'Press [Enter] key to continue...'
exit