#Crawler for Bridge / ELDIS
#Currently works in one big batch - but may be better to rework so that it outputs a file for every 100 records, and then to merge and upload those later...
import urllib2
import json
from rdflib.graph import Graph
from rdflib.namespace import Namespace, NamespaceManager
from rdflib.term import Literal, URIRef
from urlparse import urlparse, urlunparse

database = 'eldis'
token = 'c2ee7827-83de-4c99-b336-dbe73d340874'

def dbpedia_url(string):
    string = string[0].upper() + string[1:].lower()
    string = string.replace(" ","_")
    return string

def fetch_data(data_url):
    global token
    req = urllib2.Request(data_url)
    req.add_header('Accept', 'application/json')
    req.add_header('Token-Guid', token)
    try:
        resp = urllib2.urlopen(req)
        content = json.loads(resp.read())
    except Exception as inst:
	print inst
        print "ERROR fetching" + data_url
    return content

# Replace [ and ] if they occur in the path, query or fragment
def fix_iri(url):
    urlobj = urlparse(url)
    path = urlobj.path.replace('[',"%5B").replace(']',"%5D")
    query = urlobj.query.replace('[',"%5B").replace(']',"%5D")
    fragment = urlobj.fragment.replace('[',"%5B").replace(']',"%5D")
    return url.replace(urlobj.path,path).replace(urlobj.query,query).replace(urlobj.fragment,fragment)

g = Graph()


RDF = Namespace("http://www.w3.org/1999/02/22-rdf-syntax-ns#")
RDFS = Namespace("http://www.w3.org/2000/01/rdf-schema#")
OWL = Namespace("http://www.w3.org/2002/07/owl#")
g.namespace_manager.bind('owl', OWL, override=False)

DC = Namespace("http://purl.org/dc/elements/1.1/")
g.namespace_manager.bind('dc', DC, override=False)
DCTERMS = Namespace("http://purl.org/dc/terms/")
g.namespace_manager.bind('dcterms', DCTERMS, override=False)
DBPEDIA = Namespace("http://dbpedia.org/ontology/")
g.namespace_manager.bind('dbpedia', DBPEDIA, override=False)
DBPROP = Namespace("http://dbpedia.org/property/")
g.namespace_manager.bind('dbprop', DBPROP, override=False)
DBRES = Namespace("http://dbpedia.org/resource/")
g.namespace_manager.bind('dbres', DBRES, override=False)
FAO = Namespace("http://www.fao.org/countryprofiles/geoinfo/geopolitical/resource/")
g.namespace_manager.bind('fao', FAO, override=False)
IATI = Namespace("http://tools.aidinfolabs.org/linked-iati/def/iati-1.01#")
g.namespace_manager.bind('iati', IATI, override=False)
FOAF = Namespace("http://xmlns.com/foaf/0.1/")
g.namespace_manager.bind('foaf', FOAF, override=False)
SKOS = Namespace("http://www.w3.org/2004/02/skos/core#")
g.namespace_manager.bind('skos', SKOS, override=False)
BIBO = Namespace("http://purl.org/ontology/bibo/")
g.namespace_manager.bind('bibo', BIBO, override=False)
BASE = Namespace("http://linked-development.org/"+database +"/")
g.namespace_manager.bind('base', BASE, override=False)


def build_graph(data_url,n):
    print "Reading "+data_url
    global g
    content = fetch_data(data_url)
    try:
        for document in content['results']:
            uri = BASE['output/' + document['object_id'] +'/']
            g.add((uri,DCTERMS['title'],Literal(document['title'])))
            try:
                g.add((uri,DCTERMS['abstract'],Literal(document['description'])))
            except:
                pass
            g.add((uri,DCTERMS['type'],DCTERMS['Text']))
            g.add((uri,RDF['type'],BIBO['Article']))
            g.add((uri,DCTERMS['identifier'],URIRef(document['metadata_url'])))
            g.add((uri,DCTERMS['date'],Literal(document['publication_date'].replace(' ','T'))))
            g.add((uri,DCTERMS['language'],Literal(document['language_name'])))
            g.add((uri,RDFS['seeAlso'],URIRef(document['website_url'].replace('display&','display?'))))
    
            for author in document['author']:
                g.add((uri,DCTERMS['creator'],Literal(author)))

            try:
                for publisher in document['publisher_array']['Publisher']:
                    puburi = BASE['organisation/' + publisher['object_id'] +'/']
                    g.add((uri,DCTERMS['publisher'],puburi))
                    g.add((puburi,DCTERMS['title'],Literal(publisher['object_name'])))
                    g.add((puburi,FOAF['name'],Literal(publisher['object_name'])))
                    g.add((puburi,RDF['type'],DBPEDIA['Organisation']))
                    g.add((puburi,RDF['type'],FAO['organization']))
                    g.add((puburi,RDF['type'],FOAF['organization']))
                    # We could follow this URL to get more information on the organisation...
                    g.add((puburi,RDFS['seeAlso'],publisher['metadata_url']))         
            except:
                #This could be improved. Bridge and Eldis appear to differ on publisher values
                g.add((uri,DCTERMS['publisher'],Literal(document['publisher']))) 

            #ELDIS / BRIDGE Regions do not map onto FAO regions effectively. We could model containments in future...
            try:
                for region in document['category_region_array']['Region']:
                    regionuri = BASE['regions/' + region['object_id'] +'/']
                    g.add((uri,DCTERMS['coverage'],regionuri))
                    g.add((regionuri,RDFS['label'],Literal(region['object_name'])))            
            except:
                pass


            try:
                for country in document['country_focus_array']['Country']:
                    countryuri = BASE['countries/' + country['object_id'] +'/']
                    g.add((uri,DCTERMS['coverage'],countryuri))
                    g.add((countryuri,RDFS['label'],Literal(country['object_name']))) 
                    g.add((countryuri,FAO['codeISO2'],Literal(country['iso_two_letter_code']))) 
                    g.add((countryuri,RDFS['seeAlso'],URIRef(country['metadata_url'])))
                    g.add((countryuri,OWL['sameAs'],DBRES[country['object_name']]))
                    g.add((countryuri,OWL['sameAs'],FAO[country['object_name']]))
            except:
                pass
    

            try:
               for category in document['category_theme_array']['theme']:
                   themeuri = BASE['themes/' + category['object_id'] +'/']
                   g.add((uri,DCTERMS['subject'],themeuri))
                   g.add((themeuri,RDF['type'],SKOS['Concept']))
                   g.add((themeuri,RDFS['label'],Literal(category['object_name']))) 
                   g.add((themeuri,RDFS['seeAlso'],URIRef(category['metadata_url'])))
                   g.add((themeuri,OWL['sameAs'],dbpedia_url(DBRES[category['object_name']])))
            except:
                pass

            try:
               for document_url in document['urls']:
                   g.add((uri,BIBO['uri'],fix_iri(document_url)))
            except:
               pass
        f = open('/home/eldis/' + 'rdf/'+database+'-'+str(n)+'.rdf','w')
        f.write(g.serialize())
        f.close()
        g.remove((None,None,None))
    
        try:
            if(content['metadata']['next_page']):
                print str(int(content['metadata']['total_results']) - int(content['metadata']['start_offset'])) + " records remaining"
                build_graph(content['metadata']['next_page'],n+1)
            else:
                print "Build complete"
        except:
            print "No more pages"
    except Exception as inst:
        print inst
        print "Failed to read "+ data_url


build_graph("http://api.ids.ac.uk/openapi/"+database+"/get_all/documents/full?num_results=1000",1)


