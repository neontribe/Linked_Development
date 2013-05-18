"""
copyright 2013 neontribe ltd neil@neontribe.co.uk; 
& practical participation ltd tim@practicalparticipation.co.uk

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

"""

#Crawler for the IDS Knowledge Services API: http://api.ids.ac.uk/docs/

import datetime
import getopt
import json
import os
import urllib2
import os, sys
sys.path.insert(1, os.path.join(sys.path[0], '..'))  #So that we can get at the configuration files

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
    ORG = Namespace("http://www.w3.org/ns/org#")
    VCARD = Namespace("http://www.w3.org/2006/vcard/ns#")
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
        self.graph.namespace_manager.bind('org', self.ORG, override=False)
        self.graph.namespace_manager.bind('vcard', self.VCARD, override=False)
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
    # Handle bad URLs (e.g. no domain component)
    def fix_iri(self, url):
        urlobj = urlparse(url)
        if urlobj.netloc:
            path = urlobj.path.replace('[',"%5B").replace(']',"%5D")
            query = urlobj.query.replace('[',"%5B").replace(']',"%5D")
            fragment = urlobj.fragment.replace('[',"%5B").replace(']',"%5D")
            return url.replace(urlobj.path,path).replace(urlobj.query,query).replace(urlobj.fragment,fragment)
        else:
            return null