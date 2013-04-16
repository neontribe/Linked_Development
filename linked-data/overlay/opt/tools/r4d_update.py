"""
copyright neontribe ltd 2013 neil@neontribe.co.uk

updates r4d data weekly

empties /home/r4d/rdf
get new files
makes new global.graph

imports into new graph in virtuoso

this is the old plan, currently everytime i try to rename a graph, virtuoso segfaults
issues isql commands to remove eldis graph, then renames new one

this is the new plan
clear rdf data
download new datestamped files
delete graph
import new graph.
"""

import os


def main():
    os.system('rm -rf /home/r4d/rdf/*')
    os.system('echo http://r4d.graph.iri > /home/r4d/rdf/global.graph')
    
    os.system('wget  http://www.dfid.gov.uk/r4d/rdf/R4DOutputsData.zip')
    os.system('mv R4DOutputsData.zip /home/r4d/rdf/R4DOutputsData.zip')
    #unpack r4d data
    os.system('unzip /home/r4d/rdf/R4DOutputsData.zip -d /home/r4d/rdf/')
    os.system('rm -f /home/r4d/rdf/R4DOutputsData.zip')
    #open default conection to isql and run commands in a file
    #get virtuoso password
    fh = open('/etc/virtuoso-opensource-6.1/password', 'r')
    password = fh.read()
    fh.close()
    os.system("isql-vt 1111 dba " + password + " /opt/tools/r4d_update.isql")


if __name__ == "__main__":
    main()
