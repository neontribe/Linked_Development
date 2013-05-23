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
     * @return Response
     */
    public function getAction()
    {
        \EasyRdf_TypeMapper::set('http:\/\/www.w3.org\/2004\/02\/skos\/core#Concept', '\\LD\\APIBundle\\Entity\\Theme');

        $url = 'http://linked-development.org/eldis/themes/C833/';
        $graph = \EasyRdf_Graph::newAndLoad($url);

        return new \Symfony\Component\HttpFoundation\Response(
            '<h3>$graph->types</h3><pre>' . json_encode($graph->types()) . '</pre>' .
            '<h3>$graph->getUri</h3><pre>' . json_encode($graph->getUri()) . '</pre>' .
            '<h3>$graph->type</h3><pre>' . json_encode($graph->type()) . '</pre>' .
            '<h3>$graph->label</h3><pre>' . json_encode($graph->label()) . '</pre>' .
            '<h3>$graph->allOfType(http://www.w3.org/2000/01/rdf-schema#label),</h3><pre>' . json_encode($graph->allOfType('http://www.w3.org/2000/01/rdf-schema#label')) . '</pre>' .
            '<h3>$graph->dumpResource</h3><p>' . $graph->dumpResource('http://linked-development.org/eldis/output/A64003/') . '</p>' .
            '<p>' . ($graph->dump()) . '</p>'
        );
    }
}