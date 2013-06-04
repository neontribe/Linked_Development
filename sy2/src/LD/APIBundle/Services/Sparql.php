<?php
namespace LD\APIBundle\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LD\APIBundle\Services\QueryBuilders\QueryBuilderInterface;

/**
 * Wrapper to making easy rdf sparql queries
 */
class Sparql
{
    protected $logger = null;
    protected $container = null;
    protected $endpoint = null;
    protected $queryBuilder = null;

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
     * Set the current query builder
     *
     * @param \LD\APIBundle\Services\ids\QueryBuilderInterface $queryBuilder
     */
    public function setQueryBuilder(QueryBuilderInterface $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * Get the current query builder
     *
     * @return \LD\APIBundle\Services\ids\DefaultQueryBuilder
     */
    public function getQueryBuilder()
    {
        if ($this->queryBuilder) {

            return $this->queryBuilder;
        }

        return new DefaultQueryBuilder();
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
     * Easy RDF Query of the endpoint
     *
     * @param array  $elements The query in the form of an array
     * @param string $graph    The graph to access
     *
     * @return EasyRdf_Sparql_Result|EasyRdf_Graph|array
     */
    public function query(array $elements, $graph)
    {
        $data = array();
        foreach ($elements['queries'] as $key => $query) {
            $data[$key] = $this->__query($query, $graph);
            // $count += count($data[$key]);
        }

        return $data;
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
        $query = $this->getQueryBuilder()->createQuery($elements, $graph);
        $this->container->get('logger')->debug('Query: ' . $query);
        $client = new \EasyRdf_Sparql_Client($this->endpoint);

        $time = microtime(true);
        $result = $client->query($query);
        $this->container->get('logger')->debug(
            sprintf('Sparql query took %d ms', microtime(true) - $time)
        );


        return $result;
    }
}
