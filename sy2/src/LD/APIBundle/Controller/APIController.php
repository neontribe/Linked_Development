<?php

namespace LD\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Top level API controller
 *
 * @see http://api.ids.ac.uk/
 */
class APIController extends Controller
{
    /**
     * Get data for current route
     *
     * @return array
     */
    protected function getData()
    {
        $routeName = $this->container->get('request')->get('_route');
        $resource = sprintf(
            '%s/../Resources/fixtures/%s.%s',
            __DIR__, $routeName, 'json'
        );

        return json_decode(file_get_contents($resource), true);
    }
}
