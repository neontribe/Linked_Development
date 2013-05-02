<?php

namespace LD\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * API Tests
 */
class CountControllerObjectTest extends BaseTestCase
{
    /**
     * Test json as query parameter
     */
    public function testCount()
    {
        $client = static::createClient();

        $client->request('GET', '/count/assets/A12345?format=json');
        $this->checkArray(json_decode($client->getResponse()->getContent(), true));
    }

    /**
     * Check the given array is a valid API response.
     *
     * @param array $data Decoded API response
     */
    protected function checkData(array $data)
    {
        $this->assertTrue(
            !array_key_exists('available_types', $data),
            'Response type was not accepted.'
        );

        $this->assertTrue(
            array_key_exists('metadata', $data),
            'Meta data not present.'
        );

        $this->assertTrue(
            array_key_exists('region_count', $data),
            'Results not.'
        );
    }
}
