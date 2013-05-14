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

    public function __construct($endpoint, $container)
    {
        $this->endpoint = $endpoint;
        $this->container = $container;
        $this->logger = $container->get('logger');
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

    /**
     * Easy RDF Query of the endpoint
     *
     * @param string $graph
     * @param array  $elements
     *
     * @return EasyRdf_Sparql_Result|EasyRdf_Graph
     */
    public function query($graph, array $elements)
    {
        // select * from >http://linked-development.org/eldis> where ...

        $query = $elements['select'];
        if ($graph && $graph != 'all') {
            $query .= "\nfrom <foooo>";
        }
        $query .= "\n";
        $query .= $elements['where'];
        
        $client = new \EasyRdf_Sparql_Client($this->endpoint);

//        echo $query;
//        die();

        $result = $client->query($query);

        return $result;
    }
}
