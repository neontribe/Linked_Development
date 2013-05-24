<?php
/**
 * The Document entity
 *
 * PHP Version 5.3
 *
 * @category  LDAPIBundle
 * @package   LD\APIBundle\Entity
 * @author    Toby Batch <tobias@neontribe.co.uk>
 * @copyright 2012 Neontribe
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      http://www.neontribe.co.uk
 */

namespace LD\APIBundle\Entity;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Document entity
 */
class Document extends AbstractBaseEntity
{
    protected $authors = array();
    protected $categories = array();

    public function toArray($format = AbstractBaseEntity::SHORT)
    {
        $data = parent::toArray();
        $data['title'] = $data['object_name'];
        unset($data['object_name']);

        return $data;
    }

    /**
     * Take a binding entry from virtuoso and return a new Region object
     *
     * @param mixed                                      $row    The array of data from virtuoso
     * @param \Symfony\Component\Routing\RouterInterface $router The router object used to generate the metadata url
     * @param string                                     $graph  rd4 | eldis | all
     *
     * @return \LD\APIBundle\Entity\Region
     * @throws \RuntimeException
     */
    public static function createFromRow($row, RouterInterface $router, $graph = 'all')
    {
        $objectName = $row->dctitle->getValue();
        $objectType = 'Country';
        $objectId = 'Not present in sparql';

        $metadataUrl = $router->generate(
            'ld_api_get_get',
            array(
                'graph' => $graph,
                'obj' => 'documents',
                'parameter' => $objectId,
                'format' => 'full',
                'query' => $objectName,
            ),
            true
        );

        return new Document(
            $metadataUrl, $objectId, $objectName, $objectType
        );
    }
}