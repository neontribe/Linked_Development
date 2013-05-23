<?php
/**
 * The country entity
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
 * Region entity
 */
class Region extends AbstractBaseEntity
{
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
        $url = $row->region;

        $objectName = $row->regionlabel->getValue();
        $objectType = 'region';

        $parts = explode('/', trim($url, ' /'));
        $objectId = array_pop($parts);

        $metadataUrl = $router->generate(
            'ld_api_get_get_1',
            array(
                'graph' => $graph,
                'obj' => 'region',
                'parameter' => $objectId,
                'format' => 'full',
            ),
            UrlGeneratorInterface::ABSOLUTE_PATH
        );

        return new Region($metadataUrl, $objectId, $objectName, $objectType);
    }

    /**
     * Return a short format array representation of this entity
     *
     * A wrapper for toArray(SHORT)
     *
     * @return array
     */
    public function short()
    {
        return $this->toArray(AbstractBaseEntity::SHORT);
    }
}