#!/bin/bash
#turn virtuoso on to be able to insert data
service  virtuoso-opensource-6.1 start &&
isql-vt 1111 dba dba /opt/tools/eldis_load.isql 
function pause(){
   read -p "$*"
}
pause 'Press [Enter] key to continue...'