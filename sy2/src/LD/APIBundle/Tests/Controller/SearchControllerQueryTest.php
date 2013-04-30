<?php

namespace LD\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * API Tests
 */
class SearchControllerQueryTest extends SearchControllerObjectTest
{
    protected $searchStubNQ = '/search/documents/foo/bar';
    protected $searchStub = '/search/documents/foo/bar?country=Angola';
}
