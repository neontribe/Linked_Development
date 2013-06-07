<?php

namespace LD\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use LD\APIBundle\Entity\Region;
use LD\APIBundle\Entity\Theme;

/**
 * Count controller
 *
 * @see http://api.ids.ac.uk/
 */
class CountController extends APIController
{
    /**
     * Count is a dynamic clustering of objects.
     *
     * A count is a dynamic clustering of objects or search results into
     * categories  (theme, country, region, keywords). A count shows the number
     * of hits within the search that match that category.
     *
     * @param string $graph     the graph to use, see service.yml
     * @param string $object    document|organisations|item
     * @param string $parameter theme|country|region|keyword
     * @param string $format    short|full
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *      "/{graph}/count/{object}/{parameter}",
     *      requirements={
     *          "object" = "documents|organisations|items",
     *          "parameter" = "country|theme|region|keyword"
     *      },
     *      defaults={"format" = "short"}
     * )
     * @Route(
     *      "/{graph}/count/{object}/{parameter}/{format}",
     *      requirements={
     *          "object" = "documents|organisations|items",
     *          "parameter" = "country|theme|region|keyword"
     *      }
     * )
     * @Method({"GET", "HEAD", "OPTIONS"})
     */
    public function countAction($graph, $object, $parameter, $format = 'short')
    {
        // get and set  the query factory
        $querybuilders = $this->container->getParameter('querybuilder');
        if (isset($querybuilders['count'][$object][$parameter])) {
            $builder = $querybuilders['count'][$object][$parameter];
        } elseif (isset($querybuilders['default'])) {
            $builder = $querybuilders['default'];
        } else {
            $builder = 'LD\APIBundle\Services\ids\DefaultQueryBuilder';
        }

        // get the sparql
        $spqls = $this->container->getParameter('sparqls');
        $this->container->get('logger')->info(
            sprintf('Fetching sparql: count->%s->%s', $object, $parameter)
        );
        $spql = $spqls['count'][$object][$parameter];

        // fetch factory
        $entfactories = $this->container->getParameter('factories');
        $this->container->get('logger')->info(
            sprintf('Fetching factory: count->%s->%s', $object, $parameter)
        );
        $factoryClass = $entfactories['count'][$object][$parameter];

        $response = $this->chomp(
            $graph, $spql, $factoryClass, $builder, $format, $parameter
        );

        return $this->response($response);
    }
}