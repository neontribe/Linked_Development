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
 * Level entity
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

    /**
     * Take a binding entry from virtuoso and return a new Region object
     *
     * @param mixed                                      $row    The array of data from virtuoso
     * @param \Symfony\Component\Routing\RouterInterface $router The router object used to generate the metadata url
     *
     * @return \LD\APIBundle\Entity\Region
     * @throws \RuntimeException
     */
    public static function createFromRow($row, RouterInterface $router)
    {
        $level = 'Missing in sparql';

        $url = $row->theme;

        $objectName = $row->themelabel->getValue();
        $objectType = 'theme';

        $parts = explode('/', trim($url, ' /'));
        $objectId = array_pop($parts);

        $metadataUrl = $router->generate(
            'ld_api_get_get_1',
            array(
                'obj' => 'theme',
                'parameter' => $objectId,
                'format' => 'full',
            )
        );

        return new Theme($level, $metadataUrl, $objectId, $objectName, $objectType);
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