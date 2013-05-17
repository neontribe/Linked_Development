<?php
namespace LD\APIBundle\Services\ids;

use Symfony\Component\HttpFoundation\Request;

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
        $this->logger->warn('Sparql::curl is depricated.  Use the EasyRDF function instead');
        
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
     * Build a query
     *
     * @param string $graph
     * @param array  $elements
     *
     * @return string
     */
    public function createQuery($graph, array $elements)
    {

        if (isset($elements['define'])) {
            $define = $elements['define'];
        } else {
            $define = '';
        }

        $select = $elements['select'];

        if ($graph && $graph != 'all') {
            $from = " from <" . $graph . '>';
        } else {
            $from = '';
        }

        $where = $elements['where'];
        
        $request = Request::createFromGlobals();
        $offset = $this->getOffset($request);
        $limit = $this->getLimit($request);

        $query = sprintf(
            '%s %s %s %s limit %s offset %s',
            $define, $select, $from, $where, $offset, $limit
        );

        $this->logger->debug('Query: ' . $query);

        return $query;
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
        $query = $this->createQuery($graph, $elements);

        $client = new \EasyRdf_Sparql_Client($this->endpoint);
        $result = $client->query($query);

        return $result;
    }

    protected function getLimit(Request $req = null)
    {
        $_req = ($req) ? $req : Request::createFromGlobals();
        
        return $_req->query->get(
            'num_results', 
            $this->container->getParameter('sparql_default_limit')
        );
        // num_results=10&start_offset=10", 
    }

    protected function getOffset(Request $req = null)
    {
        $_req = ($req) ? $req : Request::createFromGlobals();
        
        return $_req->query->get(
            'start_offset', 
            $this->container->getParameter('sparql_default_offset') // I can't see why this won't always be zero
        );

    }
}