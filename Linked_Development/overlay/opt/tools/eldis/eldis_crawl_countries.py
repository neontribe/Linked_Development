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
from eldis import Eldis

from rdflib.graph import Graph
from rdflib.namespace import Namespace, NamespaceManager
from rdflib.term import Literal, URIRef
from urlparse import urlparse, urlunparse

class Eldis_Geo(Eldis):
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
                
                # This mapping is imperfect so we should add a mapping later based on ISO code lookup
                # self.graph.add((uri,self.OWL['sameAs'],self.FAO[self.dbpedia_url(country['country_name'])]))
                
                self.graph.add((uri,self.DCTERMS['identifier'],Literal(country['object_id'])))
                
                
                try:
                    for region in country['category_region_array']['Region']:
                        region_uri = self.BASE['geography/' + region['object_id'] +'/']
                        self.graph.add((region_uri,self.RDF['type'],self.SKOS['Concept']))
                        self.graph.add((region_uri,self.RDF['type'],self.FAO['geographical_region']))
                        
                        self.graph.add((region_uri,self.SKOS['inScheme'],scheme_uri))
                        self.graph.add((region_uri,self.SKOS['topConceptOf'],scheme_uri))
                        self.graph.add((scheme_uri,self.SKOS['hasTopConcept'],region_uri))
                                                
                        self.graph.add((region_uri,self.SKOS['prefLabel'],Literal(region['object_name'])))
                        self.graph.add((region_uri,self.RDFS['label'],Literal(region['object_name'])))
                        
                        self.graph.add((region_uri,self.SKOS['narrower'],uri))
                        
                        self.graph.add((uri,self.SKOS['broader'],region_uri))
                        self.graph.add((uri,self.FAO['isInGroup'],region_uri))
                        
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
    out_dir='/home/eldis/'
        
    if len(args) > 0:
        data_url = args[0]
    if len(args) > 1:
        loop = args[1]
    if len(args) == 3:
        out_dir = args[2]
    if not out_dir[-1:] == os.sep:
        out_dir = out_dir + os.sep
    crawler = Eldis_Geo(out_dir,
                  data_url,
                  loop)
    crawler.build_graph()

if __name__ == "__main__":
    main()
