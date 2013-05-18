"""
copyright 2013 neontribe ltd neil@neontribe.co.uk
based on a script provided by Tim Davies


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

takes three arguments, the first the data_url to query (quoted) 
the second is a loop number
the third is an output directory

eg python eldis_crawl.py "http://www.getdata.com" 0 /home/neil/getdata
"""

#Crawler for Bridge / ELDIS category data

import datetime
import getopt
import json
import os
import sys
import urllib2

from rdflib.graph import Graph
from rdflib.namespace import Namespace, NamespaceManager
from rdflib.term import Literal, URIRef
from urlparse import urlparse, urlunparse


class Eldis(object):

    database = 'eldis'
    token = 'c2ee7827-83de-4c99-b336-dbe73d340874'
    
    RDF = Namespace("http://www.w3.org/1999/02/22-rdf-syntax-ns#")
    RDFS = Namespace("http://www.w3.org/2000/01/rdf-schema#")
    OWL = Namespace("http://www.w3.org/2002/07/owl#")
    DC = Namespace("http://purl.org/dc/elements/1.1/")
    DCTERMS = Namespace("http://purl.org/dc/terms/")
    DBPEDIA = Namespace("http://dbpedia.org/ontology/")
    DBPROP = Namespace("http://dbpedia.org/property/")
    DBRES = Namespace("http://dbpedia.org/resource/")
    FAO = Namespace("http://www.fao.org/countryprofiles/geoinfo/geopolitical/resource/")
    IATI = Namespace("http://tools.aidinfolabs.org/linked-iati/def/iati-1.01#")
    FOAF = Namespace("http://xmlns.com/foaf/0.1/")
    SKOS = Namespace("http://www.w3.org/2004/02/skos/core#")
    BIBO = Namespace("http://purl.org/ontology/bibo/")
    BASE = Namespace("http://linked-development.org/"+database +"/")

    
    def __init__(self, out_dir='/home/eldis/', data_url=None, loop=1):
        self.graph = Graph()
        self.graph.namespace_manager.bind('owl', self.OWL, override=False)
        self.graph.namespace_manager.bind('dc', self.DC, override=False)
        self.graph.namespace_manager.bind('dcterms', self.DCTERMS, override=False)
        self.graph.namespace_manager.bind('dbpedia', self.DBPEDIA, override=False)
        self.graph.namespace_manager.bind('dbprop', self.DBPROP, override=False)
        self.graph.namespace_manager.bind('dbres', self.DBRES, override=False)
        self.graph.namespace_manager.bind('fao', self.FAO, override=False)
        self.graph.namespace_manager.bind('iati', self.IATI, override=False)
        self.graph.namespace_manager.bind('foaf', self.FOAF, override=False)
        self.graph.namespace_manager.bind('skos', self.SKOS, override=False)
        self.graph.namespace_manager.bind('bibo', self.BIBO, override=False)
        self.graph.namespace_manager.bind('base', self.BASE, override=False)
        self.out_dir = out_dir
        if data_url:
            self.data_url = data_url
        else:
            contfile = open(outdir + 'nexturl', 'r')
            data_url = contfile.readline()
            contfile.close()
        self.loop = loop
        
    def dbpedia_url(self, string):
        string = string[0].upper() + string[1:].lower()
        string = string.replace(" ","_")
        return string
    
    def fetch_data(self, data_url):
        req = urllib2.Request(data_url)
        req.add_header('Accept', 'application/json')
        req.add_header('Token-Guid', self.token)
        try:
            resp = urllib2.urlopen(req)
            content = json.loads(resp.read())
        except Exception as inst:
            print inst
            print "ERROR fetching" + data_url
        return content
    
    # Replace [ and ] if they occur in the path, query or fragment
    def fix_iri(self, url):
        urlobj = urlparse(url)
        path = urlobj.path.replace('[',"%5B").replace(']',"%5D")
        query = urlobj.query.replace('[',"%5B").replace(']',"%5D")
        fragment = urlobj.fragment.replace('[',"%5B").replace(']',"%5D")
        return url.replace(urlobj.path,path).replace(urlobj.query,query).replace(urlobj.fragment,fragment)
    
    def build_graph(self):
        """
        ok this is fun.
        using rdflib 3.4, none of these commands
        self.graph.remove((None,None,None))
        self.grpah.close()
        self.graph = None
        self.graph = Graph()
        
        free the ram used, they all make empty graphs, so if we iterate
        over reading in files to graphs our memory usage spirals. on 2013/04/12 
        the memory usage for  http://api.ids.ac.uk/openapi/"+eldis.database+"/get_all/documents/full
        in 1000 record chunks was 1.5G, if that memory is not available then the process is KILLED
        
        I cannot find a way to free this from inside python have looked at gc module, I suspect this
        may lie in some underlieing code.
        
        the current fix will to to write out to a file either a follow up url or 'No more pages', 
        and take this as the input, and run a loop from outside this code to spawn a series
        of python processes so the memory is always freed when the process ends.
        
        file names have a datestamp in them because virtuoso by default does not import the same
        file twice. So without this updates will not be read.
        
        """
        date = datetime.date.today().isoformat()
        print "Reading "+self.data_url
        content = self.fetch_data(self.data_url)
        try:
            scheme_uri = self.BASE['geography/']
            self.graph.add((scheme_uri,self.RDF['type'],self.SKOS['ConceptScheme']))
            self.graph.add((scheme_uri,self.RDFS['label'],Literal('IDS Country Taxonomy')))
            
            for country in content['results']:
                                
                uri = self.BASE['geography/' + country['object_id'] +'/']
                self.graph.add((uri,self.RDF['type'],self.SKOS['Concept']))
                self.graph.add((uri,self.SKOS['inScheme'],scheme_uri))
                
                self.graph.add((uri,self.RDFS['label'],Literal(country['country_name'],lang="en"))) 
                self.graph.add((uri,self.SKOS['prefLabel'],Literal(country['country_name'],lang="en"))) 
                self.graph.add((uri,self.SKOS['altLabel'],Literal(country['alternative_name'],lang="en"))) 
                
                self.graph.add((uri,self.FAO['codeISO2'],Literal(country['iso_two_letter_code']))) 
                self.graph.add((uri,self.FAO['codeISO3'],Literal(country['iso_three_letter_code']))) 

                self.graph.add((uri,self.RDFS['seeAlso'],URIRef(country['metadata_url'])))
                self.graph.add((uri,self.OWL['sameAs'],self.DBRES[self.dbpedia_url(country['country_name'])]))
                self.graph.add((uri,self.OWL['sameAs'],self.FAO[self.dbpedia_url(country['country_name'])]))
                
                self.graph.add((uri,self.DCTERMS['identifier'],Literal(org['object_id'])))
                
                
                try:
                    for region in country['category_region_array']['Region']:
                        region_uri = self.BASE['geography/' + region['object_id'] +'/']
                        self.graph.add((region_uri,self.RDF['type'],self.SKOS['Concept']))
                        self.graph.add((region_uri,self.SKOS['inScheme'],scheme_uri))
                        self.graph.add((region_uri,self.SKOS['topConceptOf'],scheme_uri))
                        self.graph.add((scheme_uri,self.SKOS['hasTopConcept'],region_uri))
                                                
                        self.graph.add((region_uri,self.SKOS['prefLabel'],Literal(region['object_name'])))
                        self.graph.add((region_uri,self.RDFS['label'],Literal(region['object_name'])))
                        
                        self.graph.add((region_uri,self.SKOS['narrower'],uri))
                        self.graph.add((uri,self.SKOS['broader'],region_uri))
                        
                        self.graph.add((region_uri,self.DCTERMS['identifier'],Literal(region['object_id'])))
                        
                        self.graph.add((region_uri,self.RDFS['seeAlso'],URIRef(region['metadata_url'])))
                        
                except Exception as e:
                    pass 


            rdf = open(self.out_dir + 'rdf/' + self.database + '-country-' + date + '-' + str(self.loop) + '.rdf','w')
            rdf.write(self.graph.serialize())
            rdf.close()
            #no longer needed
            #self.graph.remove((None,None,None))
            
            contfile = open(self.out_dir + 'nexturl', 'w')
            try:
                if(content['metadata']['next_page']):
                    contfile.write(content['metadata']['next_page'])
                    print str(int(content['metadata']['total_results']) - int(content['metadata']['start_offset'])) + " records remaining"
                    #self.build_graph(content['metadata']['next_page'],n+1)
                else:
                    print "Build complete"
            except:
                contfile.write("No more pages")
                print "No more pages"
            contfile.close()
        except Exception as inst:
            print inst
            print "Failed to read "+ self.data_url

def usage(e=None):
    if e:
        print >> sys.stderr, "Error:", e
    print >> sys.stderr, "Syntax: %s" % sys.argv[0]
    print >> sys.stderr, __doc__
    sys.exit(1)

def main():
    try:
        opts, args = getopt.gnu_getopt(sys.argv[1:], "", [])
    except getopt.GetoptError, e:
        usage(e)
    data_url = "http://api.ids.ac.uk/openapi/"+'eldis'+"/get_all/countries/full?num_results=1000"
    loop = 0
    out_dir='/home/neil/eldis/'
        
    if len(args) > 0:
        data_url = args[0]
    if len(args) > 1:
        loop = args[1]
    if len(args) == 3:
        out_dir = args[2]
    if not out_dir[-1:] == os.sep:
        out_dir = out_dir + os.sep
    eldis = Eldis(out_dir,
                  data_url,
                  loop)
    eldis.build_graph()

if __name__ == "__main__":
    main()
