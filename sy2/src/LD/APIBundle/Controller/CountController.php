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
     * @param string $format    short|full
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *      "/count/{object}/{parameter}",
     *      requirements={
     *          "object" = "documents|organisations|items",
     *          "parameter" = "country|theme|region|keyword"
     *      },
     *      defaults={"format" = "short"}
     * )
     * @Method({"GET", "HEAD", "OPTIONS"})
     */
    public function countAction($object, $parameter, $format = 'short')
    {
        $entity = '\\LD\\APIBundle\\Entity\\' . ucfirst($parameter);
        $router = $this->get('router');

        $spqlsrvc = $this->get('sparql');
        $spqls = $this->container->getParameter('sparqls');
        $spql = $spqls['count'][$object][$parameter];
        $data = $spqlsrvc->query($spql);
        //return $this->response($data);

        $response = array();
        $total = 0;
        foreach ($data as $row) {
            $obj = $entity::createFromRow($row, $router);
            if ($format == 'short') {
                $data = $obj->short();
            } else {
                $data = $obj->full();
            }
            $data['count'] = $row->count->getValue();
            $response[] = $data;
            $total += $row->count->getValue();
        }

        $_response = array(
            'metadata' => array(
                'total_results' => $total,
            ),
            $parameter . '_count' => $response,
        );

        return $this->response($_response);
    }
}
