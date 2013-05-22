<?php
namespace LD\APIBundle\Services\ids;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Wrapper to making easy rdf sparql queries
 */
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
    protected $container = null;
    protected $endpoint = null;

    /**
     * Create a new instance of this service
     *
     * @param string             $endpoint  The sparql endpoint to query
     * @param ContainerInterface $container An instance of the current container
     */
    public function __construct($endpoint, ContainerInterface $container)
    {
        $this->endpoint = $endpoint;
        $this->container = $container;
        $this->logger = $container->get('logger');
    }

    /**
     * Use cURL to hit the endpoint
     *
     * @param string $spql The sparql query
     *
     * @return array
     */
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

        return json_decode($response, true);
    }

    /**
     * Build a sparql query.
     *
     * This funtion expects to get an array of query elements as the first
     * paramter.  See the LD\APIBundle\Resources\config\services.yml for an
     * example.
     *
     * It requires that the array has at least two elements, select and where,
     * these are then glued together to make a sparql query.  So a simple query
     * would be:
     *
     *     array(
     *       'select' => 'select count(*)',
     *       'where' => 'where {?a ?b ?c}',
     *     );
     *
     * In addtion there can be a define index that will allow name spaces to be
     * added.
     *
     * @param array  $elements The query in the form of an array
     * @param string $graph    The graph to access
     *
     * @return string
     */
    public function createQuery(array $elements, $graph = null)
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
            '%s %s %s %s offset %s limit %s',
            $define, $select, $from, $where, $offset, $limit
        );

        $this->logger->debug('Query: ' . $query);

        return $query;
    }

    /**
     * Easy RDF Query of the endpoint
     *
     * @param array  $elements The query in the form of an array
     * @param string $graph    The graph to access
     *
     * @return EasyRdf_Sparql_Result|EasyRdf_Graph|array
     */
    public function query(array $elements, $graph)
    {
        if (isset($elements['multiquery']) && $elements['multiquery'] == true) {
            unset($elements['multiquery']);
            $data = array();
            foreach ($elements as $key => $query) {
                $data[$key] = $this->__query($query, $graph);
            }

            return $data;
        } else {

            return $this->__query($elements, $graph);
        }
    }

    /**
     * Internal query function
     *
     * This will be called multiple times from Sparql::query
     *
     * @param array  $elements The query in the form of an array
     * @param string $graph    The graph to access
     *
     * @return EasyRdf_Sparql_Result|EasyRdf_Graph|array
     */
    private function __query(array $elements, $graph)
    {
        $query = $this->createQuery($elements, $graph);
        $client = new \EasyRdf_Sparql_Client($this->endpoint);

        $time = microtime(true);
        $result = $client->query($query);
        $this->container->get('logger')->debug(
            sprintf('Sparql query took %d ms', microtime(true) - $time)
        );


        return $result;
    }

    /**
     * Check the http query for how many objects to return
     *
     * @param Request $req The request object, if null Request::createFromGlobals will be used to create a new one
     *
     * @access protected
     * @return integer
     */
    protected function getLimit(Request $req = null)
    {
        $_req = ($req) ? $req : Request::createFromGlobals();

        return $_req->query->get(
            'num_results',
            $this->container->getParameter('sparql_default_limit')
        );
        // num_results=10&start_offset=10",
    }

    /**
     * Check the http query for offset to start returning objects from
     *
     * @param Request $req The request object, if null Request::createFromGlobals will be used to create a new one
     *
     * @access protected
     * @return integer
     */
    protected function getOffset(Request $req = null)
    {
        $_req = ($req) ? $req : Request::createFromGlobals();

        return $_req->query->get(
            'start_offset',
            $this->container->getParameter('sparql_default_offset') // I can't see why this won't always be zero
        );

    }

    private function array_depth(array $array)
    {
        $maxDepth = 1;

        foreach ($array as $value) {
            if (is_array($value) && $maxDepth < 100) {
                $depth = array_depth($value) + 1;

                if ($depth > $maxDepth) {
                    $maxDepth = $depth;
                }
            }
        }

        return $maxDepth;
    }
}
