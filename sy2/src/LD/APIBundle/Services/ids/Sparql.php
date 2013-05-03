<?php
namespace LD\APIBundle\Services\ids;

class Sparql
{
    protected $endpoint = 'http://public5.neontribe.co.uk/ld/eldis/sparql';
    /*
     * PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
     *
     * select distinct * where {?a rdf:type <http://purl.org/ontology/bibo/Article>} limit 10
     *
     * Works
     */

    /*
     * PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
     * PREFIX SKOS: <http://www.w3.org/2004/02/skos/core#>
     * 
     * select * where {?a SKOS:Concept ?c} limit 10
     *
     * Fails (0 results)
     */

    /*
     * PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
     * PREFIX SKOS: <http://www.w3.org/2004/02/skos/core#>
     * select * where {?a ?b <http://www.w3.org/2004/02/skos/core#Concept>}
     *
     * Return (all?) themes
     *
     * PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
     * PREFIX SKOS: <http://www.w3.org/2004/02/skos/core#>
     * select * where {?a ?b <http://www.w3.org/2004/02/skos/core#Concept>}
     * ORDER BY DESC(?a)
     * LIMIT 10
     * OFFSET 10
     */

    public function getAllThemes($offset = 0, $limit = 10)
    {
        $spql = array(
            'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>',
            'PREFIX SKOS: <http://www.w3.org/2004/02/skos/core#>',
            'select * where {?a ?b <http://www.w3.org/2004/02/skos/core#Concept>}',
            'ORDER BY DESC(?a) LIMIT 10 OFFSET 10',
        );

        $params = array(
            'default-graph-uri' => '',
            'query' => implode("\n", $spql),
            'format' => 'application/sparql-results+json',
        );

        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $this->endpoint . '?' . http_build_query($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        return json_decode(curl_exec($curl), TRUE);
    }
}
