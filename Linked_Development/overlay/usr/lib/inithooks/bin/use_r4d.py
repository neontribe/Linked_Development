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

get and install an initial set of r4d data
put in place auto updates for it

"""

import os

def main():
    os.system('/bin/mkdir -p /home/r4d/rdf')
    os.system('/bin/echo http://linked-development.org/r4d/ > /home/r4d/rdf/global.graph')
    os.system('/usr/bin/touch /home/r4d/active')
    
    os.system('/usr/bin/wget  http://www.dfid.gov.uk/r4d/rdf/R4DOutputsData.zip')
    os.system('/bin/mv R4DOutputsData.zip /home/r4d/rdf/')
    #unpack r4d data
    os.system('/usr/bin/unzip /home/r4d/rdf/R4DOutputsData.zip -d /home/r4d/rdf/')
    os.system('/bin/rm -f /home/r4d/rdf/R4DOutputsData.zip')
    #add data to triple store
    os.system("/opt/tools/call_isql /opt/tools/r4d_load.isql")
    
    #so now look at add file to cron tab
    fh = open('/etc/cron.d/r4d', 'w')
    fh.write('0 1 * * 0 root /bin/bash /root/.profile;/usr/bin/python /opt/tools/r4d_update.py\n')
    fh.close()


if __name__ == "__main__":
    main()
