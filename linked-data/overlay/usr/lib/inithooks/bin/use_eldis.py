"""
based on /usr/lib/inithooks/bin/setpass.py
Copyright (c) 2010 Alon Swartz <alon@turnkeylinux.org>

Copyright 2013 Neontribe ltd <neil@neontribe.co.uk>

ask if we should setup eldis data

get and install an initial set of data
put in place auto updates for it

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
    eldis = d.yesno(
        "ELDIS data",
        "Mirror ELDIS data on this server, this will take some time.")
    
    if eldis:
        os.system('mkdir -p /home/eldis/rdf')
        os.system('echo http://eldis.graph.iri > /home/eldis/rdf/global.graph')
        os.system('touch /home/eldis/active')
        #launch the python crawler
        os.system('ln -s /opt/tools/eldis_crawl.py /usr/lib/inithooks/bin/')
        #start import of eldis data
        loop = 1
        os.system('python /opt/tools/eldis_crawl.py "http://api.ids.ac.uk/openapi/eldis/get_all/documents/full?num_results=1000" 1 /home/eldis/')

        next_url_fh = open('/home/eldis/nexturl','r')
        next_url = next_url_fh.read()
        next_url_fh.close()
        #loop while there are new urls to go to. see Eldis documentation as to why
        while next_url != "No more pages":
            loop += 1
            os.system('python /opt/tools/eldis_crawl "' + next_url + '" ' + str(loop) + ' /home/eldis/')
            next_url_fh = open('/home/eldis/nexturl','r')
            next_url = next_url_fh.read()
            next_url_fh.close()
            #safety
            if loop > 500:
                break
        #open default conection to isql and run commands in a file
        os.system("service  virtuoso-opensource-6.1 start && isql-vt 1111 dba dba /opt/tools/eldis_load.isql")


if __name__ == "__main__":
    main()