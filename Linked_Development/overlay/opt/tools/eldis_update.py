"""
copyright neontribe ltd 2013 neil@neontribe.co.uk

updates eldis data weekly

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
    
empties /home/eldis/rdf
get new files
makes new global.graph

imports into database

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
    os.system('/bin/rm -rf /home/eldis/rdf/*')
    os.system('/bin/echo http://linked-development.org/eldis/ > /home/eldis/rdf/global.graph')
    
    #start import of eldis data
    loop = 1
    os.system('/usr/bin/python /opt/tools/eldis_crawl.py "http://api.ids.ac.uk/openapi/eldis/get_all/documents/full?num_results=1000" 1 /home/eldis/')

    next_url_fh = open('/home/eldis/nexturl','r')
    next_url = next_url_fh.read()
    next_url_fh.close()
    #loop while there are new urls to go to. see Eldis documentation as to why
    while next_url != "No more pages":
        loop += 1
        os.system('/use/bin/python /opt/tools/eldis_crawl.py "' + next_url + '" ' + str(loop) + ' /home/eldis/')
        next_url_fh = open('/home/eldis/nexturl','r')
        next_url = next_url_fh.read()
        next_url_fh.close()
        #safety
        if loop > 500:
            break
   #open default conection to isql and run commands in a file
   os.system("/opt/tools/call_isql /opt/tools/eldis_update2.isql")


if __name__ == "__main__":
    main()
