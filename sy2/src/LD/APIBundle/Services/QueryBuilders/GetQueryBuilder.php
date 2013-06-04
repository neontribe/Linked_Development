<?php
namespace LD\APIBundle\Services\QueryBuilders;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Wrapper to making easy rdf sparql queries
 */
class GetQueryBuilder extends DefaultQueryBuilder
{
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
        $request = Request::createFromGlobals();
        $params = $request->attributes->get('_route_params');
        $_id = $params['id'];

        $query = str_replace(
            '__URI__',
            'http://linked-development.org/eldis/output/' . $_id . '/',
            parent::createQuery($elements, $graph)
        );

        return $query;
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
}
