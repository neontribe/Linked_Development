<?php

namespace LD\APIBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * API Tests
 */
abstract class BaseTestCase extends WebTestCase
{
    /**
     * Check that the (X)HTML document has valid data within it.
     *
     * @param \Symfony\Component\DomCrawler\Crawler $crawler Doc crawler
     */
    protected function checkHTML($crawler)
    {
        $apiresponse = $crawler->filterXpath('//*[@id="api-response"]');
        $this->assertGreaterThan(0, $apiresponse->count());
        $data = json_decode($apiresponse->text(), true);
        $this->checkArray($data);
    }

    /**
     * Check the given string is valid XML
     *
     * @param string $raw XML String
     *
     * @return \DOMDocument
     */
    protected function checkXML($raw)
    {
        $dom = new \DOMDocument();
        $dom->loadXML($raw);
        // This should get the children of the root node
        $children = $dom->firstChild->childNodes;
        $this->assertGreaterThan(0, $children->length);

        return $dom;
    }

    /**
     * Check the given array is a valid API response.
     *
     * @param array $data Decoded API response
     */
    protected function checkArray(array $data, $checkData = true)
    {
        $this->assertTrue(count($data) > 0, 'Response was not iterable data.');
        if ($checkData) {
            $this->checkData($data);
        }
    }

    /**
     * Check the given array is a valid API response.
     *
     * @param array $data Decoded API response
     */
    abstract protected function checkData(array $data, $param = null);
}
