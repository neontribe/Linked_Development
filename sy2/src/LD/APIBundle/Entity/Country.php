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
 * Country entity
 */
class Country extends AbstractBaseEntity
{
    protected $twolettercode;

    public function __constuct($twolettercode, $metadataUrl, $objectId, $objectName, $objectType)
    {
        $this->twolettercode = $twolettercode;
        parent::__construct($metadataUrl, $objectId, $objectName, $objectType);
    }
    
    public function toArray($format)
    {
        $data = parent::toArray($format);
        $data['iso_two_letter_code'] = $this->twolettercode;
    }
}