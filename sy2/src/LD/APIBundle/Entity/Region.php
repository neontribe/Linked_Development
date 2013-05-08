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
    
    public static function createFromBinding(array $binding)
    {
        //
    }
}