<?php
namespace LD\APIBundle\Services\Factories;

use LD\APIBundle\Entity\Region;
use LD\APIBundle\Entity\Theme;
use LD\APIBundle\Entity\Country;
use LD\APIBundle\Entity\EmptyEntity;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use \Iterator;

/**
 * Default entoty factory
 */
class GetFactory extends BaseFactory
{
    protected $data;

    /**
     * Take an EasyRDF response and process it into data ready to be used later.
     *
     * @param mixed  $data  The response from the sparql query
     * @param string $type  The type of the data object being processed
     * @param string $graph The name of the graph that was used in this query
     *
     * @return array
     */
    public function process($data, $type, $graph = 'all')
    {
        $this->container->get('logger')->debug(
            'Processing response, data type: ' . $this->getType($data)
        );

        $func = 'get' . ucfirst($type);
        $this->data = call_user_func_array(
            array($this, $func),
            array($data, $graph, $type)
        );

        return $this->data;
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
        $response = array();

        foreach ($this->data as $row) {
            switch ($format) {
                case self::FULL:
                    $data = $row->full();
                    $data['count'] = $row->getCount();
                    $response[] = $data;
                    break;
                default:
                case self::SHORT:
                    $data = $row->short();
                    $data['count'] = $row->getCount();
                    $response[] = $data;
                    break;
            }
        }

        return $response;
    }

}
