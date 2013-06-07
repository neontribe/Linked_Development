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

global out_dir, script_dir
script_dir = "/opt/tools/eldis/" #Include trailing slash
out_dir = "/home/eldis"

def loop(script,url):
    #start import of eldis data
    loop = 1
    os.system('/usr/bin/python '+script_dir+script+'.py "'+url+'" 1 "'+out_dir +'"')

    next_url_fh = open(out_dir+'/nexturl','r')
    next_url = next_url_fh.read()
    next_url_fh.close()
    #loop while there are new urls to go to. see Eldis documentation as to why
    while next_url != "No more pages":
        loop += 1
        os.system('/usr/bin/python '+script_dir+script+'.py "' + next_url + '" ' + str(loop) + ' "'+out_dir +'"')
        next_url_fh = open(out_dir+'/nexturl','r')
        next_url = next_url_fh.read()
        next_url_fh.close()
        #safety
        if loop > 500:
            break

def main():
    os.system('/bin/rm -rf '+out_dir+'/rdf/*')
    os.system('/bin/echo http://linked-development.org/eldis/ > '+out_dir+'/rdf/global.graph')
    
    loop('eldis_crawl','http://api.ids.ac.uk/openapi/eldis/get_all/documents/full?num_results=1000')    
    loop('eldis_crawl_countries','http://api.ids.ac.uk/openapi/eldis/get_all/countries/full?num_results=1000')
    loop('eldis_crawl_orgs','http://api.ids.ac.uk/openapi/eldis/get_all/organisations/full?num_results=1000')
    loop('eldis_crawl_subjects','http://api.ids.ac.uk/openapi/eldis/get_all/themes/full?num_results=1000')
    
    #open default conection to isql and run commands in a file
    os.system("/opt/tools/call_isql "+script_dir+"eldis_update2.isql")


if __name__ == "__main__":
    main()

