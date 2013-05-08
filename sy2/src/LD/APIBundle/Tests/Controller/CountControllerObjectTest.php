<?php

namespace LD\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * API Tests
 */
class CountControllerObjectTest extends BaseTestCase
{
    private $checkOldApi = true;

    /**
     * Test json as query parameter
     */
    public function testCountDocumentRegion()
    {
        $client = static::createClient();

//        $client->request('GET', '/count/assets/A12345?format=json');
//        $this->checkArray(json_decode($client->getResponse()->getContent(), true));

        $kernel = static::createKernel();
        $kernel->boot();
        $apikeyfile = $kernel->getRootDir() . '/config/apikey';
        $apikey = file_get_contents($apikeyfile);

        if ($this->checkOldApi) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'http://api.ids.ac.uk/openapi/eldis/count/documents/region');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt(
                $curl,
                CURLOPT_HTTPHEADER,
                array(
                    'Accept: application/json',
                    'Token-Guid: ' . $apikey,
                )
            );

            $client->request('GET', '/count/documents/region?format=json');

            $response1 = json_decode(curl_exec($curl), true);
            $response2 = json_decode(
                $client->getResponse()->getContent(), true
            );

            // check that the top level keys exist
            foreach (array_keys($response1) as $key) {
                $this->assertTrue(array_key_exists($key, $response2));
            }

            // check that the array keys for the first results of the region_count match
            $rc1 = $response1['region_count'][0];
            $rc2 = $response2['region_count'][0];

            foreach (array_keys($rc1) as $key) {
                $this->assertTrue(array_key_exists($key, $rc2));
            }

            // check metadats holds matchng keys
            $md1 = $response1['metadata'];
            $md2 = $response2['metadata'];

            foreach (array_keys($md1) as $key) {
                $this->assertTrue(array_key_exists($key, $md2));
            }
        }
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
