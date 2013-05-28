"""
NOTE - If refactoring use this version as it contains ORG namespace.
And includes improved fix_iri function


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
import gc
from eldis import Eldis

from rdflib.graph import Graph
from rdflib.namespace import Namespace, NamespaceManager
from rdflib.term import Literal, URIRef
from urlparse import urlparse, urlunparse


class Eldis_Orgs(Eldis):

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
            org_types_uri = self.BASE['themes/organisation_types']
            self.graph.add((org_types_uri,self.RDF['type'],self.SKOS['ConceptScheme']))
            
            for org in content['results']:
                org_uri = self.BASE['organisation/' + org['object_id'] +'/']

                self.graph.add((org_uri,self.RDF['type'],self.ORG['Organization']))
                self.graph.add((org_uri,self.RDF['type'],self.FOAF['Organization']))
                self.graph.add((org_uri,self.RDF['type'],self.DBPEDIA['Organisation']))
                self.graph.add((org_uri,self.RDF['type'],self.ORG['Organization']))
                                
                self.graph.add((org_uri,self.DCTERMS['identifier'],Literal(org['object_id'])))
                self.graph.add((org_uri,self.DCTERMS['title'],Literal(org['name'])))
                self.graph.add((org_uri,self.FOAF['name'],Literal(org['name'])))
                self.graph.add((org_uri,self.RDFS['label'],Literal(org['name'])))
                
                self.graph.add((org_uri,self.DCTERMS['created'],Literal(org['date_created'].replace(' ','T'))))
                
                try:
                    self.graph.add((org_uri,self.FOAF['nick'],Literal(org['acronym'])))
                except:
                    pass

                try:
                    self.graph.add((org_uri,self.DCTERMS['description'],Literal(org['description'])))
                except:
                    pass
                
                try:
                    address_uri = self.BASE['organisation/' + org['object_id'] +'/address']
                    self.graph.add((org_uri,self.VCARD['hasAddress'],address_uri))
                    self.graph.add((address_uri,self.RDF['type'],self.VCARD['Work']))
                    self.graph.add((address_uri,self.VCARD['country'],Literal(org['location_country'])))
                    self.graph.add((address_uri,self.VCARD['streetAddress'],Literal(org['address1'] + ", " + org['address2'])))
                    self.graph.add((address_uri,self.VCARD['locality'],Literal(org['address3'])))
                    self.graph.add((address_uri,self.VCARD['postalCode'],Literal(org['postcode'])))
                    
                except Exception as e:
                    pass
                
                try:
                    for category in org['category_theme_array']['theme']:
                         self.graph.add((org_uri,self.FOAF['topic_interest'],self.BASE['themes/' + category['object_id']]))
                except:
                    pass
                
                try:
                    for category in org['country_focus_array']['Country']:
                         self.graph.add((org_uri,self.FOAF['topic_interest'],self.BASE['geography/' + category['object_id']]))
                except:
                    pass
                
                #This section could be extended to use propper concept lists etc.
                try:
                    type_uri = self.BASE['themes/organisation_types/' + org['organisation_type_id']]
                    self.graph.add((org_uri,self.DCTERMS['type'],type_uri))
                    self.graph.add((type_uri,self.SKOS['prefLabel'],Literal(org['organisation_type'].strip())))
                    self.graph.add((type_uri,self.RDFS['label'],Literal(org['organisation_type'].strip())))
                    self.graph.add((type_uri,self.DCTERMS['identifier'],Literal(org['organisation_type_id'])))
                    self.graph.add((type_uri,self.RDF['type'],self.SKOS['Concept']))
                    self.graph.add((type_uri,self.SKOS['inConceptScheme'],org_types_uri))
                    self.graph.add((type_uri,self.SKOS['topConceptOf'],org_types_uri))
                    self.graph.add((org_types_uri,self.SKOS['hasTopConcept'],type_uri))
                except:
                    pass
                
                try:
                    self.graph.add((org_uri,self.FOAF['homepage'],URIRef(self.fix_iri(org['organisation_url']))))
                except Exception as e: 
                    pass



                self.graph.add((org_uri,self.RDFS['seeAlso'],URIRef(org['metadata_url'])))


            rdf = open(self.out_dir + 'rdf/' + self.database + '-orgs-' + date + '-' + str(self.loop) + '.rdf','w')
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
    data_url = "http://api.ids.ac.uk/openapi/eldis/get_all/organisations/full?num_results=1000"
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
    try:
        opts, args = getopt.gnu_getopt(sys.argv[1:], "", [])
    except getopt.GetoptError, e:
        usage(e)
    
    crawler = Eldis_Orgs(out_dir,
                  data_url,
                  loop)
    crawler.build_graph()
    
if __name__ == "__main__":
    main()
