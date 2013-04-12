#!/bin/bash
python /usr/lib/inithooks/bin/use_eldis.py 
function pause(){
   read -p "$*"
}
pause 'Press [Enter] key to continue...'