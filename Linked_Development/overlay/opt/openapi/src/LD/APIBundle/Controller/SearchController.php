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
 * Get controller
 *
 * @see http://api.ids.ac.uk/
 */
class SearchController extends APIController
{
    /**
     * @param string $graph  the graph to use, see service.yml
     * @param string $object documents|assets|countries|themes|organisations|region
     * @param string $id     the object id
     *
     * @Route(
     *      "/{graph}/search/{object}/{id}",
     *      requirements={
     *          "object" = "documents|assets|countries|themes|organisations|region",
     *      }
     * )
     * @Method({"GET", "HEAD", "OPTIONS"})
     * @return Response
     */
    public function searchAction($graph, $object, $id)
    {
        // get and set  the query factory
        $querybuilders = $this->container->getParameter('querybuilder');
        if (isset($querybuilders['search'][$object])) {
            $builder = $querybuilders['get'][$object];
        } elseif (isset($querybuilders['default'])) {
            $builder = $querybuilders['default'];
        } else {
            $builder = 'LD\APIBundle\Services\ids\DefaultQueryBuilder';
        }

        // get the sparql
        $spqls = $this->container->getParameter('sparqls');
        $this->container->get('logger')->info(
            sprintf('Fetching sparql: get->%s', $object)
        );
        $spql = $spqls['get'][$object];

        // fetch factory
        $entfactories = $this->container->getParameter('factories');
        $this->container->get('logger')->info(
            sprintf('Fetching factory: get->%s', $object)
        );
        $factoryClass = $entfactories['get'][$object];

        $response = $this->chomp($graph, $spql, $factoryClass, $builder);

        return $this->response($response);
    }
}
