<?php

namespace LD\APIBundle\Entity;

use Symfony\Component\HttpFoundation\Request;
/**
 * Top level entity
 */
abstract class AbstractBaseEntity implements CreateFromBinding
{
    const SHORT = 1;
    const FULL = 2;

    private $metadataUrl;
    private $objectId;
    private $objectName;
    private $objectType;

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
    )
    {
        $this->setMetadataUrl($metadataUrl);
        $this->setObjectId($objectId);
        $this->setObjectName($objectName);
        $this->setObjectType($objectType);
    }


    /**
     * Set metatdata url
     *
     * @param string $val
     *
     * @return void;
     */
    public function setMetadataUrl($val)
    {
        $this->metadataUrl = $val;
    }

    /**
     * Set object id
     *
     * @param string $val
     *
     * @return void;
     */
    public function setObjectId($val)
    {
        $this->objectId = $val;
    }

    /**
     * Set object name
     *
     * @param string $val
     *
     * @return void;
     */
    public function setObjectName($val)
    {
        $this->objectName = $val;
    }

    /**
     * Set object type
     *
     * @param string $val
     *
     * @return void;
     */
    public function setObjectType($val)
    {
        $this->objectType = $val;
    }

    /**
     * Get metatdata url
     *
     * @return string
     */
    public function getMetadataUrl()
    {
        return $this->metadataUrl;
    }

    /**
     * Get object id
     *
     * @return string
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Get object name
     *
     * @return string
     */
    public function getObjectName()
    {
        return $this->objectName;
    }

    /**
     * Get object typr
     *
     * @return string
     */
    public function getObjectType()
    {
        return $this->objectType;
    }

    /**
     * Return a json string representation of this object
     *
     * @return string
     */
    public function toJson()
    {
        $data = $this->toArray();
        if (defined('JSON_PRETTY_PRINT')) {

            return json_encode($data, JSON_PRETTY_PRINT);
        } else {

            return json_encode($data);
        }
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
        return array(
            'metadata_url' => $this->getMetadataUrl(),
            'object_id' => $this->getObjectId(),
            'object_name' => $this->getObjectName(),
            'object_type' => $this->getObjectType(),
        );
    }

    /**
     * Return a short format array representation of this entity
     *
     * A wrapper for toArray(SHORT)
     *
     * @return array
     */
    public abstract function short();
}