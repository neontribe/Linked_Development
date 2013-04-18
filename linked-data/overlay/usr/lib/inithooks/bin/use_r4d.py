"""
based on /usr/lib/inithooks/bin/setpass.py
Copyright (c) 2010 Alon Swartz <alon@turnkeylinux.org>

Copyright 2013 Neontribe ltd <neil@neontribe.co.uk>


get and install an initial set of 4rd data
put in place auto updates for it

"""

import os
import sys


def main():
    os.system('mkdir -p /home/r4d/rdf')
    os.system('echo http://r4d.graph.iri > /home/r4d/rdf/global.graph')
    os.system('touch /home/r4d/active')
    
    os.system('wget  http://www.dfid.gov.uk/r4d/rdf/R4DOutputsData.zip')
    os.system('mv R4DOutputsData.zip /home/r4d/rdf/')
    #unpack r4d data
    os.system('unzip /home/r4d/rdf/R4DOutputsData.zip -d /home/r4d/rdf/')
    os.system('rm -f /home/r4d/rdf/R4DOutputsData.zip')
    
    #open default conection to isql and run commands in a file
    #get virtuoso password
    fh = open('/etc/virtuoso-opensource-6.1/password', 'r')
    password = fh.read()
    fh.close()
    os.system("isql-vt 1111 dba " + password + " /opt/tools/r4d_load.isql")
    
    #so now look at add file to cron tab
    fh = open('/etc/cron.d/r4d', 'w')
    fh.write('0 0 * * 0 /usr/bin/python /opt/tools/r4d_update.py')
    fh.close()


if __name__ == "__main__":
    main()
