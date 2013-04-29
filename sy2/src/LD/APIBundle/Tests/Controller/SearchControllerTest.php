<?php

namespace LD\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * API Tests
 */
class SearchControllerTest extends WebTestCase
{
    /**
     * Test index
     */
    public function testMimeTypes()
    {
        $client = static::createClient();

        $stub = 'search/documents?country=Angola';

        // json
        $client->request('GET', $stub . '&format=json');
        $json = json_decode($client->getResponse()->getContent(), true);
        $this->assertTrue(count($json) > 0, 'Response was not JSON');
        $this->assertTrue(
            !array_key_exists('available_types', $json),
            'JSON type was not recognised.'
        );

        $client->request('GET', $stub, array(), array(), array('Content-type' => 'application/json'));
        $json = json_decode($client->getResponse()->getContent(), true);
        print_r($client->getResponse()->getContent());
        $this->assertTrue(count($json) > 0, 'Response was not JSON');
        $this->assertTrue(
            !array_key_exists('available_types', $json),
            'JSON type was not recognised.'
        );

        // html
        $crawler = $client->request('GET', $stub . '&format=html');

        // xhtml
        $crawler = $client->request('GET', $stub . '&format=xhtml');

        // xml
        $crawler = $client->request('GET', $stub . '&format=xml');

        // finally bad types
        // $this->assertTrue($crawler->filter('html:contains("Hello Fabien")')->count() > 0);
    }
}
