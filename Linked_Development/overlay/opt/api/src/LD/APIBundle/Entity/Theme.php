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
 * Theme entity
 */
class Theme extends AbstractBaseEntity
{
    private $level;

    /**
     * Constructor
     *
     * @param string $level       level
     * @param string $metadataUrl metadataUrl
     * @param string $objectId    objectId
     * @param string $objectName  objectName
     * @param string $objectType  objectType
     */
    public function __construct(
        $level,
        $metadataUrl,
        $objectId,
        $objectName,
        $objectType
    )
    {
        parent::__construct($metadataUrl, $objectId, $objectName, $objectType);
        $this->setLevel($level);
    }

    /**
     * Return an array representation of this object
     *
     * @param int $format self::SHORT | self:: FULL
     *
     * @return array
     */
    public function toArray($format = AbstractBaseEntity::SHORT)
    {
        $data = parent::toArray();
        $data['level'] = $this->getLevel();

        return $data;
    }

    /**
     * Set two letter iso code
     *
     * @param string $val
     *
     * @return void;
     */
    public function setLevel($val)
    {
        $this->level = $val;
    }

    /**
     * Get two letter iso code
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    public static function createFromUri($uri)
    {

        $graph = \EasyRdf_Graph::newAndLoad($uri);

    }
}