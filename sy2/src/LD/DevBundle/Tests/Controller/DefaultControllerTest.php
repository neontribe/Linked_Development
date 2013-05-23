<?php

namespace LD\DevBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Dev Tests
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * Test index
     */
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');
    }
}
