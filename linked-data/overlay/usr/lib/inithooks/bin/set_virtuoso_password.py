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

    f = open('/usr/lib/inithooks/firstboot.d/setpass', 'w')
    isql = """set password "dba" \"""" + password + """\";
update DB.DBA.SYS_USERS set U_PASSWORD='""" + password + """' where U_NAME='dav';
exit;"""
    f.write(isql)
    f.close()
    
    #open default conection to isql and run commands in a file
    command = "isql-vt 1111 dba dba /usr/lib/inithooks/firstboot.d/setpass"
    os.system(command)
    os.system('rm -f /usr/lib/inithooks/firstboot.d/setpass')

    #we need to know the password for future use in scripts so we write it to a file
    fh = open('/etc/virtuoso-opensource-6.1/password', 'w')
    fh.write(password)
    fh.close()
    
    os.system("/opt/tools/call_isql /opt/tools/activate_vad.isql")

if __name__ == "__main__":
    main()
