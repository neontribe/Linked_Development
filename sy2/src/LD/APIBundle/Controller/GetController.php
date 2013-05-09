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
     * @Route("/get/{obj}/{parameter}/{format}/{query}")
     * @Route("/get/{obj}/{parameter}/{format}",  defaults={"query" = "null"})
     * @Route("/get/{obj}/{parameter}",  defaults={"format" = "short", "query" = "null"})
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
     * @Route("/get_all/{parameter}")
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
     * @Route("/get_children/{obj}/{parameter}")
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
