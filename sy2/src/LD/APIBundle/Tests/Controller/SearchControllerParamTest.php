<?php

namespace LD\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * API Tests
 */
class SearchControllerParamTest extends SearchControllerObjectTest
{
    protected $searchStubNQ = '/search/documents/foo';
    protected $searchStub = '/search/documents/foo?country=Angola';
}
