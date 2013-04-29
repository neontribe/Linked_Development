<?php

namespace LD\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * API Tests
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * Test index
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/search');

        // $this->assertTrue($crawler->filter('html:contains("Hello Fabien")')->count() > 0);
    }
}
