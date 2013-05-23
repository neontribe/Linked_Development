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
     * @Route("/{graph}/get/assets/{id}", defaults={"format" = "short", "name" = "null"})
     * @Route("/{graph}/get/assets/{id}/{format}", defaults={"name" = "null"}, requirements={"format" = "short|full"})
     * @Route("/{graph}/get/assets/{id}/{format}/{name}", requirements={"format" = "short|full"})
     * @Method({"GET", "HEAD", "OPTIONS"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAssetAction($graph, $id, $format, $name)
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
        $spql = $spqls['get']['assets'];
        $spql['where'] = str_replace('__ID__', $id, $spql['where']);



        $graph = \EasyRdf_Graph::newAndLoad(
            $this->container->getParameter('sparql_endpoint') . '?' .
            http_build_query(array('query' => $spqlsrvc->createQuery($graph, $spql)))
        );

        $foo = $graph->primaryTopic();
        print_r($foo); die();
        \EasyRdf_Namespace::set('pt', 'http://purl.org/dc/terms/');
//        $title = $graph->get('pt:title');

        return $this->response($graph->resource('pt:title'));

        $data = $spqlsrvc->query($_graph, $spql);

        $_response = array();
        return $this->response(array('data' => $data, 'resp' => $_response));
    }

    /**
     * Retrieve a single object
     *
     * This can be a document, organisation, theme, country or  region
     *
     * @Route("/{graph}/get/{obj}/{parameter}/{format}/{query}")
     * @Route("/{graph}/get/{obj}/{parameter}/{format}",  defaults={"query" = "null"})
     * @Route("/{graph}/get/{obj}/{parameter}",  defaults={"format" = "short", "query" = "null"})
     * @Method({"GET", "HEAD", "OPTIONS"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction($obj, $parameter, $format, $query)
    {
        $data = $this->getData($obj, $parameter);
        return $this->response($data);
    }

    /**
     * Retrieve all objects
     *
     * Currently the first 10 records are displayed (with a link to the next 10)
     *
     * @Route("/{graph}/get_all/{parameter}")
     * @Method({"GET", "HEAD", "OPTIONS"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllAction($parameter)
    {
        $data = $this->getData($parameter);

        return $this->response($data);
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