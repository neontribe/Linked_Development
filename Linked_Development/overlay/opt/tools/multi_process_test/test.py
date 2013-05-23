# See http://pastebin.com/zf3ndUiT

from multiprocessing import Process, Queue
import json
import urllib2


def worker(data_url,queue):
    data_url = queue.get()

    req = urllib2.Request(data_url)
    req.add_header('Accept', 'application/json')
    req.add_header('Token-Guid', "c2ee7827-83de-4c99-b336-dbe73d340874")
    try:
        print "Fetching " + data_url
        resp = urllib2.urlopen(req)
        content = json.loads(resp.read())
    except Exception as inst:
        print inst
        print "ERROR fetching" + data_url
    
    if(content['metadata']['next_page']):
        print content['metadata']['next_page']
        queue.put(content['metadata']['next_page'])
    else:
        print "Build complete"
    
    return

if __name__ == '__main__':
    jobs = []
    q = Queue()
    q.put("http://api.ids.ac.uk/openapi/"+'eldis'+"/get_all/organisations/full?num_results=100")
    p = Process(target=worker, args=("http://api.ids.ac.uk/openapi/"+'eldis'+"/get_all/organisations/full?num_results=100",q,))
    p.start()
    p.join()