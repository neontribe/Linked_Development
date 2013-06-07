<?php
namespace LD\APIBundle\Services\QueryBuilders;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Wrapper to making easy rdf sparql queries
 */
class DefaultQueryBuilder extends AbstractQueryBuilder
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

        $_where = $elements['where'];
        $where = $this->filterSubstitution($_where);

        $query = $this->addOffsetLImit(
            $elements,
            sprintf('%s %s %s %s', $define, $select, $from, $where)
        );

        return $query;
    }
}
