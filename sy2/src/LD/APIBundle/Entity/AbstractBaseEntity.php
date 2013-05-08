<?php

namespace LD\APIBundle\Entity;

abstract class AbstractBaseEntity
{
    private $metadataUrl;
    private $objectId;
    private $objectName;
    private $objectType;
    
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
     * @return array
     */
    public function toArray()
    {
        return array(
            'metadata_url' => $this->getMetadataUrl(),
            'object_id' => $this->getObjectId(),
            'object_name' => $this->getObjectName(),
            'object_type' => $this->getObjectType(),
        );
    }
}
