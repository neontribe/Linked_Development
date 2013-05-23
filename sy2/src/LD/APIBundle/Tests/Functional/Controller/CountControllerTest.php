<?php

namespace LD\APIBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * API Tests
 */
class CountControllerTest extends BaseTestCase
{
    private $activeUrl = false;

    /**
     * Test json as query parameter
     */
    public function testCount()
    {
        $client = static::createClient();
        $graphs = array_keys($client->getContainer()->getParameter('graphs'));;

        $objects = array(
            'documents',
            'organisations',
            // 'item',
        );
        echo "\n** count items skipped **\n";
        $params = array(
            'theme',
            'country',
            'region',
            // 'keyword',
        );
        echo "** count keywords skipped **\n";

        foreach ($graphs as $graph) {
            if ($graph) {
                foreach ($objects as $object) {
                    foreach ($params as $param) {
                        $this->activeUrl = sprintf(
                            '/%s/count/%s/%s?format=json',
                            $graph, $object, $param
                        );
                        $client->getContainer()->get('logger')->debug('Fetching: ' . $this->activeUrl);
                        $client->request('GET', $this->activeUrl);
                        $response1 = json_decode(
                            $client->getResponse()->getContent(), true
                        );
                        $this->checkData($response1, $param);
                    }
                }
            }
        }
    }

    /**
     * Check the given array is a valid API response.
     *
     * @param array $data Decoded API response
     */
    protected function checkData(array $data, $param = null)
    {
        $this->assertTrue(
            !array_key_exists('available_types', $data),
            'Response type was not accepted. [' . $this->activeUrl . ']'
        );

        $this->assertTrue(
            array_key_exists('metadata', $data),
            'Meta data not present. [' . $this->activeUrl . ']' . json_encode(array_keys($data))
        );

        $this->assertTrue(
            array_key_exists($param . '_count', $data),
            'Results not. [' . $this->activeUrl . ']'
        );
    }
}
