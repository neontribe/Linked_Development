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
 * Top level API controller
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
     * @param string $object    document|organisations|item
     * @param string $parameter theme|country|region|keyword
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *      "/count/{object}/{parameter}",
     *      requirements={
     *          "object" = "documents|organisations|items",
     *          "parameter" = "country|theme|region|keyword"
     *      }
     * )
     * @Method({"GET", "HEAD", "OPTIONS"})
     */
    public function countAction($object, $parameter)
    {
        $spqlsrvc = $this->get('sparql');
        $spqls = $this->container->getParameter('sparqls');
        $spql = $spqls['count'][$object][$parameter];
        $data = $spqlsrvc->curl($spql);
        //return $this->response($data);

        $_response = $this->_parseBindings(
            $data['results']['bindings'], $parameter
        );

        return $this->response($_response);
    }

    protected function _parseBindings($bindings, $type)
    {
        $entity = '\\LD\\APIBundle\\Entity\\' . ucfirst($type);
        $total = 0;
        $router = $this->get('router');
        $response = array();
        foreach ($bindings as $binding) {
            $theme = $entity::createFromBinding($binding, $router)->toArray();
            if (!isset($binding['count']['value'])) {
                throw new \RuntimeException(
                    '$binding["count"]["value"]" not set'
                );
            }
            $count = $binding['count']['value'];
            $theme['count'] = $count;
            $total += $count;
            $response[] = $theme;

        }

        $_response = array(
            'metadata' => array(
                'total_results' => $total,
            ),
            $type . '_count' => $response,
        );

        return $_response;
    }
}
