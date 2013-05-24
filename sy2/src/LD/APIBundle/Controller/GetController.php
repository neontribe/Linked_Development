<?php

namespace LD\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Top level API controller
 *
 * @see http://api.ids.ac.uk/
 */
class GetController extends APIController
{
    /**
     * Retrieve a single object
     *
     * This can be a document, organisation, theme, country or  region
     *
     * @Route(
     *      "/{graph}/get/{obj}/{parameter}/{format}/{query}",
     *       defaults={"format" = "short", "name" = "null"},
     *       requirements={
     *          "obj" = "documents|organisationis|themes|countries|regions|items|assets",
     *          "format" = "short|full"
     *      }
     * )
     * @Method({"GET", "HEAD", "OPTIONS"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction($graph, $obj, $parameter, $format, $query)
    {
        $router = $this->get('router');

        $graphs = $this->container->getParameter('graphs');
        if (isset($graphs[$graph])) {
            $_graph = $graphs[$graph];
        } else {
            $_graph = null;
        }

        $spqlsrvc = $this->get('sparql');
        $spqls = $this->container->getParameter('sparqls');
        $spql = $spqls['get'][$obj];
        $spql['where'] = str_replace('__ID__', $parameter, $spql['where']);
        $data = $spqlsrvc->query($_graph, $spql);

        print_r($data); die();

        $_response = array();
        return $this->response(array('data' => $data, 'resp' => $_response));
    }

    /**
     * Retrieve all objects
     *
     * Currently the first 10 records are displayed (with a link to the next 10)
     *
     * @Route("/{graph}/get_all/{parameter}/{format}")
     * @Method({"GET", "HEAD", "OPTIONS"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllAction($graph, $parameter, $format)
    {
        $entity = '\\LD\\APIBundle\\Entity\\' . ucfirst($parameter);
        $router = $this->get('router');

        $graphs = $this->container->getParameter('graphs');
        if (isset($graphs[$graph])) {
            $_graph = $graphs[$graph];
        } else {
            $_graph = null;
        }

        $spqlsrvc = $this->get('sparql');
        $spqls = $this->container->getParameter('sparqls');
        $spql = $spqls['get_all'][$parameter];
        $data = $spqlsrvc->query($_graph, $spql);

        foreach ($data as $row) {
            $obj = $entity::createFromRow($row, $router, $graph);
            if ($format == 'short') {
                $data = $obj->short();
            } else {
                $data = $obj->full();
            }
            $response[] = $data;
        }

        $_response = array(
            'results' => $response,
        );

        return $this->response($_response);
    }

    /**
     * Retrieve the objects in the level below (children) of searched for object
     *
     * This is only currently possible in Theme objects
     *
     * @Route("/{graph}/get_children/{obj}/{parameter}")
     * @Method({"GET", "HEAD", "OPTIONS"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getChildrenAction($obj, $parameter)
    {
        $data = $this->getData($obj, $parameter);
        return $this->response($data);
    }

    /**
     * Retrieve the objects in the level below (children) of searched for object
     *
     * This is only currently possible in Theme objects
     *
     * @Route("/fieldlist")
     * @Method({"GET", "HEAD", "OPTIONS"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fieldListAction()
    {
        $data = $this->getData();
        return $this->response($data);
    }
}
