<?php

namespace LD\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * API Tests
 */
class GetControllerObjectTest extends BaseTestCase
{
    protected $getStubs = array(
        '/get/assets/A12345',
        '/get/themes/C1598',
        '/get/countries/A1078',
    );

    protected $getAllStubs = array(
        '/get_all/documents',
        '/get_all/themes',
    );

    protected $getChildrenStubs = array(
        '/get_children/themes/C34',
    );

    /**
     * Test json as query parameter
     */
    public function testGet()
    {
        $client = static::createClient();

        foreach ($this->getStubs as $stub) {
            $client->request('GET', $stub . '?format=json');
            $this->checkArray(json_decode($client->getResponse()->getContent(), true));
        }
    }

    /**
     * Test json as query parameter
     */
    public function testGetAll()
    {
        $client = static::createClient();

        foreach ($this->getAllStubs as $stub) {
            $client->request('GET', $stub . '?format=json');
            $this->checkArray(json_decode($client->getResponse()->getContent(), true));
        }
    }

    /**
     * Test json as query parameter
     */
    public function testGetChildrenAll()
    {
        $client = static::createClient();

        foreach ($this->getChildrenStubs as $stub) {
            $client->request('GET', $stub . '?format=json');
            $this->checkArray(json_decode($client->getResponse()->getContent(), true));
        }
    }

    /**
     * Test json as query parameter
     */
    public function testFieldlist()
    {
        $client = static::createClient();

        $client->request('GET', '/fieldlist?format=json');
        $this->checkArray(
            json_decode($client->getResponse()->getContent(), true),
            false
        );
    }

    /**
     * Check the given array is a valid API response.
     *
     * @param array $data Decoded API response
     */
    protected function checkData(array $data, $param = null)
    {
        $this->assertTrue(
            array_key_exists('results', $data),
            'Results data not present.'
        );
        $this->assertGreaterThan(
            0, count($data['results']), 'Results data not present.'
        );
    }
}
