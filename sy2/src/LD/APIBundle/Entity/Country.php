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
 * Country entity
 */
class Country extends AbstractBaseEntity
{
    private $isoTwoLetterCode;

    /**
     * Constructor
     *
     * @param string $isoTwoLetterCode isoTwoLetterCode
     * @param string $metadataUrl      metadataUrl
     * @param string $objectId         objectId
     * @param string $objectName       objectName
     * @param string $objectType       objectType
     */
    public function __construct(
        $isoTwoLetterCode,
        $metadataUrl,
        $objectId,
        $objectName,
        $objectType
    )
    {
        parent::__construct($metadataUrl, $objectId, $objectName, $objectType);
        $this->setIsoTwoLetterCode($isoTwoLetterCode);
    }

    /**
     * Return an array representation of this object
     *
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();
        $data['iso_two_letter_code'] = $this->getIsoTwoLetterCode();

        return $data;
    }

    /**
     * Set two letter iso code
     *
     * @param string $val
     *
     * @return void;
     */
    public function setIsoTwoLetterCode($val)
    {
        $this->isoTwoLetterCode = $val;
    }

    /**
     * Get two letter iso code
     *
     * @return string
     */
    public function getIsoTwoLetterCode()
    {
        return $this->isoTwoLetterCode;
    }

    /**
     * Take a binding entry from virtuoso and return a new Country object
     *
     * @param array                                      $binding The array of data from virtuoso
     * @param \Symfony\Component\Routing\RouterInterface $router  The router object used to generate the metadata url
     *
     * @return \LD\APIBundle\Entity\Region
     * @throws \RuntimeException
     */
    public static function createFromBinding(array $binding, RouterInterface $router)
    {
        if (!isset($binding['countrycode']['value'])) {
            throw new \RuntimeException(
                '$binding["countrycode"]["value"]" not set'
            );
        }
        if (!isset($binding['countrylabel']['value'])) {
            throw new \RuntimeException(
                '$binding["countrylabel"]["value"]" not set'
            );
        }

        $objectName = $binding['countrylabel']['value'];
        $objectType = 'Country';
        $objectId = 'Not present in sparql';
        $isoTwoLetterCode = $binding['countrycode']['value'];

        $metadataUrl = $router->generate(
            'ld_api_get_get_1',
            array(
                'obj' => 'countries',
                'parameter' => $objectId,
                'format' => 'full',
                'query' => $objectName,
            )
        );

        return new Country(
            $isoTwoLetterCode, $metadataUrl, $objectId, $objectName, $objectType
        );
    }
}