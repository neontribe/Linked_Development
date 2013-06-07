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
class CountFactory extends BaseFactory
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

        $func = 'count' . ucfirst($type);
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

    /**
     * Parse the list of results and build the response data array
     *
     * @param mixed  $data  The list of result rows.
     * @param string $graph The name of the graph it use.
     *
     * @return array
     */
    protected function countRegion($data, $graph)
    {
        $router = $this->container->get('router');

        $response = array();

        // this we know is a multipart query
        foreach ($data as $key => $rows) {
            if ($key == 'none') {
                $row = $rows[0]; // This is a single result
                $entity = new EmptyEntity('', '', '', '');
                $entity->setCount($row->count->getValue());
                $response[] = $entity;
            } elseif ($key == 'all') {
                foreach ($rows as $row) {
                    $url = $row->url;

                    $objectName = $row->label->getValue();
                    $objectType = 'region';

                    $parts = explode('/', trim($url, ' /'));
                    $objectId = array_pop($parts);

                    $metadataUrl = $router->generate(
                        'ld_dev_default_index', // TODO This needs correcting when get routes are done
                        array(
                            'graph' => $graph,
                            'obj' => $objectType,
                            'parameter' => $objectId,
                            'format' => 'full',
                        ),
                        UrlGeneratorInterface::ABSOLUTE_PATH
                    );

                    $entity = new Region(
                        $metadataUrl, $objectId, $objectName, $objectType
                    );
                    $entity->count = $row->count->getValue();
                    $response[] = $entity;
                }
            } else {
                // Not reachable
            }
        }

        return $response;
    }

    /**
     * Parse the list of results and build the response data array
     *
     * @param mixed  $data  The list of result rows.
     * @param string $graph The name of the graph it use.
     *
     * @return array
     */
    protected function countTheme($data, $graph)
    {
        $router = $this->container->get('router');

        $response = array();

        // this we know is a multipart query
        foreach ($data as $key => $rows) {
            if ($key == 'none') {
                $row = $rows[0]; // This is a single result
                $entity = new EmptyEntity('', '', '', '');
                $entity->setCount($row->count->getValue());
                $response[] = $entity;
            } elseif ($key == 'all') {
                foreach ($rows as $row) {
                    $url = $row->url;

                    $objectName = trim($row->label->getValue());
                    $objectType = 'theme';

                    $parts = explode('/', trim($url, ' /'));
                    $objectId = array_pop($parts);

                    $metadataUrl = $router->generate(
                        'ld_dev_default_index', // TODO This needs correcting when get routes are done
                        array(
                            'graph' => $graph,
                            'obj' => $objectType,
                            'parameter' => $objectId,
                            'format' => 'full',
                        ),
                        UrlGeneratorInterface::ABSOLUTE_PATH
                    );

                    $entity = new Theme(
                        'NPIS', $metadataUrl, $objectId, $objectName, $objectType
                    );
                    $entity->setCount($row->count->getValue());

                    $response[] = $entity;
                }
            }
        }

        return $response;
    }

    /**
     * Parse the list of results and build the response data array
     *
     * @param mixed  $data  The list of result rows.
     * @param string $graph The name of the graph it use.
     *
     * @return array
     */
    protected function countCountry($data, $graph)
    {
        $router = $this->container->get('router');

        $response = array();

        // this we know is a multipart query
        foreach ($data as $key => $rows) {
            if ($key == 'none') {
                $row = $rows[0]; // This is a single result
                $entity = new EmptyEntity('', '', '', '');
                $entity->setCount($row->count->getValue());
                $response[] = $entity;
            } elseif ($key == 'all') {
                foreach ($rows as $row) {
                    $url = $row->url;

                    $objectName = trim($row->label->getValue());
                    $objectType = 'country';

                    $parts = explode('/', trim($url, ' /'));
                    $objectId = array_pop($parts);

                    $metadataUrl = $router->generate(
                        'ld_dev_default_index', // TODO This needs correcting when get routes are done
                        array(
                            'graph' => $graph,
                            'obj' => $objectType,
                            'parameter' => $objectId,
                            'format' => 'full',
                        ),
                        UrlGeneratorInterface::ABSOLUTE_PATH
                    );

                    $entity = new Theme(
                        'NPIS', $metadataUrl, $objectId, $objectName, $objectType
                    );
                    $entity->setCount($row->count->getValue());

                    $response[] = $entity;
                }
            }
        }

        return $response;
    }
}
