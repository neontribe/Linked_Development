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

}