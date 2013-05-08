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
class CountController extends APIController
{
    /**
     * Count is a dynamic clustering of objects.
     *
     * A count is a dynamic clustering of objects or search results into
     * categories  (theme, country, region, keywords). A count shows the number
     * of hits within the search that match that category.
     *
     * @Route("/count/documents/region")
     * @Method({"GET", "HEAD", "OPTIONS"})
     */
    public function countDocumentRegion()
    {
        $spql = $this->get('sparql');
        $data = $spql->curl(
            implode(
                "\n",
                array(
                    'select distinct ?region ?regionlabel count(distinct ?article) where {',
                    '  ?article a <http://purl.org/ontology/bibo/Article> .',
                    '  ?article <http://purl.org/dc/terms/coverage> ?region .',
                    '  OPTIONAL {?r <http://www.fao.org/countryprofiles/geoinfo/geopolitical/resource/codeISO2> ?c .',
                            '    FILTER ( ?region = ?r ) .',
                             '  }',
                      'FILTER ( !BOUND(?r) ) .',
                      '?region <http://www.w3.org/2000/01/rdf-schema#label> ?regionlabel .',
                     '}',
                )
            )
        );

        return $this->response($data);
    }

}
