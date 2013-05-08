<?php
namespace LD\APIBundle\Services\ids;

class Sparql
{
    /*
    SELECT DISTINCT ?class
    WHERE {
      ?s a ?class .
    }
    LIMIT 25
    OFFSET 0
     */

    // http://stackoverflow.com/questions/2930246/exploratory-sparql-queries

    protected $logger = null;
    protected $endpoint = null;

    public function __construct($endpoint, $logger)
    {
        $this->endpoint = $endpoint;
        $this->logger = $logger;
    }

    public function curl($spql)
    {
        $params = array(
            'default-graph-uri' => '',
            'query' => $spql,
            'format' => 'application/sparql-results+json',
        );

        $url = $this->endpoint . '?' . http_build_query($params);
        $this->logger->info('Hitting Virtuoso: ' . $url);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($curl);
        $this->logger->debug($response);

        return json_decode($response, TRUE);
    }
/*
    public function getAllThemes($limit = 10, $offset = 0)
    {
        $spql = array(
            'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>',
            'PREFIX SKOS: <http://www.w3.org/2004/02/skos/core#>',
            'select * where {?a ?b <http://www.w3.org/2004/02/skos/core#Concept>}',
            sprintf('ORDER BY DESC(?a) LIMIT %d OFFSET %d', $limit, $offset),
        );

        return json_decode($this->curl($spql), TRUE);
    }

    public function getAllDocuments($limit = 10, $offset = 0)
    {
        $spql = array(
            'PREFIX BIBO: <http://purl.org/ontology/bibo/>',
            'select * where {?a ?b BIBO:Article}',
            sprintf('ORDER BY DESC(?a) LIMIT %d OFFSET %d', $limit, $offset),
        );

        return json_decode($this->curl($spql), TRUE);
    }

    public function getAllCountries($limit = 10, $offset = 0)
    {
        $spql = array(
            'select distinct ?country ?countrycode where {?a <http://purl.org/dc/terms/coverage> ?country .',
            '?country <http://www.fao.org/countryprofiles/geoinfo/geopolitical/resource/codeISO2> ?countrycode .',
            '}',
        );

        return json_decode($this->curl($spql), TRUE);
    }
 */
}
