<?php

namespace LD\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * API Tests
 */
class CountControllerObjectTest extends BaseTestCase
{
    private $checkOldApi = true;
    private $apikey = null;

    private $activeUrl = false;

    public function setUp()
    {
        if (null == static::$kernel) {
            static::$kernel = static::createKernel();
            static::$kernel->boot();
        }
        $apikeyfile = static::$kernel->getRootDir() . '/config/apikey';
        $this->apikey = file_get_contents($apikeyfile);
    }

    /**
     * Test json as query parameter
     */
    public function testCount()
    {
        $client = static::createClient();

        $objects = array(
            'documents',
            'organisations',
            // 'item',
        );
        echo "\n** count items skipped **\n";
        $params = array(
            'theme',
            'country',
            'region',
            // 'keyword',
        );
        echo "** count keywords skipped **\n";

        foreach ($objects as $object) {
            foreach ($params as $param) {
                $this->activeUrl = '/count/' . $object . '/' . $param . '?format=json';
                $client->request('GET', $this->activeUrl);
                $response1 = json_decode(
                    $client->getResponse()->getContent(), true
                );
                $this->checkData($response1, $param);
                if ($this->checkOldApi) {
                    $response2 = $this->fetchData('http://api.ids.ac.uk/openapi/eldis/count/' . $object . '/' . $param);

                    $this->compareData($response1, $response2);
                    $this->compareData($response1['metadata'], $response2['metadata']);
                    // use the second array elelment, the first will be missing type specific additons, e.g. {"count":20260,"metadata_url":"","object_name":"","object_type":"","object_id":""}
                    $this->compareData($response1[$param . '_count'][1], $response2[$param . '_count'][1]);
                }
            }
        }
    }

    protected function fetchData($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                'Accept: application/json',
                'Token-Guid: ' . $this->apikey,
            )
        );

        return json_decode(curl_exec($curl), true);
    }

    /**
     * Take two arrays and make sure they have the same top level keys
     *
     * @param array $new First array
     * @param array $old Second array
     */
    protected function compareData(array $new, array $old)
    {
        foreach (array_keys($new) as $key) {
            $this->assertTrue(array_key_exists($key, $old), 'Array key ' . $key . ' not found, ' . json_encode($new) . "\n" . json_encode($old));
        }
    }

    /**
     * Check the given array is a valid API response.
     *
     * @param array $data Decoded API response
     */
    protected function checkData(array $data, $param = null)
    {
        $this->assertTrue(
            !array_key_exists('available_types', $data),
            'Response type was not accepted. [' . $this->activeUrl . ']'
        );

        $this->assertTrue(
            array_key_exists('metadata', $data),
            'Meta data not present. [' . $this->activeUrl . ']' . json_encode(array_keys($data))
        );

        $this->assertTrue(
            array_key_exists($param . '_count', $data),
            'Results not. [' . $this->activeUrl . ']'
        );
    }
}
