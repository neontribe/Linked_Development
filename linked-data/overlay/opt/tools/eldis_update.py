"""
updates eldis data weekly

empties /home/eldis/rdf
get new files
makes new global.graph

imports into new graph in virtuoso

issues isql commands to remove eldis graph, then renames new one

"""
import os


def main():
    os.system('rm -rf /home/eldis/rdf/*')
    os.system('echo http://eldis.graph.iri.new > /home/eldis/rdf/global.graph')

    #start import of eldis data
    loop = 1
    os.system('python /opt/tools/eldis_crawl.py "http://api.ids.ac.uk/openapi/eldis/get_all/documents/full?num_results=1000" 1 /home/eldis/')

    next_url_fh = open('/home/eldis/nexturl','r')
    next_url = next_url_fh.read()
    next_url_fh.close()
    #loop while there are new urls to go to. see Eldis documentation as to why
    while next_url != "No more pages":
        loop += 1
        os.system('python /opt/tools/eldis_crawl.py "' + next_url + '" ' + str(loop) + ' /home/eldis/')
        next_url_fh = open('/home/eldis/nexturl','r')
        next_url = next_url_fh.read()
        next_url_fh.close()
        #safety
        if loop > 500:
            break
    #open default conection to isql and run commands in a file
    #get virtuoso password
    fh = open('/etc/virtuoso-opensource-6.1/password', 'w')
    password = fh.read()
    fh.close()
    os.system("isql-vt 1111 dba " + password + " /opt/tools/eldis_update.isql")

if __name__ == "__main__":
    main()
