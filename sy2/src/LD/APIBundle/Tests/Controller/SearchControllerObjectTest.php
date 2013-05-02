<?php

namespace LD\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * API Tests
 */
class SearchControllerObjectTest extends BaseTestCase
{
    protected $searchStubNQ = '/search/documents';
    protected $searchStub = '/search/documents?country=Angola';
    protected $searchResponse = '/search/documents/{type}?country=Angola';

    /**
     * Test json as query parameter
     */
    public function testShortResponse()
    {
        $client = static::createClient();

        $client->request('GET', str_replace('{type}', 'short', $this->searchResponse) . '&format=json');
        $this->checkArray(json_decode($client->getResponse()->getContent(), true));

        $client->request('GET', str_replace('{type}', 'full', $this->searchResponse) . '&format=json');
        $this->checkArray(json_decode($client->getResponse()->getContent(), true));

        $client->request('GET', str_replace('{type}', 'id', $this->searchResponse) . '&format=json');
        $this->checkArray(json_decode($client->getResponse()->getContent(), true));
    }

    /**
     * Test the not including a query string fails
     */
    public function testNoQuery()
    {
        $client = static::createClient();
        $client->request('GET', $this->searchStubNQ);
        $this->assertTrue($client->getResponse()->getStatusCode() == 400);
    }

    /**
     * Test json as query parameter
     */
    public function testJSONMimeType()
    {
        $client = static::createClient();

        $client->request('GET', $this->searchStub . '&format=json');
        $this->checkArray(json_decode($client->getResponse()->getContent(), true));

        $client->request('GET', $this->searchStub, array(), array(), array('HTTP_Accept' => 'application/json'));
        $this->checkArray(json_decode($client->getResponse()->getContent(), true));
    }

    /**
     * Test html
     */
    public function testHTMLQueryMimeType()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', $this->searchStub . '&format=html');
        $this->checkHTML($crawler);

        $crawler = $client->request('GET', $this->searchStub, array(), array(), array('HTTP_Accept' => 'text/html'));
        $this->checkHTML($crawler);
    }

    /**
     * Test xhtml
     */
    public function testXHTMLMimeType()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $this->searchStub . '&format=html');
        $this->checkHTML($crawler);

        $crawler = $client->request('GET', $this->searchStub, array(), array(), array('HTTP_Accept' => 'application/xhtml+xml'));
        $this->checkHTML($crawler);
    }

    /**
     * Test xml
     */
    public function testXMLMimeType()
    {
        $client = static::createClient();
        $client->request('GET', $this->searchStub . '&format=xml');
        $this->checkXML($client->getResponse()->getContent());

        $client->request('GET', $this->searchStub, array(), array(), array('HTTP_Accept' => 'application/xml'));
        $this->checkXML($client->getResponse()->getContent());
    }

    /**
     * Test bad/unsupported mime type
     */
    public function testBadMimeType()
    {
        $client = static::createClient();

        $client->request('GET', $this->searchStub . '&format=foo');
        $this->assertTrue(count(json_decode($client->getResponse()->getContent(), true)) > 0, 'Response was not iterable data.');
        $this->assertTrue(array_key_exists('available_types', json_decode($client->getResponse()->getContent(), true)));
        $this->assertTrue(array_key_exists('detail', json_decode($client->getResponse()->getContent(), true)));

        $client->request('GET', $this->searchStub, array(), array(), array('HTTP_Accept' => 'application/foo'));
        $this->assertTrue(count(json_decode($client->getResponse()->getContent(), true)) > 0, 'Response was not iterable data.');
        $this->assertTrue(array_key_exists('available_types', json_decode($client->getResponse()->getContent(), true)));
        $this->assertTrue(array_key_exists('detail', json_decode($client->getResponse()->getContent(), true)));
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
            array_key_exists('results', $data),
            'Results not.'
        );
    }
}
