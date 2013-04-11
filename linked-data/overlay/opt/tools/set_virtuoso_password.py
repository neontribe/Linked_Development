"""
based on /usr/lib/inithooks/bin/setpass.py
Copyright (c) 2010 Alon Swartz <alon@turnkeylinux.org>

Copyright 2013 Neontribe ltd <neil@neontribe.co.uk>

set virtuoso passwords in a way that looks like other turnkey linux firstboot stuff

"""

import os
import sys
import getopt
import subprocess
from subprocess import PIPE

from dialog_wrapper import Dialog

def fatal(s):
    print >> sys.stderr, "Error:", s
    sys.exit(1)

def usage(e=None):
    if e:
        print >> sys.stderr, "Error:", e
    print >> sys.stderr, "Syntax: %s" % sys.argv[0]
    print >> sys.stderr, __doc__
    sys.exit(1)

def main():
    try:
        #note none used at present
        opts, args = getopt.gnu_getopt(sys.argv[1:], "hp:", ['help', 'pass='])
    except getopt.GetoptError, e:
        usage(e)


    d = Dialog('TurnKey Linux - First boot configuration')
    password = d.get_password(
        "",
        "Please enter new password for the virtuoso.")
    
    try:
        #if password is a number it needs to be a double quoted string
        float(password)
        qpassword = '"' + password + '"'
    except:
        qpassword = password
    f = open('setpass', 'w')
    f.write('set password dba ' + qpassword + ';')
    f.write("update DB.DBA.SYS_USERS set U_PASSWORD='" + password + "' where U_NAME='dav' ;")
    f.close()
    
    #open default conection to isql and run commands in a file
    command = "isql-vt 1111 dba dba /usr/lib/inithooks/firstboot.d/setpass"
    os.system(command)
    os.system('rm -f /usr/lib/inithooks/firstboot.d/setpass')


if __name__ == "__main__":
    main()
