#!/bin/bash
python /usr/lib/inithooks/bin/data_imports.py
function pause(){
   read -p "$*"
}
pause 'Press [Enter] key to continue...'