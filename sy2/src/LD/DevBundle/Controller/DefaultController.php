<?php

namespace LD\DevBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Default home page
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $docroot = $this->get('kernel')->getRootDir() . '/../web';
        $cmt = 'Never';
        $dmt = 'Never';

        $cif = $docroot . '/coverage/index.html';
        $dif = $docroot . '/docs/index.html';

        if (file_exists($cif)) {
            $cmt = date('Y/m/d H:s', filemtime($cif));
        }

        if (file_exists($dif)) {
            $dmt = date('Y/m/d H:s', filemtime($dif));
        }

        return array(
            'coveragemtime' => $cmt,
            'docsmtime' => $dmt,
        );
    }


    /**
     * @Route("/graphite")
     * @Template()
     *
     * @return array
     */
    public function graphiteAction()
    {
        $params = array(
            'default-graph-uri' => '',
            'query' => 'select distinct ?region ?regionlabel count(distinct ?article) where { ?article a <http://purl.org/ontology/bibo/Article> . ?article <http://purl.org/dc/terms/coverage> ?region . OPTIONAL {?r <http://www.fao.org/countryprofiles/geoinfo/geopolitical/resource/codeISO2> ?c .  FILTER ( ?r = ?region ) .  } FILTER ( !BOUND(?r) ) . ?region <http://www.w3.org/2000/01/rdf-schema#label> ?regionlabel . }',
            'format' => 'application/rdf+xml',
        );

        $url = 'http://ld.neontribe.org/sparql?' . http_build_query($params);

        $graph = new \Graphite();
        $graph->cacheDir('/tmp/graphite');
        $foo = $graph->load($url);
        $bar = $graph->resource('http://linked-development.org/eldis/regions/C29/	');
//        $bar = $graph->allObjects();
//        $bar = $graph->allSubjects();
//        $bar = $graph->dump();
//        $bar = $graph->dumpText();

        return array('foo' => print_r($bar, true));
    }
}
