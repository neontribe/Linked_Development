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

ask if we should setup various data imports
then run those that are needed one at a time.

"""


from dialog_wrapper import Dialog
from use_eldis import main as eldis_setup
from use_r4d import main as r4d_setup


def main():
    
    #ensure virtuoso is using new ini file
    #os.system('service virtuoso-opensource-6.1 restart')
    
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
