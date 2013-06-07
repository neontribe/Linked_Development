<?php
/**
 * Entity factory interface
 */
namespace LD\APIBundle\Services\Factories;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Defines function for Entity factories
 */
interface FactoryInterface extends ContainerAwareInterface
{
    const SHORT = 0;
    const FULL = 1;

    /**
     * Take an EasyRDF response and process it into data ready to be used later.
     *
     * @param mixed  $data   The response from the sparql query
     * @param string $object The type of the data object being processed
     *
     * @return array
     */
    public function process($data, $object);

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
    public function getResponse($format);
}
