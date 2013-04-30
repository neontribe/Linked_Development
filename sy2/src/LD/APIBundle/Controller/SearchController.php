<?php

namespace LD\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;

/**
 * Top level API controller
 *
 * @see http://api.ids.ac.uk/
 */
class SearchController extends APIController
{
    /**
     * Search for a set of objects
     *
     * @param string $obj Assets or Categories, for assets this may be Documents, Organisations or Items, for categories this may be themes, countries or regions
     *
     * @Route("/search/{obj}")
     * @Method({"GET", "HEAD", "OPTIONS"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchWithObjectAction($obj)
    {
        $query = $this->getRequest()->query->all();
        if (!count($query)) {
            throw new BadRequestHttpException('object search must have some query string, eg /objects/search/short?q=undp');
        }

        $data = $this->getData($obj);

        return $this->response($data);
    }

    /**
     * Search for a set of objects
     *
     * @param string $obj   Assets or Categories, for assets this may be Documents, Organisations or Items, for categories this may be themes, countries or regions
     * @param string $param Format (full or short), All, ID, Keyword_count, Region_count, or Theme_count
     *
     * @Route("/search/{obj}/{param}")
     * @Method({"GET"})
     * @Template()
     *
     * @return array()
     */
    public function searchWithParamAction($obj, $param)
    {
        $query = $this->getRequest()->query->all();
        if (!count($query)) {
            throw new BadRequestHttpException('object search must have some query string, eg /objects/search/short?q=undp');
        }

        $data = $this->getData($obj, $param);

        return $this->response($data);
    }

    /**
     * Search for a set of objects
     *
     * @param string $obj   Assets or Categories, for assets this may be Documents, Organisations or Items, for categories this may be themes, countries or regions
     * @param string $param Format (full or short), All, ID, Keyword_count, Region_count, or Theme_count
     * @param string $query Free query string
     *
     * @Route("/search/{obj}/{param}/{query}")
     * @Method({"GET"})
     * @Template()     *
     *
     * @return array()
     */
    public function searchWithQueryAction($obj, $param, $query)
    {
        $query = $this->getRequest()->query->all();
        if (!count($query)) {
            throw new BadRequestHttpException('object search must have some query string, eg /objects/search/short?q=undp');
        }

        $data = $this->getData($obj, $param, $query);

        return $this->response($data);
    }
}
