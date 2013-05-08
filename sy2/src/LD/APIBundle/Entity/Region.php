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

class Region extends AbstractBaseEntity
{
    /**
     * Constructor
     *
     * @param string $metadataUrl metadataUrl
     * @param string $objectId    objectId
     * @param string $objectName  objectName
     * @param string $objectType  objectType
     */
    public function __construct(
        $metadataUrl, $objectId, $objectName, $objectType
    ) {
        $this->setMetadataUrl($metadataUrl);
        $this->setObjectId($objectId);
        $this->setObjectName($objectName);
        $this->setObjectType($objectType);
    }

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
        if (!isset($binding['callret-2']['value'])) {
            throw new \RuntimeException(
                '$binding["callret-2"]["value"]" not set'
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
        /*
        {
            "count": 8658,
            "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/regions/C21/full/africa-south-of-sahara/",
            "object_id": "C21",
            "object_name": "Africa South of Sahara",
            "object_type": "region"
        },
         *
                "region": {
                    "type": "uri",
                    "value": "http:\/\/linked-development.org\/eldis\/regions\/C28\/"
                },
                "regionlabel": {
                    "type": "literal",
                    "value": "North America"
                },
                "callret-2": {
                    "type": "typed-literal",
                    "datatype": "http:\/\/www.w3.org\/2001\/XMLSchema#integer",
                    "value": "478"
                }
            */
    }
}