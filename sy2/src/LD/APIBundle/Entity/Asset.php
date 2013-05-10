<?php
/**
 * The asset entity
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
 * Asset entity
 */
class Asset extends AbstractBaseEntity
{
    private $author = array();
    private $title;

    /**
     * Constructor
     *
     * @param string $title       title
     * @param string $metadataUrl metadataUrl
     * @param string $objectId    objectId
     * @param string $objectName  objectName
     * @param string $objectType  objectType
     */
    public function __construct(
        $title,
        $metadataUrl,
        $objectId,
        $objectName,
        $objectType
    )
    {
        parent::__construct($metadataUrl, $objectId, $objectName, $objectType);
        $this->setTitle($title);
    }

    /**
     * Return an array representation of this object
     *
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();
        $data['title'] = $this->getTitle();

        return $data;
    }

    /**
     * Set two letter iso code
     *
     * @param string $val
     *
     * @return void;
     */
    public function setTitle($val)
    {
        $this->title = $val;
    }

    /**
     * Get two letter iso code
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Take a binding entry from virtuoso and return a new Country object.
     *
     * I don't think this ever gets called as bindings are part of the count
     * results ans there is no /count/assets/foo
     *
     * @param array                                      $binding The array of data from virtuoso
     * @param \Symfony\Component\Routing\RouterInterface $router  The router object used to generate the metadata url
     *
     * @return \LD\APIBundle\Entity\Region
     * @throws \RuntimeException
     */
    public static function createFromBinding(array $binding, RouterInterface $router)
    {
        return false;
    }

    public static function createFromUriList(array $uris, RouterInterface $router)
    {
        foreach ($uris as $uri) {

        }
    }
}