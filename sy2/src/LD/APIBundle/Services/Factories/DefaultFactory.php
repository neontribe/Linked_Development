<?php
namespace LD\APIBundle\Services\Factories;

/**
 * Default entoty factory
 */
class DefaultFactory extends BaseFactory
{
    protected $data;

    /**
     * Take an EasyRDF response and process it into data ready to be used later.
     *
     * @param mixed  $data   The response from the sparql query
     * @param string $object The type of the data object being processed
     *
     * @return array
     */
    public function process($data, $object)
    {
        return $this->data = array(
            'object_id' => $object,
            'object_name' => 'Unkown object',
            'metadata_url' => $this->getContainer()->get('router')->generate('ld_dev_default_index'),
            'data' => $data,
        );
    }

    /**
     * Format the data held by this factory ready to be output by the API.
     *
     * @param integer $format The format to build a response for.
     *
     * @see FactoryInterface:SHORT
     * @see FactoryInterface:FULL
     *
     * @return array
     */
    public function getResponse($format)
    {
        switch ($format) {
            case self::FULL:
                return $this->data;
                break;
            default:
            case self::SHORT:
                return $this->data;
                break;
        }
    }
}
