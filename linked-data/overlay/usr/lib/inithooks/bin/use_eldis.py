"""
Copyright 2013 Neontribe ltd <neil@neontribe.co.uk>

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


get and install an initial set of eldis data
put in place auto updates for it

"""

import os


def main():
    os.system('/bin/mkdir -p /home/eldis/rdf')
    os.system('/bin/echo http://linked-development.org/eldis/ > /home/eldis/rdf/global.graph')
    os.system('/usr/bin/touch /home/eldis/active')
    
    #start import of eldis data
    loop = 1
    os.system('/usr/bin/python /opt/tools/eldis_crawl.py "http://api.ids.ac.uk/openapi/eldis/get_all/documents/full?num_results=1000" 1 /home/eldis/')

    next_url_fh = open('/home/eldis/nexturl','r')
    next_url = next_url_fh.read()
    next_url_fh.close()
    #loop while there are new urls to go to. see Eldis documentation as to why
    while next_url != "No more pages":
        loop += 1
        os.system('/usr/bin/python /opt/tools/eldis_crawl.py "' + next_url + '" ' + str(loop) + ' /home/eldis/')
        next_url_fh = open('/home/eldis/nexturl','r')
        next_url = next_url_fh.read()
        next_url_fh.close()
        #safety
        if loop > 500:
            break
    #add data to triple store
    os.system("/opt/tools/call_isql /opt/tools/eldis_load.isql")
    
    #so now look at add file to cron tab
    fh = open('/etc/cron.d/eldis', 'w')
    fh.write('0 0 * * 0 root /usr/bin/python /opt/tools/eldis_update.py')
    fh.close()


if __name__ == "__main__":
    main()
