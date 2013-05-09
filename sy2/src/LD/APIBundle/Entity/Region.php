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

/**
 * Region entity
 */
class Region extends AbstractBaseEntity
{
    /**
     * Take a binding entry from virtuoso and return a new Region object
     *
     * @param array                                      $binding The array of data from virtuoso
     * @param \Symfony\Component\Routing\RouterInterface $router  The router object used to generate the metadata url
     *
     * @return \LD\APIBundle\Entity\Region
     * @throws \RuntimeException
     */
    public static function createFromBinding(array $binding, RouterInterface $router)
    {
        if (!isset($binding['region']['value'])) {
            throw new \RuntimeException(
                '$binding["region"]["value"]" not set'
            );
        }
        if (!isset($binding['regionlabel']['value'])) {
            throw new \RuntimeException(
                '$binding["regionlabel"]["value"]" not set'
            );
        }

        $url = $binding['region']['value'];

        $objectName = $binding['regionlabel']['value'];
        $objectType = 'region';

        $parts = explode('/', trim($url, ' /'));
        $objectId = array_pop($parts);

        $metadataUrl = $router->generate(
            'ld_api_get_get',
            array(
                'obj' => 'region',
                'parameter' => $objectId,
                'format' => 'full',
            )
        );

        return new Region($metadataUrl, $objectId, $objectName, $objectType);
    }
}