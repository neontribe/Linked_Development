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
 * Get controller m16a3, mtar, m249, m98, pp19
 *
 * @see http://api.ids.ac.uk/
 */
class GetController extends APIController
{
    /**
     * @Route("/get")
     */
    public function getAction()
    {        
        \EasyRdf_TypeMapper::set('http:\/\/www.w3.org\/2004\/02\/skos\/core#Concept', '\\LD\\APIBundle\\Entity\\Theme');
        
        $url = 'http://linked-development.org/eldis/themes/C833/';
        $graph = \EasyRdf_Graph::newAndLoad($url);
                
        return new \Symfony\Component\HttpFoundation\Response(
            '<p>' . htmlspecialchars($graph->resource('http://www.w3.org/1999/02/22-rdf-syntax-ns#type')) . '</p>'
        );
    }
}