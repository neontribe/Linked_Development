<?php

$sparql_prefixes = <<<PREFIX
    PREFIX rdf:     <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
    PREFIX rdfs:    <http://www.w3.org/2000/01/rdf-schema#>
    PREFIX skos:    <http://www.w3.org/2004/02/skos/core#>
    PREFIX owl:     <http://www.w3.org/2002/07/owl#>
    PREFIX bibo:    <http://purl.org/ontology/bibo/>
    PREFIX dcterms: <http://purl.org/dc/terms/>
    PREFIX foaf:    <http://xmlns.com/foaf/0.1/>
    PREFIX dbpedia: <http://dbpedia.org/ontology/>
    PREFIX dbprop:  <http://dbpedia.org/property/>
    PREFIX faogeo:  <http://www.fao.org/countryprofiles/geoinfo/geopolitical/resource/>
PREFIX;

return array(
    'endpoint' => 'http://localhost:8890/sparql',
    'rdf_cache' => 'cache/',
    'sparql_prefixes' => $sparql_prefixes,
);
