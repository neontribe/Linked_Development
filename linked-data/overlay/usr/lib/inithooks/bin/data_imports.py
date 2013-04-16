"""
based on /usr/lib/inithooks/bin/setpass.py
Copyright (c) 2010 Alon Swartz <alon@turnkeylinux.org>

Copyright 2013 Neontribe ltd <neil@neontribe.co.uk>

ask if we should setup various data imports
then run those that are needed one at a time.

"""

import os
import sys
import getopt
import subprocess

from dialog_wrapper import Dialog
from subprocess import PIPE
from use_eldis import main as eldis_setup
from use_r4d import main as r4d_setup


def main():
    
    #ensure virtuoso is using new ini file
    os.system('service virtuoso-opensource-6.1 restart')
    
    d = Dialog('TurnKey Linux - First boot configuration')
    eldis = d.yesno(
        "ELDIS data",
        "Mirror ELDIS data on this server, this will take some time.")
    
    r4d = d.yesno(
        "R4D data",
        "Mirror R4D data on this server, this will take some time.")
    
    if eldis:
        eldis_setup()
        
    if r4d:
        r4d_setup()
        


if __name__ == "__main__":
    main()
