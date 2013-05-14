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
    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    public function getCategoryRegion()
    {
        return $this->categoryRegion;
    }

    public function setCategoryRegion($categoryRegion)
    {
        $this->categoryRegion = $categoryRegion;
        return $this;
    }

    public function getCategorySubject_array()
    {
        return $this->categorySubject_array;
    }

    public function setCategorySubject_array($categorySubject_array)
    {
        $this->categorySubject_array = $categorySubject_array;
        return $this;
    }

    public function getCategoryTheme_array()
    {
        return $this->categoryTheme_array;
    }

    public function setCategoryTheme_array($categoryTheme_array)
    {
        $this->categoryTheme_array = $categoryTheme_array;
        return $this;
    }

    public function getCorporateAuthor()
    {
        return $this->corporateAuthor;
    }

    public function setCorporateAuthor($corporateAuthor)
    {
        $this->corporateAuthor = $corporateAuthor;
        return $this;
    }

    public function getCountryFocus()
    {
        return $this->countryFocus;
    }

    public function setCountryFocus($countryFocus)
    {
        $this->countryFocus = $countryFocus;
        return $this;
    }

    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getEtal()
    {
        return $this->etal;
    }

    public function setEtal($etal)
    {
        $this->etal = $etal;
        return $this;
    }

    public function getHeadline()
    {
        return $this->headline;
    }

    public function setHeadline($headline)
    {
        $this->headline = $headline;
        return $this;
    }

    public function getKeyword()
    {
        return $this->keyword;
    }

    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }

    public function getLanguageId()
    {
        return $this->languageId;
    }

    public function setLanguageId($languageId)
    {
        $this->languageId = $languageId;
        return $this;
    }

    public function getLanguageName()
    {
        return $this->languageName;
    }

    public function setLanguageName($languageName)
    {
        $this->languageName = $languageName;
        return $this;
    }

    public function getLicenseType()
    {
        return $this->licenseType;
    }

    public function setLicenseType($licenseType)
    {
        $this->licenseType = $licenseType;
        return $this;
    }

    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;
        return $this;
    }

    public function getPublicationYear()
    {
        return $this->publicationYear;
    }

    public function setPublicationYear($publicationYear)
    {
        $this->publicationYear = $publicationYear;
        return $this;
    }

    public function getPublisherArray()
    {
        return $this->publisherArray;
    }

    public function setPublisherArray($publisherArray)
    {
        $this->publisherArray = $publisherArray;
        return $this;
    }

    public function getSite()
    {
        return $this->site;
    }

    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getUrls()
    {
        return $this->urls;
    }

    public function setUrls($urls)
    {
        $this->urls = $urls;
        return $this;
    }

    public function getWebsiteUrl()
    {
        return $this->websiteUrl;
    }

    public function setWebsiteUrl($websiteUrl)
    {
        $this->websiteUrl = $websiteUrl;
        return $this;
    }


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
     * @param int $format self::SHORT | self:: FULL
     *
     * @return array
     */
    public function toArray($format = AbstractBaseEntity::SHORT)
    {
        $data = parent::toArray();
        $data['title'] = $this->getTitle();

        return $data;
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
        return false;
    }

    public static function createFromUriList(array $uris, RouterInterface $router)
    {
        foreach ($uris as $uri) {

        }
    }
}